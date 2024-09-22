<?php  
error_reporting(1);
defined('BASEPATH') OR exit('No direct script access allowed');
class Reports extends Admin_Controller 
{	
	public function __construct()
	{
		parent::__construct();
        $this->load->library('session');
        $this->session->set_userdata('report','1');
		$this->data['page_title'] = 'Reports';
		$this->load->model('model_reports');
		$this->load->model('model_company');
		$this->data['date_format']=$this->model_company->get_currency_date_format();
		$this->date_format=$this->model_company->get_currency_date_format();
	}

	/* 
    * It redirects to the report page
    * and based on the year, all the orders data are fetch from the database.
    */
	public function index()
	{
		if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
		
		$today_year = date('Y');

		if($this->input->post('select_year')) {
			$today_year = $this->input->post('select_year');
		}

		$parking_data = $this->model_reports->getOrderData($today_year);
		$this->data['report_years'] = $this->model_reports->getOrderYear();
		

		$final_parking_data = array();
		foreach ($parking_data as $k => $v) {
			
			if(count($v) > 1) {
				$total_amount_earned = array();
				foreach ($v as $k2 => $v2) {
					if($v2) {
						$total_amount_earned[] = $v2['net_amount'];						
					}
				}
				$final_parking_data[$k] = array_sum($total_amount_earned);	
			}
			else {
				$final_parking_data[$k] = 0;	
			}
		}
		$this->data['selected_year'] = $today_year;
		$this->data['company_currency'] = $this->company_currency();
		$this->data['results'] = $final_parking_data;
		$this->render_template('reports/index', $this->data);
	}


	public function inventoryreport(){
  	$this->data['page_title'] = 'Inventory Report';
    $record=$this->model_reports->getInventoryreport();    
    $arrayData=array();
    $arr_val=array();
    foreach ($record as $key => $value){
		$arrayData[]=$value;
		if($value['attribute_value_id']==''){

		}else{
	    $ar=json_decode($value['attribute_value_id']);

        for($i=0;$i<count($ar);$i++){
        if(!empty($ar[$i])){
          
         $arRp=$this->model_reports->getInventryAttr($ar[$i]);
         }
	     $arr_val[$i]=$arRp[0]['value'];
        //  $arr_val[$i]=$ar[$i];
        }
		}
        $arrayData[$key]["attr"]=$arr_val;
    }
    $this->data['record']=$arrayData;
    $this->render_template('reports/inventory', $this->data);
    }


