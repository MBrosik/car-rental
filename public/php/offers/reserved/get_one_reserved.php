<?php

include_once("../../utils/db.php");
include_once("../../utils/functions.php");

session_start();

if (isset($_SESSION["id"], $_POST["id"], $_POST["sql_type"])) {

   if ($_POST["sql_type"] == "reserved") {
      $reserved_table = "reservation";
   } else {
      $reserved_table = "reservation_archive";
   };   

   $sql =  "SELECT 
         $reserved_table.id as id,

         brands.brand as brand, 
         cars.model as model,
         cars.img as img,
         $reserved_table.car_id as car_id,
         cars.price_per_day as price_per_day,         

         $reserved_table.reserve_time as reserve_time,
         $reserved_table.start_time as start_time,
         $reserved_table.end_time as end_time,       
         
         status_enum.status as status

         FROM cars 
         INNER JOIN $reserved_table 
         ON cars.id = $reserved_table.car_id
         INNER JOIN brands
         ON cars.brand_id = brands.id
         INNER JOIN status_enum 
         ON status_enum.id = $reserved_table.status_id
         WHERE $reserved_table.user_id = ? AND $reserved_table.id = ? 
         ";

   $reserved = funcs::select_bind(
      $connect,
      $sql,
      "ss",
      $_SESSION["id"],
      $_POST["id"]
   );

   $currentTime = funcs::get_data($connect, "SELECT value FROM variables WHERE name='date_now'")[0]["value"];
   $reserved[0]["date_now"] = $currentTime * 1000;

   if ($reserved_table == "reservation" && strtotime($reserved[0]["start_time"]) < $currentTime) {
      $id = $reserved[0]["id"];
      $path = "https://chart.googleapis.com/chart?cht=qr&chs=500x500&chl=$id";
      $type = pathinfo($path, PATHINFO_EXTENSION);
      $data = file_get_contents($path);
      $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

      $reserved[0]["qr_code"] = $base64;
   }


   echo json_encode($reserved[0]);
}
