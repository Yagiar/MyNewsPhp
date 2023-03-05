<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet" type="text/css">
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
<div class="news">
<?php
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
else{
// Соединяемся с базой данных
$host = "localhost";
$username = "root";
$password = "";
$dbname = "News";

$dsn = "mysql:host=$host;dbname=$dbname";
$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Определяем количество новостей на странице
$limit = 5;

// Получаем номер текущей страницы
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Вычисляем смещение (offset) для запроса к базе данных
$offset = ($page - 1) * $limit;

// Выбираем новости с категорией "sql" для текущей страницы
$sql = "SELECT news.id, news.title, news.text, `groups`.name AS group_name, news.date
        FROM news
        INNER JOIN news_categories ON news.id = news_categories.news_id
        INNER JOIN categories ON news_categories.category_id = categories.id
        INNER JOIN `groups` ON news.group_id = `groups`.id
        WHERE categories.name = 'sql'
        LIMIT $limit OFFSET $offset";

$stmt = $pdo->query($sql);
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Выбираем общее количество новостей для пагинации
$sql_count = "SELECT COUNT(*) as total
              FROM news
              INNER JOIN news_categories ON news.id = news_categories.news_id
              INNER JOIN categories ON news_categories.category_id = categories.id
              INNER JOIN `groups` ON news.group_id = `groups`.id
              WHERE categories.name = 'sql'";

$stmt_count = $pdo->query($sql_count);
$count_result = $stmt_count->fetch(PDO::FETCH_ASSOC);
$total = (int)$count_result['total'];

// Выводим результаты

    foreach ($news as $item) {
        echo "<h2>" . $item['title'] . "</h2>";
        echo "<p>" . $item['text'] . "</p>";
        echo "<p>Group: " . $item['group_name'] . "</p>";
        echo "<p>Date: " . $item['date'] . "</p></li>";
        echo "<hr>";
    }
echo "</div>";
// Кнопка выхода
echo '<form method="post" action="logout.php">';
echo '<button type="submit" name="logout">Выход</button>';
echo '</form>';
    // Выводим ссылки на страницы
    $pages = ceil($total / $limit);
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
    else{
      echo "Новости с категорией 'sql' не найдены.";
    }
}
?>
</body>
</html>