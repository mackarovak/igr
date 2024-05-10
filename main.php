<?php
$servername = "mysql"; // Имя хоста базы данных (имя сервиса MySQL в Docker Compose)
$username = "nuancce";
$password = "1";
$dbname = "database";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка соединения: " . $conn->connect_error);
}

// Поиск по имени
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Создаем таблицу products, если она не существует
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2),
    image_path VARCHAR(255)
)";
$conn->query($sql);

// Добавляем товары в таблицу, если их нет
$sql = "SELECT COUNT(*) as count FROM products";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$count = $row['count'];

if ($count == 0) {
    $sql = "INSERT INTO products (name, description, price, image_path) VALUES
            ('Чебурашка', 'Мягкая', 20.00, 'toy1.jpg'),
            ('Игрушка 2', 'Лего', 15.00, 'toy2.jpg'),
            ('Игрушка 3', 'Лепка', 10.00, 'toy3.jpg')";
    $conn->query($sql);
}

// Выводим товары из базы данных
$sql = "SELECT * FROM products";

// Применяем фильтр по описанию, если выбран
if (isset($_GET['description']) && $_GET['description'] != '') {
    $selected_description = $_GET['description'];
    $sql .= " WHERE description = '" . $selected_description . "'";
}

// Применяем поиск по имени, если введен запрос
if ($search_query != '') {
    $sql .= " WHERE name LIKE '%" . $search_query . "%'";
}

$result = $conn->query($sql);

echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Магазин игрушек</title>";
echo "<style>";
echo "
    body {
        font-family: Arial, sans-serif;
        background-color: #FFA500; /* Яркий цвет фона */
        margin: 0;
        padding: 0;
    }

    .container {
        display: flex;
        background-color: #FFA500; /* Яркий цвет фона */
        justify-content: space-around;
        flex-wrap: wrap;
        max-width: 1200px;
        margin: 20px auto;
        padding: 0 20px;
    }

    .product {
        width: 300px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin: 10px;
        padding: 10px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        color: black; /* Добавляем черный цвет текста */
    }

    .product img {
        max-width: 100%;
        height: auto;
        margin-bottom: 10px;
    }

    .price {
        font-weight: bold;
        color: #27ae60;
    }

    .menu {
        background-color: #FFA500;
        color: #fff;
        padding: 10px;
        text-align: center;
    }

    .menu a {
        color: #fff;
        text-decoration: none;
        margin: 0 10px;
    }
";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<div class='menu'>";
echo "<h1>Добро пожаловать в наш магазин игрушек!</h1>";
echo "<div>";
echo "<p>";
if ($_SERVER['REQUEST_URI'] == "/glav.html") {
    echo "<a href='glav.html' class='current-page'>Главная</a> | <a href='#'>Контакты</a>";
} else {
    echo "<a href='glav.html'>Главная</a> | <a href='#'>Контакты</a>";
}

// Поле для поиска по имени
echo "<form method='GET'>";
echo "<input type='text' name='search' placeholder='Поиск по имени' value='" . $search_query . "'>";
echo "<input type='submit' value='Искать'>";
echo "</form>";

// Фильтр по описанию
echo "<form method='GET'>";
echo "<select name='description'>";
echo "<option value=''>Выберите описание</option>";
$descriptions = ['Мягкая', 'Лего', 'Лепка'];
foreach ($descriptions as $desc) {
    echo "<option value='" . $desc . "' " . ($selected_description == $desc ? 'selected' : '') . ">" . $desc . "</option>";
}
echo "</select>";
echo "<input type='submit' value='Фильтровать'>";
echo "</form>";

echo "</p>";
echo "</div>";
echo "<div class='container'>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='product'>";
        echo "<img src='images/" . $row["image_path"] . "' alt='" . $row["name"] . "'>";
        echo "<h2>" . $row["name"] . "</h2>";
        echo "<p>" . $row["description"] . "</p>";
        echo "<p class='price'>Цена: $" . $row["price"] . "</p>";
        echo "</div>";
    }
} else {
    echo "Нет доступных товаров";
}

echo "</div>";

// Копирайт
echo "<footer style='background-color: #333; color: #fff; text-align: center; padding: 10px;'>";
echo "© 2024 Мой интернет-магазин. Все права защищены.";
echo "</footer>";

echo "</body>";
echo "</html>";

$conn->close();
?>