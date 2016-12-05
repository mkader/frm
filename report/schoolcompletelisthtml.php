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
$boldCenter = "style='text-align:center;font-weight:bold;'";
$bold = "style='font-weight:bold;'";

if (Sessions::isValidSession() && isset($_GET['year']) ) {
	$clsDB = new DB();
	$clsReports = new Reports($clsDB);

	$year  = $_GET['year'];
	$schoolYearFeeSum = $clsReports->getSchoolYearFeeSum($year);
	Summary($schoolYearFeeSum, $year);

	$parentFeeSum = $clsReports->getParentFeeSum($year);
	EnrollmentPaymentSummary($parentFeeSum);

	$enrollmentList = $clsReports->getEnrollmentList( "concat(first_name,' ', last_name) asc");
	StudentList($enrollmentList);

	$enrollmentList = $clsReports->getEnrollmentList();
	EnrollmentList($enrollmentList);
}

function DisplayRow($style, $values) {
	$html='';
	$html.='<td>'.$values[0].'</td>';
	$html.='<td align="right">'.$values[1].'</td>';
	$html.='<td align="right">'.$values[2].'</td>';
	$html.='<td align="right">'.$values[3].'</td>';
	$html.='<td align="right">'.$values[4].'</td>';
	$html.='<td align="right">'.$values[5].'</td>';
	$html.='<td align="right">'.$values[6].'</td>';
	$html.='<td align="right">'.$values[7].'</td>';
	$html.='<td align="right">'.$values[8].'</td>';
	if (sizeof($values)>9)
		$html.='<td align="right">'.$values[9].'</td>';
	if (sizeof($values)>10) {
		$html.='<td align="right">'.$values[10].'</td>';
		$html.='<td align="right">'.$values[11].'</td>';
		$html.='<td align="right">'.$values[12].'</td>';
		$html.='<td align="right">'.$values[13].'</td>';
	}
	if (sizeof($values)>14) {
		$html.='<td align="right">'.$values[14].'</td>';
		$html.='<td align="right">'.$values[15].'</td>';
		$html.='<td align="right">'.$values[16].'</td>';
		$html.='<td align="right">'.$values[17].'</td>';
	}
	echo '<tr '. $style .'>'.$html.'</tr>';
}

