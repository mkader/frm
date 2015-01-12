<?php
require_once('lib/include.php');

$db = new DB();
$pledge = new Pledges($db);
$session = new Sessions();
$common = new Commons();
function pledgelist() {
	global $pledge;
	$response = array();

	try {
		$responseData = $pledge->getPledgeList();
		Logger::log('PledgeList complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

function iudpledge($iud, $action_type, $action_type_done) {
    global $pledge, $session, $common;
    $response = array();
    $id =  @intval($_POST['id']);
    $event_id = 0;
	$donator_id = 0;
	$amount = 0;
	$payment_method_id = 0;
	$payment_type_id = 0;
	
	if ($iud!='d') {
		$event_id = @intval($_POST['event_id']);
		$donator_id = @intval($_POST['donator_id']);
		$amount = $_POST['amount'];
		$payment_method_id = @intval($_POST['payment_method_id']);
		$payment_type_id =  @intval($_POST['payment_type_id']);
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid Pledge ID specified with the request';
		return $response;
	}

	try {
		$id = $pledge->iudPledge($iud, $id, $event_id, $donator_id, $amount, $payment_method_id, $payment_type_id);
    	Logger::log($action_type. ' pledge complete');
        if ($id > 0) {
            $response['success'] = 1;
            $response['id'] = $id;
            $response['message'] = 'The pledge was successfully '. $action_type_done;
        } else {
            $response['error'] = 1;
            $response['message'] = 'Could not complete '. $action_type .' pledge request. Please check the details you entered and try again.';
        }
    } catch (DBException $e) {
        $response['error'] = 1;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

// Request Handler
$response = array();
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
        Logger::log('Processing '. $action_type .' pledge request...');
        $response = iudpledge($iud, $action_type, $action_type_done);
    }
} else if (isset($_GET['action'])) {
    $action = $_GET['action'];
   	if ($action == 'pledgelist') {
        Logger::log('Processing list of pledge...');
        $response = pledgeList();
    }
} else {
    $response['error'] = 1;
    $response['message'] = 'There was no request action specified.';
}

header('Content-type: text/plain');
Logger::log(print_r($response, true));
echo json_encode($response);

?>

