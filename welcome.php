<?php
session_start();

// Подключение к базе данных
$servername = "mysql";
$username = "nuancce";
$password = "1";
$dbname = "database";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка соединения: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_password'])) {
    $newPassword = $_POST['new_password'];
    $username = $_SESSION['username'];

    // Хэширование нового пароля
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Обновление пароля в базе данных
    $sql = "UPDATE users SET password='$hashedPassword' WHERE username='$username'";
    if ($conn->query($sql) === TRUE) {
        $message = "Пароль успешно изменен!";
    } else {
        $message = "Ошибка при изменении пароля: " . $conn->error;
    }
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

$page_title = "Добро пожаловать - Магазин игрушек";
$site_title = "Добро пожаловать в наш магазин игрушек!";
$menu_content = "<a href='main.php'>Главная</a> | <a href='contacts.php'>Контакты</a> | <a href='register.php'>Регистрация</a>";
$body_content = "
<div style='text-align:center; margin-top: 50px;'>
    <h1>Добро пожаловать, " . $_SESSION['username'] . "!</h1>
    <form method='POST'>
        <label for='new_password'>Введите новый пароль:</label><br>
        <input type='password' id='new_password' name='new_password' required><br><br>
        <button type='submit'>Изменить пароль</button>
    </form>
    <form method='POST'>
        <button type='submit' name='logout'>Выход</button>
    </form>
    <p style='color: blue;'><?php if(isset($message)) { echo $message; } ?></p>
</div>";
$footer_content = "© 2021 Магазин игрушек. Все права защищены.";

include 'base.html';

$conn->close();
?>