<?php
require_once('lib/include.php');

$clsDB = new DB();
$clsFee = new Fees($clsDB);

/**
 *	list of donators fee.
 *	Returns an array of success donators fee list or error message.
 *
 *	@return	array
 *	@throws	DBException
 */
function enrollmentFeeList() {
	global $clsFee;
	$response = array();
	$id =  @intval($_GET['id']);
	try {
		$responseData = $clsFee->getEnrollmentFeeList($id);
		Logger::log('Fee List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

/**
 *	create, modify and delete fee.
 *	Returns an array of success or error message
 *
 *	@param	string	$iud - i (insert) or u (update) or d (delete).
 *	@param	string	$actionType
 *	@param	string	$actionTypeDone
 *	@return	array
 *	@throws	DBException
 */
function iudFee($iud, $actionType, $actionTypeDone) {
    global $clsFee;
    $response = array();
    $id =  @intval($_POST['id']);
    $enrollmentID = 0;
	$eventID = 0;
	$amount = 0;
	$feeMethodID = 0;
	$feeDate = '';
	$comments = '';

	if ($iud!='d') {
		$feeDate = $_POST['fee_date'];
		$amount = $_POST['amount'];
		$feeMethodID = @intval($_POST['fee_method_id']);
		$comments = $_POST['comments'];
		$eventID = @intval($_POST['event_id']);
		$enrollmentID = @intval($_POST['enrollment_id']);
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid Fee ID specified with the request';
		return $response;
	}

	try {
		$id = $clsFee->iudFee($iud, $id, $feeDate, $amount,
			$feeMethodID, $enrollmentID,  $eventID, $comments);
    	Logger::log($actionType. ' fee complete');
        if ($id > 0) {
            $response['success'] = 1;
            $response['id'] = $id;
            $response['message'] = 'The fee was successfully '. $actionTypeDone;
        } else {
            $response['error'] = 1;
            $response['message'] = 'Could not complete '. $actionType .' fee request. Please check the details you entered and try again.';
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
	        Logger::log('Processing '. $actionType .' fee request...');
	        $response = iudFee($iud, $actionType, $actionTypeDone);
	    }
	    Logger::log(print_r($response, true));
	} else if (isset($_GET['action'])) {
	    $action = $_GET['action'];
	   	if ($action == 'feelist') {
	        Logger::log('Processing list of fee...');
	        $response = feeList();
	    }else if ($action == 'enrollmentfeelist') {
	    	Logger::log('Processing list of enrollment fee...');
	        $response = enrollmentFeeList();
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

