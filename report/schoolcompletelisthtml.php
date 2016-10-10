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

	$year  = $_GET['year'];
	$schoolYearFeeSum = $clsReports->getSchoolYearFeeSum($year);
	Summary($schoolYearFeeSum, $year);
	
	/*$parentFeeSum = $clsReports->getParentFeeSum($year);
	EnrollmentPaymentSummary($parentFeeSum);*/
}

function EnrollmentPaymentSummary($parentFeeSum) {
	/*$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex($workSheetNum);
	$objWorksheet = $objPHPExcel->getActiveSheet();

	$center = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
	$bold = array('font' => array('bold' => true));
	$boldCenter =$center+$bold;

	$row=1;
	$objWorksheet->setCellValue('A'. $row, "Enrollment Fee Summary Report")
				->getStyle('A'. $row.':R'. $row)->applyFromArray($boldCenter);
	$objPHPExcel->getActiveSheet()->mergeCells('A'. $row.':R'. $row);
	$objWorksheet->getColumnDimension('A')->setWidth(35);
	$objWorksheet->getColumnDimension('B')->setWidth(35);
	$objWorksheet->getColumnDimension('C')->setWidth(7);
	$objWorksheet->getColumnDimension('D')->setWidth(6);
	$objWorksheet->getColumnDimension('E')->setWidth(9);
	
	$objWorksheet->getColumnDimension('F')->setWidth(7);
	$objWorksheet->getColumnDimension('G')->setWidth(7);
	$objWorksheet->getColumnDimension('H')->setWidth(7);
	$objWorksheet->getColumnDimension('I')->setWidth(7);
	$objWorksheet->getColumnDimension('J')->setWidth(7);
	$objWorksheet->getColumnDimension('K')->setWidth(7);
	$objWorksheet->getColumnDimension('L')->setWidth(7);
	$objWorksheet->getColumnDimension('M')->setWidth(7);
	$objWorksheet->getColumnDimension('N')->setWidth(7);
	$objWorksheet->getColumnDimension('O')->setWidth(7);
	$objWorksheet->getColumnDimension('P')->setWidth(7);
	$objWorksheet->getColumnDimension('Q')->setWidth(7);
	$row++;
	$colValues= array("Parent Name", "Address", "Aid", "Fees",  "Students", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Total");
	DisplayRow($objWorksheet, $row, $boldCenter, $colValues, true);
	$objWorksheet->freezePane('A3');
	
	foreach ($parentFeeSum as $key_name => $key_value) {
		$row++;
		$colValues= array($key_value["parent_name"], $key_value["address_line"],  
				$key_value["financial_aid"], $key_value["total_fee"],  $key_value["no_of_student"],  
				$key_value["S10"], $key_value["S11"], $key_value["S12"], $key_value["E1"], $key_value["E2"],
				$key_value["E3"], $key_value["E4"], $key_value["E5"], $key_value["E6"], $key_value["E7"],
				$key_value["E8"], $key_value["E9"], $key_value["SE"]);
		DisplayRow($objWorksheet, $row, array(), $colValues, false);
	}
	
	$row++;
	$objPHPExcel->getActiveSheet()->mergeCells('A'. $row.':C'. $row);
	$objWorksheet->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objWorksheet->setCellValue('E' . $row, '=SUM(E3:E'.($row-1).')');
	$colValues= array("TOTAL ","","",'=SUM(D3:D'.($row-1).')','=SUM(E3:E'.($row-1).')',
					'=SUM(F3:F'.($row-1).')','=SUM(G3:G'.($row-1).')','=SUM(H3:H'.($row-1).')',
					'=SUM(I3:I'.($row-1).')','=SUM(J3:J'.($row-1).')','=SUM(K3:K'.($row-1).')',
					'=SUM(L3:L'.($row-1).')','=SUM(M3:M'.($row-1).')','=SUM(N3:N'.($row-1).')',
					'=SUM(O3:O'.($row-1).')','=SUM(P3:P'.($row-1).')','=SUM(Q3:Q'.($row-1).')',
					'=SUM(R3:R'.($row-1).')');
	DisplayRow($objWorksheet, $row, $bold, $colValues, true);

	$objWorksheet->getStyle('A1:N1')->applyFromArray(
			array(
					'borders' => array(
							'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
					'fill' => array(
							'type'=> PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array('argb' => '#FFFF00')
					)
			)
	);

	$styleArray = array(
			'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
			)
	);

	$objWorksheet->getStyle('A1:' . $objWorksheet->getHighestColumn() . $objWorksheet->getHighestRow())
	->applyFromArray($styleArray);

	$objWorksheet->setTitle("Enrollment Fee Summary");

	return $objWorksheet;*/
}

