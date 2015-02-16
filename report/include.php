<?php
	require_once('../dnsconfig.php');
	set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '../../lib/');
	require_once('db.class.php');
	require_once('dbexception.class.php');
	require_once('logger.class.php');
	require_once('sessions.class.php');
	require_once('users.class.php');
	require_once('events.class.php');
	require_once('pledges.class.php');
	require_once('donators.class.php');
	require_once('payments.class.php');
	require_once('expenses.class.php');
	require_once('selects.class.php');
	require_once('logs.class.php');
	require_once('commons.class.php');
	require_once('reports.class.php');
?>