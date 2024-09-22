<?php 

class Model_investors extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}


  public function getInvestorYear($id)
  {
    $sql = "SELECT * FROM `investor_receive_amount` where investor_id=".$id." ";
    $query = $this->db->query($sql);
    $result = $query->result_array();
    
    $return_data = array();
    foreach ($result as $k => $v) {
      $date = date('Y', strtotime($v['paid_date']));
      $return_data[] = $date;
    }

    $return_data = array_unique($return_data);

    return $return_data;
  }

	private function months()
	{
		return array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');   
	}

	/* get active brand infromation */
	public function getActiveInvestors()
	{
		$sql = "SELECT * FROM `investors` WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/* get the brand data */
	public function getInvestorsData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM `investors` WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM `investors`";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('investors', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('investors', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('investors');
			return ($delete == true) ? true : false;
		}
	}

	public function getTotalOrderAmount($id,$year){
	$months_re = $this->months();
    //  print_r($months_re);
    //$current_month= date('m');
      
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

    $grossAmt=array();
    $expencesAmt=array();
    $assetAmt=array();
    $balancedat=array();
    $investorType=$this->db->select('name,percentage,type')->from('investors')->where('id',$id)->get()->row_array();
    $balancedata=array();
    $forEachData=array();
    $balancedataOverAll=array();
        // echo"<pre>";
    if($investorType['type']=='Primary'){

    foreach($months as $key => $month_value){
   
   	if($month_value=='01'){
    $monthData="January";
   	}elseif($month_value=='02'){
    $monthData="February";
   	}elseif($month_value=='03'){
    $monthData="March";
   	}elseif($month_value=='04'){
    $monthData="April";
   	}elseif($month_value=='05'){
     $monthData="May";
   	}elseif($month_value=='06'){
     $monthData="June";
   	}elseif($month_value=='07'){
     $monthData="July";
   	}elseif($month_value=='08'){
     $monthData="August";
   	}elseif($month_value=='09'){
     $monthData="September";
   	}elseif($month_value=='10'){
   	$monthData="October";	
   	}elseif($month_value=='11'){
   	 $monthData="November";	
   	}elseif($month_value=='12'){
     $monthData="December";
   	}else{
     $monthData="";
   	}

   //$grossAmt=$this->db->select('sum(distinct o.gross_amount) as gross_amount,sum(oi.qty * p.price_selling) as org_amount')->from('orders')->where('paid_status','1')->where('MONTH(`paid_date`)',$month_value)->get()->row_array();

   $grossAmt=$this->db->select('sum(order_profit) as gross_amount')->from('orders')->where('paid_status','1')->where('MONTH(`date_time`)',$month_value)->where('YEAR(`date_time`)',$year)->get()->row_array();

   $grossAmtOverAll=$this->db->select('sum(order_profit) as gross_amount')->from('orders')->where('paid_status!=','4')->where('MONTH(`date_time`)',$month_value)->where('YEAR(`date_time`)',$year)->get()->row_array();

   // $data['gross_amount']=$grossAmt;
 
   $expencesAmt=$this->db->select('sum(amount) as amount')->from('expenses')->where('MONTH(`expense_date`)',$month_value)->where('YEAR(`expense_date`)',$year)->get()->row_array();
   // $data['amount']=$expencesAmt;

   // $assetAmt=$this->db->select('sum(value) as value')->from('assets')->where('MONTH(`asset_date`)',$month_value)->get()->row_array();
   
  $IDChecker=$this->db->query('select count(id) as id from tbl_investor_porofit where investor_id = '.$id.' ')->row_array();

   if($IDChecker['id']>0){

  $paidAmt=$this->db->select('SUM(paid_amount) as paid_amount')->from('investor_receive_amount')->where('MONTH(`paid_date`)',$month_value)->where('YEAR(`paid_date`)',$year)->where('investor_id',$id)->get()->row_array();
   // echo $month_value;
   // print_r($paidAmt);
   // die;
    // $paidAmt=$this->db->query('select paid_amount,pending_amount from tbl_investor_porofit where month='.$month_value.' ')->row_array();
  // echo $month_value;
  // print_r($paidAmt);

   if(!empty($paidAmt['paid_amount'])){
   $forEachData[$key]['paid_amount']=round($paidAmt['paid_amount'],2);
   }else{
   $forEachData[$key]['paid_amount']=0; 	
   }

   // $forEachData[$key]['pending_amount']=$paidAmt['pending_amount'];
    }else{
   $forEachData[$key]['paid_amount']=0;
   // $forEachData[$key]['pending_amount']=0;
    }

   // $data['value']=$assetAmt;    
   $expAmt=$expencesAmt['amount'];
   $overAllSellWithExp=$grossAmt['gross_amount'];

   $profitAmtp=$grossAmt['gross_amount']-($expencesAmt['amount']);
   $proditAmtOverAll=$grossAmtOverAll['gross_amount']-($expencesAmt['amount']);

   $primaryInvAmount=$profitAmtp*$investorType['percentage']/100;
   $primaryInvAmountOverAll=$proditAmtOverAll*$investorType['percentage']/100;


   $balancedata[]=round($primaryInvAmount,2);
   $balancedataOverAll[]=round($primaryInvAmountOverAll,2);

   $forEachData[$key]['profit_amount']=round($primaryInvAmount,2);
   $forEachData[$key]['profit_amount_without_collection']=round($primaryInvAmountOverAll,2);
   $forEachData[$key]['investor_id']=$id;
   $forEachData[$key]['name']=$investorType['name'];
   $forEachData[$key]['monthData']=$monthData;
   $forEachData[$key]['month']=$month_value;

   $forEachData[$key]['expAmt']=$expAmt;
   $forEachData[$key]['overAllSellWithExp']=$overAllSellWithExp;
   
    }
   $IDChecker=$this->db->query('select count(id) as id from tbl_investor_porofit where investor_id = '.$id.' ')->row_array();

     // print_r($value_balance); 
//    echo $value_balance['investor_id'];
    if($IDChecker['id']>0){
    $this->db->where('investor_id',$id)->delete('tbl_investor_porofit');
    foreach ($forEachData as $balance_key => $value_balance){
   //print_r($forEachData);
    $Insert_arr1=array(
               'paid_amount'=>$value_balance['paid_amount'],
               // 'pending_amount'=>$value_balance['pending_amount'],
               'total_amount'=>$value_balance['profit_amount'],
               'month'=>$value_balance['month'],
               'investor_id'=>$value_balance['investor_id'],
               );
     $this->db->insert('tbl_investor_porofit',$Insert_arr1);
    }
    }else{
    foreach ($forEachData as $balance_key => $value_balance){

    $Insert_arr1=array(
    	         'paid_amount'=>$value_balance['paid_amount'],
               // 'pending_amount'=>$value_balance['pending_amount'],
               'total_amount'=>$value_balance['profit_amount'],
               'month'=>$value_balance['month'],
               'investor_id'=>$value_balance['investor_id'],
                     );
     $this->db->insert('tbl_investor_porofit',$Insert_arr1);

    }
    }
    }elseif($investorType['type']=='Secondary'){
    
  $primaryInvestor=$this->db->select('name,percentage,type')->from('investors')->where('type','Primary')->get()->row_array(); 	
    
  foreach($months as $key => $month_value){
   	
			if($month_value=='01'){
    $monthData="January";
   	}elseif($month_value=='02'){
    $monthData="February";
   	}elseif($month_value=='03'){
    $monthData="March";
   	}elseif($month_value=='04'){
    $monthData="April";
   	}elseif($month_value=='05'){
     $monthData="May";
   	}elseif($month_value=='06'){
     $monthData="June";
   	}elseif($month_value=='07'){
     $monthData="July";
   	}elseif($month_value=='08'){
     $monthData="August";
   	}elseif($month_value=='09'){
     $monthData="September";
   	}elseif($month_value=='10'){
   	$monthData="October";	
   	}elseif($month_value=='11'){
   	 $monthData="November";	
   	}elseif($month_value=='12'){
     $monthData="December";
   	}else{
     $monthData="";
   	}

   $grossAmt=$this->db->select('sum(order_profit) as gross_amount')->from('orders')->where('paid_status','1')->where('MONTH(`date_time`)',$month_value)->where('YEAR(`date_time`)',$year)->get()->row_array();

   $grossAmtOverAll=$this->db->select('sum(order_profit) as gross_amount')->from('orders')->where('paid_status!=','4')->where('MONTH(`date_time`)',$month_value)->where('YEAR(`date_time`)',$year)->get()->row_array();
   // $data['gross_amount']=$grossAmt;
 
   $expencesAmt=$this->db->select('sum(amount) as amount')->from('expenses')->where('MONTH(`expense_date`)',$month_value)->where('YEAR(`expense_date`)',$year)->get()->row_array();
   // $data['amount']=$expencesAmt;

   //$assetAmt=$this->db->select('sum(value) as value')->from('assets')->where('MONTH(`asset_date`)',$month_value)->get()->row_array();
   // $data['value']=$assetAmt;    

   $expAmt=$expencesAmt['amount'];
   $overAllSellWithExp=$grossAmt['gross_amount'];

   $profitAmtp=$grossAmt['gross_amount']-($expencesAmt['amount']);

   $profitAmtpOverAll=$grossAmtOverAll['gross_amount']-($expencesAmt['amount']);   

   $primaryInvAmount=$profitAmtp*$primaryInvestor['percentage']/100;

   $primaryInvAmountOverAll=$profitAmtpOverAll*$primaryInvestor['percentage']/100;


   $remainingAmtOverAll=round($profitAmtpOverAll,2)-round($primaryInvAmountOverAll,2);

   $remainingAmt=round($profitAmtp,2)-round($primaryInvAmount,2);


   $remainingBalanceOverAll=$remainingAmtOverAll*$investorType['percentage']/100;

   $remainingBalance=$remainingAmt*$investorType['percentage']/100;
   
   $balancedata[]=round($remainingBalance,2);
   $balancedataOverAll[]=round($remainingBalanceOverAll,2);

   $IDChecker=$this->db->query('select count(id) as id from tbl_investor_porofit where investor_id = '.$id.' ')->row_array();
   
   if($IDChecker['id']>0){
   // $paidAmt=$this->db->select('paid_amount,pending_amount')->from('tbl_investor_porofit')->where('MONTH',$month_value)->where('investor_id',$id)->get()->row_array();

   $paidAmt=$this->db->select('SUM(paid_amount) as paid_amount')->from('investor_receive_amount')->where('MONTH(`paid_date`)',$month_value)->where('YEAR(`paid_date`)',$year)->where('investor_id',$id)->get()->row_array();

if(!empty($paidAmt['paid_amount'])){
$paid_amount=round($paidAmt['paid_amount'],2);
}else{
$paid_amount=0;
}

   $forEachData[$key]['paid_amount']=$paid_amount;
   // $forEachData[$key]['pending_amount']=$paidAmt['pending_amount'];
    }else{
   $forEachData[$key]['paid_amount']=0;
   // $forEachData[$key]['pending_amount']=0;
    }


   $forEachData[$key]['profit_amount']=round($remainingBalance,2);
   $forEachData[$key]['profit_amount_without_collection']=round($remainingBalanceOverAll,2);
   $forEachData[$key]['investor_id']=$id;
   $forEachData[$key]['name']=$investorType['name'];
   $forEachData[$key]['monthData']=$monthData;
   $forEachData[$key]['month']=$month_value;
   $forEachData[$key]['expAmt']=$expAmt;
   $forEachData[$key]['overAllSellWithExp']=$overAllSellWithExp;
    }

   $IDChecker=$this->db->query('select count(`id`) as id from tbl_investor_porofit where investor_id = '.$id.' ')->row_array();
    if($IDChecker['id']>0){
     $this->db->where('investor_id',$id)->delete('tbl_investor_porofit');
    foreach ($forEachData as $balance_key => $value_balance) {
     $Insert_arr1=array(
               'total_amount'=>$value_balance['profit_amount'],
               'month'=>$value_balance['month'],
               'paid_amount'=>$value_balance['paid_amount'],
               // 'pending_amount'=>$value_balance['pending_amount'],
               'investor_id'=>$value_balance['investor_id'],
               );
     $this->db->insert('tbl_investor_porofit',$Insert_arr1);
    }
    }else{
    foreach ($forEachData as $balance_key => $value_balance){
    $Insert_arr1=array(
    	       'paid_amount'=>$value_balance['paid_amount'],
               // 'pending_amount'=>$value_balance['pending_amount'],
               'total_amount'=>$value_balance['profit_amount'],
               'month'=>$value_balance['month'],
               'investor_id'=>$value_balance['investor_id'],
               );
     $this->db->insert('tbl_investor_porofit',$Insert_arr1);
     }

    }
    }else{

    }
    return $forEachData;
	}

  public function investorsTotalAmt($id){
  $totalAmt=0;  
  $investorType=$this->db->select('name,percentage,type')->from('investors')->where('id',$id)->get()->row_array();

   $grossAmt=$this->db->select('sum(order_profit) as gross_amount')->from('orders')->where('paid_status','1')->get()->row_array();

   $expencesAmt=$this->db->select('sum(amount) as amount')->from('expenses')->get()->row_array();

   if($investorType['type']=='Primary'){

   $profitAmtp=$grossAmt['gross_amount']-($expencesAmt['amount']);

   $primaryInvAmount=$profitAmtp*$investorType['percentage']/100;
   $totalAmt=$primaryInvAmount;
   return $totalAmt;
    }elseif($investorType['type']=='Secondary'){

  $primaryInvestor=$this->db->select('name,percentage,type')->from('investors')->where('type','Primary')->get()->row_array();   

   $profitAmtp=$grossAmt['gross_amount']-($expencesAmt['amount']);  

   $primaryInvAmount=$profitAmtp*$primaryInvestor['percentage']/100;

   $remainingAmt=round($profitAmtp,2)-round($primaryInvAmount,2);

   $remainingBalance=$remainingAmt*$investorType['percentage']/100;

   $totalAmt=$remainingBalance;
   return $totalAmt;
    }else{
   return $totalAmt;
    }
 
  }

	public function getReceiveAmount($id,$year=''){
    return $this->db->select('*')->from('investor_receive_amount')->where('investor_id',$id)->where('YEAR(`paid_date`)',$year)->get()->result_array();
	}

    public function getSumOfReceiveAmount($id,$year=''){
     if(!empty($year)){
     return $this->db->select('sum(paid_amount) as paid_amount')->from('investor_receive_amount')->where('investor_id',$id)->where('YEAR(`paid_date`)',$year)->get()->row_array();
     }else{
     return $this->db->select('sum(paid_amount) as paid_amount')->from('investor_receive_amount')->where('investor_id',$id)->get()->row_array();
     }
	}

	public function payBill($arr){
    $this->db->insert('investor_receive_amount',$arr);
	}

  public function removeInvestor($id)
	{
		 // $a=$this->db->select('*')->from('investor_receive_amount')->where('id',$id)->get()->result_array();     
    if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('investor_receive_amount');
			return ($delete == true) ? true : false;
		}
	}

	public function getInvestorName($id){
    return $this->db->select('name')->from('investors')->where('id',$id)->get()->row_array();
	}

}