function DisplayRow($values) {
	$html='';
	//$objWorksheet->getStyle('A'. $row.':N'. $row)->applyFromArray($style);
	$html.='<td>'.$values[0].'</td>';
	$html.='<td align="right">'.$values[1].'</td>';
	$html.='<td align="right">'.$values[2].'</td>';
	$html.='<td align="right">'.$values[3].'</td>';
	$html.='<td align="right">'.$values[4].'</td>';
	$html.='<td align="right">'.$values[5].'</td>';
	$html.='<td align="right">'.$values[6].'</td>';
	$html.='<td align="right">'.$values[7].'</td>';
	$html.='<td align="right">'.$values[8].'</td>';
	$html.='<td align="right">'.$values[9].'</td>';
	$html.='<td align="right">'.$values[10].'</td>';
	$html.='<td align="right">'.$values[11].'</td>';
	$html.='<td align="right">'.$values[12].'</td>';
	$html.='<td align="right">'.$values[13].'</td>';
	if (sizeof($values)>14) {
		$html.='<td align="right">'.$values[14].'</td>';
		$html.='<td align="right">'.$values[15].'</td>';
		$html.='<td align="right">'.$values[16].'</td>';
		$html.='<td align="right">'.$values[17].'</td>';
	   // ->getStyle('O'. $row.':R'. $row)->applyFromArray($style);
	}
	echo '<tr>'.$html.'</tr>';
}

