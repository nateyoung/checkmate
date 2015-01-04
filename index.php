<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/dygraph/1.1.0/dygraph-combined.js"></script>
<script src="js/jquery_checkmate.js" type="text/javascript"> </script>
<link rel="stylesheet" type="text/css" href="css/checkmate.css" />

<!-- Gritter CSS & js -->
<link rel="stylesheet" type="text/css" href="css/jquery.gritter.css" />
<script src="js/jquery.gritter.js" type="text/javascript"> </script>

<style type="text/css">
</style>

<?php
  session_start();
?>

<body>
  <!-- ########################################################################################################################################################## -->
  <!-- ####### Home page                                                    ##################################################################################### -->
  <!-- ########################################################################################################################################################## -->
  
  <div data-role="page" id="home" data-theme="b" class="ui-collision-home">
    
    <!-- header -->
    <div data-role="header" style="overflow:hidden;">
      <a href="#left_menu" class="ui-btn-left" data-icon="grid" data-iconpos="notext">Menu</a>
      <h1>Check in</h1>
    </div>
    <!-- /header -->
    
    <!-- content -->
    <div role="content" id="main">
      <form class="ui-filterable">
        <input id="filter-for-listview" data-type="search" placeholder="Enter your name..." data-theme="a">
      </form>
    <ul id="users_lv" data-role="listview" data-inset="true" data-filter="true" data-input="#filter-for-listview"></ul>
    <a href="#popupLogin" data-rel="popup" data-position-to="window" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-icon-user ui-btn-icon-left ui-btn-b" data-transition="pop">I'm new...</a>
    <div data-role="popup" id="popupLogin" data-theme="b" class="ui-corner-all">
      <!-- Visitor form -->
      <form id="visitor_form">
        <div style="padding:10px 20px;">
          <h3>Please Register</h3>
          <label for="fn" class="ui-hidden-accessible">First Name</label>
          <input type="text" name="firstname" id="fn" value="" placeholder="First Name" required>
          <label for="mn" class="ui-hidden-accessible">Middle Name</label>
          <input type="text" name="middlename" id="mn" value="" placeholder="Middle Name">
          <label for="ln" class="ui-hidden-accessible">Last Name</label>
          <input type="text" name="lastname" id="ln" value="" placeholder="Last Name" required>
          <label for="gender_select" class="ui-hidden-accessible">Gender</label>
          <select name="gender" id="gender_select">
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>
          <label for="grade_select" class="ui-hidden-accessible">Grade</label>
          <select name="grade" id="grade_select">
            <option value="6">6th grade</option>
            <option value="7">7th grade</option>
            <option value="8">8th grade</option>
            <option value="9">9th grade</option>
            <option value="10">10th grade</option>
            <option value="11">11th grade</option>
            <option value="12">12th grade</option>
          </select>
          <label for="email" class="ui-hidden-accessible">email</label>
          <input type="email" name="email" id="email" value="" placeholder="email">
          <label for="cphone" class="ui-hidden-accessible">cell phone</label>
          <input type="tel" name="cphone" id="cphone" value="" placeholder="cellphone">
          <label for="bday">birthday:</label>
          <input type="date" name="bday" id="bday" value="" placeholder="birthday">
          
          <button type="submit" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check">Register</button>
        </div>
      </form>
    </div>
  </div>
  
  <!-- /content -->
  
  <!-- footer -->
  <div data-rol="footer">
    <!-- <a href="#reports_page" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-info ui-btn-inline ui-btn-mini">Reports</a> -->
    <!-- <div class="sliderContainer"> -->
    <!-- <div id="dateSliderExample"></div> -->
    <!-- </div> -->
  </div>
  <!-- /footer -->
  
  <!-- panel -->
  <div data-role="panel" id="rightpanel" data-position="right" data-display="overlay" >
    <div id="output_text"></div><br />
    <ul data-role="listview" id="action_lv">
      <li><a href="#" class="ui-btn ui-shadow ui-corner-all ui-btn-inline ui-btn-mini">Check in</a></li>
      <!-- <li><a href="#" class="ui-btn ui-shadow ui-corner-all ui-btn-inline ui-btn-mini">Show History</a></li> -->
      <li><a href="#user_history" class="ui-btn ui-corner-all ui-shadow ui-btn-inline">Show History</a></li>
    </ul>
    
  </div>
  <!-- /panel -->
  
  <!-- panel -->
  <div data-role="panel" id="rightpanel_visitor" data-position="right" data-display="overlay" >
    <ul data-role="listview">
      <li><a href="#" class="ui-btn ui-shadow ui-corner-all ui-btn-inline ui-btn-mini">Create profile</a></li>
    </ul>
  </div>
  <!-- /panel -->
  
  <!-- menu panel -->
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
  <!-- /menu panel -->
  
