<style type="text/css">
	.bold{
    font-weight: 800;
    font-size: 18px;
    padding: 12px;
	}
</style>

	<?php 
	if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
	$OverAllSelling=round($OverAllSelling['OverallSelling'],2).' '.$date_format['currency'];
	$OverAllCollection=round($OverAllCollection['netOverAllColection'],2).' '.$date_format['currency'];
	$outstandingAmt=round($outstandingAmt['outStandAmt'],2).' '.$date_format['currency'];
	$OverAllFOC=round($OverAllFOC['OverAllFOC'],2).' '.$date_format['currency'];

	
	}else{
	$OverAllSelling=$date_format['currency'].' '.round($OverAllSelling['OverallSelling'],2);
	$OverAllCollection=$date_format['currency'].' '.round($OverAllCollection['netOverAllColection'],2);
	$outstandingAmt=$date_format['currency'].' '.round($outstandingAmt['outStandAmt'],2);
    $OverAllFOC=$date_format['currency'].' '.round($OverAllFOC['OverAllFOC'],2);

	}
	?>

<div class="row">
<div class="col-sm-6">
	<div class="bold">Sales Report</div>
	<table class="table table-bordered table-striped">
<tbody>
	<tr>
	 <td>Over All Selling Amount</td>
	 <td><?php echo $OverAllSelling;?></td>
	</tr>
	<tr>
		<td>Over All Collection Amount</td>
		<td><?php echo $OverAllCollection;?></td>
	</tr>
	<tr>
		<td>Total Outstanding Amount</td>
		<td><?php echo $outstandingAmt;?></td>
	</tr>

	<tr>
		<td>Over All FOC Amount</td>
		<td><?php echo $OverAllFOC?></td>
	</tr>
</tbody>
</table>
</div>


	<?php 
	if($date_format['currency']=='INR' OR $date_format['currency']=='USD'){
	$profitAmountData=round($profitAmount,2).' '.$date_format['currency'];
    $OverAllExp=round($OverAllExp['amount'],2).' '.$date_format['currency'];

    $primaryInvestorProfitData=round($primaryInvestorProfit,2).' '.$date_format['currency'];
    $sec01InvestorProfitData=round($sec01InvestorProfit,2).' '.$date_format['currency'];
    $sec02InvestorProfitData=round($sec02InvestorProfit,2).' '.$date_format['currency'];

	}else{
	$profitAmountData=$date_format['currency'].' '.round($profitAmount,2);
    $OverAllExp=$date_format['currency'].' '.round($OverAllExp['amount'],2);

    $primaryInvestorProfitData=$date_format['currency'].' '.round($primaryInvestorProfit,2);
    $sec01InvestorProfitData=$date_format['currency'].' '.round($sec01InvestorProfit,2);
    $sec02InvestorProfitData=$date_format['currency'].' '.round($sec02InvestorProfit,2);

	}
	?>

<div class="col-sm-6">
	<div class="bold">Profit Report</div>
<table class="table table-bordered table-striped">
<tbody>
	<tr>
	 <td>Over All Profit</td>
	 <td><?php echo $profitAmountData;?></td>
	</tr>
    <tr>
	 <td>Profit Formulas</td>
	 <td>Gross Amount - (saleOut qty * cost price) - Expenses Amount<sub> (of a particular month )</sub></td>
	</tr>
</tbody>
</table>
</div>

</div>


<div class="row">
<div class="col-sm-6">
	
<div class="bold">Expenses Report</div>
<table class="table table-bordered table-striped">
<tbody>
	<tr>
	 <td>Over All Expense Amount</td>
	 <td><?php echo $OverAllExp;?></td>
	</tr>
</tbody>
</table>
</div>

<div class="col-sm-6">
<div class="bold">Investor Profit Report</div>
<table class="table table-bordered table-striped">
<tbody>
	<tr>
	 <td><?php echo $primaryInvestorName;?></td>
	 <td><?php echo $primaryInvestorProfitData;?></td>
	</tr>
	
	<tr>
		<td><?php echo $sec01InvestorName;?></td>
		<td><?php echo $sec01InvestorProfitData;?></td>
	</tr>
	<tr>
		<td><?php echo $sec02InvestorName;?></td>
		<td><?php echo $sec02InvestorProfitData;?></td>
	</tr>
</tbody>
</table>
</div>	

</div>
