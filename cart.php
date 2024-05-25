<?php
// Устанавливаем время жизни сессии в секундах (например, 1 неделя)
$session_lifetime = 604800; // 60 секунд * 60 минут * 24 часа * 7 дней

// Устанавливаем время жизни cookie сессии того же пользователя (например, 0 - до закрытия браузера)
$cookie_lifetime = 0;

session_set_cookie_params($session_lifetime, '/', '', false, true);
ini_set('session.gc_maxlifetime', $session_lifetime);

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Перенаправляем на страницу входа, если пользователь не авторизован
    exit();
}

$page_title = "Корзина";
$site_title = "Салон красоты - Корзина";

function removeFromCart($productId) {
    if(isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $product) {
            if ($product['id'] == $productId) {
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                break;
            }
        }
    }
}

function placeOrder() {
    $_SESSION['ordered_items'] = $_SESSION['cart'];
    $_SESSION['cart'] = [];
}

function calculateTotalPrice() {
    $totalPrice = 0;
    foreach ($_SESSION['cart'] as $product) {
        $totalPrice += $product['price'];
    }
    return $totalPrice;
}

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
    $totalPrice = calculateTotalPrice();
    echo "<p style='text-align: center; margin-top: 10px;'>Общая стоимость: $totalPrice</p>";
    echo "<form method='post' style='text-align: center; margin-top: 20px;'>";
    echo "<input type='submit' name='remove_all' value='Удалить все товары' class='pink-button'>";
    echo "<br>"; 
    echo "<br>";
    echo "<input type='submit' name='place_order' value='Оформить заказ' class='pink-button'>";
    echo "</form>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    placeOrder();
    echo "<p style='text-align: center; margin-top: 20px; color: #E4717A; font-size: 18px;'>Вы успешно оформили заказ. Спасибо за покупку!</p>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_all'])) {
    $_SESSION['cart'] = [];
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $productId = $_POST['remove_id'];
    removeFromCart($productId);
    header("Location: {$_SERVER['PHP_SELF']}");
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