<?php
require_once('include.php');
require_once('../word/PHPWord.php');

if (Sessions::isValidSession() && isset($_GET['year']) ) {
	$clsDB = new DB();
	$clsReports = new Reports($clsDB);
	$objPHPWord = new PHPWord();

	$year  = $_GET['year'];//@intval(date('Y'));
	$paymentsList = $clsReports->getCompleteSum($year);

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

		$document = $objPHPWord->loadTemplate('../doc/Donations_Receipt_Template_word.docx');

		$document->setValue('DBName', $name);
		$document->setValue('DBAddress', $address);
		$document->setValue('DBCity', $city);
		$document->setValue('DBState', $state);
		$document->setValue('DBZip', $zip);
		$document->setValue('DBPaid', $paid);
		if (strlen($email)>0)
			$document->save('../doc/'.$year.'/donations/'.str_replace('/',' ',$name).'_'.$email.'.docx');
		else
			$document->save('../doc/'.$year.'/donations/'.str_replace('/',' ',$name).'.docx');
	}
}
?>