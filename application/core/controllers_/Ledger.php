<?php  
error_reporting(1);
defined('BASEPATH') OR exit('No direct script access allowed');

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

}