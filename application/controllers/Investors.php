<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Investors extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
        $this->session->set_userdata('report','0');
		$this->not_logged_in();
		$this->data['page_title'] = 'Payment History';
		$this->load->model('model_investors');
		$this->load->model('model_company');
        $this->data['company_data'] = $this->model_company->getCompanyData(1);
        $this->data['date_format']=$this->model_company->get_currency_date_format();
	    $this->date_format=$this->model_company->get_currency_date_format();

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

		if(!in_array('viewInvestors', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$this->render_template('investors/index', $this->data);	
	}

	/*
	* It retrieve the specific Expenses information via a Expenses id
	* and returns the data in json format.
	*/
	public function fetchInvestorsDataById($id) 
	{
		if($id) {
			$data = $this->model_investors->getInvestorsData($id);
			echo json_encode($data);
		}
	}

	/*
	* It retrieves all the Expenses data from the database 
	* This function is called from the datatable ajax function
	* The data is return based on the json format.
	*/
	public function fetchInvestorsData()
	{
		$result = array('data' => array());

		$data = $this->model_investors->getInvestorsData();

		// print_r("expression");
		// print_r($data);
		// print_r("expression");
		// exit;
		foreach ($data as $key => $value) {

			// button
			$buttons = '';
            $btnURL=base_url('investors/payment_history/'.$value['id']);
			if(in_array('updateInvestors', $this->permission)) {
				$buttons = '<button type="button" class="btn btn-default" onclick="editFunc('.$value['id'].')" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>';
			}

			if(in_array('deleteInvestors', $this->permission)) {
			    $buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}
            $buttons .= '<a href="'.$btnURL.'"><button type="button" class="btn btn-default">Pay</button></a>';

			$status = ($value['active'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

			$result['data'][$key] = array(
				$value['name'],
				$value['percentage'],
				$value['type'],
				$status,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	/*
    * If the validation is not valid, then it provides the validation error on the json format
    * If the validation for each input is valid then it inserts the data into the database and 
    returns the appropriate message in the json format.
    */
	public function create()
	{
		if(!in_array('createInvestors', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		$this->form_validation->set_rules('investor_name', 'Investor Name', 'trim|required');
		$this->form_validation->set_rules('investor_percentage', 'Percentage', 'trim|required');
		$this->form_validation->set_rules('investor_type', 'Type', 'trim|required');
		$this->form_validation->set_rules('active', 'Active', 'trim|required');

		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

        if ($this->form_validation->run() == TRUE) {
        	$data = array(
        		'name' => $this->input->post('investor_name'),
        		'percentage' => $this->input->post('investor_percentage'),
        		'type' => $this->input->post('investor_type'),
        		'active' => $this->input->post('active'),	
        	);

        	$create = $this->model_investors->create($data);
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

	/*
    * If the validation is not valid, then it provides the validation error on the json format
    * If the validation for each input is valid then it updates the data into the database and 
    returns a n appropriate message in the json format.
    */
	public function update($id)
	{


		if(!in_array('updateInvestors', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		if($id) {
			$this->form_validation->set_rules('edit_investor_name', 'Investor Name', 'trim|required');
			$this->form_validation->set_rules('edit_investor_percentage', 'Percentage', 'trim|required');
			$this->form_validation->set_rules('edit_investor_type', 'Type', 'trim|required');
			$this->form_validation->set_rules('edit_active', 'Active', 'trim|required');

			$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

	        if ($this->form_validation->run() == TRUE) {
	        	$data = array(
	        		'name' => $this->input->post('edit_investor_name'),
	        		'percentage' => $this->input->post('edit_investor_percentage'),
	        		'type' => $this->input->post('edit_investor_type'),
	        		'active' => $this->input->post('edit_active'),	
	        	);

	        	$update = $this->model_investors->update($data, $id);
	        	if($update == true) {
	        		$response['success'] = true;
	        		$response['messages'] = 'Succesfully updated';
	        	}
	        	else {
	        		$response['success'] = false;
	        		$response['messages'] = 'Error in the database while update.';			
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

	/*
	* If checks if the Expenses id is provided on the function, if not then an appropriate message 
	is return on the json format
    * If the validation is valid then it removes the data into the database and returns an appropriate 
    message in the json format.
    */
	public function remove()
	{
		if(!in_array('deleteInvestors', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		
		$expense_id = $this->input->post('expense_id');

		$response = array();
		if($expense_id) {
			$delete = $this->model_investors->remove($expense_id);
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

	public function payBill(){
    $arr=array(
     'paid_date'=>date('Y-m-d h:i:s',strtotime($_POST['date'])),
     'paid_amount'=>$_POST['pay_amount'],
     'investor_id'=>$_POST['investor_id'],
    );
    $totalAmt=$this->model_investors->investorsTotalAmt($_POST['investor_id']);
    $receiveAmt=$this->model_investors->getSumOfReceiveAmount($_POST['investor_id']);
    $totalRemainingAmt=round($totalAmt,2)-round($receiveAmt['paid_amount'],2);
     // echo round($_POST['pay_amount'],2)."<br>";
     // echo $totalRemainingAmt;
    // die;

    $this->model_investors->payBill($arr);

    // if($_POST['pay_amount']>$totalRemainingAmt){
    // echo"101";
    // die;
    // }else{
    // $this->model_investors->payBill($arr);
    // }
    

    // print_r($_POST);
    //investorsTotalAmt()
    // echo 'Total='.$totalAmt.'<br>';
    // echo 'Receive ='.print_r($receiveAmt['paid_amount']);
    // $receiveAmt=$this->model_investors->getReceiveAmount($id);
    // $totalReceiveAmt=$this->model_investors->getSumOfReceiveAmount($id);
	}

	public function payment_history($id=''){
    if(!empty($id)){
    $id=$id;
    }else{
    $id=$this->input->post('investorID');
    } 
    
    if(!empty($this->input->post('select_year'))){
     $curent_year=$this->input->post('select_year');
    }else{
     $curent_year=date('Y');
    }
     

    $yearDropdown=$this->model_investors->getInvestorYear($id);
    if(!empty($yearDropdown)){
    $this->data['yearDropdown'] = $yearDropdown;
    }else{
    $this->data['yearDropdown'] = array(date('Y'));
    }
    // echo"<pre>";
    // print_r($this->data['yearDropdown']);
    // die;
    // $this->data['yearDropdown']=$this->model_investors->getInvestorYear($id);
    $this->data['curent_year']=$curent_year;
    $this->data['investorID']=$id;
    //$this->uri->segment(3);
    $this->data['forEachData']=$this->model_investors->getTotalOrderAmount($id,$curent_year);
    $this->data['receiveAmt']=$this->model_investors->getReceiveAmount($id,$curent_year);
    $this->data['totalReceiveAmt']=$this->model_investors->getSumOfReceiveAmount($id,$curent_year);
    $this->data['investor_name']=$this->model_investors->getInvestorName($id,$curent_year);
    $this->data['InvestorId']=$id;
    $this->render_template('investors/details',$this->data);
	}

    public function removeInvestor(){
		$expense_id = $this->input->post('id');
       // $this->model_investors->removeInvestor($expense_id);

		$response = array();
		if($expense_id) {
			$delete = $this->model_investors->removeInvestor($expense_id);
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

}