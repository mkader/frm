<?php
require_once('lib/include.php');

$db = new DB();
$user = new Users($db);
$session = new Sessions();

function login() {
    global $user, $session;
    $response = array();

    $username = $_POST['username'];
    $password = $_POST['password'];
    $securitycode = $_POST['securitycode'];

	try {
    	//if($session->securityCode() != $securitycode) {
    	//	$response['error'] = 1;
		//	$response['message'] = 'Invalid security code. Please try again.';
		//} else {
			$responseData = $user->login($username, $password);
			Logger::log('Login complete');
			if (!$responseData) {
				$response['error'] = 1;
				$response['message'] = 'Invalid e-mail/password combination specified. Please try again.';
			} else {
				$response['success'] = 1;
				$response['data'] = $responseData;
				$session->setLoginUserInfo($responseData);
			}
		//}
    } catch (DBException $e) {
        $response['error'] = 1;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

function userlist() {
    global $user;
    $response = array();

	try {
		$responseData = $user->getUserList();
		Logger::log('UserList complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
    } catch (DBException $e) {
        $response['error'] = 1;
        $response['message'] = $e->getMessage();
    }
    return $response;
}

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

function iuduser($iud, $action_type, $action_type_done) {
    global $user, $session;
    $response = array();
    $id =  @intval($_POST['id']);
    $name = '';
	$password = '';
	$phone = '';
	$email = '';
	$user_type_id = 0;
	$active = 0;
	if ($iud!='d') {
		$name = $_POST['name'];
		$password = $_POST['password'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		$user_type_id =  @intval($_POST['user_type']);
		$active = $_POST['active'];
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid User ID specified with the request';
		return $response;
	}

	/*if ($iud=='d' && !$session->isLoginUserSuperAdmin()) {
		$response['error'] = 1;
		$response['message'] = 'You do not have sufficient privileges to complete this request.';
		return $response;
	}*/

	try {
		if(($iud=='d') ||($iud!='d' && !$user->isUserExists($id, $email))) {
			$id = $user->iudUser($iud, $id, $name, $email, $password, $phone, $user_type_id, $active);
	    	Logger::log($action_type. ' user complete');
	        if ($id > 0) {
	            $response['success'] = 1;
	            $response['id'] = $id;
	            $response['message'] = 'The user was successfully '. $action_type_done;
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
    if ($action == 'iud') {
    	$action_type ="insert";
    	$action_type_done ="inserted";
    	$iud = $_POST['iud'];
    	if ($iud=='u') {
    		$action_type ="update";
    		$action_type_done ="updated";
    	}
    	else if ($iud=='d') {
    		$action_type ="delete";
    		$action_type_done ="deleted";
    	}
        Logger::log('Processing '. $action_type .' user request...');
        $response = iuduser($iud, $action_type, $action_type_done);
    } else if ($action == 'login') {
        Logger::log('Processing login request...');
        $response = login();
    }
} else if (isset($_GET['action'])) {
    $action = $_GET['action'];
   	if ($action == 'logout') {
        Logger::log('Processing logout request...');
        $response = logout();
    }  else if ($action == 'userlist') {
        Logger::log('Processing list of user...');
        $response = userList();
    }
} else {
    $response['error'] = 1;
    $response['message'] = 'There was no request action specified.';
}

header('Content-type: text/plain');
Logger::log(print_r($response, true));
echo json_encode($response);

?>

