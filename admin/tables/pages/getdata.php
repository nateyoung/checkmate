<?php     


/*
 * examples/mysql/loaddata.php
 * 
 * This file is part of EditableGrid.
 * http://editablegrid.net
 *
 * Copyright (c) 2011 Webismymind SPRL
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://editablegrid.net/license
 */
                              


/**
 * This script loads data from the database and returns it to the js
 *
 */
       
require_once("EditableGrid.php");

$config = array(
  "db_name" => "youth",
  "db_user" => "root",
  "db_password" => "ohf83838",
  "db_host" => "localhost"
);                

/**
 * fetch_pairs is a simple method that transforms a mysqli_result object in an array.
 * It will be used to generate possible values for some columns.
*/
function fetch_pairs($mysqli,$query){
  if (!($res = $mysqli->query($query)))return FALSE;
  $rows = array();
  while ($row = $res->fetch_assoc()) {
    $first = true;
    $key = $value = null;
    foreach ($row as $val) {
      if ($first) { $key = $val; $first = false; }
      else { $value = $val; break; } 
    }
    $rows[$key] = $value;
  }
  return $rows;
}


// Database connection
$mysqli = mysqli_init();
$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
$mysqli->real_connect($config['db_host'],$config['db_user'],$config['db_password'],$config['db_name']); 
                    
// create a new EditableGrid object
$grid = new EditableGrid();

/* 
*  Add columns. The first argument of addColumn is the name of the field in the databse. 
*  The second argument is the label that will be displayed in the header
*/

$grid->addColumn('uid', 'UID', 'integer', NULL, false); 
$grid->addColumn('firstname', 'First Name', 'string');  
$grid->addColumn('middlename', 'Middle Name', 'string');  
$grid->addColumn('lastname', 'Last Name', 'string');  
$grid->addColumn('birthday', 'Birthday', 'date');  
$grid->addColumn('grad_year', 'Grad Year', 'string');  
$grid->addColumn('gender', 'Gender', 'string');  
$grid->addColumn('cellphone', 'Cellphone', 'string');  
$grid->addColumn('homephone', 'Homephone', 'string');  
$grid->addColumn('email', 'Email', 'email');  
$grid->addColumn('small_group_id', 'Small Group ID', 'string');  // TODO - populate w/ SGIDs
$grid->addColumn('addr_street', 'Street Addr', 'string');  
$grid->addColumn('addr_city', 'City', 'string');  
$grid->addColumn('addr_state', 'State', 'string');   
$grid->addColumn('addr_zip', 'Zip', 'string');  // TODO - fix to int or float?


// $grid->addColumn('age', 'Age', 'integer');  
// $grid->addColumn('height', 'Height', 'float');  
// /* The column id_country and id_continent will show a list of all available countries and continents. So, we select all rows from the tables */
// $grid->addColumn('id_continent', 'Continent', 'string' , fetch_pairs($mysqli,'SELECT id, name FROM continent'),true);  
// $grid->addColumn('id_country', 'Country', 'string', fetch_pairs($mysqli,'SELECT id, name FROM country'),true );  
// $grid->addColumn('email', 'Email', 'email');                                               
// $grid->addColumn('freelance', 'Freelance', 'boolean');  
// $grid->addColumn('lastvisit', 'Lastvisit', 'date');  
// $grid->addColumn('website', 'Website', 'string');  
                                                                       
$result = $mysqli->query('SELECT *, date_format(birthday, "%d/%m/%Y") as birthday FROM students ORDER BY firstname, lastname');
$mysqli->close();

// send data to the browser
$grid->renderJSON($result);

