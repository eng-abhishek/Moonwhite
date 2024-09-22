<?php  
error_reporting(1);
defined('BASEPATH') OR exit('No direct script access allowed');
require FCPATH.'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
class Ledger extends Admin_Controller 
{	
	public function __construct()
	{
		parent::__construct();
		     $this->load->library('session');
        $this->session->set_userdata('report','1');
		$this->data['page_title'] = 'Ledger Report';
		$this->load->model('model_ledger');
	    $this->load->model('model_expenses');
		$this->load->model('model_orders');
		$this->load->library('session');
		$this->load->model('model_company');
		$this->date_format=$this->model_company->get_currency_date_format();
		$this->data['company_data'] = $this->model_company->getCompanyData(1);
	}

	/* 
    * It redirects to the report page
    * and based on the year, all the orders data are fetch from the database.
    */
	public function index()
	{ 
		
    if(!in_array('viewExpenses', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->render_template('ledger/index',$this->data);
	}

	public function getLegerData(){
    $data['data']=$this->model_ledger->getLegerData();
    $actual_balence=0; 

    foreach ($data['data'] as $key => $value) {
 
    $debit_atc=$value['debit'] ? str_replace(',','',$value['debit']) : " ";
    
    $actual_balence=$actual_balence+(float)$value['credit']-(float)$debit_atc;

    $value['id']=$key+1;

if($value['credit']==NULL){
$credit=$value['credit'];
}else{

if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
$credit=$value['credit'].' '.$this->date_format['currency'];
}else{
$credit=$this->date_format['currency'].' '.$value['credit'];
}
}

if($value['debit']==NULL){
$debit=$value['debit'];	
}else{
// $debit=$value['debit']." ".MY_CURRENCY;
if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
$debit=$value['debit'].' '.$this->date_format['currency'];
}else{
$debit=$this->date_format['currency'].' '.$value['debit'];
}
}

if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
$actual_bal=$actual_balence.' '.$this->date_format['currency'];
}else{
$actual_bal=$this->date_format['currency'].' '.$actual_balence;
}

    $result['data'][$key]=array(
	'id'=>$value['id'],  
	'particulars'=>$value['particulars'],
	'credit'=>$credit, 
	'debit'=>$debit ? str_replace(',','',$debit) : " ",
	'date'=>date($this->date_format['date_format'],strtotime($value['date'])),
	'actual_balence'=>$actual_bal,

    ); 
    }
    if(!empty($actual_bal)){
    $actual_bal=$actual_bal;
    }else{
    $actual_bal='0';
    }
  
    $this->session->set_userdata('actual_balence',$actual_balence);
    if($result){
     $result['total']=array('total'=>$actual_bal);
    }else{
    $result['data']=array();
    $result['total']=array('total'=>$actual_bal);
    }
    echo json_encode($result);
	}

	function gettotalbalence(){
    echo $this->session->userdata('actual_balence')." ".MY_CURRENCY;
	}

   public function exportLedgerData(){
   $data['data']=$this->model_ledger->getLegerData();
    $actual_balence=0; 

    foreach ($data['data'] as $key => $value) {
 
    $debit_atc=$value['debit'] ? str_replace(',','',$value['debit']) : " ";
    
    $actual_balence=$actual_balence+(float)$value['credit']-(float)$debit_atc;

    $value['id']=$key+1;

if($value['credit']==NULL){
$credit=$value['credit'];
}else{
$credit=$value['credit'];
// if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
// .' '.$this->date_format['currency'];
// }else{
// $credit=$this->date_format['currency'].' '.$value['credit'];
// }
}

if($value['debit']==NULL){
$debit=$value['debit'];	
}else{
// $debit=$value['debit']." ".MY_CURRENCY;
$debit=$value['debit'];
// if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
// .' '.$this->date_format['currency'];
// }else{
// $debit=$this->date_format['currency'].' '.$value['debit'];
// }
}

// if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
// $actual_bal=$actual_balence.' '.$this->date_format['currency'];
// }else{
// $actual_bal=$this->date_format['currency'].' '.$actual_balence;
// }
$actual_bal=$actual_balence;

    $result['data'][$key]=array(
	'id'=>$value['id'],  
	'particulars'=>$value['particulars'],
	'credit'=>$credit, 
	'debit'=>$debit ? str_replace(',','',$debit) : " ",
	'date'=>date($this->date_format['date_format'],strtotime($value['date'])),
	'actual_balence'=>$actual_bal,
    ); 
    }
    

        $tmpfileName = 'ledger-report';  
        $tmpspreadsheet = new Spreadsheet();
        $tmpspreadsheet->setActiveSheetIndex(0);
        $tmpspreadsheet->getActiveSheet()->getStyle("A1:F1")->getFont()->setBold(true);
        $tmpsheet = $tmpspreadsheet->getActiveSheet()->setTitle('ledger-report');
        $tmpsheet->setCellValue('A1', 'S.No');
        $tmpsheet->setCellValue('B1', 'Particulars');
        $tmpsheet->setCellValue('C1', 'Date');
        $tmpsheet->setCellValue('D1', 'Debit');   
        $tmpsheet->setCellValue('E1', 'Credit');
        $tmpsheet->setCellValue('F1', 'Balance');   
        
          $rows_student = 2; 
          //$qty=array();
          foreach ($result['data'] as $key=>$record_value) {
    // echo"<pre>";
    // print_r($record_value);
    //  die;

        //  $qty[]=$record_value['qty'];
          $tmpsheet->setCellValue('A' . $rows_student, $key+1);
          $tmpsheet->setCellValue('B' . $rows_student, $record_value['particulars']);
          $tmpsheet->setCellValue('C' . $rows_student, $record_value['date']);          
          $tmpsheet->setCellValue('D' . $rows_student, $record_value['debit']);
          $tmpsheet->setCellValue('E' . $rows_student, $record_value['credit']);
          $tmpsheet->setCellValue('F' . $rows_student, $record_value['actual_balence']);
          $rows_student++;   
          }
          $extra_row=$rows_student;
        
          $tmpsheet->setCellValue('A' . $extra_row,'');
          $tmpsheet->setCellValue('B' . $extra_row,'');
          $tmpsheet->setCellValue('C' . $extra_row,'');
          $tmpsheet->setCellValue('D' . $extra_row, '');
          $tmpsheet->setCellValue('E' . $extra_row, 'Total');
          $tmpsheet->setCellValue('F' . $extra_row, $record_value['actual_balence']);

        $tmpwriter = new Xls($tmpspreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $tmpfileName .'.xls"'); 
        header('Cache-Control: max-age=0');
        $tmpwriter->save('php://output'); // download file



   }

}