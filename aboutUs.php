<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="style.css" rel="stylesheet" type="text/css">
  <link href="about.css" rel="stylesheet" type="text/css">
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
<div class="block">
<?php
// установка соединения с базой данных
$host = "localhost";
$dbname = "News";
$username = "root";
$password = "";
$db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

// запрос к базе данных для получения информации о группах и категориях
$sql = "SELECT g.id, g.name, COUNT(n.id) AS news_count 
        FROM `groups` g 
        LEFT JOIN news n ON n.group_id = g.id 
        GROUP BY g.id, g.name 
        ORDER BY g.name";
$stmt = $db->query($sql);
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT c.id, c.name, COUNT(nc.news_id) AS news_count
        FROM categories c
        LEFT JOIN news_categories nc ON c.id = nc.category_id
        GROUP BY c.id, c.name
        ORDER BY c.name";
$stmt = $db->query($sql);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// вывод информации о группах и категориях на страницу
echo "<h2>Группы</h2>";
foreach ($groups as $group) {
    echo "<ul>";
    echo "<li><a href=\"group-{$group['name']}.php\">{$group['name']} ({$group['news_count']})</a></li>";
    echo "</ul>";
}
echo "<hr>";
echo "<h2>Категории</h2>";
foreach ($categories as $category) {
  echo "<ul>";
  echo "<li><a href=\"category-{$category['name']}.php\">{$category['name']} ({$category['news_count']})</a></li>";
  echo "</ul>";
}
?>
</div>
</body>
</html>