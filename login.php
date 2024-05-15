<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: welcome.php"); // Если пользователь уже вошел, перенаправляем на страницу приветствия
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "mysql";
    $username = "nuancce";
    $password = "1";
    $dbname = "database";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Ошибка соединения: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header("Location: welcome.php"); // Перенаправляем на страницу приветствия после успешного входа
            exit();
        } else {
            $error = "Неверный логин или пароль";
        }
    } else {
        $error = "Неверный логин или пароль";
    }

    $conn->close();
}
include 'base.html';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
</head>
<body>
    <div style="text-align: center;">
    <h2>Вход</h2>

        <?php if (isset($error)) {
            echo "<p style='color: red;'>$error</p>";
        } ?>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="username" placeholder="Имя пользователя" required><br><br>
            <input type="password" name="password" placeholder="Пароль" required><br><br>
            <button type="submit">Войти</button>
        </form>
    </div>
</body>
</html>