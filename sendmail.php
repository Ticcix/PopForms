<?php

	$site_owners_email = 'example@example.com'; // თქვენი ელ-ფოსტა
	$site_owners_name = 'Example'; // საიტის სახელი

	$name = filter_var($_POST['contactName'], FILTER_SANITIZE_STRING);
	$email = filter_var($_POST['contactEmail'], FILTER_SANITIZE_EMAIL);
	$subject = filter_var($_POST['contactSubject'], FILTER_SANITIZE_STRING);
	$message = filter_var($_POST['contactMessage'], FILTER_SANITIZE_STRING);
	
	$error = "";

	if (strlen($name) < 2) {
		$error['name'] = "მიუთითეთ თქვენი სახელი.";
	}

	if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email)) {
		$error['email'] = "მიუთითეთ თქვენი ელ-ფოსტა";
	}

	if (strlen($subject) < 2) {
		$error['subject'] = "მიუთითეთ თემა.";
	}

	if (strlen($message) < 2) {
		$error['message'] = "დაწერეთ კომენტარი.";
	}

	if (!$error) {

		require_once('phpmailer/class.phpmailer.php');
		$mail = new PHPMailer();
        
		$mail->AddAddress($site_owners_email, $site_owners_name);
		$mail->IsHTML(true);

		$mail->From = $email;
		$mail->FromName = $name;
		$mail->Subject = $subject;
		$mail->Body = '<b>სახელი:</b> '. $name .'<br/><b>E-mail:</b> '. $email . '<br/><br/><b>შეტყობინება:</b><br/>' . $message;

		$mail->Send();

		echo "<div class='alert alert-success' role='alert'>Thanks " . $name . ". თქვენი შეტყობინება იქნა გაგზავნილი.</div>";

	} # შეცდომა
	else {

		$response = (isset($error['name'])) ? "<div class='alert alert-danger'  role='alert'>" . $error['name'] . "</div> \n" : null;
		$response .= (isset($error['email'])) ? "<div class='alert alert-danger'  role='alert'>" . $error['email'] . "</div> \n" : null;
		$response .= (isset($error['subject'])) ? "<div class='alert alert-danger'  role='alert'>" . $error['subject'] . "</div> \n" : null;
		$response .= (isset($error['message'])) ? "<div class='alert alert-danger'  role='alert'>" . $error['message'] . "</div>" : null;

		echo $response;
	} # გაგზავნის მეთოდი

?>