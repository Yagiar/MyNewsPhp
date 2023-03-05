<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link href="style.css" rel="stylesheet" type="text/css">
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
<div class="news">
<?php
// проверяем, авторизован ли пользователь
session_start();
if (!isset($_SESSION["username"])) {
  header("Location: login.php");
  exit();
}
// Обрабатываем нажатие кнопки выхода
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
else
{
  $host='localhost';
$dbname='News';
$username='root';
$password='';
try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Ошибка подключения к базе данных: " . $e->getMessage();
  exit();
}
  // определяем текущую страницу
if (isset($_GET["page"])) {
  $page = $_GET["page"];
} else {
  $page = 1;
}
$limit = 5; // количество новостей на странице

// получаем общее количество новостей
$stmt = $pdo->prepare("SELECT COUNT(*) FROM news");
$stmt->execute();
$total = $stmt->fetchColumn();

// вычисляем количество страниц
$pages = ceil($total / $limit);

// вычисляем смещение для запроса
$offset = ($page - 1) * $limit;

// получаем новости для текущей страницы
$stmt = $pdo->prepare("SELECT news.title, news.text, news.date, `groups`.name AS group_name, GROUP_CONCAT(categories.name SEPARATOR ', ') AS category_names
                      FROM news
                      INNER JOIN `groups` ON news.group_id = `groups`.id
                      INNER JOIN news_categories ON news.id = news_categories.news_id
                      INNER JOIN categories ON news_categories.category_id = categories.id
                      GROUP BY news.id
                      ORDER BY news.date DESC
                      LIMIT :limit OFFSET :offset");
$stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
$stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
$stmt->execute();
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);

// выводим новости
foreach ($news as $item) {
  echo "<h2>{$item['title']}</h2>";
  echo "<p>{$item['text']}</p>";
  echo "<p>Дата: {$item['date']}</p>";
  echo "<p>Группа: {$item['group_name']}</p>";
  echo "<p>Категории: {$item['category_names']}</p>";
  echo "<hr>";
}
echo "</div>";
// Кнопка выхода
echo '<form method="post" action="logout.php">';
echo '<button type="submit" name="logout">Выход</button>';
echo '</form>';
// выводим навигацию по страницам
if ($pages > 1) {
  echo "<div class='pagination'>";
  for ($i = 1; $i <= $pages; $i++) {
      if ($i == $page) {
          echo "<span>$i</span>";
      } else {
          echo "<a href='news.php?page=$i'>$i</a>";
      }
  }
  echo "</div>";
}
}
?>

</body>
</html>