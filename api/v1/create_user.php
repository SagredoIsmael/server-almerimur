<?php

include "../config/database.php";
include "../helpers/token.php";
include "../helpers/password.php";

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
    'message' => "No authorization header"
  ];
  echo json_encode($response);
  header("HTTP/1.1 401 No authorization header");
  return;
}

// Check if has a file
if ($_FILES['image']) {

  $upload_dir = './upload/';
  $file = $upload_dir . basename($_FILES['image']['tmp_name'].$_FILES['image']['name']);
  $GLOBALS["file_image"] = '/upload/' . basename($_FILES['image']['tmp_name'].$_FILES['image']['name']);

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $file)) {
    $response = [
      'message' => "Error al subir la imagen.",
    ];
    echo json_encode($file);
    header("HTTP/1.1 400 Error upload file");
    return;
  }

} else {
  $GLOBALS["file_image"] = "";
}

try {
  $token_auth = $headers["authorization"] ? $headers["authorization"] : $headers["Authorization"];
  $token_decode = decodeToken($token_auth);

  $role = $_POST["role"];
  $name = $_POST["name"];
  $job = $_POST["job"];
  $email = $_POST["email"];
  $password = hashPassword($_POST["password"]);
  $contract = $_POST["contract"];
  $hourly = $_POST["hourly"];

  $query = "INSERT INTO user(user_image, user_role, user_name, user_job, user_email, user_password, user_contract, user_hourly) VALUES ('$GLOBALS[file_image]', '$role', '$name', '$job', '$email', '$password', '$contract', '$hourly')";

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
  header("HTTP/1.1 400 '$e'");
}

?>