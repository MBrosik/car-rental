<?php


include_once("../utils/db.php");
include_once("../utils/functions.php");

session_start();


if (isset($_SESSION["id"], $_POST["id"], $_POST["user_type"], $_POST["activated"])) {
   $query = mysqli_query($connect, "SELECT * FROM users WHERE user_type_id=1 AND id = $_SESSION[id]");
   $users = mysqli_fetch_all($query, MYSQLI_ASSOC);

   var_dump($users);

   if (count($users) == 1) {
      funcs::sql_bind(
         $connect,
         "UPDATE users SET user_type_id=(SELECT id from user_types WHERE type = ?), activated=? WHERE id = ?",
         "sss",
         ...array($_POST["user_type"], $_POST["activated"], $_POST["id"])
      );

      echo "{'code':1}";
   }
}
