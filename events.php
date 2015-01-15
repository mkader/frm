<?php
require_once('lib/include.php');

$db = new DB();
$event = new Events($db);
$session = new Sessions();
$common = new Commons();
function eventlist() {
	global $event;
	$response = array();

	try {
		$responseData = $event->getEventList();
		Logger::log('Event List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

function iudevent($iud, $action_type, $action_type_done) {
    global $event, $session, $common;
    $response = array();
    $id =  @intval($_POST['id']);
    $title = '';
	$event_date = '';
	$location = '';
	$description = '';
	$target_amount = 0;
	$pledge_type_id = 0;

	if ($iud!='d') {
		$title = $_POST['title'];
		$event_date = $_POST['event_date'];
		$location = $_POST['location'];
		$description = $_POST['description'];
		$target_amount = $_POST['target_amount'];
		$pledge_type_id =  @intval($_POST['pledge_type']);
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid Event ID specified with the request';
		return $response;
	}

	try {
		$id = $event->iudEvent($iud, $id, $title, $event_date, $location, $description, $target_amount, $pledge_type_id);
		Logger::log($action_type. ' event complete');
		if ($id > 0) {
			$response['success'] = 1;
			$response['id'] = $id;
			$response['message'] = 'The event was successfully '. $action_type_done;
		} else {
			$response['error'] = 1;
			$response['message'] = 'Could not complete '. $action_type .' event request. Please check the details you entered and try again.';
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
        Logger::log('Processing '. $action_type .' event request...');
        $response = iudevent($iud, $action_type, $action_type_done);
    }
} else if (isset($_GET['action'])) {
    $action = $_GET['action'];
   	if ($action == 'eventlist') {
        Logger::log('Processing list of event...');
        $response = eventList();
    }
} else {
    $response['error'] = 1;
    $response['message'] = 'There was no request action specified.';
}

header('Content-type: text/plain');
Logger::log(print_r($response, true));
echo json_encode($response);

?>

