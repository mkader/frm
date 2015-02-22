<?php
require_once('lib/include.php');

$clsDB = new DB();
$clsExpense = new Expenses($clsDB);

/**
 *	list of expenses.
 *	Returns an array of success expenses list or error message.
 *
 *	@return	array
 *	@throws	DBException
 */
function expenseList() {
	global $clsExpense;
	$response = array();

	try {
		$responseData = $clsExpense->getExpenseList();
		Logger::log('Expense List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

/**
 *	create, modify and delete expense.
 *	Returns an array of success or error message
 *
 *	@param	string	$iud - i (insert) or u (update) or d (delete).
 *	@param	string	$actionType
 *	@param	string	$actionTypeDone
 *	@return	array
 *	@throws	DBException
 */
function iudExpense($iud, $actionType, $actionTypeDone) {
    global $clsExpense;
    $response = array();
    $id =  @intval($_POST['id']);
    $title = '';
	$expenseDate = '';
	$comments = '';
	$amount = 0;
	$eventID = 0;

	if ($iud!='d') {
		$title = $_POST['title'];
		$expenseDate = $_POST['expense_date'];
		$comments = $_POST['comments'];
		$amount = $_POST['amount'];
		$eventID =  @intval($_POST['event_id']);
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid Expense ID specified with the request';
		return $response;
	}

	try {
		$id = $clsExpense->iudExpense($iud, $id, $expenseDate, $eventID, $title, $comments, $amount);
		Logger::log($actionType. ' expense complete');
		if ($id > 0) {
			$response['success'] = 1;
			$response['id'] = $id;
			$response['message'] = 'The expense was successfully '. $actionTypeDone;
		} else {
			$response['error'] = 1;
			$response['message'] = 'Could not complete '. $actionType .' expense request. Please check the details you entered and try again.';
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
	        Logger::log('Processing '. $actionType .' expense request...');
	        $response = iudExpense($iud, $actionType, $actionTypeDone);
	    }
	    Logger::log(print_r($response, true));
	} else if (isset($_GET['action'])) {
	    $action = $_GET['action'];
	   	if ($action == 'expenselist') {
	        Logger::log('Processing list of expense...');
	        $response = expenseList();
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

