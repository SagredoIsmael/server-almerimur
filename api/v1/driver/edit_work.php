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

  
  $user_id = $token_decode->id;
  $id = $_POST["id"];
  $client_name = $_POST["client"] ? $_POST["client"] : "NULL";  
  $project_name = $_POST["project"] ? $_POST["project"] : "NULL";
  $date= $_POST["date"] ? $_POST["date"] : "NULL";
  $vehicle = $_POST["vehicle"] ? $_POST["vehicle"] : "NULL";
  $concept = $_POST["concept"] ? $_POST["concept"] : "NULL";
  $hours = $_POST["hours"] ? $_POST["hours"] : "NULL";
  $travels = $_POST["travels"] ? $_POST["travels"] : "NULL";
  $comments = $_POST["comments"] ? $_POST["comments"] : "NULL";


  $query = "UPDATE driver_work SET driver_work_client_name='$client_name', driver_work_project_name='$project_name', driver_work_date='$date', driver_work_vehicle_name='$vehicle', driver_work_concept='$concept', driver_work_hours='$hours', driver_work_travels='$travels', driver_work_comments='$comments' WHERE driver_work_id='$id'";

  $query = str_replace("'NULL'", "NULL", $query);

  api_post($query);

  $response = [
    'message' => "Trabajo actualizado",
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