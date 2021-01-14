<?php

include_once 'error.php';

function getSortRooms($connection, $data)
{
    $er = new Errors();

    if (strstr($data, "cost_per_night") != false || strstr($data, "data_create") != false) {
        echo $data. "\n";
        $query = $connection->query("SELECT * FROM room_list ORDER BY $data");
        $rooms_list = [];
        while($room = $query->fetch_assoc()) {
            $rooms_list[] = $room;
        }
        echo json_encode($rooms_list);
    }
    else {
        $er->err("Uncorrect parameter");
    }
}

?>
