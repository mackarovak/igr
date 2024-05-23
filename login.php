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
?>

<?php
$page_title = "Вход";
$site_title = "Вход на сайт";

ob_start();
?>

<style>
     body {
        margin: 0;
        padding: 0;
    }
    .center {
        display: flex;
        justify-content: center;
        align-items: flex-start; /* Изменено на flex-start */
        height: 100vh;
    }
    .register-form {
        width: 350px; /* Увеличенная ширина формы */
        padding: 30px; /* Увеличенный внутренний отступ */
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #FFF; /* Белый фон */
        text-align: center;
        margin-top: 50px; /* Отступ сверху */
    }
    .register-form input[type="text"],
    .register-form input[type="password"] {
        padding: 10px;
        margin: 3px 0; /* Уменьшенные отступы */
        border-radius: 5px;
        border: 1px solid #ccc;
    }
    .register-form button {
        padding: 10px 20px;
        margin-top: 10px; /* Отступ над кнопкой */
        border: none;
        border-radius: 5px;
        background-color: #E4717A; /* Цвет кнопки */
        color: #fff;
        font-weight: bold;
        cursor: pointer;
    }
</style>

<div class="center">
<div class="register-form">
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
</div>

<?php
$body_content = ob_get_clean();
$footer_content = "© 2024 Салон красоты";
include 'base.html';
?>