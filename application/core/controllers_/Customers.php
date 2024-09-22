<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Customers - Dashboard';
        $this->load->library('session');
        $this->session->set_userdata('report','0');
		$this->load->model('model_customers');
	}

	/* 
    * It only redirects to the manage stores page
    */
	public function index()
	{
		if(!in_array('viewCustomers', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$this->render_template('customers/index', $this->data);	
	}

	/*
	* It retrieve the specific store information via a store id
	* and returns the data in json format.
	*/
	public function fetchCustomersDataById($id) 
	{
		if($id) {
			$data = $this->model_customers->getCustomersData($id);
			echo json_encode($data);
		}
	}

	/*
	* It retrieves all the store data from the database 
	* This function is called from the datatable ajax function
	* The data is return based on the json format.
	*/
	public function fetchCustomersData()
	{
		$result = array('data' => array());

		$data = $this->model_customers->getCustomersData();

		foreach ($data as $key => $value) {
           
            if($value['email']==''){
            $email="N/A";             
            }else{
            $email=$value['email'];
            }

			$address = $value['address'] == "" ? "-" : $value['address'];
			$contact = ($value['contact'] == 0 || $value['contact'] == "")  ? "-" : $value['contact'];
			$area = $value['area'] == ""  ? "-" : $value['area'];
			$trn = ($value['trn'] == 0 || $value['trn'] == "")  ? "-" : $value['trn'];
			// button
			$buttons = '';

			if(in_array('updateCustomers', $this->permission)) {
				$buttons = '<button type="button" class="btn btn-default" onclick="editFunc('.$value['id'].')" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>';
			}

			if(in_array('deleteCustomers', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}

			$status = ($value['active'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

			$result['data'][$key] = array(
				$value['name'],
			    $value['code'],
				$address,
				$contact,
				$email,
				$area,
				$trn,
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
		if(!in_array('createCustomers', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();
		$this->form_validation->set_rules('customer_name', 'Customer name', 'trim|required');
		$this->form_validation->set_rules('customer_address', 'Customer Address', 'trim|required');
		$this->form_validation->set_rules('customer_contact', 'Customer Contact', 'trim|required');
		$this->form_validation->set_rules('customer_area', 'Customer Area', 'trim|required');
		$this->form_validation->set_rules('customer_trn', 'Customer TRN', 'trim|required');
		$this->form_validation->set_rules('active', 'Active', 'trim|required');

		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

        if ($this->form_validation->run() == TRUE) {
        	$data = array(
        		'name' => $this->input->post('customer_name'),
        		'address' => $this->input->post('customer_address'),
        		'contact' => $this->input->post('customer_contact'),	
        		'email'=>$this->input->post('email'),
        		'area' => $this->input->post('customer_area'),
        		'trn' => $this->input->post('customer_trn'),
        		'active' => $this->input->post('active'),
        		//'code'=>strtotime(date("Y-m-d h:i:s")),
        	             );
        	$create = $this->model_customers->create($data);

        	if($create == true) {
        		$response['success'] = true;
        		$response['messages'] = 'Succesfully created';
        	}
        	else {
        		$response['success'] = false;
        		$response['messages'] = 'Error in the database while creating the brand information';			
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
		if(!in_array('updateCustomers', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		if($id) {
			$this->form_validation->set_rules('edit_customer_name', 'Customer name', 'trim|required');
			$this->form_validation->set_rules('edit_customer_address', 'Customer Address', 'trim|required');
			$this->form_validation->set_rules('edit_customer_contact', 'Customer Contact', 'trim|required');
			$this->form_validation->set_rules('edit_customer_area', 'Customer Area', 'trim|required');
			$this->form_validation->set_rules('edit_customer_trn', 'Customer TRN', 'trim|required');
			$this->form_validation->set_rules('edit_active', 'Active', 'trim|required');



			$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

	        if ($this->form_validation->run() == TRUE) {
	        	$data = array(
	        		'name' => $this->input->post('edit_customer_name'),
	        		'address' => $this->input->post('edit_customer_address'),
	        		'contact' => $this->input->post('edit_customer_contact'),	
	        		'area' => $this->input->post('edit_customer_area'),
	        		'trn' => $this->input->post('edit_customer_trn'),	
	        		'active' => $this->input->post('edit_active'),
	        		'email'=>$this->input->post('email'),
	        		// 'code'=>"1000".$id,
	        	);

	        	$update = $this->model_customers->update($data, $id);
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

	/*
	* If checks if the store id is provided on the function, if not then an appropriate message 
	is return on the json format
    * If the validation is valid then it removes the data into the database and returns an appropriate 
    message in the json format.
    */
	public function remove()
	{
		if(!in_array('deleteCustomers', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		
		$customer_id = $this->input->post('customer_id');

		$response = array();
		if($customer_id) {
			$delete = $this->model_customers->remove($customer_id);
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