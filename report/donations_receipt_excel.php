<?php
require_once('include.php');
require_once('../excel/PHPExcel.php');

if (Sessions::isValidSession() && isset($_GET['year']) ) {
	$clsDB = new DB();
	$clsReports = new Reports($clsDB);
	$clsEvents = new Events($clsDB);
	$objPHPExcel = new PHPExcel();

	$workSheetNum = 0;
	$year  = $_GET['year'];//@intval(date('Y'));
	$objWorksheet = paymentSumWorkSheet($objPHPExcel, $workSheetNum,
			$clsReports->getCompleteSum($year), 'Summary');

	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'. $year .'_DonationsReceipt.xls"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');

	//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	//$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
}


function paymentSumWorkSheet($objPHPExcel, $workSheetNum, $paymentsList, $title) {
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex($workSheetNum);
	$objWorksheet = $objPHPExcel->getActiveSheet();
	$objWorksheet->setCellValue('A1', "Name")
				 ->setCellValue('B1', "Company Name")
				 ->setCellValue('C1', "Address")
				 ->setCellValue('D1', "City")
				 ->setCellValue('E1', "State")
				 ->setCellValue('F1', "Zip")
				 ->setCellValue('G1', "Paid to Date")
				 ->setCellValue('H1', "Email");

	$objWorksheet->getColumnDimension('A')->setWidth(30);
	$objWorksheet->getColumnDimension('B')->setWidth(20);
	$objWorksheet->getColumnDimension('C')->setWidth(25);
	$objWorksheet->getColumnDimension('D')->setWidth(15);
	$objWorksheet->getColumnDimension('E')->setWidth(9);
	$objWorksheet->getColumnDimension('F')->setWidth(9);
	$objWorksheet->getColumnDimension('G')->setWidth(12);
	$objWorksheet->getColumnDimension('H')->setWidth(25);
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
		$name =$key_value["name"];
		$company_name =$key_value["company_name"];
		$address =$key_value["address1"].
		(strlen($key_value["address2"])>0?', '. $key_value["address2"]:'');
		$city= $key_value["city"];
		$state= $key_value["state"];
		$zip= $key_value["zipcode"];
		$paid= $key_value["amount"];
		$email= $key_value["email"];
		$objWorksheet->setCellValue('A' . $row, $name)
		->setCellValue('B' . $row, $company_name)
		->setCellValue('C' . $row, $address)
		->setCellValue('D' . $row, $city)
		->setCellValue('E' . $row, $state)
		->setCellValue('F' . $row, $zip)
		->setCellValue('G' . $row, $paid)
		->setCellValue('H' . $row, $email);
		$row++;
	}

	$objWorksheet->getStyle('G2:'. $objWorksheet->getHighestColumn() . $objWorksheet->getHighestRow())
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