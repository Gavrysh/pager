<?php

namespace GSPager;

class DirPager extends Pager
{
    protected $dirname;

    public function __construct(
        View $view,
        $dirname = '.',
        int $items_per_page = 10,
        int $links_count = 3,
        $get_params = null,
        string $counter_param = 'page')
    {
        // Видаляємо останній символ /, якщо він існує
        $this->dirname = rtrim($dirname, '/');

        // Ініціалізуємо змінні через конструктор базового класу
        parent::__construct(
            $view,
            $items_per_page,
            $links_count,
            $get_params,
            $counter_param);
    }

    public function getItemsCount()
    {
        $countline = 0;

        // Відкриваємо каталог
        if (($dir = opendir($this->dirname)) !== false) {
            while (($file = readdir($dir)) !== false) {
                // Якщо поточна позиція є файлом, підраховуємо її
                if (is_file($this->dirname.'/'.$file)) {
                    ++$countline;
                }
            }
            // Закриваємо каталог
            closedir($dir);
        }

        return $countline;
    }

    public function getItems()
    {
        // Поточна сторінка
        $current_page = $this->getCurrentPage();

        // Загальна кількість сторінок
        $total_pages = $this->getPagesCount();

        // Перевіряємо, чи потрапляє запитуваний номер сторінки
        // в інтервал від мінімального до максимального
        if ($current_page <= 0 || $current_page > $total_pages) {
            return 0;
        }

        // Витягуємо позиції поточної сторінки
        $arr = [];

        // Номер, починаючи з котрого необхідно вибрати рядки файлу
        $first = ($current_page - 1) * $this->getItemsPerPage();

        // Відкриваємо каталог
        if (($dir = opendir($this->dirname)) === false) {
            return 0;
        }

        $i = -1; // Лічильник
        while (($file = readdir($dir)) !== false) {
            // Якщо поточна позиція є файл
            if (is_file($this->dirname.'/'.$file)) {
                // Збільшуємо лічильник
                ++$i;

                // Поки не досягнутий номер $first, достроково закінчуємо ітерацію
                if ($i < $first) {
                    continue;
                }

                // У випадку досягнення кінця виборки, достроково залишаємо цикл
                if($i > $first + $this->getItemsPerPage() - 1) {
                    break;
                }

                // Розміщуємо шляхи до файлів у масив, який буде повернуто методом
                $arr[] = $this->dirname.'/'.$file;
            }
        }
        // Закриваємо каталог
        closedir($dir);

        return $arr;
    }
}