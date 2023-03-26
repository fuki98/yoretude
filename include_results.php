<?php
require __DIR__ . "/includes/functions.php";

$search = str_replace(" ", "|", trim($_GET['search']));
$videos = $conn->prepare("SELECT * FROM videos LEFT JOIN members ON members.member_id = videos.member_id WHERE videos.tags REGEXP ? AND videos.converted = 1 ORDER BY videos.uploaded_at DESC"); // Regex!
$videos->execute([$search]);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>YoreTude - Your Digital Video Repository</title>
<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body style="background-color:#DDDDDD">
<?php foreach($videos as $video) { ?>
<?php
$video['views'] = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE video_id = ?");
$video['views']->execute([$video['video_id']]);
$video['views'] = $video['views']->fetchColumn();
						
$video['comments'] = $conn->prepare("SELECT COUNT(comment_id) FROM comments WHERE video_id = ?");
$video['comments']->execute([$video['video_id']]);
$video['comments'] = $video['comments']->fetchColumn();

if($video['video_id'] == $_GET['v']) echo '<a id="selected"></a>';
?>
<div class="<?php echo ($video['video_id'] == $_GET['v']) ? ("moduleFrameEntrySelected") : ("moduleFrameEntry"); ?>">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr valign="top">
			<td><a href="watch.php?v=<?php echo htmlspecialchars($video['video_id']); ?>" class="bold" target="_parent"><img src="get_still.php?video_id=<?php echo htmlspecialchars($video['video_id']); ?>" class="moduleEntryThumb" width="80" height="60"></a></td>
			<td width="100%">
				<div class="moduleFrameTitle"><a href="watch.php?v=<?php echo htmlspecialchars($video['video_id']); ?>" target="_parent"><?php echo htmlspecialchars($video['title']); ?></a></div>
				<div class="moduleFrameDetails">Added: <?php echo date("F j, Y", strtotime($video['uploaded_at'])); ?> by  <a href="profile.php?user=<?php echo htmlspecialchars($video['username']); ?>" target="_parent"><?php echo htmlspecialchars($video['username']); ?></a></div>
				<div class="moduleFrameDetails">Runtime: <?php echo gmdate("i:s", $video['duration']); ?><br>Views: <?php echo $video['views']; ?><br>Comments: <?php echo $video['comments']; ?></div>
				<?php if($video['video_id'] == $_GET['v']) { ?>
					<div style="font-size: 13px; font-weight: bold; padding: 4px 6px 4px 6px; background-color:#FFCC66; color: #AA7733; ">&#60;&#60;&#60; NOW PLAYING!</div>
				<?php } ?>
			</td>
		</tr>
	</table>
</div>
<?php } ?>

</body>
</html>
