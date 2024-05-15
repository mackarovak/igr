<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servername = "mysql";
$username = "nuancce";
$password = "1";
$dbname = "database";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка соединения: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: login.php"); // Перенаправляем на страницу входа при успешной регистрации
        exit();
    } else {
        echo "Ошибка при регистрации пользователя: " . $conn->error;
    }
}

$conn->close();
?>