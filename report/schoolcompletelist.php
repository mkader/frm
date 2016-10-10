<?php
require_once('include.php');
require_once('../excel/PHPExcel.php');

if (Sessions::isValidSession() && isset($_GET['year']) ) {
	$clsDB = new DB();
	$clsReports = new Reports($clsDB);
	$objPHPExcel = new PHPExcel();

	$workSheetNum = 0;
	$year  = $_GET['year'];
	$schoolYearFeeSum = $clsReports->getSchoolYearFeeSum($year);
	$objWorksheet = Summary($objPHPExcel, $workSheetNum++,
		$schoolYearFeeSum, $year);
	
	$parentFeeSum = $clsReports->getParentFeeSum($year);
	$objWorksheet = EnrollmentPaymentSummary($objPHPExcel, $workSheetNum++, $parentFeeSum);
	
	$notPaidThisMonth = $clsReports->getNotPaidThisMonth($year, date('m'));
	$objWorksheet = NotPaidList($objPHPExcel, $workSheetNum++, $notPaidThisMonth);
	
	$enrollmentList = $clsReports->getEnrollmentList( "concat(first_name,' ', last_name) asc");
	$objWorksheet = StudentList($objPHPExcel, $workSheetNum++, $enrollmentList);
	
	$enrollmentList = $clsReports->getEnrollmentList();
	$objWorksheet = EnrollmentList($objPHPExcel, $workSheetNum++, $enrollmentList);
	
	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'. $year.' - '. ($year+1) .'_MCCSchool.xls"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');

}

function NotPaidList($objPHPExcel, $workSheetNum, $parentFeeSum) {
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex($workSheetNum);
	$objWorksheet = $objPHPExcel->getActiveSheet();

	$center = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
	$bold = array('font' => array('bold' => true));
	$boldCenter =$center+$bold;

	$row=1;
	$objWorksheet->setCellValue('A'. $row, "This Month Not Paid Report")
	->getStyle('A'. $row.':I'. $row)->applyFromArray($boldCenter);
	$objPHPExcel->getActiveSheet()->mergeCells('A'. $row.':I'. $row);
	$objWorksheet->getColumnDimension('A')->setWidth(25);
	$objWorksheet->getColumnDimension('B')->setWidth(15);
	$objWorksheet->getColumnDimension('C')->setWidth(25);
	$objWorksheet->getColumnDimension('D')->setWidth(15);
	$objWorksheet->getColumnDimension('E')->setWidth(35);
	$objWorksheet->getColumnDimension('F')->setWidth(15);
	$objWorksheet->getColumnDimension('G')->setWidth(7);
	$objWorksheet->getColumnDimension('H')->setWidth(7);
	$objWorksheet->getColumnDimension('I')->setWidth(15);
	$row++;
	$colValues= array("Father Name", "Father Cell", "Mother Name" , "Moterh Cell" , "Address", "Phone", "Aid", "Fees",  "No of Students");
	DisplayRow($objWorksheet, $row, $boldCenter, $colValues, true);
	$objWorksheet->freezePane('A3');

	foreach ($parentFeeSum as $key_name => $key_value) {
		$row++;
		$colValues= array($key_value["father_name"], $key_value["father_cell"],
				$key_value["mother_name"], $key_value["mother_cell"],  
				$key_value["address_line"], $key_value["phone"], 
				$key_value["financial_aid"], $key_value["total_fee"], $key_value["no_of_student"]);
		DisplayRow($objWorksheet, $row, array(), $colValues, false);
	}
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

	$objWorksheet->setTitle("Not Paid List");

	return $objWorksheet;
}

function EnrollmentPaymentSummary($objPHPExcel, $workSheetNum, $parentFeeSum) {
	$objPHPExcel->createSheet();
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

	return $objWorksheet;
}

