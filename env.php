<?php

// Escolha do header das p�ginas
$page_hader = 'Medtracker';
putenv("PAGE_HEADER=$page_hader");
// Escolha de ambiente
define('AMBIENTE', 'local'); // local ou producao




// DEFINI��O DA BASE DE DADOS LOCAL
  $local_database = [
      'DB_HOST' => 'localhost',
      'DB_USERNAME' => 'root',
      'DB_PASSWORD' => '',
      'DB_NAME' => 'tmsnewversion',
      'DB_PORT' => '3306',
  ];



// DEFINI��O DA BASE DE DADOS DE PRODU��O
  $producao_database = [
      'DB_HOST' => 'localhost',
      'DB_USERNAME' => 'root',
      'DB_PASSWORD' => '03skmc12',
      'DB_NAME' => 'skmcombr8',
      'DB_PORT' => '3307',
  ];




switch (AMBIENTE) {
  case 'local':
      $variables = $local_database;
    break;
  case 'producao':
      $variables = $producao_database;
}



foreach ($variables as $key => $value) {
    putenv("$key=$value");
}
