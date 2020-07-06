<?php
// Необходимые HTTP-заголовки
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Получаем соединение с БД
include_once '../config/database.php';

// Создание объекта товара
include_once '../objects/product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

// Получаем отправленные данные
$data = json_decode(file_get_contents('php://input'));

// Убеждаемся, что данные не пусты
if (
    !empty($data->name) &&
    !empty($data->price) &&
    !empty($data->description) &&
    !empty($data->category_id)
) {
    // Устанавливаем значения свойств товара
    $product->name = $data->name;
    $product->price = $data->price;
    $product->description = $data->description;
    $product->category_id = $data->category_id;
    $product->created = date('Y-m-d H:i:s');

    // Создаем товар
    if ($product->create()) {
        // Устанавливаем код ответа - 201 Создано
        http_response_code(201);

        // Сообщаем пользователю
        echo json_encode(array('message' => 'Товар был создан.'), JSON_UNESCAPED_UNICODE);
    } else {
        // Устанавливаем код ответа - 503 Сервис недоступен
        http_response_code(503);

        // Сообщаем пользователю
        echo json_encode(array('message' => 'Невозможно создать товар.'), JSON_UNESCAPED_UNICODE);
    }
} else {
    // Устанавливаем код ответа - 400 Неверный запрос
    http_response_code(400);

    // Сообщаем пользователю
    echo json_encode(array('message' => 'Невозможно создать товар. Данные неполные.'), JSON_UNESCAPED_UNICODE);
}