
            <table id="legerData" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Oreder Id</th>
                <th>Customer Code</th>
                <th>Customer Details</th>
                <th>Customer Address</th>
                <th>Order Date</th>
                <th>Payment Receive Date</th>
                <th>Due Date</th>
                <th>Value</th>
                <th>VAT</th>
                <th>PAYMENTS</th>
                <th>Created By</th>
                <th>Action</th>
               </tr>
              </thead>
             <tbody>
            <?php
             // echo"<pre>";
             // print_r($result);
             foreach ($result as $key01 => $value_one){
              //echo"<pre>";
              $total_sumup=array();
              $total_vat=array();
              ?>
              <?php foreach ($value_one as $key02 => $value_two){
               //print_r($value_two);
               // echo $value_two['cust_details'];
              $total_sumup[]=$value_two['net_amount'];
              $total_vat[]=$value_two['vat_charge'];
              $next_due_date=date($date_format['date_format'],strtotime($value_two['date_time']. ' +'.$value_two['due_date'].'days'));
              if($value_two['paid_status']==3){
              $next_due_date="N/A";
              }else{
              $next_due_date=date($date_format['date_format'],strtotime($value_two['date_time']. ' +'.$value_two['due_date'].'days'));
              }

               if(!empty($value_two['firstname'])){
                $frname=$value_two['firstname'];
                }else{
                $frname='';
                }

                if(!empty($value_two['lastname'])){
                $lastname=$value_two['lastname'];
                }else{
                $lastname='';
                }
                $user_name=$frname.' '.$lastname;
                
              ?>
              <tr>
                
                <td><?php echo $value_two['bill_no'];?></td> 
                <td><?php echo $value_one[0]['code'];?></td>
                <td><?php echo $value_two['cust_details'];?></td>
                <td><?php echo $value_two['customer_address'];?></td>
                <td><?php echo date($date_format['date_format'],strtotime($value_two['date_time']));?>
                </td>

                <?php if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
                  $vatAmt=round($value_two['vat_charge'],2).' '.$date_format['currency'];
                  $netAmt=round($value_two['net_amount'],2).' '.$date_format['currency'];
                 }else{
                   $vatAmt=$date_format['currency'].' '.round($value_two['vat_charge'],2);
                   $netAmt=$date_format['currency'].' '.round($value_two['net_amount'],2);
                }?>
                <td>
                <?php if($value_two['paid_status']==3){
                echo"N/A";
                }elseif(!empty($value_two['paid_date'])){
                echo date($date_format['date_format'],strtotime($value_two['paid_date']));
              
                }else{
                  echo"N/A";
                } ?> 
                </td>
                <td>
                <?php echo $next_due_date;?>  
                </td>

                <td><?php echo $netAmt;?></td>
                <td><?php echo $vatAmt;?></td>
                <td><?php if($value_two['paid_status']=='2'){ echo"NOT"; }elseif($value_two['paid_status']=='3'){ echo"FOC";}elseif( $value_two['paid_status'] =='1'){ echo"RECEIVED";}elseif($value_two['paid_status']=='4'){ echo"CANCELLED"; }else{ } ;?></td>
                <td> <?php echo $user_name;?></td>
                <td><a href="<?php echo base_url('reports/order_details/'.$value_two['order_id']);?>" target="_blank"><button type="button" title="Click to view order detail"><i class="fa fa-external-link-square" aria-hidden="true"></i></button></a></td>
              </tr>
              <?php } ?>
              <tr>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
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
                               
            <?php } ?>
            </tbody>
            </table>
<script type="text/javascript">
// $('#legerData').DataTable(); 

 $('#legerData').DataTable({
     "searching": false,
     "info":false,
     "ordering": false,
 }); 
</script>