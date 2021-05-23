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

  $title = $_POST["title"] ? $_POST["title"] : "NULL";  
  $number = $_POST["number"] ? $_POST["number"] : "NULL";
  $id_work = $_POST["id_work"] ? $_POST["id_work"] : "NULL";

  $query = "INSERT INTO mechanic_rechange(mechanic_rechange_work_id, mechanic_rechange_title, mechanic_rechange_number) VALUES ('$id_work', '$title', '$number')";

  $query = str_replace("'NULL'", "NULL", $query);

  $result = api_post($query);


  $response = [
    'message' => "Recambio creado",
    'rechanges' => $result,
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