<?php
$to = ''; // Put the eMail Address for the request here.
$subject = 'Receptionist 0.2 - How Can I help? - "'.$_POST['query'].'"'; // edit the subject to your liking
$message = $_POST['message']."\n\n".$_POST['referer'];

$header = 'From: '.$_POST['email'] . "\r\n";
$header .= 'X-Mailer: PHP/' . phpversion() . "\r\n"; 

mail($to, $subject, $message, $header);

header("Location: ".$_SERVER['HTTP_REFERER']);
?>