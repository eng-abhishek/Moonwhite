<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();
        $this->load->library('session');
        $this->session->set_userdata('report','0');
		$this->data['page_title'] = 'Company';
            $this->load->model('model_company');
        $this->data['company_data'] = $this->model_company->getCompanyData(1);
		$this->load->model('model_company');
	}

    /* 
    * It redirects to the company page and displays all the company information
    * It also updates the company information into the database if the 
    * validation for each input field is successfully valid
    */
	public function index()
	{
        if(!in_array('updateCompany', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        
		$this->form_validation->set_rules('company_name', 'Company name', 'trim|required');
		$this->form_validation->set_rules('service_charge_value', 'Charge Amount', 'trim|integer');
		$this->form_validation->set_rules('vat_charge_value', 'Vat Charge', 'trim|integer');
		$this->form_validation->set_rules('address', 'Address', 'trim|required');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');

        $this->form_validation->set_rules('country', 'country', 'trim|required');
        $this->form_validation->set_rules('ar_country', 'Ar country', 'trim|required');
        $this->form_validation->set_rules('ar_address', 'Ar Address', 'trim|required');
        $this->form_validation->set_rules('ar_company_name', 'Ar Company name', 'trim|required');
	
        if ($this->form_validation->run() == TRUE) {
            // true case
        	$data = array(
        		'company_name' => $this->input->post('company_name'),
        		'service_charge_value' => $this->input->post('service_charge_value'),
        		'vat_charge_value' => $this->input->post('vat_charge_value'),
        		'address' => $this->input->post('address'),
        		'phone' => $this->input->post('phone'),
        		'country' => $this->input->post('country'),
        		'message' => $this->input->post('message'),
                'currency' => $this->input->post('currency'),
                'date_format' => $this->input->post('date_format'),
                'capital'=>$this->input->post('capital'),

                'ar_country' => $this->input->post('ar_country'),
                'ar_address' => $this->input->post('ar_address'),
                'ar_company_name' => $this->input->post('ar_company_name'),
                'ar_phone' => $this->input->post('ar_phone'),
                'email'=>$this->input->post('email'),
                );
            
        	$update = $this->model_company->update($data, 1);
        	if($update == true) {
        		$this->session->set_flashdata('success', 'Successfully created');
        		redirect('company/', 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('company/index', 'refresh');
        	}
        }
        else {

            // false case
            $this->data['currency_symbols'] = $this->currency();
        	$this->data['company_data'] = $this->model_company->getCompanyData(1);
            $this->data['date_format']=$this->model_company->getDateFormat();
            // echo"<pre>";      
            // print_r($this->data['date_format']);
            // die;
			$this->render_template('company/index', $this->data);			
        }		
	}

    

}