function Summary($schoolYearFeeSum, $year) {
	global $boldCenter, $bold;
	echo '<table border="1" rules="all">
			<tr><td colspan="14" style="text-align:center;font-weight:bold;font-size:18px">Weekend School '.$year.' - ' .($year+1) .' Summary</td></tr>
			<tr style="background-color:#FFFF00;font-weight:bold">
			<th></th><th colspan="3">'.$year.'</th><th colspan="9">'.($year+1).'</th><th></th></tr>';

	$colValues= array("","Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Total");
	DisplayRow($boldCenter, $colValues);

	$iValue0 =$schoolYearFeeSum[0];
	$colValues= array("Fee", $iValue0["S10"], $iValue0["S11"], $iValue0["S12"], $iValue0["E1"], $iValue0["E2"],
					  $iValue0["E3"], $iValue0["E4"], $iValue0["E5"], $iValue0["E6"], $iValue0["E7"],
				  $iValue0["E8"], $iValue0["E9"], $iValue0["SE"]);
	DisplayRow('', $colValues);

	$iValue1 =$schoolYearFeeSum[1];
	$colValues= array("Donation", $iValue1["S10"], $iValue1["S11"], $iValue1["S12"], $iValue1["E1"], $iValue1["E2"],
			$iValue1["E3"], $iValue1["E4"], $iValue1["E5"], $iValue1["E6"], $iValue1["E7"],
			$iValue1["E8"], $iValue1["E9"], $iValue1["SE"]);
	DisplayRow('', $colValues);
	
	$iValue5 =$schoolYearFeeSum[4];
	$colValues= array("Sale", $iValue5["S10"], $iValue5["S11"], $iValue5["S12"], $iValue5["E1"], $iValue5["E2"],
			$iValue5["E3"], $iValue5["E4"], $iValue5["E5"], $iValue5["E6"], $iValue5["E7"],
			$iValue5["E8"], $iValue5["E9"], $iValue5["SE"]);
	DisplayRow('', $colValues);

	$colValues= array("Income", $iValue0["S10"]+$iValue1["S10"]+$iValue5["S10"], 
						$iValue0["S11"]+$iValue1["S11"]+$iValue5["S11"], 
						$iValue0["S12"]+$iValue1["S12"]+$iValue5["S12"], 
						$iValue0["E1"]+$iValue1["E1"]+$iValue5["E1"], 
						$iValue0["E2"]+$iValue1["E2"]+$iValue5["E2"],
					  	$iValue0["E3"]+$iValue1["E3"]+$iValue5["E3"], 
						$iValue0["E4"]+$iValue1["E4"]+$iValue5["E4"],
			          	$iValue0["E5"]+$iValue1["E5"]+$iValue5["E5"], 
						$iValue0["E6"]+$iValue1["E6"]+$iValue5["E6"],
			          	$iValue0["E7"]+$iValue1["E7"]+$iValue5["E7"], 
						$iValue0["E8"]+$iValue1["E8"]+$iValue5["E7"],
					    $iValue0["E9"]+$iValue1["E9"]+$iValue5["E9"], 
						$iValue0["SE"]+$iValue1["SE"]+$iValue5["SE"]);
	DisplayRow($bold, $colValues);
	echo '<tr><td colspan="14"></tr>';

	$iValue2 =$schoolYearFeeSum[2];
	$colValues= array("Salary", $iValue2["S10"], $iValue2["S11"], $iValue2["S12"], $iValue2["E1"], $iValue2["E2"],
			$iValue2["E3"], $iValue2["E4"], $iValue2["E5"], $iValue2["E6"], $iValue2["E7"],
			$iValue2["E8"], $iValue2["E9"], $iValue2["SE"]);
	DisplayRow('', $colValues);

	$iValue3 =$schoolYearFeeSum[3];
	$colValues= array("Supplies", $iValue3["S10"], $iValue3["S11"], $iValue3["S12"], $iValue3["E1"], $iValue3["E2"],
			$iValue3["E3"], $iValue3["E4"], $iValue3["E5"], $iValue3["E6"], $iValue3["E7"],
			$iValue3["E8"], $iValue3["E9"], $iValue3["SE"]);
	DisplayRow('', $colValues);

	$colValues= array("Expenses", $iValue2["S10"]+$iValue3["S10"], $iValue2["S11"]+$iValue3["S11"],
					  $iValue2["S12"]+$iValue3["S12"], $iValue2["E1"]+$iValue3["E1"], $iValue2["E2"]+$iValue3["E1"],
					  $iValue2["E3"]+$iValue3["E3"], $iValue2["E4"]+$iValue3["E4"],
			          $iValue2["E5"]+$iValue3["E5"], $iValue2["E6"]+$iValue3["E6"],
			          $iValue2["E7"]+$iValue3["E7"], $iValue2["E8"]+$iValue3["E8"],
					    $iValue2["E9"]+$iValue3["E9"], $iValue2["SE"]+$iValue3["SE"]);
	DisplayRow($bold, $colValues);
	echo '<tr><td colspan="14"></tr>';

	$colValues= array("Total", $iValue0["S10"]+$iValue1["S10"] - ($iValue2["S10"]+$iValue3["S10"]),
						$iValue0["S11"]+$iValue1["S11"] - ($iValue2["S11"]+$iValue3["S11"] ),
						$iValue0["S12"]+$iValue1["S12"] - ($iValue2["S12"]+$iValue3["S12"]),
						$iValue0["E1"]+$iValue1["E1"] - ($iValue2["E1"]+$iValue3["E1"]),
						$iValue0["E2"]+$iValue1["E1"] - ($iValue2["E2"]+$iValue3["E1"]),
						$iValue0["E3"]+$iValue1["E3"] - ($iValue2["E3"]+$iValue3["E3"]),
						$iValue0["E4"]+$iValue1["E4"] - ($iValue2["E4"]+$iValue3["E4"]),
						$iValue0["E5"]+$iValue1["E5"] - ($iValue2["E5"]+$iValue3["E5"]),
						$iValue0["E6"]+$iValue1["E6"] - ($iValue2["E6"]+$iValue3["E6"]),
						$iValue0["E7"]+$iValue1["E7"] - ($iValue2["E7"]+$iValue3["E7"]),
						$iValue0["E8"]+$iValue1["E8"] - ($iValue2["E8"]+$iValue3["E8"]),
					    $iValue0["E9"]+$iValue1["E9"] - ($iValue2["E9"]+$iValue3["E9"]),
					    $iValue0["SE"]+$iValue1["SE"] - ($iValue2["SE"]+$iValue3["SE"]));
	DisplayRow($bold, $colValues);

	echo '</table><br/>';
}

