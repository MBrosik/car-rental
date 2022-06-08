<?php

include_once("../../utils/db.php");
include_once("../../utils/functions.php");

session_start();

if (isset($_SESSION["id"], $_POST["id"], $_POST["start_time"], $_POST["end_time"])) {
   $query = mysqli_query($connect, "SELECT * FROM users WHERE user_type_id IN (1,2) AND id = $_SESSION[id]");
   $users = mysqli_fetch_all($query, MYSQLI_ASSOC);

   if (count($users) == 1) {


      funcs::sql_bind(
         $connect,
         "UPDATE reservation
         SET  start_time=?, end_time=?
         WHERE id=?",
         "sss",         
         $_POST["start_time"],
         $_POST["end_time"],
         $_POST["id"]
      );
   }
}