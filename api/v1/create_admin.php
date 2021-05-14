<?php

include "../config/database.php";
include "../helpers/password.php";

header("Access-Control-Allow-Origin:*");

$username = $_POST["username"];
$password = hashPassword($_POST["password"]);

$query = "INSERT INTO admin(admin_username, admin_password) VALUES ('$username', '$password')";

api_post($query);

header("HTTP/1.1 200 OK");

?>