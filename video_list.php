<?php
require __DIR__ . "/includes/functions.php";

if(!isset($_GET['user'])) require __DIR__ . "/errors/404.php";

$profile = $conn->prepare("SELECT * FROM members WHERE members.username = ?");
$profile->execute([$_GET['user']]);

if($profile->rowCount() == 0) {
	require __DIR__ . "/errors/404.php";
} else {
	$profile = $profile->fetch(PDO::FETCH_ASSOC);
}

$profile['videos'] = $conn->prepare("SELECT video_id FROM videos WHERE member_id = ? AND converted = 1");
$profile['videos']->execute([$profile["member_id"]]);
$profile['videos'] = $profile['videos']->rowCount();
$profile['favorites'] = $conn->prepare("SELECT favorite_id FROM favorites WHERE member_id = ?");
$profile['favorites']->execute([$profile["member_id"]]);
$profile['favorites'] = $profile['favorites']->rowCount();

$videos = $conn->prepare(
	"SELECT * FROM videos
	LEFT JOIN members ON members.member_id = videos.member_id
	WHERE videos.member_id = ? AND videos.converted = 1
	ORDER BY videos.uploaded_at DESC"
);
$videos->execute([$profile['member_id']]);

$related_tags = [];
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>YoreTude - Your Digital Video Repository</title>
<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body style="background-color:#DDDDDD">
<div style="font-weight: bold; padding-left: 5px; padding: 3px; padding-right: 5px; border-bottom: 1px dashed #999;">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td><b>My Videos // <span style="text-transform: capitalize;"><?php echo htmlspecialchars($profile["username"]); ?></span> (<?php echo $profile['videos']; ?>)</b></td>
		<td align="right"><div style="vertical-align: text-bottom; text-align: right; padding: 2px;">
		<a href="index.php" target="_blank"><img src="/img/logo_sm.gif" width="38" height="15" border="0"></a>
		</div></td>
	</tr>
</table>
</div>
<?php if($videos !== false) { ?>
<?php foreach($videos as $video) { ?>
<?php
$video['views'] = $conn->prepare("SELECT COUNT(view_id) AS views FROM views WHERE video_id = ?");
$video['views']->execute([$video['video_id']]);
$video['views'] = $video['views']->fetchColumn();
$video['comments'] = $conn->prepare("SELECT COUNT(comment_id) AS comments FROM comments WHERE video_id = ?");
$video['comments']->execute([$video['video_id']]);
$video['comments'] = $video['comments']->fetchColumn();
$tags = explode(" ", $video['tags']);
?>
<div class="moduleEntry">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<td><a href="watch.php?v=<?php echo htmlspecialchars($video['video_id']); ?>" class="bold" target="_parent"><img src="get_still.php?video_id=<?php echo htmlspecialchars($video['video_id']); ?>" class="moduleEntryThumb" width="80" height="60"></a></td>
		<td width="100%"><div class="moduleFrameTitle"><a href="watch.php?v=<?php echo htmlspecialchars($video['video_id']); ?>" target="_parent"><?php echo htmlspecialchars($video['title']); ?></a></div>
		<div class="moduleFrameDetails">Added: <?php echo date("F j, Y", strtotime($video['uploaded_at'])); ?>		<br>by <a href="profile.php?user=<?php echo htmlspecialchars($video['username']); ?>" target="_parent"><?php echo htmlspecialchars($video['username']); ?></a></div>
		<div class="moduleFrameDetails">Views:  <?php echo $video['views']; ?> <br> Comments: <?php echo $video['comments']; ?></div>
		
		</td>
	</tr>
</table>
</div>
<?php } ?>
<?php } ?>
</body>
