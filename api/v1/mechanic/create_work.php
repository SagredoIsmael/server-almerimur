<?php

include __DIR__."/../../config/database.php";
include __DIR__."/../../helpers/token.php";

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
  header('Access-Control-Allow-Headers: authorization, Content-Type');
  header('Access-Control-Max-Age: 1728000');
  die();
}

header('Access-Control-Allow-Origin: *');

$headers = apache_request_headers();

$token_auth = $headers["authorization"] ? $headers["authorization"] : $headers["Authorization"];

if (!$token_auth) {
  $response = [
    'message' => "Debe tener autorizacion, contacte con el administrador."
  ];
  echo json_encode($response);
  header("HTTP/1.1 401 Not authorization");
  return;
}

try {
  $token_auth = $headers["authorization"] ? $headers["authorization"] : $headers["Authorization"];;
  $token_decode = decodeToken($token_auth);

  $user_id = $token_decode->id;
  $client_name = $_POST["client"] ? $_POST["client"] : "NULL";  
  $machine_name = $_POST["machine"] ? $_POST["machine"] : "NULL";
  $date= $_POST["date"] ? $_POST["date"] : "NULL";
  $hours = $_POST["hours"] ? $_POST["hours"] : "NULL";
  $works = $_POST["works"] ? $_POST["works"] : "NULL";

  $query = "INSERT INTO mechanic_work(mechanic_work_user_id, mechanic_work_client_name, mechanic_work_machine_name, mechanic_work_date, mechanic_work_hours, mechanic_work_works) VALUES ('$user_id', '$client_name', '$machine_name', '$date', '$hours', '$works')";

  $query = str_replace("'NULL'", "NULL", $query);

  $result = api_post($query);

  $response = [
    'message' => "Trabajo creado",
    'work' => $result,
  ];

  echo json_encode($response);
  header("HTTP/1.1 200 OK");
} catch(Exception $e) {
  $response = [
    'message' => $e
  ];
  echo json_encode($response);
  header("HTTP/1.1 401 Error '$e'");
}

?>