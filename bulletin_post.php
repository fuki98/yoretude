<?php
require __DIR__ . "/includes/header.php";


// Make sure the user is logged in.
if(!isset($session)) {
	header("Location: login.php", true, 401);
	die();
}

// Post that bulletin!
if(isset($_POST['subject']) || isset($_POST['body'])) {
$post_bulletin = $conn->prepare("INSERT INTO bulletins (bulletin_id, subject, member_id, body) VALUES (:bulletin_id, :subject, :member_id, :body)");
$post_bulletin->execute([
	":bulletin_id" => generateId(8),
	":subject" => trim($_POST['subject']),
	":member_id" => $session['member_id'],
	":body" => trim($_POST['comment'])
]);
}
?>
<div class="tableSubTitle">Bulletin Creation</div>
<form method="POST">
<b>Subject</b>:<br><textarea maxlength="500" rows="1" name="subject"></textarea>
<br>
<br>
<b>Comment</b>:<br><textarea maxlength="500" name="comment" cols="66" rows="6"></textarea>
<br>
<br>
        <td><input type="submit" name="post_bulletin" value="Post Bulletin"> <a href="/"><button type="button">Cancel</button></a></td>
