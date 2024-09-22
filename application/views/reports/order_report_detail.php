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
.table_price_area{width:50%; float:right;}
.dataTables_info{
  display:none;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Order Detail
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
            <div class="row">
            <div class="col-sm-6">
            <h3 class="box-title">Order Detail</h3>   
            </div>
     
            <div class="col-sm-6">
            <a href="<?php echo base_url('reports/getpdfInvoiceSalesReport/'.$id);?>"><button type="submit" style="float: right;margin-right:25px;margin-top:5px;"  name="btnsubmit" class="btn btn-warning">Download PDF</button></a>
            </div>
          </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
          <h4>Customer Details</h4>
          <div>
          
            <div class="row" style="padding-top:8px">
            <div class="col-sm-2"></div>
            <div class="col-sm-3"><b>Code</b></div>
            <div class="col-sm-2">:</div>
            <div class="col-sm-3"><?php echo $custID['code'];?></div>
            <div class="col-sm-2"></div>
            </div>

            <div class="row" style="padding-top:8px">
            <div class="col-sm-2"></div>
            <div class="col-sm-3"><b>Name</b></div>
            <div class="col-sm-2">:</div>
            <div class="col-sm-3"><?php echo $custID['name'];?></div>
            <div class="col-sm-2"></div>
            </div>

            <div class="row" style="padding-top:8px">
            <div class="col-sm-2"></div>
            <div class="col-sm-3"><b>Address</b></div>
            <div class="col-sm-2">:</div>
            <div class="col-sm-3"><?php echo $custID['address'];?></div>
            <div class="col-sm-2"></div>
            </div>

            <div class="row" style="padding-top:8px">
            <div class="col-sm-2"></div>
            <div class="col-sm-3"><b>Contact No</b></div>
            <div class="col-sm-2">:</div>
            <div class="col-sm-3"><?php echo $custID['contact'];?></div>
            <div class="col-sm-2"></div>
            </div>

            <div class="row" style="padding-top:8px">
            <div class="col-sm-2"></div>
            <div class="col-sm-3"><b>Email</b></div>
            <div class="col-sm-2">:</div>
            <div class="col-sm-3"><?php echo $custID['email'];?></div>
            <div class="col-sm-2"></div>
            </div>

            <div class="row" style="padding-top:8px">
            <div class="col-sm-2"></div>
            <div class="col-sm-3"><b>TRN No</b></div>
            <div class="col-sm-2">:</div>
            <div class="col-sm-3"><?php echo $custID['trn'];?></div>
            <div class="col-sm-2"></div>
            </div>
            </div>
          <h4>Order Details</h4>

          <div class="row" style="padding-top:8px">
          <div class="col-sm-2"></div>
          <div class="col-sm-3"><b>Order Date</b></div>
          <div class="col-sm-2">:</div>
          <div class="col-sm-3"><?php echo $order_data['date_time'];?></div>
          <div class="col-sm-2"></div>
          </div>

          <?php
          if($order_data['paid_status'] == 1) {
          $paid_status = '<span class="label label-success">Paid</span>';
          }elseif($order_data['paid_status'] == 2){
          $paid_status = '<span class="label label-primary">Not Paid</span>';

          }elseif($order_data['paid_status'] == 3){
          $paid_status = '<span class="label label-warning">FOC</span>';
   
          }elseif($order_data['paid_status'] == 4){
          $paid_status = '<span class="label label-danger">CANCELLED</span>';
          }else{
          $paid_status='';
          }
          ?>

          <div class="row" style="padding-top:8px">
          <div class="col-sm-2"></div>
          <div class="col-sm-3"><b>Order Status</b></div>
          <div class="col-sm-2">:</div>
          <div class="col-sm-3"><?php echo $paid_status;?></div>
          <div class="col-sm-2"></div>
          </div>

          <div class="row" style="padding-top:8px">
          <div class="col-sm-2"></div>
          <div class="col-sm-3"><b>Payment Method</b></div>
          <div class="col-sm-2">:</div>
          <div class="col-sm-3"><?php echo $order_data['payment_status'];?></div>
          <div class="col-sm-2"></div>
          </div>

          <div class="row" style="padding-top:8px">
          <div class="col-sm-2"></div>
          <div class="col-sm-3"><b>Bill No</b></div>
          <div class="col-sm-2">:</div>
          <div class="col-sm-3"><?php echo $order_data['bill_no'];?></div>
          <div class="col-sm-2"></div>
          </div>

              <div class="table-responsive" style="padding-top:30px">
              <table class="table table-bordered table-striped">
              <thead>
              <tr>
              <th>Invoice No</th>
              <th>Model No</th>
              <th>Product name</th>
              <th>Color</th>
              <th>Size</th>
              <th>Price</th>
              <th>Qty</th>
              <th>Discount</th>
              <th>Amount</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach($orders_items as $key => $value){
              $discountAmt=$value['discount'] ? : 0;
              $rate=$value['rate'] ? : 0;
              $rowAmt=round($rate*$value['qty'],2);
              $netActAmt=$rowAmt-$discountAmt;

   if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
   $rate=$value['rate'].' '.$this->date_format['currency'];
   $rowtotalAmt=$netActAmt.' '.$date_format['currency'];
   $rowDiscountAmt=$discountAmt.' '.$date_format['currency'];
   }else{
   $rate=$this->date_format['currency'].' '.$value['rate'];
   $rowtotalAmt=$date_format['currency'].' '.$netActAmt;
   $rowDiscountAmt=$date_format['currency'].' '.$discountAmt;
   }

                ?>
              <tr>
              <td><?php echo $value['invoice_no_pro'];?></td>
              <td><?php echo $value['model_no_pro'];?></td>
              <td><?php echo $value['pro_name'];?></td>
              <td><?php echo $value['color'];?></td>
              <td><?php echo $value['size'] ? : "N/A" ;?></td>
              <td><?php echo $rate;?></td>
              <td><?php echo $value['qty'];?></td>
              <td><?php echo $rowDiscountAmt;?></td>
              <td><?php echo $rowtotalAmt;?></td>
              </tr>
              <?php } ?>
              </tbody>
              </table>
<?php
  if($this->date_format['currency']=='INR' OR $this->date_format['currency']=='USD'){
   $vat=$order_data['vat_charge'].' '.$this->date_format['currency'];
   $totalDiscount=$order_data['total_discount'].' '.$this->date_format['currency'];
   $netAmt=$order_data['net_amount'].' '.$this->date_format['currency'];
   $grossAmt=$order_data['gross_amount'].' '.$this->date_format['currency'];
   }else{
   $vat=$this->date_format['currency'].' '.$order_data['vat_charge'];
   $totalDiscount=$this->date_format['currency'].' '.$order_data['total_discount'];
   $netAmt=$this->date_format['currency'].' '.$order_data['net_amount'];
   $grossAmt=$this->date_format['currency'].' '.$order_data['gross_amount'];
   }
?>
<div class="table_price_area">
  <div class="table-responsive">
    <table class="table">
      <tbody>
        <tr>
          <th style="width:50%">Gross Amount:</th>
          <td><?php echo $grossAmt;?></td>
        </tr>
        <tr>
          <th>Vat Charge (<?php echo $order_data['vat_charge_rate'];?>%) :</th>
          <td><?php echo $vat;?></td>
        </tr>
        <tr>
          <th>Discount:</th>
          <td><?php echo $totalDiscount;?></td>
        </tr>
        <tr>
          <th>Net Amount:</th>
          <td><?php echo $netAmt;?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

          </div>
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
</script>
