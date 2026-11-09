<?php
/**
 * Шаблон формы.
 *
 * @var array<string, mixed>  $old    Ранее введённые значения.
 * @var array<string, string> $errors Ошибки по полям.
 */
?>
<form method="post" action="">
    <div>
        <label for="title">Заголовок</label>
        <input type="text" id="title" name="title" required minlength="3" maxlength="80" value="<?= e((string) $old['title']) ?>">
        <?php if (isset($errors['title'])): ?><div class="error"><?= e($errors['title']) ?></div><?php endif; ?>
    </div>

    <div>
        <label for="author">Автор</label>
        <input type="text" id="author" name="author" required minlength="2" maxlength="50" value="<?= e((string) $old['author']) ?>">
        <?php if (isset($errors['author'])): ?><div class="error"><?= e($errors['author']) ?></div><?php endif; ?>
    </div>

    <div>
        <label for="entry_date">Дата</label>
        <input type="date" id="entry_date" name="entry_date" required max="<?= e(date('Y-m-d')) ?>" value="<?= e((string) $old['entry_date']) ?>">
        <?php if (isset($errors['entry_date'])): ?><div class="error"><?= e($errors['entry_date']) ?></div><?php endif; ?>
    </div>

    <div>
        <label for="mood">Настроение</label>
        <select id="mood" name="mood" required>
            <option value="">— выберите —</option>
            <?php foreach (MOODS as $key => $label): ?>
                <option value="<?= e($key) ?>" <?= $old['mood'] === $key ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['mood'])): ?><div class="error"><?= e($errors['mood']) ?></div><?php endif; ?>
    </div>

    <div class="inline">
        <label>Теги</label>
        <?php foreach (TAGS as $key => $label): ?>
            <label>
                <input type="checkbox" name="tags[]" value="<?= e($key) ?>" <?= in_array($key, (array) $old['tags'], true) ? 'checked' : '' ?>>
                <?= e($label) ?>
            </label>
        <?php endforeach; ?>
        <?php if (isset($errors['tags'])): ?><div class="error"><?= e($errors['tags']) ?></div><?php endif; ?>
    </div>

    <div>
        <label for="note">Заметка</label>
        <textarea id="note" name="note" rows="5" required minlength="10" maxlength="1000"><?= e((string) $old['note']) ?></textarea>
        <?php if (isset($errors['note'])): ?><div class="error"><?= e($errors['note']) ?></div><?php endif; ?>
    </div>

    <div><button type="submit">Сохранить</button></div>
</form>
