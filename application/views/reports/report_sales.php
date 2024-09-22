<style type="text/css">
  .buttons-excel {
    float: right;
     padding-right: 15px; 
    margin-right: 30px;
    margin-bottom: 10px;
    border-color: #218838;
    background-color: #218838;
    color: white;
    font-size: 14px;
}
.dataTables_length{
 display: none;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Sales
      <small>Report</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Report</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

       <div class="box">
          <div class="box-header">
            <h3 class="box-title">Sales Report</h3> 
          </div>
          <!-- /.box-header -->
          <form name="search" method="POST" action="<?php echo base_url('reports/exportexcelsalesreport');?>" id="filterForm">
          <div class="row">
          <div class="col-sm-1"></div>            
          <div class="col-sm-2">
          <select name="customer_name" id="customer_name" class="form-control">
          <option value="all">~~select~~</option> 
          <?php foreach ($customerData as $key => $value) { ?>
          <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>  
          <?php }  ?>
          <option value="all">All Customer</option>  
          </select>
          </div>
          <div class="col-sm-2">
          <input type="text" name="from_date" class="form-control" id="from_date" placeholder="From">
          </div>
          <div class="col-sm-2">
          <input type="text" name="to_date" class="form-control" id="to_date" placeholder="To">  
          </div>
          <div class="col-sm-2">
          <select name="payID" id="payID" class="form-control">
          <option value="">~~payment~~</option> 
          <option value="4">Cancel</option>
          <option value="3">FOC</option>  
          <option value="2">Not Paid</option>
          <option value="1">Paid</option>
          </select>
          </div>
          <div class="col-sm-1">
          <a href="javascript:void(0)">
          <input type="button" name="search" onclick="getSalesReportByName()" class="btn btn-primary form-control" value="Search"></a>
          </div>
          <div class="col-sm-2">
          <button type="submit" class="buttons-excel" name="export_data">Export Data</button>
          </div>  
          </div>
          </form>
           <div class="box-body" id="ajaxSalesReport">
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

<script type="text/javascript">
$(function(){

$("#from_date").datepicker({
autoclose: true,
format: 'dd-mm-yyyy',
}).on('changeDate', function (selected) {
var minDate = new Date(selected.date);
minDate.setDate(minDate.getDate() + 1);
$('#to_date').datepicker('setStartDate', minDate);
});

$("#to_date").datepicker({
autoclose: true,
format: 'dd-mm-yyyy',
}).on('changeDate', function (selected){
var minDate = new Date(selected.date);
minDate.setDate(minDate.getDate() - 1);
$('#from_date').datepicker('setEndDate', minDate);
});

// $.ajax({
//   url:'<?php //echo base_url('reports/getSalesReportList');?>',
//   method:'POST',
//   success:function(data){
//   $('#ajaxSalesReport').html(data);
//   }
// })
getSalesReportByName();
})

$('#filterForm').submit(function(){
  var from_date=$('#from_date').val();
  var to_date =$('#to_date').val();

   if( (from_date!='' && to_date=='') || (from_date=='' && to_date!='')){
  alert('Please select valid date range');
  return false;
  }
})

function getSalesReportByName(){
var customer_name =$('#customer_name').val();
var from_date=$('#from_date').val();
var to_date =$('#to_date').val();
var payID =$('#payID').val();

 if( (from_date!='' && to_date=='') || (from_date=='' && to_date!='')){
  alert('Please select valid date range');
  return false;
  }

$.ajax({
  url:'<?php echo base_url('reports/getSalesReportList');?>',
  method:'POST',
  data:{customer_name:customer_name,from_date:from_date,to_date:to_date,payID:payID},
  success:function(data){
  $('#ajaxSalesReport').html(data);
  }
})
}
</script>
