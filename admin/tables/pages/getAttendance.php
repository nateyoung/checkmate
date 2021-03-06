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
require_once("../../../pages/db_config.php");

// Database connection
$mysqli = mysqli_init();
$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
$mysqli->real_connect($hostname,$username,$password,$databasename); 
                    
// create a new EditableGrid object
$grid = new EditableGrid();

function get_sgids() {
  $rows = array();

  return $rows;
}

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


/* 
*  Add columns. The first argument of addColumn is the name of the field in the databse. 
*  The second argument is the label that will be displayed in the header
*/

$grid->addColumn('firstname', 'First Name', 'string', NULL, false);  
$grid->addColumn('lastname', 'Last Name', 'string', NULL, false);  
$grid->addColumn('date', 'Date', 'date', NULL, false);  


// $grid->addColumn('age', 'Age', 'integer');  
// $grid->addColumn('height', 'Height', 'float');  
// /* The column id_country and id_continent will show a list of all available countries and continents. So, we select all rows from the tables */
// $grid->addColumn('id_continent', 'Continent', 'string' , fetch_pairs($mysqli,'SELECT id, name FROM continent'),true);  
// $grid->addColumn('id_country', 'Country', 'string', fetch_pairs($mysqli,'SELECT id, name FROM country'),true );  
// $grid->addColumn('email', 'Email', 'email');                                               
// $grid->addColumn('freelance', 'Freelance', 'boolean');  
// $grid->addColumn('lastvisit', 'Lastvisit', 'date');  
// $grid->addColumn('website', 'Website', 'string');  
                                                                       
$result = $mysqli->query('SELECT DISTINCT DATE_FORMAT(DATE(attendance.date), "%Y-%c-%d (%a)") as date,
  students.firstname as firstname,
  students.lastname as lastname
  FROM students JOIN attendance
  ON (students.uid = attendance.uid)
  ORDER BY date,firstname,lastname');

$mysqli->close();

// send data to the browser
$grid->renderJSON($result);

