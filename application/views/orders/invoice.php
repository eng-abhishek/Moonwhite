<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice</title>
    <style> 
 * { font-family: DejaVu Sans, sans-serif; }
</style>

	<style>
		*{padding:0; margin:0;}
		p{font-size:14px; line-height:24px;}
		.invoice_section{padding:20px; font-family:lato; border: 1px solid #f4f4f4; margin:20px;}
		.logo_section{float:left;}
        .logo_section img{}
        h3{font-family: 'Source Sans Pro',sans-serif;}
		.logo_section_right{float:right;}
		.company_address{padding:10px;}
		.clr_both{clear:both;}
		.date_section{width:65%; float:left; padding:30px 0 15px 0;}
		.heading_text{padding:30px 0 10px 0;}
		.table-responsive {min-height: .01%; overflow-x: auto;}
		.table {width: 100%; max-width: 100%; margin-bottom: 20px;}
		table { background-color: transparent; border-spacing: 0; border-collapse: collapse; text-align:left;}
		td{background:#f9f9f9; padding:10px 0;}
		td, th{padding:10px; border: 1px solid #f4f4f4;}
		.table_price_area{width:50%; float:right;}
		.ftr_heading{padding:5px;}
	</style>
</head>
<body>
	<div class="invoice_section">

		<div class="logo_section" style="width:41.66666667%;">
			<h3 class="company_address"><?php echo $company_info['company_name'];?></h3>
			<h3 class="company_address"><?php echo $company_info['address'];?></h3>
			<h3 class="company_address"><?php echo $company_info['phone'];?></h3>
		
		</div>

		<div class="logo_section" style="width:16.66666667%;">
        <img src="<?php echo base_url('assets/images/mwlogo.png');?>">
		</div>

		<div class="logo_section_right" style="width:41.66666667%;">
			<h3 class="company_address"><?php echo $company_info['ar_company_name'];?></h3>
			<h3 class="company_address"><?php echo $company_info['ar_address'];?></h3>
			<h3 class="company_address"><?php echo $company_info['ar_phone'];?></h3>
		</div>

		<div class="clr_both"></div>
		
		<div class="date_section">
			<div>
				<p><strong>Date:</strong><?php echo $order_date;?></p>
				<p><strong>Invoice Number:</strong><?php echo 'MW00'.$order_data['id'];?></p>
				<p><strong>Bill ID:</strong><?php echo $order_data['bill_no'];?></p>
			</div>
			
			<div>
				<h3 class="heading_text">Billing Address</h3>
			    <p><strong>Name:</strong><?php echo $custID['name'];?></p>
			    <p><strong>Code:</strong><?php echo $custID['code'];?></p>
				<p><strong>Address:</strong> <?php echo $custID['address'];?></p>
				<p><strong>Phone:</strong><?php echo $custID['contact'];?></p>
			</div>
			
		</div>
		
		<div class="date_section" style="width:35%;">
			<div>
				<p><strong>Terms:</strong> <?php echo $due_days;?> days</p>
				<p><strong>Pay Date:</strong> <?php echo $next_due_date;?></p>
			</div>
			<div>
				<h3 class="heading_text">Shipping Address</h3>
			    <p><strong>Name:</strong><?php echo $custID['name'];?></p>
			    <p><strong>Code:</strong><?php echo $custID['code'];?></p>
				<p><strong>Address:</strong> <?php echo $custID['address'];?></p>
				<p><strong>Phone:</strong><?php echo $custID['contact'];?></p>
			</div>
		</div>
		
		<div class="clr_both"></div>
		
		<div class="table-responsive">
			<table class="table table-striped">
			   <thead>
			          <tr>
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
			  foreach ($orders_items as $key => $value){
               ?>		
				<tr>
					<td><?php echo $value['model_no'];?></td>
				    <td><?php echo $value['description'];?></td>
				    <td><?php echo $value['color'];?></td>
					<td><?php echo $value['size'];?></td>
				    <td><?php echo $value['rate'];?></td>
					<td><?php echo $value['qty'];?></td>
				 	<td><?php echo $value['rate']*$value['qty'];?></td>
				
				 </tr>
			<?php } ?>	
				</tbody>
			</table>
		 </div>
	
		 <div class="table_price_area">
			<div class="table-responsive">
				<table class="table">
					<tbody>
					<tr>
					  <th style="width:50%">Gross Amount:</th>
					  <td><?php echo $order_data['gross_amount'];?></td>
					</tr>
					<tr>
					  <th>Vat Charge (AED <?php echo $order_data['vat_charge_rate'];?>%)</th>
					  <td><?php echo $order_data['vat_charge'];?></td>
					</tr> 
					<tr>
					  <th>Discount:</th>
					  <td><?php echo $order_data['total_discount'];?></td>
					</tr>
					<tr>
					  <th>Net Amount:</th>
					  <td><?php echo $order_data['net_amount'];?></td>
					</tr>
				   
				  </tbody>
				</table>
			</div>
		 </div>
		 <div class="clr_both"></div>
		 
		 <div class="date_section">			
			<div>
				<h4 class="ftr_heading">Checker:</h4>
				<h4 class="ftr_heading">Date:</h4>
			</div>
			
		</div>
		
		<div class="date_section" style="width:35%;">			
			<div>
				<h4 class="ftr_heading">Customer Signature:</h4>
				<h4 class="ftr_heading">Date:</h4>
			</div>
		</div>
		
		<div class="clr_both"></div>
	</div>
</body>

</html>