<?php
// Необходимые HTTP-заголовки
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

// Подключение файлов для соединения с БД и файл с объектом Category
include_once '../config/database.php';
include_once '../objects/category.php';

// Создание подключения к базе данных
$database = new Database();
$db = $database->getConnection();

// Инициализация объекта
$category = new Category($db);

// Запрос для категорий
$stmt = $category->read();
$num = $stmt->rowCount();

// Проверяем, найдено ли больше 0 записей
if ($num > 0) {

    // Массив
    $categories_arr = array();
    $categories_arr['records'] = array();

    // Получим содержимое нашей таблицы
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Извлекаем строку
        extract($row);

        $category_item = array(
            'id' => $id,
            'name' => $name,
            'description' => html_entity_decode($description)
        );

        array_push($categories_arr['records'], $category_item);
    }

    // Устанавливаем код ответа - 200 OK
    http_response_code(200);

    // Покажем данные категорий в формате json
    echo json_encode($categories_arr);
} else {

    // Устанавливаем код ответа - 404 Ничего не найдено
    http_response_code(404);

    // Сообщаем пользователю, что категории не найдены
    echo json_encode(array('message' => 'Категории не найдены.'), JSON_UNESCAPED_UNICODE);
}