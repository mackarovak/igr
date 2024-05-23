<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Салон красоты";
$site_title = "Добро пожаловать в салон красоты!";

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
            ('Тонирование волос', 'Парикмахерские услуги', 50.00, '1.jpg'),
            ('Покрытие шеллаком', 'Маникюр', 20.00, '2.jpg'),
            ('Эпиляция', 'Уход за телом', 50.00, '3.jpg'),
            ('Полировка волос', 'Парикмахерские услуги', 10.00, '5.jpg'),
            ('Снятие шеллака', 'Маникюр', 5.00, '6.png'),
            ('Массаж лица', 'Уход за телом', 50.00, '4.jpg')";
    $conn->query($sql);
}

$sql = "SELECT * FROM products";
$search_query = '';

if (isset($_GET['description']) && $_GET['description'] != '') {
    $selected_description = $conn->real_escape_string($_GET['description']);
    $sql .= " WHERE description = '" . $selected_description . "'";
}

if (isset($_GET['search']) && $_GET['search'] != '') {
    $search_query = $conn->real_escape_string($_GET['search']);
    $sql .= (strpos($sql, 'WHERE') === false ? ' WHERE' : ' AND') . " name LIKE '%" . $search_query . "%'";
}

$result = $conn->query($sql);
?>

<style>
.product {
    width: 300px;
    height: 400px;
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
select, input[type='text'] {
        padding: 10px 20px; /* Внутренние отступы */
        border: 1px solid #E4717A; /* Цвет рамки */
        border-radius: 5px;
        background-color: #FFC2D6; /* Цвет фона */
        color: #E4717A; /* Цвет текста */
        font-size: 16px;
        margin-right: 10px; /* Отступ между элементами */
    }
    .pink-button {
        padding: 10px 20px;
        border: 1px solid #E4717A;
        border-radius: 5px;
        background-color: #E4717A;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
    }

    .pink-button:hover {
        background-color: #FF6B90;
    }

    input[type='submit'], .menu a {
        padding: 10px 20px; /* Внутренние отступы */
        border: 1px solid #E4717A; /* Цвет рамки */
        border-radius: 5px;
        background-color: #E4717A; /* Цвет фона */
        color: #fff; /* Цвет текста */
        font-size: 16px;
        cursor: pointer;
    }

    input[type='submit']:hover, .menu a:hover {
        background-color: #FF6B90; /* Изменение цвета при наведении */
    }
</style>

<div class='menu'>
    <div>
        <form method='GET'>
            <select name='description'>
                <option value=''>Выберите описание</option>
                <option value='Парикмахерские услуги'>Парикмахерские услуги</option>
                <option value='Уход за телом'>Уход за телом</option>
                <option value='Маникюр'>Маникюр</option>
            </select>
            <input type='text' name='search' placeholder='Поиск по имени'>
            <input type='submit'class='button' value='Применить фильтр и поиск'>
            <a href='cart.php'>Корзина</a>
        </form>
    </div>
</div>

<div class='container'>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<div class='product-info'>";
            echo "<h2>" . $row["name"] . "</h2>";
            echo "<p>" . $row["description"] . "</p>";
            echo "<p class='price'>Цена: $" . $row["price"] . "</p>";
            echo "<button class='pink-button' onclick='addToCart(" . $row["id"] . ", \"" . $row["name"] . "\", " . $row["price"] . ")'>Записаться</button>";            echo "</div>";
            echo "<div class='product-image'>";
            echo "<img src='images/" . $row["image_path"] . "' alt='" . $row["name"] . "'>";
            echo "</div>";
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
$footer_content = "© 2024 Салон красоты";
include 'base.html';

$conn->close();
?>