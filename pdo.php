<?php ## Посторінкова навігація таблиці languages
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once './vendor/autoload.php';

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=test',
        'gases',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $obj = new \GSPager\PdoPager(
        new \GSPager\PageList(),
        $pdo,
        'languages');

    // Вміст поточної таблиці
    foreach ($obj->getItems() as $language) {
        echo htmlspecialchars($language['name']).'<br>';
    }

    // Посторінкова навігація
    echo '<p>'.@$obj.'</p>';
} catch (PDOException $e) {
    echo 'З\'єднання з базою даних не вдале! '.$e->getMessage();
}