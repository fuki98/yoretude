<?php
require __DIR__ . "/includes/header.php";

if(!isset($_GET['user'])) require __DIR__ . "/index_down.php";

$profile = $conn->prepare("SELECT * FROM members WHERE members.username = ?");
$profile->execute([$_GET['user']]);

if($profile->rowCount() == 0) {
	header("Location: index_down.php");
} else {
	$profile = $profile->fetch(PDO::FETCH_ASSOC);
}

$profile['videos'] = $conn->prepare("SELECT video_id FROM videos WHERE member_id = ? AND converted = 1");
$profile['videos']->execute([$profile["member_id"]]);
$profile['videos'] = $profile['videos']->rowCount();
$profile['favorites'] = $conn->prepare("SELECT favorite_id FROM favorites WHERE member_id = ?");
$profile['favorites']->execute([$profile["member_id"]]);
$profile['favorites'] = $profile['favorites']->rowCount();

$profile_latest_video = $conn->prepare(
	"SELECT * FROM videos
	LEFT JOIN members ON members.member_id = videos.member_id
	WHERE videos.member_id = ? AND videos.converted = 1
	GROUP BY videos.video_id
	ORDER BY videos.uploaded_at DESC LIMIT 1"
);
$profile_latest_video->execute([$profile['member_id']]);

if($profile_latest_video->rowCount() == 0) {
	$profile_latest_video = false;
} else {
	$profile_latest_video = $profile_latest_video->fetch(PDO::FETCH_ASSOC);
	
	$profile_latest_video['views'] = $conn->prepare("SELECT COUNT(view_id) AS views FROM views WHERE video_id = ?");
	$profile_latest_video['views']->execute([$profile_latest_video['video_id']]);
	$profile_latest_video['views'] = $profile_latest_video['views']->fetchColumn();
	
	$profile_latest_video['comments'] = $conn->prepare("SELECT COUNT(comment_id) AS comments FROM comments WHERE video_id = ?");
	$profile_latest_video['comments']->execute([$profile_latest_video['video_id']]);
	$profile_latest_video['comments'] = $profile_latest_video['comments']->fetchColumn();
    $videos = $conn->prepare(
	"SELECT * FROM videos
	LEFT JOIN members ON members.member_id = videos.member_id
	WHERE videos.member_id = ? AND videos.converted = 1
	ORDER BY videos.uploaded_at DESC LIMIT 4"
);
$videos->execute([$profile['member_id']]);
$favorites = $conn->prepare(
	"SELECT * FROM favorites
	LEFT JOIN videos ON favorites.video_id = videos.video_id
	LEFT JOIN members ON members.member_id = videos.member_id
	WHERE favorites.member_id = ? AND videos.converted = 1
	ORDER BY favorites.favorite_id DESC LIMIT 4"
);
$favorites->execute([$profile['member_id']]);
}

if(empty($profile['channelicon'])) {
	$profile['channelicon'] = $profile_latest_video['video_id'];
}
?>

	<link rel="stylesheet" href="/styles.css" type="text/css">
	<link rel="stylesheet" href="/base.css" type="text/css">
    		
