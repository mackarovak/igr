<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Подключение к базе данных
$servername = "mysql";
$username = "nuancce";
$password = "1";
$dbname = "database";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка соединения: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_password'])) {
    $newPassword = $_POST['new_password'];
    $username = $_SESSION['username'];

    // Хэширование нового пароля
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Обновление пароля в базе данных
    $sql = "UPDATE users SET password='$hashedPassword' WHERE username='$username'";
    if ($conn->query($sql) === TRUE) {
        $message = "Пароль успешно изменен!";
    } else {
        $message = "Ошибка при изменении пароля: " . $conn->error;
    }
}

if (isset($_SESSION['ordered_items'])) {
    $ordered_items = $_SESSION['ordered_items'];
} else {
    $ordered_items = [];
}

if (isset($_POST['add_to_cart'])) {
    // Добавление товара в корзину
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    $ordered_items[] = array('id' => $product_id, 'name' => $product_name, 'price' => $product_price);
    $_SESSION['ordered_items'] = $ordered_items;
}

if (isset($_POST['logout'])) {
    unset($_SESSION['username']);
    header("Location: login.php");
    exit;
}

// Получение и отображение данных из таблицы reservations
$reservations_query = "SELECT * FROM reservations WHERE customer_name = '{$_SESSION['username']}'";
$reservations_result = $conn->query($reservations_query);

ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Заказы</title>
</head>
<body>
    <h1>Ваши заказы:</h1>
    <?php
    if ($reservations_result->num_rows > 0) {
        while($row = $reservations_result->fetch_assoc()) {
            echo "<p>Название товара: " . $row["product_name"] . " - Цена: " . $row["price"] . "</p>";
        }
    } else {
        echo "<p>У вас пока нет заказов.</p>";
    }
    ?>
</body>
</html>


<div style="background-color: white; padding: 20px; margin-top: 20px; text-align: center;">
    <?php
    if (!empty($ordered_items)) {
        echo "<h2 style='color: #E4717A;'>Ваши заказанные товары:</h2>";
        echo "<ul style='list-style-type: none; padding: 0;'>";
        foreach ($ordered_items as $product) {
            echo "<li>{$product['name']} - Цена: {$product['price']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: #E4717A; font-size: 24px;'>Вы еще не сделали заказ</p>";
    }
    ?>
</div>

<style>
     body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }

    .center {
        display: flex;
        justify-content: center;
        align-items: flex-start; /* Изменено на flex-start */
        height: 100vh;
    }

    .register-form {
        width: 350px;
        padding: 30px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #FFF; /* Белый фон */
        text-align: center;
        margin-top: 20px; /* Уменьшенный отступ сверху */
    }

    .register-form input[type="password"] {
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
        border: 1px solid #ccc;
        width: 100%;
    }

    .register-form button {
        padding: 10px 20px;
        margin-top: 10px;
        border: none;
        border-radius: 5px;
        background-color: #E4717A;
        color: #fff;
        font-weight: bold;
        cursor: pointer;
    }
</style>

<div class="center">
    <div class="register-form">
        <h2 style='color: #E4717A;'>Личный кабинет</h2>
        <p>Добро пожаловать, <?php echo $_SESSION['username']; ?>!</p>
        <form method='POST'>
            <label for='new_password'>Введите новый пароль:</label><br>
            <input type='password' id='new_password' name='new_password' required><br><br>
            <button type='submit'>Изменить пароль</button>
        </form>
        <form method='POST'>
            <button type='submit' name='logout'>Выход</button>
        </form>
        <p style='color: #E4717A;'><?php if(isset($message)) { echo $message; } ?></p>
    </div>
</div>
<?php
$body_content = ob_get_clean();

$page_title = "Личный кабинет - Салон красоты";
$site_title = "Личный кабинет";
$menu_content = "<a href='main.php'>Главная</a> | <a href='contacts.php'>Контакты</a>";
$footer_content = "© 2024 Салон красоты";

include 'base.html';

$conn->close();
?>