<?php
require_once('lib/include.php');

$clsDB = new DB();
$clsMembers = new Members($clsDB);

/**
 *	list of members.
 *	Returns an array of success member list or error message.
 *
 *	@return	array
 *	@throws	DBException
 */
function memberList() {
    global $clsMembers;
    $response = array();

	try {
		$responseData = $clsMembers->getMemberList();
		Logger::log('Member List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
    } catch (DBException $e) {
        $response['error'] = 1;
        $response['message'] = $e->getMessage();
    }
    return $response;
}

/**
 *	create, modify and delete member.
 *	Returns an array of success or error message
 *
 *	@param	string	$iud - i (insert) or u (update) or d (delete).
 *	@param	string	$actionType
 *	@param	string	$actionTypeDone
 *	@return	array
 *	@throws	DBException
 */
function iudMember($iud, $actionType, $actionTypeDone) {
    global $clsMembers;
    $response = array();
    $id =  @intval($_POST['id']);
    $name = '';
	$phone = '';
	$email = '';
	$active = 0;
	if ($iud!='d') {
		$name = $_POST['name'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		$active = @intval($_POST['active']);
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid Member ID specified with the request';
		return $response;
	}

	try {
		$id = $clsMembers->iudMember($iud, $id, $name, $email, $phone, $active);
		Logger::log($actionType. ' member complete');
		if ($id > 0) {
			$response['success'] = 1;
			$response['id'] = $id;
			$response['message'] = 'The member was successfully '. $actionTypeDone;
		} else {
			$response['error'] = 1;
			$response['message'] = 'Could not complete '. $action_type .' member request. Please check the details you entered and try again.';
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
    if ($action == 'iud'  && Sessions::isValidSession()) {
    	$actionType ="insert";
    	$actionTypeDone ="inserted";
    	$iud = $_POST['iud'];
    	if ($iud=='u') {
    		$actionType ="update";
    		$actionTypeDdone ="updated";
    	}
    	else if ($iud=='d') {
    		$actionType ="delete";
    		$actionTypeDone ="deleted";
    	}
        Logger::log('Processing '. $actionType .' member request...');
        $response = iudMember($iud, $actionType, $actionTypeDone);
    } else if ($action == 'login') {
        Logger::log('Processing login request...');
        $response = login();
    }
    Logger::log(print_r($response, true));
} else if (isset($_GET['action'])) {
    $action = $_GET['action'];
   	if ($action == 'memberlist'  && Sessions::isValidSession()) {
        Logger::log('Processing list of member...');
        $response = memberList();
    }
} else {
    $response['error'] = 1;
    $response['message'] = 'There was no request action specified.';
    Logger::log(print_r($response, true));
}

header('Content-type: text/plain');
echo json_encode($response);
?>

