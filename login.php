<?php
// Запустить сессию
session_start();

// Если пользователь уже авторизован, перенаправить на страницу новостей
if (isset($_SESSION['username'])) {
    header('Location: news.php');
    exit();
}

// Обработка данных формы при отправке
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получить имя пользователя и пароль из формы
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Подключение к базе данных
    $db_host = 'localhost';
    $db_name = 'News';
    $db_user = 'root';
    $db_pass = '';
    $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);

    // Поиск пользователя в базе данных
    $query = $db->prepare('SELECT username FROM users WHERE username = :username AND password = :password');
    $query->bindParam(':username', $username);
    $query->bindParam(':password', $password);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Если пользователь найден, сохранить его имя в сессии
        $_SESSION['username'] = $user['username'];
        // Перенаправить на страницу новостей
        header('Location: login.php');
        exit();
    } else {
        // Если пользователь не найден, показать сообщение об ошибке
        $error = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($error)): ?>
        <p><?= $error ?></p>
    <?php endif; ?>
    <form method="post">
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
        </div>
        <div>
            <button type="submit">Login</button>
            <button onclick="location.href='register.php'">Регистрация</button>
        </div>
    </form>
</body>
</html>
