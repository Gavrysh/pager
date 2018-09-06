<?php ## Посторінкова навігація по файлу
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once './vendor/autoload.php';

$obj = new \GSPager\FilePager(
    new \GSPager\PageList(),
    'largetextfile.txt');

// Вміст поточної сторінки
foreach ($obj->getItems() as $line) {
    echo htmlspecialchars($line).'<br>';
}

// Посторінкова навігація
echo '<p>'.$obj.'</p>';