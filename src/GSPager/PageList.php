<?php

namespace GSPager;

class PageList extends View
{
    public function render(Pager $pager)
    {
        // Об'єкт посторінкової навігації
        $this->pager = $pager;

        // Рядок для результату що повертається
        $return_page = '';

        // Поточний номер сторінки
        $current_page = $this->pager->getCurrentPage();

        // Загальна кількість сторінок
        $total_pages = $this->pager->getPagesCount();

        // Посилання на першу сторінку
        $return_page .= $this->link('&lt;&lt;', 1).' ... ';

        // Виводимо посилання ʼНазадʼ, якщо це не перша сторінка
        if ($current_page != 1) {
            $return_page .= $this->link('&lt;', $current_page - 1).' ... ';
        }

        // Виводимо попередні елементи
        if ($current_page > $this->pager->getVisibleLinkCount() + 1) {
            $init = $current_page - $this->pager->getVisibleLinkCount();
            for ($i = $init; $i < $current_page; ++$i) {
                $return_page .= $this->link($i, $i) . ' ';
            }
        } else {
            for ($i = 1; $i < $current_page; ++$i) {
                $return_page .= $this->link($i, $i) . ' ';
            }
        }

        // Виводимо поточний елемент
        $return_page .= "$i ";

        // Виводимо наступні елементи
        if ($current_page + $this->pager->getVisibleLinkCount() < $total_pages) {
            $cond = $current_page + $this->pager->getVisibleLinkCount();
            for ($i = $current_page + 1; $i <= $cond; ++$i) {
                $return_page .= $this->link($i, $i).' ';
            }
        } else {
            for ($i = $current_page + 1; $i <= $total_pages; ++$i) {
                $return_page .= $this->link($i, $i).' ';
            }
        }

        // Виводимо посилання попереду, якщо це не остання сторінка
        if ($current_page != $total_pages) {
            $return_page .= " ... ".$this->link('&gt;', $current_page + 1);
        }

        // Посилання на останню сторінку
        $return_page .= " ... ".$this->link('&gt;&gt;', $total_pages);

        return $return_page;
    }
}