function DisplayRow($objWorksheet, $row, $style ,$values, $isTitle) {
	$objWorksheet->getStyle('A'. $row.':N'. $row)->applyFromArray($style);
	$objWorksheet->setCellValue('A'. $row, $values[0])
				 ->setCellValue('B'. $row, $values[1])
				 ->setCellValue('C'. $row, $values[2])	
				 ->setCellValue('D'. $row, $values[3])	
				 ->setCellValue('E'. $row, $values[4])	
				 ->setCellValue('F'. $row, $values[5])
				 ->setCellValue('G'. $row, $values[6])	
				 ->setCellValue('H'. $row, $values[7])	
				 ->setCellValue('I'. $row, $values[8]);
	if (sizeof($values)>9) {	
		$objWorksheet->setCellValue('J'. $row, $values[9])	
					 ->setCellValue('K'. $row, $values[10])	
					 ->setCellValue('L'. $row, $values[11])	
					 ->setCellValue('M'. $row, $values[12])	
					 ->setCellValue('N'. $row, $values[13]);
	}
	if (sizeof($values)>14) {
		 $objWorksheet->setCellValue('O'. $row, $values[14])
		 			  ->setCellValue('P'. $row, $values[15])
		 			  ->setCellValue('Q'. $row, $values[16])
		 			  ->setCellValue('R'. $row, $values[17])
		         	  ->getStyle('O'. $row.':R'. $row)->applyFromArray($style);
	}
}

function Summary($objPHPExcel, $workSheetNum, $schoolYearFeeSum, $year) {
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex($workSheetNum);
	$objWorksheet = $objPHPExcel->getActiveSheet();
	
	$center = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
	$bold = array('font' => array('bold' => true));
	$boldCenter =$center+$bold;
	
	$row=1;
	$objWorksheet->setCellValue('A'. $row, "Weekend School ".$year." - " .($year+1))
				 ->getStyle('A'. $row.':N'. $row)->applyFromArray($boldCenter);
	$objPHPExcel->getActiveSheet()->mergeCells('A'. $row.':N'. $row);
	
	$row++;
	$objWorksheet->setCellValue('B'. $row,  $year)
				 ->getStyle('B'. $row.':D'. $row)->applyFromArray($boldCenter);
	$objPHPExcel->getActiveSheet()->mergeCells('B'. $row.':D'. $row);
	$objWorksheet->setCellValue('E'. $row,  $year+1)
				 ->getStyle('E'. $row.':N'. $row)->applyFromArray($boldCenter);
	$objPHPExcel->getActiveSheet()->mergeCells('E'. $row.':M'. $row);
	
	$row++;
	$colValues= array("","Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Total");
	DisplayRow($objWorksheet, $row, $boldCenter, $colValues, false);

	$row++;
	$sumIncome=$row;
	$key_value =$schoolYearFeeSum[0];
	$colValues= array("Fee", $key_value["S10"], $key_value["S11"], $key_value["S12"], $key_value["E1"], $key_value["E2"], 
					  $key_value["E3"], $key_value["E4"], $key_value["E5"], $key_value["E6"], $key_value["E7"], 
			          $key_value["E8"], $key_value["E9"], $key_value["SE"]);
	DisplayRow($objWorksheet, $row, array(), $colValues, true);
	
	$row++;
	$key_value =$schoolYearFeeSum[1];
	$colValues= array("Donation", $key_value["S10"], $key_value["S11"], $key_value["S12"], $key_value["E1"], $key_value["E2"],
			$key_value["E3"], $key_value["E4"], $key_value["E5"], $key_value["E6"], $key_value["E7"],
			$key_value["E8"], $key_value["E9"], $key_value["SE"]);
	DisplayRow($objWorksheet, $row, array(), $colValues, true);
	
	$row++;
	$colValues= array("Income", '=SUM(B'.$sumIncome.':B'.($row-1).')', '=SUM(C'.$sumIncome.':C'.($row-1).')', 
					  '=SUM(D'.$sumIncome.':D'.($row-1).')', '=SUM(E'.$sumIncome.':E'.($row-1).')', 
					  '=SUM(F'.$sumIncome.':F'.($row-1).')', '=SUM(G'.$sumIncome.':G'.($row-1).')', 
					  '=SUM(H'.$sumIncome.':H'.($row-1).')', '=SUM(I'.$sumIncome.':I'.($row-1).')', 
			          '=SUM(J'.$sumIncome.':J'.($row-1).')', '=SUM(K'.$sumIncome.':K'.($row-1).')', 
			          '=SUM(L'.$sumIncome.':L'.($row-1).')', '=SUM(M'.$sumIncome.':M'.($row-1).')', 
					    '=SUM(N'.$sumIncome.':N'.($row-1).')');
	DisplayRow($objWorksheet, $row, $bold, $colValues, true);
	$incomeRow = $row;
	
	$row++;
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

	return $objWorksheet;
}