</div>

<!-- ########################################################################################################################################################## -->
<!-- ####### Reports page                                                 ##################################################################################### -->
<!-- ########################################################################################################################################################## -->
<div data-role="page" id="reports_page">
  <div data-role="header" data-add-back-btn="true" data-theme="b">
    <a href="#left_menu2" class="ui-btn-left" data-icon="grid" data-iconpos="notext">Menu</a>
    <h1>Reports Page</h1>
  </div>
  <div id="chart_placeholder"></div>
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

<!-- ########################################################################################################################################################## -->
<!-- ####### Attendance page                                              ##################################################################################### -->
<!-- ########################################################################################################################################################## -->
<div data-role="page" id="attendance_page">
  <div data-role="header" data-add-back-btn="true" data-theme="b">
    <a href="#home" class="ui-btn-right" data-icon="home" data-iconpos="notext" data-direction="reverse">Home</a>
    <h1>Attendance Records - Summary</h1>
    <div data-role="navbar">
      <ul>
        <li>
          <label for="daterange_att">Date Range</label>
          <select name="daterange_att" id="daterange_att">
            <option value="%">All</option>
            <option value="1 MONTH">Month</option>
            <option value="3 MONTH">3 Months</option>
            <option value="6 MONTH">6 Months</option>
            <option value="1 YEAR">1 Year</option>
          </select>
        </li>
        <li>
          <label for="sgid_att">Small Group ID</label>
          <select name="sgid_att" id="sgid_att">
          </select>
        </li>
        <li>
          <label for="gradyear_att">Grade</label>
          <select name="gradyear_att" id="gradyear_att">
            <option value="%">All</option>
            <option value="2021">6</option>
            <option value="2020">7</option>
            <option value="2019">8</option>
            <option value="2018">9</option>
            <option value="2017">10</option>
            <option value="2016">11</option>
            <option value="2015">12</option>
          </select>
        </li>
        <li>
          <label for="gender_att">Gender</label>
          <select name="gender_att" id="gender_att">
            <option value="%">All</option>
            <option value="m">Male</option>
            <option value="f">Female</option>
          </select>
        </li>
      </ul>
    </div>
  </div>
  <div role="main" class="ui-content">
    <table data-role="table" id="attendance_table" data-mode="reflow" class="ui-responsive table-stroke">
    </table>
  </div>
</div>

<!-- ########################################################################################################################################################## -->
<!-- ####### Stale Users page                                             ##################################################################################### -->
<!-- ########################################################################################################################################################## -->
<div data-role="page" id="stale_users_page">
  <div data-role="header" data-add-back-btn="true" data-theme="b">
    <a href="#home" class="ui-btn-right" data-icon="home" data-iconpos="notext" data-direction="reverse">Home</a>
    <h1>Inactive Students Report</h1>
    <div data-role="navbar">
      <ul>
        <li>
          <label for="daterange_stale">Inactive for</label>
          <select name="daterange_stale" id="daterange_stale">
            <option value="1 WEEK">1 Week</option>
            <option value="1 MONTH">1 Month</option>
            <option value="3 MONTH">3 Months</option>
            <option value="6 MONTH">6 Months</option>
            <option value="1 YEAR">1 Year</option>
          </select>
        </li>
        <li>
          <label for="sgid_stale">Small Group ID</label>
          <select name="sgid_stale" id="sgid_stale">
          </select>
        </li>
        <li>
          <label for="gradyear_stale">Grade</label>
          <select name="gradyear_stale" id="gradyear_stale">
            <option value="%">All</option>
            <option value="2021">6</option>
            <option value="2020">7</option>
            <option value="2019">8</option>
            <option value="2018">9</option>
            <option value="2017">10</option>
            <option value="2016">11</option>
            <option value="2015">12</option>
          </select>
        </li>
        <li>
          <label for="gender_stale">Gender</label>
          <select name="gender_stale" id="gender_stale">
            <option value="%">All</option>
            <option value="m">Male</option>
            <option value="f">Female</option>
          </select>
        </li>
      </ul>
    </div>
  </div>
  <div role="main" class="ui-content">
    <table data-role="table" id="stale_users_table" data-mode="reflow" class="ui-responsive table-stroke">
    </table>
  </div>
  <div role="footer">
  </div>