<style type="text/css">
	.userTable {
	border: 1px solid #999999;
	background-color: #F4F4F4;
	width: 300px;
	}
	
	.spaceMaker {
	padding-top: 2px;
	}
		
	tr.rows td{
	padding-top: 6px;
	padding-bottom: 6px;
	padding-left: 15px;
	}
	
	tr.connectRows td{
	padding-top: 3px;
	}
	
	tr.broadcastRow td {
	padding-top: 6px;
	padding-bottom: 6px;
	}
	
	tr.connectRowsTop td {
	padding-top: 8px;
	}
	
	tr.connectRowsBottom td {
	padding-top: 3px;
	padding-bottom: 8px;
	}

	tr.rowsLine td{
	padding-top: 6px;
	padding-bottom: 6px;
	padding-left: 15px;
	border-bottom: 1px solid #999999;
	}
	
	tr.rowsLineBottom td{
	padding-top: 6px;
	padding-bottom: 6px;
	padding-left: 15px;
	}
	
	.profileTitles {
	font-family:  Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #999999;
	padding-top: 4px;
	padding-bottom: 4px;
	}
	
	.profileHeaders {
	background-color: #999999;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	font-weight: bold;
	color: #FFFFFF;
	padding-bottom: 3px;
	padding-top: 3px;
	}
	
	.aboutTable {
	width: 560px;
	border: 1px solid #999999;
	}
	
	.aboutImg {
	width: 140px;
	height: 108px;
	border: 2px solid #999999;
	}
	
	.commentPostTable {
	width: 560px;
	border: 1px solid #999999;
	border: 1px solid #999999;
	}
	
	
	.videoPostTable {
	width: 560px;
	border: 1px solid #999999;
	padding-left: 15px;
	}
	
	.videoPostImg {
	width: 154px;
	height: 124px;
	border: 1px solid #999999;
	}
	
	
	.connectTable {
	border: 1px solid #999999;	
	width: 300px;
	}
	
	.topSpace {
	padding-top: 3px;
	}	
	
	.connectImages {
	padding-left: 3px;
	}
	
	.connectImages2 {
	padding-left: 2px;
	}

	
	.connectLinks {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight: normal;
	}
	
	.bulletinTable {
	width: 300px;
	border: 1px solid #999999;
	}
	
	tr.bulletin td {
	background-color: #F4F4F4;
	border-right: 1px solid #FFFFFF;
	padding-top: 3px;
	padding-bottom: 3px;
	padding-left: 3px;
	padding-right: 3px;
	border-bottom: 1px solid #FFFFFF;
	}
	
	tr.bulletinTitle td {
	padding-top: 3px;
	padding-bottom: 3px;
	border-bottom: 1px solid #999999;
	}
	
	tr.bulletinPost td {
	padding-top: 5px;
	padding-bottom: 5px;
	border-top: 1px solid #999999;
	}
	
	tr.commentsMsg td {
	padding-top: 5px;
	padding-bottom: 5px;
	border-top: 1px solid #999999;
	background-color: #F4F4F4;
	}
	
	td.buttonPost {
	padding-top: 4px;
	padding-bottom: 4px;
	border-top: 1px solid #999999;
	}
	
	td.bulletinTopFirstCells {
	border-right: 1px solid #999999;
	border-bottom: none;
	}
	
	td.checkBoxSection {
	background-color: #F4F4F4;
	width: 15px;
	border-right: 1px solid #999999;
	}
		
	
	.bulletinSmallImg {
	padding-left: 5px;
	}
	
	.commentsImg {
	width: 60px;
	border: 2px solid #999999;
	}
	
	tr.comments td{
	border-bottom: 1px solid #999999;
	padding-top: 3px;
	padding-bottom: 5px;
	}
	
	
	.bulletinReadTable {
	width: 560px;
	border: 1px solid #999999;
	}
	
	td.bulletinRead {
	background-color: #F4F4F4;
	border-right: 1px solid #999999;
	border-bottom: 1px solid #999999;
	}
	
	td.bulletinReadBottom {
	background-color: #F4F4F4;
	border-right: 1px solid #999999;
	}
	
	
	td.bulletinReadLast {
	background-color: #F4F4F4;
	border-bottom: 1px solid #999999;
	}
	
	
	tr.emptyBulletin td{
	background-color: #F4F4F4;
	padding-top: 3px;
	padding-bottom: 5px;
	}
	
	
	td.leftBg {
	background-color: #F4F4F4;
	border-right: 1px solid #999999;
	}
	

	a.edit:link {color: white; text-decoration: underline; }
	a.edit:visited {color: white; text-decoration: underline; }
	a.edit:hover {color: white; text-decoration: underline; }
	a.edit:active {color: white; text-decoration: underline; } 


	
	td.bulletinReadRight {
	border-bottom: 1px solid #999999;
	}
	
	
	td.bulletinReadRightBottom {
	border-bottom: none;
	}
	
	td.bulletinReadBottom {
	background-color: #F4F4F4;
	border-right: 1px solid #999999;
	border-bottom: none;
	}
	
	tr.bulletinCols td {
	border-bottom: 1px solid #999999;
	padding-top: 3px;
	padding-bottom: 5px;
	}
	
	td.bulletinData {
	border-right: 1px solid #999999;
	padding-bottom: 5px;
	padding-right: 3px;
	padding-left: 3px;
	}

