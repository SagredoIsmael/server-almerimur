<?php

include "../config/database.php";
include "../helpers/token.php";

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
  header('Access-Control-Allow-Headers: authorization, Content-Type, authorization');
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

$upload_dir = './upload/';
$file = $upload_dir . basename($_FILES['image']['tmp_name'].$_FILES['image']['name']);
$file_image = '/upload/' . basename($_FILES['image']['tmp_name'].$_FILES['image']['name']);


if (!move_uploaded_file($_FILES['image']['tmp_name'], $file)) {
  $response = [
    'message' => "No se envio una imagen.",
  ];
  echo json_encode($file);
  header("HTTP/1.1 400 Error upload file");
  return;
}

try {
  $token_auth = $headers["authorization"] ? $headers["authorization"] : $headers["Authorization"];;
  $token_decode = decodeToken($token_auth);
  $user_id = $token_decode->id;

  $query = "SELECT user_image FROM user WHERE user_id='$user_id'";
  $result = api_get($query);
  $image = $result[0]["user_image"];

  // Delete file
  unlink(".".$image);

  $query = "UPDATE user SET user_image='$file_image' WHERE user_id='$user_id'";

  api_post($query);

  $query = "SELECT user_image FROM user WHERE user_id='$user_id'";
  $result = api_get($query);
  $image = $result[0]["user_image"];

  $response = [
    'message' => "Perfil modificado",
    'user_image' => $image
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