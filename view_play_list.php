<?php
require __DIR__ . "/includes/header.php";

$playlist = $conn->prepare("SELECT * FROM playlist WHERE id = ? ");
$playlist->execute([$_GET['p']]);

if($playlist->rowCount() == 0) {
	header("Location: index.php");
	die();
} else {
	$playlist = $playlist->fetch(PDO::FETCH_ASSOC);
}
$creator_info = $conn->prepare("SELECT * FROM members WHERE member_id = ?");
$creator_info->execute([$video_info['member_id']]);
$creator_info = $creator_info->fetch(PDO::FETCH_ASSOC);
$playlist['videos'] = $conn->prepare("SELECT video_id FROM playlist_videos WHERE playlist_id = ?");
$playlist['videos']->execute([$playlist["playlist_id"]]);
$playlist['videos'] = $playlist['videos']->rowCount();

$videos = $conn->prepare(
	"SELECT * FROM playlist_videos
	LEFT JOIN videos ON playlist_videos.video_id = videos.video_id
	LEFT JOIN members ON members.member_id = videos.member_id
	WHERE playlist_videos.playlist_id = ? AND videos.converted = 1
	ORDER BY playlist_videos.video_id DESC"
);
$videos->execute([$playlist['id']]);
?>
<html>
<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<td style="padding-right: 15px;">

		<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#CCCCCC">
			<tr>
				<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
				<td width="100%"><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
				<td>
				
				<div class="watchTitleBar">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr valign="top">
							<td><div class="moduleTitle">PlayList Videos / <span style="text-transform: capitalize;"><?php echo htmlspecialchars($playlist['title']); ?></span></div></td>
 							<td align="right"> 
								<div style="font-weight: bold; color: #444; margin-right: 5px;">
									Videos 7
								</div>
							</td>
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
								if(!in_array(strtolower($tag), array_map('strtolower', $related_tags))) { // To prevent repeated tags.
									array_push($related_tags, $tag);
								}
							}
							?></div>
							<div class="moduleEntryDetails">Added: <?php echo date("F j, Y", strtotime($video['uploaded_at'])); ?> by <a href="profile.php?user=<?php echo htmlspecialchars($video['username']); ?>"><?php echo htmlspecialchars($video['username']); ?></a></div>
							<div class="moduleEntryDetails">Runtime: <?php echo gmdate("i:s", $video['duration']); ?> | Views: <?php echo $video['views']; ?> | Comments: <?php echo $video['comments']; ?></div>
						</td>
					</tr>
				</table>
			</div>
            </td>
			<?php } ?>
			<?php } ?>
				
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
			</tr>
			<tr>
				<td><img src="/img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</table>

		</td>
		<td width="180">
		<div style="font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 13px;">Playlist Creator:</div> 
		<div style="font-family: Arial, Helvetica, sans-serif; font-weight: normal; font-size: 13px;"><a href="/profile?user=<?php echo htmlspecialchars($creator_info['username']); ?>"><?php echo htmlspecialchars($creator_info['username']); ?></a></div>
		<br/>
		<div style="font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 13px;">Playlist Description:</div> 
		<div style="font-family: Arial, Helvetica, sans-serif; font-weight: normal; font-size: 13px;"><?php echo htmlspecialchars($playlist['description']); ?></div>	
 		<br/>
		<div style="font-family: Arial, Helvetica, sans-serif; font-weight: normal; font-size: 13px;"><a href="profile_play_list?user=<?php echo htmlspecialchars($creator_info['username']); ?>">view all of <?php echo htmlspecialchars($creator_info['username']); ?>'s playlists</a></div>
		</td>
	</tr>
</table>



		</td>
	</tr>
</table>


<table cellpadding="10" cellspacing="0" border="0" align="center">
	<tr>
		<td align="center" valign="center">