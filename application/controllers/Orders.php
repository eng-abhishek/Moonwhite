<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// require FCPATH.'vendor/autoload.php';
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;

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
	$this->load->model('model_reports');
		$this->load->model('model_company');
		$this->load->model('model_customers');
	$this->load->model('model_company');
		$this->data['date_format']=$this->model_company->get_currency_date_format();
	$this->date_format=$this->model_company->get_currency_date_format();
	$this->data['company_data'] = $this->model_company->getCompanyData(1);
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
public function fetchOrdersData($id='')
	{
		$result = array('data' => array());

if($id){
		$data = $this->model_orders->getOrdersDataByCustomer($id);
}else{
			$data = $this->model_orders->getOrdersData();
}

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
$sendMail=base_url('orders/send/'.$value['id']);
			$buttons .= '<a href="'.$sendMail.'"><button type="button" class="btn btn-default"><i class="fa fa-send"></i></button></a>';
			
      if($value['paid_status']=='2'){
       $paid_status = '<span class="label label-warning">Not Paid</span>';
       }elseif($value['paid_status']=='3'){ 
       $paid_status = '<span class="label label-info">FOC</span>';
       }elseif( $value['paid_status'] =='1'){
       $paid_status = '<span class="label label-success">Paid</span>';
       }else{
       
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
	        
	         $this->generateInvoice($order_id);
	          
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
	        $this->generateInvoice($id);
	        
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
	                $response['messages'] = "Order has Cancelled Successfully"; 
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
	    
	    public function printDiv($id)
	{
	if(!in_array('viewOrder', $this->permission)){
	        redirect('dashboard', 'refresh');
	    }
	    
	     $order_data = $this->model_orders->getOrdersData($id);
	    // print_r($order_data);
	    // die;
	    $ordersItemsData = $this->model_orders->getOrdersItemData($id);
	    
	    $this->data['order_data']=$order_data;
	    $orders_items=array();
	    
	    foreach($ordersItemsData as $key => $value){
	    //print_r($value);
	    $productVal=$this->model_reports->getproductData($value['product_id']);
	    $orders_items[]=$value; 
	    $orders_items[$key]['model_no_pro']=$productVal['model_no'];
	    $orders_items[$key]['invoice_no_pro']=$productVal['invoice_no'];
	    $orders_items[$key]['pro_name']=$productVal['pro_name'];
	    $orders_items[$key]['unit_pro']=$productVal['unit'];
	    //print_r($productVal);
	    }
	    $this->data['orders_items']=$orders_items;
	    $this->data['company_info']= $this->model_company->getCompanyData(1);
	    $this->data['custID'] = $this->model_orders->getCustomerData($order_data['cust_id']);
	    $this->data['order_date'] = date($this->date_format['date_format'], strtotime($order_data['date_time']));
	    $this->data['paid_status']= ($order_data['paid_status'] == 1) ? "Paid" : "Unpaid";
	    $this->data['order_date_new'] = date($this->date_format['date_format'], strtotime($order_data['date_time']));
	    $this->data['due_days']=$order_data['due_date'];
	    $this->data['next_due_date']=date($this->date_format['date_format'],strtotime($order_data['date_time']. ' +'.$order_data['due_date'].'days'));
	     $fileName = $order_data['bill_no'].'.pdf';
	            $this->pdf->open_invoice('reports/invoice_pdf_file',$this->data,$fileName);
	}
	
	public function generateInvoice($id)
	{
	
	if(!in_array('viewOrder', $this->permission)) {
	           redirect('dashboard', 'refresh');
	       }
	
	$order_data = $this->model_orders->getOrdersData($id);
	    // print_r($order_data);
	    // die;
	    $ordersItemsData = $this->model_orders->getOrdersItemData($id);
	    
	    $this->data['order_data']=$order_data;
	    $orders_items=array();
	    
	    foreach($ordersItemsData as $key => $value){
	    //print_r($value);
	    $productVal=$this->model_reports->getproductData($value['product_id']);
	    $orders_items[]=$value; 
	    $orders_items[$key]['model_no_pro']=$productVal['model_no'];
	    $orders_items[$key]['invoice_no_pro']=$productVal['invoice_no'];
	    $orders_items[$key]['pro_name']=$productVal['pro_name'];
	    $orders_items[$key]['unit_pro']=$productVal['unit'];
	    //print_r($productVal);
	    }
	    $this->data['orders_items']=$orders_items;
	    $this->data['company_info']= $this->model_company->getCompanyData(1);
	    $this->data['custID'] = $this->model_orders->getCustomerData($order_data['cust_id']);
	    $this->data['order_date'] = date($this->date_format['date_format'], strtotime($order_data['date_time']));
	    $this->data['paid_status']= ($order_data['paid_status'] == 1) ? "Paid" : "Unpaid";
	    $this->data['order_date_new'] = date($this->date_format['date_format'], strtotime($order_data['date_time']));
	    $this->data['due_days']=$order_data['due_date'];
	    $this->data['next_due_date']=date($this->date_format['date_format'],strtotime($order_data['date_time']. ' +'.$order_data['due_date'].'days'));
	     //$fileName = $order_data['bill_no'].'.pdf';
	     // echo"<pre>";
		     // print_r($this->data);
		     // die;
		          //$time = time();
		          $fileName = $order_data['bill_no'].'.pdf';
		  $this->model_orders->UpdateOrderInvoice($id,$fileName);
		              // echo"<pre>";
			              // print_r($this->data);
			    
			      $this->pdf->load_view('reports/invoice_pdf_file',$this->data,$fileName);
			   
			   //$this->load->view('reports/invoice_pdf_file',$this->data);
			}
			     function send($id=''){
			     
			     $invoiceData=$this->model_orders->getInvoice($id);
			     $invoiceName=$invoiceData['invoice'];
			     $cust_id=$invoiceData['cust_id'];
			     $bill_no=$invoiceData['bill_no'];
			     
			     $customer=$this->model_orders->getCustomerEmail($cust_id);
			     $custEmail=$customer['email'];
			     $custName=$customer['name'];
			     // echo $invoiceName;
			     // die;
			     $attched_file= $_SERVER["DOCUMENT_ROOT"]."/inventory/uploads/invoice/".$invoiceName;
			     //$attched_file= $_SERVER["DOCUMENT_ROOT"]."/mwp/uploads/invoice/".$invoiceName;
			    
			     $company_info= $this->model_company->getCompanyData(1);
			  //   $url=base_url('uploads/invoice/'.$invoiceName);
			     // echo $url;
			     // die;
	   		  //Create an instance; passing `true` enables exceptions
				$this->load->library('phpmailer_lib');

				// PHPMailer object
				$mail = $this->phpmailer_lib->load();

		     	//$mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host     = 'smtp.gmail.com';    
        $mail->SMTPAuth = true;
        $mail->Username =  'kipm1engg@gmail.com';
        $mail->Password = '8874308699';
        $mail->SMTPSecure = 'ssl';
        $mail->Port     = 465;

		$mail->setFrom($company_info['email'], $company_info['company_name']);
		$mail->addReplyTo($company_info['email'], $company_info['company_name']);
		// $mail->setFrom('from@example.com', 'Mailer');
		// $mail->addAddress('abhisheksoni78655@gmail.com', 'Joe User');     //Add a recipient
		$mail->addAddress($custEmail, $custName);
		// $mail->addAddress('ellen@example.com');               //Name is optional
		// $mail->addReplyTo('kipm1engg@gmail.com', 'Information');
		$mail->addCC($company_info['email']);
		// $mail->addBCC('bcc@example.com');
		// //Attachments
		$mail->addAttachment($attched_file);         //Add attachments
		// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
		//Content
		$mail->isHTML(true);                                  //Set email format to HTML
		$mail->Subject = $company_info['company_name'];
		$mail->Body    = 'Your Order Id Is '.$bill_no;
		$mail->AltBody = 'Your Order Id Is '.$bill_no;
		$mail->send();
		//echo 'Message has been sent';
		redirect('orders','refresh');
			}

		// 	 function test_mail(){

  //            $this->load->library('phpmailer_lib');
        
  //       // PHPMailer object
  //       $mail = $this->phpmailer_lib->load();
  // // print_r($mail);
		// 	//Create an instance; passing `true` enables exceptions
		// // die;
		// 	try {
		// 	    //Server settings
		// 	     //Server settings
		// 	   // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
		// 	    $mail->isSMTP();                                            //Send using SMTP
		// 	    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
		// 	    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
		// 	$mail->Username = 'kipm1engg@gmail.com';
		// 	$mail->Password = '8874308699';
		// 	//    $mail->Username   = 'user@example.com';                     //SMTP username
		// 	//    $mail->Password   = 'secret';                               //SMTP password
		// 	    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
		// 	    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
		// 	    //Recipients
		// 	      $mail->setFrom('kipm1engg@gmail.com', 'Test Mail');
		// 	    $mail->addAddress('abhisheksoni78655@gmail.com', 'Demo User');     //Add a recipient
		// 	    $mail->addReplyTo('kipm1engg@gmail.com', 'Information');
		// 	    // $mail->setFrom('from@example.com', 'Mailer');
		// 	    // $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
		// 	    // $mail->addAddress('ellen@example.com');               //Name is optional
		// 	    // $mail->addReplyTo('info@example.com', 'Information');
		// 	    // $mail->addCC('cc@example.com');
		// 	    // $mail->addBCC('bcc@example.com');
		// 	    // //Attachments
		// 	    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
		// 	    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
		// 	    //Content
		// 	    $mail->isHTML(true);                                  //Set email format to HTML
		// 	    $mail->Subject = 'Here is the subject';
		// 	    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
		// 	    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		// 	    $mail->send();
		// 	    echo 'Message has been sent';
		// 	} catch (Exception $e) {
		// 	    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		// 	}
		// 	 }


         function send_data(){
        // Load PHPMailer library
        $this->load->library('phpmailer_lib');
        
        // PHPMailer object
        $mail = $this->phpmailer_lib->load();
        
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host     = 'smtp.gmail.com';    
        $mail->SMTPAuth = true;
        $mail->Username =  'kipm1engg@gmail.com';
        $mail->Password = '8874308699';
        $mail->SMTPSecure = 'ssl';
        $mail->Port     = 465;
        
        $mail->setFrom('kipm1engg@gmail.com', 'Test Mail');
        $mail->addReplyTo('kipm1engg@gmail.com', 'Information');
        
        // Add a recipient
        $mail->addAddress('abhisheksoni78655@gmail.com', 'Demo User');
        
        // Add cc or bcc 
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');
        
        // Email subject
        $mail->Subject = 'Send Email via SMTP using PHPMailer in CodeIgniter';
        
        // Set email format to HTML
        $mail->isHTML(true);
        
        // Email body content
        $mailContent = "<h1>Send HTML Email using SMTP in CodeIgniter</h1>
            <p>This is a test email sending using SMTP mail server with PHPMailer.</p>";
        $mail->Body = $mailContent;
        
        // Send email
        if(!$mail->send()){
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }else{
            echo 'Message has been sent';
        }
    }


			}