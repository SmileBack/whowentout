<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../fire/core/boot.php';
boot();

mysql_connect(
  $server = "mysql-shared-02.phpfog.com",
  $username = "Custom App-27847",
  $password = 'MySQL4668');
$result = mysql_select_db("whowentout_com");

var_dump($result);

