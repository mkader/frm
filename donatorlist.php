<table id="jqGrid"></table>
<div id="jqGridPager"></div>

  <script>
  $(function() {
    $( "#tabs" ).tabs({
      beforeLoad: function( event, ui ) {
        debugger;
        updateContent("pledgelist.php", "#pledgelistid");
        /*ui.jqXHR.error(function() {
          ui.panel.html(
            "Couldn't load this tab. We'll try to fix this as soon as possible. " +
            "If this wouldn't be a demo." );
        });*/
      }
    });
  });
  </script>
  
 
<br>
<div id="tabs" style="width:900px">
  <ul>
    <li><a href="#pledgelistid">Pledges</a></li>
    <li><a href="#tabs-1">Payments</a></li>
    <li><a href="#tabs-2">Tab 2</a></li>
    <li><a href="#tabs-3">Tab 3 (slow)</a></li>
    <li><a href="#tabs-4">Tab 4 (broken)</a></li>
  </ul>
  <idv id="pledgelistid">
  	<script>
		updateContent("pledgelist.php", "#pledgelistid");
	</script>
  </div>
</div>
 

<script type="text/javascript" src="js/donators.js" ></script>