function EnrollmentList($objPHPExcel, $workSheetNum, $enrollmentList) {
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex($workSheetNum);
	$objWorksheet = $objPHPExcel->getActiveSheet();
	$objWorksheet->getSheetView()->setZoomScale(90);
	;
	$center = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
	$bold = array('font' => array('bold' => true));
	$boldCenter =$center+$bold;
	
	$row=1;
	$objWorksheet->setCellValue('A'. $row, 'Father Name (Cell)');
	$objWorksheet->setCellValue('B'. $row, 'Mother Name (Cell)');
	$objWorksheet->setCellValue('C'. $row, 'Address');
	$objWorksheet->setCellValue('D'. $row, 'Home Phone');
	$objWorksheet->setCellValue('E'. $row, 'Total Fee');
	$objWorksheet->setCellValue('F'. $row, 'Financial Aid');
	$objWorksheet->setCellValue('G'. $row, 'Languages');
	$row++;
	$objWorksheet->setCellValue('A'. $row, 'Emergency Contact1 (Relation)');
	$objWorksheet->setCellValue('B'. $row, 'Phone');
	$objWorksheet->setCellValue('C'. $row, 'Emergency Contact2 (Relation)');
	$objWorksheet->setCellValue('D'. $row, 'Phone');
	$objWorksheet->setCellValue('E'. $row, 'Physician Name (Phone)');
	$objWorksheet->setCellValue('F'. $row, 'Address');
	$objWorksheet->setCellValue('G'. $row, 'Hospital');
	$objWorksheet->setCellValue('H'. $row, 'Comments');
	$row++;
	$objWorksheet->setCellValue('B'. $row, 'Student Name');
	$objWorksheet->setCellValue('C'. $row, 'DOB - Age (Gender)');
	$objWorksheet->setCellValue('D'. $row, 'Allergies Details');
	$objWorksheet->setCellValue('E'. $row, 'Medical Conditions');
	$objWorksheet->setCellValue('F'. $row, 'Comments');
	$objWorksheet->freezePane('A4');
	
	$objWorksheet->getColumnDimension('A')->setWidth(30);
	$objWorksheet->getColumnDimension('B')->setWidth(30);
	$objWorksheet->getColumnDimension('C')->setWidth(25);
	$objWorksheet->getColumnDimension('D')->setWidth(25);
	$objWorksheet->getColumnDimension('E')->setWidth(25);
	$objWorksheet->getColumnDimension('F')->setWidth(25);
	$objWorksheet->getColumnDimension('G')->setWidth(25);
	$objWorksheet->getColumnDimension('H')->setWidth(25);
	$first=false;
	$row++;
	$fmname ='';
	foreach ($enrollmentList as $key_name => $key_value) {
		$fname = $key_value["father_name"]. ' ('. $key_value["father_cell"] .')';
		$mname = $key_value["mother_name"]. ' ('. $key_value["mother_cell"] .')';
		if (($fname.''.$mname) != $fmname) {
			$fmname = $fname.''.$mname;
			if ($first) {
				$objPHPExcel->getActiveSheet()->mergeCells('A'. $row.':H'. $row);
				$row++;
			}
			$first=true;
			$objWorksheet->setCellValue('A'. $row, $fname);
			$objWorksheet->setCellValue('B'. $row, $mname);
			$addressLine =  $key_value["address"].', '. $key_value["city"].' - '. $key_value["zipcode"];
			$objWorksheet->setCellValue('C'. $row, $addressLine);
			$objWorksheet->setCellValue('D'. $row, $key_value["phone"]);
			$objWorksheet->setCellValue('E'. $row, $key_value["total_fee"]);
			$objWorksheet->setCellValue('F'. $row, $key_value["financial_aid"]);
			$objWorksheet->setCellValue('G'. $row, $key_value["language_primary"].', '.$key_value["language_other"]);
			$row++;
			$objWorksheet->setCellValue('A'. $row, $key_value["emergency_contact1"].' ('.$key_value["emergency_relation1"].')');
			$objWorksheet->setCellValue('B'. $row, $key_value["emergency_phone1"]);
			$objWorksheet->setCellValue('C'. $row, $key_value["emergency_contact1"].' ('.$key_value["emergency_relation2"].')');
			$objWorksheet->setCellValue('D'. $row, $key_value["emergency_phone2"]);
			$objWorksheet->setCellValue('E'. $row, $key_value["physician_name"]. ' ('. $key_value["physician_phone"] .')');
			$objWorksheet->setCellValue('F'. $row, $key_value["physician_address"]);
			$objWorksheet->setCellValue('G'. $row, $key_value["emergency_hospital"]);
			$objWorksheet->setCellValue('H'. $row, $key_value["comments"]);
			$row++;
		}
		$objWorksheet->setCellValue('B'. $row, $key_value["first_name"].' '.$key_value["middle_name"].' '.$key_value["last_name"]);
		$objWorksheet->setCellValue('C'. $row, $key_value["dob"].' -'. $key_value["age"]. ' ('. $key_value["gender"] .')');
		$objWorksheet->setCellValue('D'. $row, $key_value["allergies_details"]);
		$objWorksheet->setCellValue('E'. $row, $key_value["medical_conditions"]);
		$objWorksheet->setCellValue('F'. $row, $key_value["scomments"]);
		$row++;
	}
	
	$borders = array(
			'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
			)
	);
	$borderFill = array(
			$borders,
			'fill' => array(
					'type'=> PHPExcel_Style_Fill::FILL_SOLID,
					'startcolor' => array('argb' => '#FFFF00')
			)
	);
	$objWorksheet->getStyle('A1:H1')->applyFromArray($borderFill);
	$objWorksheet->getStyle('A2:H2')->applyFromArray($borderFill);
	$objWorksheet->getStyle('A3:H3')->applyFromArray($borderFill);
	

	$objWorksheet->getStyle('A1:' . $objWorksheet->getHighestColumn() . $objWorksheet->getHighestRow())
				 ->applyFromArray($borders);

	$objWorksheet->setTitle("Enrollment Full Details");

	return $objWorksheet;
}

