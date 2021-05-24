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

$token_auth = $headers["authorization"] ? $headers["authorization"] : $headers["Authorization"];

if (!$token_auth) {
  $response = [
    'message' => "No authorization header"
  ];
  echo json_encode($response);
  header("HTTP/1.1 401 No authorization header");
  return;
}

try {
  $token_auth = $headers["authorization"] ? $headers["authorization"] : $headers["Authorization"];
  $token_decode = decodeToken($token_auth);
  $user_id = $token_decode->id;

  $query_driver = "SELECT driver_work_date FROM driver_work ORDER BY driver_work_date";
  $query_mechanic = "SELECT mechanic_work_date FROM mechanic_work ORDER BY mechanic_work_date";

  $result_driver = api_get($query_driver);
  $result_mechanic = api_get($query_mechanic);

  $result = array_merge($result_driver, $result_mechanic);

  $response = [
    'message' => "Fechas de trabajos obtenidos",
    'dates' => $result,
  ];

  echo json_encode($response);
  header("HTTP/1.1 200 OK");
} catch(Exception $e) {
  $response = [
    'message' => $e
  ];
  echo json_encode($response);
  header("HTTP/1.1 401 '$e'");
}

?>