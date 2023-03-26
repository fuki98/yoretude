<?php
$email = $_POST['contact_email'];
contact_message = $_POST['message'];
$formcontent="From: $name \n Message: $message";
$recipient = "contact@yoretude.com";
$subject = $_POST['contact_subject'];
$mailheader = "From: $email \r\n";
mail($recipient, $subject, $formcontent, $mailheader) or die("Error!");
echo "Thank You!";
?>
