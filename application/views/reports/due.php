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
.dataTables_length{
display: none;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Due
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
            <h3 class="box-title">Due Report</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
          
          <div class="row">
          <div class="col-sm-10"></div>
          <div class="col-sm-2">
          <a href="<?php echo base_url('reports/exportExcelDueReport');?>"><button type="submit" class="buttons-excel">Export Data</button></a>
          </div>  
          </div>
            <table id="duetbl" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>S.No</th>
                <th>Oeder ID</th>
                <th>Customer Code</th>
                <th>Customer Name</th>
                <th>Order Date</th>
                <th>Due Date</th>
                <th>Due Amount</th>
               </tr>
              </thead>
             <tbody>
           <?php foreach($record as $key => $value){ ?>
            <tr>
            <td><?php echo $key+1;?></td>
            <td><?php echo $value['bill_no'];?></td>
            <td><?php echo $value['customer_code'];?></td>
            <td><?php echo $value['customer_name'];?></td>
            <td><?php echo date($date_format['date_format'],strtotime($value['order_date']));?></td>
            <td><?php echo date($date_format['date_format'],strtotime($value['due_date']));?></td>
            <td><?php if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
            echo $value['due_amount'].' '.$date_format['currency'];
            }else{
            echo $date_format['currency'].' '.$value['due_amount'];
            }?></td>
            </tr>
            <?php } ?>
            </tbody>
            </table>
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
   "ordering": false,
   "searching": false,
   "paging": true, 
  });
});
</script>
