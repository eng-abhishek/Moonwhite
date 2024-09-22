
            <table id="legerData" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Bill No</th>
                <th>Customer Name</th>
                <th>Qty</th>
                <th>Order Date</th>
               </tr>
              </thead>
             <tbody>
              <?php 
                $arrQty=array();
                foreach ($productData as $key => $value){
                $arrQty[]=$value['qty'];
              ?>
              <tr>
                <td><?php echo $value['bill_no'];?></td>
                <td><?php echo $value['name'];?></td>
                <td><?php echo $value['qty'];?></td>
                <td><?php echo date($date_format['date_format'],strtotime($value['date_time']));?></td>
               
              </tr>
              <?php } ?>
              <tr><td></td><th>Total</th><td><?php echo array_sum($arrQty);?></td><td></td></tr>
            </tbody>
            </table>
<script type="text/javascript">
   $('#legerData').DataTable({
     "searching":false,
     "info":false,
     "ordering": false,
 }); 
</script>