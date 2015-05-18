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
	<li><a target="self" href="report/remainder.php">Remainder Letter-PDF</a></li>
</ol>
</div>