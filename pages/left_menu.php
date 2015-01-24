<div data-role="panel" id="left_menu" data-position="left" data-display="overlay" >
  <ul data-role="listview">
    <li><a href="#home"           data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-grid ui-btn-inline ui-btn-mini">Home</a></li>
    <!-- Only show reports for logged in user -->
    <?php if(isset($_SESSION["user"])){ ?>
    <li><a href="#reports_page"   data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-info ui-btn-inline ui-btn-mini">Reports</a></li>
    <?php } ?>
    <li><a href="#settings_page"  data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-edit ui-btn-inline ui-btn-mini">Settings</a></li>
  </ul>
</div>