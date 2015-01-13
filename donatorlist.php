<table id="jqGrid"></table>
<div id="jqGridPager"></div>

  <script>
  $(function() {
    $( "#tabs" ).tabs({
      beforeLoad: function( event, ui ) {
        debugger;
        updateContent("pledgelist.php");
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
    <li><a href="#tabs-1">Pledges</a></li>
    <li><a href="pledgelist.php">Payments</a></li>
    <li><a href="ajax/content2.html">Tab 2</a></li>
    <li><a href="ajax/content3-slow.php">Tab 3 (slow)</a></li>
    <li><a href="ajax/content4-broken.php">Tab 4 (broken)</a></li>
  </ul>
  <div id="tabs-1">
    <p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. 
    Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. 
    Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend 
    adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. 
    Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. 
    Nunc tristique tempus lectus.</p>
  </div>
</div>
 

<script type="text/javascript" src="js/donators.js" ></script>
