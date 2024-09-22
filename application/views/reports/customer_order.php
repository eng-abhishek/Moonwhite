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
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Customer 
      <small>Orders</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Customer Orders</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

       <div class="box">
          <div class="box-header">
            <h3 class="box-title">Customer Orders</h3> 
          </div>
          <!-- /.box-header -->
        
           <div class="box-body">
            <table id="customerOrder" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Oreder Id</th>
                <th>Date</th>
                <th>Value</th>
                <th>VAT</th>
                <th>PAYMENTS</th>
                <th>Action</th>
               </tr>
              </thead>
             <tbody>
            <?php
             // echo"<pre>";
             // print_r($result);
              $i=0;
              foreach ($result[0] as $key02 => $value_two){
               //print_r($value_two);
               // echo $value_two['cust_details'];

              $total_sumup[]=$value_two['net_amount'];
              $total_vat[]=$value_two['vat_charge'];
              ?>
              <tr>
                <td><?php echo $value_two['bill_no'];?></td>
   
                <td><?php echo date($date_format['date_format'],strtotime($value_two['date_time']));?></td>
                <?php if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
                  $vatAmt=round($value_two['vat_charge'],2).' '.$date_format['currency'];
                  $netAmt=round($value_two['net_amount'],2).' '.$date_format['currency'];
                 }else{
                   $vatAmt=$date_format['currency'].' '.round($value_two['vat_charge'],2);
                   $netAmt=$date_format['currency'].' '.round($value_two['net_amount'],2);
                }?>
                <td><?php echo $netAmt;?></td>
                <td><?php echo $vatAmt;?></td>
                <td><?php if($value_two['paid_status']=='2'){ echo"NOT"; }elseif($value_two['paid_status']=='3'){ echo"FOC";}elseif( $value_two['paid_status'] =='1'){ echo"RECEIVED";}elseif($value_two['paid_status']=='4'){ echo"CANCELLED"; }else{ } ;?></td>
                <td><a href="<?php echo base_url('reports/order_details/'.$value_two['order_id']);?>" target="_blank"><button type="button" title="Click to view order detail"><i class="fa fa-external-link-square" aria-hidden="true"></i></button></a></td>
              </tr>
              <?php } ?>
              <tr>
              <th></th>
              <th>Total</th>                                
              <th>
                <?php if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
                echo array_sum($total_sumup).' '.$date_format['currency'];
                }else{
                echo $date_format['currency'].' '.array_sum($total_sumup);
                }?>
              </th>
              <th>
               <?php if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
                echo array_sum($total_vat).' '.$date_format['currency'];
                }else{
                echo $date_format['currency'].' '.array_sum($total_vat);
                }?> 
              </th>
              <th></th>
              <th></th>
             </tr> 


          

             
                       
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
$(function(){
$('#customerOrder').DataTable({
  "ordering": false, 
});
})
</script>
