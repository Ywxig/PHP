<?php

declare(strict_types=1);

namespace App\Validator;

/**
 * Поле не должно быть пустым.
 */
final class RequiredValidator implements ValidatorInterface
{
    /**
     * @param mixed $value Значение поля.
     * @return string|null Ошибка или null.
     */
    public function validate(mixed $value): ?string
    {
        return ($value === null || $value === '' || $value === [])
            ? 'Поле обязательно для заполнения.'
            : null;
    }
}
