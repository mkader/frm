<?php
require_once('lib/include.php');

$db = new DB();
$log = new Logs($db);

function loglist() {
	global $log;
	$response = array();

	try {
		$responseData = $log->getLogList();
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
	        $response = loglist();
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

