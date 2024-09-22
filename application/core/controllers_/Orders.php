<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();
        $this->load->library('session');
        $this->session->set_userdata('report','0');
		$this->not_logged_in();

		$this->data['page_title'] = 'Orders';

		$this->load->model('model_orders');
		$this->load->model('model_products');
		$this->load->model('model_company');
		$this->load->model('model_customers');
	    $this->load->model('model_company');
		$this->data['date_format']=$this->model_company->get_currency_date_format();
	    $this->date_format=$this->model_company->get_currency_date_format();
	}

	/* 
	* It only redirects to the manage order page
	*/
	public function index()
	{
		if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->data['page_title'] = 'Manage Orders';
		$this->render_template('orders/index', $this->data);		
	 }

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
    public function fetchOrdersData()
	    {


		$result = array('data' => array());

		$data = $this->model_orders->getOrdersData();
       
		foreach ($data as $key => $value) {

			$count_total_item = $this->model_orders->countOrderItem($value['id']);
			// $date = date('d-m-Y', strtotime($value['date_time']));
			// $time = date('h:i a', strtotime($value['date_time']));
            $custData = $this->model_orders->get_customerData($value['cust_id']);
		    $date_time = date($this->date_format['date_format'], strtotime($value['date_time']));
			//$time = date('h:i a', strtotime($value['date_time']));

			// $date_time = $date . ' ' . $time;

			// button
			$buttons = '';

			if(in_array('viewOrder', $this->permission)) {
				$buttons .= '<a target="__blank" href="'.base_url('orders/printDiv/'.$value['id']).'" class="btn btn-default"><i class="fa fa-print"></i></a>';
			}

			if(in_array('updateOrder', $this->permission)) {
				$buttons .= ' <a href="'.base_url('orders/update/'.$value['id']).'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
			}

			if(in_array('deleteOrder', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}

			if($value['paid_status'] == 1) {
				$paid_status = '<span class="label label-success">Paid</span>';	
			}
			else {

            if($value['gross_amount']>0){
                $paid_status = '<span class="label label-warning">Not Paid</span>';
             }else{
                $paid_status = '<span class="label label-info">FOC</span>';
             }
            
			}

			if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
			$net_amt=$value['net_amount'].' '.$this->date_format['currency'];
			}else{
			$net_amt=$this->date_format['currency'].' '.$value['net_amount'];
			}
           if(!empty($custData['name'])){
           $cust=$custData['name'];
           }else{
           $cust="N/A"; 
           }
			$result['data'][$key] = array(
				$value['bill_no'],
				$cust,
				$value['customer_phone'],
				$date_time,
				$count_total_item,
				$net_amt,
				$paid_status,
				$buttons
			);
		} // /foreach
		echo json_encode($result);
	}
	/*
	* If the validation is not valid, then it redirects to the create page.
	* If the validation for each input field is valid then it inserts the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function create()
	{
		// $this->data['products'] = $this->model_products->getActiveProductData();
  //       echo"<pre>";
		// print_r($this->data['products']);
		// die;

		if(!in_array('createOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->data['page_title'] = 'Add Order';

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		
	
        if ($this->form_validation->run() == TRUE) {
        	
        	$order_id = $this->model_orders->create();
        	
        	if($order_id) {
        		$this->session->set_flashdata('success', 'Successfully created');
        		redirect('orders/','refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('orders/create/', 'refresh');
        	}
        }
        else {
            // false case
        	$company = $this->model_company->getCompanyData(1);
        	$this->data['company_data'] = $company;
        	$this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	$this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;

        	$this->data['products'] = $this->model_products->getActiveProductData();

        	$this->data['customers'] = $this->model_customers->getActiveCustomers();      	

            $this->render_template('orders/create', $this->data);
        }	
	}

	/*
	* It gets the product id passed from the ajax method.
	* It checks retrieves the particular product data from the product id 
	* and return the data into the json format.
	*/
	public function getProductValueById()
	{
		$product_id = $this->input->post('product_id');
		if($product_id) {
			$product_data = $this->model_products->getProductDataById($product_id);
			echo json_encode($product_data);
		}
	}

	/*
	* It gets the all the active product inforamtion from the product table 
	* This function is used in the order page, for the product selection in the table
	* The response is return on the json format.
	*/
	public function getTableProductRow()
	{
		$products = $this->model_products->getActiveProductData();
		echo json_encode($products);
	}

	/*
	* If the validation is not valid, then it redirects to the edit orders page 
	* If the validation is successfully then it updates the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function update($id='')
	{   
		
		if(!in_array('updateOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		if(!$id) {
			redirect('dashboard', 'refresh');
		}

		$this->data['page_title'] = 'Update Order';

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		
	
        if ($this->form_validation->run() == TRUE) {        	
        	
        	$update = $this->model_orders->update($id);
        	
        	if($update == true) {
        		$this->session->set_flashdata('success', 'Successfully updated');
        		redirect('orders/', 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('orders/update/'.$id, 'refresh');
        	}
        }
        else {
            // false case
        	$company = $this->model_company->getCompanyData(1);
        	$this->data['company_data'] = $company;
        	$this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	$this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;

        	$result = array();
        	$orders_data = $this->model_orders->getOrdersData($id);

    		$result['order'] = $orders_data;
    		$orders_item = $this->model_orders->getOrdersItemData($orders_data['id']);

    		foreach($orders_item as $k => $v) {
    			$result['order_item'][] = $v;
    		}

    		$this->data['order_data'] = $result;
            $this->data['edit_id']=$id;
        	$this->data['products'] = $this->model_products->getActiveProductData();      
        	$this->data['customers'] = $this->model_customers->getActiveCustomers();  	

            $this->render_template('orders/edit', $this->data);
        }
	}

	/*
	* It removes the data from the database
	* and it returns the response into the json format
	*/
	public function remove()
	{
		if(!in_array('deleteOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$order_id = $this->input->post('order_id');

        $response = array();
        if($order_id) {
            $delete = $this->model_orders->remove($order_id);

            if($delete == true) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed"; 
            }
            else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the product information";
            }
        }
        else {
            $response['success'] = false;
            $response['messages'] = "Refersh the page again!!";
        }

        echo json_encode($response); 
	}

	/*
	* check quentity avaliablity 
	* ewhen order the product
	*/
	public function checkqty(){
	$qty=$this->input->post('qty');
	$product_id=$this->input->post('product_id');
    $this->model_orders->checkqty($qty,$product_id);
    }

	/*
	* It gets the product id and fetch the order data. 
	* The order print logic is done here 
	*/
	public function printDiv($id)
	{
	   // echo"<pre>";	 
	   // $orders_items = $this->model_products->getOrdersItemData($id);
	   // print_r($orders_items);
    //    die;
		if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        
		if($id) {
			$order_data = $this->model_orders->getOrdersData($id);
			$orders_items = $this->model_orders->getOrdersItemData($id);
			$company_info = $this->model_company->getCompanyData(1);

			$order_date = date($this->date_format['date_format'], strtotime($order_data['date_time']));
			$paid_status = ($order_data['paid_status'] == 1) ? "Paid" : "Unpaid";

			$order_date_new = date($this->date_format['date_format'], strtotime($order_data['date_time']));
    		//$next_due_date =  date($this->date_format['date_format'], strtotime($order_date_new. ' + 30 days'));
    		$due_days=$order_data['due_date'];
    		$next_due_date=date(MY_DATE_FORMAT,strtotime($order_data['date_time']. ' +'.$order_data['due_date'].'days'));    		

			$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
			  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.min.css').'">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper">
			  <section class="invoice">
			    <!-- title row -->
			    <div class="row">
			    	<div class="col-xs-2">
			    	<div>
				       <img src="../../assets/images/mwlogo.png" style="vertical-align:middle;height: 72px;position: absolute;
margin-left: -1vw;margin-top: 1.5vh;">
				    </div>
			      	</div>

			      <div class="col-xs-10">
			        <h2 class="page-header">
			         '.$company_info['company_name'].'			          
			        </h2>
			       
			        <h2 class="page-header">
			          '.$company_info['address'].'	 '.$company_info['phone'].'	           
			        </h2>
			       		       
			      </div>
			      <!-- /.col -->
			      <br/>
			      <br/>
			    </div>
			    <!-- info row -->
			    <div class="row invoice-info">
			      
			      <div class="col-sm-4 invoice-col">
			        
			        <b>Date:</b> '.$order_date.'<br>
			        <b>Invoice Number:</b> MW00'.$order_data['id'].'<br>
			        <b>Bill ID:</b> '.$order_data['bill_no'].'<br>
			      </div>
			      <div class="col-sm-4 invoice-col">
			      
			      </div>

			      <div class="col-sm-4 invoice-col">
			        
			        <b>Terms:</b> '.$due_days.' Days <br>
			        <b>Pay Date:</b> '.$next_due_date.' <br>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			    <!-- Table row -->


			     <div class="row invoice-info">
			      <br/>
			      <div class="col-sm-4 invoice-col">
			        <h4>Billing Address</h4>
			       
			        <b>Name:</b> '.$order_data['customer_name'].'<br>
			        <b>Address:</b> '.$order_data['customer_address'].' <br />
			        <b>Phone:</b> '.$order_data['customer_phone'].'
			      </div>

			       <div class="col-sm-4 invoice-col">
			       </div>

			      <div class="col-sm-4 invoice-col">
			        <h4>Shipping Address</h4>			        
			        <b>Name:</b> '.$order_data['customer_name'].'<br>
			        <b>Address:</b> '.$order_data['customer_address'].' <br />
			        <b>Phone:</b> '.$order_data['customer_phone'].'
			      </div>

			      <!-- /.col -->
			    </div>


			    <div class="row">
			    <br/>
			      <div class="col-xs-12 table-responsive">
			        <table class="table table-striped">
			          <thead>
			          <tr>
			          	<th>Model No</th>
			            <th>Product name</th>
			            <th>Color</th>
			            <th>Size</th>
			            <th>Price</th>
			            <th>Qty</th>
			            <th>Amount</th>
			          </tr>
			          </thead>
			          <tbody>'; 
			          foreach ($orders_items as $k => $v) {
			       

            if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
             $amt=$v['amount'].' '.$this->date_format['currency'];
             $rate=$v['rate'].' '.$this->date_format['currency'];
            }else{
             $amt=$this->date_format['currency'].' '.$v['amount'];
             $rate=$this->date_format['currency'].' '.$v['rate'];
            }
			$product_data = $this->model_products->getProductDataById($v['product_id']); 
			        
			          	$html .= '<tr>
			          	 	<td>'.$product_data['model_no'].'</td>
				            <td>'.$product_data['description'].'</td>
				            <td>'.$v['color'].'</td>
				            <td>'.$v['size'].'</td>
				            <td>'.$rate.'</td>
				            <td>'.$v['qty'].'</td>
				            <td>'.$amt.'</td>
			          	</tr>';
			          }
			          
			          $html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			    <div class="row">
			      
			      <div class="col-xs-5 pull-right">

			        <div class="table-responsive">';
			 if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
             $char_serv=$order_data['service_charge'].' '.$this->date_format['currency'];
             $rate_serv=$order_data['service_charge_rate'].' '.$this->date_format['currency'];
             $vat_ch=$order_data['vat_charge'].' '.$this->date_format['currency'];
             $vat_ch_rate=$order_data['vat_charge_rate'].' '.$this->date_format['currency'];
             $total_discount=$order_data['total_discount'].' '.$this->date_format['currency'];
             $net_amount=$order_data['net_amount'].' '.$this->date_format['currency'];
             $gross_amount=$order_data['gross_amount'].' '.$this->date_format['currency'];

            }else{
             $char_serv=$this->date_format['currency'].' '.$order_data['service_charge'];
             $rate_serv=$this->date_format['currency'].' '.$order_data['service_charge_rate'];
             $vat_ch=$this->date_format['currency'].' '.$order_data['vat_charge'];
             $vat_ch_rate=$this->date_format['currency'].' '.$order_data['vat_charge_rate'];
             $total_discount=$this->date_format['currency'].' '.$order_data['total_discount'];
             $net_amount=$this->date_format['currency'].' '.$order_data['net_amount'];
             $gross_amount=$this->date_format['currency'].' '.$order_data['gross_amount'];
            }
			        $html.='<table class="table">
			            <tr>
			              <th style="width:50%">Gross Amount:</th>
			              <td>'.$gross_amount.'</td>
			            </tr>';
			            if($order_data['service_charge'] > 0) {

         			     $html .= '<tr>
				              <th>Service Charge ('.$rate_serv.'%)</th>
				              <td>'.$char_serv.'</td>
				            </tr>';
			            }

			            if($order_data['vat_charge'] > 0) {
			            	$html .= '<tr>
				              <th>Vat Charge ('.$vat_ch_rate.'%)</th>
				              <td>'.$vat_ch.'</td>
				            </tr>';
			            }
			            
			         
			            $html .=' <tr>
			              <th>Discount:</th>
			              <td>'.$total_discount.'</td>
			            </tr>
			            <tr>
			              <th>Net Amount:</th>
			              <td>'.$net_amount.'</td>
			            </tr>
			           
			          </table>
			        </div>
			      </div>

			     
			      <!-- /.col -->
			    </div>

			     <div class="row invoice-info">
			      
			      <div class="col-sm-4 invoice-col">
			        <b>Checker :</b> <br>
			        <b>Date :</b>  <br />			        
			      </div>

			       <div class="col-sm-4 invoice-col">
			       </div>

			      <div class="col-sm-4 invoice-col">			        	        
			        <b>Customer Signature :</b>  <br>
			        <b>Date :</b>  <br />			        
			      </div>

			      <!-- /.col -->
			    </div>

			    <!-- /.row -->
			  </section>
			  <!-- /.content -->
			</div>
		</body>
	</html>';

			  echo $html;
		}
	}

}