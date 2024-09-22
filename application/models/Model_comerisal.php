<?php 

class Model_comerisal extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get active brand infromation */

   public function getinvoiceno($id,$year=''){
   return $this->db->select('invoice_no')->from('commercial_invoice_info')->where('id',$id)->get()->row_array();
   }

	public function getComerisalInvoice()
	{
		$sql = "SELECT a.id,a.invoice_no,a.delivery_date,(sum(b.`initial_qty` * b.`price`)) as total_balance from commercial_invoice_info a LEFT JOIN products b ON a.id = b.invoice_id  GROUP By a.invoice_no";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

    public function getPaidAmountOfInvoice($invoice_no,$year=''){
    return $this->db->select('sum(paid_amount) as paid_amount')->from('comersial_invoice')->where('invoice_no',$invoice_no)->get()->row_array();
    }

	public function getProductList($invoice_no,$year=''){
    //$invoice_no
		$sql = "SELECT * FROM `products` where invoice_id='".$invoice_no."' ORDER BY id DESC";
		$query = $this->db->query($sql);
		$data=$query->result_array();

        $ar=array();
            foreach($data as $key => $value) {
            $ar[]=$value;

    $factory=$this->db->select('a.name')->from('factories a')->join('commercial_invoice_info b','b.factory_id = a.id')->where('b.id',$invoice_no)->get()->row_array();
    // echo"<pre>";
    // print_r($factory);
    // die;

    $ar[$key]['factory_name']=$factory['name'];
    $attr_val=json_decode($value['attribute_value_id']);
    $attr_color=$this->db->select('value')->from('attribute_value')->where('attribute_parent_id','5')->where_in('id',$attr_val)->get()->row_array(); 
    $ar[$key]['color']=$attr_color['value'];
    $attr_size=$this->db->select('value')->from('attribute_value')->where('attribute_parent_id','4')->where_in('id',$attr_val)->get()->row_array(); 
     $ar[$key]['size']=$attr_size['value'];
            }
    return $ar;
	}

	public function getPaidAmount($invoice_no,$year=''){
    return $this->db->select('id,invoice_no,paid_amount,paid_date')->from('comersial_invoice')->where('invoice_no',$invoice_no)->get()->result_array();
	}

    public function pay_amount($arr){
         $this->db->insert('comersial_invoice',$arr);
	}
	/* get the brand data */


	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('commercial_invoice_info', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('comersial_invoice');
			return ($delete == true) ? true : false;
		}
	}

	public function checkPandingAmount($invoice_no){
    return $this->db->select('sum(paid_amount) as paid_amount')->from('comersial_invoice')->where('invoice_no',$invoice_no)->get()->row_array();
	}

    public function checkTotalPayAmt($invoice_no){
    return $this->db->select('sum(price*initial_qty) as total_billAmt')->from('products')->where('invoice_id',$invoice_no)->get()->row_array();
	}

	public function getallfactory(){
     return $this->db->select('*')->from('factories')->get()->result_array();
	}

	public function getComericalData($id){
    return $this->db->select('*')->from('commercial_invoice_info')->where('id',$id)->get()->row_array();
	}

   public function update($data,$id){
   //$this->db->where('id',$id)->update('commercial_invoice_info',$data);
   if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('commercial_invoice_info', $data);
			return ($update == true) ? true : false;
		}
   }

   public function checkDuplicateInvoice(){
     extract($_POST);
     $data=$this->db->query('select count(id) as id from commercial_invoice_info where invoice_no ="'.$invoice_no.'"')->result_array();
     return $data[0]['id'];
        }

    public function checkDuplicateInvoiceOnEdit(){
    	//print_r($_POST);
    extract($_POST);
    $data=$this->db->query('select count(id) as id from commercial_invoice_info where invoice_no ="'.$invoice_no.'" && id ="'.$id.'" ')->result_array();	
    return $data[0]['id'];
    }


}