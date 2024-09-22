<?php 

class Model_profitshare extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/*getting the total months*/
	private function months()
	{
		return array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');   
	}

	/* getting the year of the orders */
	public function getProfitshareYear()
	{
		$sql = "SELECT * FROM `orders` WHERE paid_status = ?";
		$query = $this->db->query($sql, array(1));
		$result = $query->result_array();
		
		$return_data = array();
		foreach ($result as $k => $v) {
			$date = date('Y', strtotime($v['date_time']));
			$return_data[] = $date;
		}

		$return_data = array_unique($return_data);

		return $return_data;
	}

	// getting the order reports based on the year and moths
	public function getProfitshareData($year)
	{

        if($year) {
     	$months_re = $this->months();
        if($year==date('Y')){
        $current_month= date('m');
        }else{
        $current_month= '12';
        }

		$re=array();
		for($i=0;$i<count($months_re);$i++){
        if($months_re[$i]==$current_month OR $months_re[$i]<=$current_month){
        $re[]=$months_re[$i];
        }
		}

         $months=$re;
		
			$profit = array();
		
			foreach ($months as $month_k => $month_y) {

		$sql="SELECT SUM(`order_profit`) as net_amount,date_time From `orders` where YEAR(`date_time`) ='".$year."' AND MONTH(`date_time`)='".$month_y."' AND paid_status=1";			
	    $query = $this->db->query($sql);
		$result = $query->row_array();

        //$profit['profit'][$month_k]=$result;

         $sql2 = "SELECT sum(`amount`) as net_expense_amount,expense_date FROM `expenses` WHERE YEAR(`expense_date`)='".$year."' AND  MONTH(`expense_date`)='".$month_y."' AND active = 1";
			
		$query2 = $this->db->query($sql2);
		$result2 = $query2->row_array();
      
		    	$month_year='';
				$get_mon_year = $year.'-'.$month_y;	

					if(!empty($result['date_time'])){
					$month_year = date('Y-m', strtotime($result['date_time']));
					}

					if(!empty($result2['expense_date'])){
					$month_year = date('Y-m', strtotime($result2['expense_date']));
					}

				if( ($get_mon_year == $month_year) || ($get_mon_year == $month_year_ex) ) {
			
				
        $profit[$get_mon_year]['actual_profit']=$result['net_amount']-$result2['net_expense_amount'];

         $profit[$get_mon_year]['expense']=$result2['net_expense_amount'];

					}else{
		$profit[$get_mon_year]['actual_profit']=0;

         $profit[$get_mon_year]['expense']=0;

					}
		
		 }

			 // echo"<pre>";
    //         print_r($profit);
    //         die;

			return $profit;			
		}
	}

	// getExpenseData based on the year and moths
	public function getExpenseData($year)
	{
		
		if($year) {
     	$months_re = $this->months();
        if($year==date('Y')){
        $current_month= date('m');
        }else{
        $current_month= '12';
        }
		$re=array();
		for($i=0;$i<count($months_re);$i++){
        if($months_re[$i]==$current_month OR $months_re[$i]<=$current_month){
        $re[]=$months_re[$i];
        }
		}

         $months=$re;

			$sql = "SELECT e.amount as net_expense_amount, e.expense_date  FROM `expenses` e WHERE YEAR(e.`expense_date`)='".$year."' AND e.active = ?";
			
			
			$query = $this->db->query($sql, array(1));
			$result = $query->result_array();

			// print_r($result);
			// exit;

			$expense_final_data = array();
			foreach ($months as $month_k => $month_y) {
				$get_mon_year = $year.'-'.$month_y;	

				$expense_final_data[$get_mon_year][] = '';
				foreach ($result as $k => $v) {
					$date_time = strtotime($v['expense_date']);
					$month_year = date('Y-m', $date_time);

					if($get_mon_year == $month_year) {
						$expense_final_data[$get_mon_year][] = $v;
					}
				}
			}	
			// print_r($expense_final_data);
			// exit;
			return $expense_final_data;			
		}
	}

}