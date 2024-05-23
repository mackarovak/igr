<style>
    .centered {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 200%; /* Задайте ширину в зависимости от требований дизайна */
    }
    .pink-text {
        text-align: center;
        color: #EB5284; /* Глубокий розовый цвет */
        font-family: 'Arial', sans-serif; /* Замените шрифт на красивый */
    }
    .gray-text {
        text-align: left;
        color: #888888; /* Приятный серый цвет */
    }
    .image-text-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 800px; /* Задайте максимальную ширину контейнера */
        margin: 0 auto;
    }
    .image {
        flex: 1;
        text-align: right; /* Изменено на право */
    }
    .text {
        flex: 1;
    }
</style>

<?php

$page_title = "Сертификаты";
$site_title = "Салон красоты";
$menu_content = "<a href='main.php' class='button'>Услуги</a> | <a href='ser.html' class='button'>Сертификат</a> | <a href='register.php' class='button'>Регистрация</a> | <a href='login.php' class='button'>Вход</a> | <a href='otzyv.php' class='button'>Отзывы</a> | <a href='contacts.php' class='button'>Контакты</a>";

$body_content = "
    <div class='image-text-container'>
        <div class='text'>
            <h1 class='gray-text'>Окунись в мир красоты с нами!</h1>
        </div>
        <div class='image'>
            <img src='images/face.jpg' alt='Сертификат 1' class='centered'>
        </div>
    </div>
";

$footer_content = "© 2024 Салон красоты";

include 'base.html';
?>