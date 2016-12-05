<?php
require_once('lib/include.php');

$clsDB = new DB();
$clsTeachers = new Teachers($clsDB);

/**
 *	list of attendances.
 *	Returns an array of success attendance list or error message.
 *
 *	@return	array
 *	@throws	DBException
 */
function teacherAttendanceList() {
	global $clsTeachers;
	$id =  @intval($_GET['id']);
	$response = array();
	try {
		$responseData = $clsTeachers->getTeacherAttendanceList($id);
		Logger::log('Attendance List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}


/**
 *	create, modify and delete Attendance.
 *	Returns an array of success or error message
 *
 *	@param	string	$iud - i (insert) or u (update) or d (delete).
 *	@param	string	$actionType
 *	@param	string	$actionTypeDone
 *	@return	array
 *	@throws	DBException
 */
function iudAttendance($iud, $actionType, $actionTypeDone) {
    global $clsTeachers;
    $response = array();
    $id =  @intval($_POST['id']);
    $teacher_id = 0;
    $attendance_date ='';
    $time_in='';
    $time_out=''; 
    $hours=0;
	
	if ($iud!='d') {
		$teacher_id = @intval($_POST['teacher_id']);
		$attendance_date = $_POST['attendance_date'];
		$time_in = $_POST['time_in'];
		$time_out = $_POST['time_out'];
		$hours = $_POST['hours'];
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid data specified with the request';
		return $response;
	}

	try {
		$id = $clsTeachers->iudAttendance($iud, $id, $teacher_id, $attendance_date, 
			$time_in, $time_out, $hours);
    	Logger::log($actionType. ' Attendance complete');
        if ($id > 0) {
            $response['success'] = 1;
            $response['id'] = $id;
            $response['message'] = 'The Attendance was successfully '. $actionTypeDone;
        
        } else {
            $response['error'] = 1;
            $response['message'] = 'Could not complete '. $actionType .' Attendance request. Please check the details you entered and try again.';
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
	        Logger::log('Processing '. $actionType .' Attendance request...');
	        $response = iudAttendance($iud, $actionType, $actionTypeDone);
	    }
	    Logger::log(print_r($response, true));
	} else if (isset($_GET['action'])) {
	    $action = $_GET['action'];
	   	if ($action == 'teacherattendancelist') {
	        Logger::log('Processing list of attendance...');
	        $response = teacherAttendanceList();
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

