<?php
require_once('lib/include.php');

$clsDB = new DB();
$clsACH = new ACHs($clsDB);

/**
 *	list of donators ACH.
 *	Returns an array of success donators ACH list or error message.
 *
 *	@return	array
 *	@throws	DBException
 */
function donatorsACHList() {
	global $clsACH;
	$response = array();
	$id =  @intval($_GET['id']);
	try {
		$responseData = $clsACH->getDonatorsACHList($id);
		Logger::log('ACH List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

/**
 *	create, modify and delete ACH.
 *	Returns an array of success or error message
 *
 *	@param	string	$iud - i (insert) or u (update) or d (delete).
 *	@param	string	$actionType
 *	@param	string	$actionTypeDone
 *	@return	array
 *	@throws	DBException
 */
function iudACH($iud, $actionType, $actionTypeDone) {
    global $clsACH;
    $response = array();
    $id =  @intval($_POST['id']);
    $donatorID = 0;
	$paymentMethodID = 0;
	$achDate = '';
	$bankName = '';
	$bankAccountTypeID = 1;
	$routingNumber = '';
	$accountNnumber = '';
	$voidCheckIncluded = 0;
	$creditCardTypeID = 1;
	$creditCardNumber = '';
	$creditCardEexpiraitonDate = '';
	$creditCcardSecurityCode = '';
	$cycle = 0;
	$amount = 0;
	$startDate = '';
	$endDate = '';
	$comments = '';

	if ($iud!='d') {
		$donatorID = @intval($_POST['donator_id']);
		$paymentMethodID = @intval($_POST['payment_method_id']);
		$achDate = $_POST['ach_date'];
		//$bankName = $_POST['bank_name'];
		//$bankAccountTypeID = @intval($_POST['bank_account_type_id']);
		//$routingNumber = $_POST['routing_number'];
		//$accountNnumber = $_POST['account_number'];
		//$voidCheckIncluded = @intval($_POST['void_check_included']);
		//$creditCardTypeID = @intval($_POST['credit_card_type_id']);
		//$creditCardNumber = $_POST['credit_card_number'];
		//$creditCardEexpiraitonDate = $_POST['credit_card_expiraiton_date'];
		//$creditCcardSecurityCode = $_POST['credit_card_security_code'];
		$cycle = @intval($_POST['cycle']);
		$amount = $_POST['amount'];
		$startDate = $_POST['start_date'];
		$endDate = $_POST['end_date'];
		$comments = $_POST['comments'];
	}

	if (($iud=='u' || $iud=='d') && $id<=0) {
		$response['error'] = 1;
		$response['message'] = 'Invalid ACH ID specified with the request';
		return $response;
	}

	try {
		$id = $clsACH->iudACH($iud, $id, $donatorID, $paymentMethodID, $achDate, 
    		$bankName, $bankAccountTypeID, $routingNumber, $accountNnumber, 
    		$voidCheckIncluded, $creditCardTypeID, $creditCardNumber, 
    		$creditCardEexpiraitonDate, $creditCcardSecurityCode, $cycle, 
    		$amount, $startDate, $endDate, $comments);
    	Logger::log($actionType. ' ACH complete');
        if ($id > 0) {
            $response['success'] = 1;
            $response['id'] = $id;
            $response['message'] = 'The ACH was successfully '. $actionTypeDone;
        } else {
            $response['error'] = 1;
            $response['message'] = 'Could not complete '. $actionType .' ACH request. Please check the details you entered and try again.';
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
	        Logger::log('Processing '. $actionType .' ACH request...');
	        $response = iudACH($iud, $actionType, $actionTypeDone);
	    }
	    Logger::log(print_r($response, true));
	} else if (isset($_GET['action'])) {
	    $action = $_GET['action'];
	   	if ($action == 'achlist') {
	        Logger::log('Processing list of ACH...');
	        $response = achList();
	    }else if ($action == 'donatorsachlist') {
	    	Logger::log('Processing list of donatos ACH...');
	        $response = donatorsACHList();
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

