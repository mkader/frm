<div id="header" style="height:80px;">
	<table style="width:100%; height:72px;" border="0">
		<tr style ="width:100%">
			<td align ="left" style="box-shadow: 2px 2px 3px #222222;"><img src="img/mcclogo.png" id="imgSeal" style="margin-bottom: 0px;" />
<?php
if (Sessions::isValidSession()){
?>
				<br>
				<div id="header-bottom" style = "text-align:left;">
					<span id="User" style="color:white;font-weight:bold">Welcome <?php echo Sessions::loginName() ?></span>&nbsp;&nbsp;
					<span id="LogoffSpan"><button id="logout">Logoff</button></span>
				</div>
<?php } ?>
            </td>
			<td style="color:black;text-shadow: 1px 1px 1px;box-shadow: 2px 2px 3px #222222;">
				Masjid us-Sunnah
				<div id="header-top">FundRaise App</div>
			</td>
			<td width="50%">&nbsp;</td>
		</tr>
	</table>
</div>
<?php if (Sessions::isValidSession()){ ?>
<div>
	<ul id="menu-bar">
		<li>My Profile</li>
<?php if (Sessions::isLoginUserSuperAdmin()) { ?> <li>User</li> <?php } ?>
		<li>Event</li>
		<li>Donator</li>
		<!-- <li>Pledge</li>-->
		<li>Expense</li>
		<li>Template</li>
		<li>Report</li>
<?php if (Sessions::isLoginUserSuperAdmin()) { ?> <li>Log</li> <?php } ?>
	</ul>
</div>
<br />
<?php } ?>
