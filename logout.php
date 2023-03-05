<?php
session_start(); // начинаем сессию
session_destroy(); // завершаем сессию

header("Location: login.php"); // перенаправляем на страницу авторизации
exit;
?>
