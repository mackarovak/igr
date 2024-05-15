<?php


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Магазин игрушек";
$site_title = "Добро пожаловать в наш магазин игрушек!";

ob_start();

$servername = "mysql";
$username = "nuancce";
$password = "1";
$dbname = "database";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка соединения: " . $conn->connect_error);
}

// Проверка входа пользователя
if (isset($_SESSION['username'])) {
    $menu_content = "<a href='#'>Главная</a> | <a href='#'>Контакты</a> | <a href='logout.php'>Выход</a>";
} else {
    $menu_content = "<a href='#'>Главная</a> | <a href='#'>Контакты</a> | <a href='register.php'>Регистрация</a> | <a href='login.php'>Вход</a>";
}

$search_query = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2),
    image_path VARCHAR(255)
)";
$conn->query($sql);

// Создание таблицы users
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
)";
$conn->query($sql);

$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Создание таблицы "orders" (замените поля на необходимые)
$create_orders_table = "CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL
)";

$conn->query($create_orders_table);

$sql = "SELECT COUNT(*) as count FROM products";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$count = $row['count'];

if ($count == 0) {
    $sql = "INSERT INTO products (name, description, price, image_path) VALUES
            ('Чебурашка', 'Мягкая', 20.00, 'toy1.jpg'),
            ('Лего-игрушка', 'Лего', 15.00, 'toy2.jpg'),
            ('Тесто', 'Лепка', 10.00, 'toy3.jpg')";
    $conn->query($sql);
}

$sql = "SELECT * FROM products";

if (isset($_GET['description']) && $_GET['description'] != '') {
    $selected_description = $conn->real_escape_string($_GET['description']);
    $sql .= " WHERE description = '" . $selected_description . "'";
}

if ($search_query != '') {
    $search_query = $conn->real_escape_string($search_query);
    $sql .= (strpos($sql, 'WHERE') === false ? ' WHERE' : ' AND') . " name LIKE '%" . $search_query . "%'";
}

$result = $conn->query($sql);
?>

<div class='menu'>
    <div>
        <form method='GET'>
            <select name='description'>
                <option value=''>Выберите описание</option>
                <option value='Мягкая'>Мягкая</option>
                <option value='Лего'>Лего</option>
                <option value='Лепка'>Лепка</option>
            </select>
            <input type='text' name='search' placeholder='Поиск по имени'>
            <input type='submit' value='Применить фильтр и поиск'>
        </form>
    </div>
    <div>
        <a href='cart.php'>Корзина</a>
    </div>
</div>

<div class='container'>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<img src='images/" . $row["image_path"] . "' alt='" . $row["name"] . "'>";
            echo "<h2>" . $row["name"] . "</h2>";
            echo "<p>" . $row["description"] . "</p>";
            echo "<p class='price'>Цена: $" . $row["price"] . "</p>";
            echo "<button onclick='addToCart(" . $row["id"] . ", \"" . $row["name"] . "\", " . $row["price"] . ")'>Добавить в корзину</button>";
            echo "</div>";
        }
    } else {
        echo "Нет доступных товаров";
    }
    ?>
</div>

<script>
function addToCart(productId, productName, productPrice) {
    fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({id: productId, name: productName, price: productPrice})
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
    });
}
</script>

<?php
$body_content = ob_get_clean();
$footer_content = "© 2024 Мой интернет-магазин. Все права защищены.";
include 'base.html';

$conn->close();
?>