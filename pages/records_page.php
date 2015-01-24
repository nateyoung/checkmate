<div data-role="page" id="records_page">
  <div data-role="header" data-add-back-btn="true" data-theme="b">
    <a href="#left_menu5" class="ui-btn-left" data-icon="grid" data-iconpos="notext">Menu</a>
    <h1>Students</h1>
  </div>
  <div role="main" class="ui-content">
    <?php
    if(isset($_SESSION["user"]))
    { ?>
    <div class="student-iframe-wrapper">
      <iframe src="admin/tables/attendance.html"></iframe>
    </div>
    <?php
    }
    ?>
  </div>

  <!-- menu panel -->
  <div data-role="panel" id="left_menu5" data-position="left" data-display="overlay" >
    <ul data-role="listview">
      <li><a href="#home"           data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-grid ui-btn-inline ui-btn-mini">Home</a></li>
      <!-- Only show reports for logged in user -->
      <?php if(isset($_SESSION["user"])){ ?>
      <li><a href="#reports_page"   data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-info    ui-btn-inline ui-btn-mini">Reports</a></li>
      <li><a href="#students_page"  data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-user    ui-btn-inline ui-btn-mini">Students</a></li>
      <li><a href="#sg_page"        data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-user    ui-btn-inline ui-btn-mini">Small Groups</a></li>
      <li><a href="#records_page"   data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-bullets ui-btn-inline ui-btn-mini">Records</a></li>
      <?php } ?>
      <li><a href="#settings_page"  data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-edit ui-btn-inline ui-btn-mini">Settings</a></li>
    </ul>
  </div>
  <!-- /menu panel -->
  
</div>
