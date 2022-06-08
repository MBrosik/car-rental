<?php

include_once("./utils/db.php");
include_once("./utils/functions.php");

$code = -1;

if (!isset($_SESSION["id"])) {

   $users = funcs::select_bind(
      $connect,
      "SELECT * FROM users WHERE login=?",
      "s",
      $_POST["login"],
   );

   if (count($users) == 0) {
      funcs::sql_bind(
         $connect,
         "INSERT INTO users(login,password) VALUES(?, AES_ENCRYPT(?, 123))",
         "ss",
         $_POST["login"],
         $_POST["password"]
      );

      $code = 1;
   }
}
echo  json_encode(array("code" => $code));
