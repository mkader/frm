<?php
require_once('lib/include.php');

$db = new DB();
$teacher = new Teachers($db);

function teacherList() {
	global $teacher;
	$response = array();

	try {
		$responseData = $teacher->getTeacherList();
		Logger::log('Teacher List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

function iudTeacher($iud, $action_type, $action_type_done) {
    global $teacher;
    $response = array();
    $id =  @intval($_POST['id']);
    $name = '';
    $address =  '';
    $city =  '';
    $zipcode =  '';
    $state =  '';
    $phone =  '';
    $comments ='';
    $email='';
    $ssn='';
    $join_date = '';
    $resign_date ='';
    $full_time = 0;
    $active = 0;
    $fee_deduction = 0;
    $volunteer = 0;

	if ($iud!='d') {
		$name = $_POST['name'];
		$address =  $_POST['address'];
		$city =  $_POST['city'];
		$zipcode =  $_POST['zipcode'];
		//$state =  $_POST['state'];
		$state = "WI";
		$phone =  $_POST['phone'];
		$comments =  $_POST['comments'];
		$email =  $_POST['email'];
		$ssn =  $_POST['ssn'];
		$join_date =  $_POST['join_date'];
		$resign_date =$_POST['resign_date'];
		$full_time =$_POST['full_time'];
		$active =$_POST['active'];
		$fee_deduction =$_POST['fee_deduction'];
		$volunteer =$_POST['volunteer'];
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid Teacher ID specified with the request';
		return $response;
	}

	try {
		$id = $teacher->iudTeacher($iud, $id, $name, $address, $city, $zipcode, $state,
				$phone, $email, $comments, $ssn, $join_date, $resign_date, $full_time, $active,
    			$fee_deduction, $volunteer);
		Logger::log($action_type. ' teacher complete');
		if ($id > 0) {
			$response['success'] = 1;
			$response['id'] = $id;
			$response['message'] = 'The teacher was successfully '. $action_type_done;
		} else {
			$response['error'] = 1;
			$response['message'] = 'Could not complete '. $action_type .' teacher request. Please check the details you entered and try again.';
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
	        Logger::log('Processing '. $action_type .' teacher request...');
	        $response = iudTeacher($iud, $action_type, $action_type_done);
	    }
	    Logger::log(print_r($response, true));
	} else if (isset($_GET['action'])) {
	    $action = $_GET['action'];
	   	if ($action == 'teacherlist') {
	        Logger::log('Processing list of teacher...');
	        $response = teacherList();
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

