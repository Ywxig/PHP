<?php

declare(strict_types=1);

namespace App\Validator;

/**
 * Проверка длины строки (в символах, UTF-8).
 */
final class LengthValidator implements ValidatorInterface
{
    /**
     * @param int $min Минимальная длина.
     * @param int $max Максимальная длина.
     */
    public function __construct(private int $min, private int $max)
    {
    }

    /**
     * @param mixed $value Значение поля.
     * @return string|null Ошибка или null.
     */
    public function validate(mixed $value): ?string
    {
        if (!is_string($value)) {
            return 'Ожидается строка.';
        }
        $len = mb_strlen($value);
        if ($len < $this->min || $len > $this->max) {
            return "Длина должна быть от {$this->min} до {$this->max} символов.";
        }
        return null;
    }
}
