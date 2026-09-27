<?php
// Задаем путь к папке с изображениями
$dir = './image/';
// Сканируем содержимое директории
// scandir — Получает список файлов и каталогов, расположенных по  указанному пути.
// Возвращает array, содержащий имена файлов и каталогов, расположенных по  пути, переданному в параметре
$files = scandir($dir);

// Если нет ошибок при сканировании
if ($files === false) {
   return;
}

echo "<div style='display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px;'>";

for ($i = 0; $i < count($files); $i++) {
   // Пропускаем текущий каталог и родительский
   if (($files[$i] != ".") && ($files[$i] != "..")) {
       // Получаем путь к изображению
       $path = $dir . $files[$i]; ?>
       <img width="200" src="<?php echo $path; ?>" alt="<?php echo $files[$i]; ?>">
   <?php
   }
}
echo "</div>";
