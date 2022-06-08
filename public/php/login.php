<?php

include_once("./utils/db.php");
include_once("./utils/functions.php");

session_start();
$code = -1;

if(!isset($_SESSION["id"])){

   $users = funcs::select_bind(
      $connect,
      "SELECT * FROM users WHERE login=? and password=AES_ENCRYPT(?, 123)",
      "ss",
      $_POST["login"],
      $_POST["password"]
   );


   if (count($users) != 0) {
      if ($users[0]["activated"] == 1) {
         $_SESSION["id"] = $users[0]["id"];
         $code = 1;
      }
   }
}

echo  json_encode(array("code" => $code));
