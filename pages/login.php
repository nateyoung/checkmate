<?php
require "db_config.php";
include('../log4php/Logger.php');
Logger::configure('../logs/logconfig.xml');

$username=$_POST['username']; 
$password=$_POST['password']; 
$query="SELECT username,admin
FROM users
WHERE users.username=\"$username\" AND users.password=\"$password\"";
    
  $result = $mysqli->query($query) or die(mysql_error());

  if(mysqli_num_rows($result))
  {
    $row = mysqli_fetch_array($result, true);
    //error_log("nate:" . $row['admin']);
    session_start();
    $_SESSION["user"] = $username;
    $_SESSION["admin"] = $row['admin'];

    // log user login
    $log = Logger::getLogger('loginLogger');;
    $log->info(getcwd().": ".$username." logged in from ".$_SERVER['REMOTE_ADDR']);

  }
  else
  {
    session_start();
    unset($_SESSION["user"]);
    unset($_SESSION["admin"]);
  }

  /* free result set */
  $mysqli->close();
?> 
