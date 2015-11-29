<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Editing of books database (admin only)</title>
<style>
  @import url('styles.css');
</style>
</head>
<body style="background: url(fon.png) top left repeat-x">
  <?php
  if (!empty($_POST['books_db'])) {
    // установка начальных настроек подключения к БД
    require_once 'login.php'; 
    // подключение файла с "самодельными" функциями
    require_once 'functions.php';

    // извлечение информации о всех книгах из БД
    $books = array();

    $query = "SELECT * FROM books"; 
    $result = $connection->query($query);
    if (!$result) {die($connection->error . ". Error code: " . $connection->errno);}
      else {$books = $result->fetch_array(MYSQLI_ASSOC);}

    foreach ($books as $i => $bid) {
      echo $books['bid'];
    }

    // закрытие подключения
    $connection->close();
  }
  ?>
</body>
</html>