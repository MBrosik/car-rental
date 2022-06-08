<?php
include_once("../../utils/db.php");
include_once("../../utils/functions.php");

session_start();


if (isset($_SESSION["id"], $_POST["switchBool"], $_POST["timestamp"])) {
   $query = mysqli_query($connect, "SELECT * FROM users WHERE user_type_id IN (1,2) AND id = $_SESSION[id]");
   $users = mysqli_fetch_all($query, MYSQLI_ASSOC);

   if (count($users) == 1) {

      funcs::sql_bind($connect, "UPDATE variables SET value = ? WHERE name = 'time_difference'", "i", $_POST["timestamp"]);
      
      
      if($_POST["switchBool"] == "true"){
         mysqli_query($connect, "ALTER EVENT edit_date ENABLE");                  
      }
      else{
         mysqli_query($connect, "ALTER EVENT edit_date DISABLE");         
      }      

   }
}