<?php

header('Content-Type: application/json; charset:UTF-8');

// проверка дупликатов при создании rooms
function checkDuplicate($connection, $table, $typeId, $id)
{
    $table = $connection->real_escape_string($table);
    $id = $connection->real_escape_string($id);
    $typeId = $connection->real_escape_string($typeId);
    $query = $connection->query("SELECT * FROM $table WHERE $typeId = '$id'");
    if (mysqli_num_rows($query) === 0) {
        return 1;
    } else return 0;
}

// проверка валидности даты
function isValidDate($date) {
    //преобразует в нужный формат
    $d = DateTime::createFromFormat('Y-m-d', $date);
    // сравнивает результат с введенной датой
    return $d && $d->format('Y-m-d') === $date;
}

// Проверка на отсутствие строк
function checkRow($connection, $id, $table, $typeId)
{
    $table = $connection->real_escape_string($table);
    $id = $connection->real_escape_string($id);
    $typeId = $connection->real_escape_string($typeId);
    $testQuery = $connection->query("SELECT * FROM $table WHERE $typeId = '$id'");
    if (mysqli_num_rows($testQuery) == 0) {
        return 0;
    } else {
        return 1;
    }
}
?>
