<?php
require_once('lib/include.php');

$clsDB = new DB();
$clsUsers = new Users($clsDB);

/**
 *	Update login user profile.
 *	Returns an array of success or error message.
 *
 *	@return	array
 *	@throws	DBException
 */
function myProfileUpdate() {
    global $clsUsers;
    $response = array();

    $password = $_POST['password'];
    $phone = $_POST['phone'];

	try {
    	$responseData = $clsUsers->myprofileUpdate($phone, $password);
		Logger::log('Myprofile Update complete');
		if (!$responseData) {
			$response['error'] = 1;
		    $response['message'] = 'Could not complete myprofile update user request. Please check the details you entered and try again.';
	    } else {
			$response['success'] = 1;
			$response['message'] = 'MyProfile updated successfully.';
		}
	} catch (DBException $e) {
        $response['error'] = 1;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

/**
 *	user login.
 *	Returns an array of success user info or error message.
 *
 *	@return	array
 *	@throws	DBException
 */
function login() {
	global $clsUsers;
	$response = array();

	$usersName = $_POST['username'];
	$password = $_POST['password'];
	$securityCode = $_POST['securitycode'];

	try {
		//Enable this code when i deploy to server.
		/*if(Sessions::securityCode() != $securityCode) {
			$response['error'] = 1;
			$response['message'] = 'Invalid security code. Please try again.';
		} else {*/
			$responseData = $clsUsers->login($usersName, $password);
			Logger::log('Login complete');
			if (!$responseData) {
				$response['error'] = 1;
				$response['message'] = 'Invalid e-mail/password combination specified. Please try again.';
			} else {
				$response['success'] = 1;
				$response['data'] = $responseData;
				Sessions::setLoginUserInfo($responseData);
			}
		//}
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}

	return $response;
}

/**
 *	list of users.
 *	Returns an array of success user list or error message.
 *
 *	@return	array
 *	@throws	DBException
 */
function userList() {
    global $clsUsers;
    $response = array();

	try {
		$responseData = $clsUsers->getUserList();
		Logger::log('User List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
    } catch (DBException $e) {
        $response['error'] = 1;
        $response['message'] = $e->getMessage();
    }
    return $response;
}

/**
 *	logout.
 *	Returns an array of success or error message.
 *
 *	@return	array
 *	@throws	DBException
 */
function logout() {
    $response = array();
	try {
        session_unset();
		session_destroy();
        Logger::log('Logout complete');
        $response['success'] = 1;
        $response['message'] = 'You have logged out successfully.';
    } catch (DBException $e) {
        $response['error'] = 1;
        $response['message'] = $e->getMessage();
    }
    return $response;
}

/**
 *	create, modify and delete user.
 *	Returns an array of success or error message
 *
 *	@param	string	$iud - i (insert) or u (update) or d (delete).
 *	@param	string	$actionType
 *	@param	string	$actionTypeDone
 *	@return	array
 *	@throws	DBException
 */
function iudUser($iud, $actionType, $actionTypeDone) {
    global $clsUsers;
    $response = array();
    $id =  @intval($_POST['id']);
    $name = '';
	$password = '';
	$phone = '';
	$email = '';
	$userTypeID = 0;
	$active = 0;
	if ($iud!='d') {
		$name = $_POST['name'];
		$password = $_POST['password'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		$userTypeID =  @intval($_POST['user_type']);
		$active = @intval($_POST['active']);
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid User ID specified with the request';
		return $response;
	}

	/*if ($iud=='d' && !Sessions::isLoginUserSuperAdmin()) {
		$response['error'] = 1;
		$response['message'] = 'You do not have sufficient privileges to complete this request.';
		return $response;
	}*/

	try {
		if(($iud=='d') ||($iud!='d' && !$clsUsers->isUserExists($id, $email))) {
			$id = $clsUsers->iudUser($iud, $id, $name, $email, $password, $phone, $userTypeID, $active);
	    	Logger::log($actionType. ' user complete');
	        if ($id > 0) {
	            $response['success'] = 1;
	            $response['id'] = $id;
	            $response['message'] = 'The user was successfully '. $actionTypeDone;
	        } else {
	            $response['error'] = 1;
	            $response['message'] = 'Could not complete '. $action_type .' user request. Please check the details you entered and try again.';
	        }
		} else {
			$response['error'] = 1;
			$response['message'] = 'The user already exists in the system.';
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
        Logger::log('Processing '. $actionType .' user request...');
        $response = iudUser($iud, $actionType, $actionTypeDone);
    } else if ($action == 'login') {
        Logger::log('Processing login request...');
        $response = login();
    } else if ($action == 'myprofileupdate' && Sessions::isValidSession()) {
        Logger::log('Processing update myprofile request...');
        $response = myProfileUpdate();
    }
    Logger::log(print_r($response, true));
} else if (isset($_GET['action'])) {
    $action = $_GET['action'];
   	if ($action == 'logout') {
        Logger::log('Processing logout request...');
        $response = logout();
    }  else if ($action == 'userlist'  && Sessions::isValidSession()) {
        Logger::log('Processing list of user...');
        $response = userList();
    }
} else {
    $response['error'] = 1;
    $response['message'] = 'There was no request action specified.';
    Logger::log(print_r($response, true));
}

header('Content-type: text/plain');
echo json_encode($response);
?>

