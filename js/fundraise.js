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
