<?php  
error_reporting(1);
defined('BASEPATH') OR exit('No direct script access allowed');

require FCPATH.'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

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
        $this->load->model('model_orders');
            $this->load->model('model_expenses');
		$this->data['date_format']=$this->model_company->get_currency_date_format();
		$this->date_format=$this->model_company->get_currency_date_format();
	    $this->data['company_data'] = $this->model_company->getCompanyData(1);
    }

    public function year()
    {
        return array('2022', '2023', '2024', '2025', '2026', '2027', '2028', '2029', '2030', '2031', '2032', '2033');   
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


     /*  Inventory Report
     *   Purpose : Check Inventory Report
     *   Date: 22-03-2022
     */    

	public function inventoryreport(){
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

  	$this->data['page_title'] = 'Inventory Report';
    $record=$this->model_reports->getInventoryreport();
    // print_r($record['id']);
    // die;
    $arrayData=array();
    $arr_val=array();
    foreach ($record as $key => $value){

        $saleOutPro=$this->model_reports->getSaleOutProduct($value['id']);
   
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
        $arrayData[$key]["getoutqty"]=$saleOutPro['getoutqty'] ? : 0;
        
        }
       $this->data['record']=$arrayData;
        //    print_r($this->data);
        // die;
       $this->render_template('reports/inventory', $this->data);
      }

    public function exportExcelInventory(){
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }


    $this->data['page_title'] = 'Inventory Report';
    $record=$this->model_reports->getInventoryreport();    
    // echo"<pre>";
    // print_r($record);
    // die;
    $arrayData=array();
    $arr_val=array();
    foreach ($record as $key => $value){
        $saleOutPro=$this->model_reports->getSaleOutProduct($value['id']);
   
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
        $arrayData[$key]["getoutqty"]=$saleOutPro['getoutqty'] ? : 0;
        }
        // echo"<pre>";
        // print_r($arrayData);
        // die;
        $tmpfileName = 'inventory-report';
        $tmpspreadsheet = new Spreadsheet();
        $tmpspreadsheet->setActiveSheetIndex(0);
        $tmpspreadsheet->getActiveSheet()->getStyle("A1:K1")->getFont()->setBold(true);
        $tmpsheet = $tmpspreadsheet->getActiveSheet()->setTitle('asset-report');
        $tmpsheet->setCellValue('A1', 'S No');
        $tmpsheet->setCellValue('B1', 'Invoice No');        
        $tmpsheet->setCellValue('C1', 'Model No');
        $tmpsheet->setCellValue('D1', 'Description');
        $tmpsheet->setCellValue('E1', 'Color');

        $tmpsheet->setCellValue('F1', 'Actual Stock');
        $tmpsheet->setCellValue('G1', 'SaleOut Stock');
        $tmpsheet->setCellValue('H1', 'Available Stock');

        $tmpsheet->setCellValue('I1', 'CIF');
        $tmpsheet->setCellValue('J1', 'Cost Price');
        $tmpsheet->setCellValue('K1', 'Total Value');
    
    $rows_asset=2;
    $totalAmt=array();
    
      foreach ($arrayData as $key=>$val){
           
            $totalAmt[]=$val['price'] * $val['qty'];
            $totalAmtrow=round($val['price'] * $val['qty'],2);
             $cif=$val['cif'];
             $price= $val['price'];  

            // if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
            // $cif=$val['cif'].' '.$this->date_format['currency'];
            // $price= $val['price'].' '.$this->date_format['currency'];
            // $totalprice= $totalAmtrow.' '.$this->date_format['currency'];
             
            // }else{
            // $cif=$this->date_format['currency'].' '.$val['cif'];
            // $price= $this->date_format['currency'].' '.$val['price'];
            // $totalprice= $this->date_format['currency'].' '.$totalAmtrow;
            // }

    $saleoutStock=$val['getoutqty'];

    $tmpsheet->setCellValue('A' . $rows_asset, $key+1);
    $tmpsheet->setCellValue('B' . $rows_asset, $val['oth_invoice_no']);
    $tmpsheet->setCellValue('C' . $rows_asset, $val['model_no']);
    $tmpsheet->setCellValue('D' . $rows_asset, strip_tags($val['description']));
    $tmpsheet->setCellValue('E' . $rows_asset, $val['attr']);

     $tmpsheet->setCellValue('F' . $rows_asset, $val['initial_qty']);
     $tmpsheet->setCellValue('G' . $rows_asset, $saleoutStock);
     $tmpsheet->setCellValue('H' . $rows_asset, $val['qty']);
    
    $tmpsheet->setCellValue('I' . $rows_asset, $cif);
    $tmpsheet->setCellValue('J' . $rows_asset, $price);
    $tmpsheet->setCellValue('K' . $rows_asset, $totalAmtrow);
    $rows_asset++;   
    }

    $totalAmtCr=round(array_sum($totalAmt),2);

    // if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
           
    // $totalAmtCr=round(array_sum($totalAmt),2).' '.$this->date_format['currency'];
    // }else{
    // $totalAmtCr=$this->date_format['currency'].' '.round(array_sum($totalAmt),2);
           
    // }

            $tmpsheet->setCellValue('A' . $rows_asset,'');
            $tmpsheet->setCellValue('B' . $rows_asset,'');
            $tmpsheet->setCellValue('C' . $rows_asset,'');
            $tmpsheet->setCellValue('D' . $rows_asset,'');
            $tmpsheet->setCellValue('E' . $rows_asset,'');
            $tmpsheet->setCellValue('F' . $rows_asset,'');

            $tmpsheet->setCellValue('G' . $rows_asset,'');
            $tmpsheet->setCellValue('H' . $rows_asset,'');
            $tmpsheet->setCellValue('I' . $rows_asset,'');

            $tmpsheet->setCellValue('J' . $rows_asset,'Total Amount');
            $tmpsheet->setCellValue('K' . $rows_asset, $totalAmtCr);

    $tmpwriter = new Xls($tmpspreadsheet);
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $tmpfileName .'.xls"'); 
    header('Cache-Control: max-age=0');
    $tmpwriter->save('php://output'); // download file 
    }

   /*
   *  Sales report function 
   */

    public function salesreport(){
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
    //echo"<pre>";
    $this->data['page_title'] = 'Sales Report';
    $this->data['customerData']=$this->model_reports->getCustomerData();
     //print_r($this->data['result']);
    $this->render_template('reports/report_sales', $this->data);
    }
   
   /*
   *  Sales report List Data function 
   */

    public function getSalesReportList(){
    //extract($_POST);
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
    $customer_name=$this->input->post('customer_name');
    $from_date=$this->input->post('from_date');
    $to_date=$this->input->post('to_date');
    $payID=$this->input->post('payID');

    $this->data['result']=$this->model_reports->getsalesreport($customer_name,$from_date,$to_date,$payID);

    // echo"<pre>";
    // print_r($this->data['result']);
    // die;
    $this->load->view('reports/tmp_sales_report', $this->data);
    }

    public function exportexcelsalesreport(){
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

    $customer_name=$this->input->post('customer_name');
    $from_date=$this->input->post('from_date');
    $to_date=$this->input->post('to_date');
    $payID=$this->input->post('payID');

    $result=$this->model_reports->getsalesreport($customer_name,$from_date,$to_date,$payID);


    $tmpfileName = 'sales-report';  
    $tmpspreadsheet = new Spreadsheet();
    $tmpspreadsheet->setActiveSheetIndex(0);
    $tmpspreadsheet->getActiveSheet()->getStyle("A1:I1")->getFont()->setBold(true);
    $tmpsheet = $tmpspreadsheet->getActiveSheet()->setTitle('sales-report');
    $tmpsheet->setCellValue('A1', 'Order Id');
    $tmpsheet->setCellValue('B1', 'Customer Code');
    $tmpsheet->setCellValue('C1', 'Customer Details');
    $tmpsheet->setCellValue('D1', ' Customer Address');
    $tmpsheet->setCellValue('E1', 'Order Date');
    $tmpsheet->setCellValue('F1', 'Order Value');
    $tmpsheet->setCellValue('G1', 'VAT');
    $tmpsheet->setCellValue('H1', 'PAYMENTS');
    $tmpsheet->setCellValue('I1', 'Created By');
     $rows_asset=0;
 foreach ($result as $key01 => $value_one){
   // $rows_asset = 2;
    if($rows_asset>0){
        $rows_asset=$end_row;
    }else{
        $rows_asset=2;
    }
    $total_net_amount=array();
    $total_vat_charge=array();

 foreach ($value_one as $key02 => $arrData){

    // if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
    // $net_amount=$arrData['net_amount'].' '.$this->date_format['currency'];
    // $vat_charge=$arrData['vat_charge'].' '.$this->date_format['currency'];
    // }else{
    // $net_amount=$this->date_format['currency'].' '.$arrData['net_amount'];
    // $vat_charge=$this->date_format['currency'].' '.$arrData['vat_charge'];
    // }

    if(!empty($arrData['firstname'])){
    $frname=$arrData['firstname'];
    }else{
    $frname='';
    }

    if(!empty($arrData['lastname'])){
    $lastname=$arrData['lastname'];
    }else{
    $lastname='';
    }
    $user_name=$frname.' '.$lastname;


    $total_net_amount[]=$arrData['net_amount'];
    $total_vat_charge[]=$arrData['vat_charge'];

    $paid_status='';
    if($arrData['paid_status']==1){
    $paid_status='RECEIVED';     
    }elseif($arrData['paid_status']==2){
    $paid_status='NOT';
    }elseif($arrData['paid_status']==3){
    $paid_status='FOC';
    }elseif($arrData['paid_status']==4){
    $paid_status='CANCELLED';
    }

    $tmpsheet->setCellValue('A' . $rows_asset, $arrData['bill_no']);
    $tmpsheet->setCellValue('B' . $rows_asset, $arrData['code']);
    $tmpsheet->setCellValue('C' . $rows_asset, $arrData['cust_details']);
    $tmpsheet->setCellValue('D' . $rows_asset, $arrData['customer_address']);
    $tmpsheet->setCellValue('E' . $rows_asset, date($this->data['date_format']['date_format'],strtotime($arrData['date_time'])));
    $tmpsheet->setCellValue('F' . $rows_asset, $arrData['net_amount']);
    $tmpsheet->setCellValue('G' . $rows_asset, $arrData['vat_charge']);
    $tmpsheet->setCellValue('H' . $rows_asset, $paid_status);
     $tmpsheet->setCellValue('I' . $rows_asset, $user_name);

    $rows_asset++;   
    }
    $end_row=$rows_asset;

    $total_net_amount_cur=round(array_sum($total_net_amount),2); 
    $total_vat_charge_cur=round(array_sum($total_vat_charge),2);


    // if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
    // $total_net_amount_cur=round(array_sum($total_net_amount),2).' '.$this->date_format['currency'];
    // $total_vat_charge_cur=round(array_sum($total_vat_charge),2).' '.$this->date_format['currency'];
    // }else{
    // $total_net_amount_cur=$this->date_format['currency'].' '.round(array_sum($total_net_amount),2);
    // $total_vat_charge_cur=$this->date_format['currency'].' '.round(array_sum($total_vat_charge),2);
    // }

    $tmpsheet->setCellValue('A' . $rows_asset, '');
    $tmpsheet->setCellValue('B' . $rows_asset, '');
    $tmpsheet->setCellValue('C' . $rows_asset, '');
    $tmpsheet->setCellValue('D' . $rows_asset, '');
    $tmpsheet->setCellValue('E' . $rows_asset, 'Total');
    $tmpsheet->setCellValue('F' . $rows_asset, $total_net_amount_cur);
    $tmpsheet->setCellValue('G' . $rows_asset, $total_vat_charge_cur);
    $tmpsheet->setCellValue('H' . $rows_asset, '');
    $tmpsheet->setCellValue('I' . $rows_asset, '');
    $end_row++;
}

    $tmpwriter = new Xls($tmpspreadsheet);
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $tmpfileName .'.xls"'); 
    header('Cache-Control: max-age=0');
    $tmpwriter->save('php://output'); // download file
    }


    /* get order detais
    * 
    */

    public function order_details($id){
    // $data=$this->model_reports->getOrderDetailsById($id);
    //echo"<pre>";
    // print_r($data);
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
    $this->data['order_data'] = $this->model_orders->getOrdersData($id);
    $orders_items = $this->model_orders->getOrdersItemData($id);
    $orderItem=array();
    
    foreach($orders_items as $key => $value){
    //print_r($value);
    $productVal=$this->model_reports->getproductData($value['product_id']);
    $orderItem[]=$value; 
    $orderItem[$key]['model_no_pro']=$productVal['model_no'];
    $orderItem[$key]['invoice_no_pro']=$productVal['invoice_no'];
    $orderItem[$key]['pro_name']=$productVal['pro_name'];
    $orderItem[$key]['unit_pro']=$productVal['unit'];
    //print_r($productVal);
    }
    $this->data['orders_items']=$orderItem;
    $this->data['id']=$id;
    //$this->data['orders_items'] = $this->model_orders->getOrdersItemData($id);
    $this->data['company_info'] = $this->model_company->getCompanyData(1);
    $this->data['custID'] = $this->model_orders->getCustomerData($this->data['order_data']['cust_id']);
    //print_r($this->data); 
    $this->render_template('reports/order_report_detail', $this->data);
    }


    /* Invoice Salse Report
    *  Purpose : Get Sales Repoort 
    *  Date : 24-03-2022
    */

    public function invoice_report(){
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
    //echo"<pre>";
    $this->data['page_title'] = 'Invoice Sales Report';
    $this->data['customerData']=$this->model_reports->getCustomerData();  
     //print_r($this->data['result']);
    $currectYear=date('Y');
    //$currectYear='2025';
    $year=$this->year();
    $yearDropdown=array();
    foreach($year as $yearData){
    if($yearData<=$currectYear){
    $yearDropdown[]=$yearData;
    }
    }
    $this->data['yearDropdown']=array_reverse($yearDropdown);
    $this->render_template('reports/invoice_report', $this->data);
    }

    public function getInvoiceSalesReportList(){
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

     
    $this->data['page_title'] = 'Invoice Sales Report';
    $custName=$this->input->post('custID');
    $payID=$this->input->post('payID');
    $this->data['result']=$this->model_reports->getInvoicereport($custName,$_POST['yearID'],$_POST['monthID'],$payID);
    $this->load->view('reports/tmp_sales_invoice_report', $this->data);
    }

    public function exportInvoiceSalesReportList(){
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
    $custName=$this->input->post('custID');
    $payID=$this->input->post('payID');
    $result=$this->model_reports->getInvoicereport($custName,$_POST['yearID'],$_POST['monthID'],$payID); 
    
    $tmpfileName = 'invoice-sales-report';  
    $tmpspreadsheet = new Spreadsheet();
    $tmpspreadsheet->setActiveSheetIndex(0);
    $tmpspreadsheet->getActiveSheet()->getStyle("A1:I1")->getFont()->setBold(true);
    $tmpsheet = $tmpspreadsheet->getActiveSheet()->setTitle('invoice-sales-report');
    $tmpsheet->setCellValue('A1', 'Order Id');
    $tmpsheet->setCellValue('B1', 'Customer Code');
    $tmpsheet->setCellValue('C1', 'Customer Details');
    $tmpsheet->setCellValue('D1', ' Customer Address');
    $tmpsheet->setCellValue('E1', 'Order Date');
    $tmpsheet->setCellValue('F1', 'Order Value');
    $tmpsheet->setCellValue('G1', 'VAT');
    $tmpsheet->setCellValue('H1', 'PAYMENTS');
    $tmpsheet->setCellValue('I1', 'Created By');

    $rows_asset=0;
    foreach ($result as $key01 => $value_one){
   // $rows_asset = 2;
    if($rows_asset>0){
        $rows_asset=$end_row;
    }else{
        $rows_asset=2;
    }
    $total_net_amount=array();
    $total_vat_charge=array();
   foreach ($value_one as $key02 => $arrData){

    
    // if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
    // $net_amount=$arrData['net_amount'].' '.$this->date_format['currency'];
    // $vat_charge=$arrData['vat_charge'].' '.$this->date_format['currency'];
    // }else{
    // $net_amount=$this->date_format['currency'].' '.$arrData['net_amount'];
    // $vat_charge=$this->date_format['currency'].' '.$arrData['vat_charge'];
    // }

    $total_net_amount[]=$arrData['net_amount'];
    $total_vat_charge[]=$arrData['vat_charge'];

    $paid_status='';
    if($arrData['paid_status']==1){
    $paid_status='RECEIVED';     
    }elseif($arrData['paid_status']==2){
    $paid_status='NOT';
    }elseif($arrData['paid_status']==3){
    $paid_status='FOC';
    }elseif($arrData['paid_status']==4){
    $paid_status='CANCELLED';
    }

       if(!empty($arrData['firstname'])){
    $frname=$arrData['firstname'];
    }else{
    $frname='';
    }

    if(!empty($arrData['lastname'])){
    $lastname=$arrData['lastname'];
    }else{
    $lastname='';
    }
    $user_name=$frname.' '.$lastname;


    $tmpsheet->setCellValue('A' . $rows_asset, $arrData['bill_no']);
    $tmpsheet->setCellValue('B' . $rows_asset, $arrData['code']);
    $tmpsheet->setCellValue('C' . $rows_asset, $arrData['cust_details']);
    $tmpsheet->setCellValue('D' . $rows_asset, strip_tags($arrData['customer_address']));
    $tmpsheet->setCellValue('E' . $rows_asset, date($this->data['date_format']['date_format'],strtotime($arrData['date_time'])));
    $tmpsheet->setCellValue('F' . $rows_asset, $arrData['net_amount']);
    $tmpsheet->setCellValue('G' . $rows_asset, $arrData['vat_charge']);
    $tmpsheet->setCellValue('H' . $rows_asset, $paid_status);
    $tmpsheet->setCellValue('I' . $rows_asset, $user_name);
    
    $rows_asset++;   
    }

    $end_row=$rows_asset;

    $total_net_amount_cur=round(array_sum($total_net_amount),2);
    $total_vat_charge_cur=round(array_sum($total_vat_charge),2);

    // if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
    // $total_net_amount_cur=round(array_sum($total_net_amount),2).' '.$this->date_format['currency'];
    // $total_vat_charge_cur=round(array_sum($total_vat_charge),2).' '.$this->date_format['currency'];
    // }else{
    // $total_net_amount_cur=$this->date_format['currency'].' '.round(array_sum($total_net_amount),2);
    // $total_vat_charge_cur=$this->date_format['currency'].' '.round(array_sum($total_vat_charge),2);
    // }

    $tmpsheet->setCellValue('A' . $rows_asset, '');
    $tmpsheet->setCellValue('B' . $rows_asset, '');
    $tmpsheet->setCellValue('C' . $rows_asset, '');
    $tmpsheet->setCellValue('D' . $rows_asset, '');
    $tmpsheet->setCellValue('E' . $rows_asset, 'Total');
    $tmpsheet->setCellValue('F' . $rows_asset, $total_net_amount_cur);
    $tmpsheet->setCellValue('G' . $rows_asset, $total_vat_charge_cur);
    $tmpsheet->setCellValue('H' . $rows_asset, '');
    $tmpsheet->setCellValue('I' . $rows_asset, '');
     
    
        $end_row++;
    }

    $tmpwriter = new Xls($tmpspreadsheet);
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $tmpfileName .'.xls"'); 
    header('Cache-Control: max-age=0');
    $tmpwriter->save('php://output'); // download file
    }
    
   
    public function getpdfInvoiceSalesReport($id=''){
   // $custName=$this->input->post('custID');
    //$this->data['result']=$this->model_reports->getInvoicereport($id);
//    echo"<pre>";
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

    $order_data = $this->model_orders->getOrdersData($id);
    // print_r($order_data);
    // die;
    $ordersItemsData = $this->model_orders->getOrdersItemData($id);
    
    $this->data['order_data']=$order_data;

    $orders_items=array();
    
    foreach($ordersItemsData as $key => $value){
    //print_r($value);
    $productVal=$this->model_reports->getproductData($value['product_id']);
    $orders_items[]=$value; 
    $orders_items[$key]['model_no_pro']=$productVal['model_no'];
    $orders_items[$key]['invoice_no_pro']=$productVal['invoice_no'];
    $orders_items[$key]['pro_name']=$productVal['pro_name'];
    $orders_items[$key]['unit_pro']=$productVal['unit'];

    //print_r($productVal);
    }

    $this->data['orders_items']=$orders_items;
    $this->data['company_info']= $this->model_company->getCompanyData(1);
    $this->data['custID'] = $this->model_orders->getCustomerData($order_data['cust_id']);
    $this->data['order_date'] = date($this->date_format['date_format'], strtotime($order_data['date_time']));
    $this->data['paid_status']= ($order_data['paid_status'] == 1) ? "Paid" : "Unpaid";
    $this->data['order_date_new'] = date($this->date_format['date_format'], strtotime($order_data['date_time']));
    $this->data['due_days']=$order_data['due_date'];
    $this->data['next_due_date']=date($this->date_format['date_format'],strtotime($order_data['date_time']. ' +'.$order_data['due_date'].'days'));

     $fileName = $order_data['bill_no'].'.pdf';
     $this->pdf->download_invoice('reports/invoice_pdf_file',$this->data,$fileName);
    }

    public function download_invoice_report(){
        $this->data='';$fileName="test.pdf";
    $this->pdf->load_view('reports/test_pdf',$this->data,$fileName);
    }


    /*  Due Report
    *   Purpose: Get Due Amount Report
    *   Date: 22-03-2022 
    */

	public function duereport(){
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
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

    public function exportExcelDueReport(){
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

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

    $tmpfileName = 'due-report';  
    $tmpspreadsheet = new Spreadsheet();
    $tmpspreadsheet->setActiveSheetIndex(0);
    $tmpspreadsheet->getActiveSheet()->getStyle("A1:F1")->getFont()->setBold(true);
    $tmpsheet = $tmpspreadsheet->getActiveSheet()->setTitle('asset-report');
    $tmpsheet->setCellValue('A1', 'Order Id');
    $tmpsheet->setCellValue('B1', 'Customer Code');
    $tmpsheet->setCellValue('C1', ' Customer Name');
    $tmpsheet->setCellValue('D1', ' Order Date');
    $tmpsheet->setCellValue('E1', ' Due Date');
    $tmpsheet->setCellValue('F1', 'Due Amount');
   
    $rows_asset = 2;
    foreach ($arr as $arrData) {
    if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
    $value_due=$arrData['due_amount'].' '.$this->date_format['currency'];
    }else{
    $value_due=$this->date_format['currency'].' '.$arrData['due_amount'];
    }

    $tmpsheet->setCellValue('A' . $rows_asset, $arrData['bill_no']);
    $tmpsheet->setCellValue('B' . $rows_asset, $arrData['customer_code']);
    $tmpsheet->setCellValue('C' . $rows_asset, $arrData['customer_name']);
    $tmpsheet->setCellValue('D' . $rows_asset, $arrData['order_date']);
    $tmpsheet->setCellValue('E' . $rows_asset, $arrData['due_date']);
    $tmpsheet->setCellValue('F' . $rows_asset, $arrData['due_amount']);

    $rows_asset++;   
    }
    $tmpwriter = new Xls($tmpspreadsheet);
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $tmpfileName .'.xls"'); 
    header('Cache-Control: max-age=0');
    $tmpwriter->save('php://output'); // download file
    }

    /*  Account Report
    *   Purpose: Get Over All Balance Sheet
    *   Date: 22-03-2022 
    */

    public function account(){
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
    $this->data['page_title'] = 'Account Report';
    $currectYear=date('Y');
    //$currectYear='2025';
    $year=$this->year();
    $yearDropdown=array();
    foreach($year as $yearData){
    if($yearData<=$currectYear){
    $yearDropdown[]=$yearData;
    }
    }
    $this->data['yearDropdown']=array_reverse($yearDropdown);
    $this->render_template('reports/account',$this->data);
    }

    public function getAccountDataList(){
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }


    $assetsData=$this->model_reports->getAssetsDataList($_POST['monthID'],$_POST['yearID']);
       
    $expencesData=$this->model_reports->getExpencesDataList($_POST['monthID'],$_POST['yearID']);

    $curr_assetsData=$this->model_reports->getCurrentAssetsDataList($_POST['monthID'],$_POST['yearID']);

    $CurrentAssetStatisData=$this->model_reports->getCurrentAssetStaticData();
    // echo"<pre>";
    // print_r($CurrentAssetStatisData);
    // die;

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
    
   if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
    $getInvestorPrimaryAmt=$getInvestorPrimary['amt'][0].' '.$this->date_format['currency'];
    $CurrentInvestor01Amt=$CurrentInvestor01['amt'][0].' '.$this->date_format['currency'];
    $CurrentInvestor02Amt=$CurrentInvestor02['amt'][0].' '.$this->date_format['currency'];

    }else{
    $getInvestorPrimaryAmt=$this->date_format['currency'].' '.$getInvestorPrimary['amt'][0];
    $CurrentInvestor01Amt=$this->date_format['currency'].' '.$CurrentInvestor01['amt'][0];
    $CurrentInvestor02Amt=$this->date_format['currency'].' '.$CurrentInvestor02['amt'][0];   
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

    if($getInvestorPrimary['amt'][0]>0){
    $html.='<tr><td></td><td>'.$getInvestorPrimary['name'][0].'</td><td>'.$getInvestorPrimaryAmt.'</td></tr>';
    }
    
    if($CurrentInvestor01['amt'][0]>0){
    $html.='<tr><td></td><td>'.$CurrentInvestor01['name'][0].'</td><td>'.$CurrentInvestor01Amt.'</td></tr>';
    }
   
    if($CurrentInvestor02['amt'][0]>0){
     $html.='<tr><td></td><td>'.$CurrentInvestor02['name'][0].'</td><td>'.$CurrentInvestor02Amt.'</td></tr>';
    }

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


    public function exportAccountDataList(){
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

    $assetsData=$this->model_reports->getAssetsDataList($_POST['monthID'],$_POST['yearID']);
    $tmpfileName = 'account-report';  
    $tmpspreadsheet = new Spreadsheet();
    $tmpspreadsheet->setActiveSheetIndex(0);
    $tmpspreadsheet->getActiveSheet()->getStyle("A1:C1")->getFont()->setBold(true);
    $tmpsheet = $tmpspreadsheet->getActiveSheet()->setTitle('asset-report');
    $tmpsheet->setCellValue('A1', 'code');
    $tmpsheet->setCellValue('B1', 'Asset');
    $tmpsheet->setCellValue('C1', 'Amount');

    $rows_asset = 2;

    foreach ($assetsData as $assetsDataVal) {
    $value_Amt=$assetsDataVal['value']; 
    // if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
    // $value_Amt=$assetsDataVal['value'].' '.$this->date_format['currency'];
    // }else{
    // $value_Amt=$this->date_format['currency'].' '.$assetsDataVal['value'];
    // }

    $tmpsheet->setCellValue('A' . $rows_asset, $assetsDataVal['code']);
    $tmpsheet->setCellValue('B' . $rows_asset, $assetsDataVal['name']);
    $tmpsheet->setCellValue('C' . $rows_asset, $value_Amt);
    $rows_asset++;   
    }

    $tmpspreadsheet->createSheet();
    $tmpspreadsheet->setActiveSheetIndex(1);
    $tmpspreadsheet->getActiveSheet()->getStyle("A1:C1")->getFont()->setBold(true);
    $twotmpsheet=$tmpspreadsheet->getActiveSheet()->setTitle('current-asset-report');
    $twotmpsheet->setCellValue('A1','code');
    $twotmpsheet->setCellValue('B1','Asset');
    $twotmpsheet->setCellValue('C1','Amount');

    $curr_assetsData=$this->model_reports->getCurrentAssetsDataList($_POST['monthID'],$_POST['yearID']);
    $CurrentAssetStatisData=$this->model_reports->getCurrentAssetStaticData();

    $getInvestorPrimary=$this->model_reports->getInvestorPrimary($_POST['monthID'],$_POST['yearID']);

    $CurrentInvestor01=$this->model_reports->getInvestorSecon01($_POST['monthID'],$_POST['yearID']);

    $CurrentInvestor02=$this->model_reports->getInvestorSecon02($_POST['monthID'],$_POST['yearID']);

    $rows_current_asst=2;
    foreach($curr_assetsData as $key1 => $currentAssetValue){
    $value_curAsset=$currentAssetValue['value'];
        
    // if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
    // $value_curAsset=$currentAssetValue['value'].' '.$this->date_format['currency'];
    // }else{
    // $value_curAsset=$this->date_format['currency'].' '.$currentAssetValue['value'];
    // }

    $twotmpsheet->setCellValue('A' . $rows_current_asst, $currentAssetValue['code']);
    $twotmpsheet->setCellValue('B' . $rows_current_asst, $currentAssetValue['name']);
    $twotmpsheet->setCellValue('C' . $rows_current_asst, $value_curAsset);
    $rows_current_asst++;
    }

    $rows_static_current_asst=$rows_current_asst;

    $pendingAmt=round($CurrentAssetStatisData['pending_amount']['amt'][0],2);
    $comersialAmt=round($CurrentAssetStatisData['paid_comersial_amt']['amt'][0],2);
    $proAvbAmt=round($CurrentAssetStatisData['avaliable_pro_amt']['amt'][0],2);

    // if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
    // $pendingAmt=round($CurrentAssetStatisData['pending_amount']['amt'][0],2).' '.$this->date_format['currency'];
    // $comersialAmt=round($CurrentAssetStatisData['paid_comersial_amt']['amt'][0],2).' '.$this->date_format['currency'];
    // $proAvbAmt=round($CurrentAssetStatisData['avaliable_pro_amt']['amt'][0],2).' '.$this->date_format['currency'];

    // }else{
    // $pendingAmt=$this->date_format['currency'].' '.round($CurrentAssetStatisData['pending_amount']['amt'][0],2);
    // $comersialAmt=$this->date_format['currency'].' '.round($CurrentAssetStatisData['paid_comersial_amt']['amt'][0],2);
    // $proAvbAmt=$this->date_format['currency'].' '.round($CurrentAssetStatisData['avaliable_pro_amt']['amt'][0],2);
    // }


    $twotmpsheet->setCellValue('A' . $rows_static_current_asst, '');
    $twotmpsheet->setCellValue('B' . $rows_static_current_asst,$CurrentAssetStatisData['pending_amount']['name'][0]);
    $twotmpsheet->setCellValue('C' . $rows_static_current_asst, $pendingAmt);
    $row_s_asset02=$rows_static_current_asst+1;


    $twotmpsheet->setCellValue('A' . $row_s_asset02, '');
    $twotmpsheet->setCellValue('B' . $row_s_asset02,$CurrentAssetStatisData['paid_comersial_amt']['name'][0]);
    $twotmpsheet->setCellValue('C' . $row_s_asset02, $comersialAmt);
    $row_s_asset03=$row_s_asset02+1;

    $twotmpsheet->setCellValue('A' . $row_s_asset03, '');
    $twotmpsheet->setCellValue('B' . $row_s_asset03,$CurrentAssetStatisData['avaliable_pro_amt']['name'][0]);
    $twotmpsheet->setCellValue('C' . $row_s_asset03, $proAvbAmt);


    if($getInvestorPrimary['amt'][0]>0){
    $row_investor=$row_s_asset03+1;
    $twotmpsheet->setCellValue('A' . $row_investor, '');
    $twotmpsheet->setCellValue('B' . $row_investor, $getInvestorPrimary['name'][0]);
    $twotmpsheet->setCellValue('C' . $row_investor, $getInvestorPrimary['amt'][0]);
    $rows_investor01=$row_investor;
    }else{
    $rows_investor01=$row_s_asset03;
    }

    if($CurrentInvestor01['amt'][0]>0){
    $rows_investor02=$rows_investor01+1;
    $twotmpsheet->setCellValue('A' . $rows_investor02, '');
    $twotmpsheet->setCellValue('B' . $rows_investor02, $CurrentInvestor01['name'][0]);
    $twotmpsheet->setCellValue('C' . $rows_investor02, $CurrentInvestor01['amt'][0]);
    $rows_investor03=$rows_investor02;
    }else{
    $rows_investor03=$rows_investor01;
    }

    if($CurrentInvestor02['amt'][0]>0){
    $rows_investor04=$rows_investor03+1;   
    $twotmpsheet->setCellValue('A' . $rows_investor04, '');
    $twotmpsheet->setCellValue('B' . $rows_investor04, $CurrentInvestor02['name'][0]);
    $twotmpsheet->setCellValue('C' . $rows_investor04, $CurrentInvestor02['amt'][0]);
    }

    $expencesData=$this->model_reports->getExpencesDataList($_POST['monthID'],$_POST['yearID']);

    $tmpspreadsheet->createSheet();
    $tmpspreadsheet->setActiveSheetIndex(2);
    $tmpspreadsheet->getActiveSheet()->getStyle("A1:C1")->getFont()->setBold(true);
    $threetmpsheet=$tmpspreadsheet->getActiveSheet()->setTitle('expences-report');
    $threetmpsheet->setCellValue('A1','code');
    $threetmpsheet->setCellValue('B1','Asset');
    $threetmpsheet->setCellValue('C1','Amount');

    $rows_expences=2;

    foreach($expencesData as $key3 => $expencesDataVal){
    // if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
    
    // $value_exp=$expencesDataVal['amount'].' '.$this->date_format['currency'];
    // }else{
    // $value_exp=$this->date_format['currency'].' '.$expencesDataVal['amount'];
    // }
    $value_exp=$expencesDataVal['amount'];
    $threetmpsheet->setCellValue('A' . $rows_expences, $expencesDataVal['code']);
    $threetmpsheet->setCellValue('B' . $rows_expences, $expencesDataVal['name']);
    $threetmpsheet->setCellValue('C' . $rows_expences, $value_exp);
    $rows_expences++;
    }

    $tmpspreadsheet->setActiveSheetIndex(0);

    $tmpwriter = new Xls($tmpspreadsheet);
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $tmpfileName .'.xls"'); 
    header('Cache-Control: max-age=0');
    $tmpwriter->save('php://output'); // download file
    }


    public function balance_sheet_report(){
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
    $this->data['page_title'] = 'Balance Sheet Report';
    $currectYear=date('Y');
    //$currectYear='2025';
    $year=$this->year();
    $yearDropdown=array();
    foreach($year as $yearData){
    if($yearData<=$currectYear){
    $yearDropdown[]=$yearData;
    }
    }
    $this->data['yearDropdown']=array_reverse($yearDropdown);

    $this->render_template('reports/balance_sheet',$this->data);
    }

    /*
    * Balance Report
    *
    */

    public function getBalanceSheetDataList(){
    if(!in_array('viewReports', $this->permission)){
            redirect('dashboard', 'refresh');
                                                   }
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

    // echo"<pre>";    
    // print_r($all_focAmt);
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
   $OutstandingAmtCur=round($OutstandingAmt['OutstandingAmt'],2).' '.$this->date_format['currency'];
   }else{
   $netAssetCapitalDataVal=$this->date_format['currency'].' '.$netAssetCapital;
   $OutstandingAmtCur=$this->date_format['currency'].' '.round($OutstandingAmt['OutstandingAmt'],2);
   }

    $html.='<tr><td>Net</td><td>'.$netAssetCapitalDataVal.'</td></tr>';

    $html.='</table>';

    $html.='<table class="table table-bordered table-striped">';
    $html.='<tr><th>Remarks</th> <th>Debit</th> <th>Credit</th> </tr>';
    
    $html.='<tr><td>Outstanding payment Customer</td> <td>'.$OutstandingAmtCur.'</td> <td></td> </tr>';
    $crassetdata=array();
    foreach ($currentAsset as $key03 => $currentAssetDataVal){

   if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
   $currentAssetDV=$currentAssetDataVal['value'].' '.$this->date_format['currency'];
   }else{
   $currentAssetDV=$this->date_format['currency'].' '.$currentAssetDataVal['value'];
   }

    $crassetdata[]=$currentAssetDataVal['value'];
    $html.='<tr><td>'.$currentAssetDataVal['title'].'</td> <td></td> <td>'.$currentAssetDV.'</td></tr>';
    }

    $supplyerArr=array();
    foreach ($give_to_supplyer as $key04 => $givetoSupplyerDataval){
        $giveToSuppAmt=round($givetoSupplyerDataval['giveToSuppAmt'],2);
   if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
   $currentAssetGTS=$giveToSuppAmt.' '.$this->date_format['currency'];
   }else{
   $currentAssetGTS=$this->date_format['currency'].' '.$giveToSuppAmt;
   }

    $supplyerArr[]=$givetoSupplyerDataval['giveToSuppAmt'];
    $html.='<tr><td>Give to supplier('.$givetoSupplyerDataval['name'].')</td> <td>'.$currentAssetGTS.'</td><td></td></tr>';
    }


    
    if($current_stock['currentStock']>0){
    if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
    $currentStock=round($current_stock['currentStock'],2).' '.$this->date_format['currency'];
    }else{
    $currentStock=$this->date_format['currency'].' '.round($current_stock['currentStock'],2);
    }
    $html.='<tr><td>Current Stock</td> <td></td><td>'.$currentStock.'</td> </tr>';  
    }else{  }


    if($allexp['exp_amt']>0){
    if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
    $allexpVal=round($allexp['exp_amt'],2).' '.$this->date_format['currency'];
    }else{
    $allexpVal=$this->date_format['currency'].' '.round($allexp['exp_amt'],2);
    }
    $html.='<tr><td>Expenses,salary,fuel,phone Stock</td> <td>'.$allexpVal.'</td><td></td> </tr>';
    }else{  }
    
    if($primaryInvestor['amt'][0]>0){
    if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
    $primaryInvestorVal=round($primaryInvestor['amt'][0],2).' '.$this->date_format['currency'];
    }else{
    $primaryInvestorVal=$this->date_format['currency'].' '.round($primaryInvestor['amt'][0],2);
    } 
    $html.='<tr><td>'.$primaryInvestor['name'][0].'</td> <td>'.$primaryInvestorVal.'</td><td></td></tr>';
    }else{  }


    if($secondryInvestor01['amt'][0]>0){
    if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
    $secondryInvestor01Val=round($secondryInvestor01['amt'][0],2).' '.$this->date_format['currency'];
    }else{
    $secondryInvestor01Val=$this->date_format['currency'].' '.round($secondryInvestor01['amt'][0],2);
    } 
    $html.='<tr><td>'.$secondryInvestor01['name'][0].'</td> <td>'.$secondryInvestor01Val.'</td><td></td> </tr>';
    }else{  }


    if($secondryInvestor02['amt'][0]>0){
    if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
    $secondryInvestor02Val=round($secondryInvestor02['amt'][0],2).' '.$this->date_format['currency'];
    }else{
    $secondryInvestor02Val=$this->date_format['currency'].' '.round($secondryInvestor02['amt'][0],2);
    } 
    $html.='<tr><td>'.$secondryInvestor02['name'][0].'</td> <td>'.$secondryInvestor02Val.'</td><td></td> </tr>';
    }else{  }

    if($all_focAmt['foc_amt']>0){
    if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
    $all_focAmtVal=round($all_focAmt['foc_amt'],2).' '.$this->date_format['currency'];
    }else{
    $all_focAmtVal=$this->date_format['currency'].' '.round($all_focAmt['foc_amt'],2);
    } 
    $html.='<tr><td>FOC Stock</td> <td>'.$all_focAmtVal.'</td><td></td></tr>';
    }else{  }
    
    $totalDebit=$primaryInvestor['amt'][0] + $secondryInvestor01['amt'][0] + $secondryInvestor02['amt'][0]+ array_sum($supplyerArr) + $OutstandingAmt['OutstandingAmt'] + $allexp['exp_amt'];
   

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

    $html.='<tr><td>Total</td> <td>'.$totalDebitDataVal.'</td> <td>'.$totalcreditDataVal.'</td></tr>';
    
    $html.='<tr><th>Balance</th><th>'.$balanceAmtdata.'<th></tr>';
    $html.='<tr><th>Fixed Asset</th><th>'.$assetDataArrDataVal.'</th><td></td></tr>';
    
    $totalAmtBalance= $balanceAmt+array_sum($assetDataArr);

   if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
   $totalAmtBalanceData=round($totalAmtBalance,2).' '.$this->date_format['currency'];
   }else{
   $totalAmtBalanceData=$this->date_format['currency'].' '.round($totalAmtBalance,2);
   }

    $html.='<tr><th></th><th>'.$totalAmtBalanceData.'</th><td></td></tr>';

    $totalCapitalAmt=$totalAmtBalance-$assetCapitaldata;

   if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
   $totalCapitalAmtData=round($totalCapitalAmt,2).' '.$this->date_format['currency'];
   }else{
   $totalCapitalAmtData=$this->date_format['currency'].' '.round($totalCapitalAmt,2);
   }
    $html.='<tr><th>Capital Min(Balance)</th><th>'.$totalCapitalAmtData.'</th><th></th></tr>';

    $html.='</table>';
    echo $html;
    }

