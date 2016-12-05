<?php
require_once('lib/include.php');

$clsDB = new DB();
$clsTeachers = new Teachers($clsDB);

/**
 *	list of salary.
 *	Returns an array of success salary list or error message.
 *
 *	@return	array
 *	@throws	DBException
 */
function teacherSalaryList() {
	global $clsTeachers;
	$id =  @intval($_GET['id']);
	$response = array();
	try {
		$responseData = $clsTeachers->getTeacherSalaryList($id);
		Logger::log('Salary List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}


/**
 *	create, modify and delete salary.
 *	Returns an array of success or error message
 *
 *	@param	string	$iud - i (insert) or u (update) or d (delete).
 *	@param	string	$actionType
 *	@param	string	$actionTypeDone
 *	@return	array
 *	@throws	DBException
 */
function iudSalary($iud, $actionType, $actionTypeDone) {
    global $clsTeachers;
    $response = array();
    $id =  @intval($_POST['id']);
    $school_teacher_id = 0;
    $salary_date ='';
    $worked_hours=0;
    $total_salary=0; 
    $deduction=0;
    $payment=0;
    
	
	if ($iud!='d') {
		$school_teacher_id = @intval($_POST['teacher_id']);
		$salary_date = $_POST['salary_date'];
		$worked_hours = $_POST['worked_hours'];
		$total_salary = $_POST['total_salary'];
		$deduction = $_POST['deduction'];
		$payment = $_POST['payment'];
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid data specified with the request';
		return $response;
	}

	try {
		$id = $clsTeachers->iudSalary($iud, $id, $school_teacher_id, 
			$salary_date, $worked_hours, $total_salary, $deduction, $payment);
    	Logger::log($actionType. ' Salary complete');
        if ($id > 0) {
            $response['success'] = 1;
            $response['id'] = $id;
            $response['message'] = 'The Salary was successfully '. $actionTypeDone;
        
        } else {
            $response['error'] = 1;
            $response['message'] = 'Could not complete '. $actionType .' Salary request. Please check the details you entered and try again.';
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
	        Logger::log('Processing '. $actionType .' Salary request...');
	        $response = iudSalary($iud, $actionType, $actionTypeDone);
	    }
	    Logger::log(print_r($response, true));
	} else if (isset($_GET['action'])) {
	    $action = $_GET['action'];
	   	if ($action == 'teachersalarylist') {
	        Logger::log('Processing list of salary...');
	        $response = teacherSalaryList();
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

