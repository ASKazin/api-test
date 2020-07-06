<?php
// Необходимые HTTP-заголовки
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

// Подключение необходимых файлов
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/product.php';

// Создание подключения к БД
$database = new Database();
$db = $database->getConnection();

// Инициализируем объект
$product = new Product($db);

// Получаем ключевые слова
$keywords = isset($_GET['s']) ? $_GET['s'] : '';

// Запрос товаров
$stmt = $product->search($keywords);
$num = $stmt->rowCount();

// Проверяем, найдено ли больше 0 записей
if ($num > 0) {
    // Массив товаров
    $products_arr = array();
    $products_arr["records"] = array();

    // Получаем содержимое нашей таблицы
    // fetch() быстрее чем fetchAll()
    // TODO: найти замеры скорости
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Извлечём строку
        extract($row);

        $product_item = array(
            'id' => $id,
            'name' => $name,
            'description' => html_entity_decode($description),
            'price' => $price,
            'category_id' => $category_id,
            'category_name' => $category_name
        );

        array_push($products_arr['records'], $product_item);
    }

    // Устанавливаем код ответа - 200 OK
    http_response_code(200);

    // Покажем товары
    echo json_encode($products_arr);
} else {
    // Устанавливаем код ответа - 404 Ничего не найдено
    http_response_code(404);

    // Сообщаем пользователю, что товары не найдены
    echo json_encode(array('message' => 'Товары не найдены.'), JSON_UNESCAPED_UNICODE);
}