/*----- 2nd --------*/
   
/* Product Model Report
*  Puspose: For Filter Product a/c model
*  Date: 22-03-2022
*/
 
  public function product_model($id=''){
  $this->data['page_title'] = 'Product Model Report';  
  if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

  if($id){
  $this->data['productData']=$this->model_reports->getProductModel($id);
  $this->data['model_no']=$id;
  }
  $this->render_template('reports/product_model_report',$this->data);
 // $this->load->view('reports/tmp_product_model',$this->data);
  } 

  public function getProductModelData(){
  
  $model_no=$this->input->post('model_no');
  // echo $model_no;
  $this->data['productData']=$this->model_reports->getProductModel($model_no);
  $this->load->view('reports/tmp_product_model',$this->data);
  }


   public function exportProductModelData(){
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
    $model_no=$this->input->post('model_no');
    $record=$this->model_reports->getProductModel($model_no);

        $tmpfileName = 'product-model-sheet';  
        $tmpspreadsheet = new Spreadsheet();
        $tmpspreadsheet->setActiveSheetIndex(0);
        $tmpspreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true);
        $tmpsheet = $tmpspreadsheet->getActiveSheet()->setTitle('customer-record');
        $tmpsheet->setCellValue('A1', 'Bill No');
        $tmpsheet->setCellValue('B1', 'Customer Name');
        $tmpsheet->setCellValue('C1', 'Qty');
        $tmpsheet->setCellValue('D1', 'Order Date');   
          $rows_student = 2; 
          $qty=array();
          foreach ($record as $record_value) {
          $qty[]=$record_value['qty'];
          $tmpsheet->setCellValue('A' . $rows_student, $record_value['bill_no']);
          $tmpsheet->setCellValue('B' . $rows_student, $record_value['name']);
          $tmpsheet->setCellValue('C' . $rows_student, $record_value['qty']);
          $tmpsheet->setCellValue('D' . $rows_student, date($this->data['date_format']['date_format'],strtotime($record_value['date_time'])));

          $rows_student++;   
          }
          $extra_row=$rows_student;
        
          $tmpsheet->setCellValue('A' . $extra_row,'');
          $tmpsheet->setCellValue('B' . $extra_row,'Total');
          $tmpsheet->setCellValue('C' . $extra_row,array_sum($qty));
          $tmpsheet->setCellValue('D' . $extra_row, '');

        $tmpwriter = new Xls($tmpspreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $tmpfileName .'.xls"'); 
        header('Cache-Control: max-age=0');
        $tmpwriter->save('php://output'); // download file
    }

