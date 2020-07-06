<?php
// Необходимые HTTP-заголовки
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

// Подключение БД и файл, содержащий объекты
include_once '../config/database.php';
include_once '../objects/product.php';

// Получаем соединение с БД
$database = new Database();
$db = $database->getConnection();

// Инициализация объекта
$product = new Product($db);

// Запрашиваем товары
$stmt = $product->read();
$num = $stmt->rowCount();

// Проверка, найдено ли больше 0 записей
if ($num > 0) {
    // Массив товаров
    $products_arr = array();
    $products_arr['records'] = array();

    // Получаем содержимое нашей таблицы
    // fetch() быстрее, чем fetchAll()
    // TODO: найти замеры скорости
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Извлекаем строку
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

    // Выводим дданные о товаре в формате JSON
    echo json_encode($products_arr);
} else {
    // Устанавливаем код ответа - 404 Не найдено
    http_response_code(404);

    // Сообщаем пользователю, что товары не найдены
    echo json_encode(array('message' => 'Товары не найдены.'), JSON_UNESCAPED_UNICODE);
}