<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = [
        'id' => $data['id'],
        'name' => $data['name'],
        'price' => $data['price']
    ];

    // Сохранение данных корзины в сессию
    $_COOKIE['cart'] = json_encode($_SESSION['cart']);
    setcookie('cart', $_COOKIE['cart'], time() + 3600, '/');

    $response = ['message' => 'Услуга добавлена в корзину'];
    echo json_encode($response);
}
?>