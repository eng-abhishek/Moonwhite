<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Products extends Admin_Controller 
{
    public function __construct()
    {
        parent::__construct();

        $this->not_logged_in();
        $this->load->library('session');
        $this->session->set_userdata('report','0');
        $this->data['page_title'] = 'Products';

        $this->load->model('model_products');
        // $this->load->model('model_brands');
        $this->load->model('model_category');
        // $this->load->model('model_stores');
        $this->load->model('model_attributes');
        $this->load->model('model_company');
        $this->data['date_format']=$this->model_company->get_currency_date_format();
        $this->date_format=$this->model_company->get_currency_date_format();
         $this->data['company_data'] = $this->model_company->getCompanyData(1);
    }

    /* 
    * It only redirects to the manage product page
    */
    public function index()
    {
        if(!in_array('viewProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $this->render_template('products/index', $this->data);  
    }

    /*
    * It Fetches the products data from the product table 
    * this function is called from the datatable ajax function
    */
    public function fetchProductData()
    {
        $result = array('data' => array());

        $data = $this->model_products->getProductData();
        foreach ($data as $key => $value) {
            $model_url=base_url('reports/product_model').'/'.$value['model_no'];

            //$store_data = $this->model_stores->getStoresData($value['store_id']);
            // button
            $buttons = '';
            if(in_array('updateProduct', $this->permission)) {
                $buttons .= '<a href="'.base_url('products/update/'.$value['id']).'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
            }

            if(in_array('deleteProduct', $this->permission)) {
                $buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';

                $buttons .= ' <button type="button" class="btn btn-default" onclick="cloneProd('.$value['id'].')" data-toggle="modal" data-target="#cloneModal"><i class="fa fa-clone"></i></button>';
            }

            $buttons .= '<a href="'.$model_url.'" target="_blank"><button type="button" class="btn btn-default"><i class="fa fa-external-link-square"></i></button></a>';  

            if($value['image']=='<p>You did not select a file to upload.</p>'){
            $img = '<img src="'.base_url('assets/images/product_image/default_user.png').'" alt="'.$value['name'].'" class="img-circle" width="50" height="50" />';
            }else{
            $img = '<img src="'.base_url($value['image']).'" alt="'.$value['name'].'" class="img-circle" width="50" height="50" />';    
            }          
            
            $saleOutStock = $value['initial_qty']-$value['qty'];

            $qty_status = '';
            if($value['qty'] <= 10) {
                $qty_status = '<span class="label label-warning">Low !</span>';
            } else if($value['qty'] <= 0) {
                $qty_status = '<span class="label label-danger">Out of stock !</span>';
            }
               
           if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
            $price=$value['price'].' '.$this->date_format['currency'];
            $cif=$value['cif'].' '.$this->date_format['currency'];
            $price_selling=$value['price_selling'].' '.$this->date_format['currency'];
            }else{
            $price=$this->date_format['currency'].' '.$value['price'];
            $cif=$this->date_format['currency'].' '.$value['cif'];
            $price_selling=$this->date_format['currency'].' '.$value['price_selling'];
            }

        $result['data'][$key] = array(
                $value['oth_invoice_no'],
                //date($this->date_format['date_format'],strtotime($value['delivery_date'])),
                $img,
                $value['description'],    
                $value['model_no'],
                $value['color'],
                //$value['size'],
                $cif,
                $price,
                $price_selling,
                $value['initial_qty'],
                $value['qty'] . ' ' . $qty_status,
                //$store_data['name'],
                $saleOutStock,
                $buttons
            );
        } // /foreach
        echo json_encode($result);
    }   

    /*
    * If the validation is not valid, then it redirects to the create page.
    * If the validation for each input field is valid then it inserts the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
    public function create($invoice_no='')
    {
        if(!in_array('createProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        //$this->form_validation->set_rules('product_name', 'Product name', 'trim|required');
        $this->form_validation->set_rules('description', 'Product Description', 'trim|required');
        
        $this->form_validation->set_rules('model_no', 'Product Model', 'trim|required');
        //$this->form_validation->set_rules('sku', 'SKU', 'trim|required');
        $this->form_validation->set_rules('price', 'WSP Price', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');
       // $this->form_validation->set_rules('product_image', 'Product Image', 'trim|required');
        //$this->form_validation->set_rules('availability', 'Availability', 'trim|required');
        
        if ($this->form_validation->run() == TRUE){
            // true case
            $upload_image = $this->upload_image();
            if($this->input->post('attributes_value_id')){
             $attr_val=json_encode($this->input->post('attributes_value_id'));
            }else{
             $attr_val='';
            }
            if($this->input->post('category')){
             $cat_value=json_encode($this->input->post('category'));
            }else{
             $cat_value='';
            }
            $data = array(
                'name' => 'Null',
                'invoice_id' => $this->input->post('factory_id'),
                'model_no' => $this->input->post('model_no'),
                'cif' => $this->input->post('cif'),
                'sku' => $this->input->post('sku'),
                'unit' => $this->input->post('unit'),
                'price' => $this->input->post('price'),
                'price_selling' => $this->input->post('price_selling'),
                'qty' => $this->input->post('qty'),
                'image' => $upload_image,
                'description' => $this->input->post('description'),
                'attribute_value_id' =>$attr_val,
                'category_id' =>$cat_value,
                'initial_qty' => $this->input->post('qty'),
                'availability' => $this->input->post('availability'),
                  );

            $create = $this->model_products->create($data);
            if($create == true) {
                $this->session->set_flashdata('success', 'Successfully created');
                redirect('products/', 'refresh');
            }
            else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('products/create', 'refresh');
            }
        }
        else {
            // false case

            // attributes 
            $attribute_data = $this->model_attributes->getActiveAttributeData();

            $attributes_final_data = array();
            foreach ($attribute_data as $k => $v) {
                $attributes_final_data[$k]['attribute_data'] = $v;
                $value = $this->model_attributes->getAttributeValueData($v['id']);
                $attributes_final_data[$k]['attribute_value'] = $value;
            }

            $this->data['attributes'] = $attributes_final_data;
            // $this->data['brands'] = $this->model_brands->getActiveBrands();   
            $this->data['invoice_no'] = $this->model_products->getInvoiceNo();           
            $this->data['category'] = $this->model_category->getActiveCategroy();           
            // $this->data['stores'] = $this->model_stores->getActiveStore();    
            $this->data['invoice_Id']=base64_decode($invoice_no);       

            $this->render_template('products/create', $this->data);
        }   
    }

    /*
    * This function is invoked from another function to upload the image into the assets folder
    * and returns the image path
    */
    public function upload_image()
    {
        // assets/images/product_image
        $config['upload_path'] = 'assets/images/product_image';
        $config['file_name'] =  uniqid();
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1000';

        // $config['max_width']  = '1024';s
        // $config['max_height']  = '768';

        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('product_image'))
        {
            $error = $this->upload->display_errors();
            return $error;
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $type = explode('.', $_FILES['product_image']['name']);
            $type = $type[count($type) - 1];
            
            $path = $config['upload_path'].'/'.$config['file_name'].'.'.$type;
            return ($data == true) ? $path : false;            
        }
    }

    /*
    * If the validation is not valid, then it redirects to the edit product page 
    * If the validation is successfully then it updates the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
    public function update($product_id)
    {      
        if(!in_array('updateProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        if(!$product_id) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('description', 'Product Description', 'trim|required');
        $this->form_validation->set_rules('model_no', 'Product Model', 'trim|required');
        $this->form_validation->set_rules('price', 'WSP Price', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            // true case
            if($this->input->post('attributes_value_id01')){
             $attr_val=json_encode($this->input->post('attributes_value_id01'));
            }elseif($this->input->post('attributes_value_id02')){
             $attr_val=json_encode($this->input->post('attributes_value_id02'));
            }else{
             $attr_val='';   
            }
            if($this->input->post('category')){
             $cat_value=json_encode($this->input->post('category'));
            }else{
             $cat_value='';
            }
            $data = array(
                'name' => 'NULL',
                'invoice_id' => $this->input->post('factory_id'),
                'model_no' => $this->input->post('model_no'),
                'cif' => $this->input->post('cif'),
                'sku' => $this->input->post('sku'),
                'unit' => $this->input->post('unit'),
                'price' => $this->input->post('price'),
                'price_selling' => $this->input->post('price_selling'),
                'qty' => $this->input->post('qty'),
                'description' => $this->input->post('description'),
                'attribute_value_id' =>$attr_val,
                'category_id' =>$cat_value,
                'availability' => $this->input->post('availability'),
                 );
            
            if($_FILES['product_image']['size'] > 0) {
                $upload_image = $this->upload_image();
                $upload_image = array('image' => $upload_image);
                
                $this->model_products->update($upload_image, $product_id);
            }

            $update = $this->model_products->update($data, $product_id);
            if($update == true) {
                $this->session->set_flashdata('success', 'Successfully updated');
                redirect('products/', 'refresh');
            }
            else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('products/update/'.$product_id, 'refresh');
            }
        }
        else {
            // attributes 
            $attribute_data = $this->model_attributes->getActiveAttributeData();

            $attributes_final_data = array();
            foreach ($attribute_data as $k => $v) {
                $attributes_final_data[$k]['attribute_data'] = $v;

                $value = $this->model_attributes->getAttributeValueData($v['id']);

                $attributes_final_data[$k]['attribute_value'] = $value;
            }
            
            // false case
            $this->data['attributes'] = $attributes_final_data;
            //$this->data['brands'] = $this->model_brands->getActiveBrands();         
            $this->data['category'] = $this->model_category->getActiveCategroy();           
            //$this->data['stores'] = $this->model_stores->getActiveStore();          
            $this->data['invoice_no'] = $this->model_products->getInvoiceNo();

            $product_data = $this->model_products->getProductDataById($product_id);
            $this->data['product_data'] = $product_data;
         
            $this->render_template('products/edit', $this->data); 
        }   
    }

    /*
    * It removes the data from the database
    * and it returns the response into the json format
    */
    public function remove()
    {
        if(!in_array('deleteProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        
        $product_id = $this->input->post('product_id');

        $response = array();
        if($product_id) {
            $delete = $this->model_products->remove($product_id);
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


 public function clone()
    {
          
        $response = array();      
        $product_id = $this->input->post('product_id');

            $table = "products";
            $primary_key_field = "id";
        // function DuplicateRecord($table, $primary_key_field, $primary_key_val) { 
            /* CREATE SELECT QUERY */ 
            $this->db->where('id', $product_id); 
            $query = $this->db->get($table); 
            foreach ($query->result() as $val){
                 //   print_r($val);
                $data = array(
                'name' =>   'NULL',
                'invoice_id' => $val->invoice_id,
                'model_no' => $val->model_no.'-'.'cloned',
                'cif' => $val->cif,
                'sku' => $val->sku,
                'unit' => $val->unit,
                'price' =>$val->price,
                'initial_qty'=>$val->initial_qty,
                'qty' => $val->qty,
                'image'=>$val->image,
                'description' => $val->description,
                'attribute_value_id' =>$val->attribute_value_id,
                'brand_id' => $val->brand_id,
                'category_id' => $val->category_id,
                'store_id' =>$val->store_id,
                'availability' =>$val->availability,

                //'delivery_date' => $val->delivery_date,
                //'invoice_no' =>$val->invoice_no,
                'price_selling' =>$val->price_selling,
                );
                $this->model_products->create($data);
            } //endforeach 
            
            //insert the new record into table 
           //$this->db->insert($table); 

        $response['success'] = true;
        $response['messages'] = "Successfully Cloned"; 

        echo json_encode($response);
    }


    // public function clone()
    // {
          
    //     $response = array();      
    //     $product_id = $this->input->post('product_id');

    //         $table = "products";
    //         $primary_key_field = "id";
    //     // function DuplicateRecord($table, $primary_key_field, $primary_key_val) { 
    //         /* CREATE SELECT QUERY */ 
    //         $this->db->where('id', $product_id); 
    //         $query = $this->db->get($table); 

           

    //         foreach ($query->result() as $row){
    //             foreach($row as $key=>$val) { 
    //                 if($key != $primary_key_field) {           
    //                     //Below code can be used instead of passing a data array directly to the insert or update functions  
    //                     // print_r($key);
    //                     // echo "cloned";
    //                     if($key == "name")
    //                     {
    //                         echo"HHHHHH-->".$val.-'cloned';
    //                         $this->db->set($key, $val."-cloned");     
    //                     }
    //                     else{
    //                         $this->db->set($key, $val);     
    //                     }
                        
                        


    //                 } //endif 
    //                                              print_r($val);  
                  
    //             } //endforeach

     
    //         } //endforeach 
  
    //     die;    
    //         //insert the new record into table 
        
    //     //$this->db->insert($table); 

    //     $response['success'] = true;
    //     $response['messages'] = "Successfully Cloned"; 

    //    // }
    //    // print_r($this->db->get_compiled_select());    
    //     // exit;
    //     //exit;
    //     echo json_encode($response);
    // }

      public function view_product_list($id){
      //echo "<pre>";
      $id=base64_decode($id);
      $this->data['dataList']=$this->model_products->getProductDataList($id);  
      //print_r($this->data['dataList']);
      $this->render_template('products/product_list', $this->data);
    }

}