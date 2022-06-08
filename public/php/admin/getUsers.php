<?php

include_once("../utils/db.php");
include_once("../utils/functions.php");

session_start();


if (isset($_SESSION["id"])) {
   $query = mysqli_query($connect, "SELECT * FROM users WHERE user_type_id=1 AND id = $_SESSION[id]");
   $users = mysqli_fetch_all($query, MYSQLI_ASSOC);

   if (count($users) == 1) {
      $query = mysqli_query($connect, "SELECT users.id as id, users.login as login, user_types.type as user_type, users.activated as activated 
         FROM users INNER JOIN user_types ON user_types.id = users.user_type_id");
      $all_users = mysqli_fetch_all($query, MYSQLI_ASSOC);

      echo json_encode($all_users);

      header("Content-Type: application/json");
   }
}