</div>

<!-- ########################################################################################################################################################## -->
<!-- ####### Checkins page                                                ##################################################################################### -->
<!-- ########################################################################################################################################################## -->
<div data-role="page" id="checkins_page">
  <div data-role="header" data-add-back-btn="true" data-theme="b">
    <a href="#home" class="ui-btn-right" data-icon="home" data-iconpos="notext" data-direction="reverse">Home</a>
    <h1>Checkin Summary</h1>
    <div data-role="navbar">
      <ul>
        <li>
          <label for="daterange_ci">Date Range</label>
          <select name="daterange_ci" id="daterange_ci">
            <option value="1 WEEK">1 Week</option>
            <option value="1 MONTH">1 Month</option>
            <option value="3 MONTH">3 Months</option>
            <option value="6 MONTH">6 Months</option>
            <option value="1 YEAR">1 Year</option>
          </select>
        </li>
        <li>
          <label for="sgid_ci">Small Group ID</label>
          <select name="sgid_ci" id="sgid_ci">
          </select>
        </li>
        <li>
          <label for="gradyear_ci">Grade</label>
          <select name="gradyear_ci" id="gradyear_ci">
            <option value="%">All</option>
            <option value="2021">6</option>
            <option value="2020">7</option>
            <option value="2019">8</option>
            <option value="2018">9</option>
            <option value="2017">10</option>
            <option value="2016">11</option>
            <option value="2015">12</option>
          </select>
        </li>
        <li>
          <label for="gender_ci">Gender</label>
          <select name="gender_ci" id="gender_ci">
            <option value="%">All</option>
            <option value="m">Male</option>
            <option value="f">Female</option>
          </select>
        </li>
      </ul>
    </div>
  </div>
  <div role="main" class="ui-content">
    <ul data-role="listview" id="lv_attendance">
    </ul>
  </div>
  <div role="footer">
  </div>
</div>

<!-- ########################################################################################################################################################## -->
<!-- ####### User History page                                            ##################################################################################### -->
<!-- ########################################################################################################################################################## -->
<div data-role="page" id="user_history">
  <div data-role="header" data-add-back-btn="true" data-theme="b">
    <a href="#home" class="ui-btn-right" data-icon="home" data-iconpos="notext" data-direction="reverse">Home</a>
    <h1>User History</h1>
    <div id="user_history_opt"></div>
  </div>
  <div role="main" class="ui-content">
    <ul data-role="listview" data-inset="true" id="user_attendance_lv">
    </ul>
  </div>
  <div role="footer">
  </div>
</div>

<!-- ########################################################################################################################################################## -->
<!-- ####### All User History page                                        ##################################################################################### -->
<!-- ########################################################################################################################################################## -->
<div data-role="page" id="all_user_history">
  <div data-role="header" data-add-back-btn="true" data-theme="b">
    <a href="#home" class="ui-btn-right" data-icon="home" data-iconpos="notext" data-direction="reverse">Home</a>
    <h1>All Users' History</h1>
    <div data-role="navbar">
      <ul>
        <li>
          <label for="daterange_uh">Date Range</label>
          <select name="daterange_uh" id="daterange_uh">
            <option value="1 WEEK">1 Week</option>
            <option value="1 MONTH">1 Month</option>
            <option value="3 MONTH">3 Months</option>
            <option value="6 MONTH">6 Months</option>
            <option value="1 YEAR">1 Year</option>
          </select>
        </li>
        <li>
          <label for="sgid_uh">Small Group ID</label>
          <select name="sgid_uh" id="sgid_uh">
          </select>
        </li>
        <li>
          <label for="gradyear_uh">Grade</label>
          <select name="gradyear_uh" id="gradyear_uh">
            <option value="%">All</option>
            <option value="2021">6</option>
            <option value="2020">7</option>
            <option value="2019">8</option>
            <option value="2018">9</option>
            <option value="2017">10</option>
            <option value="2016">11</option>
            <option value="2015">12</option>
          </select>
        </li>
        <li>
          <label for="gender_uh">Gender</label>
          <select name="gender_uh" id="gender_uh">
            <option value="%">All</option>
            <option value="m">Male</option>
            <option value="f">Female</option>
          </select>
        </li>
      </ul>
    </div>
  </div>
  <div role="main" class="ui-content">
    <ul data-role="listview" data-inset="true" id="all_user_attendance_lv">
    </ul>
  </div>
  <div role="footer">
  </div>
