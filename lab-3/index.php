<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная работа - Расписание и циклы</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        table {
            border-collapse: collapse;
            width: 50%;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px 15px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .section {
            margin-bottom: 40px;
        }
        pre {
            background: #f8f8f8;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <div class="section">
        <h2>1. Расписание работы на основе текущего дня недели</h2>
        <p>Сегодня: <strong><?php echo date('d.m.Y (l)'); ?></strong></p>

        <?php
        // Определяем день недели (1 - понедельник, ..., 7 - воскресенье)
        $dayOfWeek = (int)date('N');

        // График для John Styles (понедельник, среда, пятница)
        if (in_array($dayOfWeek, [1, 3, 5])) {
            $johnSchedule = '8:00-12:00';
        } else {
            $johnSchedule = 'Нерабочий день';
        }

        // График для Jane Doe (вторник, четверг, суббота)
        if (in_array($dayOfWeek, [2, 4, 6])) {
            $janeSchedule = '12:00-16:00';
        } else {
            $janeSchedule = 'Нерабочий день';
        }
        ?>

        <table>
            <thead>
                <tr>
                    <th>№</th>
                    <th>Фамилия Имя</th>
                    <th>График работы</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>John Styles</td>
                    <td><?php echo $johnSchedule; ?></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Jane Doe</td>
                    <td><?php echo $janeSchedule; ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>2. Циклы в PHP</h2>

        <h3>А) Цикл FOR с выводом промежуточных значений</h3>
        <pre><?php
        $a = 0;
        $b = 0;

        echo "Начало цикла for:\n";
        for ($i = 0; $i <= 5; $i++) {
           $a += 10;
           $b += 5;
           echo "Шаг $i: a = $a, b = $b\n";
        }
        echo "End of the loop: a = $a, b = $b\n";
        ?></pre>

        <h3>Б) Тот же цикл с использованием WHILE</h3>
        <pre><?php
        $a = 0;
        $b = 0;
        $i = 0;

        echo "Начало цикла while:\n";
        while ($i <= 5) {
           $a += 10;
           $b += 5;
           echo "Шаг $i: a = $a, b = $b\n";
           $i++;
        }
        echo "End of the loop: a = $a, b = $b\n";
        ?></pre>

        <h3>В) Тот же цикл с использованием DO-WHILE</h3>
        <pre><?php
        $a = 0;
        $b = 0;
        $i = 0;

        echo "Начало цикла do-while:\n";
        do {
           $a += 10;
           $b += 5;
           echo "Шаг $i: a = $a, b = $b\n";
           $i++;
        } while ($i <= 5);
        echo "End of the loop: a = $a, b = $b\n";
        ?></pre>
    </div>

</body>
</html>
