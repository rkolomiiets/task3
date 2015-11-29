<?php
  // функция для защиты от внедрения SQL и XSS-кода
  function filter_string($connection, $string)
  {
    $string = $connection->real_escape_string(strip_tags(htmlentities(stripslashes($string))));
    return $string;
  }
?>