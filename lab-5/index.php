<?php

declare(strict_types=1);

/**
 * Лабораторная работа: приложение для управления банковскими транзакциями.
 *
 * Все классы размещены в одном файле для простоты.
 */

/**
 * Одна банковская транзакция (неизменяемый объект).
 */
class Transaction
{
    /**
     * @param int               $id               Уникальный идентификатор транзакции
     * @param DateTimeImmutable $date             Дата транзакции
     * @param float             $amount           Сумма транзакции
     * @param string            $description      Описание платежа
     * @param string            $merchant         Получатель платежа
     * @param string            $merchantCategory Категория получателя (нужна для таблицы)
     */
    public function __construct(
        private int $id,
        private DateTimeImmutable $date,
        private float $amount,
        private string $description,
        private string $merchant,
        private string $merchantCategory
    ) {
    }

    /**
     * Возвращает идентификатор транзакции.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращает дату транзакции.
     *
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * Возвращает сумму транзакции.
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Возвращает описание платежа.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Возвращает название получателя платежа.
     *
     * @return string
     */
    public function getMerchant(): string
    {
        return $this->merchant;
    }

    /**
     * Возвращает категорию получателя платежа.
     *
     * @return string
     */
    public function getMerchantCategory(): string
    {
        return $this->merchantCategory;
    }

    /**
     * Считает количество полных дней с момента транзакции до сегодняшней даты.
     *
     * @return int Количество дней (0, если транзакция сегодня)
     */
    public function getDaysSinceTransaction(): int
    {
        $today = new DateTimeImmutable('today');
        $date = $this->date->setTime(0, 0);

        return (int) $date->diff($today)->days;
    }
}

/**
 * Контракт хранилища транзакций.
 */
interface TransactionStorageInterface
{
    /**
     * Добавляет транзакцию в хранилище.
     *
     * @param Transaction $transaction Добавляемая транзакция
     * @return void
     */
    public function addTransaction(Transaction $transaction): void;

    /**
     * Удаляет транзакцию по идентификатору.
     *
     * @param int $id Идентификатор транзакции
     * @return void
     */
    public function removeTransactionById(int $id): void;

    /**
     * Возвращает все транзакции.
     *
     * @return Transaction[]
     */
    public function getAllTransactions(): array;

    /**
     * Ищет транзакцию по идентификатору.
     *
     * @param int $id Идентификатор транзакции
     * @return Transaction|null Найденная транзакция или null
     */
    public function findById(int $id): ?Transaction;
}

/**
 * Репозиторий: только хранение и базовый доступ к транзакциям.
 */
class TransactionRepository implements TransactionStorageInterface
{
    /** @var Transaction[] Транзакции, индексированные по id */
    private array $transactions = [];

    /**
     * {@inheritdoc}
     *
     * Если транзакция с таким id уже есть, она будет заменена.
     */
    public function addTransaction(Transaction $transaction): void
    {
        $this->transactions[$transaction->getId()] = $transaction;
    }

    /**
     * {@inheritdoc}
     */
    public function removeTransactionById(int $id): void
    {
        unset($this->transactions[$id]);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllTransactions(): array
    {
        return array_values($this->transactions);
    }

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?Transaction
    {
        return $this->transactions[$id] ?? null;
    }
}

/**
 * Бизнес-логика над коллекцией транзакций.
 * Сам транзакции не хранит и не создаёт: всё берёт из хранилища.
 */
class TransactionManager
{
    /**
     * @param TransactionStorageInterface $repository Хранилище транзакций
     */
    public function __construct(
        private TransactionStorageInterface $repository
    ) {
    }

    /**
     * Вычисляет общую сумму всех транзакций.
     *
     * @return float
     */
    public function calculateTotalAmount(): float
    {
        return array_sum(array_map(
            static fn (Transaction $t): float => $t->getAmount(),
            $this->repository->getAllTransactions()
        ));
    }

    /**
     * Вычисляет сумму транзакций за период (границы включительно).
     *
     * @param string $startDate Начало периода, например "2026-01-01"
     * @param string $endDate   Конец периода, например "2026-01-31"
     * @return float
     * @throws InvalidArgumentException Если период задан неверно
     */
    public function calculateTotalAmountByDateRange(string $startDate, string $endDate): float
    {
        $start = $this->parseDate($startDate)->setTime(0, 0, 0);
        $end = $this->parseDate($endDate)->setTime(23, 59, 59);

        if ($start > $end) {
            throw new InvalidArgumentException('Начальная дата позже конечной.');
        }

        $total = 0.0;
        foreach ($this->repository->getAllTransactions() as $t) {
            if ($t->getDate() >= $start && $t->getDate() <= $end) {
                $total += $t->getAmount();
            }
        }

        return $total;
    }

