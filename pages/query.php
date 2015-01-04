<?php
require "db_config.php";

function get_gradyear($gradyear)
{
  switch($gradyear)
  {
    case "6"  : return "2021" ;
    case "7"  : return "2020" ;
    case "8"  : return "2019" ;
    case "9"  : return "2018" ;
    case "10" : return "2017" ;
    case "11" : return "2016" ;
    case "12" : return "2015" ;
    default   : return "2015" ;
  }
}

// validate form vars
$uid          = isset($_REQUEST['uid'])           ? $_REQUEST['uid']                      : 0;
$pname        = isset($_REQUEST['pname'])         ? $_REQUEST['pname']                    : "asdf";
$fn           = isset($_REQUEST['fn'])            ? $_REQUEST['fn']                       : "";
$mn           = isset($_REQUEST['mn'])            ? $_REQUEST['mn']                       : "";
$ln           = isset($_REQUEST['ln'])            ? $_REQUEST['ln']                       : "";
$bday         = isset($_REQUEST['bday'])          ? $_REQUEST['bday']                     : "";
$gender       = isset($_REQUEST['gender'])        ? $_REQUEST['gender']                   : "";
$cell         = isset($_REQUEST['cell'])          ? $_REQUEST['cell']                     : "";
$homeph       = isset($_REQUEST['homeph'])        ? $_REQUEST['homeph']                   : "";
$email        = isset($_REQUEST['email'])         ? $_REQUEST['email']                    : "";
$addr_street  = isset($_REQUEST['addr_street'])   ? $_REQUEST['addr_street']              : "";
$addr_city    = isset($_REQUEST['addr_city'])     ? $_REQUEST['addr_city']                : "";
$addr_state   = isset($_REQUEST['addr_state'])    ? $_REQUEST['addr_state']               : "";
$addr_zip     = isset($_REQUEST['addr_zip'])      ? $_REQUEST['addr_zip']                 : "";
$gradyear     = isset($_REQUEST['gradyear'])      ? get_gradeyear($_REQUEST['gradyear'])  : "0000";

// report options
$daterange    = isset($_REQUEST['daterange'])     ? $_REQUEST['daterange']                : "0";
$sgid         = isset($_REQUEST['sgid'])          ? $_REQUEST['sgid']                     : "%";
$gender_rep   = isset($_REQUEST['gender_rep'])    ? $_REQUEST['gender_rep']               : "%";
$gradyear_rep = isset($_REQUEST['gradyear_rep'])  ? $_REQUEST['gradyear_rep']             : "%";



