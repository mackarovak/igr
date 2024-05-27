<?php


session_start();

if (!isset($_SESSION['username'])) {
    $response = ['error' => 'Пользователь не авторизован'];
    echo json_encode($response);
    exit();
}

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

    // Подключение к базе данных
    $servername = "mysql";
    $username = "nuancce";
    $password = "1";
    $dbname = "database";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    foreach ($_SESSION['cart'] as $product) {
        $id = $conn->real_escape_string($product['id']);
        $name = $conn->real_escape_string($product['name']);
        $price = $conn->real_escape_string($product['price']);

        $sql = "INSERT INTO orders (product_id, product_name, product_price) VALUES ('$id', '$name', '$price')";
        if ($conn->query($sql) === TRUE) {
            $response = ['success' => 'Услуга добавлена в корзину и в базу данных заказов'];
            echo json_encode($response);
        } else {
            $response = ['error' => 'Ошибка при добавлении в базу данных: ' . $conn->error];
            echo json_encode($response);
        }
    }

    $conn->close();
}
?>