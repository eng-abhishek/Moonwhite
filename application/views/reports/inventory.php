<?php 
//error_reporting();
?>
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
  display:none;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Inventory
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
            <div calss="col-sm-6"><h3 class="box-title">Inventory Report</h3></div>
            <div calss="col-sm-6"><a href="<?php echo base_url('reports/exportExcelInventory');?>"><button type="button" class="buttons-excel">Export Data</button></a></div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
         <!--  <button type="button" class="buttons-excel" onclick="exportData()">Export Data</button> -->
            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>S.No</th>
                <th>Invoice No</th>
                <th>Model No</th>
                <th>Description</th>
                <th>Color</th>
                <th>Actual Stock</th>
                <th>SaleOut Stock</th>           
                <th>Available Stock</th>
                <th>CIF</th>
                <th>Cost Price</th>
                <th>Total Value</th>
               </tr>
              </thead>
             <tbody>
           <?php 
            $totalAmt=array();
            foreach($record as $key => $value){ 
            $totalAmt[]=$value['price'] * $value['qty'];
             ?>
             <tr>
            <td><?php echo $key+1;?></td>
            <td><?php echo $value['oth_invoice_no'];?></td>
            <td><?php echo $value['model_no'];?></td>
            <td><?php echo $value['description'];?></td>
            <td><?php echo $value['attr'];?></td>
            <td><?php echo $value['initial_qty'];?></td> 
            <td><?php echo $value['getoutqty'];?></td>
            <td><?php echo $value['qty'];?></td>

            <td>
            <?php if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
            echo $value['cif'].' '.$date_format['currency'];
            }else{
            echo $date_format['currency'].' '.$value['cif'];
            }?></td>
            <td>
            <?php if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
            echo $value['price'].' '.$date_format['currency'];
            }else{
            echo $date_format['currency'].' '.$value['price'];
            }?>
            </td>
            <td><?php $pr_qty=$value['price'] * $value['qty'];?>
            <?php if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
            echo $pr_qty.' '.$date_format['currency'];
            }else{
            echo $date_format['currency'].' '.$pr_qty;
            }?>
            </td>
             </tr>
           <?php  }?>
           <tfoot>
            <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
                <th></th>
                <th>Total Amount</th>
            <th colspan="2"><?php $ar_total_sum=array_sum($totalAmt);
            if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
            echo $ar_total_sum.' '.$date_format['currency'];
            }else{
            echo $date_format['currency'].' '.$ar_total_sum;
            }
            ?></th>
            </tr> </tfoot>
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
</script>
<script type="text/javascript">
 $(function(){
  $('#manageTable').DataTable({
     "processing": false,
     "paging": true,
     "searching": false,
     "info":false,
     "ordering": false,
  }); 
 }) 
function exportData(){
    /* Get the HTML data using Element by Id */
    var table = document.getElementById("manageTable");
    /* Declaring array variable */
    var rows =[];
      //iterate through rows of table
    for(var i=0,row; row = table.rows[i];i++){
        //rows would be accessed using the "row" variable assigned in the for loop
        //Get each cell value/column from the row
        // console.log(row);
        col02=row.cells[2].innerText;
        column1 = row.cells[0].innerText;
        column2 = row.cells[1].innerText;
        column3 = col02.replace(/[_\W]+/g, " ");
        column4 = row.cells[3].innerText;
        column5 = row.cells[4].innerText;
        //column5=col05.text();
        column6 = row.cells[5].innerText;
        column7 = row.cells[6].innerText;
        column8 = row.cells[7].innerText;

    /* add a new records in the array */
        rows.push(
            [
                column1,
                column2,
                column3,
                column4,
                column5,
                column6,
                column7,
                column8,
            ]
        );
        }
        csvContent = "data:text/csv;charset=utf-8,";
         /* add the column delimiter as comma(,) and each row splitted by new line character (\n) */
        rows.forEach(function(rowArray){
            row = rowArray.join(",");
            csvContent += row + "\r\n";
        });
 
        /* create a hidden <a> DOM node and set its download attribute */
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "inventory-report.csv");
        document.body.appendChild(link);
         /* download the data file named "Stock_Price_Report.csv" */
        link.click();
}
</script>