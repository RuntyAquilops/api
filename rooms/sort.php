<?php

include_once 'error.php';

function getSortRooms($connection, $data)
{
    $er = new Errors();

    $order = isset($_GET['sort']) ? $_GET['sort']  : '';
    $allowed = array("cost_per_night", "data_create", "-cost_per_night", "-data_create");
    $key = array_search($order, $allowed);
    if ($key) {
        $orderby = $allowed[$key];
        $query = $connection->query("SELECT * FROM `room_list` ORDER BY $orderby");
        if ($query) {
            $rooms_list = [];
            while($room = $query->fetch_assoc()) {
                $rooms_list[] = $room;
            }
            echo json_encode($rooms_list);
        } else {
            http_response_code(500);
            $er->err("Sorry, some problems with database");
        }
    } else {
        http_response_code(422);
        $er->err("Check parameter. You can sort only 'cost_per_night' and 'data_create'");
    }
}

?>
