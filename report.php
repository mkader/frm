<script>
$('#payment_method_id').change(function() {
	if ($(this).val()!="") {
    var url = "report/reminder.php?event_id=" + $(this).val();
    var win = window.open(url, '_blank');
  	win.focus();
	}
});
</script>
<?php
require_once('/lib/include.php');
if (Sessions::isValidSession()) {
	$clsDB = new DB();
	$clsEvents = new Events($clsDB);
?>
<div>
<ol>
	<li>
		Complete List-Excel
		<a href="report/completelist.php?year=<?php echo date('Y')-1?>"><?php echo date('Y')-1?></a>
		<a href="report/completelist.php?year=<?php echo date('Y')?>"><?php echo date('Y')?></a>
	</li><br>
	<li>
			List<br>
			&nbsp;&nbsp;Complete - HTML
			<a target="self" href="report/completelisthtml.php?year=<?php echo date('Y')-1?>"><?php echo date('Y')-1?></a>
			<a target="self" href="report/completelisthtml.php?year=<?php echo date('Y')?>"><?php echo date('Y')?></a>
			<br>&nbsp;&nbsp;<a target="self" href="report/reminderlisthtml.php">Reminder - HTML</a>
	</li><br>
	<li>
				Payment Method List-HTML
				<a target="self" href="report/paymentmethodlisthtml.php?year=<?php echo date('Y')?>"><?php echo date('Y')?></a>
	</li><br>
	<li>
		Reminder Letter&nbsp;
		&nbsp;[Pledge Reminder Template&nbsp;=>&nbsp;Operation&nbsp;<a href="template/Operation_PR_Template_Excel.docx">Word</a>
		&nbsp;<a href="template/Operation_PR_Template_Excel.docx">Excel</a>
		&nbsp;New Masjid&nbsp;<a href="template/New Masjid_PR_Template_word.docx">Word</a>
		&nbsp;<a href="template/New Masjid_PR_Template_Excel.docx">Excel</a>]
		<br>
<?php
	$activeEvents = $clsEvents->getActiveEventList();
	foreach ($activeEvents as $key_name => $key_value) {
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$key_value["title"]."&nbsp;&nbsp;<a target='self' href='report/reminder_excel.php?pledge_type_id=".$key_value["pledge_type_id"]."&event_id=".$key_value["id"] ."'> Excel</a>&nbsp;&nbsp;<a target='self' href='report/reminder_word.php?pledge_type_id=".$key_value["pledge_type_id"]."&event_id=".$key_value["id"] ."'> Word</a><br>";
		//echo '<option value='.$key_value["id"]. '>'. $key_value["title"] .'</option>';
	}
?>
	</li><br>
	<li>
		Donations Receipt
		<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Excel -
		<a target="self" href="report/donations_receipt_excel.php?year=<?php echo date('Y')-1?>"><?php echo date('Y')-1?></a>
		<a href="report/donations_receipt_excel.php?year=<?php echo date('Y')?>"><?php echo date('Y')?> </a>
		&nbsp;<a href="template/Donations_Receipt_Template_Excel.docx">Donations_Receipt_Template_Excel.docx</a>
		<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Word -
		<a target="self" href="report/donations_receipt_word.php?year=<?php echo date('Y')-1?>"><?php echo date('Y')-1?></a>
		<a href="report/donations_receipt_word.php?year=<?php echo date('Y')?>"><?php echo date('Y')?></a>
		&nbsp;<a href="template/Donations_Receipt_Template_Word.docx">Donations_Receipt_Template_Word.docx</a>
	</li><br>
</ol>
</div>
<?php
}
?>