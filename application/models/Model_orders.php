<?php 
class Model_orders extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the orders data */
	public function getOrdersData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM `orders` WHERE id = ?";
			$query = $this->db->query($sql, array($id));			
			return $query->row_array();
		}

		$sql = "SELECT * FROM `orders` where paid_status!=4 ORDER BY id DESC";
		$query = $this->db->query($sql);
		
		return $query->result_array();
	}

	public function getOrdersDataByCustomer($id)
	{	
			$sql = "SELECT * FROM `orders` WHERE cust_id = '".$id."' ";
			$query = $this->db->query($sql);			
			return $query->result_array();
	}

	public function getCustomerData($id){
    return $this->db->select('*')->from('customers')->where('id',$id)->get()->row_array();
	}

	public function get_model($id){
	//attribute_value
	}

	public function get_customerData($id){
	return $this->db->select('name')->from('customers')->where('id',$id)->get()->row_array();
	}
	// get the orders item data
	public function getOrdersItemData($order_id = null)
	{
		if(!$order_id) {
			return false;
		}
		$sql = "SELECT * FROM `orders_item` WHERE order_id = ?";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}

	public function create()
	{
		$user_id = $this->session->userdata('id');
		$bill_no = 'BILPR-'.strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        $totalProfit=array(); 
    	$data = array(
    		'bill_no' => $bill_no,
    		'customer_name' => $this->input->post('customer_name'),
    		'cust_id'=>$this->input->post('customer_name'),
    		'customer_address' => $this->input->post('customer_address'),
    		'customer_phone' => $this->input->post('customer_phone'),
    		// 'date_time' => date('Y-m-d h:i:s a'),
    		'gross_amount' => $this->input->post('gross_amount_value'),
    		'service_charge_rate' => $this->input->post('service_charge_rate'),
    		'service_charge' => ($this->input->post('service_charge_value') > 0) ?$this->input->post('service_charge_value'):0,
    		'vat_charge_rate' => $this->input->post('vat_charge_rate'),
    		'vat_charge' => ($this->input->post('vat_charge_value') > 0) ? $this->input->post('vat_charge_value') : 0,
    		'net_amount' => $this->input->post('net_amount_value'),
    		'total_discount' => $this->input->post('total_discount'),
    		// 'paid_status' => 2,
    		
         	'date_time' => date('Y-m-d h:i:s',strtotime($this->input->post('order_date'))),
    		'user_id' => $user_id,
    		'due_date'=>$this->input->post('due_days'),
            'payment_status'=>$this->input->post('payment_status'),
            //'paid_date'=>date('Y-m-d h:i:s',strtotime($this->input->post('paid_date'))),
        	);

    		if($this->input->post('order_date')){
	    	$data['date_time'] = date('Y-m-d h:i:s',strtotime($this->input->post('order_date')));
	    	}else{
	    	$data['date_time'] = date('Y-m-d h:i:s');
	    	}
           
           if($this->input->post('gross_amount_value')==0){
            $data['paid_status']=3;  
			}else{
            $data['paid_status']=2;
	 		}

		$insert = $this->db->insert('orders', $data);
		$order_id = $this->db->insert_id();

		$this->load->model('model_products');

		$count_product = count($this->input->post('product'));
    	
       
    	for($x = 0; $x < $count_product; $x++) {
       $attr_id=$this->db->select('attribute_value_id')->from('products')->where('id',$this->input->post('product')[$x])->get()->row_array();

    $attr_val=json_decode($attr_id['attribute_value_id']);
    $attr_color=$this->db->select('value')->from('attribute_value')->where('attribute_parent_id','5')->where_in('id',$attr_val)->get()->row_array(); 
    $attr_size=$this->db->select('value')->from('attribute_value')->where('attribute_parent_id','4')->where_in('id',$attr_val)->get()->row_array(); 

    		$items = array(
    			'order_id' => $order_id,
    			'product_id' => $this->input->post('product')[$x],
    			'qty' => $this->input->post('qty')[$x],
    			'rate' => $this->input->post('rate_value')[$x],
    			'amount' => $this->input->post('amount_value')[$x],
    			'sub_total' => $this->input->post('subtotal_value')[$x],
    			'color'=>$attr_color['value'],
    			'size'=>$attr_size['value'],
    			'discount'=>$this->input->post('discount')[$x],
    		);
    		 $this->db->insert('orders_item', $items);

    		// now decrease the stock FROM `the` product
    		$product_data = $this->model_products->getProductDataById($this->input->post('product')[$x]);

             $totalProfit[]=$this->input->post('qty')[$x]*$product_data['price'];

    		$qty = (int) $product_data['qty'] - (int) $this->input->post('qty')[$x];

    		$update_product = array('qty' => $qty);

    		$this->model_products->update($update_product, $this->input->post('product')[$x]);
    	}
  //  print_r($attr_color);
    //die;
    	//echo $this->input->post('gross_amount_value');
    	$grossAmt=$this->input->post('gross_amount_value');
    	$profitEachOrder=$grossAmt-array_sum($totalProfit);
        $arrProfit=array(
         'order_profit'=>$profitEachOrder,
        );
        $this->db->where('id',$order_id)->update('orders',$arrProfit);
		return ($order_id) ? $order_id : false;
	}

	public function countOrderItem($order_id)
	{
		if($order_id) {
			$sql = "SELECT * FROM `orders_item` WHERE order_id = ?";
			$query = $this->db->query($sql, array($order_id));
			return $query->num_rows();
		}
	}

	public function update($id)
	{
		if($id) {
			$user_id = $this->session->userdata('id');
			// fetch the order data 

			$data = array(
				'customer_name' => $this->input->post('customer_name'),
    		    'cust_id'=>$this->input->post('customer_name'),
	    		'customer_address' => $this->input->post('customer_address'),
	    		'customer_phone' => $this->input->post('customer_phone'),
	    		'gross_amount' => $this->input->post('gross_amount_value'),
	    		'service_charge_rate' => $this->input->post('service_charge_rate'),
	    		'service_charge' => ($this->input->post('service_charge_value') > 0) ? $this->input->post('service_charge_value'):0,
	    		'vat_charge_rate' => $this->input->post('vat_charge_rate'),
	    		'vat_charge' => ($this->input->post('vat_charge_value') > 0) ? $this->input->post('vat_charge_value') : 0,
	    		'net_amount' => $this->input->post('net_amount_value'),
	    		'total_discount' => $this->input->post('total_discount'),
	    		'paid_status' => $this->input->post('paid_status'),
	    		'user_id' => $user_id,
	    		'due_date'=>$this->input->post('due_days'),
	    	    'payment_status'=>$this->input->post('payment_status'),
             	
	    	    'paid_date'=>date('Y-m-d h:i:s',strtotime($this->input->post('paid_date'))),
	    	);

	    	if($this->input->post('order_date')){
	    		$data['date_time'] = date('Y-m-d h:i:s',strtotime($this->input->post('order_date')));
	    	}

	        if($this->input->post('gross_amount_value')==0){
            $data['paid_status']=3;  
			}else{
            $data['paid_status']=$this->input->post('paid_status');
	 		}
			// if($this->input->post('paid_status')=='1'){
			// $data['paid_date']=date('Y-m-d h:i:s');
			// }
			$this->db->where('id', $id);
			$update = $this->db->update('orders', $data);

			
			
			// now remove the order item data 
			$this->db->where('order_id', $id);
			$this->db->delete('orders_item');

			// now decrease the product qty
			$count_product = count($this->input->post('product'));
	    	for($x = 0; $x < $count_product; $x++) {
     $attr_id=$this->db->select('attribute_value_id')->from('products')->where('id',$this->input->post('product')[$x])->get()->row_array();
    $attr_val=json_decode($attr_id['attribute_value_id']);
    $attr_color=$this->db->select('value')->from('attribute_value')->where('attribute_parent_id','5')->where_in('id',$attr_val)->get()->row_array(); 
    $attr_size=$this->db->select('value')->from('attribute_value')->where('attribute_parent_id','4')->where_in('id',$attr_val)->get()->row_array(); 
	    		$items = array(
	    			'order_id' => $id,
	    			'product_id' => $this->input->post('product')[$x],
	    			'qty' => $this->input->post('qty')[$x],
	    			'rate' => $this->input->post('rate_value')[$x],
	    			'amount' => $this->input->post('amount_value')[$x],
	    			'discount' => $this->input->post('discount_value')[$x],
    				'sub_total' => $this->input->post('subtotal_value')[$x],
    				'color'=>$attr_color['value'],
    			    'size'=>$attr_size['value'],
	    		);
	    		$this->db->insert('orders_item', $items);

	    		// // now decrease the stock FROM `the` product
	    		$product_data = $this->model_products->getProductDataById($this->input->post('product')[$x]);
                $totalProfit[]=$this->input->post('qty')[$x]*$product_data['price'];
	    	
	    	}
       	$grossAmt=$this->input->post('gross_amount_value');
    	$profitEachOrder=$grossAmt-array_sum($totalProfit);
        $arrProfit=array(
         'order_profit'=>$profitEachOrder,
        );
        $this->db->where('id',$id)->update('orders',$arrProfit);
			return true;
		}
	}

	public function remove($id)
	{
		if($id){
           $orderItem=$this->db->select('*')->from('orders_item')->where('order_id',$id)->get()->result_array();
           foreach ($orderItem as $key => $value) {
            $product_data = $this->model_products->getProductDataById($value['product_id']);
    		$qty = (int) $product_data['qty'] + (int) $value['qty'];
    		$update_product = array('qty' => $qty);
    		$this->model_products->update($update_product, $value['product_id']);
            }
			$this->db->where('id', $id);
			$delete = $this->db->where('id',$id)->update('orders',['paid_status'=>4,'cancel_order_date'=>date('Y-m-d h:i:s')]);
			// $this->db->where('order_id', $id);
			// $delete_item = $this->db->delete('orders_item');
			return ($delete == true) ? true : false;
		}
	}

	public function countTotalPaidOrders()
	{
		$sql = "SELECT * FROM `orders` WHERE paid_status = ?";
		$query = $this->db->query($sql, array(1));
		return $query->num_rows();
	}

	public function checkqty($qty_pr,$product_id){
     // echo $qty."<pre>";
     // echo $product_id;
// print_r($_POST);
// 		die;
    $qty=$this->db->select('qty')->from('products')->where('id',$product_id)->get()->row_array();

	//  print_r($qty['qty']);
	// die;
	 if($qty_pr>$qty['qty']){
       echo"101";
	  }else{
	   echo"102";	
	 }
	}

	public function UpdateOrderInvoice($id,$filename){
    $invoice_array=$this->db->select('invoice')->from('orders')->where('id',$id)->get()->row_array(); 
    $dir=$_SERVER['DOCUMENT_ROOT'].'/inventory/uploads/invoice/';

    if(!empty($invoice_array['invoice'])){
    unlink($dir.'/'.$invoice_array['invoice']);
    $arr=['invoice'=>$filename];
    $this->db->where('id',$id)->update('orders',$arr);
    }else{
    $arr=['invoice'=>$filename];
    $this->db->where('id',$id)->update('orders',$arr);
    }
	}

	public function getOrdersItemDataWithPro($id){
   
    return $this->db->select('a.discount,a.qty,a.rate,a.color,a.size,b.model_no,b.description')->from('orders_item a')->join('products b','b.id=a.product_id')->where('a.order_id',$id)->get()->result_array();
	}

	public function getInvoice($id){
    return $this->db->select('bill_no,cust_id,invoice')->from('orders')->where('id',$id)->get()->row_array();
	}

	public function getCustomerEmail($id){
    return $this->db->select('*')->from('customers')->where('id',$id)->get()->row_array();
	}

}