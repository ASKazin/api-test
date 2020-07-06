<?php

class Product
{
    // Подключаемся к БД
    private $connection;
    private $table_name = 'products';

    // Свойства объета
    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $category_name;
    public $created;

    /**
     * Product constructor
     * Конструктор для соединения с БД
     *
     * @param $db
     */
    public function __construct($db)
    {
        $this->connection = $db;
    }

    /**
     * Метод read()
     * Получение товара
     *
     * @return mixed
     */
    function read()
    {
        // Выбираем все записи в таблице
        $query = 'SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created FROM ' . $this->table_name . ' p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created DESC';

        // Подготавливаем запрос
        $stmt = $this->connection->prepare($query);

        // Выполняем запрос
        $stmt->execute();

        return $stmt;
    }

    /**
     * Метод readOne()
     * Получение одного товара
     *
     * @return mixed
     */
    function readOne()
    {
        // Выбираем одну запись в таблице
        $query = 'SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created FROM ' . $this->table_name . ' p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ? LIMIT 0,1';

        // Подготавливаем запрос
        $stmt = $this->connection->prepare($query);

        // Привязываем id товара, который будет прочитан
        $stmt->bindParam(1, $this->id);

        // Выполняем запрос
        $stmt->execute();

        // Получаем извлеченную строку
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Устанавливаем значения свойств объекта
        $this->name = $row['name'];
        $this->price = $row['price'];
        $this->description = $row['description'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
    }

    /**
     * Метод create()
     * Создание товаров
     *
     * @return boolean
     */
    function create()
    {
        // Запрос для вставки (создания) записей
        $query = 'INSERT INTO ' . $this->table_name . ' SET name = :name, price = :price, description = :description, category_id = :category_id, created = :created';

        // Подготавливаем запрос
        $stmt = $this->connection->prepare($query);

        // Очищаем данные для вставки
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->created = htmlspecialchars(strip_tags($this->created));

        // Привязываем значения
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':created', $this->created);

        // Выполняем запрос
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Метод update()
     * Обновление товара
     *
     * @return boolean
     */
    function update()
    {
        // Запрос для обновления записи (товара)
        $query = 'UPDATE ' . $this->table_name . ' SET name = :name, price = :price, description = :description, category_id = :category_id WHERE id = :id';

        // Подготавливаем запрос
        $stmt = $this->connection->prepare($query);

        // Очищаем данные
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Привязываем значения
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        // Выполняем запрос
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Метод delete()
     * Удаление товара
     *
     * @return bool
     */
    function delete()
    {
        // Запрос для удаления записи (товара)
        $query = 'DELETE FROM ' . $this->table_name . ' WHERE id = ?';

        // Подготовка запроса
        $stmt = $this->connection->prepare($query);

        // Очищаем данные
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Привязываем id записи для удаления
        $stmt->bindParam(1, $this->id);

        // Выполняем запрос
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Метод search()
     * Поиско товаров
     *
     * @param $keywords
     * @return mixed
     */
    function search($keywords)
    {
        // Выборка по всем записям
        $query = 'SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created FROM ' . $this->table_name . ' p LEFT JOIN categories c ON p.category_id = c.id WHERE p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ? ORDER BY p.created DESC';

        // Подготовка запроса
        $stmt = $this->connection->prepare($query);

        // Очищаем данные
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        // Привязываем параметры
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        // Выполняем запрос
        $stmt->execute();

        return $stmt;
    }

    /**
     * Метод readPaging()
     * Чтение товаров с пагинацией
     *
     * @param $from_record_num
     * @param $records_per_page
     * @return mixed
     */
    public function readPaging($from_record_num, $records_per_page)
    {
        // Выборка
        $query = 'SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created FROM ' . $this->table_name . ' p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created DESC LIMIT ?, ?';

        // Подготовка запроса
        $stmt = $this->connection->prepare($query);

        // Свяжем значения переменных
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        // Выполняем запрос
        $stmt->execute();

        // Вернём значения из базы данных
        return $stmt;
    }

    /**
     * Метод count()
     * Используется для пагинации товаров
     *
     * @return mixed
     */
    public function count()
    {
        $query = 'SELECT COUNT(*) as total_rows FROM ' . $this->table_name . '';

        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }
}