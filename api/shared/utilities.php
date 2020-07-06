<?php

class Utilities
{
    /**
     * @param $page
     * @param $total_rows
     * @param $records_per_page
     * @param $page_url
     * @return array
     */
    public function getPaging($page, $total_rows, $records_per_page, $page_url)
    {
        // Массив пагинации
        $paging_arr = array();

        // Кнопка для первой страницы
        $paging_arr['first'] = $page > 1 ? $page_url . 'page=1' : '';

        // Подсчёт всех товаров в базе данных для подсчета общего количества страниц
        $total_pages = ceil($total_rows / $records_per_page);

        // Диапазон ссылок для показа
        $range = 2;

        // Отображать диапазон ссылок вокруг текущей страницы
        $initial_num = $page - $range;
        $condition_limit_num = ($page + $range) + 1;

        $paging_arr['pages'] = array();
        $page_count = 0;

        for ($x = $initial_num; $x < $condition_limit_num; $x++) {
            // Убедимся, что $x > 0 И $x <= $total_pages
            if (($x > 0) && ($x <= $total_pages)) {
                $paging_arr['pages'][$page_count]['page'] = $x;
                $paging_arr['pages'][$page_count]['url'] = $page_url . 'page=' . $x;
                $paging_arr['pages'][$page_count]['current_page'] = $x == $page ? 'yes' : 'no';

                $page_count++;
            }
        }

        // Кнопка для последней страницы
        $paging_arr['last'] = $page < $total_pages ? $page_url . 'page=' . $total_pages : '';

        // Формат json
        return $paging_arr;
    }
}