$(function(){
	$("#menu-bar li").on("click", function(){
		$("#menu-bar li").removeClass( "menuClicked" );
		$(this).addClass( "menuClicked" );
		var menu = $(this).text().toLowerCase();
		if (menu=="my profile") updateContent("myprofile.php");
		else if (menu=="user")  updateContent("userlist.php");
		else if (menu=="event") updateContent("eventlist.php");
		else if (menu=="pledge") updateContent("pledgelist.php");
	});
})	
function loadContent() {
	$( "#menu-bar li" ).first().addClass( "menuClicked" );
	updateContent("myprofile.php");
}

function updateContent(url) {
	common.ajaxCall("get", url, null, 
		function( response ) { 
			$('#content').html(response)
		}, 
		function( response ) {
			alert("Error: " + response);
	});
}
