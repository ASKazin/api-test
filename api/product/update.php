<?php
// Необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Подключение БД и файл, содержащий объекты
include_once '../config/database.php';
include_once '../objects/product.php';

// Получаем соединение с БД
$database = new Database();
$db = $database->getConnection();

// Подготовка объекта
$product = new Product($db);

// Получаем id товара для редактирования
$data = json_decode(file_get_contents("php://input"));

// Установим id свойства товара для редактирования
$product->id = $data->id;

// Установим значения свойств товара
$product->name = $data->name;
$product->price = $data->price;
$product->description = $data->description;
$product->category_id = $data->category_id;

// Обновляем товар
if ($product->update()) {

    // Устанавливаем код ответа - 200 OK
    http_response_code(200);

    // Сообщаем пользователю
    echo json_encode(array("message" => "Товар был обновлён."), JSON_UNESCAPED_UNICODE);
} else {

    // Устанавливаем код ответа - 503 Сервис не доступен
    http_response_code(503);

    // Сообщаем пользователю
    echo json_encode(array("message" => "Невозможно обновить товар."), JSON_UNESCAPED_UNICODE);
}