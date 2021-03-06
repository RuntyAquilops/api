<?php

include_once 'utils.php';
include_once 'error.php';
include_once 'connect.php';

header('Content-Type: application/json; charset:UTF-8');

// добавить номер отеля. Принимает на вход текстовое описание и цену за ночь. Возвращает ID номера отеля.
function createRoom($connection, $inputData)
{
    $er = new Errors();
    date_default_timezone_set('Europe/Moscow');
    $number_room = $inputData['number_room'];
    $cost = $inputData['cost_per_night'];
    $data_create = date('Y-m-d H:i:s');

    // проверка на существование номера
    if (checkDuplicate($connection, 'room_list', 'number_room', $number_room) == 0) {
        http_response_code(409);
        $er->err("This hotel room already exists");
    } elseif ($number_room && $cost && $data_create) {
        $number_room = $connection->real_escape_string($number_room);
        $cost = $connection->real_escape_string($cost);
        $query = $connection->query("INSERT INTO `room_list` (`id_room`, `number_room`, `cost_per_night`, `data_create`) VALUES (NULL, '$number_room', '$cost', '$data_create');");
        if ($query) {
            http_response_code(201);
            $response = array(
                'id_room' => $connection->insert_id);
            echo json_encode($response);
        } elseif (!$query) {
            http_response_code(500);
            $er->err("Cant insert row in table. Check parametres or its problem with charset. Please retry with english characters");
        }
    } else {
        http_response_code(422);
        $er->err("Uncorrect request");
    }
}

// получение информации о всех номеров в отеле
function getRooms($connection)
{
    $rooms = $connection->query("SELECT * FROM room_list");
    $rooms_list = [];
    while($room = $rooms->fetch_assoc()) {
        $rooms_list[] = $room;
    }
    echo json_encode($rooms_list);
}

// удаляет номер отеля и все его брони. Принимает на вход ID номера отеля
function deleteRoom($connection, $idRoom)
{
    $er = new Errors();
    $existRoom = checkRow($connection, $idRoom, 'room_list', 'id_room');
    if ($existRoom != 0 && $idRoom != NULL) {
        $idRoom = $connection->real_escape_string($idRoom);
        $query = $connection->query("DELETE FROM bookings WHERE bookings.id_room = $idRoom");
        $q_2 = $connection->query("DELETE FROM room_list WHERE room_list.id_room = $idRoom");
        if ($query && $q_2) {
            http_response_code(200);
            $response = array(
                "status" => "Room and bookings with id_room $idRoom was deleted"
            );
            echo json_encode($response);
        } else {
            http_response_code(500);
            $er->err("Some problems with deleting");
        }
    } elseif ($existRoom == 0 && $idRoom != NULL) {
        http_response_code(404);
        $er->err("Room with id $idRoom not found");
    } else {
        http_response_code(422);
        $er->err("Uncorrect request");
    }
}

?>
