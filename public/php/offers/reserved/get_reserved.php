<?php

include_once("../../utils/db.php");
include_once("../../utils/functions.php");

session_start();


function get_reservation(string $reserved_table)
{
   global $connect;

   $end_string = "";
   if ($reserved_table == "reservation") {
      $end_string = "AND status_enum.status = 'reserved'";
   }
   
   return funcs::select_bind(
      $connect,
      "SELECT 
         $reserved_table.id as id,

         CONCAT(brands.brand, ' ', cars.model) as name,         

         $reserved_table.reserve_time as reserve_time,
         $reserved_table.start_time as start_time,
         $reserved_table.end_time as end_time,

         status_enum.status as status

      FROM $reserved_table 
      INNER JOIN cars 
      ON $reserved_table.car_id = cars.id
      INNER JOIN status_enum 
      ON $reserved_table.status_id = status_enum.id
      INNER JOIN brands
      On cars.brand_id = brands.id
      WHERE $reserved_table.user_id = ? $end_string",
      "s",
      $_SESSION["id"]
   );
}


if (isset($_SESSION["id"])) {
   echo json_encode(array(
      "reserved" => get_reservation("reservation"),
      "archived" => get_reservation("reservation_archive"),
   ));
}
