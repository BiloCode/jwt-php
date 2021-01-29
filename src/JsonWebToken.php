<?php

  class JsonWebToken {

    const TOKEN_METHOD = "AES-128-ECB";

    private function decode_all($token, $key){
      $decode_token = base64_decode($token);
      $decode_token = openssl_decrypt($decode_token, self::TOKEN_METHOD, $key);
      $decode_token = json_decode($decode_token);

      if(!isset($decode_token)){
        throw new JwtDecodeException("Invalid token format.");
      }

      return $decode_token;
    }

    function encode($payload, $key, $expired_days = "14 days"){
      $data = [
        "payload" => json_encode($payload),
        "createdAt" => strtotime("now"),
        "expiredIn" => strtotime("+ $expired_days")
      ];

      $token = openssl_encrypt(json_encode($data), self::TOKEN_METHOD, $key);
      $token = base64_encode($token);

      return $token;
    }

    function decode($token, $key){
      $decode_token = $this->decode_all($token, $key);
      return $decode_token->payload;
    }

    function expired($token, $key) {
      $decode_token = $this->decode_all($token, $key);

      $token_created_at = $decode_token->createdAt;
      $token_expired = $decode_token->expiredIn;

      return $token_created_at >= $token_expired; 
    }

  }