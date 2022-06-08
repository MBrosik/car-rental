<?php

include_once("../utils/db.php");
include_once("../utils/functions.php");

session_start();

if (isset($_SESSION["id"], $_POST["id"])) {
   $query = mysqli_query($connect, "SELECT * FROM users WHERE user_type_id IN (1,2) AND id = $_SESSION[id]");
   $users = mysqli_fetch_all($query, MYSQLI_ASSOC);

   if (count($users) == 1) {
      $data = funcs::select_bind(
         $connect,
         "SELECT * 
         FROM reservation 
         WHERE reservation.car_id = (SELECT car_id FROM reservation WHERE reservation.id = ?) AND status_id=1",
         "s",
         $_POST["id"]
      );


      if (count($data) == 0) {

         funcs::sql_bind(
            $connect, 
            "UPDATE reservation 
            SET status_id= 1
            WHERE id = ?", 
            "s", 
            $_POST["id"]
         );
         funcs::sql_bind($connect, "DELETE FROM reservation WHERE car_id=? AND id!=?", "ss", $data[0]["car_id"], $_POST["id"]);
      }
   }
}
