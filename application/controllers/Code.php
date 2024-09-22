<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Code extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->load->library('session');
        $this->session->set_userdata('report','0');
		$this->data['page_title'] = 'Code';
		$this->load->model('model_code');
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
		if(!in_array('viewCode', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->render_template('code/index', $this->data);	
	}

	/*
	* It retrieve the specific Expenses information via a Expenses id
	* and returns the data in json format.
	*/
	public function fetchCodeDataById($id) 
	{
		if($id) {
			$data = $this->model_code->getCodeData($id);
			echo json_encode($data);
		}
	}

	/*
	* It retrieves all the Expenses data from the database 
	* This function is called from the datatable ajax function
	* The data is return based on the json format.
	*/
	public function fetchCodeData()
	{
		$result = array('data' => array());

		$data = $this->model_code->getCodeData();

		foreach ($data as $key => $value) {
			// button
		 $buttons = '';
		 if(in_array('updateCode', $this->permission)) {
         $buttons = '<button type="button" class="btn btn-default" onclick="editFunc('.$value['id'].')" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>';
		 			}
         
			$result['data'][$key] = array(
				$key+1,
				$value['title'],
				$value['code'],
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

		if(!in_array('createAsset', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();
		$this->form_validation->set_rules('code', 'Code', 'trim|required|is_unique[expences_code.code]');
		$this->form_validation->set_rules('active', 'Active', 'trim|required');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');
        if ($this->form_validation->run() == TRUE) {
        	$data = array(
        		'code' => $this->input->post('code'),
        		'active' => $this->input->post('active'),
        	    'title'=>$this->input->post('title'),
        	);

        	$create = $this->model_code->create($data);
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
       
		if(!in_array('updateAsset', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		
		$response = array();
		if($id) {
		$this->form_validation->set_rules('edit_active', 'Active', 'trim|required');
	    $this->form_validation->set_rules('edit_title', 'Title', 'trim|required');

        if($this->input->post('edit_code') != $this->input->post('exist_code')){
        
         $is_unique =  '|is_unique[expences_code.code]';
         }else{
         $is_unique = "";
        }

	    $this->form_validation->set_rules('edit_code', 'Code', 'trim|required'.$is_unique);
	    $this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');
	        if ($this->form_validation->run() == TRUE) {
	        	$data = array(
	        	'code' => $this->input->post('edit_code'),
        		'active' => $this->input->post('edit_active'),
        		'title'=>$this->input->post('edit_title'),
	        	);

	        	$update = $this->model_code->update($data, $id);
	        	if($update == true) {
                 // echo"Successfully";
                 // die;  		
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
		if(!in_array('deleteAsset', $this->permission)) {
			redirect('dashboard', 'refresh');
		}		
		$asset_id = $this->input->post('asset_id');
		$response = array();
		if($asset_id) {
			$delete = $this->model_code->remove($asset_id);
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