<?php

declare(strict_types=1);

namespace App\Validator;

use DateTimeImmutable;

/**
 * Проверка даты формата Y-m-d, которая не находится в будущем.
 */
final class DateValidator implements ValidatorInterface
{
    /**
     * @param mixed $value Значение поля.
     * @return string|null Ошибка или null.
     */
    public function validate(mixed $value): ?string
    {
        if (!is_string($value)) {
            return 'Ожидается дата.';
        }
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
        if ($date === false || $date->format('Y-m-d') !== $value) {
            return 'Неверный формат даты (ожидается ГГГГ-ММ-ДД).';
        }
        if ($date > new DateTimeImmutable('today')) {
            return 'Дата не может быть в будущем.';
        }
        return null;
    }
}
