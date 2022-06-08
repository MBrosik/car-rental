<?php

include_once("../utils/db.php");
include_once("../utils/functions.php");

session_start();

if (isset($_SESSION["id"])) {

   $user_reserved_table = funcs::select_bind(
      $connect,
      "SELECT * FROM reservation INNER JOIN status_enum ON status_enum.id = reservation.status_id
      WHERE status_enum.status='reserved' AND reservation.user_id=? AND reservation.end_time < FROM_UNIXTIME((SELECT value FROM variables WHERE name='date_now'))",
      "s",
      $_SESSION["id"]
   );

   if (count($user_reserved_table) == 0) {

      $query = mysqli_query(
         $connect,
         "SELECT 
      cars.id as id,

      brands.brand as brand, 
      cars.model as model,
      cars.img as img,
      cars.price_per_day as price_per_day,

      reservation.user_id as user_id,

      reservation.id as reservation_id,
      reservation.start_time as start_time,
      reservation.end_time as end_time,

      status_enum.status as status

      FROM cars 
      LEFT JOIN reservation       
      ON cars.id = reservation.car_id     
      LEFT JOIN status_enum 
      ON reservation.status_id = status_enum.id       
      INNER JOIN brands
      On cars.brand_id = brands.id
   "
      );

      $offers = mysqli_fetch_all($query, MYSQLI_ASSOC);      

      $offers_grouped = funcs::array_group_by($offers, fn ($el) => $el["id"]);

      $offers_filtered = array();

      foreach ($offers_grouped as $key => $value) {
         $array = array(
            "id" => $key,
            "name" => $value[0]["brand"]." ".$value[0]["model"],
            "img" => $value[0]["img"],
            "price_per_day" => floatval($value[0]["price_per_day"]),
            "are_you_waiting" => false,
            "your_order" => array(
               "start_time" => "",
               "end_time" => ""
            )
         );
         if ($value[0]["user_id"] == null) {
            $array["status"] = "open";
            $array["count"]  = 0;

            array_push($offers_filtered, $array);            
         } else if ($value[0]["status"] != "reserved") {
            $array["status"] = "waiting";
            $array["count"]  = count($value);

            $filtered_arr = array_filter($value, fn ($el) => $el["user_id"] == $_SESSION["id"]);
            $count = count($filtered_arr);

            if ($count != 0) {
               $array["are_you_waiting"] = true;

               $user_arr = $filtered_arr[array_keys($filtered_arr)[0]];
               $array["your_order"] = array(
                  "start_time" => $user_arr["start_time"],
                  "end_time" => $user_arr["end_time"]
               );
            }


            array_push($offers_filtered, $array);            
         }
      }

      echo json_encode([
         "code"=> 1,
         "offers_filtered"=> $offers_filtered,
         "date_now" => funcs::get_data($connect, "SELECT value*1000 as value FROM variables WHERE name='date_now'")[0]["value"]
      ]);
   }
   else {
      echo json_encode(["code"=> -1, "offers_filtered"=> []]);
   }
}
