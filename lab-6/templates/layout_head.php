<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Дневник настроения</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Дневник настроения</h1>
<?php if (isset($_GET['saved'])): ?>
    <div class="ok">Запись успешно сохранена.</div>
<?php endif; ?>
