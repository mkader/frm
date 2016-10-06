<?php
require_once('include.php');
require_once('../word/PHPWord.php');

if (Sessions::isValidSession() && isset($_GET['event_id']) ) {
	$clsDB = new DB();
	$clsReports = new Reports($clsDB);
	$objPHPWord = new PHPWord();
	$year = date("Y");
	$pledge_type_id  = $_GET['pledge_type_id'];
	$event_id  = $_GET['event_id'];
	$list = $clsReports->getReminderList($event_id);

	foreach ($list as $key_name => $key_value) {

		$name =$key_value["name"];
		$company_name =$key_value["company_name"];
		$address =$key_value["address1"].
				 (strlen($key_value["address2"])>0?', '. $key_value["address2"]:'');
		$city= $key_value["city"];
		$state= $key_value["state"];
		$zip= $key_value["zipcode"];
		$pledged_amount= $key_value["pledgedamount"];
		$paid= $key_value["paid"];
		$balance =  ($key_value["pledgedamount"]-$key_value["paid"]);
		$event= $key_value["event_title"];
		$email= $key_value["email"];

		if ($pledge_type_id == 2)
			$document = $objPHPWord->loadTemplate('../template/New Masjid_PR_Template_Word.docx');
		else
			$document = $objPHPWord->loadTemplate('../template/Operation_PR_Template_Word.docx');

		$document->setValue('DBName', $name);
		$document->setValue('DBAddress', $address);
		$document->setValue('DBCity', $city);
		$document->setValue('DBState', $state);
		$document->setValue('DBZip', $zip);
		$document->setValue('DBPaid', $paid);
		$document->setValue('DBPledged', $pledged_amount);
		$document->setValue('DBBalance', $balance);
		$document->setValue('DBEvent', $event);

		$path = '../doc/'.$year.'/remainders/'. $event_id.'_';
		if ($pledge_type_id == 2) $path.='masjid/'; else $path.='operation/';

		//echo $path.' - ' .file_exists($path);
		if(!file_exists($path)) {
			mkdir($path.'mail/', 0777, true);
			mkdir($path.'email/', 0777, true);
		}

		if (strlen($email)>0)
			$document->save($path.'email/'.str_replace('/',' ',$name).'_'.$email.'.docx');
		else
			$document->save($path.'mail/'.str_replace('/',' ',$name).'.docx');

	}
}
?>
