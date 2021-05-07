<?php

include "../config/database.php";
include "../helpers/token.php";
include "../helpers/password.php";

header("Access-Control-Allow-Origin:*");

$username = $_POST["username"];

$query = "SELECT * FROM admin WHERE username='$username'";

$result = api_get($query);

if ($result) {
  $admin_password = $result[0]["password"];
  $password = $_POST["password"];
  if (!password_verify($password, $admin_password)) {
    $response = new stdClass();
    $response->message = "Clave incorrecta";
    echo json_encode($response);
    header("HTTP/1.1 400 Bad request");
    return;
  }
  $payload = [
    'id' => $result[0]["id"]
  ];
  $auth = array("token" => createToken($payload));
  $response = array_merge($auth, $result[0]);
  unset($response["password"]);
  echo json_encode($response);
  header("HTTP/1.1 200 OK");
  return;
  // $result_encode = json_encode($result[0]);
} else {
  $username = $_POST["username"];
  $response = new stdClass();
  $response->message = "Usuario no encontrado";
  echo json_encode($response);
  header("HTTP/1.1 404 Admin not found");
}

?>