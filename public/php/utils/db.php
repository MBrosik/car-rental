<?php

$server = "localhost";
$username = "root";
$password = "";
$db = "car_rental";


$connect = mysqli_connect($server, $username, $password, $db) or die("SOMETHING WAS WRONG");