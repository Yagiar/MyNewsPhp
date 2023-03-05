<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <link href="style.css" rel="stylesheet" type="text/css">
    <link href="rules.css" rel="stylesheet" type="text/css">
    <title>Document</title>
</head>
<body>
    
<div class="nav">

<nav>
  <ul>
    <li>
      <a href="news.php">News</a>
      <ul>
        <li>
          <a>Groups</a>
          <ul>
            <li><a href="group-student.php">Student</a></li>
            <li><a href="group-child.php">Child</a></li>
            <li><a href="group-aged.php">Aged</a></li>
          </ul>
        </li>
        <li>
          <a>Categories</a>
          <ul>
            <li><a href="category-php.php">php</a></li>
            <li><a href="category-js.php">js</a></li>
            <li><a href="category-html.php">html</a></li>
            <li><a href="category-css.php">css</a></li>
            <li><a href="category-sql.php">sql</a></li>
          </ul>
        </li>
      </ul>
    </li>
    <li><a href="aboutUs.php">About us</a></li>
    <li><a href="Rules.php">Rules</a></li>
  </ul>
</nav>
</div>
      <div class="container">
      <h1>Правила</h1>

      <h2>Общие положения</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean gravida tincidunt tortor, ac laoreet mauris fermentum nec. In hac habitasse platea dictumst. Praesent rutrum sollicitudin turpis, vel consectetur tortor convallis ac.</p>
  
      <h2>Регистрация</h2>
      <p>Ut et sapien in mi venenatis convallis id id ante. Nunc mollis, nunc a hendrerit convallis, risus velit ullamcorper odio, vel vestibulum turpis velit sit amet arcu. Suspendisse non libero non arcu fermentum fringilla vel sit amet metus. Ut fringilla eget metus nec congue.</p>
  
      <h2>Использование сайта</h2>
      <p>Curabitur tincidunt faucibus magna, non molestie felis malesuada in. Phasellus sed ipsum nec purus bibendum pellentesque. Donec vitae quam mauris. Morbi rhoncus ligula vel libero fermentum, a bibendum elit venenatis. Quisque vestibulum fringilla est id varius.</p>
    </div>
<?php
session_start();
if (!isset($_SESSION["username"])) {
  header("Location: login.php");
  exit();
}
echo "Вы успешно авторизованы!";

// Кнопка выхода
echo '<form method="post" action="logout.php">';
echo '<button type="submit" name="logout">Выход</button>';
echo '</form>';

// Обрабатываем нажатие кнопки выхода
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
</body>
</html>