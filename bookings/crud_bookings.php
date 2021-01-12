<?php

include_once 'utils.php';
include_once 'error.php';
include_once 'connect.php';

// Добавить бронь. Принимает на вход существующий ID номера отеля, дату начала, дату окончания брони
function createBooking($connection, $inputData)
{
    $er = new Errors();
    $id_room = $inputData['id_room'];
    $data_start = $inputData['start_data'];
    $data_end = $inputData['end_data'];
    $existRoom = checkRow($connection, $id_room, 'bookings', 'id_room');

    if (isValidDate($data_start) && isValidDate($data_end) && $existRoom) {
        $connection->query("INSERT INTO `bookings` (`id_booking`, `id_room`, `start_data`, `end_data`) VALUES (NULL, '$id_room', '$data_start', '$data_end');");
        $last_id = mysqli_insert_id($connection);
        $response = array(
            'id_booking' => $last_id
        );
        http_response_code(201);
        echo json_encode($response);
    } elseif (!isValidDate($data_start) || !isValidDate($data_end)) {
        http_response_code(422);
        $er->err("Not valid start_data or end_data. Please check parameters. Remember that data must be 'year-month-day'");
    } elseif ($existRoom == 0) {
        http_response_code(404);
        $er->err("The room with id $id_room not found");
    } else {
        http_response_code(422);
        $er->err("Uncorrect request");
    }
}

// получить список броней номера отеля. Принимает на вход ID номера отеля. Сортировка по дате начала бронирования
function getOneBooking($connection, $idRoom)
{
    $resp = new Errors();
    $bookings = $connection->query("SELECT * FROM bookings WHERE id_room = $idRoom ORDER BY start_data");
    $bookings_list = [];

    if ($idRoom != NULL && is_numeric($idRoom) && mysqli_num_rows($bookings) > 0) {
        http_response_code(200);
        while($booking = $bookings->fetch_assoc()) {
            $bookings_list[] = $booking;
        }
        echo json_encode($bookings_list);
    } elseif($idRoom != NULL && is_numeric($idRoom) && mysqli_num_rows($bookings) === 0) {
        http_response_code(404);
        $resp->err("Hotel room not found");
    } else {
        http_response_code(422);
        $resp->err("Uncorrect request. Check parameter");
    }
}

// Удаляет бронь. Принимает на вход ID брони.
function deleteBooking($connection, $idBooking)
{
    $er = new Errors();
    $existBooking = checkRow($connection, $idBooking, 'bookings', 'id_booking');
    if ($existBooking != 0 && $idBooking != NULL) {
        $query = $connection->query("DELETE FROM `bookings` WHERE `bookings`.`id_booking` = $idBooking");
        http_response_code(200);
        $response = array(
            "status" => "Booking with id $idBooking was deleted"
        );
        echo json_encode($response);
    } elseif ($existBooking == 0 && $idBooking != NULL) {
        http_response_code(404);
        $er->err("Booking with id $idBooking not found");
    } else {
        http_response_code(422);
        $er->err("Uncorrect request");
    }
}
?>
