<style type="text/css">
  .ml10{ margin-left: 10px}
</style>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Commercial Invoice</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Commercial Invoice</li>
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
          <h3 class="box-title">Manage Commercial Invoice</h3>
          </div>
           <div class="row">
           <p class="box-title" style="float:left;margin-left:25px;font-size: 18px;font-weight: 800;">Invoice No: <?php echo $invoice_no;?></p>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>S.No</th>
                <th>Factory Info</th>
                <th>Model No</th>
                <th>Product Description</th>
                <th>Color</th>
                <th>Size</th>
                <th>CIF</th>
                <th>Cost Price</th>
                <th>Qty</th>
                <th>Amount</th>
               </tr>
              </thead>
             <?php 
              $amount=array();
             if($data){ foreach ($data as $key => $value){
              $amount[]=$value['price']*$value['initial_qty'];

              if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
              $cif=$value['cif'].' '.$date_format['currency'];
              $price= $value['price'].' '.$date_format['currency'];
              $into_amt=round($value['price']*$value['initial_qty'],2).' '.$date_format['currency'];
              } else{
              $cif=$date_format['currency'].' '.$value['cif'];
              $price= $date_format['currency'].' '.$value['price'];
              $into_amt=$date_format['currency'].' '.round($value['price']*$value['initial_qty'],2);
              }
              ?>
        <tr> 
				<td><?php echo $key+1;?></td>
				<td><?php echo $value['factory_name'];?></td>
				<td><?php echo $value['model_no'];?></td>
				<td><?php echo $value['description'];?></td>
				<td><?php echo $value['color'];?></td>
			  <td><?php echo $value['size'];?></td>
			  <td><?php echo $cif;?></td>
        <td><?php echo $price;?></td>
        <td><?php echo $value['initial_qty'];?></td>
        <td><?php echo $into_amt;?></td>
			   </tr>
             <?php }  } ?>
         <tfoot>
            <tr> 
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <th>Total Amount</th>
        <th></th>
        <th><?php 
              if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
               $totalBillAmount=round(array_sum($amount),2).' '.$date_format['currency'];
              }else{
               $totalBillAmount=$date_format['currency'].' '.round(array_sum($amount),2);
              } 
               echo $totalBillAmount;
              ?></th>
         </tr>
         </tfoot>    
            </table>
          </div>
          <!-- /.box-body -->
        </div>

        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Payment History</h3>
          </div>
          <!-- /.box-header -->
          <form method="post" id="payBill" action="<?php echo base_url('commercialinvoice/payBill');?>">
          <div class="box-body">
          <div class="row">
          <div class="col-sm-2"></div>
          <div class="col-sm-3">
          <label>Select Date</label>    
          <input type="text" name="date" id="inputeDate" class="form-control" required="">  
          </div>
          <div class="col-sm-3">
          <label>Enter Amount</label>  
          <input type="number" name="pay_amount" step=".01" min="0" class="form-control" required=""> 
          <input type="text" hidden name="invoice_no" value="<?php echo $invoice_ID;?>">
          </div>
          <div class="col-sm-3">
          <label></label>
          <input type="submit" name="paybtn" class="btn btn-primary form-control" value="Click To Pay">
          </div>
          <div class="col-sm-1"></div>
          </div>
          </div>
          </form>
          <div class="box-body">
          <p style="font-size:18px;font-weight:800;padding-top:14px;color:#c10404;margin-left: 12px">Balance To Be Paid:
            <?php 
            $requiredPaidAmt=round(array_sum($amount),2)-round($paid_amount,2);
            
            if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
              echo $requiredPaidAmt.' '.$date_format['currency'];
              }else{
              echo $date_format['currency'].' '.$requiredPaidAmt;
              }
            ?></p>
            <table id="manageTablePayHistory" class="table table-bordered table-striped">
              <thead>
               <tr>
                <th>S.No</th>
                <th>Paid Date</th>
                <th>Amount</th>
                <th>Action</th>
               </tr>
              </thead>
             <?php 
              $subtotal_amount=array();
             if($paidAmt){ foreach ($paidAmt as $key => $value){
               $subtotal_amount[]=$value['paid_amount'];

             if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
              $paid_amount=$value['paid_amount'].' '.$date_format['currency'];
              }else{
              $paid_amount=$date_format['currency'].' '.$value['paid_amount'];
              }
              ?>
        <tr> 
        <td><?php echo $key+1;?></td>
        <td><?php echo date($date_format['date_format'],strtotime($value['paid_date']));?></td>
        <td><?php echo $paid_amount;?></td>
        <td><a href="javascript:void(0)" onclick="remove('<?php echo $value['id'];?>')" data-toggle="modal" data-target="#removeModal">Delete</a></td>
         </tr>
             <?php }  } ?>
         <tfoot>
            <tr> 
        <td></td>
        <th>Sub Total</th>
        <th><?php 
             if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
               $totalPaidAmt=round(array_sum($subtotal_amount),2).' '.$date_format['currency'];
              }else{
               $totalPaidAmt=$date_format['currency'].' '.round(array_sum($subtotal_amount),2);
              }
              echo $totalPaidAmt;
              ?></th>
        <th></th>
         </tr>
         </tfoot>    
            </table>
          </div>

          <div class="box-body">
        
     <!--      <div class="row">
          <div class="col-sm-4"></div>
          <div class="col-sm-2" style="font-size:16px;font-weight:800">Total Billable Amount</div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-3" style="font-size:15px;font-weight:800"><?php echo $totalBillAmount;?></div>
          <div class="col-sm-2"></div>  
          </div>  --> 

        <!--   <div class="row">
          <div class="col-sm-4"></div>
          <div class="col-sm-2" style="font-size:16px;font-weight:800">Balance To Be Paid</div>
          <div class="col-sm-1">:</div>
          <div class="col-sm-3" style="font-size:15px;font-weight:800"><?php 
          $remainingAmt=array_sum($amount) - array_sum($subtotal_amount);
         if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
           echo $remainingAmt.' '.$date_format['currency'];
          }else{
           echo $date_format['currency'].' '.$remainingAmt;
          } ?></div>
          <div class="col-sm-2"></div>  
          </div>   -->

          </div>

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

