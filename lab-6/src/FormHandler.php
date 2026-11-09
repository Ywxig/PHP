<?php

declare(strict_types=1);

namespace App;

use App\Validator\RequiredValidator;
use App\Validator\ValidatorInterface;

/**
 * Принимает данные формы, нормализует их и прогоняет через валидаторы.
 */
final class FormHandler
{
    /**
     * @param array<string, ValidatorInterface[]> $rules Правила: имя поля => список валидаторов.
     */
    public function __construct(private array $rules)
    {
    }

    /**
     * Нормализует и проверяет данные.
     *
     * @param array<string, mixed> $post Данные из $_POST.
     * @return array{0: array<string, mixed>, 1: array<string, string>} [чистые данные, ошибки по полям]
     */
    public function handle(array $post): array
    {
        $data = [];
        $errors = [];

        foreach ($this->rules as $field => $validators) {
            $raw = $post[$field] ?? '';
            $value = is_array($raw)
                ? array_values(array_filter($raw, 'is_string'))
                : trim((string) $raw);
            $data[$field] = $value;

            foreach ($validators as $validator) {
                // Необязательные пустые поля дальше не проверяем.
                if (($value === '' || $value === []) && !$validator instanceof RequiredValidator) {
                    continue;
                }
                $error = $validator->validate($value);
                if ($error !== null) {
                    $errors[$field] = $error;
                    break;
                }
            }
        }

        return [$data, $errors];
    }
}
