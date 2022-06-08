<?php

include_once("../../utils/db.php");
include_once("../../utils/functions.php");

session_start();

if (isset($_SESSION["id"], $_POST["id"])) {
   $query = mysqli_query($connect, "SELECT * FROM users WHERE user_type_id IN (1,2) AND id = $_SESSION[id]");
   $users = mysqli_fetch_all($query, MYSQLI_ASSOC);

   if (count($users) == 1) {
      funcs::sql_bind($connect, "DELETE FROM reservation WHERE id=?", "s", $_POST["id"]);
   }
}