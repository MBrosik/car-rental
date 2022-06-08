<?php
require_once("./utils/db.php");
require_once("./utils/functions.php");

session_start();

$status = null;
$login = null;

if (isset($_SESSION["id"])) {   

   $user_tab = funcs::select_bind(
      $connect,
      "SELECT user_types.type as type, users.login as login FROM users INNER JOIN user_types ON user_types.id = users.user_type_id WHERE users.id = ?",
      "s",
      $_SESSION["id"]
   );

   $status = $user_tab[0]["type"];
   $login = $user_tab[0]["login"];
  
}

echo json_encode(Array("status"=>$status,"login"=>$login));