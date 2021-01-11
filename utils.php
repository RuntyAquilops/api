<?php

header('Content-Type: application/json; charset:UTF-8');

// проверка дупликатов при создании rooms
function checkDuplicate($connection, $table, $typeId, $id)
{
    $query = $connection->query("SELECT * FROM $table WHERE $typeId = '$id'");
    if (mysqli_num_rows($query) === 0) {
        return 1;
    } else return 0;
}

// проверка валидности даты
function isValidDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

// Проверка на количество строк
function checkRow($connection, $id, $table, $typeId)
{
    $testQuery = $connection->query("SELECT * FROM $table WHERE $typeId = '$id'");
    if (mysqli_num_rows($testQuery) == 0) {
        return 0;
    } else {
        return 1;
    }
}
?>
