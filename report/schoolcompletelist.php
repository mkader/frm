<?php
require_once('include.php');
require_once('../excel/PHPExcel.php');

//if (Sessions::isValidSession() && isset($_GET['year']) ) {
	$clsDB = new DB();
	$clsReports = new Reports($clsDB);
	$clsEvents = new Events($clsDB);
	$objPHPExcel = new PHPExcel();

	$workSheetNum = 0;
	$year  = '2016_2017';//$_GET['year'];
	//$clsReports->getCompleteSum($year)
	$objWorksheet = summaryWorkSheet($objPHPExcel, $workSheetNum++,
			'', $year.' Summary');

	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'. $year .'_MCCSchool.xls"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');

//}

function summaryWorkSheet($objPHPExcel, $workSheetNum, $paymentsList, $title) {
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex($workSheetNum);
	$objWorksheet = $objPHPExcel->getActiveSheet();
	
	
	$row=1;
	$objWorksheet->setCellValue('A'. $row++, "Weekend School 2016 -2017");
	
	$objWorksheet->setCellValue('A'. $row, "2016");
	$objWorksheet->setCellValue('B'. $row++, "2017");
	
	$objWorksheet->setCellValue('B'. $row, "Oct");
	$objWorksheet->setCellValue('C'. $row, "Nov");	
	$objWorksheet->setCellValue('D'. $row, "Dec");	
	$objWorksheet->setCellValue('E'. $row, "Jan");	
	$objWorksheet->setCellValue('F'. $row, "Feb");	
	$objWorksheet->setCellValue('G'. $row, "Mar");	
	$objWorksheet->setCellValue('H'. $row, "Apr");	
	$objWorksheet->setCellValue('I'. $row, "May");	
	$objWorksheet->setCellValue('J'. $row, "June");	
	$objWorksheet->setCellValue('K'. $row, "Jul");	
	$objWorksheet->setCellValue('L'. $row, "Aug");	
	$objWorksheet->setCellValue('M'. $row, "Sep");	
	$objWorksheet->setCellValue('N'. $row++, "Total");
	
	/*Student Fee	1												1
	Donation	2												2
	Income	3												3
		
	Salary	4												4
	Cleaning	5												5
	Expenses	9												9
		
	Total	-6												-6
	
	
	/$objWorksheet->getColumnDimension('A')->setWidth(30);
	$objWorksheet->getColumnDimension('B')->setWidth(30);
	$objWorksheet->getColumnDimension('C')->setWidth(25);
	$objWorksheet->getColumnDimension('D')->setWidth(15);
	$objWorksheet->getColumnDimension('E')->setWidth(15);
	$objWorksheet->getColumnDimension('F')->setWidth(18);
	$objWorksheet->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objWorksheet->freezePane('A2');

	$objWorksheet->getStyle('A1:F1')->applyFromArray(
			array(
					'font'    => array('bold' => true),
					'borders' => array(
							'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
					'fill' => array(
							'type'=> PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array('argb' => '#FFFF00')
					)
			)
	);

	$row = 2;
	foreach ($paymentsList as $key_name => $key_value) {
		$name =$key_value["name"].
			(strlen($key_value["company_name"])>0?' ('. $key_value["company_name"].')':'');
		$address =$key_value["address1"].
			(strlen($key_value["address2"])>0?', '. $key_value["address2"]:'');
		$csz= $key_value["city"].
			(strlen($key_value["state"])>0?', '. $key_value["state"]:'').
			(strlen($key_value["zipcode"])>0?' '. $key_value["zipcode"]:'');
		$objWorksheet->setCellValue('A' . $row, $name)
					 ->setCellValue('B' . $row, $address)
					 ->setCellValue('C' . $row, $csz)
					 ->setCellValue('D' . $row, $key_value["phone"])
					 ->setCellValue('E' . $row, $key_value["email"])
					 ->setCellValue('F' . $row, $key_value["amount"]);
		$row++;
	}

	$objWorksheet->setCellValue('E' . ($row+1), 'TOTAL ');
	$objWorksheet->getStyle('E' . ($row+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objWorksheet->setCellValue('F' . ($row+1), '=SUM(F2:F'.($row-1).')');
	$objWorksheet->getStyle('E' . ($row+1).':F' . ($row+1))->applyFromArray(
			array(
					'font'    => array('bold' => true),
					'borders' => array(
							'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
					'fill' => array(
							'type'=> PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array('argb' => '#FFFF00')
					)
			)
	);

	$objWorksheet->getStyle('F2:'. $objWorksheet->getHighestColumn() . $objWorksheet->getHighestRow())
				 ->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

	$styleArray = array(
			'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
			)
	);

	$objWorksheet->getStyle('A1:' . $objWorksheet->getHighestColumn() . $objWorksheet->getHighestRow())
	->applyFromArray($styleArray);

	$objWorksheet->setTitle($title);*/

	return $objWorksheet;
}

