<?php
require_once('lib/include.php');

$clsDB = new DB();
$clsLog = new Logs($clsDB);

/**
 *	list of logs.
 *	Returns an array of success logs list or error message.
 *
 *	@return	array
 *	@throws	DBException
 */
function logList() {
	global $clsLog;
	$response = array();

	try {
		$responseData = $clsLog->getLogList();
		Logger::log('Log List complete');
		$response['success'] = 1;
		$response['data'] = $responseData;
	} catch (DBException $e) {
		$response['error'] = 1;
		$response['message'] = $e->getMessage();
	}
	return $response;
}

// Request Handler
$response = array();
if (Sessions::isValidSession()) {
	if (isset($_GET['action'])) {
	    $action = $_GET['action'];
	   	if ($action == 'loglist') {
	        Logger::log('Processing list of log...');
	        $response = logList();
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

