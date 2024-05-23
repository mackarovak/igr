<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Регистрация пользователя";
$site_title = "Регистрация нового пользователя";

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
        margin: 10px 0; /* Увеличенные отступы */
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
        <h2>Регистрация</h2>
        <form method="POST" action="register_process.php">
            <input type="text" name="username" placeholder="Имя пользователя" required><br>
            <input type="password" name="password" placeholder="Пароль" required><br>
            <button type="submit">Зарегистрироваться</button>
        </form>
    </div>
</div>

<?php
$body_content = ob_get_clean();
$footer_content = "© 2024 Салон красоты";
include 'base.html';
?>