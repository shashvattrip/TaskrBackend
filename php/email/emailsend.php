<?php

require '/PHPMailer/PHPMailerAutoload.php';

$mail_receiver  = $newMember;

$mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "shashvat@celbits.org";
$mail->Password = "celshashvat";
$mail->SetFrom("shashvat@celbits.org");
$mail->Subject = "Taskr";

//$project_id = 3;
$ip = "localhost";

require_once('/facebook/php-sdk/facebook.php');

		  $config = array(
		      'appId' => '589333571151255',
		      'secret' => '6c80d2a59b63979beb65933fba4bae94',
		      'fileUpload' => false, // optional
		      'allowSignedRequest' => false, // optional, but should be set to false for non-canvas apps
		  );

  		$facebook = new Facebook($config);
  		//$user_id = $facebook->getUser();
  		$loginUrl = $facebook->getLoginUrl(
		    array(
		        'canvas'    => 1,
		        'fbconnect' => 0,
		        'scope' => 'email,publish_stream,offline_access',
		        'redirect_uri' => 'http://' . $ip . '/github/taskrbackend/login.php?pid='.$project_id //the url to go to after a successful login
		       
		    ));



$mail->Body = "Hello, Login to Taskr by clicking on the following link : $loginUrl";
$mail->AddAddress($mail_receiver);
 if(!$mail->Send())
    {
    echo "Mailer Error: " . $mail->ErrorInfo;
    }
    else
    {
    echo "Message has been sent";
    }

?>