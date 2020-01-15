<?php

$MAILTO = array('thilo@reimers.de', 'Reimers'); 

if(empty($_POST))
	exit();

$errors = array();

if(empty($_POST['mail']) || !filter_var($_POST['mail'],FILTER_VALIDATE_EMAIL))
	$errors[] = 'E-Mail-Adresse fehlt oder besteht aus ungültigen Zeichen.';
else $POST_SAN['mail'] = $_POST['mail'];

if(empty($_POST['von'])) $errors[] = 'Der Name fehlt.';
else $POST_SAN['von'] = strip_tags($_POST['von']);

if(empty($_POST['betreff'])) $errors[] = 'Der Betreff fehlt.';
else $POST_SAN['betreff'] = strip_tags($_POST['betreff']);

if(empty($_POST['nachricht'])) $errors[] = 'Die Nachricht fehlt.';
else $POST_SAN['nachricht'] = strip_tags($_POST['nachricht']);

if(count($errors) > 0) {
	printf('
		<div class="error"><h3>Ein oder mehrere Fehler sind aufgetreten</h3>
		<ul><li>%s</li></ul></div>',
		implode('</li><li>', $errors));
} else {

	require(__DIR__.'/PHPMailer/PHPMailerAutoload.php');
	$mail = new PHPMailer();
	$mail->CharSet = 'utf-8';
	$mail->Mailer = 'mail';

	$mail->setFrom('thilo@reimers.de', 'Rechtsanwalt Reimers');
	$mail->addReplyTo($POST_SAN['mail'], $POST_SAN['von']);
	$mail->addAddress($MAILTO[0], $MAILTO[0]);

	$mail->Subject = $POST_SAN['betreff'];

	//nur die nachricht $mail->Body = $POST_SAN['nachricht'];
	$mail->Body = sprintf("%s\n%s\n\n%s", $POST_SAN['von'], $POST_SAN['mail'], $POST_SAN['nachricht']);


	//send the message, check for errors
	if (!$mail->send()) {
		printf('<div class="error"><h3>Kontaktanfrage
			konnte nicht versendet werden.</h3>');
	} else {
		printf('<div class="special-title"><h3>Ihre Anfrage wurde
			erfolgreich versendet.<br><br><a href="index.html">Zurück</a></h3>
			<!-- Google Code for Formular Thilo Conversion Page -->
			<script type="text/javascript">
			/* <![CDATA[ */
			var google_conversion_id = 1004745509;
			var google_conversion_language = "en";
			var google_conversion_format = "3";
			var google_conversion_color = "ffffff";
			var google_conversion_label = "J6-xCJD6iXIQpeaM3wM";
			var google_remarketing_only = false;
			/* ]]> */
			</script>
			<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
			</script>
			<noscript>
			<div style="display:inline;">
			<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1004745509/?label=J6-xCJD6iXIQpeaM3wM&amp;guid=ON&amp;script=0"/>
			</div>
			</noscript>');
	}
}
?>