public function customer_all_order($custId){
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

    $this->data['result']=$this->model_reports->getsalesreportByCustomer($custId);
    // echo"<pre>";
    // print_r($this->data['result']);
    // die;
    $this->render_template('reports/customer_order', $this->data);
}

public function expense_report(){
if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

$this->data['page_title'] = 'Expense Reports';

$currectYear=date('Y');
//$currectYear='2025';
$year=$this->year();
$yearDropdown=array();
foreach($year as $yearData){
if($yearData<=$currectYear){
$yearDropdown[]=$yearData;
}
}
$this->data['yearDropdown']=array_reverse($yearDropdown);

$this->data['exp_code']=$this->model_expenses->getExpencesCode();
$this->render_template('reports/expence_report', $this->data);
}

public function getExpenceDataList(){
$this->data['expData']=$this->model_reports->getExpenceReport();
$this->load->view('reports/tmp_expence_report', $this->data);
}

 public function exportExpenceDataList(){
if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

 $expData=$this->model_reports->getExpenceReport();
 $tmpfileName = "expenses-report";  
        $tmpspreadsheet = new Spreadsheet();
        $tmpspreadsheet->setActiveSheetIndex(0);
        $tmpspreadsheet->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true);
        $tmpsheet = $tmpspreadsheet->getActiveSheet()->setTitle('expenses-report');
        $tmpsheet->setCellValue('A1','Code');
        $tmpsheet->setCellValue('B1','Expenses Name');
        $tmpsheet->setCellValue('C1','Amount');
        $tmpsheet->setCellValue('D1','Expenses Date');   
          $rows_student = 2; 
          $qty=array();
          foreach ($expData as $record_value) {
          $qty[]=$record_value['amount'];
          $tmpsheet->setCellValue('A' . $rows_student, $record_value['code']);
          $tmpsheet->setCellValue('B' . $rows_student, $record_value['title']);
          $tmpsheet->setCellValue('C' . $rows_student, $record_value['amount']);
          $tmpsheet->setCellValue('D' . $rows_student, date($this->data['date_format']['date_format'],strtotime($record_value['expense_date'])));

          $rows_student++;   
          }
          $extra_row=$rows_student;
        
          $tmpsheet->setCellValue('A' . $extra_row,'');
          $tmpsheet->setCellValue('B' . $extra_row,'Total');
          $tmpsheet->setCellValue('C' . $extra_row,round(array_sum($qty),2));
          $tmpsheet->setCellValue('D' . $extra_row, '');

        $tmpwriter = new Xls($tmpspreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$tmpfileName.'.xls"'); 
        header('Cache-Control: max-age=0');
        $tmpwriter->save('php://output'); // download file
    }

    /*
    *
    *
    */ 


   public function consolidated(){
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

    $this->data['page_title'] = 'Consolidated Reports';
    $currectYear=date('Y');
    //$currectYear='2025';
    $year=$this->year();
    $yearDropdown=array();
    foreach($year as $yearData){
    if($yearData<=$currectYear){
    $yearDropdown[]=$yearData;
    }
    }
    $this->data['yearDropdown']=array_reverse($yearDropdown);
    $this->render_template('reports/consolidate_sheet',$this->data);
    }


    public function getConsolidateDataList(){
    //($_POST['monthID'],$_POST['yearID']);
    //echo"<pre>";
    $data['OverAllCollection']=$this->model_reports->getCollectionAmtInCon();
    $data['OverAllSelling']=$this->model_reports->getOverAllSellingAmtCon();
    $data['OverAllFOC']=$this->model_reports->getFOCAmtCon();
    $data['OverAllExp']=$this->model_reports->allExpencesCon();    
    $OverAllProfit=$this->model_reports->getProfitOfAMonth();
    $data['profitAmount']=$OverAllProfit['profitAmt'] - $data['OverAllExp']['amount'];
    $pInvestor=$this->model_reports->getPrimaryInInfo();
    $s01Investor=$this->model_reports->getSecondary01InInfo();
    $s02Investor=$this->model_reports->getSecondary02InInfo();
    
    $data['primaryInvestorProfit']=($data['profitAmount']*$pInvestor['percentage']/100);
     
    $data['primaryInvestorName']=$pInvestor['name'];

    $amountForSecondary=$data['profitAmount']-$data['primaryInvestorProfit'];

    $data['sec01InvestorProfit']=($amountForSecondary*$s01Investor['percentage']/100);
    $data['sec01InvestorName']=$s01Investor['name'];

    $data['sec02InvestorProfit']=($amountForSecondary*$s02Investor['percentage']/100);
    $data['sec02InvestorName']=$s02Investor['name'];

    $data['outstandingAmt']=$this->model_reports->getOutStandingAmtCon();
    $data['date_format']=$this->model_company->get_currency_date_format();
    //print_r($data); 
    $this->load->view('reports/tmp_consolidate_sheet',$data);

    }

    public function exportconsolidatedDataList(){
    $data['OverAllCollection']=$this->model_reports->getCollectionAmtInCon();
    $data['OverAllSelling']=$this->model_reports->getOverAllSellingAmtCon();
    $data['OverAllFOC']=$this->model_reports->getFOCAmtCon();
    $data['OverAllExp']=$this->model_reports->allExpencesCon();  

    $OverAllProfit=$this->model_reports->getProfitOfAMonth();
    $data['profitAmount']=$OverAllProfit['profitAmt'] - $data['OverAllExp']['amount'];
    $pInvestor=$this->model_reports->getPrimaryInInfo();
    $s01Investor=$this->model_reports->getSecondary01InInfo();
    $s02Investor=$this->model_reports->getSecondary02InInfo();
    
    $data['primaryInvestorProfit']=($data['profitAmount']*$pInvestor['percentage']/100);
     
    $data['primaryInvestorName']=$pInvestor['name'];

    $amountForSecondary=$data['profitAmount']-$data['primaryInvestorProfit'];

    $data['sec01InvestorProfit']=($amountForSecondary*$s01Investor['percentage']/100);
    $data['sec01InvestorName']=$s01Investor['name'];

    $data['sec02InvestorProfit']=($amountForSecondary*$s02Investor['percentage']/100);
    $data['sec02InvestorName']=$s02Investor['name'];

    $data['outstandingAmt']=$this->model_reports->getOutStandingAmtCon();
    $data['date_format']=$this->model_company->get_currency_date_format();
    //print_r($data); 
  //  $this->load->view('reports/tmp_consolidate_sheet',$data);

        //$expData=$this->model_reports->getExpenceReport();
        $tmpfileName = "consolidated-report";  
        $tmpspreadsheet = new Spreadsheet();
        $tmpspreadsheet->setActiveSheetIndex(0);

        $tmpspreadsheet->getActiveSheet()->getStyle("A1:A5")->getFont()->setBold(true);
        $tmpspreadsheet->getActiveSheet()->getStyle("G1:G2")->getFont()->setBold(true);
        $tmpspreadsheet->getActiveSheet()->getStyle("A9:A10")->getFont()->setBold(true);
        $tmpspreadsheet->getActiveSheet()->getStyle("G9:G12")->getFont()->setBold(true);

        $tmpsheet = $tmpspreadsheet->getActiveSheet()->setTitle('consolidated-report');
        $tmpsheet->setCellValue('A1','Sales Report');

        $tmpsheet->setCellValue('A2','Over All Selling Amount');
        $tmpsheet->setCellValue('A3','Over All Collection Amount');
        $tmpsheet->setCellValue('A4','Total Outstanding Amount');
        $tmpsheet->setCellValue('A5','Over All FOC Amount');   

        $tmpsheet->setCellValue('B2',round($data['OverAllSelling']['OverallSelling'],2));
        $tmpsheet->setCellValue('B3',round($data['OverAllCollection']['netOverAllColection']));
        $tmpsheet->setCellValue('B4',round($data['outstandingAmt']['outStandAmt'],2));
        $tmpsheet->setCellValue('B5',round($data['OverAllFOC']['OverAllFOC']));


/* profit report */
        $tmpsheet->setCellValue('G1','Profit Report');
        $tmpsheet->setCellValue('G2','Total Outstanding Amount');
        $tmpsheet->setCellValue('H2',round($data['profitAmount'],2));
/* end profit report */

/*  expense report */
        $tmpsheet->setCellValue('A9','Expenses Report');
        $tmpsheet->setCellValue('A10','Over All Expense Amount');
        $tmpsheet->setCellValue('B10',$data['OverAllExp']['amount']);

/*  end expense report */

/*---- investor profit report --*/
        $tmpsheet->setCellValue('G9','Investor Profit Report');
        $tmpsheet->setCellValue('G10',$data['primaryInvestorName']);
        $tmpsheet->setCellValue('G11',$data['sec01InvestorName']);
        $tmpsheet->setCellValue('G12',$data['sec02InvestorName']);

        $tmpsheet->setCellValue('H10',round($data['primaryInvestorProfit'],2));
        $tmpsheet->setCellValue('H11',round($data['sec01InvestorProfit'],2));
        $tmpsheet->setCellValue('H12',round($data['sec02InvestorProfit'],2));


        $tmpwriter = new Xls($tmpspreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$tmpfileName.'.xls"'); 
        header('Cache-Control: max-age=0');
        $tmpwriter->save('php://output'); // download file
    }

    public function checkExcel(){
    if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Hello World !');

    //$writer = new Xlsx($spreadsheet);
    //$writer->save(FCPATH.'uploads/hello world.xlsx');
    //$this->render_template('excel/index', $this->data);
    $writer = new Csv($spreadsheet);
    $filename = 'name-of-the-generated-file';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename=ab.csv'); 
    header('Cache-Control: max-age=0');
    ob_end_clean();
    $writer->save('php://output'); // download file 
    }

}	
