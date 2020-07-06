<?php

class Database
{
    // Учетные данные БД
    private $host = '';
    private $db_name = '';
    private $username = '';
    private $password = '';
    public $connection;

    /**
     * Соединяемся с БД
     *
     * @return mixed
     */
    public function getConnection()
    {
        $this->connection = null;

        try {
            $this->connection = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->connection->exec('set names utf8');
        } catch (PDOException $exception) {
            echo 'Connection error: ' . $exception->getMessage();
        }
        return $this->connection;
    }
}