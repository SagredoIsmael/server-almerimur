<?php


$conn = null;

function api_connect(){
  $host = "localhost";
  $user = "root";
  $password = "garciasanchezz12";
  $bd = "almerimur";
  $GLOBALS["conn"] = mysqli_connect($host, $user, $password, $bd) or die (mysqli_connect_error());
}


function api_close(){
  mysqli_close($GLOBALS["conn"]);
}

function api_get($query) {
  try {
    api_connect();
    $result = mysqli_query($GLOBALS["conn"], $query);
    $results = null;
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
      $results[] = $row;
    }
    $result->close();
    api_close();
    return $results;
  } catch(Exception $e) {
    die ("Error". $e);
  }
}

function api_post($query){
  try {
    api_connect();
    $result = mysqli_query($GLOBALS["conn"], $query);
    if (!$result) {
      die(mysqli_error($GLOBALS["conn"]));
    }
    $lastInsertedId = mysqli_insert_id($GLOBALS["conn"]);
    api_close();
    $response = [
      "id" => $lastInsertedId
    ];
    return $response;
  } catch(Exception $e) {
    die ("Error". $e);
  }
}

?>