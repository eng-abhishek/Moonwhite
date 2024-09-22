<?php

defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(1);

class Commercialinvoice extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->load->library('session');
        $this->session->set_userdata('report','0');
        $this->not_logged_in();
		$this->data['page_title'] = 'Commercial Invoice';
		$this->load->model('model_comerisal');
	    $this->load->model('model_company');
		$this->data['date_format']=$this->model_company->get_currency_date_format();
	    $this->date_format=$this->model_company->get_currency_date_format();
	    $this->data['company_data'] = $this->model_company->getCompanyData(1);
	}


   public function year()
    {
        return array('2022', '2023', '2024', '2025', '2026', '2027', '2028', '2029', '2030', '2031', '2032', '2033');   
    }

	/* 
    * It only redirects to the manage Expenses page
    */
	public function index()
	{
		// echo"<pre>"; 
		// print_r($this->permission);
		// die;
		if(!in_array('viewComInvoice', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
 	 // echo"<pre>";
       $arr=array();
       $total_balance=$this->model_comerisal->getComerisalInvoice();
       // print_r($total_balance);
       //  die;
       foreach ($total_balance as $key => $balence_val){
       // print_r($balence_val);
       // echo round($balence_val['total_balance'],2);
       // echo"<pre>"; 
       // print_r($balence_val);
       //  die;
       $paidAmt=$this->model_comerisal->getPaidAmountOfInvoice($balence_val['id']);
       $actualBalenceData=$balence_val['total_balance']-$paidAmt['paid_amount'];
       $actualBalence=round($actualBalenceData,2);
       if($paidAmt['paid_amount']>0){
         $paid_amt=$paidAmt['paid_amount'];
       }else{
         $paid_amt='0';
       }
       if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
			$act_balence=$actualBalence.' '.$this->date_format['currency'];
            $totalBalance= round($balence_val['total_balance'],2).' '.$this->date_format['currency'];
            $paidAmtVal=round($paid_amt,2).' '.$this->date_format['currency'];
			}else{
			$act_balence=$this->date_format['currency'].' '.$actualBalence;
			$totalBalance= $this->date_format['currency'].' '.round($balence_val['total_balance'],2);
			$paidAmtVal=$this->date_format['currency'].' '.round($paid_amt,2);
			}
		
       $arr[]=array(
       'id'=>$balence_val['id'],
       'total_balance'=>$totalBalance,
       'avaliable_balence'=>$act_balence,
       'invoice_no'=>$balence_val['invoice_no'],
       'delivery_date'=>date($this->date_format['date_format'],strtotime($balence_val['delivery_date'])),
       'paid_amount'=>$paidAmtVal,
       );
       }
	    $this->data['comerisal_invoice']=$arr;
	    $this->data['factory']=$this->model_comerisal->getallfactory();
		$this->render_template('comerisal_invoice/index', $this->data);	
	}

	public function payment_history($invoice_no=''){
    if(!in_array('viewComInvoice', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		
    if(!empty($invoice_no)){
     $invoice_no=$invoice_no;
     }else{
     $invoice_no=$this->input->post('invoice_no');
     }

     $year=$this->input->post('year'); 
    
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

    $paidAmt=$this->model_comerisal->getPaidAmountOfInvoice(base64_decode($invoice_no),$year);
    $this->data['data']=$this->model_comerisal->getProductList(base64_decode($invoice_no),$year);
    $this->data['paidAmt']=$this->model_comerisal->getPaidAmount(base64_decode($invoice_no),$year);
   
    $invoicenoData=$this->model_comerisal->getinvoiceno(base64_decode($invoice_no),$year);
    // print_r($invoicenoData);
    // die;
    $this->data['invoice_no']=$invoicenoData['invoice_no'];
    $this->data['invoice_ID']=base64_decode($invoice_no);
    $this->data['paid_amount']=$paidAmt['paid_amount'];
    $this->render_template('comerisal_invoice/payment_status', $this->data);	
	}
	
	public function payBill(){
    // echo $_POST['invoice_no'];
    // die;
    $payStatus=$this->model_comerisal->checkPandingAmount($_POST['invoice_no']);
    $billAmt=$this->model_comerisal->checkTotalPayAmt($_POST['invoice_no']);
    // echo 'Paid'.$payStatus['paid_amount'];
    // echo 'Need To Paid'.$billAmt['total_billAmt'];
    // die;
    $remainingAmt=$billAmt['total_billAmt']-$payStatus['paid_amount'];
    //echo round($_POST['pay_amount'],2);=
    //echo $remainingAmt;
    if(round($_POST['pay_amount'],2)>round($remainingAmt,2)){
    echo"101";
    die;
	}else{
     $arr=array(
    'paid_date' =>date('Y-m-d h:i:s',strtotime($_POST['date'])),
    'paid_amount' =>$_POST['pay_amount'],
    'invoice_no' =>$_POST['invoice_no'],
    );
    $this->model_comerisal->pay_amount($arr);
    
	}
	}
	/*
	* It retrieve the specific Expenses information via a Expenses id
	* and returns the data in json format.
	*/

	public function fetchInvoiceDataById($id) 
	{
		if($id) {
			$data = $this->model_comerisal->getComericalData($id);
			echo json_encode($data);
		}
	}

	/*
	* It retrieves all the Expenses data from the database 
	* This function is called from the datatable ajax function
	* The data is return based on the json format.
	*/
	
	/*
    * If the validation is not valid, then it provides the validation error on the json format
    * If the validation for each input is valid then it inserts the data into the database and 
    returns the appropriate message in the json format.
    */


	public function create()
	{
		if(!in_array('createComInvoice', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$response = array();
		//$this->form_validation->set_rules('expense_name', 'Expense Name', 'trim|required');
			$this->form_validation->set_rules('factory', 'Factory', 'trim|required');
		$this->form_validation->set_rules('invoice_no', 'Invoice No', 'trim|required|is_unique[commercial_invoice_info.invoice_no]');
		$this->form_validation->set_rules('delivery_date', 'Delivery date', 'trim|required');

		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

        if ($this->form_validation->run() == TRUE) {
        $data=array(
				'factory_id'=>$this->input->post('factory'),
				'invoice_no'=>$this->input->post('invoice_no'),
				'delivery_date'=>date('Y-m-d h:i:s',strtotime($this->input->post('delivery_date'))),
    	           );

             $create = $this->model_comerisal->create($data);
        	if($create == true) {
        		$response['success'] = true;
        		$response['messages'] = 'Succesfully created';
        	}
        	else {
        		$response['success'] = false;
        		$response['messages'] = 'Error in the database while creating';			
        	}
        }
        else {
        	$response['success'] = false;
        	foreach ($_POST as $key => $value) {
        		$response['messages'][$key] = form_error($key);
        	}
        }
        echo json_encode($response);
	}

	public function update($id)
	{
		if(!in_array('updateComInvoice', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		if($id) {
			
        if($this->input->post('edit_invoice_no') != $this->input->post('old_invoice_no')) {
		$is_unique =  '|is_unique[commercial_invoice_info.invoice_no]';
		} else {
		$is_unique =  '';
		}

		$this->form_validation->set_rules('edit_factory', 'Factory', 'trim|required');
		$this->form_validation->set_rules('edit_invoice_no', 'Invoice No', 'trim|required'.$is_unique);
		$this->form_validation->set_rules('edit_delivery_date', 'Delivery date', 'trim|required');
		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');


	    if ($this->form_validation->run() == TRUE) {
	        	$data = array(
	     'factory_id'=>$this->input->post('edit_factory'),
		'invoice_no'=>$this->input->post('edit_invoice_no'),
		'delivery_date'=>date('Y-m-d h:i:s',strtotime($this->input->post('edit_delivery_date'))),
	        	);

	        	$update = $this->model_comerisal->update($data, $id);
	        	if($update == true) {
	        		$response['success'] = true;
	        		$response['messages'] = 'Succesfully updated';
	        	}
	        	else {
	        		$response['success'] = false;
	        		$response['messages'] = 'Error in the database while updated the brand information';			
	        	}
	        }
	        else {
	        	$response['success'] = false;
	        	foreach ($_POST as $key => $value) {
	        		$response['messages'][$key] = form_error($key);
	        	}
	        }
		}
		else {
			$response['success'] = false;
    		$response['messages'] = 'Error please refresh the page again!!';
		}

		echo json_encode($response);
	}

	public function remove()
	{
		if(!in_array('deleteComInvoice', $this->permission)) {
			redirect('dashboard', 'refresh');
		}	
		$asset_id = $this->input->post('id');
		$response = array();
		if($asset_id) {
			$delete = $this->model_comerisal->remove($asset_id);
			if($delete == true) {
				$response['success'] = true;
				$response['messages'] = "Successfully removed";	
			}
			else {
				$response['success'] = false;
				$response['messages'] = "Error in the database while removing the brand information";
			}
		}
		else {
			$response['success'] = false;
			$response['messages'] = "Refersh the page again!!";
		}

		echo json_encode($response);
	}

        public function check_invoice_no_exist(){
		if($this->model_comerisal->checkDuplicateInvoice()>0){
        echo"false";
	    }else{
        echo"true";
	    }
		}

		public function check_invoice_no_exist_on_edit(){
        
        if($_POST['oldinvoice']==$_POST['invoice_no']){
         echo"true";
         die;
        }elseif($this->model_comerisal->checkDuplicateInvoice()>0){
         echo"101";
         die;
        }else{
         echo"true";
         die; 
        }
        // if($this->model_comerisal->checkDuplicateInvoiceOnEdit()>0){
        // echo"true";
        // die; 
        // }elseif($this->model_comerisal->checkDuplicateInvoice()>0){
        // echo"false";
        // die; 
        // }else{
        // echo"true";
        // die; 
        // }

		}



}