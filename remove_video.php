<?php
require __DIR__ . "/includes/header.php";
ob_get_clean();

// Make sure the user is logged in.
if(!isset($session)) {
	header("Location: login.php", true, 401);
	die();
}

$video = $conn->prepare("SELECT member_id FROM videos WHERE video_id = ?");
$video->execute([$_GET['video_id']]);
$video = $video->fetch(PDO::FETCH_ASSOC);

if($video['member_id'] == $session['member_id']) {
	$remove_video = $conn->prepare("DELETE FROM videos WHERE member_id = :member_id AND video_id = :video_id");
	$remove_video->execute([
		":member_id" => $session['member_id'],
		":video_id" => $_GET['video_id']
	]);
	try {
		unlink(__DIR__ . "/videos/".$_GET['video_id'].".flv");
		unlink(__DIR__ . "/videos/".$_GET['video_id'].".webm");
		unlink(__DIR__ . "/stills/".$_GET['video_id']."_1.jpg");
		unlink(__DIR__ . "/stills/".$_GET['video_id']."_2.jpg");
		unlink(__DIR__ . "/stills/".$_GET['video_id']."_3.jpg");
	} catch (Exception $e) {
		// who cares
	}
}

header("Location: my_videos.php");
?>