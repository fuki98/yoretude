<?php
require __DIR__ . "/includes/header.php";

if(!isset($_GET['user'])) require __DIR__ . "/index_down.php";

if(!isset($_GET['vanity'])) {
	$profile = $conn->prepare("SELECT * FROM members WHERE members.username = ?");
	$profile->execute([$_GET['user']]);
}else{
	$profile = $conn->prepare("SELECT * FROM members WHERE members.username = ?");
	$profile->execute([$_GET['vanity']]);
}

if($profile->rowCount() == 0) {
	header("Location: /");
} else {
	$profile = $profile->fetch(PDO::FETCH_ASSOC);
}

$profile['videos'] = $conn->prepare("SELECT video_id FROM videos WHERE member_id = ? AND converted = 1");
$profile['videos']->execute([$profile["member_id"]]);
$profile['videos'] = $profile['videos']->rowCount();
$profile['favorites'] = $conn->prepare("SELECT favorite_id FROM favorites WHERE member_id = ?");
$profile['favorites']->execute([$profile["member_id"]]);
$profile['favorites'] = $profile['favorites']->rowCount();
$profile['url'] = ucfirst(strtolower($profile['username']));

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
		<td><strong>Profile</strong></td>
		<td style="padding: 0px 5px 0px 5px;">|</td>
		<td><a href="profile_videos.php?user=<?php echo htmlspecialchars($profile["username"]); ?>">Public Videos</a> (<?php echo $profile['videos']; ?>)</td>
		<td style="padding: 0px 5px 0px 5px;">|</td>
		<td><a href="profile_videos_private.php?user=<?php echo htmlspecialchars($profile["username"]); ?>">Private Videos</a> (0)</td>
		<td style="padding: 0px 5px 0px 5px;">|</td>
		<td><a href="profile_favorites.php?user=<?php echo htmlspecialchars($profile["username"]); ?>">Favorites</a> (<?php echo $profile['favorites']; ?>)</td>
		<td style="padding: 0px 5px 0px 5px;">|</td>
		<td><a href="profile_friends.php?user=<?php echo htmlspecialchars($profile["username"]); ?>">Friends</a> (0)</td>
	</tr>
</table>
</div><br><br>
<div>
<table width="875" cellpadding="0" cellspacing="0">	
	<tbody><tr>
		<td width="300" valign="top">
			<table class="userTable" cellpadding="0" cellspacing="0">
				<tbody><tr class="profileHeaders">
					
					<td colspan="2">
						<div style="float: left; padding-top: 2px; padding-bottom: 2px; padding-left: 5px; padding-right: 5px">Hello. I'm <?php echo htmlspecialchars($profile["username"]); ?></div>
	<div style="float: right; padding-right: 5px">


					
					</div></td>
				</tr>												
				<tr class="rows">	
					
				
					<td width="142" align="left">
						<a href="/<?= $profile['url']  ?>"><img src="/get_still.php?video_id=<?php echo htmlspecialchars($profile['channelicon']); ?>" class="aboutImg"></a>
					</td>
					
					<td width="144" align="left">
                    <?php if(!empty($profile['birthday'])) { ?>
						<span class="profileTitles">Age: </span> <?php echo str_replace("ago", "old", timeAgo($profile['birthday'])); ?>
						<br>
                        <?php } ?>
                        <?php if(!empty($profile['gender']) && $profile['gender'] !== 0) { ?>
						<span class="profileTitles">Gender: </span> <?php
					switch($profile['gender']) {
						case '0':
							break;
						case '1':
							echo "Male";
							break;
						case '2':
							echo "Female";
							break;
					}
				?>
						<br>
                         <?php } ?>
                         <?php if(!empty($profile['current_city'])) { ?>
							<?php echo htmlspecialchars($profile['current_city']); ?>
							<br>
                             <?php } ?>
                             <?php if(!empty($profile['current_country'])) { ?>
							<?php echo htmlspecialchars($profile['current_country']); ?>
							<br>
						<?php } ?>
					</td>
			
				</tr>
			
				<tr class="rows">
					<td colspan="3">
						<span class="profileTitles">Last Login: </span><?php echo str_replace("ago", "ago", timeAgo($profile['last_login'])); ?>
						<br>
						<span class="profileTitles">Signed Up: </span><?php echo str_replace("ago", "ago", timeAgo($profile['created_at'])); ?>
						<br>
						<span class="profileTitles">URL: </span> <a href="/<?= $profile['url']  ?>">http://www.yoretude.com/user/<?= $profile['url']  ?></a>
					</td>
				</tr>
			</tbody></table>
		
			<div>&nbsp;</div>
			
						
			
			<table class="connectTable" cellpadding="0" cellspacing="0">
				<tbody><tr class="profileHeaders">
					<td colspan="5">&nbsp;&nbsp;Connect with <?php echo htmlspecialchars($profile["username"]); ?></td>
				</tr>
				<tr class="connectRowsTop">
					<td width="5">&nbsp;</td>
					<td width="21" valign="middle"><img src="/img/SendMessage.gif"></td>
					<td><span class="connectLinks"><a href="/outbox?to_user=<?php echo htmlspecialchars($profile["username"]); ?>">Send Message</a></span></td>
					<td width="21" valign="middle"><img src="/img/AddToFriends.gif"></td>
					<td><span class="connectLinks"><a href="/my_friends_invite_user?friend_id=XU6rohWMEXU">Add to Friends</a></span></td>			
				</tr>
				<tr class="connectRows">
					<td width="5">&nbsp;</td>
					<td width="21" valign="middle"><img src="/img/AddComment.gif" class="connectImages"></td>
					<td><span class="connectLinks"><a href="/profile_comment_post?user=<?php echo htmlspecialchars($profile["username"]); ?>">Add Comment</a></span></td>
					<td width="21" valign="middle"><img src="/img/FwdMember.gif"></td>
					<td><span class="connectLinks"><a href="javascript:share_profile()">Forward Member</a></span></td>
				</tr>	
				<tr class="connectRowsBottom">
					<td width="5">&nbsp;</td>
					<td width="21" valign="middle"></td>
					<td><span class="connectLinks"></span></td>
					<td width="21" valign="middle"><img src="/img/MiniSubscribe.gif"></td>
					<td><span class="connectLinks"><a href="/subscription_center?add_user=<?php echo htmlspecialchars($profile["username"]); ?>">Subscribe</a></span></td>
				</tr>
					
			</tbody></table>
			
			<div>&nbsp;</div>
			
			<table class="bulletinTable" cellpadding="0" cellspacing="0">
				<tbody><tr class="profileHeaders">
					<td colspan="3">	<div style="float: left; padding-top: 2px; padding-bottom: 2px; padding-left: 5px; padding-right: 5px">My Bulletin Board</div>
	<div style="float: right; padding-right: 5px">

