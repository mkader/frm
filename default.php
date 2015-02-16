<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
	require_once('dnsconfig.php');
	require_once('/lib/sessions.class.php');
	include("/html/head.htm");
?>
<body onload="<?php if (Sessions::isValidSession()) echo "loadContent()"; ?>">
    <div>
        <?php include("header.php");?>
    	<div id="content" class="middle">
<?php if (!Sessions::isValidSession()) 	 include("login.php"); ?>
		</div>
    	<?php include("html/footer.htm");?>
    </div>
</body>
</html>

