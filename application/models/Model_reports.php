<?php 

class Model_reports extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
  	$this->load->model('model_reports');
    $this->load->model('model_company');
  }

	/*getting the total months*/
	private function months()
	{
		return array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
	}

	/* getting the year of the orders */
	public function getOrderYear()
	{
		// $sql = "SELECT * FROM `orders` WHERE paid_status = ?";
		$sql = "SELECT * FROM `orders`";
		// $query = $this->db->query($sql, array(1));
		$query = $this->db->query($sql);
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
	public function getOrderData($year)
	{

			if($year) {
			$months_re = $this->months();
			$current_month= date('m');
			$re=array();
			for($i=0;$i<count($months_re);$i++){
			if($months_re[$i]==$current_month OR $months_re[$i]<=$current_month){
			$re[]=$months_re[$i];
			}
			}
			$months=$re;
			
			// $sql = "SELECT * FROM `orders` WHERE paid_status = ?";
			$sql = "SELECT * FROM `orders` where paid_status!=4";
			$query = $this->db->query($sql);
			//$query = $this->db->query($sql, array(1));
			$result = $query->result_array();

			$final_data = array();
			foreach ($months as $month_k => $month_y) {
				$get_mon_year = $year.'-'.$month_y;	

				$final_data[$get_mon_year][] = '';
				foreach ($result as $k => $v) {
					$month_year = date('Y-m', strtotime($v['date_time']));

					if($get_mon_year == $month_year) {
						$final_data[$get_mon_year][] = $v;
					}
				}
			}	
			return $final_data;
			
		}
	}

	public function getInventoryreport(){
    $sql = "SELECT a.*,b.invoice_no as oth_invoice_no,b.delivery_date FROM `products` a JOIN commercial_invoice_info b ON b.id=a.invoice_id ORDER BY a.id DESC";
    $query = $this->db->query($sql);
    $data=$query->result_array();
     return $data;
     }

     public function getSaleOutProduct($id){
    return $this->db->select('sum(oi.qty) as getoutqty')->from('orders_item as oi')->join('orders o','o.id = oi.order_id')->join('products p','p.id = oi.product_id')->where('o.paid_status!=4')->where('p.id',$id)->get()->row_array();
     }

     public function getInventryAttr($id){
     return $this->db->query("select * from attribute_value where id=".$id." AND attribute_parent_id=5")->result_array();
     }

  public function getsalesreport($custName='',$from_data='',$to_data='',$payID=''){
  // print_r($_POST);
  // echo $custName."cus<br>";
  // echo $from_data."from<br>";
   // echo $payID."from<br>";
   // die;
  $from_date=date('Y-m-d h:i:s',strtotime($this->input->post('from_date')));
  $to_date=date('Y-m-d h:i:s',strtotime($this->input->post('to_date')));

  if($custName!='all' AND !empty($from_data) AND !empty($to_data) AND empty($payID)){

  $custData=$this->db->select('b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->where('b.id',$custName)->where('a.date_time >=',$from_date)->where('a.date_time <=',$to_date)->order_by('a.date_time','DESC')->group_by('b.name')->get()->result_array();
     $cust=array();	

    foreach ($custData as $key => $value) {
  
  $cust[]=$this->db->select('u.firstname,u.lastname,a.due_date,a.paid_date,a.bill_no,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->join('users u','u.id = a.user_id')->where('d.id',$value['id'])->where('a.date_time >=',$from_date)->where('a.date_time <=',$to_date)->order_by('a.date_time','DESC')->get()->result_array();

    }
    }elseif($custName=='all' AND !empty($from_data) AND !empty($to_data) AND empty($payID)){

  $custData=$this->db->select('b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->where('a.date_time >=',$from_date)->where('a.date_time <=',$to_date)->group_by('b.name')->order_by('a.date_time','DESC')->get()->result_array();
     $cust=array();
    foreach ($custData as $key => $value) {

  $cust[]=$this->db->select('u.firstname,u.lastname,a.due_date,a.paid_date,a.bill_no,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->join('users u','u.id = a.user_id')->where('d.id',$value['id'])->where('a.date_time >=',$from_date)->where('a.date_time <=',$to_date)->order_by('a.date_time','DESC')->get()->result_array();
    }

    }elseif((!empty($custName) AND $custName!='all')AND empty($from_data) AND empty($to_data) AND empty($payID)){


    $custData=$this->db->select('b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->where('b.id',$custName)->order_by('a.date_time','DESC')->group_by('b.name')->get()->result_array();
     $cust=array();
    foreach ($custData as $key => $value) {

    $cust[]=$this->db->select('u.firstname,u.lastname,a.due_date,a.paid_date,a.bill_no,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->join('users u','u.id = a.user_id')->where('d.id',$value['id'])->order_by('a.date_time','DESC')->get()->result_array();
    }
    }elseif($custName=='all' AND empty($from_data) AND empty($to_data) AND empty($payID)){
     $from_date=date('Y-m-d h:i:s',strtotime($this->input->post('from_date')));
     $to_date=date('Y-m-d h:i:s',strtotime($this->input->post('to_date')));

    $custData=$this->db->select('b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->order_by('a.date_time','DESC')->group_by('b.name')->get()->result_array();
     $cust=array();
    foreach ($custData as $key => $value) {

    $cust[]=$this->db->select('u.firstname,u.lastname,a.due_date,a.paid_date,a.bill_no,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->join('users u','u.id = a.user_id')->where('d.id',$value['id'])->order_by('a.date_time','DESC')->get()->result_array();
    }
    }elseif($custName!='all' AND !empty($from_data) AND !empty($to_data) AND !empty($payID)){
    

   $custData=$this->db->select('b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->where('b.id',$custName)->where('a.date_time >=',$from_date)->where('a.date_time <=',$to_date)->where('a.paid_status',$payID)->order_by('a.date_time','DESC')->group_by('b.name')->get()->result_array();
     $cust=array(); 

    foreach ($custData as $key => $value) {
  $cust[]=$this->db->select('u.firstname,u.lastname,a.due_date,a.paid_date,a.bill_no,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->join('users u','u.id = a.user_id')->where('d.id',$value['id'])->where('a.date_time >=',$from_date)->where('a.date_time <=',$to_date)->where('a.paid_status',$payID)->order_by('a.date_time','DESC')->get()->result_array();
    }


    }elseif(($custName=='all') AND !empty($from_data) AND !empty($to_data) AND !empty($payID)){


 $custData=$this->db->select('b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->where('a.date_time >=',$from_date)->where('a.date_time <=',$to_date)->where('a.paid_status',$payID)->group_by('b.name')->order_by('a.date_time','DESC')->get()->result_array();
     $cust=array();
    foreach ($custData as $key => $value) {

  $cust[]=$this->db->select('u.firstname,u.lastname,a.due_date,a.paid_date,a.bill_no,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->join('users u','u.id = a.user_id')->where('d.id',$value['id'])->where('a.date_time >=',$from_date)->where('a.date_time <=',$to_date)->where('a.paid_status',$payID)->order_by('a.date_time','DESC')->get()->result_array();
    }


    }elseif((!empty($custName) AND $custName!='all')AND empty($from_data) AND empty($to_data) AND !empty($payID)){

    $custData=$this->db->select('b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->where('b.id',$custName)->where('a.paid_status',$payID)->order_by('a.date_time','DESC')->group_by('b.name')->get()->result_array();
     $cust=array();
    foreach ($custData as $key => $value) {

    $cust[]=$this->db->select('u.firstname,u.lastname,a.due_date,a.paid_date,a.bill_no,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->join('users u','u.id = a.user_id')->where('d.id',$value['id'])->where('a.paid_status',$payID)->order_by('a.date_time','DESC')->get()->result_array();
    }

    }elseif($custName=='all' AND empty($from_data) AND empty($to_data) AND !empty($payID)){


    $custData=$this->db->select('b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->where('a.paid_status',$payID)->order_by('a.date_time','DESC')->group_by('b.name')->get()->result_array();
     $cust=array();
    foreach ($custData as $key => $value) {

    $cust[]=$this->db->select('u.firstname,u.lastname,a.due_date,a.paid_date,a.bill_no,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->join('users u','u.id = a.user_id')->where('d.id',$value['id'])->where('a.paid_status',$payID)->order_by('a.date_time','DESC')->get()->result_array();
    }

    }elseif(empty($custName) AND empty($from_data) AND empty($to_data) AND !empty($payID)){

    $custData=$this->db->select('a.date_time,b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->where('a.paid_status',$payID)->group_by('b.id')->order_by('a.id','DESC')->get()->result_array();
     $cust=array();   
    foreach ($custData as $key => $value) {
    //  echo"<pre>";
     $cust[]=$this->db->select('u.firstname,u.lastname,a.due_date,a.paid_date,a.bill_no,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->join('users u','u.id = a.user_id')->where('d.id',$value['id'])->where('a.paid_status',$payID)->order_by('a.date_time','DESC')->get()->result_array();
    }

    }else{


    $custData=$this->db->select('a.date_time,b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->group_by('b.id')->order_by('a.id','DESC')->get()->result_array();
     $cust=array();   
    foreach ($custData as $key => $value) {
    //  echo"<pre>";
     $cust[]=$this->db->select('u.firstname,u.lastname,a.due_date,a.paid_date,a.bill_no,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->join('users u','u.id = a.user_id')->where('d.id',$value['id'])->order_by('a.date_time','DESC')->get()->result_array();
    }

    }
    return $cust;
	}


public function getsalesreportByCustomer($custId){
$custData=$this->db->select('b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->where('b.id',$custId)->group_by('b.name')->get()->result_array();
     $cust=array(); 
    foreach ($custData as $key => $value) {

  $cust[]=$this->db->select('a.bill_no,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->where('d.id',$value['id'])->order_by('a.date_time','DESC')->get()->result_array();
    }
    return $cust;
}

  /*  
  *   getOrderDetail
  */ 

   public function getproductData($id){
   return $this->db->select('p.model_no,ci.invoice_no,p.description as pro_name,p.unit')->from('products as p')->join('commercial_invoice_info as ci','ci.id = p.invoice_id')->where('p.id',$id)->get()->row_array();
   }

	public function getAllDueOrder(){
    return $this->db->select('o.due_date,o.id,o.bill_no,c.name,c.code,o.date_time,o.net_amount')->from('orders o')->join('customers c','c.id=o.cust_id')->where('o.paid_status','2')->order_by('o.date_time','DESC')->get()->result_array();
	}

	public function getCustomerData(){
    return $this->db->select('*')->from('customers')->order_by('name','AESC')->get()->result_array();
	}
    
    public function getAssetsDataList($month,$year){
    return $this->db->query('SELECT a.code,a.value,a.asset_date,b.title as name FROM `assets` a JOIN `expences_code` as b ON b.code=a.code  WHERE MONTH(a.`asset_date`) ='.$month.' AND YEAR(a.`asset_date`) ='.$year.' AND a.asset_type="asset" ORDER BY a.asset_date DESC')->result_array();
    }

    public function getCurrentAssetsDataList($month,$year){
    return $this->db->query('SELECT a.code,a.value,a.asset_date,b.title as name FROM `assets` a JOIN `expences_code` as b ON b.code=a.code  WHERE MONTH(a.`asset_date`) ='.$month.' AND YEAR(a.`asset_date`) ='.$year.' AND a.asset_type="current_asset" ORDER BY a.asset_date DESC ')->result_array();
    }

	public function getCurrentAssetStaticData(){
    $pending_amount=$this->db->query('SELECT SUM(`net_amount`) as pending_amount from orders where paid_status =2 ')->row_array();

    $paid_comersial_amt=$this->db->query('SELECT SUM(`paid_amount`) as paid_comersial_amt FROM `comersial_invoice`')->row_array();

    $avaliable_pro_amt=$this->db->query('SELECT SUM(`qty`*`price`) as avaliable_pro_amt FROM products')->row_array();
    $arr=array();
    $arr['pending_amount']['name'][]='SUNDRY DEBTORS';
    $arr['paid_comersial_amt']['name'][]='SUNDRY CREDITORS';
    $arr['avaliable_pro_amt']['name'][]='INVENTORY';
    $arr['pending_amount']['amt'][]=$pending_amount['pending_amount'];
    $arr['paid_comersial_amt']['amt'][]=$paid_comersial_amt['paid_comersial_amt'];
    $arr['avaliable_pro_amt']['amt'][]=$avaliable_pro_amt['avaliable_pro_amt'];
    return $arr;
	}

	public function getInvestorPrimary($month,$year){
    $investorName=$this->db->select('name')->from('investors')->where('id','3')->get()->row_array();
    $data=$this->db->query('SELECT SUM(`paid_amount`) as total_receivedAmt from investor_receive_amount WHERE MONTH(`paid_date`) ='.$month.' AND YEAR(`paid_date`) ='.$year.' AND investor_id=3 ')->row_array();

    $arrInvestorPr=array();
    $arrInvestorPr['name'][]=$investorName['name'];
    $arrInvestorPr['amt'][]=$data['total_receivedAmt'] ? : 0;
    return $arrInvestorPr;
	}

	public function getInvestorSecon01($month,$year){
    $investorName=$this->db->select('name')->from('investors')->where('id','1')->get()->row_array();
    $data=$this->db->query('SELECT SUM(`paid_amount`) as total_receivedAmt from investor_receive_amount WHERE MONTH(`paid_date`) ='.$month.' AND YEAR(`paid_date`) ='.$year.' AND investor_id=1 ')->row_array();

    $arrInvestorPr=array();
    $arrInvestorPr['name'][]=$investorName['name'];
    $arrInvestorPr['amt'][]=$data['total_receivedAmt'] ? : 0;
    return $arrInvestorPr;
	}

    public function getInvestorSecon02($month,$year){
    $investorName=$this->db->select('name')->from('investors')->where('id','2')->get()->row_array();	
    $data=$this->db->query('SELECT SUM(`paid_amount`) as total_receivedAmt from investor_receive_amount WHERE MONTH(`paid_date`) ='.$month.' AND YEAR(`paid_date`) ='.$year.' AND investor_id=2 ')->row_array();

    $arrInvestorPr=array();
    $arrInvestorPr['name'][]=$investorName['name'];
    $arrInvestorPr['amt'][]=$data['total_receivedAmt'] ? : 0;
    return $arrInvestorPr;
	}

    public function getExpencesDataList($month,$year){
    return $this->db->query('SELECT SUM(a.`amount`) as amount , b.title as name , a.code  FROM `expenses` a JOIN expences_code b ON a.code = b.code WHERE MONTH(a.`expense_date`) ='.$month.' AND YEAR(a.`expense_date`) ='.$year.' group by a.code ORDER BY a.expense_date DESC ')->result_array();
    }
    
    public function getInvoicereport($custName='',$year='',$month='',$payID=''){
    //print_r($_POST);
   if($custName=='all' AND !empty($year) AND !empty($month) AND !empty($payID)){
   
   $custData=$this->db->select('b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->where('MONTH(a.`date_time`)',$month)->where('YEAR(a.`date_time`)',$year)->where('a.paid_status',$payID)->group_by('b.name')->order_by('a.date_time','DESC')->get()->result_array();

    $all_data=array();  
    foreach ($custData as $key => $value) {
  $all_data[]=$this->db->select('u.firstname,u.lastname,a.due_date,a.paid_date,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->join('users u','u.id = a.user_id')->where('d.id',$value['id'])->where('MONTH(a.`date_time`)',$month)->where('YEAR(a.`date_time`)',$year)->where('a.paid_status',$payID)->order_by('a.date_time','DESC')->get()->result_array();
    }
   }elseif(($custName!='all' || !empty($custName)) AND !empty($year) AND !empty($month) AND !empty($payID)){
   
   $custData=$this->db->select('b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->where('b.id',$custName)->where('MONTH(a.`date_time`)',$month)->where('YEAR(a.`date_time`)',$year)->where('a.paid_status',$payID)->group_by('b.name')->order_by('a.date_time','DESC')->get()->result_array();

    $all_data=array();  
    foreach ($custData as $key => $value) {
  $all_data[]=$this->db->select('u.firstname,u.lastname,a.due_date,a.paid_date,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->join('users u','u.id = a.user_id')->where('d.id',$value['id'])->where('MONTH(a.`date_time`)',$month)->where('YEAR(a.`date_time`)',$year)->where('a.paid_status',$payID)->order_by('a.date_time','DESC')->get()->result_array();
    }
   
   }elseif( (!empty($custName) AND $custName!='all') AND empty($year) AND empty($month) AND !empty($payID)){

  $custData=$this->db->select('b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->where('b.id',$custName)->where('a.paid_status',$payID)->group_by('b.name')->order_by('a.date_time','DESC')->get()->result_array();

    $all_data=array();  
    foreach ($custData as $key => $value) {
  $all_data[]=$this->db->select('u.firstname,u.lastname,a.due_date,a.paid_date,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->join('users u','u.id = a.user_id')->where('a.paid_status',$payID)->where('d.id',$value['id'])->order_by('a.date_time','DESC')->get()->result_array();
    }

   }elseif($custName=='all' AND empty($year) AND empty($month) AND !empty($payID)){

   $custData=$this->db->select('b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->where('a.paid_status',$payID)->group_by('b.name')->order_by('a.date_time','DESC')->get()->result_array();

    $all_data=array();  
    foreach ($custData as $key => $value) {
  $all_data[]=$this->db->select('u.firstname,u.lastname,a.due_date,a.paid_date,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->join('users u','u.id = a.user_id')->where('a.paid_status',$payID)->where('d.id',$value['id'])->order_by('a.date_time','DESC')->get()->result_array();
    }
   }elseif($custName=='all' AND !empty($year) AND !empty($month) AND empty($payID)){

  $custData=$this->db->select('b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->where('MONTH(a.`date_time`)',$month)->where('YEAR(a.`date_time`)',$year)->group_by('b.name')->order_by('a.date_time','DESC')->get()->result_array();

    $all_data=array();  
    foreach ($custData as $key => $value) {
  $all_data[]=$this->db->select('u.firstname,u.lastname,a.due_date,a.paid_date,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->join('users u','u.id = a.user_id')->where('d.id',$value['id'])->where('MONTH(a.`date_time`)',$month)->where('YEAR(a.`date_time`)',$year)->order_by('a.date_time','DESC')->get()->result_array();
    }


   }elseif(($custName!='all' || !empty($custName)) AND !empty($year) AND !empty($month) AND empty($payID)){


  $custData=$this->db->select('b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->where('b.id',$custName)->where('MONTH(a.`date_time`)',$month)->where('YEAR(a.`date_time`)',$year)->group_by('b.name')->order_by('a.date_time','DESC')->get()->result_array();

    $all_data=array();  
    foreach ($custData as $key => $value) {
  $all_data[]=$this->db->select('u.firstname,u.lastname,a.due_date,a.paid_date,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->join('users u','u.id = a.user_id')->where('d.id',$value['id'])->where('MONTH(a.`date_time`)',$month)->where('YEAR(a.`date_time`)',$year)->order_by('a.date_time','DESC')->get()->result_array();
    }

   }elseif((!empty($custName) AND $custName!='all') AND empty($year) AND empty($month) AND empty($payID)){

   $custData=$this->db->select('b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->where('b.id',$custName)->group_by('b.name')->order_by('a.date_time','DESC')->get()->result_array();

    $all_data=array();  
    foreach ($custData as $key => $value) {
    $all_data[]=$this->db->select('u.firstname,u.lastname,a.due_date,a.paid_date,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->join('users u','u.id = a.user_id')->where('d.id',$value['id'])->order_by('a.date_time','DESC')->get()->result_array();
    }


   }elseif($custName=='all' AND empty($year) AND empty($month) AND empty($payID)){

  $custData=$this->db->select('b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->group_by('b.name')->order_by('a.date_time','DESC')->get()->result_array();

    $all_data=array();  
    foreach ($custData as $key => $value) {
  $all_data[]=$this->db->select('u.firstname,u.lastname,a.due_date,a.paid_date,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->join('users u','u.id = a.user_id')->where('d.id',$value['id'])->order_by('a.date_time','DESC')->get()->result_array();
    }


   }else{

    $custData=$this->db->select('b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->group_by('b.name')->order_by('a.date_time','DESC')->get()->result_array();

    $all_data=array();  
    foreach ($custData as $key => $value) {
  $all_data[]=$this->db->select('u.firstname,u.lastname,a.due_date,a.paid_date,d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.id as order_id')->from('orders a')->join('customers d','d.id = a.cust_id')->join('users u','u.id = a.user_id')->where('d.id',$value['id'])->order_by('a.date_time','DESC')->get()->result_array();
    }
   }


    return $all_data;
    }

   public function getInvoicereport01($custName,$year,$month){
   $custData=$this->db->select('b.name,b.id')->from('orders a')->join('customers b','b.id=a.cust_id')->where('b.id',$custName)->where('MONTH(a.`date_time`)',$month)->where('YEAR(a.`date_time`)',$year)->group_by('b.name')->get()->result_array();

      $cust_with_item=array();
     // $cust_without_item=array();
     $all_data=array();	
    foreach ($custData as $key => $value) {

  $cust_with_item=$this->db->select('c.model_no,d.name as cust_details,d.code,d.address as cust_address,d.contact as cust_contact,b.rate,c.description as pro_des,c.unit as pro_unit,c.name as product_name,a.bill_no,a.customer_name,a.customer_address,a.date_time,b.amount,a.vat_charge,a.paid_status,a.net_amount,a.total_discount,a.gross_amount,b.qty,b.rate,c.price_selling,c.attribute_value_id,c.cif,c.price,com_inv_info.invoice_no')->from('orders a')->join('orders_item b','b.order_id=a.id')->join('products c','c.id = b.product_id')->join('commercial_invoice_info com_inv_info','com_inv_info.id=c.invoice_id')->join('customers d','d.id = a.cust_id')->where('MONTH(a.`date_time`)',$month)->where('YEAR(a.`date_time`)',$year)->where('d.id',$value['id'])->order_by('a.date_time','DESC')->get()->result_array();
  $arrAtr=array();
  foreach($cust_with_item as $key01 => $value01){
  $arrAtrSize=$this->db->select('value')->from('attribute_value')->where_in('id',json_decode($value01['attribute_value_id']))->where('attribute_parent_id','4')->get()->row_array();
  $arrAtColor=$this->db->select('value')->from('attribute_value')->where_in('id',json_decode($value01['attribute_value_id']))->where('attribute_parent_id','5')->get()->row_array();

  $cust_with_item[$key01]['size']=$arrAtrSize['value'];
  $cust_with_item[$key01]['color']=$arrAtColor['value'];
  }

  $cust_without_item=$this->db->select('d.name as cust_details,d.code,a.bill_no,a.customer_name,a.customer_address,a.date_time,a.vat_charge,a.paid_status,a.net_amount,a.total_discount,a.gross_amount')->from('orders a')->join('customers d','d.id = a.cust_id')->where('MONTH(a.`date_time`)',$month)->where('YEAR(a.`date_time`)',$year)->where('d.id',$value['id'])->get()->result_array();

  $all_data['cust_with_item']=$cust_with_item;
  $all_data['cust_without_item']=$cust_without_item;
    }
    // echo "<pre>";
    // print_r($all_data);
  return $all_data;
    }

  public function capitalData(){
  return $this->db->select('*')->from('company')->where('id','1')->get()->row_array();
  }
  
  public function assetCapitalData($month,$year){
  return $this->db->select('a.code,a.name,a.value,a.asset_date,ec.title')->from('assets a')->join('expences_code ec','a.code = ec.code')->where('a.asset_type','loan')->where('MONTH(a.`asset_date`)',$month)->where('YEAR(a.`asset_date`)',$year)->get()->result_array();
  }

  public function comersial_invoiceData($month,$year){
  return $this->db->select('*')->from('comersial_invoice')->where('MONTH(`paid_date`)',$month)->where('YEAR(`paid_date`)',$year)->get()->result_array();
  }

  public function currentStockData(){
  return $this->db->query('select sum(qty * price) as currentStock from products')->row_array();
  }

  public function allExpences($month,$year){
 // return $this->db->select('sum(amount) as exp_amt')->from('expenses')->where('MONTH(`expense_date`)',$month)->where('YEAR(`expense_date`)',$year)->where('active',1)->get()->row_array();
    return $this->db->select('sum(expenses.amount) as exp_amt')->from('expenses')->join('expences_code','expences_code.code = expenses.code')->where('MONTH(`expense_date`)',$month)->where('YEAR(`expense_date`)',$year)->where('expenses.active','1')->get()->row_array();
  }
  
  public function getFOC_Amount($month,$year){
  return $this->db->select('sum(p.price * p.qty) as foc_amt')->from('orders o')->join('orders_item oi','oi.order_id=o.id')->join('products p','p.id=oi.product_id')->where('MONTH(o.`date_time`)',$month)->where('YEAR(o.`date_time`)',$year)->where('o.paid_status','3')->get()->row_array();
  }

  public function give_to_supplyer($month,$year){
  return $this->db->select('sum(a.paid_amount) as giveToSuppAmt,c.name')->from('comersial_invoice a')->join('commercial_invoice_info b','b.id = a.invoice_no')->join('factories c','c.id = b.factory_id')->where('MONTH(a.`paid_date`)',$month)->where('YEAR(a.`paid_date`)',$year)->group_by('b.factory_id')->get()->result_array();
  }

  public function getCurrentAsset($month,$year){
  return $this->db->select('a.code,a.name,a.value,a.asset_date,ec.title')->from('assets a')->join('expences_code ec','a.code = ec.code')->where('a.asset_type','current_asset')->where('MONTH(a.`asset_date`)',$month)->where('YEAR(a.`asset_date`)',$year)->get()->result_array();
  }

  public function getAsset($month,$year){
  return $this->db->select('a.code,a.name,a.value,a.asset_date,ec.title')->from('assets a')->join('expences_code ec','a.code = ec.code')->where('a.asset_type','asset')->get()->result_array();
   }

   public function getOutstandingAmt($month,$year){
   //return $this->db->query('select sum(oi.qty * p.cif) as OutstandingAmt from orders o JOIN orders_item oi ON o.id=oi.order_id JOIN products p ON p.id = oi.product_id where o.paid_status=2')->row_array();
   return $this->db->query('SELECT SUM(`net_amount`) as OutstandingAmt FROM `orders` where paid_status = 2')->row_array();
   }

  public function getProductModel($model_no){
  return $this->db->query("Select ot.cust_id, ot.bill_no, cust.name, oi.qty, ot.date_time from products as prd, orders_item as oi, orders as ot, customers as cust where prd.model_no = '".$model_no."' and oi.product_id = prd.id and oi.order_id = ot.id AND cust.id = ot.cust_id AND ot.paid_status!=4 order by ot.date_time DESC")->result_array();
  }

  public function getExpenceReport(){
   extract($_POST);
   //print_r($_POST);
  //echo $monthID;
  //die;
  if(empty($monthID) AND empty($yearID) AND $custID=='all'){

  return $this->db->select('expenses.id as ExID,expenses.code,expenses.name,expenses.amount,expenses.expense_date,expenses.active,expences_code.title')->from('expenses')->join('expences_code','expences_code.code = expenses.code')->where('expenses.active','1')->order_by('expenses.`expense_date`','DESC')->get()->result_array();

  }elseif(!empty($monthID) AND !empty($yearID) AND !empty($custID) AND $custID!='all'){

  return $this->db->select('expenses.id as ExID,expenses.code,expenses.name,expenses.amount,expenses.expense_date,expenses.active,expences_code.title')->from('expenses')->join('expences_code','expences_code.code = expenses.code')->where(['expenses.active'=>'1','expences_code.code'=>$custID,'MONTH(expenses.`expense_date`)'=>$monthID,'YEAR(expenses.`expense_date`)'=>$yearID])->order_by('expenses.`expense_date`','DESC')->get()->result_array();

  }elseif(!empty($monthID) AND !empty($yearID) AND $custID=='all'){

  return $this->db->select('expenses.id as ExID,expenses.code,expenses.name,expenses.amount,expenses.expense_date,expenses.active,expences_code.title')->from('expenses')->join('expences_code','expences_code.code = expenses.code')->where(['expenses.active'=>'1','MONTH(expenses.`expense_date`)'=>$monthID,'YEAR(expenses.`expense_date`)'=>$yearID])->order_by('expenses.`expense_date`','DESC')->get()->result_array();

  }elseif(empty($monthID) AND !empty($yearID) AND !empty($custID) AND $custID!='all') {

  return $this->db->select('expenses.id as ExID,expenses.code,expenses.name,expenses.amount,expenses.expense_date,expenses.active,expences_code.title')->from('expenses')->join('expences_code','expences_code.code = expenses.code')->where(['expenses.active'=>'1','expences_code.code'=>$custID,'YEAR(expenses.`expense_date`)'=>$yearID])->order_by('expenses.`expense_date`','DESC')->get()->result_array();

  }elseif (empty($monthID) AND empty($yearID) AND !empty($custID) AND $custID!='all') {
    # code...
  return $this->db->select('expenses.id as ExID,expenses.code,expenses.name,expenses.amount,expenses.expense_date,expenses.active,expences_code.title')->from('expenses')->join('expences_code','expences_code.code = expenses.code')->where(['expenses.active'=>'1','expences_code.code'=>$custID])->order_by('expenses.`expense_date`','DESC')->get()->result_array();


  }elseif (empty($monthID) AND !empty($yearID) AND $custID=='all') {
  
 return $this->db->select('expenses.id as ExID,expenses.code,expenses.name,expenses.amount,expenses.expense_date,expenses.active,expences_code.title')->from('expenses')->join('expences_code','expences_code.code = expenses.code')->where(['expenses.active'=>'1','YEAR(expenses.`expense_date`)'=>$yearID])->order_by('expenses.`expense_date`','DESC')->get()->result_array();

  }else{

  }
  
  }

   /*-------------------*/

   public function getCollectionAmtInCon(){
   if($_POST['monthID']=="all"){
   return $this->db->query('SELECT SUM(`net_amount`) as netOverAllColection FROM `orders` where YEAR(`date_time`)="'.$_POST['yearID'].'" AND  paid_status = 1')->row_array();
   }else{
   return $this->db->query('SELECT SUM(`net_amount`) as netOverAllColection FROM `orders` where MONTH(`date_time`)="'.$_POST['monthID'].'" AND YEAR(`date_time`)="'.$_POST['yearID'].'" AND paid_status = 1')->row_array();
   }
   }

   public function getOutStandingAmtCon(){
   if($_POST['monthID']=="all"){
   return $this->db->query('SELECT SUM(`net_amount`) as outStandAmt FROM `orders` where YEAR(`date_time`)="'.$_POST['yearID'].'" AND paid_status = 2')->row_array();
   }else{
   return $this->db->query('SELECT SUM(`net_amount`) as outStandAmt FROM `orders` where MONTH(`date_time`)="'.$_POST['monthID'].'" AND YEAR(`date_time`)="'.$_POST['yearID'].'" AND paid_status = 2')->row_array();
   }
   }

   public function getOverAllSellingAmtCon(){
   if($_POST['monthID']=="all"){
   return $this->db->query('SELECT SUM(`net_amount`) as OverallSelling FROM `orders` where YEAR(`date_time`)="'.$_POST['yearID'].'" AND paid_status!= 4')->row_array();
   }else{
   return $this->db->query('SELECT SUM(`net_amount`) as OverallSelling FROM `orders` where MONTH(`date_time`)="'.$_POST['monthID'].'" AND YEAR(`date_time`)="'.$_POST['yearID'].'" AND paid_status!= 4')->row_array();
   }
   }

 public function getFOCAmtCon(){
   if($_POST['monthID']=="all"){
   return $this->db->query('select SUM(oi.qty * p.price_selling) as OverAllFOC FROM `orders` o JOIN orders_item oi  ON oi.order_id=o.id JOIN products p ON p.id=oi.product_id where YEAR(`date_time`)="'.$_POST['yearID'].'" AND paid_status = 3')->row_array();
   }else{
   return $this->db->query('SELECT SUM(oi.qty * p.price_selling) as OverAllFOC FROM `orders` o JOIN orders_item oi  ON oi.order_id=o.id JOIN products p ON p.id=oi.product_id where MONTH(o.`date_time`)="'.$_POST['monthID'].'" AND YEAR(o.`date_time`)="'.$_POST['yearID'].'" AND o.paid_status = 3')->row_array();
   }
   }

  public function allExpencesCon(){
  if($_POST['monthID']=="all"){

  return $this->db->select('sum(expenses.amount) as amount')->from('expenses')->join('expences_code','expences_code.code = expenses.code')->where('expenses.active','1')->where('YEAR(expenses.`expense_date`)',$_POST['yearID'])->get()->row_array();

    }else{

  return $this->db->select('sum(expenses.amount) as amount')->from('expenses')->join('expences_code','expences_code.code = expenses.code')->where(['expenses.active'=>'1','MONTH(expenses.`expense_date`)'=>$_POST['monthID'],'YEAR(expenses.`expense_date`)'=>$_POST['yearID']])->get()->row_array();

    }
  }

  public function getProfitOfAMonth(){
    if($_POST['monthID']=="all"){

  return $this->db->select('sum(order_profit) as profitAmt')->from('orders')->where(['YEAR(date_time)'=>$_POST['yearID'],'paid_status'=>'1'])->get()->row_array();

    }else{

  return $this->db->select('sum(order_profit) as profitAmt')->from('orders')->where(['MONTH(date_time)'=>$_POST['monthID'],'YEAR(date_time)'=>$_POST['yearID'],'paid_status'=>'1'])->get()->row_array();
  // return $this->db->select('sum(order_profit) as profitAmt')->from('orders')->join('expences_code','expences_code.code = expenses.code')->where(['expenses.active'=>'1','MONTH(expenses.`expense_date`)'=>$_POST['monthID'],'YEAR(expenses.`expense_date`)'=>$_POST['yearID']])->get()->row_array();

    } 
  }

    public function getPrimaryInInfo(){
    return $this->db->select('*')->from('investors')->where('id','3')->get()->row_array();
    }

    public function getSecondary01InInfo(){
    return $this->db->select('*')->from('investors')->where('id','2')->get()->row_array();
    }

    public function getSecondary02InInfo(){
    return $this->db->select('*')->from('investors')->where('id','1')->get()->row_array();
    }

}