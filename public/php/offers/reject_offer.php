<?php

include_once("../utils/db.php");
include_once("../utils/functions.php");

session_start();

$code = -1;

if (isset($_SESSION["id"], $_POST["id"])) {   
   funcs::sql_bind($connect, "DELETE FROM reservation WHERE user_id=? AND car_id=?","ss", $_SESSION["id"], $_POST["id"]);
   $code = 1;
}

echo json_encode(Array("code"=>$code));