<?php //if(in_array('deleteAsset', $user_permission)): ?>
<!-- remove brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Remove Assets</h4>
      </div>

      <form role="form" action="<?php echo base_url('commercialinvoice/remove');?>" method="post" id="removeForm">
        <div class="modal-body">
          <p>Do you really want to remove?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php //endif; ?>
<script type="text/javascript">
$(function(){
  flatpickr("#inputeDate", {
      enableTime: true,
      dateFormat: "d-m-Y H:i",
  });
$('#manageTable').dataTable(); 
$('#manageTablePayHistory').dataTable(); 
});

$('#payBill').on('submit',function(){
var url=$('#payBill').attr('action');

var dataval=$('#payBill').serialize();
$.ajax({
url:url,
method:'POST',
data:dataval,
success:function(res){
console.log(res);
if(res==101){
alert('Oop`s this amount is grater than to pending amount');
}else{
location.reload();  
}
// location.reload();
}
});
return false;
})

// remove functions
function remove(id)
{
  if(id) {
    $("#removeForm").on('submit', function(){
      var form = $(this);
      // remove the text-danger
      $(".text-danger").remove();

      $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: { id:id },
        dataType: 'json',
        success:function(response) {

          //$("#manageTable").load(location.href + " #manageTable");
          location.reload();
          if(response.success === true) {
            $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
              '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
            '</div>');

            // hide the modal
            $("#removeModal").modal('hide');

          } else {

            $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
              '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>'+response.messages+
            '</div>');
          }
        }
      });

      return false;
    });
  }
}

$('#addInvoice').on('click',function(){
$('#addInvoiceForm').show();
})
</script>