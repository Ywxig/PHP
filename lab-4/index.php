<?php

declare(strict_types=1);

/**
 * @var array $transactions
 */
$transactions = [
    [
        "id" => 1,
        "date" => "2024-01-15",
        "amount" => 100.00,
        "description" => "Payment for groceries",
        "merchant" => "SuperMart",
    ],
    [
        "id" => 2,
        "date" => "2024-02-20",
        "amount" => 75.50,
        "description" => "Dinner with friends",
        "merchant" => "Local Restaurant",
    ],
    [
        "id" => 3,
        "date" => "2024-03-05",
        "amount" => 250.00,
        "description" => "Online tech store order",
        "merchant" => "TechWorld",
    ],
    [
        "id" => 4,
        "date" => "2024-04-10",
        "amount" => 45.20,
        "description" => "Monthly subscription for music",
        "merchant" => "SoundStream",
    ],
];

/**
 * Вычисляет общую сумму всех транзакций в массиве.
 *
 * @param array $transactions Массив транзакций.
 * @return float Общая сумма транзакций.
 */
function calculateTotalAmount(array $transactions): float
{
    $total = 0.0;
    foreach ($transactions as $transaction) {
        $total += $transaction['amount'];
    }
    return $total;
}

/**
 * Ищет транзакции, описание которых содержит заданную подстроку (без учета регистра).
 *
 * @param array $transactions Массив транзакций.
 * @param string $descriptionPart Подстрока для поиска в описании.
 * @return array Массив найденных транзакций.
 */
function findTransactionByDescription(array $transactions, string $descriptionPart): array
{
    $result = [];
    foreach ($transactions as $transaction) {
        if (mb_stripos($transaction['description'], $descriptionPart) !== false) {
            $result[] = $transaction;
        }
    }
    return $result;
}

/**
 * Ищет транзакцию по идентификатору с использованием цикла foreach.
 *
 * @param array $transactions Массив транзакций.
 * @param int $id Идентификатор искомой транзакции.
 * @return array|null Массив с данными транзакции или null, если не найдена.
 */
function findTransactionByIdForeach(array $transactions, int $id): ?array
{
    foreach ($transactions as $transaction) {
        if ($transaction['id'] === $id) {
            return $transaction;
        }
    }
    return null;
}

/**
 * Ищет транзакцию по идентификатору с использованием функции array_filter.
 *
 * @param array $transactions Массив транзакций.
 * @param int $id Идентификатор искомой транзакции.
 * @return array|null Массив с данными транзакции или null, если не найдена.
 */
function findTransactionById(array $transactions, int $id): ?array
{
    $filtered = array_filter($transactions, static function (array $transaction) use ($id): bool {
        return $transaction['id'] === $id;
    });

    if (empty($filtered)) {
        return null;
    }

    return reset($filtered);
}

/**
 * Вычисляет количество дней между датой транзакции и текущим днем.
 *
 * @param string $date Дата транзакции в формате YYYY-MM-DD.
 * @return int Количество дней.
 */
function daysSinceTransaction(string $date): int
{
    $transactionDate = new DateTime($date);
    $currentDate = new DateTime('now');
    $interval = $currentDate->diff($transactionDate);

    return (int) $interval->days;
}

/**
 * Добавляет новую транзакцию в глобальный массив $transactions.
 *
 * @param int $id Уникальный идентификатор.
 * @param string $date Дата транзакции (YYYY-MM-DD).
 * @param float $amount Сумма транзакции.
 * @param string $description Описание платежа.
 * @param string $merchant Название получателя платежа.
 * @return void
 */
function addTransaction(int $id, string $date, float $amount, string $description, string $merchant): void
{
    global $transactions;
    $transactions[] = [
        "id" => $id,
        "date" => $date,
        "amount" => $amount,
        "description" => $description,
        "merchant" => $merchant,
    ];
}

/**
 * Удаляет транзакцию из глобального массива $transactions по ID.
 *
 * @param int $id Идентификатор удаляемой транзакции.
 * @return void
 */
function deleteTransaction(int $id): void
{
    global $transactions;
    $transactions = array_values(array_filter($transactions, static function (array $transaction) use ($id): bool {
        return $transaction['id'] !== $id;
    }));
}

/**
 * Сортирует массив транзакций по дате по возрастанию.
 *
 * @param array $transactions Ссылка на массив транзакций.
 * @return void
 */
function sortByDate(array &$transactions): void
{
    usort($transactions, static function (array $a, array $b): int {
        return strtotime($a['date']) <=> strtotime($b['date']);
    });
}

/**
 * Сортирует массив транзакций по сумме по убыванию.
 *
 * @param array $transactions Ссылка на массив транзакций.
 * @return void
 */
function sortByAmountDescending(array &$transactions): void
{
    usort($transactions, static function (array $a, array $b): int {
        return $b['amount'] <=> $a['amount'];
    });
}

// Добавление новой транзакции
addTransaction(5, "2024-05-01", 120.00, "Gym membership", "FitnessClub");

// Сортировка по дате
sortByDate($transactions);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление банковскими транзакциями</title>
</head>
<body>
    <h1>Управление банковскими транзакциями</h1>

    <!-- TODO (п. 1.3): здесь нужна таблица <table> с выводом $transactions через foreach
         и итоговой суммой через calculateTotalAmount($transactions) -->

         <table border='1'>
         <thead>
             <tr>
                 <!-- Заголовки столбцов -->
                 <th>Дата</th>
                 <th>Сумма</th>
                 <th>Описание</th>
             </tr>
         </thead>

         <tbody>
         <!-- Вывод транзакций -->
         <?php foreach ($transactions as $transaction): ?>
         <tr>
             <td><?= $transaction['date'] ?></td>
             <td><?= $transaction['amount'] ?></td>
             <td><?= $transaction['description'] ?></td>
         </tr>
         <?php endforeach; ?>
         <tr>
             <td colspan="3">Итого: <?= calculateTotalAmount($transactions) ?></td>
         </tr>
         </tbody>

         </table>

         <div>
             <form method="GET">
                 <input type="text" name="find_by_description" value="<?= htmlspecialchars($_GET['find_by_description'] ?? '') ?>">
                 <button type="submit">Найти</button>
             </form>
             <?php
             if (!empty($_GET['find_by_description'])) {
                 $searchResult = findTransactionByDescription($transactions, trim($_GET['find_by_description']));
                 if (!empty($searchResult)) {
                     echo "<h3>Результаты поиска:</h3><ul>";
                     foreach ($searchResult as $t) {
                         echo "<li>" . htmlspecialchars($t['date'] . " - " . $t['description'] . " (" . $t['amount'] . ")") . "</li>";
                     }
                     echo "</ul>";
                 } else {
                     echo "<p>Транзакции не найдены.</p>";
                 }
             }
             ?>
         </div>

         <div>
             <form method="GET">
                 <input type="text" name="find_by_id" value="<?= htmlspecialchars($_GET['find_by_id'] ?? '') ?>">
                 <button type="submit">Найти</button>
             </form>
             <?php
             if (!empty($_GET['find_by_id'])) {
                 $searchResult = findTransactionById($transactions, (int) trim($_GET['find_by_id']));
                 if (!empty($searchResult)) {
                     echo "<h3>Результаты поиска:</h3><ul>";
                     echo "<li>" . htmlspecialchars($searchResult['date'] . " - " . $searchResult['description'] . " (" . $searchResult['amount'] . ")") . "</li>";
                     echo "</ul>";
                 } else {
                     echo "<p>Транзакции не найдены.</p>";
                 }
             }
             ?>
         </div>

</body>
</html>
