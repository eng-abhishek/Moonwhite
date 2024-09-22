<?php  

defined('BASEPATH') OR exit('No direct script access allowed');

class Profitshare extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
        $this->session->set_userdata('report','1');
		$this->data['page_title'] = 'Profit Share';
		$this->load->model('model_profitshare');
		$this->load->model('model_investors');
	    $this->load->model('model_company');
		$this->data['date_format']=$this->model_company->get_currency_date_format();
	    $this->data['company_data'] = $this->model_company->getCompanyData(1);
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

		$parking_data = $this->model_profitshare->getProfitshareData($today_year);
		$expense_data = $this->model_profitshare->getExpenseData($today_year);
		$investors_data = $this->model_investors->getInvestorsData();
		
		$this->data['profit_years'] = $this->model_profitshare->getProfitshareYear();
		

	   //	$final_parking_data = array();
		// foreach ($parking_data as $k => $v) {
		// 	if(count($v) > 1) {
		// 		$total_amount_earned = array();
		// 		foreach ($v as $k2 => $v2) {
		// 			if($v2) {						
		// 				$total_amount_earned[] = $v2['net_amount'];						
		// 			}
		// 		}
		// 		$final_parking_data[$k] = array_sum($total_amount_earned);	
		// 	}
		// 	else {
		// 		$final_parking_data[$k] = 0;	
		// 	}
			
		// }

		// $final_exp_parking_data = array();
		// foreach ($expense_data as $kk => $vv) {		
		// 	if(count($vv) > 1) {
		// 		$total_exp_amount = array();
		// 		foreach ($vv as $k22 => $v22) {
		// 			if($v22) {						
		// 				$total_exp_amount[] = $v22['net_expense_amount'];						
		// 			}
		// 		}
		// 		$final_exp_parking_data[$kk] = array_sum($total_exp_amount);	
		// 	}
		// 	else {
		// 		$final_exp_parking_data[$kk] = 0;	
		// 	}
		// }
// echo"<pre>";
// print_r($parking_data);
// //print_r($final_exp_parking_data);
// die;
		$this->data['selected_year'] = $today_year;
		$this->data['company_currency'] = $this->company_currency();
		$this->data['results'] = $parking_data;
		// $this->data['results_expense'] = $final_exp_parking_data;
		$this->data['results_investors'] = array_reverse($investors_data);

		$this->render_template('profitshare/index', $this->data);
	}
}	