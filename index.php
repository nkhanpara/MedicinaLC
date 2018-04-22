<?php
  foreach ($_POST as $key => $value) {
    //echo '<p><strong>' . $key.':</strong> '.$value.'</p>';
  }

  $res_msg = "Invalid captcha. Please verify your identity again.";

  function sanitize_my_email($field) {
    $field = filter_var($field, FILTER_SANITIZE_EMAIL);
    if (filter_var($field, FILTER_VALIDATE_EMAIL)) {
      return true;
    }
    else {
      return false;
    }
  }

  // grab recaptcha library
  require_once "recaptchalib.php";

  // your secret key
  $secret = "Opps";

  // empty response
  $response = null;

  // check secret key
  $reCaptcha = new ReCaptcha($secret);

  // if submitted check response
  if ($_POST["g-recaptcha-response"]) {
      $response = $reCaptcha->verifyResponse(
          $_SERVER["REMOTE_ADDR"],
          $_POST["g-recaptcha-response"]
      );
  }

  if ($response != null && $response->success) {
      $to_email = "opps";
      $subject = $_POST["your-subject"];
      $message = $_POST["your-message"] . "\n\nMy Contact No:" . $_POST["contact-no"];
      $headers = "From: " . $_POST["your-email"];

      //check if the email address is invalid $secure_check
      $secure_check = sanitize_my_email($_POST["your-email"]);
      if ($secure_check == false) {
        $res_msg = "Invalid email-id provided. Please use a valid one.";
      }
      else {
        //send email
        mail($to_email, $subject, $message, $headers);
        $res_msg = "Mail sent successfully. Thank you.";
      }
    }
    echo $res_msg;
?>