<?php
// установка начальных настроек подключения к БД
require_once 'login.php'; 

// подключение функции фильтрации пользовательского ввода
require_once 'functions.php';

//  обработка всей информации, кроме обложки
if (!empty($_POST)) {
  $au1_fname = filter_string($connection, $_POST['au1_fname']);
  $au1_lname = filter_string($connection, $_POST['au1_lname']);
  $au2_fname = filter_string($connection, $_POST['au2_fname']);
  $au2_lname = filter_string($connection, $_POST['au2_lname']);
  $au3_fname = filter_string($connection, $_POST['au3_fname']);
  $au3_lname = filter_string($connection, $_POST['au3_lname']); 

  $title = filter_string($connection, $_POST['title']);
  $max_file_size = $_POST['MAX_FILE_SIZE'];
  $genre = filter_string($connection, $_POST['genre']);
  
  // $abstract = filter_string($_POST['abstract']);
  $abstract = preg_replace("/[\r\n]+/", "</p><p>", filter_string($connection, $_POST['abstract']));

  $year = filter_string($connection, $_POST['year']);
  // $year = $_POST['year'];
  $price = filter_string($connection, $_POST['price']);
  if ($_POST['have'] == 'on') { $have = 1; } 
    else { $have = 0; }
}

// получение изображения обложки
if (!empty($_FILES)) {
  @is_uploaded_file($_FILES['cover']['tmp_name']) or die('Incorrect file!');
  @getimagesize($_FILES['cover']['tmp_name']) or die('Your file isn\'t an image');
  if (!isset($max_file_size)) {die('No $_POST!');}
  if ($_FILES['cover']['size'] > $max_file_size) { die('Your file too large'); }
  $upload_filename = $_SERVER['DOCUMENT_ROOT'].trim('\data\covers\ ').$_FILES['cover']['name'];
  @move_uploaded_file($_FILES['cover']['tmp_name'], $upload_filename) or die('Can\'t load your file here');
  $img_name = $_FILES['cover']['name'];
  // echo "<img src=\"$img_name\"> <br>";
}

// вставка данных книги в БД
$query = 'PREPARE statement FROM "INSERT INTO books VALUES(?,?,?,?,?,?,?,?,?,?)"'; 
$result = $connection->query($query);
if (!$result) {die($connection->error . ". Error code: " . $connection->errno);}
$query = "SET @bid = NULL,
              @title = '$title',
              @abstract = '$abstract',
              @cover = '$img_name',
              @year = '$year',
              @genre = '$genre',
              @subgenre = '',
              @price = '$price',
              @date = NULL,
              @have = '$have'";
// echo $query;
$result = $connection->query($query);
if (!$result) {die("1. " . $connection->error . ". Error code: " . $connection->errno);}         
$query = 'EXECUTE statement USING @bid, @title, @abstract, @cover, @year, @genre, @subgenre, @price, @date, @have';
$result = $connection->query($query);
if (!$result) {die("2. " . $connection->error . ". Error code: " . $connection->errno);}             
$query = 'DEALLOCATE PREPARE statement';
$result = $connection->query($query);
if (!$result) {die("3. " . $connection->error . ". Error code: " . $connection->errno);}

// получение book_id
$query = 'PREPARE statement FROM "SELECT bid FROM books WHERE title = (?)"'; 
$result = $connection->query($query);
if (!$result) {die($connection->error . ". Error code: " . $connection->errno);}
$query = "SET @title = '$title'";
$result = $connection->query($query);
if (!$result) {die("4. " . $connection->error . ". Error code: " . $connection->errno);}            
$query = 'EXECUTE statement USING @title';
$result = $connection->query($query);
if (!$result) {die("5. " . $connection->error . ". Error code: " . $connection->errno);}
  else {$book_data = $result->fetch_array(MYSQLI_ASSOC);}
$query = 'DEALLOCATE PREPARE statement';
$result = $connection->query($query);
if (!$result) {die("6. " . $connection->error . ". Error code: " . $connection->errno);}


// вставка данных авторов в БД --
// первый автор
$book_id = $book_data['bid'];
$query = 'PREPARE statement FROM "INSERT INTO authors VALUES(?,?,?)"'; 
$result = $connection->query($query);
if (!$result) {die($connection->error . ". Error code: " . $connection->errno);}
$query = "SET @bid = $book_id,
              @au_fname = '$au1_fname',
              @au_lname = '$au1_lname'";
$result = $connection->query($query);
if (!$result) {die("7. " . $connection->error . ". Error code: " . $connection->errno);}         
$query = 'EXECUTE statement USING @bid, @au_fname, @au_lname';
$result = $connection->query($query);
if (!$result) {die("8. " . $connection->error . ". Error code: " . $connection->errno);}             
$query = 'DEALLOCATE PREPARE statement';
$result = $connection->query($query);
if (!$result) {die("9. " . $connection->error . ". Error code: " . $connection->errno);}
// второй автор (если он есть)
if (!empty($au2_fname) && !empty($au2_lname)) {
  $query = 'PREPARE statement FROM "INSERT INTO authors VALUES(?,?,?)"'; 
  $result = $connection->query($query);
  if (!$result) {die($connection->error . ". Error code: " . $connection->errno);}
  $query = "SET @bid = $book_id,
                @au_fname = '$au2_fname',
                @au_lname = '$au2_lname'";
  $result = $connection->query($query);
  if (!$result) {die("10. " . $connection->error . ". Error code: " . $connection->errno);}         
  $query = 'EXECUTE statement USING @bid, @au_fname, @au_lname';
  $result = $connection->query($query);
  if (!$result) {die("11. " . $connection->error . ". Error code: " . $connection->errno);}             
  $query = 'DEALLOCATE PREPARE statement';
  $result = $connection->query($query);
  if (!$result) {die("12. " . $connection->error . ". Error code: " . $connection->errno);}
}
// третий автор (если он есть)
if (!empty($au3_fname) && !empty($au3_lname)) {
  $query = 'PREPARE statement FROM "INSERT INTO authors VALUES(?,?,?)"'; 
  $result = $connection->query($query);
  if (!$result) {die($connection->error . ". Error code: " . $connection->errno);}
  $query = "SET @bid = $book_id,
                @au_fname = '$au3_fname',
                @au_lname = '$au3_lname'";
  $result = $connection->query($query);
  if (!$result) {die("13. " . $connection->error . ". Error code: " . $connection->errno);}         
  $query = 'EXECUTE statement USING @bid, @au_fname, @au_lname';
  $result = $connection->query($query);
  if (!$result) {die("14. " . $connection->error . ". Error code: " . $connection->errno);}             
  $query = 'DEALLOCATE PREPARE statement';
  $result = $connection->query($query);
  if (!$result) {die("15. " . $connection->error . ". Error code: " . $connection->errno);}
}        


header("Location: view_books_list.php"); 


// закрытие подключения
$connection->close();
?>