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

if (!$headers["authorization"]) {
  $response = [
    'message' => "No authorization header"
  ];
  echo json_encode($response);
  header("HTTP/1.1 401 No authorization header");
  return;
}

$token_auth = $headers["authorization"];

if (!$token_auth) {
  $response = [
    'message' => "Debe tener autorizacion, contacte con el administrador."
  ];
  echo json_encode($response);
  header("HTTP/1.1 401 Not authorization");
  return;
}

try {
  $token_auth = $headers["authorization"];
  $token_decode = decodeToken($token_auth);

  
  $user_id = $token_decode->id;
  $id = $_POST["id"];
  $client_name = $_POST["client"] ? $_POST["client"] : "NULL";  
  $machine_name = $_POST["machine"] ? $_POST["machine"] : "NULL";
  $date= $_POST["date"] ? $_POST["date"] : "NULL";
  $hours = $_POST["hours"] ? $_POST["hours"] : "NULL";
  $works = $_POST["works"] ? $_POST["works"] : "NULL";

  $query = "UPDATE mechanic_work SET mechanic_work_client_name='$client_name', mechanic_work_machine_name='$machine_name', mechanic_work_date='$date', mechanic_work_hours='$hours', mechanic_work_works='$works' WHERE mechanic_work_id='$id'";

  $query = str_replace("'NULL'", "NULL", $query);

  if ($id) {
    api_post($query);
    $response = [
      'message' => "Trabajo actualizado",
    ];
    echo json_encode($response);
    header("HTTP/1.1 200 OK");
  } else {
    $response = [
      'message' => "Id requerido",
    ];
    echo json_encode($response);
    header("HTTP/1.1 400 Bad request");
  }
  
} catch(Exception $e) {
  $response = [
    'message' => $e
  ];
  echo json_encode($response);
  header("HTTP/1.1 401 '$e'");
}

?>