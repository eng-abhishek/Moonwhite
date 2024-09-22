<!-- Content Wrapper. Contains page content -->
<style type="text/css">
.buttons-excel{
 float: right;
    /* padding-right: 15px; */
    margin-right: 30px;
    margin-bottom: 10px;
    border-color: #218838;
    background-color: #218838;
    color: white;
    font-size: 14px;
} 
.buttons-excel:hover{
   float: right;
    /* padding-right: 15px; */
    margin-right: 30px;
    margin-bottom: 10px;
    border-color: #218838;
    background-color: #218838;
    color: white;
    font-size: 14px;
}
</style>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <small></small>
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
            <h3 class="box-title">Ledger Report</h3>
          </div>
          <div class="row">
          <div class="col-sm-2"></div>
          <div class="col-sm-2">
          <input type="text" name="from_date" placeholder="From Date" id="from_date" class="form-control">
          </div>
          <div class="col-sm-2">
          <input type="text" name="to_date" placeholder="To Date" id="to_date" class="form-control">  
          </div>
          <div class="col-sm-2">
          <button type="button" id="btnFilter" class="btn btn-primary">Search</button>
          </div>
          <div class="col-sm-6"></div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
          <button type="button" class="buttons-excel" onclick="exportData()">Export Data</button>
            <table id="legerData" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>id</th> 
                <th>Particulars</th>
                <th>Date</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balence</th>
               </tr>
              </thead>
                <tfoot>
      <tr>
       <th></th>
       <th></th>
       <th>Total Amount</th>
       <th></th>
       <th></th>
       <th id="total_order"></th>
      </tr>
     </tfoot>
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
<script>
  var table;
// Custom filtering function which will search data in column four between two values
$(function(){
  // var legerDatatbl=$('#legerData').DataTable();
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
}).on('changeDate', function (selected) {
var minDate = new Date(selected.date);
minDate.setDate(minDate.getDate() - 1);
$('#from_date').datepicker('setEndDate', minDate);
});


   var total_amount=0;
    // DataTables initialisation
    // Refilter the table
      table = $('#legerData').DataTable({
     "processing": false,
     "serverSide": true,
     "paging": false,
     "searching": false,
     "info":false,
     "ordering": false,
        ajax: {
            "url": '<?php echo base_url('ledger/getLegerData');?>',
            "type": "POST",
             data: function (d) {
                    d.fromDate = $('#from_date').val();
                    d.toDate = $('#to_date').val();
                }
        },
         "columns": [
            { "data": "id" },   
            { "data": "particulars" },
            { "data": "date" },
            { "data": "debit" },
            { "data": "credit" },
            { "data": "actual_balence" },
        ],
  
    drawCallback:function(settings)
    {
     $('#total_order').html(settings.json.total.total);
     //console.log(settings.json.total.total); 
    },
     });
    })
   $('#btnFilter').on('click', function () {
       // table.draw();
      //table.row.add(["30", "AS","date","25","10","15"]).draw();
    if($('#from_date').val()=='' || $('#to_date').val()==''){
    alert('Please select date range');
    return false;
    }
   var startDate = new Date($('#from_date').val());
   var endDate = new Date($('#to_date').val());

          if(startDate > endDate){
          alert('Please select valid date range');
          return false;
          }else{
          table.ajax.reload();
          gettotalbalence();
          }
    });

function gettotalbalence(){
$.ajax({
url:'<?php echo base_url('ledger/gettotalbalence');?>',
method:'POST',
success:function(data){
console.log(data);
//$('#legerData').append('<tr><td colspan="2"></td><td colspan="2"><b>Total Balence</b></td><td colspan="2"><b>'+data+'</b></td></tr>');
}
})
}

function exportData(){
    /* Get the HTML data using Element by Id */
    var table = document.getElementById("legerData");
 
    /* Declaring array variable */
    var rows =[];
 
      //iterate through rows of table
    for(var i=0,row; row = table.rows[i];i++){
        //rows would be accessed using the "row" variable assigned in the for loop
        //Get each cell value/column from the row
        console.log(row);
        column1 = row.cells[0].innerText;
        column2 = row.cells[1].innerText;
        column3 = row.cells[2].innerText;
        column4 = row.cells[3].innerText;
        column5 = row.cells[4].innerText;
        column6 = row.cells[5].innerText;
 
    /* add a new records in the array */
        rows.push(
            [
                column1,
                column2,
                column3,
                column4,
                column5,
                column6,
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
        link.setAttribute("download", "Ledger-statement-report.csv");
        document.body.appendChild(link);
         /* download the data file named "Stock_Price_Report.csv" */
        link.click();
}
</script>
