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
// Установка параметров подключения к БД
$host = 'localhost';
$dbname = 'News';
$username = 'root';
$password = '';

// Устанавливаем соединение с БД
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Включаем режим выброса исключений при ошибках
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Обрабатываем ошибку подключения
    die("Не удалось подключиться к базе данных: " . $e->getMessage());
}

try {
  $stmt = $pdo->prepare("
      SELECT COUNT(news.id)
      FROM news
      LEFT JOIN `groups` ON news.group_id = `groups`.id
      WHERE `groups`.name = :group_name
  ");
  $stmt->execute(['group_name' => 'Child']);
  $count = $stmt->fetchColumn();
} catch(PDOException $e) {
  // Обрабатываем ошибку запроса
  die("Ошибка выполнения запроса: " . $e->getMessage());
}

$limit = 5; // Количество новостей на странице
$pages = ceil($count / $limit); // Количество страниц
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Текущая страница
$offset = ($page - 1) * $limit; // Смещение для выборки новостей на текущей странице

// Получаем новости, относящиеся к группе "Child" для текущей страницы
try {
  $stmt = $pdo->prepare("
      SELECT news.id, news.title, news.text, news.date, `groups`.name as group_name, GROUP_CONCAT(categories.name) as category_names
      FROM news
      LEFT JOIN news_categories ON news.id = news_categories.news_id
      LEFT JOIN categories ON news_categories.category_id = categories.id
      LEFT JOIN `groups` ON news.group_id = `groups`.id
      WHERE `groups`.name = :group_name
      GROUP BY news.id
      ORDER BY news.date DESC
      LIMIT :limit OFFSET :offset
  ");
  $stmt->bindValue(':group_name', 'Child', PDO::PARAM_STR);
  $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
  $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
  $stmt->execute();
  $news = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
  // Обрабатываем ошибку запроса
  die("Ошибка выполнения запроса: " . $e->getMessage());
}

if (count($news) > 0) {
  foreach ($news as $item) {
      echo "<h2>" . $item['title'] . "</h2>";
      echo "<p>" . $item['text'] . "</p>";
      echo "<p>Дата: " . $item['date'] . "</p>";
      echo "<p>Группа: " . $item['group_name'] . "</p>";
      echo "<p>Категории: " . $item['category_names'] . "</p>";
      echo "<hr>";
  }
} else {
  echo "Новости не найдены";
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
          echo "<a href='group-child.php?page=$i'>$i</a>";
      }
  }
  echo "</div>";
}
}
?>

</body>
</html>