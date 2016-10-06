<?php
require_once('lib/include.php');

$clsDB = new DB();
$clsPledges = new Pledges($clsDB);

/**
 *	list of pledges.
 *	Returns an array of success pledge list or error message.
 *
 *	@return	array
 *	@throws	DBException
 */
function pledgeList() {
	global $clsPledges;
	$response = array();
	try {
		$responseData = $clsPledges->getPledgeList();
		Logger::log('Pledge List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

/**
 *	list of donators pledges.
 *	Returns an array of success donators pledge list or error message.
 *
 *	@return	array
 *	@throws	DBException
 */
function donatorsPledgeList() {
	global $clsPledges;
	$response = array();
	$id =  @intval($_GET['id']);
	try {
		$responseData = $clsPledges->getDonatorsPledgeList($id);
		Logger::log('Pledge List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

/**
 *	list of donators pledges.
 *	Returns an array of success donators pledge list JSON format or error message.
 *
 *	@return	array
 *	@throws	DBException
 */
function donatorsPledgeJSONList() {
	global $clsPledges;
	$response = array();
	$id =  @intval($_GET['id']);
	try {
		$responseData = $clsPledges->getDonatorsPledgeJSONList($id);
		Logger::log('Pledge List complete');
		$response['success'] = 1;
		$response['data'] = "{".$responseData ."}";
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

/**
 *	create, modify and delete pledge.
 *	Returns an array of success or error message
 *
 *	@param	string	$iud - i (insert) or u (update) or d (delete).
 *	@param	string	$actionType
 *	@param	string	$actionTypeDone
 *	@return	array
 *	@throws	DBException
 */
function iudPledge($iud, $actionType, $actionTypeDone) {
    global $clsPledges;
    $response = array();
    $id =  @intval($_POST['id']);
    $eventID = 0;
	$donatorID = 0;
	$amount = 0;

	if ($iud!='d') {
		$eventID = @intval($_POST['event_id']);
		$donatorID = @intval($_POST['donator_id']);
		$amount = $_POST['amount'];
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid Pledge ID specified with the request';
		return $response;
	}

	try {
		$id = $clsPledges->iudPledge($iud, $id, $eventID, $donatorID, $amount);
    	Logger::log($actionType. ' pledge complete');
        if ($id > 0) {
            $response['success'] = 1;
            $response['id'] = $id;
            $response['message'] = 'The pledge was successfully '. $actionTypeDone;
        } else {
            $response['error'] = 1;
            $response['message'] = 'Could not complete '. $actionType .' pledge request. Please check the details you entered and try again.';
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
	    	$actionType ="insert";
	    	$actionTypeDone ="inserted";
	    	$iud = $_POST['iud'];
	    	if ($iud=='u') {
	    		$actionType ="update";
	    		$actionTypeDone ="updated";
	    	} else if ($iud=='d') {
	    		$actionType ="delete";
	    		$actionTypeDone ="deleted";
	    	}
	        Logger::log('Processing '. $actionType .' pledge request...');
	        $response = iudPledge($iud, $actionType, $actionTypeDone);
	    }
	    Logger::log(print_r($response, true));
	} else if (isset($_GET['action'])) {
	    $action = $_GET['action'];
	   	if ($action == 'pledgelist') {
	        Logger::log('Processing list of pledge...');
	        $response = pledgeList();
	    }else if ($action == 'donatorspledgelist') {
	    	Logger::log('Processing list of donatos pledge...');
	        $response = donatorsPledgeList();
	    }else if ($action == 'donatorspledgelistjson') {
	    	Logger::log('Processing list of donatos pledge JSON ...');
	        $response = donatorsPledgeJSONList();
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

