<?php 
  // установка начальных настроек подключения к БД
  require_once 'login.php';
  // подключение файла с "самодельными" функциями
  require_once 'functions.php';
     
  if (!empty($_POST['register'])) 
  {
    // проверка на наличие пустых полей
    if (empty($_POST['username'])) {$errors[] = "No user name";}
    if (empty($_POST['psw1'])) {$errors[] = "No password";}
    if (empty($_POST['psw2'])) {$errors[] = "You don\' confirm your password";}
    if (empty($_POST['email'])) { $errors[] = "No email"; }
      
    // проверка подтверждения пароля
    if ($_POST['psw1'] != $_POST['psw2']) {$errors[] = "You make a mistake when confirming the password";}
    
    // валидация e-mail
    if(!preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $_POST['email'])) { 
    $errors[] = "Wrong e-mail format"; }

    // проверка на наличие ошибок, возникших при заполнении формы регистрации
    if (count($errors) != 0) {print_r($errors); }
      else { // если ошибок нет, заполняем соответсвующие поля в БД 
        $user_name = filter_string($connection, $_POST['username']);
        $user_psw = filter_string($connection, $_POST['psw1']);
        $user_email = filter_string($connection, $_POST['email']);
        
        // хэширование пароля
        $hash_user_psw = hash('ripemd128', $user_psw);
        
        // вставка данных пользователя в БД, защищенный способ
        $query = 'PREPARE statement FROM "INSERT INTO users VALUES(?,?,?,?,?,?,?,?)"'; 
        $result = $connection->query($query);
        if (!$result) {die($connection->error . ". Error code: " . $connection->errno);}

        $query = "SET @uid = NULL,
                      @uname = '$user_name',
                      @hpassword = '$hash_user_psw',
                      @email = '$user_email',
                      @admin = 'false',
                      @ava = 'defaultava.jpg',
                      @date = '',
                      @status = ''";
        $result = $connection->query($query);
        if (!$result) {die("1. " . $connection->error . ". Error code: " . $connection->errno);}
              
        $query = 'EXECUTE statement USING @uid, @uname, @hpassword, @email, @admin, @ava, @date, @status';
        $result = $connection->query($query);
        if (!$result) {die("2. " . $connection->error . ". Error code: " . $connection->errno);}     
        
        $query = 'DEALLOCATE PREPARE statement';
        $result = $connection->query($query);
        if (!$result) {die("3. " . $connection->error . ". Error code: " . $connection->errno);}
        
        // закрытие подключения
        $connection->close();
        
        // регистрация прошла успешно 
        echo "<html>
              <head>
              <title>Task 3. User registration</title>
              <style> @import url('styles.css'); </style>
              </head>
              <body style=\"background: url(fon.png) top left repeat-x\">
              <center> <div>
                <br><br>
                <h2>Congratulations! You are registered now.</h2>
                <br>
                <pre><a href=\"user_profile.php\">To your profile</a>     <a href=\"files.php\">To files list</a></pre>
              </div> </center>
              <footer></footer>
              </body>
              </html>";
      }
  } else { die("Database error"); }
?>