function Summary($schoolYearFeeSum, $year) {
	echo '<table border="1" rules="all">
			<tr><td colspan="14" style="text-align:center;font-weight:bold;font-size:18px">Weekend School '.$year.' - ' .($year+1) .' Summary</td></tr>
			<tr style="background-color:#FFFF00;font-weight:bold">
				<th></th><th colspan="3">'.$year.'</th><th colspan="9">'.($year+1).'</th><th></th></tr>';
	$colValues= array("","Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Total");
	DisplayRow($colValues);

	$iValue0 =$schoolYearFeeSum[0];
	$colValues= array("Fee", $iValue0["S10"], $iValue0["S11"], $iValue0["S12"], $iValue0["E1"], $iValue0["E2"], 
					  $iValue0["E3"], $iValue0["E4"], $iValue0["E5"], $iValue0["E6"], $iValue0["E7"], 
			          $iValue0["E8"], $iValue0["E9"], $iValue0["SE"]);
	DisplayRow($colValues);
	
	$iValue1 =$schoolYearFeeSum[1];
	$colValues= array("Donation", $iValue1["S10"], $iValue1["S11"], $iValue1["S12"], $iValue1["E1"], $iValue1["E2"],
			$iValue1["E3"], $iValue1["E4"], $iValue1["E5"], $iValue1["E6"], $iValue1["E7"],
			$iValue1["E8"], $iValue1["E9"], $iValue1["SE"]);
	DisplayRow($colValues);
	
	$colValues= array("Income", $iValue0["S10"]+$iValue1["S10"], $iValue0["S11"]+$iValue1["S11"], 
					  $iValue0["S12"]+$iValue1["S12"], $iValue0["E1"]+$iValue1["E1"], $iValue0["E2"]+$iValue1["E1"], 
					  $iValue0["E3"]+$iValue1["E3"], $iValue0["E4"]+$iValue1["E4"], 
			          $iValue0["E5"]+$iValue1["E5"], $iValue0["E6"]+$iValue1["E6"], 
			          $iValue0["E7"]+$iValue1["E7"], $iValue0["E8"]+$iValue1["E8"], 
					    $iValue0["E9"]+$iValue1["E9"], $iValue0["SE"]+$iValue1["SE"]);
	DisplayRow($colValues);
	
	/*$row++;
	$objPHPExcel->getActiveSheet()->mergeCells('B'. $row.':D'. $row);
	$objPHPExcel->getActiveSheet()->mergeCells('E'. $row.':M'. $row);
	$row++;
	$sumExpense=$row;
	$objWorksheet->setCellValue('A'. $row, "Salary");
	$row++;
	$objWorksheet->setCellValue('A'. $row, "Cleaning");
	$row++;
	$objWorksheet->setCellValue('A'. $row, "Supplies");
	$row++;
	$colValues= array("Expenses", '=SUM(B'.$sumExpense.':B'.($row-1).')', '=SUM(C'.$sumExpense.':C'.($row-1).')',
					'=SUM(D'.$sumExpense.':D'.($row-1).')', '=SUM(E'.$sumExpense.':E'.($row-1).')',
					'=SUM(F'.$sumExpense.':F'.($row-1).')', '=SUM(G'.$sumExpense.':G'.($row-1).')',
					'=SUM(H'.$sumExpense.':H'.($row-1).')', '=SUM(I'.$sumExpense.':I'.($row-1).')',
					'=SUM(J'.$sumExpense.':J'.($row-1).')', '=SUM(K'.$sumExpense.':K'.($row-1).')',
					'=SUM(L'.$sumExpense.':L'.($row-1).')', '=SUM(M'.$sumExpense.':M'.($row-1).')',
					'=SUM(N'.$sumExpense.':N'.($row-1).')');
	DisplayRow($objWorksheet, $row, $bold, $colValues, true);
	$expenseRow = $row;
	
	$row++;
	$objPHPExcel->getActiveSheet()->mergeCells('B'. $row.':D'. $row);
	$objPHPExcel->getActiveSheet()->mergeCells('E'. $row.':M'. $row);
	$row++;
	$colValues= array("Total", '=B'.$incomeRow.'-B'.$expenseRow, '=C'.$incomeRow.'-C'.$expenseRow,
			'=D'.$incomeRow.'-D'.$expenseRow, '=E'.$incomeRow.'-E'.$expenseRow,
			'=F'.$incomeRow.'-F'.$expenseRow, '=G'.$incomeRow.'-G'.$expenseRow,
			'=H'.$incomeRow.'-H'.$expenseRow, '=I'.$incomeRow.'-I'.$expenseRow,
			'=J'.$incomeRow.'-J'.$expenseRow, '=K'.$incomeRow.'-K'.$expenseRow,
			'=L'.$incomeRow.'-L'.$expenseRow, '=M'.$incomeRow.'-M'.$expenseRow,
			'=N'.$incomeRow.'-N'.$expenseRow);
	DisplayRow($objWorksheet, $row, $bold, $colValues, true);
	
	$objWorksheet->getStyle('A1:N1')->applyFromArray(
			array(
					'borders' => array(
							'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
					'fill' => array(
							'type'=> PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array('argb' => '#FFFF00')
					)
			)
	);
	
	$styleArray = array(
			'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
			)
	);
	
	$objWorksheet->getStyle('A1:' . $objWorksheet->getHighestColumn() . $objWorksheet->getHighestRow())
				 ->applyFromArray($styleArray);
	
	$objWorksheet->setTitle("Summary");

	return $objWorksheet;*/
}

?>