<?php 
error_reporting(1);
class Model_products extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_reports');
	}

	/* get the brand data */
	public function getProductData($id = null)
	{
		$sql = "SELECT a.*,b.invoice_no as oth_invoice_no,b.id as invoiceID,b.delivery_date FROM `products` a JOIN commercial_invoice_info b ON b.id=a.invoice_id ORDER BY a.id DESC";
		$query = $this->db->query($sql);
		$data=$query->result_array();

        $ar=array();
            foreach($data as $key => $value) {
    $saleOutPro=$this->model_reports->getSaleOutProduct($value['id']);
            $ar[]=$value;
    $attr_val=json_decode($value['attribute_value_id']);
    $attr_color=$this->db->select('value')->from('attribute_value')->where('attribute_parent_id','5')->where_in('id',$attr_val)->get()->row_array(); 
    $ar[$key]['color']=$attr_color['value'];
    $attr_size=$this->db->select('value')->from('attribute_value')->where('attribute_parent_id','4')->where_in('id',$attr_val)->get()->row_array(); 
     $ar[$key]['size']=$attr_size['value'];
     $ar[$key]['getoutqty']=$saleOutPro['getoutqty'];
            }
    return $ar;
	}

	public function getProductDataById($id){
	if($id){
	$sql = "SELECT * FROM `products` where id = '".$id."' ";
	$query = $this->db->query($sql, array($id));
	return $query->row_array();
	}
	}

	/*get the active factory information*/
	public function getActiveFactories()
	{
		$sql = "SELECT * FROM `factories` WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function getActiveProductData()
	{
		$sql = "SELECT * FROM `products` WHERE availability = ? ORDER BY id DESC";
		$query = $this->db->query($sql, array(1));
		$query_data=$query->result_array();

    $arr_val=array();    
    foreach ($query_data as $key => $value){
    $arr_val[]=$value;
   
    $attr_val=json_decode($value['attribute_value_id']);
    for ($i=0; $i <count($attr_val); $i++) {
    $attr_color=$this->db->select('value')->from('attribute_value')->where('attribute_parent_id','5')->where('id',$attr_val[$i])->get()->row_array();  
    $arr_val[$key]['color']=$attr_color['value'];
    }
    }
    return $arr_val;
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('products', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('products', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('products');
			return ($delete == true) ? true : false;
		}
	}

	public function countTotalProducts()
	{
		$sql = "SELECT * FROM `products`";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
     
    public function getproductSizeColor(){
   
    }

    public function getInvoiceNo(){
    return $this->db->select('*')->from('commercial_invoice_info')->get()->result_array();
   }

   public function getProductDataList($id){
   	$sql = "SELECT a.*,b.invoice_no as oth_invoice_no,b.delivery_date FROM `products` a JOIN commercial_invoice_info b ON b.id=a.invoice_id where a.invoice_id=".$id." ORDER BY a.id DESC";
		$query = $this->db->query($sql);
		$data=$query->result_array();

        $ar=array();
            foreach($data as $key => $value) {
            $ar[]=$value;
    $attr_val=json_decode($value['attribute_value_id']);
    $attr_color=$this->db->select('value')->from('attribute_value')->where('attribute_parent_id','5')->where_in('id',$attr_val)->get()->row_array(); 
    $ar[$key]['color']=$attr_color['value'];
    $attr_size=$this->db->select('value')->from('attribute_value')->where('attribute_parent_id','4')->where_in('id',$attr_val)->get()->row_array(); 
     $ar[$key]['size']=$attr_size['value'];
            }
    return $ar;
   }

}