</div></td>
				</tr>
				<tr class="bulletinTitle">
					<td align="center" class="bulletinTopFirstCells" valign="top"><span class="profileTitles">From</span></td>
					<td align="center" class="bulletinTopFirstCells" valign="top"><span class="profileTitles">Date</span></td>
					<td align="center" valign="top"><span class="profileTitles">Bulletin</span></td>
				</tr>
				<!--Begin only show this row if no postings-->
				<tr class="emptyBulletin">
					<td colspan="3" align="center"><span class="profileTitles">There are no bulletins.</span></td>
				</tr>
				<!--End only show this row if no postings-->
				
			</tbody></table>
			
		
		</td>
		
	  <td width="15" valign="top"></td>
	  <td width="560" valign="top">
			<table class="aboutTable" cellpadding="0" cellspacing="0">
				<tbody><tr class="profileHeaders">
					<td>	<div style="float: left; padding-top: 2px; padding-bottom: 2px; padding-left: 5px; padding-right: 5px">More About Me</div>
	<div style="float: right; padding-right: 5px">

</div></td> 
				</tr>
				
				<tr class="rows">
					<td>
					<?php if(!empty($profile['name'])) { ?>
						<div class="spaceMaker">		<div class="spaceMaker">
		<span class="profileTitles">Name: </span> <?php if(!empty($profile['name'])) { ?> 
		<br>
		</div>
        <?php } ?>	

</div>
					
							<div class="spaceMaker">
		<span class="profileTitles">Videos Watched: </span> 151
		<br>
		</div>


							<div class="spaceMaker">
		<span class="profileTitles">Profile Viewed: </span> 66
		<br>
		</div>


							<div class="spaceMaker">
		<span class="profileTitles">Last Login: </span> <?php echo str_replace("ago", "ago", timeAgo($profile['last_login'])); ?>
		<br>
		</div>


							<div class="spaceMaker">
		<span class="profileTitles">Member Since: </span> <?php echo str_replace("ago", "ago", timeAgo($profile['created_at'])); ?>
		<br>
		</div>
        <?php if(!empty($profile['birthday'])) { ?>
							<div class="spaceMaker">
		<span class="profileTitles">Age: </span> <?php echo str_replace("ago", "old", timeAgo($profile['birthday'])); ?>	
		<br>
		</div>
<?php } ?>	
    <?php if(!empty($profile['personal_website'])) { ?>
							<div class="spaceMaker">
		<span class="profileTitles">Personal Website: </span> <?php echo htmlspecialchars($profile['personal_website']); ?>	
		<br>
		</div>
<?php } ?>	
 <?php if(!empty($profile['about_me'])) { ?>
							<div class="spaceMaker">
		<span class="profileTitles">About Me: </span> <?php echo htmlspecialchars($profile['about_me']); ?>	
		<br>
		</div>
<?php } ?>	
<?php if(!empty($profile['relationship_status']) && $profile['relationship_status'] !== 0) { ?>
							<div class="spaceMaker">
		<span class="profileTitles">Marital Status: </span> 	<?php
					switch($profile['gender']) {
						case '0':
							break;
						case '1':
							echo "Single";
							break;
						case '2':
							echo "Taken";
							break;
					}
				?>
                <?php } ?>
		<br>
		</div>
<?php } ?>		
<?php if(!empty($profile['schools'])) { ?>
							<div class="spaceMaker">
		<span class="profileTitles">Schools: </span> <?php echo htmlspecialchars($profile['schools']); ?>
		<br>
		</div>
<?php } ?>	
<?php if(!empty($profile['occupations'])) { ?>
							<div class="spaceMaker">
		<span class="profileTitles">Occupation: </span> <?php echo htmlspecialchars($profile['occupations']); ?>
		<br>
		</div>
<?php } ?>
<?php if(!empty($profile['companies'])) { ?>
							<div class="spaceMaker">
		<span class="profileTitles">Companies: </span> <?php echo htmlspecialchars($profile['companies']); ?>
		<br>
		</div>
 <?php } ?>
