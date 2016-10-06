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
if (Sessions::isValidSession() && isset($_GET['year']) ) {
	$clsDB = new DB();
	$clsReports = new Reports($clsDB);
	$clsEvents = new Events($clsDB);

	$year  = $_GET['year'];//@intval(date('Y'));
	//paymentListWorkSheet($clsReports->getCompleteList($year), $year.' List');

	paymentSumWorkSheet($clsReports->getCompleteSum($year), $year.' Summary');

	$activeEvents = $clsEvents->getActiveEventList();
	foreach ($activeEvents as $key_name => $key_value) {
		//$list = $clsReports->getEventPaymentList($key_value["id"]);
		//paymentListWorkSheet($list, $key_value["title"].' List');

		$list = $clsReports->getEventSum($key_value["id"]);
		eventSumWorkSheet($list, $key_value["title"].' Sum');
	}
}

function paymentListWorkSheet($paymentsList, $title) {
	echo '<table border="1" rules="all">
			<tr><td colspan="9" style="text-align:center;font-weight:bold;font-size:18px">'.$title.'</td></tr>
			<tr style="background-color:#FFFF00;font-weight:bold">
				<th>NAME</th>
				<th>STREET ADDRESS</th>
				<th>CITY, ZIP</th>
				<th>PHONE</th>
				<th>EMAIL</th>
				<th>PAID</th>
				<th>DONATION DATE</th>
				<th>PAYMENT METHOD</th>
				<th>COMMENTS</th>
			</tr>';
	$row = 2;
	$totalAmount = 0;
	foreach ($paymentsList as $key_name => $key_value) {
		$totalAmount+=$key_value["amount"];
		$name =$key_value["name"].
			(strlen($key_value["company_name"])>0?' ('. $key_value["company_name"].')':'');
		$address =$key_value["address1"].
			(strlen($key_value["address2"])>0?', '. $key_value["address2"]:'');
		$csz= $key_value["city"].
			(strlen($key_value["state"])>0?', '. $key_value["state"]:'').
			(strlen($key_value["zipcode"])>0?' '. $key_value["zipcode"]:'');
		echo '<tr>
			<td>'.$name.'</td>
			<td>'.$address.'</td>
			<td>'.$csz.'</td>
			<td>'.$key_value["phone"].'</td>
			<td>'.$key_value["email"].'</td>
			<td style="text-align:right;">'.$key_value["amount"].'</td>
			<td>'.$key_value["payment_date"].'</td>
			<td>'.$key_value["payment_method"].'</td>
			<td>'.$key_value["payment_comments"].'</td>
		</tr>';
		$row++;
	}
	echo '<tr>
			<td colspan="5" style="text-align:right;font-weight:bold;font-size:13px">TOTAL</td>
			<td style="text-align:left;font-weight:bold;font-size:13px">'.number_format($totalAmount, 2, '.', '').'</td>
			<td colspan="3"></td>
		</tr></table></br>';
}

function paymentSumWorkSheet($paymentsList, $title) {
	echo '<table border="1" rules="all">
			<tr><td colspan="6" style="text-align:center;font-weight:bold;font-size:18px">'.$title.'</td></tr>
			<tr style="background-color:#FFFF00;font-weight:bold">
				<th>NAME</th>
				<th>STREET ADDRESS</th>
				<th>CITY, ZIP</th>
				<th>PHONE</th>
				<th>EMAIL</th>
				<th>PAID</th>
			</tr>';
	$row = 2;
	$totalAmount = 0;
	foreach ($paymentsList as $key_name => $key_value) {
		$totalAmount+=$key_value["amount"];
		$name =$key_value["name"].
			(strlen($key_value["company_name"])>0?' ('. $key_value["company_name"].')':'');
		$address =$key_value["address1"].
			(strlen($key_value["address2"])>0?', '. $key_value["address2"]:'');
		$csz= $key_value["city"].
			(strlen($key_value["state"])>0?', '. $key_value["state"]:'').
			(strlen($key_value["zipcode"])>0?' '. $key_value["zipcode"]:'');
		echo '<tr>
			<td>'.$name.'</td>
			<td>'.$address.'</td>
			<td>'.$csz.'</td>
			<td>'.$key_value["phone"].'</td>
			<td>'.$key_value["email"].'</td>
			<td style="text-align:right;">'.$key_value["amount"].'</td>
		</tr>';
		$row++;
	}
	echo '<tr>
			<td colspan="5" style="text-align:right;font-weight:bold;font-size:13px">TOTAL</td>
			<td style="text-align:right;font-weight:bold;font-size:13px">'.number_format($totalAmount, 2, '.', '').'</td>
		</tr></table></br>';
}

function eventSumWorkSheet($paymentsList, $title) {
	echo '<table border="1" rules="all">
			<tr><td colspan="6" style="text-align:center;font-weight:bold;font-size:18px">'.$title.'</td></tr>
			<tr style="background-color:#FFFF00;font-weight:bold">
				<th>NAME</th>
				<th>STREET ADDRESS</th>
				<th>CITY, ZIP</th>
				<th>PHONE</th>
				<th>EMAIL</th>
				<th>PLEDGED AMOUNT</th>
				<th>PAID</th>
				<th>BALANCE</th>
			</tr>';
	$row = 2;
	$totalpledgedamount = 0;
	$totalpaid = 0;
	foreach ($paymentsList as $key_name => $key_value) {
		$totalpledgedamount+=$key_value["pledgedamount"];
		$totalpaid+=$key_value["paid"];
		$balance  =  $key_value["pledgedamount"]-$key_value["paid"];
		if ($balance<0) $balance =0;
		$name =$key_value["name"].
			(strlen($key_value["company_name"])>0?' ('. $key_value["company_name"].')':'');
		$address =$key_value["address1"].
			(strlen($key_value["address2"])>0?', '. $key_value["address2"]:'');
		$csz= $key_value["city"].
			(strlen($key_value["state"])>0?', '. $key_value["state"]:'').
			(strlen($key_value["zipcode"])>0?' '. $key_value["zipcode"]:'');
		echo '<tr>
			<td>'.$name.'</td>
			<td>'.$address.'</td>
			<td>'.$csz.'</td>
			<td>'.$key_value["phone"].'</td>
			<td>'.$key_value["email"].'</td>
			<td style="text-align:right;">'.$key_value["pledgedamount"].'</td>
			<td style="text-align:right;">'.$key_value["paid"].'</td>
			<td style="text-align:right;">'.$balance.'</td>
		</tr>';
		$row++;
	}
	$totaldiff = $totalpledgedamount - $totalpaid;
	echo '<tr>
			<td colspan="5" style="text-align:right;font-weight:bold;font-size:13px">TOTAL</td>
			<td style="text-align:right;font-weight:bold;font-size:13px">'.number_format($totalpledgedamount, 2, '.', '').'</td>
			<td style="text-align:right;font-weight:bold;font-size:13px">'.number_format($totalpaid, 2, '.', '').'</td>
			<td style="text-align:right;font-weight:bold;font-size:13px">'.number_format($totaldiff, 2, '.', '').'</td>
		</tr></table></br>';
}

?>
</body>
</html>