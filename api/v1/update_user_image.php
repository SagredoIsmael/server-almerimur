<?php

include "../config/database.php";
include "../helpers/token.php";
include "../helpers/password.php";

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
  $token_auth = $headers["authorization"];
  $token_decode = decodeToken($token_auth);
  $id_user = $token_decode->id;

  $query = "SELECT image FROM user WHERE id_user='$id_user'";
  $result = api_get($query);
  $image = $result[0]["image"];

  // Delete file
  unlink(".".$image);

  $query = "UPDATE user SET image='$file_image' WHERE id_user='$id_user'";

  api_post($query);

  $query = "SELECT image FROM user WHERE id_user='$id_user'";
  $result = api_get($query);
  $image = $result[0]["image"];

  $response = [
    'message' => "Perfil modificado",
    'image' => $image
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