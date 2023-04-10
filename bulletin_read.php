<?php
require __DIR__ . "/includes/header.php";


$bulletin_info = $conn->prepare("SELECT * FROM bulletins WHERE bulletin_id = ?");
$bulletin_info->execute([$_GET['id']]);

if($bulletin_info->rowCount() == 0) {
	header("Location: /?error=1");
	die();
} else {
	$bulletin_info = $bulletin_info->fetch(PDO::FETCH_ASSOC);
}


$creator_info = $conn->prepare("SELECT * FROM members WHERE member_id = ?");
$creator_info->execute([$bulletin_info['member_id']]);
$creator_info = $creator_info->fetch(PDO::FETCH_ASSOC);
					
$comments = $conn->prepare("SELECT * FROM b_comments LEFT JOIN members ON members.member_id = b_comments.member_id WHERE bulletin_id = ? ORDER BY posted_at DESC");
$comments->execute([$bulletin_info['bulletin_id']]);
?>
<iframe id="invisible" name="invisible" src="" scrolling="yes" width="0" height="0" frameborder="0" marginheight="0" marginwidth="0"></iframe>   
<script type="text/javascript" src="swfobject.js"></script>
<script>
function CheckLogin()
{
	<?php if(!isset($session)) { ?>
		alert("You must be logged in to to perform this action!");
		return false;
	<?php } ?>
		
	return true;
}

function CommentHandler()
{

	var comment = document.comment_form.comment;
	var comment_button = document.comment_form.comment_button;

	if (comment.value.length == 0 || comment.value == null)
	{
		alert("You must enter a comment!");
		comment.focus();
		return false;
	}

	if (comment.value.length > 500)
	{
		alert("Your comment must be shorter than 500 characters!");
		comment.focus();
		return false;
	}

	comment_button.disabled='true';
	comment_button.value='Thanks for your comment!';

	return true;
}
</script>
<style type="text/css">
	.userTable {
	border: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	background-color: <?php echo htmlspecialchars($creator_info['boxFill']); ?>;
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
	border-bottom: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
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
	color: <?php echo htmlspecialchars($creator_info['profileTitles']); ?>; 
	padding-top: 4px;
	padding-bottom: 4px;
	}
	
	.profileHeaders {
	background-color: <?php echo htmlspecialchars($creator_info['profileHeaders']); ?>;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	font-weight: bold;
	color: #FFFFFF;
	padding-bottom: 3px;
	padding-top: 3px;
	}
	
	.aboutTable {
	width: 560px;
	border: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	}
	
	.aboutImg {
	width: 140px;
	height: 108px;
	border: 2px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	}
	
	.bordersImg {
	border: 2px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	}
	
	.commentPostTable {
	width: 560px;
	border: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	border: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	}
	
	
	.videoPostTable {
	width: 560px;
	border: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	padding-left: 15px;
	}
	
	.videoPostImg {
	width: 154px;
	height: 124px;
	border: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	}
	
	
	.connectTable {
	border: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;	
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
	border: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	}
	
	tr.bulletin td {
	background-color: <?php echo htmlspecialchars($creator_info['boxFill']); ?>;
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
	border-bottom: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	}
	
	tr.bulletinPost td {
	padding-top: 5px;
	padding-bottom: 5px;
	border-top: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	}
	
	tr.commentsMsg td {
	padding-top: 5px;
	padding-bottom: 5px;
	border-top: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	background-color: <?php echo htmlspecialchars($creator_info['boxFill']); ?>;
	}
	
	td.buttonPost {
	padding-top: 4px;
	padding-bottom: 4px;
	border-top: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	}
	
	td.bulletinTopFirstCells {
	border-right: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	border-bottom: none;
	}
	
	td.checkBoxSection {
	background-color: <?php echo htmlspecialchars($creator_info['boxFill']); ?>;
	width: 15px;
	border-right: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	}
		
	
	.bulletinSmallImg {
	padding-left: 5px;
	}
	
	.commentsImg {
	width: 60px;
	border: 2px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	}
	
	tr.comments td{
	border-bottom: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	padding-top: 3px;
	padding-bottom: 5px;
	}
	
	
	.bulletinReadTable {
	width: 560px;
	border: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	}
	
	td.bulletinRead {
	background-color: <?php echo htmlspecialchars($creator_info['boxFill']); ?>;
	border-right: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	border-bottom: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	}
	
	td.bulletinReadBottom {
	background-color: <?php echo htmlspecialchars($creator_info['boxFill']); ?>;
	border-right: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	}
	
	
	td.bulletinReadLast {
	background-color: <?php echo htmlspecialchars($creator_info['boxFill']); ?>;
	border-bottom: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	}
	
	
	tr.emptyBulletin td{
	background-color: <?php echo htmlspecialchars($creator_info['boxFill']); ?>;
	padding-top: 3px;
	padding-bottom: 5px;
	}
	
	
	td.leftBg {
	background-color: <?php echo htmlspecialchars($creator_info['boxFill']); ?>;
	border-right: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	}
	

	a.edit:link {color: white; text-decoration: underline; }
	a.edit:visited {color: white; text-decoration: underline; }
	a.edit:hover {color: white; text-decoration: underline; }
	a.edit:active {color: white; text-decoration: underline; } 


	
	td.bulletinReadRight {
	border-bottom: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	}
	
	
	td.bulletinReadRightBottom {
	border-bottom: none;
	}
	
	td.bulletinReadBottom {
	background-color: <?php echo htmlspecialchars($creator_info['boxFill']); ?>;
	border-right: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	border-bottom: none;
	}
	
	tr.bulletinCols td {
	border-bottom: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	padding-top: 3px;
	padding-bottom: 5px;
	}
	
	td.bulletinData {
	border-right: 1px solid <?php echo htmlspecialchars($creator_info['aboutTable']); ?>;
	padding-bottom: 5px;
	padding-right: 3px;
	padding-left: 3px;
	}

