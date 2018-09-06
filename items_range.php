<?php ## Використання представлення ItemsRange
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once './vendor/autoload.php';

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=test',
        'gases',
        'bdoZY3',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $obj = new \GSPager\PdoPager(
        new \GSPager\ItemsRange(),
        $pdo,
        'languages');

    // Вміст поточної сторінки
    foreach ($obj->getItems() as $language) {
        echo htmlspecialchars($language['name']).'<br>';
    }

    // Посторінкова навігація
    echo '<p>'.$obj.'</p>';
} catch (PDOException $e) {
    echo '<b>Помилка!</b> '.$e->getMessage();
}