</style>	
	
<table align="center" width="800" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 10px;">
	<tr>
		<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
		<td><img src="/img/pixel.gif" width="1" height="5"></td>
		<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
	</tr>
	<tr>
		<td><img src="/img/pixel.gif" width="5" height="1"></td>
		<td width="790" align="center" style="padding: 2px;">

		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td style="font-size: 10px;">&nbsp;</td>
				
								
								
								
								
				<td style="font-size: 10px;">&nbsp;</td>
			</tr>
		</table>
			
		</td>
		<td><img src="/img/pixel.gif" width="5" height="1"></td>
	</tr>
	<tr>
	</tr>
</table>

<div style="padding: 0px 5px 0px 5px;">

<div>
<table align="center" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td><a href="profile.php?user=<?php echo htmlspecialchars($profile["username"]); ?>">Profile</a></td>
		<td style="padding: 0px 5px 0px 5px;">|</td>
		<td><strong>Public Videos</strong> (<?php echo $profile['videos']; ?>)</td>
		<td style="padding: 0px 5px 0px 5px;">|</td>
		<td><a href="profile_videos_private.php?user=<?php echo htmlspecialchars($profile["username"]); ?>">Private Videos</a> (0)</td>
		<td style="padding: 0px 5px 0px 5px;">|</td>
		<td><a href="profile_favorites.php?user=<?php echo htmlspecialchars($profile["username"]); ?>">Favorites</a> (<?php echo $profile['favorites']; ?>)</td>
		<td style="padding: 0px 5px 0px 5px;">|</td>
		<td><a href="profile_friends.php?user=<?php echo htmlspecialchars($profile["username"]); ?>">Friends</a> (0)</td>
	</tr>
</table>
</div><br><br>

<div class="pageTable">
<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#CCCCCC">
	<tr>
		<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
		<td width="100%"><img src="img/pixel.gif" width="1" height="5"></td>
		<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
	</tr>
	<tr>
		<td><img src="img/pixel.gif" width="5" height="1"></td>
		<td>
			<div class="watchTitleBar">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr valign="top">
						<td><div class="watchTitle">Videos // <span style="text-transform: capitalize;"><?php echo htmlspecialchars($profile['username']); ?></span></div></td>
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
						<td><a href="watch.php?v=<?php echo htmlspecialchars($video['video_id']); ?>"><img src="get_still.php?video_id=<?php echo htmlspecialchars($video['video_id']); ?>" class="moduleEntryThumb" width="120" height="90"></a></td>
						<td width="100%">
							<div class="moduleEntryTitle"><a href="watch.php?v=<?php echo htmlspecialchars($video['video_id']); ?>"><?php echo htmlspecialchars($video['title']); ?></a></div>
							<div class="moduleEntryDescription"><?php echo htmlspecialchars($video['description']); ?></div>
							<div class="moduleEntryTags">Tags // <?php
							foreach($tags as $tag) {
								echo '<a href="results.php?search='.htmlspecialchars($tag).'">'.htmlspecialchars($tag).'</a> : ';
							}
							?></div>
							<div class="moduleEntryDetails">Added: <?php echo date("F j, Y", strtotime($video['uploaded_at'])); ?> by <a href="profile.php?user=<?php echo htmlspecialchars($video['username']); ?>"><?php echo htmlspecialchars($video['username']); ?></a></div>
							<div class="moduleEntryDetails">Runtime: <?php echo gmdate("i:s", $video['duration']); ?> | Views: <?php echo $video['views']; ?> | Comments: <?php echo $video['comments']; ?></div>
						</td>
					</tr>
				</table>
			</div>
			
			<?php } ?>
			<?php } ?>
		</td>
		<td><img src="img/pixel.gif" width="5" height="1"></td>
	</tr>
	<tr>
		<td><img src="img/box_login_bl.gif" width="5" height="5"></td>
		<td><img src="img/pixel.gif" width="1" height="5"></td>
		<td><img src="img/box_login_br.gif" width="5" height="5"></td>
	</tr>
</table>
</div>
<?php
require __DIR__ . "/includes/footer.php";
?> 