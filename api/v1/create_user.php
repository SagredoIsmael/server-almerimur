<?php

include "../config/database.php";
include "../helpers/token.php";
include "../helpers/password.php";

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
  header('Access-Control-Allow-Headers: Authorization, Content-Type');
  header('Access-Control-Max-Age: 1728000');
  die();
}

header('Access-Control-Allow-Origin: *');

$headers = apache_request_headers();

if (!$headers["Authorization"]) {
  $response = [
    'message' => "No authorization header"
  ];
  echo json_encode($response);
  header("HTTP/1.1 401 No authorization header");
  return;
}

$token_auth = $headers["Authorization"];

if (!$token_auth) {
  $response = [
    'message' => "Debe tener autorizacion, contacte con el administrador."
  ];
  echo json_encode($response);
  header("HTTP/1.1 401 Not authorization");
  return;
}

try {
  $token_auth = $headers["Authorization"];
  $token_decode = decodeToken($token_auth);

  $role = $_POST["role"];
  $name = $_POST["name"];
  $job = $_POST["job"];
  $image = $_POST["image"];
  $email = $_POST["email"];
  $password = hashPassword($_POST["password"]);
  $contract = $_POST["contract"];
  $hourly = $_POST["hourly"];

  $query = "INSERT INTO user(image, role, name, job, email, password, contract, hourly) VALUES ('$image', '$role', '$name', '$job', '$email', '$password', '$contract', '$hourly')";

  api_post($query);
  $response = [
    'message' => "Usuario creado"
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