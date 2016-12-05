<?php
require_once('lib/include.php');

$db = new DB();
$enrollment = new Enrollments($db);

function enrollmentList() {
	global $enrollment;
	$response = array();

	try {
		$responseData = $enrollment->getEnrollmentList();
		Logger::log('Enrollment List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

function iudEnrollment($iud, $action_type, $action_type_done) {
    global $enrollment;
    $response = array();
    $id =  @intval($_POST['id']);
    $father_name = '';
    $father_cell = '';
    $father_email = '';
    $mother_name = '';
    $mother_cell =  '';
    $mother_email =  '';
    $address =  '';
    $city =  '';
    $zipcode =  '';
    $state =  '';
    $phone =  '';
    $language_primary =  '';
    $language_other =  '';
    $emergency_contact1 =  '';
    $emergency_phone1 =  '';
    $emergency_relation1 =  '';
    $emergency_contact2 =  '';
    $emergency_phone2 =  '';
    $emergency_relation2 =  '';
    $comments ='';
    $physician_name='';
    $physician_phone='';
    $physician_address='';
    $emergency_hospital ='';
    $medical_conditions='';
    $financial_aid = 0;
    $total_fee = 0;

	if ($iud!='d') {
		$father_name = $_POST['father_name'];
		$father_cell = $_POST['father_cell'];
		$father_email = $_POST['father_email'];
		$mother_name = $_POST['mother_name'];
		$mother_cell =  $_POST['mother_cell'];
		$mother_email =  $_POST['mother_email'];
		$address =  $_POST['address'];
		$city =  $_POST['city'];
		$zipcode =  $_POST['zipcode'];
		//$state =  $_POST['state'];
		$state = "WI";
		$phone =  $_POST['phone'];
		$language_primary =  $_POST['language_primary'];
		$language_other =  $_POST['language_other'];
		$emergency_contact1 =  $_POST['emergency_contact1'];
		$emergency_phone1 =  $_POST['emergency_phone1'];
		$emergency_relation1 =  $_POST['emergency_relation1'];
		$emergency_contact2 =  $_POST['emergency_contact2'];
		$emergency_phone2 =  $_POST['emergency_phone2'];
		$emergency_relation2 =  $_POST['emergency_relation2'];
		$comments =  $_POST['comments'];
		$physician_name =  $_POST['physician_name'];
		$physician_phone =  $_POST['physician_phone'];
		$physician_address =  $_POST['physician_address'];
		$emergency_hospital =$_POST['emergency_hospital'];
		$total_fee =$_POST['total_fee'];
		$financial_aid =$_POST['financial_aid'];
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid Enrollment ID specified with the request';
		return $response;
	}

	try {
		$id = $enrollment->iudEnrollment($iud, $id, $father_name, $father_cell, $father_email,
				$mother_name, $mother_cell, $mother_email, $address, $city, $zipcode, $state,
				$phone, $language_primary, $language_other, $emergency_contact1, $emergency_phone1,
				$emergency_relation1, $emergency_contact2, $emergency_phone2, $emergency_relation2, $comments,
    			$physician_name, $physician_phone, $physician_address, $emergency_hospital, $total_fee,
    			$financial_aid);
		Logger::log($action_type. ' enrollment complete');
		if ($id > 0) {
			$response['success'] = 1;
			$response['id'] = $id;
			$response['message'] = 'The enrollment was successfully '. $action_type_done;
		} else {
			$response['error'] = 1;
			$response['message'] = 'Could not complete '. $action_type .' enrollment request. Please check the details you entered and try again.';
		}
    } catch (DBException $e) {
        $response['error'] = 1;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

// Request Handler
$response = array();
if (Sessions::isValidSession()) {
	if (isset($_POST['action'])) {
	    $action = $_POST['action'];
	    if ($action == 'iud') {
	    	$action_type ="insert";
	    	$action_type_done ="inserted";
	    	$iud = $_POST['iud'];
	    	if ($iud=='u') {
	    		$action_type ="update";
	    		$action_type_done ="updated";
	    	} else if ($iud=='d') {
	    		$action_type ="delete";
	    		$action_type_done ="deleted";
	    	}
	        Logger::log('Processing '. $action_type .' enrollment request...');
	        $response = iudEnrollment($iud, $action_type, $action_type_done);
	    }
	    Logger::log(print_r($response, true));
	} else if (isset($_GET['action'])) {
	    $action = $_GET['action'];
	   	if ($action == 'enrollmentlist') {
	        Logger::log('Processing list of enrollment...');
	        $response = enrollmentList();
	    }
	} else {
	    $response['error'] = 1;
	    $response['message'] = 'There was no request action specified.';
	    Logger::log(print_r($response, true));
	}
}
header('Content-type: text/plain');
echo json_encode($response);

?>