</style>	
<table width="875" cellpadding="0" cellspacing="0">	
	<tbody><tr>
		<td valign="top">
			<div>&nbsp;</div>
			
			<table class="bulletinReadTable" cellpadding="0" cellspacing="0" align="center">
				<tbody><tr class="profileHeaders">
				  <td colspan="2">&nbsp;&nbsp;Bulletin Post </td>
				</tr>
				
				<tr class="rows">
				  <td class="bulletinRead" valign="top"><div align="center"><span class="profileTitles">From:</span></div></td>
				  <td class="bulletinReadRight"><span class="profileTitles"><a href="/profile?user=<?php echo htmlspecialchars($creator_info['username']); ?>"><?php echo htmlspecialchars($creator_info['username']); ?></a><br>
				    <br>
				</span></td>
			  </tr>
				<tr class="rows">
				  <td class="bulletinRead" valign="top"><div align="center"><span class="profileTitles">Date:</span></div></td>
				  <td class="bulletinReadRight"><span class="profileTitles"><?php echo date("F j, Y, h:i a", strtotime($bulletin_info ['posted_at']. ' - 17 years')); ?></span></td>
			  </tr>
				<tr class="rows">
				  <td class="bulletinRead" valign="top"><div align="center"><span class="profileTitles">Subject:</span></div></td>
				  <td class="bulletinReadRight"><span class="profileTitles"><?php echo htmlspecialchars($bulletin_info['subject']); ?></span></td>
			  </tr>
				<tr class="rows">
				  <td width="111" class="bulletinReadBottom" valign="top"><div align="center"><span class="profileTitles">Body:</span></div></td>
				  <td width="447" valign="top">
					<div style="overflow: flow; width: 447px">
						<?php echo htmlspecialchars($bulletin_info['body']); ?>
					</div>
						
				  </td>
				</tr>				
			</tbody></table>
			
			<br>
			<center>
				<input type="button" value="Reply To User" onclick="window.location.href='/bulletin_reply?to_user=sexxiebebe23&amp;b=ChTPHPKUm60'">
				<input type="button" value="Reply To Poster" onclick="window.location.href='/bulletin_reply?to_user=sexxiebebe23&amp;b=ChTPHPKUm60'">
			<?php
			if($comments !== false) {
				foreach($comments as $comment) {
					$comment_videos = $conn->prepare("SELECT COUNT(video_id) FROM videos WHERE member_id = ? AND converted = 1");
					$comment_videos->execute([$comment["member_id"]]);
					
					$comment_favorites = $conn->prepare("SELECT COUNT(favorite_id) FROM favorites WHERE member_id = ?");
					$comment_favorites->execute([$comment["member_id"]]);
					
					echo '<hr>'.htmlspecialchars($comment['body']).'<br>- <a href="profile.php?user='.htmlspecialchars($comment['username']).'">'.htmlspecialchars($comment['username']).'</a> // <a href="profile_videos.php?user='.htmlspecialchars($comment['username']).'">Videos</a> ('.$comment_videos->fetchColumn().') | <a href="profile_favorites.php?user='.htmlspecialchars($comment['username']).'">Favorites</a> ('.$comment_favorites->fetchColumn().') - ('.timeAgo($comment['posted_at']).')';
				}
			}
			?>
			</center>
			<div>&nbsp;</div>		
			</td>
    </tr>
</tbody></table>

<?php 
require __DIR__ . "/includes/footer.php";
?>
