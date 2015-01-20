<?php
require_once('lib/include.php');

$db = new DB();
$payment = new Payments($db);

function paymentlist() {
	global $payment;
	$response = array();
	try {
		$responseData = $payment->getPaymentList();
		Logger::log('Payment List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

function donatorspaymentlist() {
	global $payment;
	$response = array();
	$id =  @intval($_GET['id']);
	try {
		$responseData = $payment->getDonatorsPaymentList($id);
		Logger::log('Payment List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

function iudpayment($iud, $action_type, $action_type_done) {
    global $payment;
    $response = array();
    $id =  @intval($_POST['id']);
    $pledge_id = 0;
	$donator_id = 0;
	$amount = 0;
	$payment_method_id = 0;
	$tax_year = 0;
	$payment_date = '';
	$comments = '';

	if ($iud!='d') {
		$payment_date = $_POST['payment_date'];
		$amount = $_POST['amount'];
		$payment_method_id = @intval($_POST['payment_method_id']);
		$tax_year = $_POST['tax_year'];
		$comments = $_POST['comments'];
		$donator_id = @intval($_POST['donator_id']);
		$pledge_id = @intval($_POST['pledge_id']);
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid Payment ID specified with the request';
		return $response;
	}

	try {
		$id = $payment->iudPayment($iud, $id, $payment_date, $amount, $payment_method_id,
    		$tax_year, $donator_id,  $pledge_id, $comments);
    	Logger::log($action_type. ' payment complete');
        if ($id > 0) {
            $response['success'] = 1;
            $response['id'] = $id;
            $response['message'] = 'The payment was successfully '. $action_type_done;
        } else {
            $response['error'] = 1;
            $response['message'] = 'Could not complete '. $action_type .' payment request. Please check the details you entered and try again.';
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
        Logger::log('Processing '. $action_type .' payment request...');
        $response = iudpayment($iud, $action_type, $action_type_done);
    }
    Logger::log(print_r($response, true));
} else if (isset($_GET['action'])) {
    $action = $_GET['action'];
   	if ($action == 'paymentlist') {
        Logger::log('Processing list of payment...');
        $response = paymentList();
    }else if ($action == 'donatorspaymentlist') {
    	Logger::log('Processing list of donatos payment...');
        $response = donatorspaymentList();
    }
} else {
    $response['error'] = 1;
    $response['message'] = 'There was no request action specified.';
    Logger::log(print_r($response, true));
}

header('Content-type: text/plain');

echo json_encode($response);

?>

