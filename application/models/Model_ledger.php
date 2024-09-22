<?php 

class Model_ledger extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}


	protected $table1 = 'orders';
	protected $table2 = 'expenses';

	public function getLegerData(){

// print_r($_POST);
// die;

	if(empty($_POST['fromDate'] || $_POST['toDate'])){
    
    return $this->db->query(" select * from ((select orders.id as id,customers.name as particulars,orders.net_amount as credit,NULL AS debit,orders.date_time as date from `orders` JOIN customers ON customers.id = orders.cust_id where orders.paid_status=1) UNION ALL (select expenses.id as id,expences_code.title as particulars,NULL as credit,expenses.amount as debit,expenses.expense_date as date from expenses JOIN expences_code ON expences_code.code = expenses.code )) results ORDER BY date DESC ")->result_array();

	}else{
    $fromDate=date(MY_DATE_FORMAT,strtotime($_POST['fromDate']));  
    $toDate=date(MY_DATE_FORMAT,strtotime($_POST['toDate']));	
    return $this->db->query(" select * from ((select orders.id as id,customers.name as particulars,orders.net_amount as credit,NULL AS debit,orders.date_time as date from `orders` JOIN customers ON customers.id = orders.cust_id where orders.paid_status=1) UNION ALL (select expenses.id as id,expences_code.title as particulars,NULL as credit,expenses.amount as debit,expenses.expense_date as date from expenses JOIN expences_code ON expences_code.code = expenses.code)) results  where date >= '$fromDate' AND date <='$toDate' ORDER BY date DESC ")->result_array();

	}
    }    

}