<?php
require_once('lib/include.php');

$db = new DB();
$donator = new Donators($db);

function donatorlist() {
	global $donator;
	$response = array();

	try {
		$responseData = $donator->getDonatorList();
		Logger::log('Donator List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

function iuddonator($iud, $action_type, $action_type_done) {
    global $donator;
    $response = array();
    $id =  @intval($_POST['id']);
    $name = '';
    $address1 = '';
    $address2 = '';
    $city = '';
    $state = '';
    $zipcode =  '';
    $email =  '';
    $phone =  '';
    $company_name =  '';
    $comments =  '';

	if ($iud!='d') {
		$name = $_POST['name'];
		$address1 = $_POST['address1'];
		$address2 = $_POST['address2'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$zipcode =  $_POST['zipcode'];
		$email =  $_POST['email'];
		$phone =  $_POST['phone'];
		$company_name =  $_POST['company_name'];
		$comments =  $_POST['comments'];
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid Donator ID specified with the request';
		return $response;
	}

	try {
		$id = $donator->iudDonator($iud, $id, $name, $address1, $address2, $city,
			$state, $zipcode, $email, $phone, $company_name, $comments);
		Logger::log($action_type. ' donator complete');
		if ($id > 0) {
			$response['success'] = 1;
			$response['id'] = $id;
			$response['message'] = 'The donator was successfully '. $action_type_done;
		} else {
			$response['error'] = 1;
			$response['message'] = 'Could not complete '. $action_type .' donator request. Please check the details you entered and try again.';
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
	        Logger::log('Processing '. $action_type .' donator request...');
	        $response = iuddonator($iud, $action_type, $action_type_done);
	    }
	    Logger::log(print_r($response, true));
	} else if (isset($_GET['action'])) {
	    $action = $_GET['action'];
	   	if ($action == 'donatorlist') {
	        Logger::log('Processing list of donator...');
	        $response = donatorList();
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

