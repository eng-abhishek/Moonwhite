<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice</title>
    <style>
</style>
<!--    <link rel="stylesheet" href="<?php //echo base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css');?>"> -->
  <!--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
	<style>
		*{padding:0; margin:0;}
		body{font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;line-height: 1.42857143;}
		 p{font-size:16px; line-height:25px;}
		.invoice_section{padding:15px; font-family:lato; margin:15px;}
		.logo_section{float:left;}
        .logo_section img{}
        /*h3{font-family: 'Source Sans Pro',sans-serif;} h3{ font-family: DejaVu Sans, sans-serif; }*/
		.logo_section_right{float:right;}
	    
		.clr_both{clear:both;}
		.date_section{width:36%; float:left; padding:30px 0 15px 0;}
		.date_section01{width:28%; float:left; padding:30px 0 15px 0;}
		.date_section02{width:36%; float:left; padding:30px 0 15px 0;}
		.heading_text{padding:30px 0 10px 0; font-size:17px}
		/*.table-responsive {min-height: .01%; overflow-x: auto;}*/
		/*.table {width: 100%; max-width: 100%; margin-bottom: 20px;}*/
		table { background-color: transparent; border-spacing: 0; border-collapse: collapse; text-align:left;}
		td{padding:10px 0;}
		td, th{padding:10px; border: 1px solid #f4f4f4;}
		.table_price_area{width:40%; float:right;}
		.ftr_heading{padding:5px;}
		/* h2{font-weight: 100;padding:4px;font-size: 18px} */
	</style>
</head>
<body>
	<div class="invoice_section">

		<div class="logo_section" style="width:35.66666667%; font-family:initial;text-align:start;">
			<h3 style="padding-top:1px;font-weight:500;line-height: 1.1;"><?php echo $company_data['company_name'];?></h3>
	
		 	<span style="font-size:22px;font-weight:500;" class="company_address"><?php echo $company_data['address'];?></span>
		 	<br>
			<span style="font-size:22px;font-weight:400;" class="company_address"><?php echo $company_data['phone'];?></span>
		</div>

		<div class="logo_section" style="width:16.66666667%;">
        <img src="<?php echo base_url('assets/images/mwlogo.png');?>">
		</div>

		<div class="logo_section_right" style="width:35.66666667%;">
		<span style="font-weight:bold;padding-top:4px;font-family: DejaVu Sans, sans-serif;"><?php echo $company_data['ar_company_name'];?></span>
		<br>
		<span class="company_address" style="font-size:16px;font-family: DejaVu Sans,sans-serif;"><?php echo $company_data['ar_address'];?></span>
			<br>
		<span class="company_address" style="font-size:16px;font-family: DejaVu Sans,sans-serif;"><?php echo $company_data['ar_phone'];?></span>
		</div>

		<div class="clr_both"></div>
		
		<div class="date_section">
			<div>
				<p><strong>Date:</strong><?php echo $order_date;?></p>
				
				<p><strong>Bill ID:</strong> <?php echo $order_data['bill_no'];?></p>
			</div>
			
			<div>
				<h3 class="heading_text">Billing Address</h3>
			    <p><strong>Name:</strong><?php echo $custID['name'];?></p>
			    <p><strong>Code:</strong><?php echo $custID['code'];?></p>
				<p><strong>Address:</strong> <?php echo $custID['address'];?></p>
				<p><strong>Phone:</strong><?php echo $custID['contact'];?></p>
			</div>
			
		</div>
		
		<div class="date_section01">
	    <p>Tax Invoice <font style="font-family: DejaVu Sans, sans-serif; font-size:12px">فاتورة ضريبية< </font></p>
	    <p>TRN <?php echo $custID['trn'];?></p>
		</div>


		<div class="date_section02">
			<div>
				<p><strong>Terms:</strong> <?php echo $due_days;?></p>
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
			<table class="table">
			  <thead>
				  <tr>
					<th>Invoice No</th>
					<th>Model No</th>
			 	    <th>Product Name</th>
				    <th>Color</th>
					<th>Size</th>
				    <th>Price</th>
				  	<th>Qty</th>
				    <th>Discount</th>
					<th>Amount</th>
                  </tr>
			  </thead>
			  <tbody>
			 <?php
          	  foreach ($orders_items as $key => $value) {
              if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
              $rowRateAmt=$value['rate'].' '.$date_format['currency'];
              $rowtotalAmt=round($value['rate']*$value['qty'],2)-$value['discount'].' '.$date_format['currency'];
             }else{
              $rowRateAmt=$date_format['currency'].' '.$value['rate'];
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
		 <div class="table_price_area">
			
				<table style="font-size:16px"> 
					<tbody>
					<tr>
					  <th style="border:none;width:50%;padding:5px">Gross Amount</th>
					  <td style="border:none;padding:5px"><?php echo $grossAmt;?></td>
					</tr>
					<tr>
					  <th style="border:none;padding:5px">Vat Charge (<?php echo $order_data['vat_charge_rate'];?> %)</th>
					  <td style="border:none;padding:5px"><?php echo $vatAmt;?></td>
					</tr> 
					<tr style="border:none;">
					  <th style="border:none;padding:5px">Discount</th>
					  <td style="border:none;padding:5px"><?php echo $discountAmt;?></td>
					</tr>
					<tr style="border:none;">
					  <th style="border:none;padding:5px">Net Amount</th>
					  <td style="border:none;padding:5px"><?php echo $netAmt;?></td>
					</tr>
				   
				  </tbody>
				</table>
			
		 </div>
		 <div class="clr_both"></div>
		 
		 <div class="date_section">			
			<div>
				<h4 class="ftr_heading">Checker:</h4>
				<h4 class="ftr_heading">Date:</h4>
			</div>
			
		</div>
		<div class="date_section01"></div>		
		<div class="date_section02">	
			<div>
				<h4 class="ftr_heading">Customer Signature:</h4>
				<h4 class="ftr_heading">Date:</h4>
			</div>
		</div>
		
		<div class="clr_both"></div>
	</div>
</body>

</html>