<style type="text/css">
  .buttons-excel{
    float: right;
     padding-right: 15px; 
    margin-right: 30px;
    margin-bottom: 10px;
    border-color: #218838;
    background-color: #218838;
    color: white;
    font-size: 14px;
}
.dataTables_info{
  display:none;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Expense
      <small>Report</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
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
            <h3 class="box-title">Expense Report</h3>
          </div>
      <!--  <div><a href="javascript:void(0)" onclick="downloadAdvanceInvoice()">PDF</a></div> -->


          <!-- /.box-header -->
          <div class="box-body">
          <div class="row">
          <form method="post" id="invoiceForm" action="<?php echo base_url('reports/exportExpenceDataList');?>">  
          <div class="col-sm-1"></div>
  
          <div class="col-sm-2">
          <label>Select Year</label>
          <select name="yearID" id="yearID" class="form-control">
          <option value="">~~select~~</option> 
          <?php 
          foreach ($yearDropdown as $key => $yearDropdownData) { ?>
          <option value="<?php echo $yearDropdownData;?>"><?php echo $yearDropdownData;?></option>
          <?php } ?>
          </select>
          </div>
          <div class="col-sm-2">
          <label>Select Month</label>  
          <select name="monthID" id="monthID" class="form-control">
          <option value="">~~select~~</option> 
          <option value="1">January</option>  
          <option value="2">February</option>  
          <option value="3">March</option>  
          <option value="4">April</option>  
          <option value="5">May</option>  
          <option value="6">June</option>  
          <option value="7">July</option>  
          <option value="8">August</option>  
          <option value="9">September</option>  
          <option value="10">October</option>  
          <option value="11">November</option>  
          <option value="12">December</option>  
          </select>
          </div>
          <div class="col-sm-2">
          <label>Select Code</label>
          <select name="custID" id="custID" class="form-control">
          <option value="all">~~select~~</option>
          <?php foreach ($exp_code as $key => $value) { ?>
          <option value="<?php echo $value['code'];?>">
          <?php echo $value['code'].'-'.$value['title'];?></option>
          <?php } ?>
          <option value="all">All Code</option>
          </select>
          </div>
          <div class="col-sm-3">
          <label></label> 
          <button type="button" name="btnsubmit" onclick="getAccountData()" style="margin-top:23px;" class="btn btn-primary">Search</button>
          </div>
          <div class="col-sm-2">
          <button type="submit" class="buttons-excel" name="export_data">Export Data</button>
          </div>  
          </form>
          </div>
          <br>
          <div id="Datalist"></div>
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
 getAccountData();  
})

$('#invoiceForm').submit(function(){
  var monthID= $('#monthID').val();
  var yearID= $('#yearID').val();
  var custID=$('#custID').val();
  if((yearID=='' && monthID!='')){
  alert('Please select valid year and month');
  return false;
  }
})

function getAccountData(){
  var monthID= $('#monthID').val();
  var yearID= $('#yearID').val();
  var custID=$('#custID').val();
  if((yearID=='' && monthID!='')){
  alert('Please select valid year and month');
  return false;
  }
  $.ajax({
  url:'<?php echo base_url('reports/getExpenceDataList');?>',
  method:'POST',
  data:{monthID:monthID,yearID:yearID,custID:custID},
  success:function(res){
  console.log(res);
  $('#Datalist').html(res);
  }
  })
}

function downloadAdvanceInvoice(){
  var monthID= $('#monthID').val();
  var yearID= $('#yearID').val();
  var custID=$('#custID').val();


  $.ajax({
  url:'<?php echo base_url('');?>',
  method:'POST',
  data:{monthID:monthID,yearID:yearID,custID:custID},
  success:function(res){
  //console.log(res);
  //$('#Datalist').html(res);
  }
  })
}
</script>
