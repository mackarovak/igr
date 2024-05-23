<?php
$page_title = "Отзывы - Салон красоты";
$site_title = "Салон красоты";

ob_start();
?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    h2 {
        text-align: center;
        color: #333;
    }

    .review {
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 20px;
        margin: 20px 0;
    }

    h3 {
        color: #E4717A;
    }

    p {
        line-height: 1.6;
    }
</style>

<div class="container">
    <h2>Отзывы</h2>
    <div class="review">
        <h3>Отличный сервис</h3>
        <p>Я посетила салон красоты и осталась очень довольна. Профессиональные мастера, уютная атмосфера и приятные цены. Обязательно вернусь снова!</p>
    </div>
    <div class="review">
        <h3>Идеальный маникюр</h3>
        <p>Хочу выразить благодарность за идеальный маникюр. Результат превзошел ожидания, атмосфера салона тоже очень приятная. Рекомендую!</p>
    </div>
</div>

<?php
$body_content = ob_get_clean();

$footer_content = "© " . date("Y") . " Салон красоты";

include 'base.html';
?>