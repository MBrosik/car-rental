<?php

class funcs
{
   public static function get_data($dataBase, $stmt)
   {
      $result = null;
      $arr = null;

      $result = mysqli_query($dataBase, $stmt);
      $arr = mysqli_fetch_all($result, MYSQLI_ASSOC);

      return $arr;
   }

   public static function select_bind(mysqli $connection, string $sql, string $bind, ...$arr): array
   {
      $stmt = mysqli_prepare($connection, $sql);
      mysqli_stmt_bind_param($stmt, $bind, ...$arr);
      mysqli_stmt_execute($stmt);

      $result = $stmt->get_result();
      return mysqli_fetch_all($result, MYSQLI_ASSOC);
   }

   public static function sql_bind(mysqli $connection, string $sql, string $bind, ...$arr)
   {
      $stmt = mysqli_prepare($connection, $sql);
      mysqli_stmt_bind_param($stmt, $bind, ...$arr);
      mysqli_stmt_execute($stmt);
   }

   public static function logger($log, $dest = "log.log")
   {
      if (!file_exists($dest)) {
         file_put_contents($dest, "");
      }

      $time = date("d.m.y h:i:s A", time());

      $current_content = file_get_contents($dest);
      $current_content .= "$time\t\t\t$log\r";
      file_put_contents($dest, $current_content);
   }


   public static function array_group_by(array $array, callable $func)
   {
      $final_arr = array();

      foreach ($array as $key => $value) {
         $key = $func($value);

         if (isset($final_arr[$key]) == false) {
            $final_arr[$key] = array();
         }

         array_push($final_arr[$key], $value);
      }

      return $final_arr;
   }

   public static function array_group_by2(array $array, callable $func)
   {
      $arr = funcs::array_group_by($array, $func);
      $final_arr = Array();

      foreach ($arr as $key => $value) {
         array_push($final_arr, Array("key" => $key, "value" => $value));
      }


      return $final_arr;
   }
}
