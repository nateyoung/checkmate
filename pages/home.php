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
        <form id="visitor_form" class="validate-form">
          <div style="padding:10px 20px;">
            <h3>Please Register</h3>
            <fieldset>
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
              <label for="gradeSelect" class="ui-hidden-accessible">Grade</label>
              <select name="grade" id="gradeSelect">
                <option value="6">6th grade</option>
                <option value="7">7th grade</option>
                <option value="8">8th grade</option>
                <option value="9">9th grade</option>
                <option value="10">10th grade</option>
                <option value="11">11th grade</option>
                <option value="12">12th grade</option>
                <option value="0">none</option>
              </select>
    
              <label for="addrStreet" class="ui-hidden-accessible">addrStreet</label>
              <input type="text" name="addrStreet" id="addrStreet" value="" placeholder="street address" required>
              <label for="addrCity" class="ui-hidden-accessible">addrCity</label>
              <input type="text" name="addrCity" id="addrCity" value="" placeholder="city" required>
              <label for="addrState" class="ui-hidden-accessible">addrState</label>
              <input type="text" name="addrState" id="addrState" value="" placeholder="state" required>
              <label for="addrZip" class="ui-hidden-accessible">addrZip</label>
              <input type="number" name="addrZip" id="addrZip" value="" placeholder="zip" required>
              <label for="email" class="ui-hidden-accessible">email</label>
              <input type="email" name="email" id="email" value="" placeholder="email">
              <label for="cphone" class="ui-hidden-accessible">cell phone</label>
              <input type="tel" name="cphone" id="cphone" value="" placeholder="cellphone">
              <label for="bday">birthday:</label>
              <input type="date" name="bday" id="bday" value="" placeholder="birthday">
              
              <button type="submit" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check">Register</button>
            </fieldset>
          </div>
        </form>
    </div>
  </div>
  
  <!-- /content -->
  
  <!-- panel -->
  <div data-role="panel" id="rightpanel" data-position="right" data-display="overlay" >
    <div id="output_text"></div><br />
    <ul data-role="listview" id="action_lv">
      <li><a href="#" class="ui-btn ui-shadow ui-corner-all ui-btn-inline ui-btn-mini">Check in</a></li>
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