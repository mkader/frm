$(function(){
	$("#menu-bar li").on("click", function(){
		var contentid ="#content";
		$("#menu-bar li").removeClass( "menuClicked" );
		$(this).addClass( "menuClicked" );
		var menu = $(this).text().toLowerCase();
		if (menu=="my profile") updateContent("myprofile.php", contentid);
		else if (menu=="user")  updateContent("userlist.php", contentid);
		else if (menu=="event") updateContent("eventlist.php", contentid);
		else if (menu=="pledge") updateContent("pledgelist.php", contentid);
		else if (menu=="donator") updateContent("donatorlist.php", contentid);
		else if (menu=="expense") updateContent("expenselist.php", contentid);
	});
})

function loadContent() {
	$( "#menu-bar li" ).first().addClass( "menuClicked" );
	updateContent("myprofile.php","#content");
}

function updateContent(url, contentid) {
	common.ajaxCall(true, "get", url, null,
		function( response ) {
			$(contentid).html(response)
		},
		function( response ) {
			alert("Error: " + response);
	});
}

var user_type_id =':;';
var user_type_value =':[All];';
var active_id ='';
var active_value =':[All];';
var payment_type_id =':;';
var payment_type_value =':[All];';
var payment_method_id =':;';
var payment_method_value =':[All];';

common.ajaxCall(false, "get", "json/select.json", null,
	function( response ) {
		user_type_id += response['user_type_id'][0];
		user_type_value += response['user_type_value'][0];
		active_id += response['user_type_id'][0];
		active_value += response['user_type_value'][0];
		payment_type_id += response['payment_type_id'][0];
		payment_type_value += response['payment_type_value'][0];
		payment_method_id += response['payment_method_id'][0];
		payment_method_value += response['payment_method_value'][0];
	},
	function( response ) {
		common.errorAlert(event, response.responseText);
	}
)

