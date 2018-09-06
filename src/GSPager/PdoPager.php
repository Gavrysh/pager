<?php

namespace GSPager;

class PdoPager extends Pager
{
    protected $pdo;
    protected $tablename;
    protected $where;
    protected $params;
    protected $order;

    public function __construct(
        View $view,
        $pdo,
        $tablename,
        $where = '',
        $params = [],
        $order = '',
        int $items_per_page = 10,
        int $links_count = 3,
        $get_params = null,
        string $counter_param = 'page')
    {
        $this->pdo       = $pdo;
        $this->tablename = $tablename;
        $this->where     = $where;
        $this->params    = $params;
        $this->order     = $order;

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
        // Формуємо запит на отримання загальної кількості записів у таблиці
        $query = "SELECT COUNT(*) AS total
            FROM {$this->tablename}
            {$this->where}";
        $tot = $this->pdo->prepare($query);
        $tot->execute($this->params);
        return $tot->fetch()['total'];
    }

    public function getItems()
    {
        // Поточна сторінка
        $current_page = $this->getCurrentPage();

        // Загальна кількість сторінок
        $total_pages = $this->getPagesCount();

        // Перевіряємо, чи попадає запитуємий номер сторінки у інтервал від мінімального до максимального
        if ($current_page <= 0 || $current_page > $total_pages) {
            return 0;
        }

        // Витягуємо позиції поточної сторінки
        $arr = [];

        // Номер, починаючи з котрого слід вибирати рядки таблиці
        $first = ($current_page - 1) * $this->getItemsPerPage();

        // Витягуємо позиції для поточної сторінки
        $query = "SELECT * FROM {$this->tablename}
          {$this->where}
          {$this->order}
          LIMIT $first, {$this->getItemsPerPage()}";

        $tbl = $this->pdo->prepare($query);
        $tbl->execute($this->params);

        return $results = $tbl->fetchAll();

    }
}