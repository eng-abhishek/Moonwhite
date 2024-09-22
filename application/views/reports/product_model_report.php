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
.btn-primary{
  margin-left: -20px
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Product Model Sale
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
            <h3 class="box-title">Product Model Sales Report</h3> 
          </div>
          <!-- /.box-header -->
        
          <div class="row">
          <form method="post" action="<?php echo base_url('reports/exportProductModelData');?>">        
          <div class="col-sm-3"></div>
          <div class="col-sm-3">
          <input type="text" name="model_no"  value="<?php echo $model_no;?>" id="model_no" class="form-control" required placeholder="Enter Model No">
          </div>   
         <div class="col-sm-2">
         <a href="javascript:void(0)"><input type="button" name="search" onclick="getSalesReportByName()" class="btn btn-primary form-control" value="Search"></a>
        </div>
          <div class="col-sm-4">
          <button type="submit" class="buttons-excel">Export Data</button>
          </div> 
          </form>
          </div>
  
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
 setTimeout(getSalesReportByName,1000); 
})

$('#filterForm').submit(function(){
  return false;
})

function getSalesReportByName(){
var model_no =$('#model_no').val();
if(model_no!=''){
$.ajax({
  url:'<?php echo base_url('reports/getProductModelData');?>',
  method:'POST',
  data:{model_no:model_no},
  success:function(data){
  $('#ajaxSalesReport').html(data);
  }
})    
}

}
</script>
