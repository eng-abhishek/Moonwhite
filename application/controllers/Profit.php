<?php  

defined('BASEPATH') OR exit('No direct script access allowed');

class Profit extends Admin_Controller 
{	
	public function __construct()
	{
		parent::__construct();
		$this->data['page_title'] = 'Profit';
		$this->load->library('session');
        $this->session->set_userdata('report','1');
		$this->load->model('model_profit');
		$this->load->model('model_company');
        $this->not_logged_in();
		$this->data['date_format']=$this->model_company->get_currency_date_format();
    	$this->data['company_data'] = $this->model_company->getCompanyData(1);
        $this->load->library('pdf');
	}

	public function year()
    {
        return array('2021','2022', '2023', '2024', '2025', '2026', '2027', '2028', '2029', '2030', '2031', '2032', '2033');   
    }


	/* 
    * It redirects to the report page
    * and based on the year, all the orders data are fetch from the database.
    */
	public function index()
	{
		
		$today_year = date('Y');

		if($this->input->post('select_year')) {
			$today_year = $this->input->post('select_year');
		}

		$parking_data = $this->model_profit->getProfitData($today_year);
	
		$this->data['profit_years'] = array_reverse($this->model_profit->getProfitYear());
		$this->data['selected_year'] = $today_year;
		$this->data['company_currency'] = $this->company_currency();
		$this->data['results'] = $parking_data;
		
		$this->render_template('profit/index', $this->data);
	}
}	