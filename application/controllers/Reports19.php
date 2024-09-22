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
	    $this->data['company_data'] = $this->model_company->getCompanyData(1);
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
        $arrayData[$key]["attr"]=$arr_val[1];
        }
     $this->data['record']=$arrayData;
     // echo"<pre>";
     // print_r($arrayData);  
     // die;
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

    public function invoice_report(){
    //echo"<pre>";
    $this->data['page_title'] = 'Sales Report';
    $this->data['customerData']=$this->model_reports->getCustomerData();  
     //print_r($this->data['result']);
    $this->render_template('reports/invoice_report', $this->data);
    }

    public function getInvoiceSalesReportList(){
    $custName=$this->input->post('custID');
    $this->data['result']=$this->model_reports->getInvoicereport($custName,$_POST['yearID'],$_POST['monthID']);
   $this->load->view('reports/tmp_sales_invoice_report', $this->data);
    }


    public function download_invoice_report(){
    $time = time();
    $fileName = 'invoice-'.$time.'.pdf';
    $custName=$this->input->post('custID');
    $result=$this->model_reports->getInvoicereport01($custName,$_POST['yearID'],$_POST['monthID']);
    $this->data['result']=$result;
    //$this->load->view('reports/report_pdf_file',$this->data);
    $this->pdf->download_invoice('reports/report_pdf_file',$this->data,$fileName);
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
    'customer_code'=>$value['code'],
    'customer_name'=>$value['name'],
    'order_date'=>date(MY_DATE_FORMAT,strtotime($value['date_time'])),	
    'due_date'=>$due_date,
    'due_amount'=>$value['net_amount'],
    'due_days'=>$value['due_date'],
    );
    }
    $this->data['record']=$arr;
    $this->render_template('reports/due', $this->data);
	}
	
    public function account(){
    $this->render_template('reports/account',$this->data);
    }

    public function getAccountDataList(){

    $assetsData=$this->model_reports->getAssetsDataList($_POST['monthID'],$_POST['yearID']);
       
    $curr_assetsData=$this->model_reports->getCurrentAssetsDataList($_POST['monthID'],$_POST['yearID']);
    
    $expencesData=$this->model_reports->getExpencesDataList($_POST['monthID'],$_POST['yearID']);
     // echo"<pre>";
     // print_r($assetsData);
     // die;
    $CurrentAssetStatisData=$this->model_reports->getCurrentAssetStaticData();

    $getInvestorPrimary=$this->model_reports->getInvestorPrimary($_POST['monthID'],$_POST['yearID']);

    $CurrentInvestor01=$this->model_reports->getInvestorSecon01($_POST['monthID'],$_POST['yearID']);

    $CurrentInvestor02=$this->model_reports->getInvestorSecon02($_POST['monthID'],$_POST['yearID']);
 
    $html='';
    if(!empty($assetsData)){
    $html.='<div style="font-size: 17px;font-weight: 800;padding-bottom:5px;">Asset Report</div>';
    $html.='<table id="manageTable0" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Code</th>
                <th>Asset</th>
                <th>Amount</th>
              </tr>
              </thead>
              <tbody>';
            foreach($assetsData as $key0 => $value0){
            if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
            $value_Amt=$value0['value'].' '.$this->date_format['currency'];
            
            }else{
           $value_Amt=$this->date_format['currency'].' '.$value0['value'];
            }
            
            $html.='<tr><td>'.$value0['code'].'</td><td>'.$value0['name'].'</td><td>'.$value_Amt.'</td></tr>';
             }
    $html.='</tbody></table>';
    }

    $html.='<div style="font-size: 17px;font-weight: 800;padding-bottom:5px;">Current Asset Report</div>';
    $html.='<table id="manageTable1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Code</th>
                <th>Asset</th>
                <th>Amount</th>
              </tr>
              </thead>
              <tbody>';
            if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
            $pendingAmt=round($CurrentAssetStatisData['pending_amount']['amt'][0],2).' '.$this->date_format['currency'];
            $comersialAmt=round($CurrentAssetStatisData['paid_comersial_amt']['amt'][0],2).' '.$this->date_format['currency'];
            $proAvbAmt=round($CurrentAssetStatisData['avaliable_pro_amt']['amt'][0],2).' '.$this->date_format['currency'];

            }else{
            $pendingAmt=$this->date_format['currency'].' '.round($CurrentAssetStatisData['pending_amount']['amt'][0],2);
            $comersialAmt=$this->date_format['currency'].' '.round($CurrentAssetStatisData['paid_comersial_amt']['amt'][0],2);
            $proAvbAmt=$this->date_format['currency'].' '.round($CurrentAssetStatisData['avaliable_pro_amt']['amt'][0],2);
            }       
    
    if($getInvestorPrimary['amt'][0]>0){
    $html.='<tr><td></td><td>'.$getInvestorPrimary['name'][0].'</td><td>'.$getInvestorPrimary['amt'][0].'</td></tr>';
    }
    
    if($CurrentInvestor01['amt'][0]>0){
    $html.='<tr><td></td><td>'.$CurrentInvestor01['name'][0].'</td><td>'.$CurrentInvestor01['amt'][0].'</td></tr>';
    }
   
    if($CurrentInvestor02['amt'][0]>0){ 
     $html.='<tr><td></td><td>'.$CurrentInvestor02['name'][0].'</td><td>'.$CurrentInvestor02['amt'][0].'</td></tr>';
    }
            foreach($curr_assetsData as $key1 => $value1){
            
            if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
            $value_curAsset=$value1['value'].' '.$this->date_format['currency'];
            }else{
            $value_curAsset=$this->date_format['currency'].' '.$value1['value'];
            }
            $html.='<tr><td>'.$value1['code'].'</td><td>'.$value1['name'].'</td><td>'.$value_curAsset.'</td></tr>';                
             }

    $html.='<tr><td></td><td>'.$CurrentAssetStatisData['pending_amount']['name'][0].'</td><td>'.$pendingAmt.'</td></tr>';

    $html.='<tr><td></td><td>'.$CurrentAssetStatisData['paid_comersial_amt']['name'][0].'</td><td>'.$comersialAmt.'</td></tr>';
  
    $html.='<tr><td></td><td>'.$CurrentAssetStatisData['avaliable_pro_amt']['name'][0].'</td><td>'.$proAvbAmt.'</td></tr>';


    $html.='</tbody></table>';
    

    if(!empty($expencesData)){
              $html.='<div style="font-size: 17px;font-weight: 800;padding-bottom:5px;">Expences Report</div>';
              $html.='<table id="manageTable2" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Code</th>
                <th>Asset</th>
                <th>Amount</th>
              </tr>
              </thead>
              <tbody>';
            foreach($expencesData as $key2 => $value2){

            if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
            $value_exp=$value2['amount'].' '.$this->date_format['currency'];
            }else{
            $value_exp=$this->date_format['currency'].' '.$value2['amount'];
            }

            $html.='<tr><td>'.$value2['code'].'</td><td>'.$value2['name'].'</td><td>'.$value_exp.'</td></tr>';
             }
           $html.='</tbody></table>';  
        }
        echo $html;
    // echo"AS";
    // print_r($assetsData);
    // echo"Curr AS";
    // print_r($curr_assetsData);
    // echo"Expen-";
    // print_r($expencesData);
    }

    public function printDiv($id){
    echo $id;
    }

    public function balance_sheet_report(){
    $this->render_template('reports/balance_sheet',$this->data);
    }

    public function getBalanceSheetDataList(){
    //$this->db->getBalanceSheetData($_POST['monthID'],$_POST['yearID']);
    // print_r($_POST);
    
    // echo"<pre>";
   
    $capitalData=$this->model_reports->capitalData();
    $assetCapitalData=$this->model_reports->assetCapitalData($_POST['monthID'],$_POST['yearID']);
    $asset=$this->model_reports->getAsset($_POST['monthID'],$_POST['yearID']);   
    // print_r($asset);
    // die;

    $currentAsset=$this->model_reports->getCurrentAsset($_POST['monthID'],$_POST['yearID']);

    $give_to_supplyer=$this->model_reports->give_to_supplyer($_POST['monthID'],$_POST['yearID']);
    $current_stock=$this->model_reports->currentStockData();

    $allexp=$this->model_reports->allExpences($_POST['monthID'],$_POST['yearID']);
    $primaryInvestor=$this->model_reports->getInvestorPrimary($_POST['monthID'],$_POST['yearID']);
    $secondryInvestor01=$this->model_reports->getInvestorSecon01($_POST['monthID'],$_POST['yearID']);
    $secondryInvestor02=$this->model_reports->getInvestorSecon02($_POST['monthID'],$_POST['yearID']);
    $all_focAmt=$this->model_reports->getFOC_Amount($_POST['monthID'],$_POST['yearID']);
    $OutstandingAmt=$this->model_reports->getOutstandingAmt($_POST['monthID'],$_POST['yearID']);
    
    // print_r($OutstandingAmt['OutstandingAmt']);
    // die; 
    //comersial_invoice
    // print_r($a);
    // print_r($b);
    
   if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
   $capitalAmt=$capitalData['capital'].' '.$this->date_format['currency'];
   }else{
   $capitalAmt=$this->date_format['currency'].' '.$capitalData['capital'];
   }

    $html='';
    $html.='<table class="table table-bordered table-striped">';
    $html.='<tr><td>Capital</td><td>'.$capitalAmt.'</td></tr>';
    $assetCapitalDataArr=array();
    
   foreach($assetCapitalData as $key01 => $assetCapitalData_value){

   if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
   $assetcapitalVal=$assetCapitalData_value['value'].' '.$this->date_format['currency'];
   }else{
   $assetcapitalVal=$this->date_format['currency'].' '.$assetCapitalData_value['value'];
   }

    $assetCapitalDataArr[]=$assetCapitalData_value['value'];
    $html.='<tr><td>'.$assetCapitalData_value['title'].'</td><td>'.$assetcapitalVal.'</td></tr>';
    }

    $assetcDAr=array_sum($assetCapitalDataArr);
    $assetCapitaldata=$assetcDAr+$capitalData['capital'];

   if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
   $assetCapitaldataVal=$assetCapitaldata.' '.$this->date_format['currency'];
   }else{
   $assetCapitaldataVal=$this->date_format['currency'].' '.$assetCapitaldata;
   }

    $html.='<tr><td>Total</td><td>'.$assetCapitaldataVal.'</td></tr>';
    
    $assetDataArr=array();

   foreach ($asset as $key02 => $assetData_value){
   if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
   $assetDataval=$assetData_value['value'].' '.$this->date_format['currency'];
   }else{
   $assetDataval=$this->date_format['currency'].' '.$assetData_value['value'];
   }

    $assetDataArr[]=$assetData_value['value'];
    $html.='<tr><td>'.$assetData_value['title'].'</td><td>'.$assetDataval.'</td></tr>';
   }

    $netAssetCapital=$assetCapitaldata-array_sum($assetDataArr);

   if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
   $netAssetCapitalDataVal=$netAssetCapital.' '.$this->date_format['currency'];
   }else{
   $netAssetCapitalDataVal=$this->date_format['currency'].' '.$netAssetCapital;
   }

    $html.='<tr><td>Net</td><td>'.$netAssetCapitalDataVal.'</td></tr>';

    $html.='</table>';

    $html.='<table class="table table-bordered table-striped">';
    $html.='<tr><th>Remarks</th> <th>Debit</th> <th>Credit</th><th></th> </tr>';
    
    $html.='<tr><td>Outstanding paymenty Customer</td> <td>'.round($OutstandingAmt['OutstandingAmt'],2).'</td> <td></td><td></td> </tr>';
    $crassetdata=array();
    foreach ($currentAsset as $key03 => $currentAssetDataVal){

   if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
   $currentAssetDV=$currentAssetDataVal['value'].' '.$this->date_format['currency'];
   }else{
   $currentAssetDV=$this->date_format['currency'].' '.$currentAssetDataVal['value'];
   }

    $crassetdata[]=$currentAssetDataVal['value'];
    $html.='<tr><td>'.$currentAssetDataVal['title'].'</td> <td></td> <td>'.$currentAssetDV.'</td> <td></td></tr>';
    }

    $supplyerArr=array();
    foreach ($give_to_supplyer as $key04 => $givetoSupplyerDataval){
   if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
   $currentAssetGTS=$givetoSupplyerDataval['value'].' '.$this->date_format['currency'];
   }else{
   $currentAssetGTS=$this->date_format['currency'].' '.$givetoSupplyerDataval['value'];
   }

    $supplyerArr[]=$givetoSupplyerDataval['giveToSuppAmt'];
    $html.='<tr><td>'.$givetoSupplyerDataval['name'].'</td> <td>'.$currentAssetGTS.'</td><td></td> <td></td></tr>';
    }
    
   if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){

   $currentStock=round($current_stock['currentStock'],2).' '.$this->date_format['currency'];

   $allexp=round($allexp['exp_amt'],2).' '.$this->date_format['currency'];
   $primaryInvestorVal=round($primaryInvestor['amt'][0],2).' '.$this->date_format['currency'];
   $secondryInvestor01Val=round($secondryInvestor01['amt'][0],2).' '.$this->date_format['currency'];
   $secondryInvestor02Val=round($secondryInvestor02['amt'][0],2).' '.$this->date_format['currency'];
   $all_focAmt=round($all_focAmt['foc_amt'],2).' '.$this->date_format['currency'];
      
   }else{
   
   $currentStock=$this->date_format['currency'].' '.round($current_stock['currentStock'],2);
    
   $allexp=$this->date_format['currency'].' '.round($allexp['exp_amt'],2);
   $primaryInvestorVal=$this->date_format['currency'].' '.round($primaryInvestor['amt'][0],2);
   $secondryInvestor0Val=$this->date_format['currency'].' '.round($secondryInvestor01['amt'][0],2);
   $secondryInvestor02Val=$this->date_format['currency'].' '.round($secondryInvestor02['amt'][0],2);
   $all_focAmt=$this->date_format['currency'].' '.round($all_focAmt['foc_amt'],2);         

   }
    
    $html.='<tr><td>Current Stock</td> <td></td><td>'.$currentStock.'</td><td></td> </tr>';
    $html.='<tr><td>Expenses,salary,fuel,phone Stock</td> <td>'.$allexp.'</td><td></td><td></td> </tr>';

     $html.='<tr><td>'.$primaryInvestor['name'][0].'</td> <td>'.$primaryInvestorVal.'</td><td></td><td></td> </tr>';

     $html.='<tr><td>'.$secondryInvestor01['name'][0].'</td> <td>'.$secondryInvestor01Val.'</td><td></td><td></td> </tr>';

     $html.='<tr><td>'.$secondryInvestor02['name'][0].'</td> <td>'.$secondryInvestor02Val.'</td><td></td><td></td> </tr>';

     $html.='<tr><td>FOC Stock</td> <td>'.$all_focAmt.'</td><td></td><td></td> </tr>';

    $totalDebit=0;

    $totalcredit=$current_stock['currentStock'] + array_sum($crassetdata);

    $balanceAmt=$totalcredit+$totalDebit;

   if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
   $balanceAmtdata=round($balanceAmt,2).' '.$this->date_format['currency'];
   $totalDebitDataVal=round($totalDebit,2).' '.$this->date_format['currency'];
   $totalcreditDataVal=round($totalcredit,2).' '.$this->date_format['currency'];
   $assetDataArrDataVal=round(array_sum($assetDataArr),2).' '.$this->date_format['currency'];

   }else{
   $balanceAmtdata=$this->date_format['currency'].' '.round($balanceAmt,2);
   $totalDebitDataVal=$this->date_format['currency'].' '.round($totalDebit,2);
   $totalcreditDataVal=$this->date_format['currency'].' '.round($totalcredit,2);
   $assetDataArrDataVal=$this->date_format['currency'].' '.round(array_sum($assetDataArr),2);

   }

    $html.='<tr><td>Total</td> <td>'.$totalDebitDataVal.'</td> <td>'.$totalcreditDataVal.'</td> <td>'.$balanceAmtdata.'</td> </tr>';
    
    $html.='<tr><th>Balance</th><th>'.$balanceAmtdata.'<th><td></td></tr>';
    $html.='<tr><th>Fixed Asset</th><th>'.$assetDataArrDataVal.'</th><td></td><td></td></tr>';
    
    $totalAmtBalance= $balanceAmt+array_sum($assetDataArr);

   if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
   $totalAmtBalanceData=round($totalAmtBalance,2).' '.$this->date_format['currency'];
   }else{
   $totalAmtBalanceData=$this->date_format['currency'].' '.round($totalAmtBalance,2);
   }

    $html.='<tr><th></th><th>'.$totalAmtBalanceData.'</th><td></td><td></td></tr>';

    $totalAmtBalanceData=$totalAmtBalance-$assetCapitaldata;

   if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
   $totalAmtBalanceDataVal=round($totalAmtBalanceData,2).' '.$this->date_format['currency'];
   }else{
   $totalAmtBalanceDataVal=$this->date_format['currency'].' '.round($totalAmtBalanceData,2);
   }

    $html.='<tr><th>Capital Min(Balance)</th><th>'.$totalAmtBalanceDataVal.'</th><th></th><th></th></tr>';

    $html.='</table>';
    echo $html;
    }

}	