<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Регистрация пользователя";
$site_title = "Регистрация нового пользователя";

ob_start();
?>

<style>
    .center {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        height: 100vh;
        padding-top: 50px;
    }
    .register-form {
        width: 300px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
</style>

<div class="center">
    <div class="register-form">
        <h2>Регистрация</h2>
        <form method="POST" action="register_process.php">
            <input type="text" name="username" placeholder="Имя пользователя" required><br><br>
            <input type="password" name="password" placeholder="Пароль" required><br><br>
            <button type="submit">Зарегистрироваться</button>
        </form>
    </div>
</div>

<?php
$body_content = ob_get_clean();
$footer_content = "© 2024 Мой интернет-магазин. Все права защищены.";
include 'base.html';
?>