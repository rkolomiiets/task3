<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>View books list</title>
<style>
  @import url('styles.css');
</style>
</head>
<body style="background: url(fon.png) top left repeat-x">
<?php 
// установка начальных настроек подключения к БД
require_once 'login.php'; 

// подключение функции фильтрации пользовательского ввода
require_once 'functions.php';

// массивы для хранения временных (промежуточных) данных
$tmp_array = $tmp_str = array();
$tmp_array1 = array(array());
// массив для хранения полной информации по книге
$book_full_info = array();

// получение данных книги из БД
// т.к. пользовательского ввода здесь нет, все идет обычными запросами

// определение количества книг в БД
$query = "SELECT COUNT(*) FROM books"; 
$result = $connection->query($query);
if (!$result) { die("1. " . $connection->error . ". Error code: " . $connection->errno); }
  else { $tmp_array = $result->fetch_array();
         $num_books = $tmp_array[0]; 
  }          

// формирование массива с информацией о книге
$query = "SELECT * FROM books LIMIT $num_books";
if ($result = $connection->query($query)) {
  while ($tmp_array = $result->fetch_assoc()) {
    if ($tmp_array['have'] == 1) {
      $book_full_info['id'] = $book_id = $tmp_array['bid'];
      $book_full_info['title'] = $tmp_array['title'];
      $book_full_info['year'] = $tmp_array['year'];
      $book_full_info['price'] = $tmp_array['price'];
      $book_full_info['cover'] = trim("data\covers\ ").$tmp_array['cover'];
    }
    // определение авторов книги
    $authors = '';
    $subquery = "SELECT au_fname, au_lname FROM authors WHERE bid = $book_id ORDER BY bid LIMIT 3";
    $subresult = $connection->query($subquery);
    if ($subresult) {
      $tmp_array1 = $subresult->fetch_all(MYSQLI_ASSOC);
      foreach ($tmp_array1 as $key => $value) {
        $tmp_str = str_split($tmp_array1[$key]['au_fname']);

/*   // показывает то, что с кириллицей в моем ПХП какая-то хуйня... :( 
   foreach ($tmp_str as $key => $value) {
     echo "<p>tmp_str[{$key}] == {$value}</p>";} */

        $authors = $authors.$tmp_str[0].". ".$tmp_array1[$key]['au_lname'];
        if ($key < count($tmp_array1) - 1) { $authors = $authors.", "; }
      }
      $book_full_info['authors'] = $authors;
    }

/*    // вывод всех данных в куче
    foreach ($book_full_info as $key => $value) {
      echo "<p>book_full_info[{$key}] == {$value}</p>";
    } */

    // вспомогательные переменные для корректного отображения информации
    // ЭТО ЛЮТЫЕ КОСТЫЛИ!!! ИХ БЫТЬ НЕ ДОЛЖНО!!! 
    $cover = $book_full_info['cover'];
    $title = $book_full_info['title'];
    $year = $book_full_info['year'];
    
    // структурированный вывод информации
    echo "<div class=\"inline\">
            <div class=\"cover\"> 
              <img src=\"$cover\" width=\"80px\"> 
            </div>
            <div>
              $title <br> 
              $authors <br>
              $year <br>
            </div>
          </div> ";
  }
}

// закрытие подключения
$connection->close();
?>  
</body>
</html>