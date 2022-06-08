<?php
include_once("../utils/db.php");
include_once("../utils/functions.php");

session_start();

$code = -1;

if (isset($_SESSION["id"], $_POST["id"], $_POST["start_time"], $_POST["start_time"])) {
   if ($_POST["start_time"] != "" && $_POST["end_time"] != "") {

      $currentTime = funcs::get_data($connect, "SELECT value FROM variables WHERE name='date_now'")[0]["value"];


      $user_reserved_table = funcs::select_bind(
         $connect,
         "SELECT * FROM reservation INNER JOIN status_enum ON status_enum.id = reservation.status_id
         WHERE status_enum.status='reserved' AND reservation.user_id=? AND reservation.end_time < FROM_UNIXTIME($currentTime)",
         "s",
         $_SESSION["id"]
      );
      if (count($user_reserved_table) == 0) {

         $found_reservations = funcs::select_bind(
            $connect,
            "SELECT reservation.id as id, status_enum.status as status
            FROM reservation 
            INNER JOIN status_enum ON status_enum.id = reservation.status_id 
            WHERE reservation.car_id = ? AND reservation.user_id=?",
            "ss",
            $_POST["id"],
            $_SESSION["id"],
         );

         if (count($found_reservations) == 0) {
            funcs::sql_bind(
               $connect,
               "INSERT INTO reservation(user_id, car_id, reserve_time, start_time, end_time, status_id) 
            VALUES(?,?,cast(? as DateTime),?,?, 2)
            ",
               "sssss",
               $_SESSION["id"],
               $_POST["id"],
               date("Y-m-d H:i:s", $currentTime),
               $_POST["start_time"],
               $_POST["end_time"],
            );
            $code = 1;
         } else if ($found_reservations[0]["status"] != "reserved") {
            funcs::sql_bind(
               $connect,
               "UPDATE reservation
            SET reserve_time=cast(? as DateTime), start_time=?, end_time=?
            WHERE id=?",
               "ssss",
               date("Y-m-d H:i:s", $currentTime),
               $_POST["start_time"],
               $_POST["end_time"],
               $found_reservations[0]["id"]
            );
            $code = 1;
         }
      }
   }
}

echo json_encode(array("code" => $code));