   public function exportExcelInventory(){
    $this->data['page_title'] = 'Inventory Report';
    $record=$this->model_reports->getInventoryreport();    
    $arrayData=array();
    $arr_val=array();
    foreach ($record as $key => $value){
        $arrayData[]=$value;
        if($value['attribute_value_id']==''){

        }else{
        $ar=json_decode($value['attribute_value_id']);

        for($i=0;$i<count($ar);$i++){
        if(!empty($ar[$i])){
          
         $arRp=$this->model_reports->getInventryAttr($ar[$i]);
         }
         $arr_val[$i]=$arRp[0]['value'];
        //  $arr_val[$i]=$ar[$i];
        }
        }
        $arrayData[$key]["attr"]=$arr_val;
    }
    

    //$this->load->library('excel');
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    // set Header
    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'S.No');
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Code');
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Description');
    $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Color');
    $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Qty');
    $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'CIF');
    $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Cost Price');
    $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Total Value');
    $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
    $rows=2;
    $totalAmt=array();
    $ar_count=array();
      foreach ($arrayData as $key=>$val){
            $ar_count[]=count($arrayData);
            $totalAmt[]=$value['price'] * $value['qty'];

            if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
            $cif=$val['cif'].' '.$this->date_format['currency'];
            $price= $val['price'].' '.$this->date_format['currency'];
            $totalprice= $totalAmt.' '.$this->date_format['currency'];
             
            }else{
            $cif=$this->date_format['currency'].' '.$val['cif'];
            $price= $this->date_format['currency'].' '.$val['price'];
            $totalprice= $this->date_format['currency'].' '.$totalAmt;
            }
            $attr=str_replace(',',' ',implode(',',$val['attr']));
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rows, $key+1);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rows, $val['id']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rows, $val['description']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rows, $attr);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rows, $val['Qty']);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rows, $cif);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rows, $price);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rows, $totalprice);

            $rows++;
           }
           $ex_row=count($ar_count)+1;

            $objPHPExcel->setCellValue('A' . $ex_row,);
            $objPHPExcel->setCellValue('B' . $ex_row,);
            $objPHPExcel->setCellValue('C' . $ex_row,);
            $objPHPExcel->setCellValue('D' . $ex_row,);
            $objPHPExcel->setCellValue('E' . $ex_row,);
            $objPHPExcel->setCellValue('F' . $ex_row,);
            $objPHPExcel->setCellValue('G' . $ex_row,'Total Amount');
            $objPHPExcel->setCellValue('H' . $ex_row, $totalAmt);

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="inventory-report.xls"');
    $objWriter->save('php://output');
    }

    public function salesreport(){
    //echo"<pre>";
    $this->data['page_title'] = 'Sales Report';
    $this->data['customerData']=$this->model_reports->getCustomerData();  
     //print_r($this->data['result']);
    $this->render_template('reports/report_sales', $this->data);
    }
   
    public function getSalesReportList(){

    if($this->input->post('cust_name') AND $this->input->post('from_date') AND $this->input->post('to_date')){
    $custName=$this->input->post('cust_name');
    $from_date=date('Y-m-d h:i:s',strtotime($this->input->post('from_date')));
    $to_date=date('Y-m-d h:i:s',strtotime($this->input->post('to_date')));
    $this->data['result']=$this->model_reports->getsalesreport($custName,$from_date,$to_date);
    }else{
    $this->data['result']=$this->model_reports->getsalesreport();
    }
    $this->load->view('reports/tmp_sales_report', $this->data);
    }

	public function exportexcelsalesreport(){
     $result=$this->model_reports->getsalesreport(); 
     // print_r($result);
     // die;
	 $this->load->library('excel');
	 // echo"1";
     // //require_once APPPATH.'/libraries/Excel.php';

     //    require_once APPPATH . "/third_party/PHPExcel.php";
     //    require_once APPPATH . '/third_party/PHPExcel/Writer/Excel2007.php';
     //    require_once APPPATH . '/third_party/PHPExcel/IOFactory.php';

    // echo $a;	
    $objPHPExcel = new PHPExcel();
    // print_r($objPHPExcel);
	//$objPHPExcel->setActiveSheetIndex(0);
	// set Header
    //     echo"Hello";
    // die;
	$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'S.No');
	$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Customer Code');
	$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Customer Details');
	$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Model');
	$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Description');

	$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Qty');
	$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'UOM');
	$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'WSP');
	$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Invoice No');
	$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Date');

	$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Value');
	$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'VAT');
	$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'PAYMENTS');
	$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);


    $rows=2;

	  foreach ($result as $key01=>$val){
	$total_val=array();
	$total_vat=array();
	  foreach ($val as $key02 => $EXvalue) {
	$total_val[]=$EXvalue['amount'];
    $total_vat[]=$EXvalue['vat_charge'];
    if($EXvalue['paid_status']==2){
    $payStatus="NOT";
    }else{
    $payStatus="RECEIVED";
    }

   if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
   $ex_amt=$EXvalue['amount'].' '.$this->date_format['currency'];
   }else{
   $ex_amt=$this->date_format['currency'].' '.$EXvalue['amount'];
   }

    if($key02>0){
    }else{
    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rows,$key01+1);
    }
    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rows, $EXvalue['code']);
    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rows, $EXvalue['cust_details']);
    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rows, $EXvalue['model_no']);
    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rows, $EXvalue['pro_des']);

    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rows, $EXvalue['qty']);
    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rows, $EXvalue['pro_unit']);
    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rows, $EXvalue['rate']);
    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rows, $EXvalue['bill_no']);
    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rows, date($this->date_format['date_format'],strtotime($EXvalue['date_time'])));

    $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rows, $ex_amt);
    $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rows, $EXvalue['vat_charge']);
    $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rows, $payStatus);
    $rows++;
            }
          
  if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
   $val_amt=array_sum($total_val).' '.$this->date_format['currency'];
   $vat_amt=array_sum($total_vat).' '.$this->date_format['currency'];
   }else{
   $val_amt=$this->date_format['currency'].' '.array_sum($total_val);
   $vat_amt=$this->date_format['currency'].' '.array_sum($total_vat);
   }

    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rows,);
    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rows,);
    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rows,);
    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rows,);
    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rows,);

    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rows,);
    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rows,);
    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rows,);
    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rows,);
    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rows,'Total Amount');

    $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rows,round($val_amt,2));
    $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rows,round($vat_amt,2));
    $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rows,);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$rows.':M'.$rows.'')->getFont()->setBold(true);
    $rows++;
            }

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="sales-report.xls"');
	$objWriter->save('php://output');
	}



	public function duereport(){

	$this->data['page_title'] = 'Due Report';	
	$dueOrder=$this->model_reports->getAllDueOrder();
    $arr=array();
    foreach ($dueOrder as $key => $value){
    $due_date=date(MY_DATE_FORMAT,strtotime($value['date_time']. ' +'.$value['due_date'].'days'));
    $arr[]=array(
    'bill_no'=>$value['bill_no'],
    'customer_name'=>$value['customer_name'],
    'order_date'=>date(MY_DATE_FORMAT,strtotime($value['date_time'])),	
    'due_date'=>$due_date,
    'due_amount'=>$value['net_amount'],
    'due_days'=>$value['due_date'],
    );
    }
    $this->data['record']=$arr;
    $this->render_template('reports/due', $this->data);
	}
	
}	