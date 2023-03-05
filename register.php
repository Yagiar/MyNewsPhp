<!DOCTYPE html>
<html>
<head>
  <title>Регистрация</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div class="header">
  	<h2>Зарегистрироваться</h2>
  </div>
	
  <form method="post" action="register.php">
  	<div class="input-group">
  	  <label>Логин</label>
  	  <input type="text" name="username" value="">
  	</div>
  	<div class="input-group">
  	  <label>Пароль</label>
  	  <input type="password" name="Password">
  	</div>
  	<div class="input-group">
  	  <button type="submit" class="btn" name="reg_user">Зарегистрироваться</button>
  	</div>
  	<p>
  		Уже зарегистрированы? <a href="login.php">Войти</a>
  	</p>
  </form>
</body>
</html>
<?php

//Нажатие на кнопку
if(isset($_POST["reg_user"]))
{
    $db_host = 'localhost';
    $db_name = 'News';
    $db_user = 'root';
    $db_pass = '';
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
	//получение данных с инпутов
$login=$_POST["username"];
$password=$_POST["Password"];
//$password=md5($password);
$request1="INSERT INTO users(username, password) VALUES (?,?)";
$result=$pdo->prepare($_request1);
$result->execute([$login,$password]);
if($result)
 echo "<h1>Вы успешно зарегистрировались</h1>";
else 

echo "Error";
}
?>