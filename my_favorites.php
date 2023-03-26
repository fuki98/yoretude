<?php
require __DIR__ . "/includes/header.php";

if(!isset($session)) {
	header("Location: login.php");
}

$videos = $conn->prepare(
	"SELECT * FROM favorites
	LEFT JOIN videos ON favorites.video_id = videos.video_id
	LEFT JOIN members ON members.member_id = videos.member_id
	WHERE favorites.member_id = ? AND videos.converted = 1
	ORDER BY favorites.favorite_id DESC"
);
$videos->execute([$session['member_id']]);

$related_tags = [];
?>

<div class="pageTitle">My Favorites</div>

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
						<td><div class="watchTitle">My Favorites</div></td>
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
						<td>
							<a href="watch.php?v=<?php echo htmlspecialchars($video['video_id']); ?>"><img src="get_still.php?video_id=<?php echo htmlspecialchars($video['video_id']); ?>" class="moduleEntryThumb" width="120" height="90"></a>
							<table width="130" cellpadding="0" cellspacing="0" border="0">
								<tr align="center">
									<td width="100%">
										<br><form method="get" action="remove_favorites.php" onsubmit="return confirm('Are you sure you want to remove this video from your favorites?');">
											<input type="hidden" value="<?php echo $video['video_id']; ?>" name="video_id">
											<input type="submit" value="Remove Video">
										</form>
									</td>
								</tr>
							</table>
						</td>
						<td width="100%">
							<div class="moduleEntryTitle"><a href="watch.php?v=<?php echo htmlspecialchars($video['video_id']); ?>"><?php echo htmlspecialchars($video['title']); ?></a></div>
							<div class="moduleEntryDescription"><?php echo htmlspecialchars($video['description']); ?></div>
							<div class="moduleEntryTags">Tags // <?php
							foreach($tags as $tag) echo '<a href="results.php?search='.htmlspecialchars($tag).'">'.htmlspecialchars($tag).'</a> : ';
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
