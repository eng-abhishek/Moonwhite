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

		$this->data['date_format']=$this->model_company->get_currency_date_format();
    	$this->data['company_data'] = $this->model_company->getCompanyData(1);
        $this->load->library('pdf');
	}

	    public function year()
    {
        return array('2021','2022', '2023', '2024', '2025', '2026', '2027', '2028', '2029', '2030', '2031', '2032', '2033');   
    }

public function pdfdetails()
 {
 
   $customer_id = '4';
   $html_content = '<h3 align="center">Convert HTML to PDF in CodeIgniter using Dompdf</h3>';
   $html_content .= '<h1>Hello</h1>';
   $this->pdf->loadHtml($html_content);
   $this->pdf->render();
   $this->pdf->stream("".$customer_id.".pdf", array("Attachment"=>0));
  
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
		$expense_data = $this->model_profit->getExpenseData($today_year);
   
		$this->data['profit_years'] = array_reverse($this->model_profit->getProfitYear());

		$final_parking_data = array();
		foreach ($parking_data as $k => $v) {	
			if(count($v) > 1) {
				$total_amount_earned = array();
				foreach ($v as $k2 => $v2) {
					if($v2) {						
						$total_amount_earned[] = $v2['net_amount'] - $v2['org_amount'];						
					}
				}
				$final_parking_data[$k] = round(array_sum($total_amount_earned),2);	
			}
			else {
				$final_parking_data[$k] = 0;	
			}
			
		}

$final_exp_parking_data = array();
		foreach ($expense_data as $kk => $vv) {		
			if(count($vv) > 1) {
				$total_exp_amount = array();
				foreach ($vv as $k22 => $v22) {
					if($v22) {						
						$total_exp_amount[] = $v22['net_expense_amount'];						
					}
				}
				$final_exp_parking_data[$kk] = array_sum($total_exp_amount);	
			}
			else {
				$final_exp_parking_data[$kk] = 0;	
			}
			
		}
// echo"<pre>";
// print_r($final_parking_data);
// die;


		//  print_r($final_parking_data);
		// echo "<br/>";
		// print_r($final_exp_parking_data);
		// exit;
		//print_r("expenses : "+ $final_exp_parking_data);
		//$returned_total_profit_amount = ((int)$final_parking_data - (int)$final_exp_parking_data);

		//exit;
		$this->data['selected_year'] = $today_year;
		$this->data['company_currency'] = $this->company_currency();
		$this->data['results'] = $final_parking_data;
		$this->data['results_expense'] = $final_exp_parking_data;
//         echo"<pre>";
//         print_r($this->data);
// die;
		$this->render_template('profit/index', $this->data);
	}
}	