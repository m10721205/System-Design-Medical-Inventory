<?php
/*
|--------------------------------------------------------------------------
| OWSA-INV V2
|--------------------------------------------------------------------------
| Author: Siamon Hasan
| Project Name: OSWA-INV
| Version: v2
| Offcial page: http://oswapp.com/
| facebook Page: https://www.facebook.com/oswapp
|
|定義資料庫
|更改資料庫從這改
|
*/
  define( 'DB_HOST', 'localhost' );          // Set database host
  define( 'DB_USER', 'root' );             // Set database user
  define( 'DB_PASS', '00000000' );             // Set database password
  define( 'DB_NAME', 'ntu1024' );        // Set database name
  mysql_query("SET NAMES 'utf8'");
  mysql_query("SET CHARACTER_SET_CLIENT='utf8'");
  mysql_query("SET CHARACTER_SET_RESULTS='utf8'");
  mysql_query("SET CHARACTER_SET_connection='utf8'");
?>
