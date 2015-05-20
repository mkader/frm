<script>
$('#payment_method_id').change(function() {
    var url = "report/remainder.php?event_id=" + $(this).val();
    var win = window.open(url, '_blank');
  	win.focus();
  	//return false;
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
			Complete List-HTML
			<a target="self" href="report/completelisthtml.php?year=<?php echo date('Y')-1?>"><?php echo date('Y')-1?></a>
			<a target="self" href="report/completelisthtml.php?year=<?php echo date('Y')?>"><?php echo date('Y')?></a>
	</li><br>
	<li>
				Payment Method List-HTML
				<a target="self" href="report/paymentmethodlisthtml.php?year=<?php echo date('Y')?>"><?php echo date('Y')?></a>
	</li><br>
	<li>
		<a target="self" href="report/remainder.php">Remainder Letter-PDF</a>
		<select id="payment_method_id">
			<option value="">Select</option>
<?php
	$activeEvents = $clsEvents->getActiveEventList();
	foreach ($activeEvents as $key_name => $key_value) {
		echo '<option value='.$key_value["id"]. '>'. $key_value["title"] .'</option>';
	}
?>
		</select>
</li>
</ol>
</div>
<?php
}
?>