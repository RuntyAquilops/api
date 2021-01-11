<?php

function getSortRooms($connection, $data)
{
    $query = $connection->query("SELECT * FROM room_list ORDER BY $data");
    $rooms_list = [];
    while($room = $query->fetch_assoc()) {
        $rooms_list[] = $room;
    }
    echo json_encode($rooms_list, JSON_PRETTY_PRINT);
}
?>
