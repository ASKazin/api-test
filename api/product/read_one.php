<?php
// Необходимые HTTP-заголовки
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

// Подключение БД и файл, содержащий объекты
include_once '../config/database.php';
include_once '../objects/product.php';

// Получаем соединение с БД
$database = new Database();
$db = $database->getConnection();

// Инициализация объекта
$product = new Product($db);

// Устанавливаем свойство ID для записи для чтения
$product->id = isset($_GET['id']) ? $_GET['id'] : die();

// Читаем детали товара для редактирования
$product->readOne();

if ($product->name != null) {
    // Создаем массив
    $product_arr = array(
        "id" => $product->id,
        "name" => $product->name,
        "description" => $product->description,
        "price" => $product->price,
        "category_id" => $product->category_id,
        "category_name" => $product->category_name
    );

    // Устанавливаем код ответа - 200 OK
    http_response_code(200);

    // Вывод в формате JSON
    echo json_encode($product_arr);
} else {
    // Устанавливаем код ответа - 404 Не найдено
    http_response_code(404);

    // Сообщаем пользователю, что товар не существует
    echo json_encode(array('message' => 'Товар не существует.'), JSON_UNESCAPED_UNICODE);
}