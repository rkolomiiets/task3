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
<header>  
  <h2>Books</h2>
</header>  
<?php 
// установка начальных настроек подключения к БД
require_once 'login.php'; 

// подключение функции фильтрации пользовательского ввода
require_once 'functions.php';

// массивы для хранения временных (промежуточных) данных
$tmp_array = array(array());
$tmp_array_1 = array(); 

// массив для хранения полной информации по книге
$book_full_info = array(array());

// получение данных книги из БД
// т.к. пользовательского ввода здесь нет, все идет обычными (незащищенными) запросами

// определение количества книг в БД
$query = "SELECT bid FROM books"; 
$result = $connection->query($query);
if (!$result) { die("1. " . $connection->error . ". Error code: " . $connection->errno); }
  else { $num_books = $result->num_rows; }          

// формирование массива с информацией о книге
$query = "SELECT * FROM books LIMIT $num_books";
if ($result = $connection->query($query)) {
  $tmp_array = $result->fetch_all(MYSQLI_ASSOC);
  // var_dump($tmp_array);
  $i = 0;
  while (isset($tmp_array[$i])) {
    $book_id = $book_full_info[$i]['id'] = $tmp_array[$i]['bid'];
    $book_full_info[$i]['cover'] = trim("data\covers\ ").$tmp_array[$i]['cover'];
    $book_full_info[$i]['title'] = $tmp_array[$i]['title'];

    // определение авторов книги
    $authors = '';
    $subquery = "SELECT au_fname, au_lname FROM authors WHERE bid = $book_id ORDER BY bid LIMIT 3";
    $subresult = $connection->query($subquery);
    if ($subresult) {
      $tmp_array_1 = $subresult->fetch_all(MYSQLI_ASSOC);
      foreach ($tmp_array_1 as $key => $value) {
        $temp_string = substr($tmp_array_1[$key]['au_fname'],0,1);
        $authors = $authors.$temp_string.". ".$tmp_array_1[$key]['au_lname'];
        if ($key < count($tmp_array_1) - 1) { $authors = $authors.", "; }
      }
      $book_full_info[$i]['authors'] = $authors;
    }

    $book_full_info[$i]['year'] = $tmp_array[$i]['year'];
    $book_full_info[$i]['genre'] = $tmp_array[$i]['genre'];
    $book_full_info[$i]['abstract'] = $tmp_array[$i]['abstract'];
    $book_full_info[$i]['price'] = $tmp_array[$i]['price'];
/*    
    foreach ($book_full_info[$i] as $key => $value) {
      echo "<p>BOOK[{$i}][{$key}] == {$value}</p>"; 
    } */
    ++$i;
  }
}
// закрытие подключения
$connection->close();
?>

<?php
// структурированный вывод информации
$i = 0;
while (isset($book_full_info[$i])) {
  echo "<div class=\"book_info\">
          <div class=\"inline\">
            <div class=\"inline\">
              <img class=\"cover\" src=\"".$book_full_info[$i]['cover']."\"> 
            </div>  
            <div class=\"inline\">
              <p class=\"title\">".$book_full_info[$i]['title']."</p>
              <p class=\"authors\">".$book_full_info[$i]['authors']."</p>
              <p class=\"year\">".$book_full_info[$i]['year']."</p>
              <p class=\"price\"> Price: ".$book_full_info[$i]['price']."$</p>          
            </div>
          </div>
        </div>";
  ++$i;
}

?>
</body>
</html>