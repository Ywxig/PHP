<?php
/**
 * Шаблон таблицы записей.
 *
 * @var array<int, array<string, mixed>> $records Записи.
 * @var string $sort Текущее поле сортировки.
 * @var string $dir  Направление: asc|desc.
 */
?>
<h2>Записи (<?= count($records) ?>)</h2>
<?php if ($records === []): ?>
    <p>Пока нет записей.</p>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <?php foreach (COLUMNS as $key => $label):
                $nextDir = ($sort === $key && $dir === 'asc') ? 'desc' : 'asc';
                $arrow = $sort === $key ? ($dir === 'asc' ? ' ▲' : ' ▼') : '';
                ?>
                <th><a href="?sort=<?= e($key) ?>&amp;dir=<?= e($nextDir) ?>"><?= e($label . $arrow) ?></a></th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($records as $r): ?>
            <tr>
                <td><?= e((string) ($r['title'] ?? '')) ?></td>
                <td><?= e((string) ($r['author'] ?? '')) ?></td>
                <td><?= e((string) ($r['entry_date'] ?? '')) ?></td>
                <td><?= e(MOODS[$r['mood'] ?? ''] ?? '—') ?></td>
                <td><?= e(implode(', ', array_map(static fn($t) => TAGS[$t] ?? $t, (array) ($r['tags'] ?? [])))) ?></td>
                <td><?= nl2br(e((string) ($r['note'] ?? ''))) ?></td>
                <td><?= e((string) ($r['created_at'] ?? '')) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
