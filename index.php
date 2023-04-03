<?php
session_start();
if(!isset($header_loaded)) {
	require __DIR__ . "/includes/header.php";
}

if(isset($_GET['v'])) {
	header("Location: watch.php?v=".$_GET['v'], true, 303);
	die();
}
?>
<?php if(isset($_GET['unavail'])) { ?>
	 <table width="100%" align="center" bgcolor="#FF0000" cellpadding="6" cellspacing="3" border="0">
		<tbody><tr>
			<td align="center" bgcolor="#FFFFFF"><span class="error">The video you have requested is not available.<br>
<br>
If you have recently uploaded this video, you may need to wait a few minutes for the video to process.</span></td>
		</tr>
	</tbody></table><br>
<?php } ?>
<?php if(isset($error)) { ?>
	 <table width="100%" align="center" bgcolor="#FF0000" cellpadding="6" cellspacing="3" border="0">
		<tbody><tr>
			<td align="center" bgcolor="#FFFFFF"><span class="error"><?= $error ?><br>
		</tr>
	</tbody></table><br>
<?php } ?>
<div id="searchbox">
  <!-- ... -->
</div>
<script>
document.getElementById("searchbox").focus();
</script>
<?php
$tags_strings = $conn->query("SELECT tags FROM videos WHERE converted = 1 ORDER BY uploaded_at DESC LIMIT 30");
$tag_list = [];
foreach($tags_strings as $result) $tag_list = array_merge($tag_list, explode(" ", $result['tags']));
$tag_list = array_slice(array_count_values($tag_list), 0, 50);
?>
<table width="790" align="center" cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<td style="padding-right: 15px;">
					<table class="roundedTable" width="595" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#EFEFEF">
			<tr>
				<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
				<td width="100%"><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
				<td width="585">
									<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr valign="top">
					<td width="33%" style="border-right: 1px dashed #369; padding: 0px 10px 10px 10px; color: #444;">
					<div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;"><a href="browse.php">Watch</a></div>
					Instantly find and watch lots fast streaming videos.
					</td>
					<td width="33%" style="border-right: 1px dashed #369; padding: 0px 10px 10px 10px; color: #444;">
					<div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;"><a href="my_videos_upload.php">Upload</a></div>
					Quickly upload and tag videos in almost any video format.
					</td>
					<td width="33%" style="padding: 0px 10px 10px 10px; color: #444;">
					<div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;"><a href="my_friends_invite.php">Share</a></div>
					Easily share your videos with family, friends, or co-workers.
					</td>
					</tr>
				</table>

				</td>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
			</tr>
			<tr>
				<td valign="bottom"><img src="/img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="/img/pixel.gif" width="1" height="5"></td>
				<td valign="bottom"><img src="/img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</table>

<?php
$views = $conn->query("SELECT * FROM views LEFT JOIN videos ON videos.video_id = views.video_id  ORDER BY views.viewed_at DESC LIMIT 4");
$vista = $conn->prepare("SELECT * FROM views ORDER BY views.viewed_at DESC LIMIT 4");
?>	
<!-- begin recently viewed -->
	<div style="padding-left: 10px; padding-right: 10px;">
	<table width="571" height="28" cellpadding="0" cellspacing="0" border="0" background="http://web.archive.org/web/2005im_/https://youtube.com/img/MediumGenericTab.jpg">
		<tbody><tr>
			<td width="380px">
				<span style="padding-left: 5px; font-size: 13px; color: #6D6D6D; font-weight: bold; padding-right: 5px;">Recently Viewed</span>
				<span style="font-size: 10px; color: #999999;"><span id="counter_recently_viewed">[1 - 4 of 12]</span>
			</span></td>
			<td align="left">		
					<span style="font-size: 13px; color: #6D6D6D;"><span></span>
			</span></td>	
			<td align="right">	
				<span style="padding-right: 10px; padding-left: 10px;"><img src="http://web.archive.org/web/2005im_/https://youtube.com/img/icon_todo.gif" border="0" width="23" height="14" style="padding-right: 5px; vertical-align: middle;">
						<a href="/browse.php?s=mp">More Recently Viewed..</a>
				</span>
			</td>
		</tr>
	</tbody></table>
	</div>

	
		<div style="padding-left: 1px;">					
		<table width="21" height="121" cellpadding="0" cellspacing="0">
			<tbody><tr>
				<td><img src="http://web.archive.org/web/2005im_/https://youtube.com/img/LeftTableArrowWhite.jpg" onclick="shiftLeft('recently_viewed')" width="21" height="121" border="0"></td>
				<td>
					<table width="548" height="121" style="background-color: #FFFFFF; border-bottom: 1px solid #CCCCCC;" cellpadding="0" cellspacing="0">
						<tbody><tr>
							<td>
                            <?php foreach($views as $video) { ?>
							<div class="videobarthumbnail_block" id="div_recently_viewed_0">
								<center>
									<div><a id="href_recently_viewed_0" href="/watch.php?v=<?php echo $video['video_id']; ?>"><img class="videobarthumbnail_gray" id="img_recently_viewed_0" src="get_still.php?video_id=<?php echo $video['video_id']; ?>" width="80" height="60"></a></div>
									<div id="title1_recently_viewed_0" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-color: #666666; padding-bottom: 3px;"><a href="/watch.php?v=<?php echo $video['video_id']; ?>" title="WOW meets Porn"><?php echo $video['title']; ?></a></div>
									<div id="title2_recently_viewed_0" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-color: #666666; padding-bottom: 3px;"><?php echo str_replace("ago", "ago", timeAgo($vistas['viewed_at'])); ?></div> 
								</center>
                                
							</div>
                            <?php } ?>
				</td>
							<div class="videobarthumbnail_block" id="div_recently_viewed_3_alternate" style="display: none">
								<center>
									<div><img src="/img/pixel.gif" width="80" height="60"></div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-color: #666666; padding-bottom: 3px;">&nbsp;</div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							</td>
						</tr>
					</tbody></table>
				</td>
				<td><img src="http://web.archive.org/web/2005im_/https://youtube.com/img/RightTableArrowWhite.jpg" onclick="shiftRight('recently_viewed')" width="21" height="121" border="0"></td>
			</tr>
		</tbody></table>
		</div>
		<!-- end recently viewed -->
        <br>
		<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#CCCCCC">
			<tr>
				<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
				<td width="100%"><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
				<td>
				<div class="moduleTitleBar">
				<div class="moduleTitle"><div style="float: right; padding: 1px 5px 0px 0px; font-size: 12px;"><a href="browse.php">See More Videos</a></div>
				Today's Featured Videos
				</div>
				</div>
		
				<?php
