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
