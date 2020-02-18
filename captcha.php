<?php

include("init.php");

$captcha = $_POST['g-recaptcha-response'] ?? '';

$valid = true;

if(!$captcha){
  $valid = false;
  return $valid;
}

$ip = $_SERVER['REMOTE_ADDR'];

// post request to server
$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode(GOOGLE_RECAPTCHA_SECRET) .  '&response=' . urlencode($captcha);
$response = file_get_contents($url);
$responseKeys = json_decode($response,true);

// Captcha Success
if($responseKeys["success"]) {
  $valid = true;
} else {
  $valid = false;
}

return $valid;

?>