    /**
     * Считает количество транзакций у получателя (без учёта регистра).
     *
     * @param string $merchant Название получателя
     * @return int
     */
    public function countTransactionsByMerchant(string $merchant): int
    {
        $needle = mb_strtolower($merchant);
        $count = 0;

        foreach ($this->repository->getAllTransactions() as $t) {
            if (mb_strtolower($t->getMerchant()) === $needle) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Сортирует транзакции по дате (от старых к новым).
     *
     * @return Transaction[]
     */
    public function sortTransactionsByDate(): array
    {
        $list = $this->repository->getAllTransactions();
        usort($list, static fn (Transaction $a, Transaction $b): int => $a->getDate() <=> $b->getDate());

        return $list;
    }

    /**
     * Сортирует транзакции по сумме по убыванию.
     *
     * @return Transaction[]
     */
    public function sortTransactionsByAmountDesc(): array
    {
        $list = $this->repository->getAllTransactions();
        usort($list, static fn (Transaction $a, Transaction $b): int => $b->getAmount() <=> $a->getAmount());

        return $list;
    }

    /**
     * Преобразует строку в дату.
     *
     * @param string $date Строка с датой
     * @return DateTimeImmutable
     * @throws InvalidArgumentException Если строку нельзя разобрать
     */
    private function parseDate(string $date): DateTimeImmutable
    {
        try {
            return new DateTimeImmutable($date);
        } catch (Exception $e) {
            throw new InvalidArgumentException("Некорректная дата: {$date}", 0, $e);
        }
    }
}

/**
 * Отвечает только за вывод транзакций в виде HTML-таблицы.
 */
final class TransactionTableRenderer
{
    /**
     * Формирует HTML-таблицу по списку транзакций.
     *
     * @param Transaction[] $transactions Список транзакций
     * @return string HTML-код таблицы
     */
    public function render(array $transactions): string
    {
        $html = '<table border="1" cellpadding="6" cellspacing="0">' . PHP_EOL;
        $html .= '<thead><tr><th>ID транзакции</th><th>Дата</th><th>Сумма</th>'
            . '<th>Описание</th><th>Получатель</th><th>Категория получателя</th>'
            . '<th>Дней с момента транзакции</th></tr></thead>' . PHP_EOL;
        $html .= '<tbody>' . PHP_EOL;

        foreach ($transactions as $t) {
            $html .= sprintf(
                '<tr><td>%d</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%d</td></tr>' . PHP_EOL,
                $t->getId(),
                $t->getDate()->format('Y-m-d'),
                number_format($t->getAmount(), 2, '.', ' '),
                $this->escape($t->getDescription()),
                $this->escape($t->getMerchant()),
                $this->escape($t->getMerchantCategory()),
                $t->getDaysSinceTransaction()
            );
        }

        return $html . '</tbody>' . PHP_EOL . '</table>' . PHP_EOL;
    }

    /**
     * Экранирует строку для безопасной вставки в HTML.
     *
     * @param string $value Исходная строка
     * @return string
     */
    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

// Начальные данные

$repository = new TransactionRepository();

$seed = [
    [1, '2026-10-20', 1250.50, 'Покупка продуктов', 'Linella', 'Супермаркеты'],
    [2, '2026-10-18', 320.00, 'Проезд за месяц', 'Chisinau Transport', 'Транспорт'],
    [3, '2026-10-15', 4999.99, 'Новые наушники', 'Darwin', 'Электроника'],
    [4, '2026-10-10', 210.75, 'Ужин в кафе', 'La Placinte', 'Рестораны'],
    [5, '2026-09-30', 899.00, 'Подписка на сервисы', 'Netflix', 'Развлечения'],
    [6, '2026-09-22', 150.00, 'Мобильная связь', 'Orange', 'Связь'],
    [7, '2026-09-15', 2300.00, 'Оплата общежития', 'MSU Dormitory', 'Жильё'],
    [8, '2026-09-05', 780.40, 'Книги по алгоритмам', 'Carturesti', 'Книги'],
    [9, '2026-08-25', 540.30, 'Продукты на неделю', 'Linella', 'Супермаркеты'],
    [10, '2026-08-12', 1200.00, 'Курс по программированию', 'Udemy', 'Образование'],
    [11, '2026-07-30', 95.90, 'Кофе и выпечка', 'Starbucks', 'Рестораны'],
];

foreach ($seed as [$id, $date, $amount, $description, $merchant, $category]) {
    $repository->addTransaction(
        new Transaction($id, new DateTimeImmutable($date), $amount, $description, $merchant, $category)
    );
}

$manager = new TransactionManager($repository);
$renderer = new TransactionTableRenderer();

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Банковские транзакции</title>
</head>
<body>
<h2>Все транзакции</h2>
<?= $renderer->render($repository->getAllTransactions()); ?>

<h2>По дате (от старых к новым)</h2>
<?= $renderer->render($manager->sortTransactionsByDate()); ?>

<h2>По сумме (по убыванию)</h2>
<?= $renderer->render($manager->sortTransactionsByAmountDesc()); ?>
</body>
</html>
