<?php

include_once("../../utils/db.php");
include_once("../../utils/functions.php");

session_start();


if (isset($_SESSION["id"])) {
   $query = mysqli_query($connect, "SELECT * FROM users WHERE user_type_id IN (1,2) AND id = $_SESSION[id]");
   $users = mysqli_fetch_all($query, MYSQLI_ASSOC);

   if (count($users) == 1) {            
      $events = funcs::get_data($connect, "SHOW EVENTS WHERE Name = 'edit_date'");
      $time_difference = funcs::get_data($connect, "SELECT value FROM variables WHERE name='time_difference'")[0]["value"];      

      echo json_encode([
         "time_difference" => intval($time_difference), 
         "sheduler_active"=> $events[0]["Status"] == "ENABLED" ? true : false
      ]);      

   }
}