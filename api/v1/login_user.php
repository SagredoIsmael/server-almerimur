<?php

include "../config/database.php";
include "../helpers/token.php";

header("Access-Control-Allow-Origin:*");

if (!$_POST["email"]) {
  $response = [
    'message' => "Usuario no encontrado"
  ];
  echo json_encode($response);
  header("HTTP/1.1 400 User not found");
  return;
}

$email = $_POST["email"];

$query = "SELECT * FROM user WHERE user_email='$email'";

$result = api_get($query);

if ($result) {
  $user_password = $result[0]["user_password"];
  $password = $_POST["password"];
  if (!password_verify($password, $user_password)) {
    $response = new stdClass();
    $response->message = "Clave incorrecta";
    echo json_encode($response);
    header("HTTP/1.1 400 Bad request");
    return;
  }
  $payload = [
    'id' => $result[0]["user_id"]
  ];
  $auth = array("token" => createToken($payload));
  $response = array_merge($auth, $result[0]);
  unset($response["password"]);
  echo json_encode($response);
  header("HTTP/1.1 200 OK");
  return;
} else {
  $response = new stdClass();
  $response->message = "Usuario no encontrado";
  echo json_encode($response);
  header("HTTP/1.1 404 User not found");
}

?>