function eventSumWorkSheet($objPHPExcel, $workSheetNum, $paymentsList, $title) {
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex($workSheetNum);
	$objWorksheet = $objPHPExcel->getActiveSheet();
	$objWorksheet->setCellValue('A1', "NAME")
	->setCellValue('B1', "STREET ADDRESS")
	->setCellValue('C1', "CITY, ZIP")
	->setCellValue('D1', "PHONE")
	->setCellValue('E1', "EMAIL")
	->setCellValue('F1', "PLEDGED AMOUNT")
	->setCellValue('G1', "PAID")
	->setCellValue('H1', "BALANCE");

	$objWorksheet->getColumnDimension('A')->setWidth(30);
	$objWorksheet->getColumnDimension('B')->setWidth(30);
	$objWorksheet->getColumnDimension('C')->setWidth(25);
	$objWorksheet->getColumnDimension('D')->setWidth(15);
	$objWorksheet->getColumnDimension('E')->setWidth(15);
	$objWorksheet->getColumnDimension('F')->setWidth(18);
	$objWorksheet->getColumnDimension('G')->setWidth(18);
	$objWorksheet->getColumnDimension('H')->setWidth(18);
	$objWorksheet->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objWorksheet->freezePane('A2');

	$objWorksheet->getStyle('A1:H1')->applyFromArray(
			array(
					'font'    => array('bold' => true),
					'borders' => array(
							'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
					'fill' => array(
							'type'=> PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array('argb' => '#FFFF00')
					)
			)
	);

	$row = 2;
	foreach ($paymentsList as $key_name => $key_value) {
		$name =$key_value["name"].
		(strlen($key_value["company_name"])>0?' ('. $key_value["company_name"].')':'');
		$address =$key_value["address1"].
		(strlen($key_value["address2"])>0?', '. $key_value["address2"]:'');
		$csz= $key_value["city"].
		(strlen($key_value["state"])>0?', '. $key_value["state"]:'').
		(strlen($key_value["zipcode"])>0?' '. $key_value["zipcode"]:'');
		$objWorksheet->setCellValue('A' . $row, $name)
		->setCellValue('B' . $row, $address)
		->setCellValue('C' . $row, $csz)
		->setCellValue('D' . $row, $key_value["phone"])
		->setCellValue('E' . $row, $key_value["email"])
		->setCellValue('F' . $row, $key_value["pledgedamount"])
		->setCellValue('G' . $row, $key_value["paid"])
		->setCellValue('H' . $row, '=F'.$row.'-G'.$row);
		$row++;
	}

	$objWorksheet->setCellValue('E' . ($row+1), 'TOTAL ');
	$objWorksheet->getStyle('E' . ($row+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objWorksheet->setCellValue('F' . ($row+1), '=SUM(F2:F'.($row-1).')');
	$objWorksheet->setCellValue('G' . ($row+1), '=SUM(G2:G'.($row-1).')');
	$objWorksheet->setCellValue('H' . ($row+1), '=SUM(H2:H'.($row-1).')');
	$objWorksheet->getStyle('E' . ($row+1).':H' . ($row+1))->applyFromArray(
			array(
					'font'    => array('bold' => true),
					'borders' => array(
							'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
					'fill' => array(
							'type'=> PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array('argb' => '#FFFF00')
					)
			)
	);

	$objWorksheet->getStyle('F2:'. $objWorksheet->getHighestColumn() . $objWorksheet->getHighestRow())
	->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

	$styleArray = array(
			'borders' => array(
					'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
			)
	);

	$objWorksheet->getStyle('A1:' . $objWorksheet->getHighestColumn() . $objWorksheet->getHighestRow())
	->applyFromArray($styleArray);

	$objWorksheet->setTitle($title);

	return $objWorksheet;
}

?>