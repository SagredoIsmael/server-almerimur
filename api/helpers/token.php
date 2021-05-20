<?php

require __DIR__.'/../vendor/autoload.php';

use \Firebase\JWT\JWT;


function createToken($token_payload) {
  $key = '__test_secret__';
  $jwt = JWT::encode($token_payload, $key, 'HS256');
  return $jwt;
}

function decodeToken($jwt_token) {
  $key = '__test_secret__';
  $decoded = JWT::decode($jwt_token, $key, array('HS256'));
  return $decoded;
}


?>