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
.dataTables_info{
  display:none;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Balance Sheet
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
            <h3 class="box-title">Balance Sheet Report</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
          <div class="row">
          <div class="col-sm-3"></div>
          <div class="col-sm-2">
          <label>Select Year</label>
          <select name="year" id="yearID" class="form-control">
          <option value="">~~select~~</option> 
          <?php 
          foreach ($yearDropdown as $key => $yearDropdownData) { ?>
          <option value="<?php echo $yearDropdownData;?>"><?php echo $yearDropdownData;?></option>
          <?php } ?>
          </select>
          </div>
          <div class="col-sm-2">
          <label>Select Month</label>  
          <select name="month" id="monthID" class="form-control">
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
          <div class="col-sm-3">
          <label></label> 
          <button type="button" name="btnsubmit" onclick="getAccountData()" style="margin-top:23px;" class="btn btn-primary">Search</button>
     <!--      <a href="javascript:void(0)"><input type="button" name="btnsubmit" class="btn btn-primary" value="Search"></a> -->
          </div>
          <div class="col-sm-2"></div>  
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
$(document).ready(function() {
  // initialize the datatable 
  manageTable = $('#duetbl').DataTable({
    dom: 'Bfrtip',
   buttons: [
    { extend: 'excel', text: 'Export Excel' }
    ],
   "ordering": false,
   "searching": false,
     "paging": false, 
  });
});

function getAccountData(){
  var monthID= $('#monthID').val();
  var yearID= $('#yearID').val();
  $.ajax({
  url:'<?php echo base_url('reports/getBalanceSheetDataList');?>',
  method:'POST',
  data:{monthID:monthID,yearID:yearID},
  success:function(res){
  console.log(res);
  $('#Datalist').html(res);
  }
  })
}
</script>
