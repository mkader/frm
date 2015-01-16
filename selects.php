<?php
require_once('lib/include.php');

$db = new DB();
$select = new Selects($db);

if (isset($_GET['action'])) {
    $action = $_GET['action'];
   	if ($action == 'select') {
   		try {
			$jsonData = $select->generateJSON();
			Logger::JSON('select',$jsonData);
		} catch (DBException $e) {
    		echo $e->getMessage();
		}
    }
} else {
    echo 'There was no request action specified.';
}
?>

