<?php
session_start();

$page_title = "Корзина";
$site_title = "Салон красоты - Корзина";

// Функция для удаления продукта из корзины
function removeFromCart($productId) {
    if(isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $product) {
            if ($product['id'] == $productId) {
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart'] = array_values($_SESSION['cart']); // Сбрасываем ключи массива корзины
                break;
            }
        }
    }
}

// Функция для оформления заказа
function placeOrder() {
    // Здесь можно добавить логику для сохранения заказа в базе данных или отправки уведомления о заказе
    // Например, можно создать таблицу "orders" с информацией о заказе и продуктах
    // И очистить корзину после успешного оформления заказа
    $_SESSION['cart'] = [];
}

// Проверка наличия корзины в сессии
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

ob_start();

if (empty($_SESSION['cart'])) {
    echo "<p style='text-align: center; color: #E4717A; font-size: 24px;'>Ваша корзина пуста</p>";
} else {
    echo "<h2 style='text-align: center; color: #E4717A;'>Ваша корзина:</h2>";
    echo "<div style='display: flex; flex-wrap: wrap; justify-content: center;'>";
    foreach ($_SESSION['cart'] as $product) {
        echo "<div class='product'>";
        echo "<div class='product-info'>";
        echo "<p>{$product['name']} - <span class='price'>Цена: {$product['price']}</span></p>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='remove_id' value='{$product['id']}'>";
        echo "<input type='submit' name='remove' value='Удалить' class='pink-button'>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
    }
    echo "</div>";
    
    echo "<form method='post' style='text-align: center; margin-top: 20px;'>";
    echo "<input type='submit' name='place_order' value='Оформить заказ' class='pink-button'>";
    echo "</form>";
}

// Проверка на действие по оформлению заказа
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    placeOrder();
    echo "<p style='text-align: center; margin-top: 20px; color: #E4717A; font-size: 18px;'>Вы успешно оформили заказ. Спасибо за покупку!</p>";
}

// Проверка на действие по удалению продукта из корзины
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $productId = $_POST['remove_id'];
    removeFromCart($productId);
    header("Location: {$_SERVER['PHP_SELF']}"); // Перенаправление после удаления продукта
    exit;
}

$body_content = ob_get_clean();
$footer_content = "© 2024 Салон красоты";
include 'base.html';
?>
<style>
    /* Стили для кнопок */
    .pink-button {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: #E4717A; /* Розовый цвет кнопки */
        color: #fff; /* Белый цвет текста */
        font-weight: bold;
        cursor: pointer;
    }

    /* Изменение стиля кнопки при наведении */
    .pink-button:hover {
        background-color: #FF6B90; /* Изменение цвета при наведении */
    }

    /* Стили для формы и продукта */
    .product {
        width: 300px;
        height: 100px;
        margin: 10px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        display: inline-block;
        background-color: #f9f9f9;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .product-info {
        margin-top: 20px;
    }

    .product-image img {
        max-width: 80%;
        max-height: 200px;
        display: block;
        margin: 0 auto;
        border-radius: 10px;
    }

    .price {
        font-weight: bold;
        color: #DE5D83; /* Цвет цены */
    }

    /* Стили для меню и ссылок */
    .menu a {
        margin-top: 20px;
        text-decoration: none;
        color: #fff;
        background-color: #E4717A;
        padding: 10px 20px;
        border-radius: 5px;
        display: inline-block;
    }

    .menu a:hover {
        background-color: #FF6B90; /* Изменение цвета при наведении */
    }
</style>