<?php

class Category
{
    // Соединение с БД и таблицей 'categories'
    private $connection;
    private $table_name = "categories";

    // Свойства объекта
    public $id;
    public $name;
    public $description;
    public $created;

    /**
     * Category constructor.
     *
     * @param $db
     */
    public function __construct($db)
    {
        $this->connection = $db;
    }

    /**
     * Метод readAll()
     *
     * @return mixed
     */
    public function readAll()
    {
        // Выборка всех данных
        $query = 'SELECT id, name, description FROM ' . $this->table_name . ' ORDER BY name';

        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Метод read()
     *
     * @return mixed
     */
    public function read()
    {
        // Выбираем все данные
        $query = 'SELECT id, name, description FROM ' . $this->table_name . ' ORDER BY name';

        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        return $stmt;
    }
}