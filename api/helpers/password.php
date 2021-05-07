<?php

function hashPassword($password){
  $options = [
    'cost' => 12,
  ];
  return password_hash($password, PASSWORD_BCRYPT, $options);
}

?>