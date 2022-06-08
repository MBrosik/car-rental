<?php
include_once("../utils/db.php");
include_once("../utils/functions.php");

session_start();
// reservation

function getReservations($reserve_table)
{
   global $connect;

   $query = mysqli_query(
      $connect,
      "SELECT 
      $reserve_table.id as id,         

      $reserve_table.user_id as user_id,         
      users.login as login,

      $reserve_table.car_id as car_id, 
      brands.brand as brand, 
      cars.model as model,
      cars.img as img,
      cars.price_per_day as price_per_day,         

      $reserve_table.reserve_time as reserve_time,
      $reserve_table.start_time as start_time,
      $reserve_table.end_time as end_time,
      status_enum.status as status

      FROM cars 
      INNER JOIN $reserve_table 
      ON cars.id = $reserve_table.car_id
      INNER JOIN status_enum 
      ON $reserve_table.status_id = status_enum.id
      INNER JOIN users 
      ON $reserve_table.user_id = users.id
      INNER JOIN brands
      ON cars.brand_id = brands.id
   "
   );

   $offers = mysqli_fetch_all($query, MYSQLI_ASSOC);


   $offers_grouped = funcs::array_group_by($offers, fn ($el) => $el["status"]);

   foreach ($offers_grouped as $key => &$value) {
      $value = funcs::array_group_by($value, fn ($el) => $el["car_id"]);
   }

   $offers_grouped2 = funcs::array_group_by($offers, fn ($el) => $el["user_id"]);

   foreach ($offers_grouped2 as $key => &$value) {
      $value = funcs::array_group_by($value, fn ($el) => $el["status"]);
   }

   return [
      "byStatus" => $offers_grouped,
      "byUser" => $offers_grouped2,
   ];
}


if (isset($_SESSION["id"])) {
   $query = mysqli_query($connect, "SELECT * FROM users WHERE user_type_id IN (1,2) AND id = $_SESSION[id]");
   $users = mysqli_fetch_all($query, MYSQLI_ASSOC);

   if (count($users) == 1) {
      echo json_encode(array(
         "reserved" => getReservations("reservation"),
         "archived" => getReservations("reservation_archive"),
      ));
   }
}