function StudentList($objPHPExcel, $workSheetNum, $enrollmentList) {
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex($workSheetNum);
	$objWorksheet = $objPHPExcel->getActiveSheet();
	$objWorksheet->getSheetView()->setZoomScale(90);
	;
	$center = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
	$bold = array('font' => array('bold' => true));
	$boldCenter =$center+$bold;

	$row=1;
	$objWorksheet->setCellValue('A'. $row, 'Student Name');
	$objWorksheet->setCellValue('B'. $row, 'DOB - Age (Gender)');
	$objWorksheet->setCellValue('C'. $row, 'Father Name / Mother Name');
	$objWorksheet->freezePane('A1');

	$objWorksheet->getColumnDimension('A')->setWidth(33);
	$objWorksheet->getColumnDimension('B')->setWidth(20);
	$objWorksheet->getColumnDimension('C')->setWidth(42);
	$row++;
	foreach ($enrollmentList as $key_name => $key_value) {
		$fmname = $key_value["father_name"]. ' / '. $key_value["mother_name"];
		$objWorksheet->setCellValue('A'. $row, $key_value["first_name"].' '.$key_value["middle_name"].' '.$key_value["last_name"]);
		$objWorksheet->setCellValue('B'. $row, $key_value["dob"].' - '. $key_value["age"]. ' ('. $key_value["gender"] .')');
		$objWorksheet->setCellValue('C'. $row, $fmname);
		$row++;
	}

	$borders = array(
			'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
			)
	);
	$borderFill = array(
			$borders,
			'fill' => array(
					'type'=> PHPExcel_Style_Fill::FILL_SOLID,
					'startcolor' => array('argb' => '#FFFF00')
			)
	);
	$objWorksheet->getStyle('A1:C1')->applyFromArray($borderFill);

	$objWorksheet->getStyle('A1:' . $objWorksheet->getHighestColumn() . $objWorksheet->getHighestRow())
	->applyFromArray($borders);

	$objWorksheet->setTitle("Student List");

	return $objWorksheet;
}

?>