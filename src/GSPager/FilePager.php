<?php

namespace GSPager;

class FilePager extends Pager
{
    protected $filename;

    public function __construct(
        View $view,
        $filename = '.',
        int $items_per_page = 10,
        int $links_count = 3,
        $get_params = null,
        string $counter_param = 'page')
    {
        $this->filename = $filename;
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

        // Відкриваємо файл
        $fd = fopen($this->filename, 'r');

        if ($fd) {
            // Підраховуємо кількість записів у файлу
            while (!feof($fd)) {
                fgets($fd, 10000);
                ++$countline;
            }
            // Закриваємо файл
            fclose($fd);
        }

        return $countline;
    }

    public function getItems()
    {
        // Поточна сторінка
        $current_page = $this->getCurrentPage();

        // Кількість позицій
        $total = $this->getItemsCount();

        // Загальна кількість сторінок
        $total_pages = $this->getPagesCount();

        // Перевіряємо, чи попадає запитуємий номер сторінки у інтервал від мінімального до максимального
        if ($current_page <= 0 || $current_page > $total_pages) {
            return 0;
        }

        // Витягуємо позиції поточної сторінки
        $arr = [];
        $fd = fopen($this->filename, 'r');
        if (!$fd) {
            return 0;
        }

        // Номер, починаючи з котрого слід вибирати рядки файлу
        $first = ($current_page - 1) * $this->getItemsPerPage();
        for ($i = 0; $i < $total; ++$i) {
            $str = fgets($fd, 10000);

            // Поки не досягнутий номер $first, достроково закінчуємо ітерацію
            if ($i < $first) {
                continue;
            }

            // У випадку досягнення кінця вибірки, достроково залишаємо цикл
            if ($i > $first + $this->getItemsPerPage() - 1) {
                break;
            }

            // Розміщуємо рядки файлу у масив, який буде повернутий методом
            $arr[] = $str;
        }

        fclose($fd);

        return $arr;
    }
}