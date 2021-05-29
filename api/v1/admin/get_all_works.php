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

  $query_driver = "SELECT driver_work_id as id, driver_work_date as date,user_name as userName, user_role as role, driver_work_project_name as project, driver_work_client_name as client, driver_work_hours as hours, driver_work_created_at as createdAt, user_hourly as hourly FROM driver_work INNER JOIN user ON user_id = driver_work_user_id ORDER BY driver_work_date";

  $query_mechanic = "SELECT mechanic_work_id as id, mechanic_work_date as date, mechanic_work_machine_name as machine, mechanic_work_client_name as client, mechanic_work_hours as hours, mechanic_work_created_at as createdAt, user_name as userName, user_role as role, user_hourly as hourly FROM mechanic_work INNER JOIN user ON user_id = mechanic_work_user_id ORDER BY mechanic_work_date";

  $result_driver = api_get($query_driver);
  $result_mechanic = api_get($query_mechanic);

  if (!$result_driver && !$result_mechanic) {
    $response = [
      'message' => "Sin trabajos obtenidos",
      'works' => [],
    ];

    echo json_encode($response);
    header("HTTP/1.1 200 OK");
    return;
  }

  if ($result_driver && !$result_mechanic) {
    $response = [
      'message' => "Trabajos de conductor obtenidos",
      'works' => $result_driver,
    ];

    echo json_encode($response);
    header("HTTP/1.1 200 OK");
    return;
  }

  if (!$result_driver && $result_mechanic) {
    $response = [
      'message' => "Trabajos de mecanico obtenidos",
      'works' => $result_mechanic,
    ];

    echo json_encode($response);
    header("HTTP/1.1 200 OK");
    return;
  }

  $result = array_merge($result_driver, $result_mechanic);

  $response = [
    'message' => "Trabajos de mecanico y conductor obtenidos",
    'works' => $result,
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