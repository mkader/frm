
$(function(){
	
	$("#phone").mask("(999) 999-9999"); 
	
	$("#login").click(function(event ) {
		var username = $( "#username" ).val();
		var password = $( "#password" ).val();
		var securitycode = $( "#securitycode" ).val();
		if ( username.length == 0 ||  password.length == 0 || securitycode.length == 0) {
			common.errorSpan(event, "#error", "Invalid login. Please Re-try.");
		} else {
			var data = {
					action: 'login',
					username: username,
					password: password,
					securitycode: securitycode,
			};
			common.ajaxCall(true, "post", "users.php", data,
				function( response ) {
					var res = common.JSONParse(response);
					if (res['error']) {
						common.errorSpan(event, "#error", res['message']);
					}else if (res['success']) {
						location.href="default.php";
					}
				},
				function( response ) {
					common.errorSpan(event, "#error", response);
				}
			)
		}
	});

	$("#logout").click(function(event ) {
		common.ajaxCall(true, "get", "users.php", {action: 'logout'},
			function( response ) {
				var res = common.JSONParse(response);
				if (res['error']) {
					common.errorAlert(event, res['message']);
				}else if (res['success']) {
					location.href="default.php";
				}
			},
			function( response ) {
				common.errorAlert(event, response);
			}
		)
	});
	
	
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
			common.ajaxCall(true, "post", "users1.php", data,
				function( response ) {
					var res = common.JSONParse(response);
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
