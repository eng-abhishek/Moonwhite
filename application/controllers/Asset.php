<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Asset extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->load->library('session');
        $this->session->set_userdata('report','0');
		$this->data['page_title'] = 'Assets';
		$this->load->model('model_asset');
	    $this->load->model('model_company');
	    $this->load->model('model_expenses');
		$this->data['date_format']=$this->model_company->get_currency_date_format();
	    $this->date_format=$this->model_company->get_currency_date_format();
	    $this->data['company_data'] = $this->model_company->getCompanyData(1);
	}

	/* 
    * It only redirects to the manage Expenses page
    */
	public function index()
	{
    // $this->data['company_data'] = $this->model_company->getCompanyData(1);
    // print_r($this->data['company_data']['company_name']);
    // die;
		if(!in_array('viewAsset', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->data['exp_code']=$this->model_expenses->getExpencesCode();
		$this->render_template('asset/index', $this->data);	
	}

	/*
	* It retrieve the specific Expenses information via a Expenses id
	* and returns the data in json format.
	*/
	public function fetchAssetDataById($id) 
	{
		if($id) {
			$data = $this->model_asset->getAssetData($id);
			echo json_encode($data);
		}
	}

	/*
	* It retrieves all the Expenses data from the database 
	* This function is called from the datatable ajax function
	* The data is return based on the json format.
	*/
	public function fetchAssetData()
	{
		$result = array('data' => array());

		$data = $this->model_asset->getAssetData();

		// print_r($data);
	
		// exit;
		foreach ($data as $key => $value) {

			// button
			$buttons = '';

			if(in_array('updateAsset', $this->permission)) {
				$buttons = '<button type="button" class="btn btn-default" onclick="editFunc('.$value['id'].')" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>';
			}

			if(in_array('deleteAsset', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}

			$status = ($value['active'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';


			if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
			$amt=$value['value'].' '.$this->date_format['currency'];
			}else{
			$amt=$this->date_format['currency'].' '.$value['value'];
			}
			if($value['asset_type']=='asset'){
			$asset="Asset";
			}elseif($value['asset_type']=='loan'){
			$asset="Loan";	
			}else{
		    $asset="Current Asset";	
			}

          if(!empty($value['asset_date'])){
          $date=date($this->date_format['date_format'],strtotime($value['asset_date']));
          }else{
          $date='';
          }
			
			$result['data'][$key] = array(
				$value['code'],
				$value['title'],
				$asset,
				$amt,
                $date,
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
		if(!in_array('createAsset', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();
		
		//$this->form_validation->set_rules('asset_name', 'Asset Name', 'trim|required');
		$this->form_validation->set_rules('asset_value', 'Asset Value', 'trim|required');
		$this->form_validation->set_rules('active', 'Active', 'trim|required');
		$this->form_validation->set_rules('code', 'Asset Code', 'trim|required');
		$this->form_validation->set_rules('asset_type', 'Asset Type', 'trim|required');
		$this->form_validation->set_rules('asset_date', 'Date', 'trim|required');

		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

        if ($this->form_validation->run() == TRUE) {
        	$data = array(
        		//'name' => $this->input->post('asset_name'),
        		'value' => $this->input->post('asset_value'),
        		'active' => $this->input->post('active'),
        		'asset_type' => $this->input->post('asset_type'),
        		'code'=>$this->input->post('code'),
        	    'asset_date'=>date('Y-m-d H:i:s',strtotime($this->input->post('asset_date'))),
        	);

        	$create = $this->model_asset->create($data);
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
		// $name = $this->input->post('edit_asset_name');
		// $value = $this->input->post('edit_asset_amount');
		// $active = $this->input->post('edit_active');
  //       echo $name;
  //       echo $value;
  //       echo $active;
  //       die;
		if(!in_array('updateAsset', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		if($id) {
			//$this->form_validation->set_rules('edit_asset_name', 'Asset Name', 'trim|required');
			$this->form_validation->set_rules('edit_asset_amount', 'Asset Value', 'trim|required');
			$this->form_validation->set_rules('edit_active', 'Active', 'trim|required');
			$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

		$this->form_validation->set_rules('edit_code', 'Asset Code', 'trim|required');
		$this->form_validation->set_rules('edit_asset_type', 'Asset Type', 'trim|required');
		$this->form_validation->set_rules('edit_asset_date', 'Date', 'trim|required');

	        if ($this->form_validation->run() == TRUE) {
	        	$data = array(
	        	//'name' => $this->input->post('edit_asset_name'),
	        		'value' => $this->input->post('edit_asset_amount'),
	        		'active' => $this->input->post('edit_active'),
	        		'asset_type' => $this->input->post('edit_asset_type'),
	        	    'asset_date'=>date('Y-m-d H:i:s',strtotime($this->input->post('edit_asset_date'))),
	        	    'code'=>$this->input->post('edit_code'),
	        	);

	        	$update = $this->model_asset->update($data, $id);
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
			$delete = $this->model_asset->remove($asset_id);
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


	public function mail(){

	$this->load->library('email');
	$this->email->from('abhisheksoni78655@gmail.com', 'Your Name');
	$this->email->to('kipm1engg@gmail.com');
	$this->email->subject('Email Test');
	$this->email->message('Testing the email class.');
	$this->email->send();

	$config = Array(
	'protocol' => 'smtp',
	'smtp_host' => 'ssl://smtp.googlemail.com',
	'smtp_port' => 465,
	'smtp_user' => 'abhisheksoni78655@gmail.com', // change it to yours
	'smtp_pass' => '7071310320', // change it to yours
	'mailtype' => 'html',
	'charset' => 'iso-8859-1',
	'wordwrap' => TRUE
	);

	$message = '';
	$this->load->library('email', $config);
	$this->email->set_newline("\r\n");
	$this->email->from('abhisheksoni78655@gmail.com'); // change it to yours
	$this->email->to('kipm1engg@gmail.com');// change it to yours
	$this->email->subject('Resume from JobsBuddy for your Job posting');
	$this->email->message($message);
	if($this->email->send())
	{
	echo 'Email sent.';
	}
	else
	{
	show_error($this->email->print_debugger());
	}


	}

}