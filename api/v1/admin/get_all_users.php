<?php

include __DIR__."/../../config/database.php";
include __DIR__."/../../helpers/token.php";

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
  header('Access-Control-Allow-Headers: authorization, Content-Type, Authorization');
  header('Access-Control-Max-Age: 1728000');
  die();
}

header('Access-Control-Allow-Origin: *');

$headers = apache_request_headers();

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

  $query = "SELECT user_id as id, user_role as role, user_name as name, user_hourly as hourly FROM user";

  $result = api_get($query);

  if (!$result) {
    $response = [
      'message' => "Usuarios obtenidos",
      'users' => [],
    ];
    echo json_encode($response);
    header("HTTP/1.1 200 OK");
    return;
  }

  $response = [
    'message' => "Uusarios obtenidos",
    'users' => $result,
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