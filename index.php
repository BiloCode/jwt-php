<?php
  require_once("./src/JsonWebToken.php");

  $key = "jwt_key";
  $jwt = new JsonWebToken();
  $token = $jwt->encode([ "userId" => 1 ], $key);

  echo "Bearer " . $token;