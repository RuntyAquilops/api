<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('default_charset', 'utf-8');

include_once 'bookings/crud_bookings.php';
include_once 'rooms/crud_rooms.php';
include_once 'connect.php';
include_once 'error.php';
include_once 'utils.php';
include_once 'rooms/sort.php';

header('Content-Type: application/json; charset:UTF-8');

$resp = new Errors();
$params = explode('/', $_GET["q"]);
// bookings или rooms
$operation = $params[0];
// получение метода (POST DELETE GET)
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
  case 'POST':
      if ($operation === 'rooms' && $params[1] == 'create') {
          if (isset($_POST['number_room'], $_POST['cost_per_night'])) {
              createRoom($connection, $_POST);
          } else {
              http_response_code(422);
              $resp->err("Check parameters");
          }
      } elseif ($operation === 'bookings' && $params[1] == 'create') {
          if (isset($_POST['id_room'], $_POST['start_data'], $_POST['end_data'])) {
              createBooking($connection, $_POST);
          } else {
              http_response_code(422);
              $resp->err("Check parameters");
          }
      } else {
          http_response_code(422);
          $resp->err("Uncorrect request");
      }
      break;

  case 'DELETE':
      if ($operation === 'rooms' && $params[1] == 'delete') {
          if (isset($params[2])) {
              deleteRoom($connection, $params[2]);
          } else {
              $resp->err("Check parameters. Example: localhost/api/rooms/delete/2, where 2 is id room");
          }
      } elseif ($operation === 'bookings' && $params[1] == 'delete') {
          if (isset($params[2])) {
              deleteBooking($connection, $params[2]);
          } else {
              $resp->err("Check parameters. Example: localhost/api/bookings/delete/13, where 13 is id booking");
          }
      } else {
          http_response_code(422);
          $resp->err("Uncorrect request");
      }
      break;

  case 'GET':
      if ($operation === 'rooms') {
          if (isset($_GET['sort'])) {
              getSortRooms($connection, $_GET['sort']);
          } elseif (!isset($_GET['sort'])) {
              echo 'here';
              getRooms($connection);
          } else {
              $resp->err("Check parameters");
          }
      } elseif ($operation === 'bookings') {
          if (isset($_GET['id_room'])) {
              getOneBooking($connection, $_GET['id_room']);
          } else {
              http_response_code(422);
              $resp->err("Uncorrect request");
          }
      } else {
          echo "Welcome\nby Runty Aquilops";
      }
      break;
  default:
      http_response_code(422);
      $resp->err("Something went wrong. Read README.md and check your request");
      break;
  }
?>
