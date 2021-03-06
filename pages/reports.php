<div data-role="page" id="reports_page">
  <div data-role="header" data-add-back-btn="true" data-theme="b">
    <a href="#left_menu2" class="ui-btn-left" data-icon="grid" data-iconpos="notext">Menu</a>
    <h1>Reports Page</h1>
  </div>
  <div id="total_att_chart"></div>
  <div class="ui-bar ui-bar-a"><h3>Reports</h3></div>
  <div class="ui-body ui-body-a">
    <ul data-role="listview" id="testlv">
      <li><a href="#attendance_page"  id="attendance_button"    class="ui-btn ui-icon-bullets ui-btn-icon-right">Attendance Table</a></li>
      <li><a href="#checkins_page"    id="checkins_button"      class="ui-btn ui-icon-bullets ui-btn-icon-right">Checkins Each Day</a></li>
      <li><a href="#stale_users_page" id="stale_users_button"   class="ui-btn ui-icon-bullets ui-btn-icon-right">Inactive Students</a></li>
      <li><a href="#all_user_history" id="users_history_button" class="ui-btn ui-icon-bullets ui-btn-icon-right">All Students' Histories</a></li>
      <li><a href="#user_info_page"   id="user_info_button"     class="ui-btn ui-icon-bullets ui-btn-icon-right">Show Student Info</a></li>
    </ul>
  </div>

  <!-- menu panel -->
  <div data-role="panel" id="left_menu2" data-position="left" data-display="overlay" >
    <ul data-role="listview">
      <li><a href="#home"         data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-grid ui-btn-inline ui-btn-mini">Home</a></li>
      <?php if(isset($_SESSION["user"])){ ?>
      <li><a href="#reports_page"   data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-info ui-btn-inline ui-btn-mini">Reports</a></li>
      <?php } ?>
      <li><a href="#settings_page"  data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-edit ui-btn-inline ui-btn-mini">Settings</a></li>
    </ul>
  </div>
  <!-- /menu panel -->
  
</div>
