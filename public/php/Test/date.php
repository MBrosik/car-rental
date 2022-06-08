<?php

echo date("d.m.y H:i:s", time()) . "<br>";

echo date("Y-m-d H:i:s", mktime(13, 20, 0, 11, 25, 2021)) . "<br>";
echo date("Y-m-d H:i:s", time()) . "<br>";


echo strtotime("2021-11-12") . "<br>";
echo strtotime("2021-11-13") . "<br>";
echo time() . "<br>";