<?php if(!empty($profile['hometown'])) { ?>
							<div class="spaceMaker">
		<span class="profileTitles">Hometown: </span> <?php echo htmlspecialchars($profile['hometown']); ?>
		<br>
		</div>
<?php } ?>
<?php if(!empty($profile['current_city'])) { ?>
							<div class="spaceMaker">
		<span class="profileTitles">Current City: </span> <?php echo htmlspecialchars($profile['current_city']); ?>
		<br>
		</div>
<?php } ?>
<?php if(!empty($profile['current_country'])) { ?>
							<div class="spaceMaker">
		<span class="profileTitles">Current Country: </span> <?php echo htmlspecialchars($profile['current_country']); ?>
		<br>
		</div>
<?php } ?>
<?php if(!empty($profile['interests_and_hobbies'])) { ?>
							<div class="spaceMaker">
		<span class="profileTitles">Hobbies &amp; Interests: </span> <?php echo htmlspecialchars($profile['interests_and_hobbies']); ?>
		<br>
		</div>
        <?php } ?>
<?php if(!empty($profile['favorite_movies_and_shows'])) { ?>
							<div class="spaceMaker">
		<span class="profileTitles">Favorite Movies &amp; Shows: </span> <?php echo htmlspecialchars($profile['favorite_movies_and_shows']); ?>
		<br>
		</div>

<?php } ?>	
				<?php if(!empty($profile['favorite_books'])) { ?>
							<div class="spaceMaker">
		<span class="profileTitles">Favorite Books: </span> <?php echo htmlspecialchars($profile['favorite_books']); ?>
		<br>
		</div>

<?php } ?>		
					<?php if(!empty($profile['favorite_music'])) { ?>
							<div class="spaceMaker">
		<span class="profileTitles">Favorite Music: </span> <?php echo htmlspecialchars($profile['favorite_music']); ?>
		<br>
		</div>

<?php } ?>	
					
					</td>
				</tr>				
			</tbody></table>
			
			<div>&nbsp;</div>
			
			<!--Begin Insert My Recent Videos Video Bar Here-->

				<table class="aboutTable" cellpadding="0" cellspacing="0">
					<tbody><tr class="profileHeaders">
					<td>
							<div style="float: left; padding-top: 2px; padding-bottom: 2px; padding-left: 5px; padding-right: 5px">My Recent Videos</div>
	<div style="float: right; padding-right: 5px"><a href="/profile_videos?user=<?php echo htmlspecialchars($profile["username"]); ?>" class="edit">See All Videos</a>

