<style type="text/css">
  .ml10{ margin-left: 10px}
  .dataTables_length{ display:none; }
</style>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small> Profit</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Partner Profit</li>
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
          <h3 class="box-title">Manage <?php echo $investor_name['name'];?> Profit</h3>
          </div>
           <div class="row">
        <div class="col-md-12 col-xs-12 ml10">
          <form class="form-inline" action="<?php echo base_url('investors/payment_history/'.$investorID.'') ?>" method="POST">
            <input type="text" name="investorID" hidden value="<?php echo $investorID;?>">
            <div class="form-group">
              <label for="date">Year</label>
              <select class="form-control" name="select_year" id="select_year">
                <?php foreach ($yearDropdown as $key => $value): ?>
                  <option value="<?php echo $value ?>" <?php if($value == $curent_year) { echo "selected"; } ?>><?php echo $value; ?></option>
                <?php endforeach ?>
              </select>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
          </form>
        </div>
        <br /> <br />

          <!--  <p class="box-title" style="float:left;margin-left:25px;font-size: 18px;font-weight: 800;">Invoice No: #17485</p> -->
          </div> 
          <!-- /.box-header -->
          <div class="box-body">
            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>S.No</th>
                <th>Month</th>
                <th>Partner Name</th>
                <th>Overall Selling Value</th>
                <th>Overall Collection amount</th>
                <th>Profit share</th>
                <th>Pending Amount</th>
              
               </tr>
              </thead>
        <?php 
          $totalnvAmt=array();
          foreach($forEachData as $key=>$forEachKey){
            $totalnvAmt[]=$forEachKey['profit_amount'];
    
      $remailningBalaance=$forEachKey['profit_amount']-$forEachKey['paid_amount'];
      
      if($forEachKey['paid_amount']>0){
      $amtPaidAmt=$forEachKey['paid_amount'];
      }else{
      $amtPaidAmt=0;
      }
    
    if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
    $profit_amount=$forEachKey['profit_amount'].' '.$date_format['currency'];
    $paid_amount=$amtPaidAmt.' '.$date_format['currency'];
    $remailningBalaanceAm=$remailningBalaance.' '.$date_format['currency'];
    $overAllSellAmt=$forEachKey['profit_amount_without_collection'].' '.$date_format['currency'];
    
    }else{
    $profit_amount=$date_format['currency'].' '.$forEachKey['profit_amount'];
    $paid_amount=$date_format['currency'].' '.$amtPaidAmt;
    $remailningBalaanceAm=$date_format['currency'].' '.$remailningBalaance;
    $overAllSellAmt=$date_format['currency'].' '.$forEachKey['profit_amount_without_collection'];
    }
            ?>
              <tr> 
              <td><?php echo $key+1;?></td>
              <td><?php echo $forEachKey['monthData'];?></td>
              <td><?php echo $forEachKey['name'];?></td>

               <td><?php echo $overAllSellAmt;?></td>
              <td><?php echo $profit_amount;?></td>          
              <td><?php echo $paid_amount;?></td>
              <td><?php echo $remailningBalaanceAm;?></td>
              </tr>
       <?php }?>
         <tfoot>
            <tr> 

        <td></td>

        <th></th>
        <th></th>
        <th>Total Amount</th>
        <th><?php echo array_sum($totalnvAmt);?></th>
        <th></th>
        <th></th>
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
          <form method="post" id="payBill" action="<?php echo base_url('investors/payBill');?>">
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
          <input type="text" name="investor_id" hidden value="<?php echo $InvestorId;?>">
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
            <?php echo array_sum($totalnvAmt)-$totalReceiveAmt['paid_amount'];?>
            </p>
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
        $valTotalAmt=array();
        foreach ($receiveAmt as $key_r => $valueAmt){
        $valTotalAmt[]=$valueAmt['paid_amount'];

    if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
    $paidBalance=$valueAmt['paid_amount'].' '.$date_format['currency'];
    }else{
    $paidBalance=$date_format['currency'].' '.$valueAmt['paid_amount'];
    }
          ?>
        <tr> 
        <td><?php echo $key_r+1;?></td>
        <td><?php echo date($date_format['date_format'],strtotime($valueAmt['paid_date']));?></td>
        <td><?php echo $paidBalance;?></td>
        <td><a href="javascript:void(0)" onclick="remove('<?php echo $valueAmt['id'];?>')" data-toggle="modal" data-target="#removeModal">Delete</a></td>
         </tr>
        <?php } ?>  
         <tfoot>
            <tr> 
        <td></td>
        <th>Sub Total</th>
        <th><?php 
    if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
    $TotalBalance=array_sum($valTotalAmt).' '.$date_format['currency'];
    }else{
    $TotalBalance=$date_format['currency'].' '.array_sum($valTotalAmt);
    }
    echo $TotalBalance;
    ?></th>
        <th></th>
         </tr>
         </tfoot>    
            </table>
          </div>
          <div class="box-body">
        
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

      <form role="form" action="<?php echo base_url('investors/removeInvestor');?>" method="post" id="removeForm">
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
      dateFormat: "d-m-Y H:i:s",
  });

$('#manageTable').dataTable({
"ordering": true,
// "searching": false,
"paging": true, 
}); 
$('#manageTablePayHistory').dataTable({
"ordering": true,
// "searching": false,
"paging": true, 
}); 
 
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
</script>