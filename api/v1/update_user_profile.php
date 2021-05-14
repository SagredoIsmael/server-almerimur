<?php

include "../config/database.php";
include "../helpers/token.php";

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
  header('Access-Control-Allow-Headers: Authorization, Content-Type, authorization');
  header('Access-Control-Max-Age: 1728000');
  die();
}

header('Access-Control-Allow-Origin: *');

$headers = apache_request_headers();

if (!$headers["authorization"]) {
  $response = [
    'message' => "No authorization header"
  ];
  echo json_encode($headers);
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
  $name = $_POST["name"];
  $job = $_POST["job"];

  $query = "UPDATE user SET user_name = '$name', user_job = '$job' WHERE user_id='$user_id'";
  
  api_post($query);
  
  $query = "SELECT user_name, user_job FROM user WHERE user_id='$user_id'";
  $result = api_get($query);
  $profile = $result[0];

  $response = [
    'message' => "Perfil modificado",
    'profile' => $profile
  ];
  echo json_encode($response);
  header("HTTP/1.1 200 OK");
} catch(Exception $e) {
  $response = [
    'message' => $e,
    'error' => "Error on catch"
  ];
  echo json_encode($response);
  header("HTTP/1.1 400 '$e'");
}

?>