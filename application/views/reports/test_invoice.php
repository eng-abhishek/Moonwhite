<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Invoice</title>
	<style>
		*{padding:0; margin:0;}
		p{font-size:14px; line-height:24px;}
		.invoice_section{padding:20px; font-family:lato; border: 1px solid #f4f4f4; margin:20px;}
		.logo_section{float:left;}
		.logo_section img{width:184px;padding-left:65px}
		.company_address{border-bottom:1px solid #eee; padding:10px;}
		.clr_both{clear:both;}
		.date_section{width:100%; float:left; padding:30px 0 15px 0;}
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
		<div class="logo_section">
    <img src="http://localhost/inventory/assets/images/mwlogo.png">

		</div>
		<div class="logo_section" style="padding-left:30px;">
			<h3 class="company_address">Moon White</h3>
			<h3 class="company_address">1234 Main St. Los Angeles, CA 98765 U.S.A. (123) 456-7890</h3>
		</div>
		<div class="clr_both"></div>
		
		<div class="date_section">
			<div>
				<p><strong>Date:</strong> 2022/03/06 02:30:30</p>
				<p><strong>Invoice Number:</strong> MW00120</p>
				<p><strong>Bill ID:</strong> BILPR-25A5</p>
			</div>
			
			<div>
				<h3 class="heading_text">Billing Address</h3>
				<p><strong>Name:</strong> 57</p>
				<p><strong>Address:</strong> DUBAI</p>
				<p><strong>Phone:</strong> 00000 00000</p>
			</div>
			
		</div>
		
		<div class="date_section" style="width:35%;">
			<div>
				<p><strong>Terms:</strong> 5 days</p>
				<p><strong>Pay Date:</strong> 2022-03-11 02:30:30</p>
			</div>
			
			<div>
				<h3 class="heading_text">Shipping Address</h3>
				<p><strong>Name:</strong> 57</p>
				<p><strong>Address:</strong> DUBAI</p>
				<p><strong>Phone:</strong> 00000 00000</p>
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
				<tr>
					<td>M700</td>
					<td><p>COTTON &amp; TRACK PANTS</p></td>
					<td>black</td>
					<td></td>
					<td>AED 16.66</td>
					<td>100</td>
					<td>AED 1666</td>
				</tr>
				</tbody>
			</table>
		 </div>
		 
		 <div class="table_price_area">
			<div class="table-responsive">
				<table class="table">
					<tbody>
					<tr>
					  <th style="width:50%">Gross Amount:</th>
					  <td>AED 1666.00</td>
					</tr>
					<tr>
					  <th>Vat Charge (AED 5%)</th>
					  <td>AED 83.30</td>
					</tr> 
					<tr>
					  <th>Discount:</th>
					  <td>AED 0</td>
					</tr>
					<tr>
					  <th>Net Amount:</th>
					  <td>AED 1749.30</td>
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