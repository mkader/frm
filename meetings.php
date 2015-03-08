<?php
require_once('lib/include.php');

$clsDB = new DB();
$clsMeeting = new Meetings($clsDB);

/**
 *	list of meetings.
 *	Returns an array of success meetings list or error message.
 *
 *	@return	array
 *	@throws	DBException
 */
function meetingList() {
	global $clsMeeting;
	$response = array();

	try {
		$responseData = $clsMeeting->getMeetingList();
		Logger::log('Meeting List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

/**
 *	create, modify and delete meeting.
 *	Returns an array of success or error message
 *
 *	@param	string	$iud - i (insert) or u (update) or d (delete).
 *	@param	string	$actionType
 *	@param	string	$actionTypeDone
 *	@return	array
 *	@throws	DBException
 */
function iudMeeting($iud, $actionType, $actionTypeDone) {
    global $clsMeeting;
    $response = array();
    $id =  @intval($_POST['id']);
    $meetingTime = '';
	$meetingDate = '';

	if ($iud!='d') {
		$meetingTime = $_POST['meeting_time'];
		$meetingDate = $_POST['meeting_date'];
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid Meeting ID specified with the request';
		return $response;
	}

	try {
		$id = $clsMeeting->iudMeeting($iud, $id, $meetingDate, $meetingTime);
		Logger::log($actionType. ' meeting complete');
		if ($id > 0) {
			$response['success'] = 1;
			$response['id'] = $id;
			$response['message'] = 'The meeting was successfully '. $actionTypeDone;
		} else {
			$response['error'] = 1;
			$response['message'] = 'Could not complete '. $actionType .' meeting request. Please check the details you entered and try again.';
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
	        Logger::log('Processing '. $actionType .' meeting request...');
	        $response = iudMeeting($iud, $actionType, $actionTypeDone);
	    }
	    Logger::log(print_r($response, true));
	} else if (isset($_GET['action'])) {
	    $action = $_GET['action'];
	   	if ($action == 'meetinglist') {
	        Logger::log('Processing list of meeting...');
	        $response = meetingList();
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

