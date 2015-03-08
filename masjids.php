<?php
require_once('lib/include.php');

$db = new DB();
$masjid = new Masjids($db);

function masjidlist() {
	global $masjid;
	$response = array();

	try {
		$responseData = $masjid->getMasjidList();
		Logger::log('Masjid List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

function iudmasjid($iud, $action_type, $action_type_done) {
    global $masjid;
    $response = array();
    $id =  @intval($_POST['id']);
    $name = '';
    $address = '';
    $city = '';
    $state = '';
    $zipcode =  '';
    $email =  '';
    $phone =  '';
    $contact_name =  '';
    $contact_phone =  '';
    $contact_email =  '';
    $comments =  '';
    $website = '';

	if ($iud!='d') {
		$name = $_POST['name'];
		$address = $_POST['address'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$zipcode =  $_POST['zipcode'];
		$email =  $_POST['email'];
		$phone =  $_POST['phone'];
		$website =  $_POST['website'];
		$contact_name =  $_POST['contact_name'];
		$contact_phone =  $_POST['contact_phone'];
		$contact_email =  $_POST['contact_email'];
		$comments =  $_POST['comments'];
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid Masjid ID specified with the request';
		return $response;
	}

	try {
		$id = $masjid->iudMasjid($iud, $id, $name, $address, $city, $state,
			$zipcode, $email, $phone, $contact_name, $contact_phone,
			$contact_email, $comments, $website);
		Logger::log($action_type. ' masjid complete');
		if ($id > 0) {
			$response['success'] = 1;
			$response['id'] = $id;
			$response['message'] = 'The masjid was successfully '. $action_type_done;
		} else {
			$response['error'] = 1;
			$response['message'] = 'Could not complete '. $action_type .' masjid request. Please check the details you entered and try again.';
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
	        Logger::log('Processing '. $action_type .' masjid request...');
	        $response = iudmasjid($iud, $action_type, $action_type_done);
	    }
	    Logger::log(print_r($response, true));
	} else if (isset($_GET['action'])) {
	    $action = $_GET['action'];
	   	if ($action == 'masjidlist') {
	        Logger::log('Processing list of masjid...');
	        $response = masjidList();
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

