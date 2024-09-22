<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Expenses extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->data['page_title'] = 'Expenses';
		$this->load->library('session');
        $this->session->set_userdata('report','0');
		$this->load->model('model_expenses');
	    $this->load->model('model_company');
		$this->data['date_format']=$this->model_company->get_currency_date_format();
	    $this->date_format=$this->model_company->get_currency_date_format();
	    $this->data['company_data'] = $this->model_company->getCompanyData(1);
	}

	/* 
    * It only redirects to the manage Expenses page
    */
	public function index()
	{
		if(!in_array('viewExpenses', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->data['exp_code']=$this->model_expenses->getExpencesCode();
		$this->render_template('expenses/index', $this->data);	
	}

	/*
	* It retrieve the specific Expenses information via a Expenses id
	* and returns the data in json format.
	*/
	public function fetchExpensesDataById($id) 
	{
		if($id) {
			$data = $this->model_expenses->getExpensesData($id);
			echo json_encode($data);
		}
	}

	/*
	* It retrieves all the Expenses data from the database 
	* This function is called from the datatable ajax function
	* The data is return based on the json format.
	*/
	public function fetchExpensesData()
	{


		$result = array('data' => array());

		$data = $this->model_expenses->getExpensesData();

		// print_r("expression");
		// print_r($data);
		// print_r("expression");
		// exit;
		foreach ($data as $key => $value) {

			// button
			$buttons = '';

			if(in_array('updateExpenses', $this->permission)) {
				$buttons = '<button type="button" class="btn btn-default" onclick="editFunc('.$value['id'].')" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>';
			}

			if(in_array('deleteExpenses', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}

			$status = ($value['active'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

			if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
			$amt=$value['amount'].' '.$this->date_format['currency'];
			}else{
			$amt=$this->date_format['currency'].' '.$value['amount'];
			}

			$result['data'][$key] = array(
				$key+1,
				$value['code'],
				$value['title'],
				$amt,
				date($this->date_format['date_format'],strtotime($value['expense_date'])),
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
		if(!in_array('createExpenses', $this->permission)){
			redirect('dashboard', 'refresh');
		}
		$response = array();
		$this->form_validation->set_rules('code', 'Code', 'trim|required');
		$this->form_validation->set_rules('expense_amount', 'Expense Amount', 'trim|required');
		$this->form_validation->set_rules('expense_date', 'Expense Date', 'trim|required');
		$this->form_validation->set_rules('active', 'Active', 'trim|required');

		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

        if ($this->form_validation->run() == TRUE) {
        	$data = array(
        		//'name' => $this->input->post('expense_name'),
        		'amount' => $this->input->post('expense_amount'),
        		'expense_date' => date($this->date_format['date_format'],strtotime($this->input->post('expense_date'))),
        		'active' => $this->input->post('active'),
        		'code'=>$this->input->post('code'),
        	);

        	$create = $this->model_expenses->create($data);
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
		if(!in_array('updateExpenses', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		if($id) {

     		$this->form_validation->set_rules('edit_code', 'Code', 'trim|required');
			$this->form_validation->set_rules('edit_expense_amount', 'Expense Amount', 'trim|required');
			$this->form_validation->set_rules('edit_expense_date', 'Expense Date', 'trim|required');
			$this->form_validation->set_rules('edit_active', 'Active', 'trim|required');

			$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

	        if ($this->form_validation->run() == TRUE) {
	        	$data = array(
	        	//	'name' => $this->input->post('edit_expense_name'),
	        		'amount' => $this->input->post('edit_expense_amount'),
	        		'expense_date' => date($this->date_format['date_format'],strtotime($this->input->post('edit_expense_date'))),
	        		'active' => $this->input->post('edit_active'),
	        		'code'=>$this->input->post('edit_code'),
	        	);

	        	$update = $this->model_expenses->update($data, $id);
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
		if(!in_array('deleteExpenses', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		
		$expense_id = $this->input->post('expense_id');

		$response = array();
		if($expense_id) {
			$delete = $this->model_expenses->remove($expense_id);
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