function EnrollmentPaymentSummary($parentFeeSum) {
	global $boldCenter, $bold;
	echo '<table border="1" rules="all">
			<tr><td colspan="18" style="text-align:center;font-weight:bold;font-size:18px">Enrollment Fee Summary Report</td></tr>';


	$colValues= array("Parent Name", "Address", "Aid", "Fees",  "Students", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Total");
	DisplayRow($boldCenter, $colValues);
	
	$S10 = $S11 = $S12 = $E1 = $E2 = $E3 = $E4 = $E5 = $E6 = $E7 = $E8 = $E9 = $SE =0;
	$no_of_student = $total_fee = 0;
	foreach ($parentFeeSum as $key_name => $key_value) {

		$colValues= array($key_value["parent_name"], $key_value["address_line"],
				$key_value["financial_aid"], $key_value["total_fee"],  $key_value["no_of_student"],
				$key_value["S10"], $key_value["S11"], $key_value["S12"], $key_value["E1"], $key_value["E2"],
				$key_value["E3"], $key_value["E4"], $key_value["E5"], $key_value["E6"], $key_value["E7"],
				$key_value["E8"], $key_value["E9"], $key_value["SE"]);

		$S10+=$key_value["S10"];
		$S11+=$key_value["S11"];
		$S12+=$key_value["S12"];
		$E1+=$key_value["E1"];
		$E2+=$key_value["E2"];
		$E3+=$key_value["E3"];
		$E4+=$key_value["E4"];
		$E5+=$key_value["E5"];
		$E6+=$key_value["E6"];
		$E7+=$key_value["E7"];
		$E8+=$key_value["E8"];
		$E9+=$key_value["E9"];
		$SE+=$key_value["SE"];
		$total_fee+=$key_value["total_fee"];
		$no_of_student+=$key_value["no_of_student"];
		DisplayRow('', $colValues);
	}

	$colValues = array("TOTAL ", "", "", $total_fee,  $no_of_student,
			$S10, $S11, $S12, $E1, $E2, $E3, $E4, $E5, $E6, $E7, $E8, $E9, $SE);
	
	DisplayRow($bold, $colValues);
	echo '</table><br/>';
}

function StudentList($enrollmentList) {
	global $boldCenter, $bold;
	echo '<table border="1" rules="all">
			<tr><td colspan="3" style="text-align:center;font-weight:bold;font-size:18px">Student List</td></tr>
			<tr '. $boldCenter .'><th>Student Name</th><th>Father Name / Mother Name</th></tr>';
	foreach ($enrollmentList as $key_name => $key_value) {
		$fmname = $key_value["father_name"]. ' / '. $key_value["mother_name"];
		echo '<tr><td align="left">'. $key_value["first_name"].' '.$key_value["middle_name"].' '.$key_value["last_name"].'</td>';
		echo '<td align="left">'.$fmname.'</td></tr>';
	}
}

function EnrollmentList($enrollmentList) {
	global $boldCenter, $bold;
	echo '<table border="1" rules="all">
			<tr><td colspan="8" style="text-align:center;font-weight:bold;font-size:18px">Enrollment Full Details</td></tr>
			<tr '. $boldCenter .'>
				<th>Father Name (Cell)</th><th>Mother Name (Cell)</th><th>Address</th>
				<th>Home Phone</th><th>Total Fee</th><th>Financial Aid</th><th>Languages</th></tr>
			<tr>
				<th>Emergency Contact1 (Relation)</th><th>Phone</th><th>Emergency Contact2 (Relation)</th>
				<th>Phone</th><th>Physician Name (Phone)</th><th>Address</th><th>Hospital</th><th>Comments</th></tr>
			<tr>
				<th></th><th>Student Name</th><th>DOB - Age (Gender)</th><th>Allergies Details</th>
				<th>Medical Conditions</th><th>Comments</th></tr>';
	
	$first=false;
	$fmname ='';
	foreach ($enrollmentList as $key_name => $key_value) {
		$fname = $key_value["father_name"]. ' ('. $key_value["father_cell"] .')';
		$mname = $key_value["mother_name"]. ' ('. $key_value["mother_cell"] .')';
		if (($fname.''.$mname) != $fmname) {
			$fmname = $fname.''.$mname;
			if ($first) echo '<tr><td colspan="8"></td></tr>';
			$first=true;
			$addressLine =  $key_value["address"].', '. $key_value["city"].' - '. $key_value["zipcode"];
			echo '<tr>
					<td>'.$fname.'</td><td>'.$mname.'</td><td>'. $addressLine .'</td>
					<td>'. $key_value["phone"] .'</td>
					<td>'. $key_value["total_fee"] .'</td>
					<td>'. $key_value["financial_aid"] .'</td>
					<td>'.$key_value["language_primary"].', '.$key_value["language_other"].'</td></tr>';
			echo '<tr><td>'.$key_value["emergency_contact1"].' ('.$key_value["emergency_relation1"].')</td>
					<td>'.$key_value["emergency_phone1"].'</td>
					<td>'.$key_value["emergency_contact1"].' ('.$key_value["emergency_relation2"].')</td>
					<td>'.$key_value["emergency_phone2"].'</td>
					<td>'.$key_value["physician_name"]. ' ('. $key_value["physician_phone"] .')</td>
					<td>'.$key_value["physician_address"].'</td>
					<td>'.$key_value["emergency_hospital"].'</td>
					<td>'.$key_value["comments"].'</td></tr>';
		}
		echo '<tr><td></td>	
				<td>'.$key_value["first_name"].' '.$key_value["middle_name"].' '.$key_value["last_name"].'</td>
				<td>'.$key_value["dob"].' -'. $key_value["age"]. ' ('. $key_value["gender"] .')</td>
				<td>'.$key_value["allergies_details"].'</td>
				<td>'.$key_value["medical_conditions"].'</td>
				<td>'.$key_value["scomments"].'</td></tr>';
	}
}
?>