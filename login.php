<style>

h1
{
	font-size:20px;
	padding:0px 20px 20px;
	margin:0px;
	text-align:center;
}

input,textarea { padding:7px; font-size:14px !important; width:250px; }

p > label:first-child
{
	display: inline-block;
	font-weight: 700;
	margin-bottom: 5px;
	padding-right: 35px;
	text-align: right;
	width: 135px;
}

#container
{
	margin:0px auto;
	width:500px;
	background-color:#eee;
	padding:20px;	
	border-radius: 5px;
	clear: both;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	-webkit-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
	-moz-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
	box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
}
</style>

<div id="container">
	<h1>Log In</h1>
	<p>
		<label for="username">E-mail:</label>
		<input type="text" name="username" id="username" />
	</p>
	
	<p>
		<label for="password">Password:</label>
		<input type="password" name="password" id="password" />
	</p>

	<p>
		<label for="email">Security Code:</label>
		<input type="text" name="securitycode" id="securitycode" />
	</p>
	<p>
		<label></label>
		<span ><img src="captchaimage.php?width=156&height=35&characters=5" /></span>
	</p>
	
	<p>
		<label></label>
		<input type="button" value="Log In" id="login"/>
	</p>
	<p>
		<label></label>
		<span id="error" style="font-size:small;font-weight:bold;color:red"></span>
	</p>
</div>
