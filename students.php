<?php
require_once('lib/include.php');

$clsDB = new DB();
$clsStudents = new Students($clsDB);

/**
 *	list of Students.
 *	Returns an array of success Student list or error message.
 *
 *	@return	array
 *	@throws	DBException
 */
function studentList() {
	global $clsStudents;
	$response = array();
	try {
		$responseData = $clsStudents->getStudentList();
		Logger::log('Student List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

/**
 *	list of Students.
 *	Returns an array of success Student list or error message.
 *
 *	@return	array
 *	@throws	DBException
 */
function enrollmentStudentList() {
	global $clsStudents;
	$response = array();
	$id =  @intval($_GET['id']);
	try {
		$responseData = $clsStudents->getEnrollmentStudentList($id);
		Logger::log('Student List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

/**
 *	list of Students.
 *	Returns an array of success Student list JSON format or error message.
 *
 *	@return	array
 *	@throws	DBException
 */
function studentJSONList() {
	global $clsStudents;
	$response = array();
	$id =  @intval($_GET['id']);
	try {
		$responseData = $clsStudents->getStudentJSONList($id);
		Logger::log('Student List complete');
		$response['success'] = 1;
		$response['data'] = "{".$responseData ."}";
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

/**
 *	create, modify and delete Student.
 *	Returns an array of success or error message
 *
 *	@param	string	$iud - i (insert) or u (update) or d (delete).
 *	@param	string	$actionType
 *	@param	string	$actionTypeDone
 *	@return	array
 *	@throws	DBException
 */
function iudStudent($iud, $actionType, $actionTypeDone) {
    global $clsStudents;
    $response = array();
    $id =  @intval($_POST['id']);
    $enrollment_id = 0;
    $first_name ='';
    $middle_name='';
    $last_name=''; 
    $gender='';
	$dob='';
	$age=0;
	$public_school_grade=''; 
	$allergies_details='';
	$reading_level_arabic='';
	$reading_level_quran='';
	$date_of_join='';
	$comments='';
	$active=0;

	if ($iud!='d') {
		$enrollment_id = @intval($_POST['enrollment_id']);
		$first_name = $_POST['first_name'];
		$middle_name = $_POST['middle_name'];
		$last_name = $_POST['last_name'];
		$gender = $_POST['gender'];
		$dob = $_POST['dob'];
		$age =  $_POST['age'];
		$public_school_grade =  $_POST['public_school_grade'];
		$medical_conditions =  $_POST['medical_conditions'];
		$allergies_details =  $_POST['allergies_details'];
		$reading_level_arabic =  $_POST['reading_level_arabic'];
		$reading_level_quran =  $_POST['reading_level_quran'];
		$date_of_join =  $_POST['date_of_join'];
		$comments =  $_POST['comments'];
		$active =  $_POST['active'];
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid Student ID specified with the request';
		return $response;
	}

	try {
		$id = $clsStudents->iudStudent($iud, $id, $enrollment_id, $first_name, $middle_name, $last_name, $gender,
			$dob, $age, $public_school_grade, $medical_conditions, $allergies_details, 
			$reading_level_arabic, $reading_level_quran, $date_of_join, $comments, $active);
    	Logger::log($actionType. ' Student complete');
        if ($id > 0) {
            $response['success'] = 1;
            $response['id'] = $id;
            $response['message'] = 'The Student was successfully '. $actionTypeDone;
        } else {
            $response['error'] = 1;
            $response['message'] = 'Could not complete '. $actionType .' Student request. Please check the details you entered and try again.';
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
	        Logger::log('Processing '. $actionType .' Student request...');
	        $response = iudStudent($iud, $actionType, $actionTypeDone);
	    }
	    Logger::log(print_r($response, true));
	} else if (isset($_GET['action'])) {
	    $action = $_GET['action'];
	   	if ($action == 'studentlist') {
	        Logger::log('Processing list of student...');
	        $response = studentList();
	    }else if ($action == 'enrollmentstudentlist') {
	    	Logger::log('Processing list of donatos student...');
	        $response = enrollmentStudentList();
	    }else if ($action == 'studentlistjson') {
	    	Logger::log('Processing list of donatos student JSON ...');
	        $response = studentJSONList();
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

