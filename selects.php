<?php
require_once('lib/include.php');

$clsDB = new DB();
$clsSelects = new Selects($clsDB);

if (isset($_GET['action'])) {
    $action = $_GET['action'];
   	if ($action == 'select') {
   		try {
			$jsonData = $clsSelects->generateJSON();
			Logger::JSON('select',"{".$jsonData."}");
		} catch (DBException $e) {
    		echo $e->getMessage();
		}
    }
} else {
    echo 'There was no request action specified.';
}
?>

