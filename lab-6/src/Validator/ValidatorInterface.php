<?php

declare(strict_types=1);

namespace App\Validator;

/**
 * Контракт для всех валидаторов полей.
 */
interface ValidatorInterface
{
    /**
     * Проверяет значение поля.
     *
     * @param mixed $value Значение из формы (для строк — после trim).
     * @return string|null Текст ошибки или null, если значение корректно.
     */
    public function validate(mixed $value): ?string;
}
