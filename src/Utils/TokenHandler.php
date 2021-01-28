<?php

namespace Src\Utils;

use \Firebase\JWT\JWT;

class TokenHandler
{

  public static function generateAccessToken($data, $expiry)
  {

    $jwt = self::generateJWT($data, $expiry);

    $cookieData = array(
      "name" => "accessToken",
      "jwt" => $jwt,
      "expiry" => $expiry
    );

    self::setHttpCookie($cookieData);
    self::setTimingCookie($cookieData);

  }

  public static function generateRefreshToken($data, $expiry)
  {

    $jwt = self::generateJWT($data, $expiry);

    $cookieData = array(
      "name" => "refreshToken",
      "jwt" => $jwt,
      "expiry" => $expiry
    );

    self::setHttpCookie($cookieData);

  }



  private static function generateJWT($data, $expiry)
  {
    $id = $data[0]->id;
    $email = $data[0]->email;


    $secret_key = SECRET_KEY;
    $issuer_claim = THE_ISSUER; // this can be the server name
    $audience_claim = THE_AUDIENCE;
    $issuedat_claim = time();
    $notbefore_claim = $issuedat_claim + 0; // not  before in sec
    $expire_claim = $issuedat_claim + $expiry; //expire time in sec
    $token = array(
      'iss' => $issuer_claim,
      'aud' => $audience_claim,
      'iat' => $issuedat_claim,
      'nbf' => $notbefore_claim,
      'exp' => $expire_claim,
      "data" => array(
        'id' => $id,
        'email' => $email,
      )
    );

    return JWT::encode($token, $secret_key);
  }

  private static function setHttpCookie($payload)
  {

    setcookie($payload['name'], $payload['jwt'], [
      'expires' => time() + $payload['expiry'],
      'path' => '/',
      'domain' => false,
      'secure' => true,
      'httponly' => true,
      'samesite' => 'Strict',
    ]);
  }

  private static function setTimingCookie($payload)
  {

    setcookie('RefreshTimer', 'Refresh Timer', [
      'expires' => time() + $payload['expiry'],
      'path' => '/',
      'domain' => false,
      'secure' => true,
      'httponly' => false,
      'samesite' => 'Strict',
    ]);
  }

  public static function blacklistToken($jwt, $db){
    $refreshTokenData = array(
      "database" => $db,
      "blacklistToken" => $jwt,
    );

    self::storeRevokedToken($refreshTokenData);
  }

  private static function storeRevokedToken($data)
  {

    $sql = '
  INSERT INTO tokenblacklist(
  revokedToken) VALUES
  (:revokedToken)
  ';

    return $data["database"]->execute(
      $sql,
      [
        'revokedToken' => $data['blacklistToken']
      ]
    );
  }

  public static function checkBlacklist($db)
  {

    $sql = '
  SELECT revokedToken FROM tokenblacklist
  WHERE revokedToken=:revokedToken
  ';

    return $db->execute(
      $sql,
      [
        'revokedToken' => $_COOKIE['refreshToken']
      ]
    );
  }
}
