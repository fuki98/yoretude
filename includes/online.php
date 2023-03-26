			<?php
    require __DIR__ . "/functions.php";
    $users = "SELECT * FROM `members`  \n"

    . "ORDER BY `members`.`last_login` DESC LIMIT 4;";
?>
<!DOCTYPE html>
	<link href="http://yoretude.com/styles.css" rel="stylesheet" type="text/css">
            <table width="180" cellspacing="0" cellpadding="0" border="0" bgcolor="#EEEEDD" align="center">
			<tbody><tr>
				<td><img src="./img/box_login_tl.gif" width="5" height="5"></td>
				<td><img src="./img/pixel.gif" width="1" height="5"></td>
				<td><img src="./img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="./img/pixel.gif" width="5" height="1"></td>
				<td width="170">

				<div style="padding: 2px 5px 10px 5px;">
				<div style="font-size: 14px; font-weight: bold; margin-bottom: 8px; color: #666633;">Last 4 users online... </div>
				<?php foreach($users as $profile) { 
                    $profile = $conn->prepare("SELECT * FROM members WHERE members.username = ?");
$profile->execute([$_GET['user']]);
                    $profile['videos'] = $conn->prepare("SELECT video_id FROM videos WHERE member_id = ? AND converted = 1");
$profile['videos']->execute([$profile["member_id"]]);
$profile['videos'] = $profile['videos']->rowCount();
$profile['favorites'] = $conn->prepare("SELECT favorite_id FROM favorites WHERE member_id = ?");
$profile['favorites']->execute([$profile["member_id"]]);
$profile['favorites'] = $profile['favorites']->rowCount();
?>
                
					<div style="font-size: 12px; font-weight: bold; margin-bottom: 5px;"><a href="profile.php?user=<?php echo htmlspecialchars($profile['username']); ?>"><?php echo htmlspecialchars($profile['username']); ?></a></div>

					<div style="font-size: 12px; margin-bottom: 8px; padding-bottom: 10px; border-bottom: 1px dashed #CCCC66;"><a href="profile_videos.php?user=<?php echo htmlspecialchars($profile['username']); ?>"><img src="./img/icon_vid.gif" alt="Videos" style="vertical-align: text-bottom; padding-left: 2px; padding-right: 1px;" width="14" height="14" border="0"></a> (<a href="profile_videos.php?user=meesh">0</a>)
					 | <a href="profile_favorites.php?user=meesh"><img src="./img/icon_fav.gif" alt="Favorites" style="vertical-align: text-bottom; padding-left: 2px; padding-right: 1px;" width="14" height="14" border="0"></a> (<a href="profile_favorites.php?user=<?php echo htmlspecialchars($profile['username']); ?>">0</a>)
					 | <a href="profile_friends.php?user=meesh"><img src="./img/icon_friends.gif" alt="Friends" style="vertical-align: text-bottom; padding-left: 2px; padding-right: 1px;" width="14" height="14" border="0"></a> (<a href="profile_friends.php?user=<?php echo htmlspecialchars($profile['username']); ?>"><?php echo htmlspecialchars($profile['username']); ?></a>)</div>

<?php } ?>
				
									
				<div style="font-weight: bold; margin-bottom: 5px;">Icon Key:</div>
				<div style="margin-bottom: 4px;"><img src="./img/icon_vid.gif" alt="Videos" style="vertical-align: text-bottom; padding-left: 2px; padding-right: 1px;" width="14" height="14" border="0"> - Videos</div>
				<div style="margin-bottom: 4px;"><img src="./img/icon_fav.gif" alt="Favorites" style="vertical-align: text-bottom; padding-left: 2px; padding-right: 1px;" width="14" height="14" border="0"> - Favorites</div>
				<img src="./img/icon_friends.gif" alt="Friends" style="vertical-align: text-bottom; padding-left: 2px; padding-right: 1px;" width="14" height="14" border="0"> - Friends
				
				</div>

				</td>
				<td><img src="./img/pixel.gif" width="5" height="1"></td>
			</tr>
			<tr>
				<td><img src="./img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="./img/pixel.gif" width="1" height="5"></td>
				<td><img src="./img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</tbody></table>
        </html>