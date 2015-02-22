<?php
require_once('lib/include.php');

$clsDB = new DB();
$clsPayment = new Payments($clsDB);

/**
 *	list of donators payment.
 *	Returns an array of success donators payment list or error message.
 *
 *	@return	array
 *	@throws	DBException
 */
function donatorsPaymentList() {
	global $clsPayment;
	$response = array();
	$id =  @intval($_GET['id']);
	try {
		$responseData = $clsPayment->getDonatorsPaymentList($id);
		Logger::log('Payment List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

/**
 *	create, modify and delete payment.
 *	Returns an array of success or error message
 *
 *	@param	string	$iud - i (insert) or u (update) or d (delete).
 *	@param	string	$actionType
 *	@param	string	$actionTypeDone
 *	@return	array
 *	@throws	DBException
 */
function iudPayment($iud, $actionType, $actionTypeDone) {
    global $clsPayment;
    $response = array();
    $id =  @intval($_POST['id']);
    $pledgeID = 0;
	$donatorID = 0;
	$amount = 0;
	$paymentMethodID = 0;
	$taxYear = 0;
	$paymentDate = '';
	$comments = '';

	if ($iud!='d') {
		$paymentDate = $_POST['payment_date'];
		$amount = $_POST['amount'];
		$paymentMethodID = @intval($_POST['payment_method_id']);
		$taxYear = $_POST['tax_year'];
		$comments = $_POST['comments'];
		$donatorID = @intval($_POST['donator_id']);
		$pledgeID = @intval($_POST['pledge_id']);
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid Payment ID specified with the request';
		return $response;
	}

	try {
		$id = $clsPayment->iudPayment($iud, $id, $paymentDate, $amount,
			$paymentMethodID, $taxYear, $donatorID,  $pledgeID, $comments);
    	Logger::log($actionType. ' payment complete');
        if ($id > 0) {
            $response['success'] = 1;
            $response['id'] = $id;
            $response['message'] = 'The payment was successfully '. $actionTypeDone;
        } else {
            $response['error'] = 1;
            $response['message'] = 'Could not complete '. $actionType .' payment request. Please check the details you entered and try again.';
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
	        Logger::log('Processing '. $actionType .' payment request...');
	        $response = iudPayment($iud, $actionType, $actionTypeDone);
	    }
	    Logger::log(print_r($response, true));
	} else if (isset($_GET['action'])) {
	    $action = $_GET['action'];
	   	if ($action == 'paymentlist') {
	        Logger::log('Processing list of payment...');
	        $response = paymentList();
	    }else if ($action == 'donatorspaymentlist') {
	    	Logger::log('Processing list of donatos payment...');
	        $response = donatorsPaymentList();
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

