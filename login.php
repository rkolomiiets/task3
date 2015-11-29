<?php
  // инициализация основных переменных подключения
  $db_host = 'localhost';
  $db_username = 'mysql';
  $db_password = 'mysql';
  $db_database = 'task3';
  $db_charset = 'utf8';

  // подключение к БД
  $connection = new mysqli($db_host, $db_username, $db_password, $db_database);
  mysqli_set_charset($connection, "utf8");

  // проверка успешности подключения
  if ($connection->connect_errno) { die($connection->connect_error); }
  
  // проверка корректности полученных данных регистрации 
  $errors = array(); // массив возможных ошибок

  if (!$connection->set_charset("utf8")) {
    $errors[] = "Charset error";
    die($connection->connect_error . ". Error code: " . $connection->errno);
  }
?>