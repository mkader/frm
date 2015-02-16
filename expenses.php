<?php
require_once('lib/include.php');

$db = new DB();
$expense = new Expenses($db);

function expenselist() {
	global $expense;
	$response = array();

	try {
		$responseData = $expense->getExpenseList();
		Logger::log('Expense List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

function iudexpense($iud, $action_type, $action_type_done) {
    global $expense;
    $response = array();
    $id =  @intval($_POST['id']);
    $title = '';
	$expense_date = '';
	$comments = '';
	$amount = 0;
	$event_id = 0;

	if ($iud!='d') {
		$title = $_POST['title'];
		$expense_date = $_POST['expense_date'];
		$comments = $_POST['comments'];
		$amount = $_POST['amount'];
		$event_id =  @intval($_POST['event_id']);
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid Expense ID specified with the request';
		return $response;
	}

	try {
		$id = $expense->iudExpense($iud, $id, $expense_date, $event_id, $title, $comments, $amount);
		Logger::log($action_type. ' expense complete');
		if ($id > 0) {
			$response['success'] = 1;
			$response['id'] = $id;
			$response['message'] = 'The expense was successfully '. $action_type_done;
		} else {
			$response['error'] = 1;
			$response['message'] = 'Could not complete '. $action_type .' expense request. Please check the details you entered and try again.';
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
	        Logger::log('Processing '. $action_type .' expense request...');
	        $response = iudexpense($iud, $action_type, $action_type_done);
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

