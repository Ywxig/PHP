<?php

declare(strict_types=1);

/**
 * Экранирует строку для безопасного вывода в HTML (защита от XSS).
 *
 * @param string $s Исходная строка.
 * @return string Экранированная строка.
 */
function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
