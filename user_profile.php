<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Task 3. Login
  <?php 
  // установка начальных настроек подключения к БД
  require_once 'login.php'; 
  // подключение файла с "самодельными" функциями
  require_once 'functions.php';
  ?>
</title>
<style>
  @import url('styles.css');
</style>
</head>
<body style="background: url(fon.png) top left repeat-x">
  <header>
    <?php 
    //определение имени пользователя и его отображение
    if (!empty($_POST)) {
      $user_name = filter_string($connection, $_POST['username']);
      echo "<h2>" . $user_name . "</h2>";
    } else { echo "<h2>Register please...</h2>"; }
    ?>
  </header>
  <hline>
  <div class="inline" id="user_profile"> 
    <?php
    // получение данных о пользователе из формы входа
    if (!empty($_POST['username'])) {
      $user_name = filter_string($connection, $_POST['username']);
      $user_psw = filter_string($connection, $_POST['psw']);
      $hash_user_psw = hash('ripemd128', $user_psw);   
    } else { die("No user data"); }
    
    $user_data = array();

    // получение данных пользователя из БД
    $query = 'PREPARE statement FROM "SELECT * FROM users WHERE uname = (?) AND hpassword = (?)"'; 
    $result = $connection->query($query);
    if (!$result) {die($connection->error . ". Error code: " . $connection->errno);}

    $query = "SET @uname = '$user_name',
                  @hpassword = '$hash_user_psw'";
    $result = $connection->query($query);
    if (!$result) {
      die("1. " . $connection->error . ". Error code: " . $connection->errno);
    } 
              
    $query = 'EXECUTE statement USING @uname, @hpassword';
    $result = $connection->query($query);
    if (!$result) {
      die("2. " . $connection->error . ". Error code: " . $connection->errno);
    } else { $user_data = $result->fetch_array(MYSQLI_ASSOC);}

    $query = 'DEALLOCATE PREPARE statement';
    $result = $connection->query($query);
    if (!$result) {die("3. " . $connection->error . ". Error code: " . $connection->errno);}
    
    // отображение данных пользовотеля
    if (!empty($user_data)) {
      $ava = trim("data\avatars\ ").$user_data['ava'];
      echo $user_data['status']."<br>";
      echo "<img src=\"$ava\">"."<br>";
      echo "Registered ".$user_data['date']."<br>";
      echo "E-mail: ".$user_data['email']."<br><br>"; 

      // отображение общих кнопок
      echo "<form action=\"user_profile.php\" method=\"POST\">
            <input type=\"submit\" class=\"button\" name=\"edit_profile\" value=\"Edit profile\">
            </form><br>
            <form action=\"view_books_list.php\" method=\"POST\">
            <input type=\"submit\" class=\"button\" name=\"view_books_list\" value=\"View books list\">
            </form><br>";
      
      // отображение админских кнопок
      if ($user_data['admin'] == 1) {
        echo "<form action=\"add_book.html\" method=\"POST\">
              <input type=\"submit\" class=\"button\" name=\"books_db\" value=\"Edit books database\"/>
              </form><br>
              <form action=\"show_purchases.php\" method=\"POST\">
              <input type=\"submit\" class=\"button\" name=\"show_purchases\" value=\"Show purchases\">
              </form><br>";
      }
    }
    ?>
  </div>
  <div class="inline" id="books_all">
    <?php
    // echo "??????";
    ?>
  </div>  
</body>
<?php 
// закрытие подключения
$connection->close();
?>
</html>