
<?php

$page_title = "Сертификаты";
$site_title = "Салон красоты";
$menu_content = "<a href='main.php' class='button'>Услуги</a> | <a href='ser.html' class='button'>Сертификат</a> | <a href='register.php' class='button'>Регистрация</a> | <a href='login.php' class='button'>Вход</a> | <a href='otzyv.php' class='button'>Отзывы</a> | <a href='contacts.php' class='button'>Контакты</a>";

$body_content = "
<div class='container'>
    <div class='product'>
        <img src='images/sertificat.jpg' alt='Сертификат 1'>
        <p class='price'>Цена: $50</p>
    </div>
    <div class='product'>
        <img src='14.jpg' alt='Сертификат 2'>
        <p class='price'>Цена: $1000</p>
    </div>
</div>
";

$footer_content = "© 2024 Салон красоты";

include 'base.html';
?>

<style>
    .price {
        font-weight: bold;
        color: #DE5D83; /* Розовый цвет текста */
    }
</style>