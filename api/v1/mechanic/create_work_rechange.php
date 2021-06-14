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

$token_auth = $headers["authorization"] ? $headers["authorization"] : $headers["Authorization"];;

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

  $number = $_POST["number"] ? $_POST["number"] : "NULL";
  $id_work = $_POST["id_work"] ? $_POST["id_work"] : "NULL";
  $id_rechange = $_POST["id_rechange"] ? $_POST["id_rechange"] : "NULL";

  $query = "INSERT INTO mechanic_rechange(mechanic_rechange_work_id, mechanic_rechange_number, rechange_id) VALUES ('$id_work', '$number', '$id_rechange')";

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