</div>

<!-- ########################################################################################################################################################## -->
<!-- ####### User Info page                                               ##################################################################################### -->
<!-- ########################################################################################################################################################## -->
<div data-role="page" id="user_info_page">
  <div data-role="header" data-add-back-btn="true" data-theme="b">
    <a href="#home" class="ui-btn-right" data-icon="home" data-iconpos="notext" data-direction="reverse">Home</a>
    <h1>User Info</h1>
    <div data-role="navbar">
      <ul>
        <li>
          <label for="daterange_ui">Date Range</label>
          <select name="daterange_ui" id="daterange_ui">
            <option value="1 WEEK">1 Week</option>
            <option value="1 MONTH">1 Month</option>
            <option value="3 MONTH">3 Months</option>
            <option value="6 MONTH">6 Months</option>
            <option value="1 YEAR">1 Year</option>
          </select>
        </li>
        <li>
          <label for="sgid_ui">Small Group ID</label>
          <select name="sgid_ui" id="sgid_ui">
          </select>
        </li>
        <li>
          <label for="gradyear_ui">Grade</label>
          <select name="gradyear_ui" id="gradyear_ui">
            <option value="%">All</option>
            <option value="2021">6</option>
            <option value="2020">7</option>
            <option value="2019">8</option>
            <option value="2018">9</option>
            <option value="2017">10</option>
            <option value="2016">11</option>
            <option value="2015">12</option>
          </select>
        </li>
        <li>
          <label for="gender_ui">Gender</label>
          <select name="gender_ui" id="gender_ui">
            <option value="%">All</option>
            <option value="m">Male</option>
            <option value="f">Female</option>
          </select>
        </li>
      </ul>
    </div>
  </div>
  <div role="main" class="ui-content">
    <table data-role="table" id="user_info_table" data-mode="reflow" class="ui-responsive table-stroke">
    </table>
  </div>
  <div role="footer">
  </div>
</div>

<!-- ########################################################################################################################################################## -->
<!-- ####### Settings page                                                ##################################################################################### -->
<!-- ########################################################################################################################################################## -->
<div data-role="page" id="settings_page">
  <div data-role="header" data-add-back-btn="true" data-theme="b">
    <a href="#left_menu3" class="ui-btn-left" data-icon="grid" data-iconpos="notext">Menu</a>
    <h1>Settings</h1>
  </div>
  <div role="main" class="ui-content">
    <?php
    if(!isset($_SESSION["user"]))
    { ?>
    <form method="post" action="#settings_page" data-ajax="false" id="login_form">
      <label for="username" class="ui-hidden-accessible">Username:</label>
      <input type="text" name="username" id="username" placeholder="Username..." data-clear-btn="true">
      <label for="password" class="ui-hidden-accessible">Password:</label>
      <input type="password" name="password" id="password" placeholder="Password..." data-clear-btn="true">
      <input type="submit" data-inline="true" value="Submit" data-theme="b">
    </form>
    <?php
    }
    else
    {
    ?>
    <form method="post" action="#settings_page" data-ajax="false" id="logout_form">
      <input type="submit" data-inline="true" value="Logout" data-theme="b">
    </form>
    <?php
    }
    ?>
  </div>
  <div role="footer">
    <?php
    if(isset($_SESSION["user"]))
    echo "user " . $_SESSION["user"] . " logged in";
    else
    {
    echo "No user logged in. To view reports, log in";
    }
    ?>
    
  </div>
  
  <!-- menu panel -->
  <div data-role="panel" id="left_menu3" data-position="left" data-display="overlay" >
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
</body>