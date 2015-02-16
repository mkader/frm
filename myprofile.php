<?php
require_once('/lib/include.php');
$name =Sessions::loginName();
$username = Sessions::loginUserName();
$id = Sessions::loginUserID();
$phone = Sessions::loginUserPhone();
?>

<script>
$(function(){

	$("#phone").mask("(999) 999-9999");

	$('#frmmyprofile').validate({
		rules: {password: {required: true, minlength: 6, maxlength: 10,} ,
			confirmpassword: {equalTo: "#password", minlength: 6, maxlength: 10,},
			phone: {required:true,}}
   });

	$("#updatemyprofile").click(function(event ) {
		if($("#frmmyprofile").valid()) {
			var password = $( "#password" ).val();
			var phone = $( "#phone" ).val();
			var data = {
					action: 'myprofileupdate',
					password: password,
					phone: phone,
			};
			common.ajaxCall(true, "post", "users.php", data,
				function( response ) {
					var res = common.jsonParse(response);
					if (res['error']) {
						common.errorSpan(event, "#error", res['message']);
					}else if (res['success']) {
						common.errorSpan(event, "#error", res['message']);
					}
				},
				function( response ) {
					common.errorSpan(event, "#error", response.statusText);
				}
			)
		}
	});
});
</script>
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
	margin:1px 1px 1px 1px;
	width:450px;
	background-color:#eee;
	padding:5px;
	border-radius: 5px;
	clear: both;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	-webkit-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
	-moz-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
	box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
}

label.error, span.error {
	font-size:small;
	font-weight:bold;
	margin-left: 170px;
	color: red;
	font-style: italic
}
input.error { border: 1px dotted red; }
</style>

<div id="container">
	<h1>My Profile</h1>
	<form id="frmmyprofile" name="frmmyprofile">
		<p>
			<label for="name">Name:</label>
			<input type="text" name="name" id="name" value="<?php echo $name ?>" readonly />
		</p>

		<p>
			<label for="username">E-mail:</label>
			<input type="text" name="email" id="email" value="<?php echo $username ?>" readonly />
		</p>
		<p>
			<label for="password">Password:</label>
			<input type="password" name="password" id="password" /><br>
		</p>
		<p>
			<label for="confirmpassword">Confirm Password:</label>
			<input type="password" name="confirmpassword" id="confirmpassword" />
		</p>
		<p>
			<label for="phone">Phone:</label>
			<input type="text" name="phone" id="phone" value="<?php echo $phone ?>"/>
		</p>
		<p>
			<label></label>
			<input type="button" name="updatemyprofile" value="Update" id="updatemyprofile"/>
			<span id="error" class="error"></span>
		</p>
	</form>
</div>
