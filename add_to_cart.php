<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Добавление товара в корзину
    $_SESSION['cart'][] = [
        'id' => $data['id'],
        'name' => $data['name'],
        'price' => $data['price']
    ];

    $response = ['message' => 'Товар успешно добавлен в корзину'];
    echo json_encode($response);
}
?>