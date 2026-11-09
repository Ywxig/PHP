<?php

declare(strict_types=1);

/**
 * Точка входа: «Дневник настроения» — форма, обработка и таблица записей.
 *
 * Запуск: php -S localhost:8000
 */

require __DIR__ . '/config.php';
require __DIR__ . '/src/helpers.php';
require __DIR__ . '/src/autoload.php';

use App\FormHandler;
use App\JsonStorage;
use App\Validator\DateValidator;
use App\Validator\EnumValidator;
use App\Validator\LengthValidator;
use App\Validator\RequiredValidator;

$handler = new FormHandler([
    'title' => [new RequiredValidator(), new LengthValidator(3, 80)],
    'author' => [new RequiredValidator(), new LengthValidator(2, 50)],
    'entry_date' => [new RequiredValidator(), new DateValidator()],
    'mood' => [new RequiredValidator(), new EnumValidator(array_keys(MOODS))],
    'tags' => [new EnumValidator(array_keys(TAGS))],
    'note' => [new RequiredValidator(), new LengthValidator(10, 1000)],
]);
$storage = new JsonStorage(DATA_FILE);

$errors = [];
$old = [
    'title' => '',
    'author' => '',
    'entry_date' => date('Y-m-d'),
    'mood' => '',
    'tags' => [],
    'note' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$data, $errors] = $handler->handle($_POST);
    $old = $data;
    if ($errors === []) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $storage->add($data);
        header('Location: ?saved=1'); // Post/Redirect/Get — защита от повторной отправки
        exit;
    }
}

// Сортировка (поле проверяется по белому списку COLUMNS)
$sort = isset($_GET['sort'], COLUMNS[$_GET['sort']]) ? (string) $_GET['sort'] : 'created_at';
$dir = (($_GET['dir'] ?? 'desc') === 'asc') ? 'asc' : 'desc';

$records = $storage->all();
usort($records, static function (array $a, array $b) use ($sort, $dir): int {
    $x = is_array($a[$sort] ?? '') ? implode(',', $a[$sort]) : (string) ($a[$sort] ?? '');
    $y = is_array($b[$sort] ?? '') ? implode(',', $b[$sort]) : (string) ($b[$sort] ?? '');
    return $dir === 'asc' ? $x <=> $y : $y <=> $x;
});

require __DIR__ . '/templates/layout_head.php';
require __DIR__ . '/templates/form.php';
require __DIR__ . '/templates/table.php';
require __DIR__ . '/templates/layout_foot.php';