&nbsp;
					</div></td>
					</tr>
					<tr>
					<td align="center">
							
		<div style="padding-left: 1px;">					
		<table width="21" height="121" cellpadding="0" cellspacing="0">
			<tbody><tr>
				<td><img src="/img/LeftSingleArrowOff.gif" onclick="shiftLeft('profile_videos')" border="0"></td>
				<td>
					<table width="500" height="121" style="background-color: #FFFFFF; " cellpadding="0" cellspacing="0">
						<tbody><tr>
							<td style="border-bottom:none;">
                            <?php foreach($videos as $video) { ?>
							<div class="videobarthumbnail_block" id="div_profile_videos_0">
								<center>
									<div><a id="href_profile_videos_0" href="/watch?v=<?php echo htmlspecialchars($video['video_id']); ?>"><img class="videobarthumbnail_white" id="img_profile_videos_0" src="/get_still.php?video_id=<?php echo htmlspecialchars($video['video_id']); ?>" width="80" height="60"></a></div>
									<div id="title1_profile_videos_0" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-color: #666666; padding-bottom: 3px;"><a href="/watch?v=<?php echo htmlspecialchars($video['video_id']); ?>" title="<?php echo htmlspecialchars($video['title']); ?>"><?php echo htmlspecialchars($video['title']); ?></a></div>
									<div id="title2_profile_videos_0" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-color: #666666; padding-bottom: 3px;">1 month ago</div>
								</center>
							</div>
                            <?php } ?>

							</td>
						</tr>
					</tbody></table>
				</td>
				<td><img src="/img/RightSingleArrowOff.gif" onclick="shiftRight('profile_videos')" border="0"></td>
			</tr>
		</tbody></table>
		</div>


					</td>
					</tr>
				</tbody></table>

			<!--End Insert My Recent Videos Video Bar Here-->
			
			<div>&nbsp;</div>
			
			<!--Begin Insert My Friends Video Bar Here-->
			<table class="aboutTable" cellpadding="0" cellspacing="0">
				<tbody><tr class="profileHeaders">
				<td>
						<div style="float: left; padding-top: 2px; padding-bottom: 2px; padding-left: 5px; padding-right: 5px">My Friends</div>
	<div style="float: right; padding-right: 5px"><a href="/profile_friends?user=<?php echo htmlspecialchars($profile["username"]); ?>" class="edit">See All Friends</a>

&nbsp;					
				</div></td></tr>
				<tr>
				<td align="center">
						
		<div style="padding-left: 1px;">					
		<table width="21" height="121" cellpadding="0" cellspacing="0">
			<tbody><tr>
				<td><img src="/img/LeftSingleArrowOff.gif" onclick="shiftLeft('recent_friends')" border="0"></td>
				<td>
					<table width="500" height="121" style="background-color: #FFFFFF; " cellpadding="0" cellspacing="0">
						<tbody><tr>
							<td style="border-bottom:none;">
							<div class="videobarthumbnail_block" id="div_recent_friends_0">
								<center>
									<div><a id="href_recent_friends_0" href="/profile?user=Devro"><img class="videobarthumbnail_white" id="img_recent_friends_0" src="/get_still.php=?video_id=" width="80" height="60"></a></div>
									<div id="title1_recent_friends_0" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-color: #666666; padding-bottom: 3px;"><a href="/profile?user=Devro" title="Devro">Devro</a></div>
									<div id="title2_recent_friends_0" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-color: #666666; padding-bottom: 3px;"></div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_recent_friends_0_alternate" style="display: none">
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
				<td><img src="/img/RightSingleArrowOff.gif" onclick="shiftRight('recent_friends')" border="0"></td>
			</tr>
		</tbody></table>
		</div>


				</td>
				</tr>
			</tbody></table>

			<!--End Insert My Friends Video Bar Here-->
			<div>&nbsp;</div>
    
			<table class="commentPostTable" cellpadding="0" cellspacing="0">
				<tbody><tr class="profileHeaders">
					<td colspan="3">	<div style="float: left; padding-top: 2px; padding-bottom: 2px; padding-left: 5px; padding-right: 5px">My Comments</div>
	<div style="float: right; padding-right: 5px">

 </div></td>
				</tr>

				<tr class="commentsMsg">
						<td colspan="3" align="center"><span class="bulletinPost" style="padding-left: 5px; padding-right: 5px"><a href="/profile_comment_post?user=<?php echo htmlspecialchars($profile["username"]); ?>">Leave a comment</a> for <?php echo htmlspecialchars($profile["username"]); ?>. The comments you post will be visible to anyone who views <?php echo htmlspecialchars($profile["username"]); ?>'s profile. <br></span></td>
				</tr>

				

			</tbody></table>
			
		</td>
	</tr>
</tbody></table>
</div><?php
require __DIR__ . "/includes/footer.php";
?> 