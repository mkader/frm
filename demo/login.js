
$(function(){

	$("#phone").mask("(999) 999-9999");

	$("#login").click(function(event ) {
		var username = $( "#username" ).val();
		var password = $( "#password" ).val();
		var securitycode = $( "#securitycode" ).val();
		if ( username.length == 0 ||  password.length == 0 || securitycode.length == 0) {
			common.errorSpan(event, "#error", "Invalid login. Please Re-try.");
		} else {

						location.href="default.html";


		}
	});

	$("#logout").click(function(event ) {
		common.ajaxCall(true, "get", "users.html", {action: 'logout'},
			function( response ) {
				var res = common.jsonParse(response);
				if (res['error']) {
					common.errorAlert(res['message']);
					event.preventDefault();
				}else if (res['success']) {
					location.href="default.html";
				}
			},
			function( response ) {
				common.errorAlert(response);
				event.preventDefault();
			}
		)
	});

});
