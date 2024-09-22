<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Orders</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Orders</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

        <?php if($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php elseif($this->session->flashdata('error')): ?>
          <div class="alert alert-error alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('error'); ?>
          </div>
        <?php endif; ?>


        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Edit Order</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" action="<?php base_url('orders/update')?>" method="post" class="form-horizontal">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <div class="form-group">
                  <label for="date" class="col-sm-12 control-label">Date: <?php echo date('Y-m-d') ?></label>
                </div>
                <div class="form-group">
                  <label for="time" class="col-sm-12 control-label">Date: <?php echo date('h:i a') ?></label>
                </div>

                <div class="col-md-12 col-xs-12 pull pull-left">
                  <div class="form-group">
                    <label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">Customer Name</label>
                    <div class="col-sm-7">
                     <!--  <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter Customer Name" value="<?php echo $order_data['order']['customer_name'] ?>" autocomplete="off"/> -->
                     <?php// print_r($order_data['order']['customer_name']);  ?>
                      <input type="text" name="edit_id" value="<?php echo $edit_id;?>" hidden>
                      <select class="form-control select_group customer_name" required="" id="customer_name" name="customer_name" style="width:100%;" onchange="getProductData()" required>
                              <option value="">~~Select~~</option>
                              <?php foreach ($customers as $k => $v): ?>
                              <option value="<?php echo $v['id'] ?>" cust_address="<?php echo $v['address'] ?>" cust_phone="<?php echo $v['contact'] ?>" <?php if($order_data['order']['cust_id'] == $v['id']) { echo "selected='selected'"; } ?>><?php echo $v['code'].'-'.$v['name']; ?></option>
                              <?php endforeach   
                              ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">Customer Address</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="customer_address" name="customer_address" placeholder="Enter Customer Address" value="<?php echo $order_data['order']['customer_address'] ?>" autocomplete="off">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">Customer Phone</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="customer_phone" name="customer_phone" placeholder="Enter Customer Phone" value="<?php echo $order_data['order']['customer_phone'] ?>" autocomplete="off">
                    </div>
                  </div>

                  <div class="form-group">
                     <label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">Terms</label>
                    <div class="col-sm-7">
                    <select name="due_days" class="form-control" required="">
                    <option value="">~~Select~~</option>   
                    <?php for($i=0;$i<=60;$i++){ ?>
                    <option value="<?php echo $i;?>" <?php if($order_data['order']['due_date'] == $i){ echo"selected";} ?>><?php echo $i.' '.'Days';?></option>
                    <?php } ?>
                    </select>
                    </div>
                  </div>

                </div>
                
                
                <br /> <br/>
                <table class="table table-bordered" id="product_info_table">
                  <thead>
                    <tr>
                      <th style="width:40%">Product</th>
                      <th style="width:10%">Qty</th>
                      <th style="width:10%">Rate</th>
                      <th style="width:10%">Amount</th>
                      <th style="width:15%">Discount</th>
                      <th style="width:30%">Sub Total</th>
                     <!--  <th style="width:10%"><button type="button" id="add_row" class="btn btn-default"><i class="fa fa-plus"></i></button></th> -->
                    </tr>
                  </thead>

                   <tbody>

                    <?php if(isset($order_data['order_item'])): ?>
                      <?php $x = 1; ?>
                      <?php foreach ($order_data['order_item'] as $key => $val): ?>
                        <?php //print_r($v); ?>
                       <tr id="row_<?php echo $x; ?>" class="qty_row">
                         <td>
                          <select class="form-control select_group product" data-row-id="row_<?php echo $x; ?>" id="product_<?php echo $x; ?>" name="product[]" style="width:100%;" onchange="getProductData(<?php echo $x; ?>)" required>
                              <option value=""></option>
                              <?php foreach ($products as $k => $v): ?>
                                <?php $des=str_replace(' ','',$v['description']);?>
                                <option value="<?php echo $v['id'] ?>" <?php if($val['product_id'] == $v['id']) { echo "selected='selected'"; } ?>><?php echo $v['model_no'].'-'.$v['color'].'-'.$des.'-'.$v['qty'].' '.'qty avaliable';?></option>
                              <?php endforeach ?>
                            </select>
                          </td>
                          <td><input type="text" name="qty[]" id="qty_<?php echo $x; ?>" class="form-control" required readonly onkeyup="getTotal(<?php echo $x; ?>)" value="<?php echo $val['qty'] ?>" autocomplete="off"></td>
                          <td>
                            <input type="text" name="rate[]" id="rate_<?php echo $x; ?>" class="form-control" value="<?php echo $val['rate'] ?>" onkeyup="onkeyup_rate_edit('<?php echo $x;?>')" autocomplete="off">
                            <input type="hidden" name="rate_value[]" id="rate_value_<?php echo $x; ?>" class="form-control" value="<?php echo $val['rate'] ?>" autocomplete="off">
                          </td>
                          <td>
                            <input type="text" name="amount[]" id="amount_<?php echo $x; ?>" class="form-control" disabled value="<?php echo $val['amount'] ?>" autocomplete="off">
                            <input type="hidden" name="amount_value[]" id="amount_value_<?php echo $x; ?>" class="form-control" value="<?php echo $val['amount'] ?>" autocomplete="off">
                          </td>


                          <td>                          
                            <input type="text" name="discount[]" id="discount_<?php echo $x; ?>" class="form-control" autocomplete="off" onkeyup="getDiscountTotal(<?php echo $x; ?>)" value="<?php echo $val['discount'] ?>">
                             <input type="hidden" name="discount_value[]" id="discount_value_<?php echo $x; ?>" class="form-control" autocomplete="off" value="<?php echo $val['discount'] ?>">
                          </td>
                          <td>
                            <input type="text" name="subtotal[]" id="subtotal_<?php echo $x; ?>" class="form-control" disabled autocomplete="off" value="<?php echo $val['sub_total'] ?>">
                            <input type="hidden" name="subtotal_value[]" id="subtotal_value_<?php echo $x; ?>" class="form-control" autocomplete="off" value="<?php echo $val['sub_total'] ?>">
                          </td>
                          <td><button type="button" class="btn btn-default" onclick="removeRow('<?php echo $x; ?>')"><i class="fa fa-close"></i></button></td>
                       </tr>
                       <?php $x++; ?>
                     <?php endforeach; ?>
                   <?php endif; ?>
                   </tbody>
                </table>

                <br/> <br/>

                <div class="col-md-6 col-xs-12 pull pull-right">
                  <div class="form-group">
                    <label for="gross_amount" class="col-sm-5 control-label">Gross Amount</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="gross_amount" name="gross_amount" disabled value="<?php echo $order_data['order']['gross_amount'] ?>" autocomplete="off">
                      <input type="hidden" class="form-control" id="gross_amount_value" name="gross_amount_value" value="<?php echo $order_data['order']['gross_amount'] ?>" autocomplete="off">
                    </div>
                  </div>
                  <?php 
                  
                  if($is_service_enabled == true): ?>
              <!--     <div class="form-group">
                    <label for="service_charge" class="col-sm-5 control-label">S-Charge <?php echo $company_data['service_charge_value'] ?> %</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="service_charge" name="service_charge" disabled value="<?php echo $order_data['order']['service_charge'] ?>" autocomplete="off">
                      <input type="hidden" class="form-control" id="service_charge_value" name="service_charge_value" value="<?php echo $order_data['order']['service_charge'] ?>" autocomplete="off">
                    </div>
                  </div> -->
                  <?php endif; 
                  
                  ?>
                  <?php if($is_vat_enabled == true): ?>
                  <div class="form-group">
                    <label for="vat_charge" class="col-sm-5 control-label">Vat <?php echo $company_data['vat_charge_value'] ?> %</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="vat_charge" name="vat_charge" disabled value="<?php echo $order_data['order']['vat_charge'] ?>" autocomplete="off">
                      <input type="hidden" class="form-control" id="vat_charge_value" name="vat_charge_value" value="<?php echo $order_data['order']['vat_charge'] ?>" autocomplete="off">
                    </div>
                  </div>
                  <?php endif; ?>

                  <div class="form-group">
                    <label for="discount" class="col-sm-5 control-label">Total Discount</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="total_discount" name="total_discount" autocomplete="off" value="<?php echo $order_data['order']['total_discount'] ?>" readonly> 

                    </div>
                  </div>
                 
                  <div class="form-group">
                    <label for="net_amount" class="col-sm-5 control-label">Net Amount</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="net_amount" name="net_amount" disabled value="<?php echo $order_data['order']['net_amount'] ?>" autocomplete="off">
                      <input type="hidden" class="form-control" id="net_amount_value" name="net_amount_value" value="<?php echo $order_data['order']['net_amount'] ?>" autocomplete="off">
                    </div>
                  </div>

                 <?php 
                 $order_data['order']['date_time'];
                 if($order_data['order']['date_time']){
                 $order_date=date('Y-m-d',strtotime($order_data['order']['date_time'])); 
                 }else{
                 $order_date='';                
                      }
                 ?>

                   <div class="form-group" id="order_date">
                    <label for="order_date" class="col-sm-5 control-label">Order Date</label>
                    <div class="col-sm-7">
                     <input type="date" name="order_date" required="" value="<?php echo $order_date;?>" class="form-control" id="orderDateInput">
                    </div>
                    </div>

                   <div class="form-group">
                    <label for="net_amount" class="col-sm-5 control-label">Payment Status</label>
                    <div class="col-sm-7">
                    <select name="payment_status" required class="form-control">
                    <option value="">~~select~~</option>
                    <option value="cod" <?php if($order_data['order']['payment_status']=='cod'){ echo"selected"; } ?>>COD</option>
                    <option value="cheque" <?php if($order_data['order']['payment_status']=='cheque'){ echo"selected"; } ?>>Cheque</option>
                    </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="paid_status" class="col-sm-5 control-label">Paid Status</label>
                    <div class="col-sm-7">
                      <select type="text" class="form-control" id="paid_status" onchange="checkpaidStatus(this.value)" name="paid_status" required="">
                        <option value="1" <?php if($order_data['order']['paid_status'] == 1) { echo "selected='selected'"; } ?>>Paid</option>
                        <option value="2" <?php if($order_data['order']['paid_status'] == 2) { echo "selected='selected'"; } ?>>Unpaid</option>
                      </select>
                    </div>
                  </div>
              
                <?php 
                 $order_data['order']['paid_date'];
                 if($order_data['order']['paid_date']){
                 $date=date('Y-m-d',strtotime($order_data['order']['paid_date'])); 
                 }
                 ?>

                <?php 
                if(!empty($order_data['order']['paid_date'])){
                 $date=date('Y-m-d',strtotime($order_data['order']['paid_date'])); ?>
                    <div class="form-group" id="paid_date">
                    <label for="paid_status" class="col-sm-5 control-label">Paid Date</label>
                    <div class="col-sm-7">
                     <input type="date" value="<?php echo $date;?>" name="paid_date" class="form-control" id="paidDateInput" required="">
                    </div>
                    </div>
                 <?php }else{ ?>
                    <div class="form-group" id="paid_date" style="display: none;">
                    <label for="paid_status" class="col-sm-5 control-label">Paid Date</label>
                    <div class="col-sm-7">
                     <input type="date" name="paid_date" class="form-control" id="paidDateInput">
                    </div>
                  </div>
                 <?php    }
                  ?>
      
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">

                <!-- <input type="hidden" name="service_charge_rate" value="<?php echo $company_data['service_charge_value'] ?>" autocomplete="off"> -->
                <input type="hidden" name="vat_charge_rate" value="<?php echo $company_data['vat_charge_value'] ?>" autocomplete="off">

                <a target="__blank" href="<?php echo base_url() . 'orders/printDiv/'.$order_data['order']['id'] ?>" class="btn btn-default" >Print</a>
                <button type="submit" class="btn btn-primary" id="createOrder">Save Changes</button>
                <a href="<?php echo base_url('orders/') ?>" class="btn btn-warning">Back</a>
              </div>
            </form>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- col-md-12 -->
    </div>
    <!-- /.row -->
    

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">
  var base_url = "<?php echo base_url(); ?>";
  function checkpaidStatus(val){
   if(val==1){
   $('#paid_date').show(); 
   $('#paidDateInput').prop('required',true);
   }else{
   $('#paid_date').hide();
   $('#paidDateInput').prop('required',false); 
   }
  }

  // function printOrder(id)
  // {
  //   if(id) {
  //     $.ajax({
  //       url: base_url + 'orders/printDiv/' + id,
  //       type: 'post',
  //       success:function(response) {
  //         var mywindow = window.open('', 'new div', 'height=400,width=600');
  //         // mywindow.document.write('<html><head><title></title>');
  //         // mywindow.document.write('<link rel="stylesheet" href="<?php //echo base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>" type="text/css" />');
  //         // mywindow.document.write('</head><body >');
  //         mywindow.document.write(response);
  //         // mywindow.document.write('</body></html>');

  //         mywindow.print();
  //         mywindow.close();

  //         return true;
  //       }
  //     });
  //   }
  // }

  $(document).ready(function() {
    setTimeout(function(){
      checkDate();
    },500);
    $(".select_group").select2();
    // $("#description").wysihtml5();

    $("#mainOrdersNav").addClass('active');
    $("#manageOrdersNav").addClass('active');
    
    
    // Add new row in the table 
    $("#add_row").unbind('click').bind('click', function() {
      var table = $("#product_info_table");
      var count_table_tbody_tr = $("#product_info_table tbody tr").length;
      var row_id = count_table_tbody_tr + 1;

      $.ajax({
          url: base_url + '/orders/getTableProductRow/',
          type: 'post',
          dataType: 'json',
          success:function(response) {
            

              // console.log(reponse.x);
               var html = '<tr id="row_'+row_id+'" calss="qty_row">'+
                   '<td>'+ 
                    '<select class="form-control select_group product" data-row-id="'+row_id+'" id="product_'+row_id+'" name="product[]" style="width:100%;" onchange="getProductData('+row_id+')">'+
                        '<option value=""></option>';
                        $.each(response, function(index, value) {
                           html += '<option value="'+value.id+'">'+value.model_no+'-'+value.color+'-'+value.description+'-'+value.qty+' '+'qty avaliable</option>';         
                           });
                      html += '</select>'+
                    '</td>'+
                    '<td><input type="number" name="qty[]" id="qty_'+row_id+'" class="form-control" onkeyup="getTotal('+row_id+')"></td>'+
                    '<td><input type="text" name="rate[]" id="rate_'+row_id+'" class="form-control"><input type="hidden" name="rate_value[]" id="rate_value_'+row_id+'" class="form-control"></td>'+
                    '<td><input type="text" name="amount[]" id="amount_'+row_id+'" class="form-control" disabled><input type="hidden" name="amount_value[]" id="amount_value_'+row_id+'" class="form-control"></td>'+
                    '<td><input type="text" name="discount[]" id="discount_'+row_id+'" class="form-control" onkeyup="getDiscountTotal('+row_id+')"><input type="hidden" name="discount_value[]" id="discount_value_'+row_id+'" class="form-control"></td><td><input type="text" name="subtotal[]" id="subtotal_'+row_id+'" class="form-control" disabled><input type="hidden" name="subtotal_value[]" id="subtotal_value_'+row_id+'" class="form-control"></td>'+
                    '<td><button type="button" class="btn btn-default" onclick="removeRow(\''+row_id+'\')"><i class="fa fa-close"></i></button></td>'+
                    '</tr>';

                if(count_table_tbody_tr >= 1) {
                $("#product_info_table tbody tr:last").after(html);  
              }
              else {
                $("#product_info_table tbody").html(html);
              }

              $(".product").select2();

          }
        });

      return false;
    });

  }); // /document

  function getTotal(row = null) {
    if(row) {
      var total = Number($("#rate_value_"+row).val()) * Number($("#qty_"+row).val());
      total = total.toFixed(2);
      $("#amount_"+row).val(total);
      $("#amount_value_"+row).val(total);

      var dis_value = $("#discount_"+row).val();
      var aftr_discount = $("#amount_value_"+row).val() - $("#discount_"+row).val();
      $("#subtotal_"+row).val(aftr_discount);
      $("#subtotal_value_"+row).val(aftr_discount);
      
      
      subAmount();

    } else {
      alert('no row !! please refresh the page');
    }
       setTimeout(function(){
    checkQuentity();
    },1000);
  }


function getDiscountTotal(row = null) {
    if(row) {
      var total = Number($("#amount_value_"+row).val()) - Number($("#discount_"+row).val());
      subtotal = total.toFixed(2);      
      var discnt_value = Number($("#discount_"+row).val());
      $("#discount_value_"+row).val(discnt_value);
      $("#subtotal_"+row).val(subtotal);
      $("#subtotal_value_"+row).val(subtotal);      
      subAmount();
    } else {
      alert('no row !! please refresh the page');
    }
  }

  // get the product information from the server
  function getProductData(row_id)
  {
    var product_id = $("#product_"+row_id).val(); 
         $('#createOrder').attr('disabled','disabled');
    if(product_id == "") {
      $("#rate_"+row_id).val("");
      $("#rate_value_"+row_id).val("");
       

      $("#amount_"+row_id).val("");
      $("#amount_value_"+row_id).val("");

      $("#qty_"+row_id).val("");       
      $("#discount_"+row_id).val("");
      $("#discount_value_"+row_id).val("");
      $("#subtotal_"+row_id).val("");
      $("#subtotal_value_"+row_id).val("");

    } else {
      $.ajax({
        url: base_url + 'orders/getProductValueById',
        type: 'post',
        data: {product_id : product_id},
        dataType: 'json',
        success:function(response) {
          // setting the rate value into the rate input field
          
          $("#rate_"+row_id).val(response.price);
          $("#rate_value_"+row_id).val(response.price);

          $("#qty_"+row_id).val(1);
          $("#qty_value_"+row_id).val(1);

          var total = Number(response.price) * 1;
          total = total.toFixed(2);
          $("#amount_"+row_id).val(total);
          $("#amount_value_"+row_id).val(total);

          $("#discount_"+row_id).val("");
          $("#discount_value_"+row_id).val("");
          $("#subtotal_"+row_id).val(total);
          $("#subtotal_value_"+row_id).val(total);
          
          subAmount();
        } // /success
      }); // /ajax function to fetch the product data 
    }
    setTimeout(function(){
    checkQuentity();
    },1000);
  }

function onkeyup_rate_edit(id){
var rate_1=$('#rate_'+id).val();
var qty_1=$('#qty_'+id).val();
$('#rate_value_'+id).val(rate_1);

var total_amt=qty_1*rate_1;
$('#amount_'+id).val(total_amt);
$('#amount_value_'+id).val(total_amt);
$('#subtotal_'+id).val(total_amt);
$('#subtotal_value_'+id).val(total_amt);
$('#gross_amount').val(total_amt);
          subAmount();
}

function onkeyup_rate(id){
//alert(id);
var rate_1=$('#rate_'+id).val();
var qty_1=$('#qty_'+id).val();
$('#rate_value_'+id).val(rate_1);

var total_amt=qty_1*rate_1;
$('#amount_'+id).val(total_amt);
$('#amount_value_'+id).val(total_amt);
$('#subtotal_'+id).val(total_amt);
$('#gross_amount').val(total_amt);
          subAmount();
}



//get customer details information from the server

  $("#customer_name").change(function()
  {    
    var cust_adress = $("#customer_name").find(":selected").attr('cust_address');
    var cust_phone = $("#customer_name").find(":selected").attr('cust_phone');

    $("#customer_address").val(cust_adress);
    $("#customer_phone").val(cust_phone);

  });


  // calculate the total amount of the order
  function subAmount() {
    var service_charge = <?php echo ($company_data['service_charge_value'] > 0) ? $company_data['service_charge_value']:0; ?>;
    var vat_charge = <?php echo ($company_data['vat_charge_value'] > 0) ? $company_data['vat_charge_value']:0; ?>;

    var tableProductLength = $("#product_info_table tbody tr").length;
    var totalSubAmount = 0;
    var totalDiscountAmount = 0;
    for(x = 0; x < tableProductLength; x++) {
      var tr = $("#product_info_table tbody tr")[x];
      var count = $(tr).attr('id');
      count = count.substring(4);

      totalSubAmount = Number(totalSubAmount) + Number($("#subtotal_"+count).val());

       totalDiscountAmount = Number(totalDiscountAmount) + Number($("#discount_"+count).val());
    } // /for

    totalSubAmount = totalSubAmount.toFixed(2);

    // sub total
    $("#gross_amount").val(totalSubAmount);
    $("#gross_amount_value").val(totalSubAmount);
    $("#total_discount").val(totalDiscountAmount);

    // vat
    var vat = (Number($("#gross_amount").val())/100) * vat_charge;
    vat = vat.toFixed(2);
    $("#vat_charge").val(vat);
    $("#vat_charge_value").val(vat);

    // service
    // var service = (Number($("#gross_amount").val())/100) * service_charge;
    // service = service.toFixed(2);
    // $("#service_charge").val(service);
    // $("#service_charge_value").val(service);
    // console.log('Serv'+service);



    // total amount
   // var totalAmount = (Number(totalSubAmount) + Number(vat) + Number(service));
    var totalAmount = (Number(totalSubAmount) + Number(vat));
    totalAmount = totalAmount.toFixed(2);
    // $("#net_amount").val(totalAmount);
    // $("#totalAmountValue").val(totalAmount);

    // var discount = $("#discount").val();
    // if(discount) {
    //   var grandTotal = Number(totalAmount) - Number(discount);
    //   grandTotal = grandTotal.toFixed(2);
    //   $("#net_amount").val(grandTotal);
    //   $("#net_amount_value").val(grandTotal);
    // } else {
      $("#net_amount").val(totalAmount);
      $("#net_amount_value").val(totalAmount);
      
   // } // /else discount 

    var paid_amount = Number($("#paid_amount").val());
    if(paid_amount) {
      var net_amount_value = Number($("#net_amount_value").val());
      var remaning = net_amount_value - paid_amount;
      $("#remaining").val(remaning.toFixed(2));
      $("#remaining_value").val(remaning.toFixed(2));
    }

  } // /sub total amount

  function paidAmount() {
    var grandTotal = $("#net_amount_value").val();

    if(grandTotal) {
      var dueAmount = Number($("#net_amount_value").val()) - Number($("#paid_amount").val());
      dueAmount = dueAmount.toFixed(2);
      $("#remaining").val(dueAmount);
      $("#remaining_value").val(dueAmount);
    } // /if
  } // /paid amoutn function

  function removeRow(tr_id)
  {
    $("#product_info_table tbody tr#row_"+tr_id).remove();
    subAmount();
  }

function checkDate(){

if($('#paid_status').val()=='1'){
$('#paid_date').show();
}else{
$('#paid_date').hide();
}
}

var errorLog=[];
 function checkQuentity(id){
 //alert(id);
 var i=1

 $(".qty_row").each(function( index ){
  var qty=$("#qty_"+i).val();
  //alert(qty);
  var product_id=$("#product_"+i).val();
  console.log( i + ": " + $("#qty_"+i).val() );
  console.log( "product_id" + ": " + $("#product_"+i).val() );
  $.ajax({
  url:'<?php echo base_url('orders/checkqty');?>',
  method:'POST',
  data:{qty:qty,product_id:product_id},
  success:function(data){
  console.log(data);
  if(data==101){
  errorLog[i++]='error';
  return false;
  }else{
  errorLog[i++]='corr';
  return false;
  }
  }
  }); 
  i++;
  });
 setTimeout(function(){
 chkqtyFxd(errorLog);
 //  console.log(errorLog);
 },1000);

 }

function chkqtyFxd(errorLog){
if(errorLog.indexOf('error')>0){
 // errorLog='error';
  alert('Oop`s stock is not avaliable');
  $('#createOrder').attr('disabled','disabled');

  return false;
  }else{
  $('#createOrder').removeAttr('disabled','disabled');
  }
} 
</script>