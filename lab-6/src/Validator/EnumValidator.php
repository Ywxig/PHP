<?php

declare(strict_types=1);

namespace App\Validator;

/**
 * Значение (или все значения массива) должно входить в список допустимых.
 */
final class EnumValidator implements ValidatorInterface
{
    /**
     * @param string[] $allowed Допустимые значения.
     */
    public function __construct(private array $allowed)
    {
    }

    /**
     * @param mixed $value Строка или массив строк.
     * @return string|null Ошибка или null.
     */
    public function validate(mixed $value): ?string
    {
        foreach ((array) $value as $item) {
            if (!is_string($item) || !in_array($item, $this->allowed, true)) {
                return 'Недопустимое значение.';
            }
        }
        return null;
    }
}