if(isset($_REQUEST['query']))
{
  $queries = [


// 0 - get number of unique names per date
"SELECT DATE_FORMAT( DATE( attendance.date ) ,  '%Y-%c-%d (%a)' ) AS days, COUNT( DISTINCT students.firstname, students.lastname ) AS attendees
FROM students
JOIN attendance ON students.uid = attendance.uid
WHERE 
  students.small_group_id LIKE '$sgid' AND
  students.gender LIKE '$gender_rep' AND
  students.grad_year LIKE '$gradyear_rep'
GROUP BY DATE( attendance.date )",

// 1 - get all unique names+dates ordered by date with subtotals
"SELECT DISTINCT DATE_FORMAT(DATE(attendance.date), '%Y-%c-%d (%a)') as days,
students.firstname as firstname,
students.lastname as lastname,
subtotals.subtotals
FROM students 
JOIN attendance ON students.uid = attendance.uid
JOIN (
    SELECT DATE(attendance.date) as dates,
    COUNT(DISTINCT students.firstname, students.lastname) as subtotals
    FROM students JOIN attendance
    ON students.uid = attendance.uid
    WHERE 
      students.small_group_id LIKE '$sgid' AND
      students.gender LIKE '$gender_rep' AND
      students.grad_year LIKE '$gradyear_rep'
    GROUP BY DATE(attendance.date)
) subtotals ON subtotals.dates = DATE(attendance.date)
WHERE 
  students.small_group_id LIKE '$sgid' AND
  students.gender LIKE '$gender_rep' AND
  students.grad_year LIKE '$gradyear_rep'
ORDER BY days,firstname,lastname",

// 2 - check in a user
"INSERT INTO attendance(uid) VALUES ($uid)",

// 3 - get list of students+uids
"SELECT uid,firstname,lastname 
FROM students
ORDER BY firstname,lastname",

// 4 - show user's checkin history
"SELECT DISTINCT DATE_FORMAT(DATE(attendance.date), '%Y-%c-%d (%a)') as days,
students.firstname as firstname,
students.lastname as lastname
FROM students JOIN attendance
ON (students.uid = attendance.uid) and (students.uid = $uid)
ORDER BY days",

// 5 - show user not checked in within last 1 day
"SELECT DATE_FORMAT(DATE(attendance.date), '%Y-%c-%d (%a)') as day,
students.firstname as firstname,
students.lastname as lastname
FROM students JOIN attendance
ON students.uid = attendance.uid
WHERE students.small_group_id LIKE '$sgid' AND
      students.gender LIKE '$gender_rep' AND
      students.grad_year LIKE '$gradyear_rep' AND
      attendance.date=(SELECT MAX(attendance.date) FROM attendance WHERE attendance.uid=students.uid) AND attendance.date<CURDATE()-INTERVAL $daterange",

// 6 - show all students' checkin histories
"SELECT DISTINCT DATE_FORMAT(DATE(attendance.date), '%Y-%c-%d (%a)') as days,
students.firstname as firstname,
students.lastname as lastname
FROM students JOIN attendance
ON (students.uid = attendance.uid)
WHERE 
  students.small_group_id LIKE '$sgid' AND
  students.gender LIKE '$gender_rep' AND
  students.grad_year LIKE '$gradyear_rep'
ORDER BY firstname,lastname,days",

// 7 - get number of unique names per date
// "SELECT DATE_FORMAT(DATE(attendance.date), '%Y') as year,
// DATE_FORMAT(DATE(attendance.date), '%c') as month,
// DATE_FORMAT(DATE(attendance.date), '%d') as day,
// COUNT(DISTINCT students.firstname, students.lastname) as attendees
// FROM students JOIN attendance
// ON students.uid = attendance.uid
// GROUP BY DATE(attendance.date)",
"SELECT DATE_FORMAT( DATE( attendance.date ) ,  '%Y/%c/%d' ) as date,
COUNT(DISTINCT students.firstname, students.lastname) as attendees
FROM students JOIN attendance
ON students.uid = attendance.uid
GROUP BY DATE(attendance.date)",

// 8 - get usernames starting with first few letters typed
"SELECT students.firstname as firstname,
students.lastname as lastname,
students.uid as uid
FROM students 
WHERE (INSTR(firstname, '{$pname}') > 0) OR (INSTR(lastname, '{$pname}') > 0)
ORDER BY firstname, lastname",

// 9 - show user information
"SELECT students.firstname, students.lastname, students.gender, students.birthday, students.grad_year,
students.cellphone, students.email, small_groups.sg_name,
  CASE
    WHEN students.grad_year = CURDATE() - INTERVAL 5 MONTH + INTERVAL 4 YEAR THEN 'Freshman'
    WHEN students.grad_year = CURDATE() - INTERVAL 5 MONTH + INTERVAL 3 YEAR THEN 'Sophomore'
    WHEN students.grad_year = CURDATE() - INTERVAL 5 MONTH + INTERVAL 2 YEAR THEN 'Junior'
    WHEN students.grad_year = CURDATE() - INTERVAL 5 MONTH + INTERVAL 1 YEAR THEN 'Senior'
    ELSE 'unknown'
  END AS school_year
FROM students JOIN small_groups
ON (students.small_group_id = small_groups.sg_id)
WHERE 
  students.small_group_id LIKE '$sgid' AND
  students.gender LIKE '$gender_rep' AND
  students.grad_year LIKE '$gradyear_rep'
ORDER BY students.firstname, students.lastname",

// 10 - register visitor
"INSERT INTO `youth`.`students` 
       (`firstname`, `middlename`, `lastname`, `birthday`, `grad_year`,  `gender`, `cellphone`,  `homephone`,  `email`,  `addr_street`,  `addr_city`, `addr_state`, `addr_zip`) 
VALUES ('$fn',       '$mn',        '$ln',      '$bday',    '$gradyear',  '$gender','$cell',      '$homeph',    '$email', '$addr_street', '$addr_city','$addr_state','$addr_zip');",

// 11 - get smallgroup IDs
"SELECT  `sg_id` ,  `sg_name` ,  `sg_leader_uid` 
FROM  `small_groups`
ORDER BY small_groups.sg_name",


];
  
  $result = $mysqli->query($queries[$_REQUEST['query']]) or die(mysql_error());
  
  // echo "result: $result";

  if($result)
  {
    $rows = array();
    while($row = mysqli_fetch_array($result, true)){
        $rows[] = $row; 
    };
  
    /* free result set */
    $mysqli->close();
  
    header('Content-Type: application/json');
    echo json_encode($rows);   
  }
};

?> 