$featured_videos = $conn->query("SELECT * FROM featured LEFT JOIN videos ON videos.video_id = featured.video_id LEFT JOIN members ON members.member_id = videos.member_id WHERE videos.converted = 1 ORDER BY featured.feature_id DESC LIMIT 5");
?>
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr valign="top">
					</tr>
				</table>
				</div>
				
				<?php foreach($featured_videos as $video) { ?>
				<?php
				$video['views'] = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE video_id = ?");
				$video['views']->execute([$video['video_id']]);
				$video['views'] = $video['views']->fetchColumn();
						
				$video['comments'] = $conn->prepare("SELECT COUNT(comment_id) FROM comments WHERE video_id = ?");
				$video['comments']->execute([$video['video_id']]);
				$video['comments'] = $video['comments']->fetchColumn();
				?>
				<div class="moduleEntry"> 
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr valign="top">
							<td>
							<table cellpadding="0" cellspacing="0" border="0">
								<tr>
									<a href="index.php?v=<?php echo $video['video_id']; ?>"><img src="get_still.php?still_id=2&video_id=<?php echo $video['video_id']; ?>" class="moduleFeaturedThumb" width="120" height="90"></a>
								</tr>
							</table>
							
							</td>
							<td width="100%"><div class="moduleEntryTitle"><a href="watch.php?v=<?php echo $video['video_id']; ?>&search=<?php echo urlencode($_GET['search']); ?>"><?php echo htmlspecialchars($video['title']); ?></a></div>
							<div class="moduleEntryDescription"><?php echo htmlspecialchars($video['description']); ?></div>
							<div class="moduleEntryTags">Tags // <?php foreach(explode(" ", $video['tags']) as $tag) echo '<a href="results.php?search='.htmlspecialchars($tag).'">'.htmlspecialchars($tag).'</a> : '; ?></div>
							<div class="moduleEntryDetails">Added: <?php echo str_replace("ago", "ago", timeAgo($video['uploaded_at'])); ?> by <a href="profile.php?user=<?php echo htmlspecialchars($video['username']); ?>"><?php echo htmlspecialchars($video['username']); ?></a></div>
							<div class="moduleEntryDetails"> Runtime: <?php echo gmdate("i:s", $video['duration']); ?> | Views: <?php echo $video['views']; ?> | Comments: <?php echo $video['comments']; ?></div>
		<br>
							</td>
						</tr>
					</table>
				</div>
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
		</td>
		
		<td width="180">
		<!--
		<table class="roundedTable" width="180" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffeebb">
			<tbody><tr>
				<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
				<td width="100%"><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
				<td width="170">
									<div style="font-size: 16px; font-weight: bold; text-align: center; padding: 5px 5px 10px 5px;">
					<?php if(isset($session)) { ?>
								
				<div style="font-size: 16px; font-weight: bold; text-align: center; padding: 5px 5px 10px 5px;"><a href="my_friends_invite.php">Invite your friends to join YoreTude!</a></div>
				 <?php } else { ?>
				<div style="font-size: 16px; font-weight: bold; text-align: center; padding: 5px 5px 10px 5px;"><a href="signup.php">Sign up for your free account!</a></div>	
					<?php } ?>
					
					<div style="font-size: 11px; font-weight: 400; padding: 5px 5px 10px 5px; margin-top: 10px; background-color:#FFFFCC;"><a href="nano_contest.php"><strong>Nano a Day Giveaway Extended!</strong></a>

<div style="text-align: left;">
<br><br><img src="https://web.archive.org/web/20051216110110im_/http://www.youtube.com/img/nano_sm.jpg" width="40" height="88" align="right" hspace="5">We're giving away a 4GB iPod Classic <strong>every week through the end of the year</strong>!  Increase your chances of winning by:


<ul>
	<li><a href="signup.php">Signing Up</a></li>
	<li><a href="signup_login.php?next=my_friends_invite.php">Inviting Your friends</a></li>
	<li><a href="signup_login.php?next=my_videos_upload.php">Uploading Videos</a></li>
</ul>

<div style="text-align: center;">View full <a href="_contest.php">contest details</a>.</div>
</div></div>


<span style="font-size: 13px; padding-top: 4px;"><a href="classic_contest_winners.php">See all classic winners!</a></span>
</div>

				
				

				</td>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
			</tr>
			<tr>
				<td valign="bottom"><img src="/img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="/img/pixel.gif" width="1" height="5"></td>
				<td valign="bottom"><img src="/img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</tbody></table>-->
		<table class="roundedTable" width="180" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#cccccc">
			<tbody><tr>
				<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
				<td width="100%"><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
				<td width="170">
							<div style="font-size: 16px; font-weight: bold; text-align: center; padding: 5px 5px 10px 5px;">
		<div style="font-size: 13px; font-weight: bold; text-align: center; color: #444444; padding-bottom: 5px;"><img src="https://web.archive.org/web/20051216110110im_/http://www.youtube.com/img/gray_arrow.gif" width="14" height="14" broder="0" align="absmiddle">&nbsp;Explore New Features</div>
		<table style="background-color: #EAEAEA;" width="100%">
			<tbody><tr>
				<td style="text-align: center;">
				<div style="padding-top: 5px;"><a href="/channels">Channels</a></div>
				<div style="font-size: 11px; font-weight: normal; padding-bottom: 8px;">Find the videos you want.</div>
				<div style="padding-top: 5px;"><a href="/signup_login.php?next=subscription_center.php">Subscriptions</a></div>
				<div style="font-size: 11px; font-weight: normal; padding-bottom: 12px;">Subscribe to videos from your favorite users</div>		
				</td>
			</tr>
		</tbody></table>
		</div>

				</td>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
			</tr>
			<tr>
				<td valign="bottom"><img src="/img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="/img/pixel.gif" width="1" height="5"></td>
				<td valign="bottom"><img src="/img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</tbody></table>
		
		<div style="margin-top: 10px;">
		<table width="180" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFCC99">
			<tr>
				<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
				<td><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
				<td width="170" style="padding: 5px; text-align: center;">
				<div style="font-weight: bold; font-size: 13px;">April Video Contest!</div>
				
				<a href="/watch.php?v=mEysvUf4LXK&search=backtoschool"><img src="/get_still.php?video_id=mEysvUf4LXK" width="90" height="60" style="border: 5px solid #FFFFFF; margin-top: 10px;"></a>
			<div style="font-size: 16px; font-weight: bold; padding-top: 5px;"><a href="monthly_contest.php">Pranks!</a></div>
				<br>

				<?php if(isset($session)) { ?>
		                <div style="font-size: 12px; font-weight: bold; margin-bottom: 7px;"><a href="my_videos_upload.php">Learn more</a></div>
				<?php } else { ?>				
				<div style="font-size: 12px; font-weight: bold; margin-bottom: 7px;"><a href="/my_videos_upload">Join the contest now!</a></div>
				<?php } ?>
				<!--<div style="font-size: 10px; font-weight: normal; margin-bottom: 3px;"><a href="monthly_contest_august_2005.php">August winners!</a></div>				
				</td>-->
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
			</tr>
			<tr>
				<td><img src="/img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</table>
		</div>
		
		<div style="margin: 10px 0px 5px 0px; font-size: 12px; font-weight: bold; color: #333;">Recent Tags:</div>
		<div style="font-size: 13px; color: #333333;">
		
				<?php foreach($tag_list as $tag => $frequency	) {
					echo '<a style="font-size: 13px;" href="results.php?search='.htmlspecialchars($tag).'">'.htmlspecialchars($tag).'</a> :'."\r\n";
				} ?>
		
					
		<div style="font-size: 14px; font-weight: bold; margin-top: 10px;"><a href="tags.php">See More Tags</a></div>
		
		</div>
	<!--
	<?php
		require __DIR__ . "/includes/whos_on_now.php";
	?>-->

		</div>
		</td>
	</tr>
</table>

<?php
require __DIR__ . "/includes/footer.php";
?>
