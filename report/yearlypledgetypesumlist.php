<html>
	<head>
		<style>
			body,table {
				font-size: 11px;
			}
			th,td {
				height: 22px;
				border-bottom-style: solid;
				overflow: hidden;
			  	white-space: pre;
			  	height: 22px;
			  	padding: 0 2px 0 2px;
			  	border-bottom-width: 1px;
			  	border-bottom-color: inherit;
				border-right-width: 1px;
				border-right-color: inherit;
				border-right-style: solid;
			}
			th {
				text-align:center;
				//color: #004276;
				font-weight: bold;
			}
		</style>
	</head>
<body>
<?php
require_once('include.php');
if (Sessions::isValidSession()) {
	$clsDB = new DB();
	$clsReports = new Reports($clsDB);
	$list = $clsReports->getYearlyPledgeTypeSumList();
	echo '<table border="1" rules="all">
			<tr><td colspan="9" style="text-align:center;font-weight:bold;font-size:18px">Yearly Pledge Type Sum List</td></tr>
			<tr style="background-color:#FFFF00;font-weight:bold">
				<th style="font-weight:bold;font-size:18px">Year</th>
				<th style="font-weight:bold;font-size:18px">Amount</th>
				<th style="font-weight:bold;font-size:18px">?</th>
				<th style="font-weight:bold;font-size:18px">Operation</th>
				<th style="font-weight:bold;font-size:18px">New Masjid</th>
				<th style="font-weight:bold;font-size:18px">Zakath</th>
				<th style="font-weight:bold;font-size:18px">Transportation</th>
				<th style="font-weight:bold;font-size:18px">Funeral</th>
				<th style="font-weight:bold;font-size:18px">School</th>
			</tr>';
	$row = 2;
	$totalpledgedamount = 0;
	$totalpaid = 0;
	foreach ($list as $key_name => $key_value) {
		//$totalpledgedamount+=$key_value["pledgedamount"];
		//$totalpaid+=$key_value["paid"];
		echo '<tr>
			<td style="text-align:right;font-size:15px">'.$key_value["year"].'</td>
			<td style="text-align:right;font-size:15px">'.number_format($key_value["amount"],2).'</td>
			<td style="text-align:right;font-size:15px">'.number_format($key_value["no"],2).'</td>
			<td style="text-align:right;font-size:15px">'.number_format($key_value["operation"],2).'</td>
			<td style="text-align:right;font-size:15px">'.number_format($key_value["newmasjid"],2).'</td>
			<td style="text-align:right;font-size:15px">'.number_format($key_value["zakath"],2).'</td>
			<td style="text-align:right;font-size:15px">'.number_format($key_value["transportation"],2).'</td>
			<td style="text-align:right;font-size:15px">'.number_format($key_value["funeral"],2).'</td>
			<td style="text-align:right;font-size:15px">'.number_format($key_value["school"],2).'</td>
		</tr>';
		$row++;
	}
	/*echo '<tr>
			<td colspan="6" style="text-align:right;font-weight:bold;font-size:53px">TOTAL</td>
			<td style="text-align:right;font-weight:bold;font-size:13px">'.number_format($totalpledgedamount, 2, '.', '').'</td>
			<td style="text-align:right;font-weight:bold;font-size:13px">'.number_format($totalpaid, 2, '.', '').'</td>
			<td style="text-align:right;font-weight:bold;font-size:13px">'.number_format($totaldiff, 2, '.', '').'</td>
		</tr>';*/
	echo '</table></br>';
}
?>
</body>
</html>