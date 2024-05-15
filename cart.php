<?php
session_start();

$page_title = "Корзина";
$site_title = "Магазин игрушек - Корзина";

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

ob_start();

// Проверка наличия корзины в сессии
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Ваша корзина пуста</p>";
} else {
    echo "<h2>Ваша корзина:</h2>";
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product) {
        echo "<li>{$product['name']} - Цена: {$product['price']} <form method=\"post\"><input type=\"hidden\" name=\"remove_id\" value=\"{$product['id']}\"><input type=\"submit\" name=\"remove\" value=\"Удалить\"></form></li>";
    }
    echo "</ul>";
    
    echo "<form method=\"post\"><input type=\"submit\" name=\"place_order\" value=\"Оформить заказ\"></form>";
}

// Обработка удаления продукта из корзины
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    removeFromCart($_POST['remove_id']);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Обработка оформления заказа
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    placeOrder();
    echo "<p>Ваш заказ успешно оформлен. Спасибо за покупку!</p>";
    // Можно также добавить дополнительную информацию о заказе, например номер заказа и детали
}

$body_content = ob_get_clean();
$footer_content = "© 2024 Мой интернет-магазин. Все права защищены.";
include 'base.html';
?>