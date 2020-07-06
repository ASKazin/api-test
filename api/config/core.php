<?php
// Показывать сообщения об ошибках
// Установить в 0 на продакшене
ini_set('display_errors', 1);
error_reporting(E_ALL);

// URL домашней страницы
$home_url = "path_to/api/";

// Страница указана в параметре URL, страница по умолчанию одна
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Установка количества записей на странице
$records_per_page = 5;

// Расчёт для запроса предела записей
$from_record_num = ($records_per_page * $page) - $records_per_page;