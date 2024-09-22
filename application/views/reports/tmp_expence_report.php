<style type="text/css">
.dataTables_length{
 display:none;
}
</style>
            <table id="legerData" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>S.No</th>
                <th>Code</th>                
                <th>Expence Name</th>
                <th>Amount</th>
                <th>Expence Date</th>
               </tr>
              </thead>
             <tbody>
            <?php
              $total_amt=array();
             ?>
              <?php foreach ($expData as $key02 => $value_two){
              $total_amt[]=$value_two['amount'];
              ?>
              <?php if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
              $row_amount=round($value_two['amount'],2).' '.$date_format['currency'];
             
              }else{
              $row_amount=$date_format['currency'].' '.round($value_two['amount'],2);

              }?>
              <tr>
                <td><?php echo $value_two['ExID'];?></td>
                <td><?php echo $value_two['code'];?></td>
                <td><?php echo $value_two['title'];?></td>
                <td><?php echo $row_amount;?></td>
                <td><?php echo date($date_format['date_format'],strtotime($value_two['expense_date']));?></td>
               </tr>
              <?php } ?>
              <?php 
               if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
                $row_amount=round(array_sum($total_amt),2).' '.$date_format['currency'];
              }else{
                $row_amount=$date_format['currency'].' '.round(array_sum($total_amt),2);
              }
              ?>
              <tr>
              <th></th>
              <th></th>
              <th>Total</th>
              <th><?php echo $row_amount;?></th>
              <th></th>
              </tr> 
            </tbody>
            </table>
<script type="text/javascript">
 $('#legerData').DataTable({
     "searching": false,
     "info":false,
     "ordering": false,
 }); 
</script>