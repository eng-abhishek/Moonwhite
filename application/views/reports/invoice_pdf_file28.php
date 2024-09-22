<!DOCTYPE lang="ar">
			<html lang="ar">
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
			</head>
			<body>
			<style>
			.page-header
			{
				padding : 0px !important;
				margin : 0px !important;
				border-bottom: none !important;
			}
			</style>
			<div class="wrapper">
			  <section class="invoice">
			    <!-- title row -->
			    <div class="row">
			    	<div class="col-xs-5 invoice-col">
				        <h3 class="page-header">
				      <?php echo $company_data['company_name'];?>		          
				        </h3>
				        <br/>
				        <span class="page-header">
				        <?php echo $company_data['address'];?>
				        </span>
				        <br/>
				        <span class="page-header">
				        <?php echo $company_data['phone'];?>          
				        </span>
			       		       
			      	</div>
			    	<div class="col-xs-2 invoice-col">			    	
				  <img src="<?php echo base_url('assets/images/mwlogo.png');?>">		    
			      	</div>
			      	<div class="col-xs-5 invoice-col">
				        <h3 class="page-header">
                        <?php echo $company_data['ar_company_name'];?>
      				    </h3>
				        <br/>
				        <span class="page-header">
			      	  <?php echo $company_data['ar_address'];?>
				        </span>
				        <br/>
				        <span class="page-header">
				      <?php echo $company_data['ar_phone'];?>        
				        </span>   
			      	</div>
			    </div>			    
			    <div class="row invoice-info">
			      <br/>
			      <div class="col-sm-5 invoice-col">
			        
			        <b>Date:</b> <?php echo $order_date;?><br>
			        <b>Invoice Number:</b><?php echo "MW00".$order_data['id'];?><br>
			        <b>Bill ID:</b><?php echo $order_data['bill_no'];?><br>
			      </div>

			      <div class="col-sm-2 invoice-col">
			      	
			      	<span>Tax Invoice <span>&nbsp;  فاتورة ضريبية<</span>
			      	<br/>
			        <?php echo $custID['trn'];?>
			      	</span>
			      </div>

			      <div class="col-sm-5 invoice-col">
			        
			        <b>Due : </b> <?php echo $due_days;?> Days <br>
			        <b>Pay Date:</b><?php echo $next_due_date;?> <br>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			    <!-- Table row -->


			     <div class="row invoice-info">
			      <br/>
			      <div class="col-sm-5 invoice-col">
			        <h4>Billing Address</h4>
			        <b>Name:</b> <?php echo $custID['name'];?><br>
			        <b>Address:</b> <?php echo $custID['address'];?><br />
			        <b>Phone:</b> <?php echo $custID['contact'];?>
			      </div>

			       <div class="col-sm-2 invoice-col">
			       </div>

			      <div class="col-sm-5 invoice-col">
			        <h4>Shipping Address</h4>			        
			        <b>Name:</b> <?php echo $custID['name'];?><br>
			        <b>Address:</b> <?php echo $custID['address'];?> <br />
			        <b>Phone:</b> <?php echo $custID['contact'];?>
			      </div>

			      <!-- /.col -->
			    </div>


			    <div class="row">
			    <br/>
			      <div class="col-xs-12 table-responsive">
			        <table class="table table-striped">
			          <thead>
			          <tr>
			            <th>Invoice No</th>
			          	<th>Model No</th>
			            <th>Product name</th>
			            <th>Color</th>
			            <th>Size</th>
			            <th>Price</th>
			            <th>Qty</th>
			            <th>Amount</th>
			          </tr>
			          </thead>
			          <tbody>
              <?php
          	  foreach ($orders_items as $key => $value) {
              if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
              $rowRateAmt=$value['rate'].' '.$date_format['currency'];
              $rowtotalAmt=round($value['rate']*$value['qty'],2).' '.$date_format['currency'];
             }else{
              $rowRateAmt=$date_format['currency'].' '.$value['rate'];
              $rowtotalAmt=$date_format['currency'].' '.round($value['rate']*$value['qty'],2);
             }

              ?>	
			        <tr>
					<td><?php echo $value['invoice_no_pro'];?></td>
					<td><?php echo $value['model_no_pro'];?></td>
				    <td><?php echo $value['pro_name'];?></td>
				  	<td><?php echo $value['color'];?></td>
					<td><?php echo $value['size'] ? : "N/A";?></td>
					<td><?php echo $rowRateAmt;?></td>
					<td><?php echo $value['qty'];?></td>
				 	<td><?php echo $rowtotalAmt;?></td>
				    </tr>
			         <?php } ?>
			          
			          </tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			    <div class="row">
			      
			      <div class="col-xs-5 pull-right">

			        <div class="table-responsive">
			 <?php 
   if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
   $grossAmt=round($order_data['gross_amount'],2).' '.$date_format['currency'];
   $vatAmt=round($order_data['vat_charge'],2).' '.$date_format['currency'];
   $discountAmt=round($order_data['total_discount'],2).' '.$date_format['currency'];
   $netAmt=round($order_data['net_amount'],2).' '.$date_format['currency'];
   }else{
   $grossAmt=$date_format['currency'].' '.round($order_data['gross_amount'],2);
   $vatAmt=$date_format['currency'].' '.round($order_data['vat_charge'],2);
   $discountAmt=$date_format['currency'].' '.round($order_data['total_discount'],2);
   $netAmt=$date_format['currency'].' '.round($order_data['net_amount'],2);
   }
          ?>
			        <table class="table">
			            <tr>
			              <th style="width:50%">Gross Amount:</th>
			              <td><?php echo $grossAmt;?></td>
			            </tr>';
			       

			           
			            	<tr>
				              <th>Vat Charge (<?php echo $order_data['vat_charge_rate'];?> %)</th>
				              <td><?php echo $vatAmt;?></td>
				            </tr>
			            
			            
			         
			           <tr>
			              <th>Discount:</th>
			              <td><?php echo $discountAmt;?></td>
			            </tr>
			            <tr>
			              <th>Net Amount:</th>
			              <td><?php echo $netAmt;?></td>
			            </tr>
			           
			          </table>
			        </div>
			      </div>

			     
			      <!-- /.col -->
			    </div>

			     <div class="row invoice-info">
			      
			      <div class="col-sm-4 invoice-col">
			        <b>Checker :</b> <br>
			        <b>Date :</b>  <br />			        
			      </div>

			       <div class="col-sm-4 invoice-col">
			       </div>

			      <div class="col-sm-4 invoice-col">			        	        
			        <b>Customer Signature :</b>  <br>
			        <b>Date :</b>  <br />			        
			      </div>

			      <!-- /.col -->
			    </div>

			    <!-- /.row -->
			  </section>
			  <!-- /.content -->
			</div>
		</body>
	</html>