<?php
require __DIR__ . "/includes/header.php";

if(isset($_GET['s']) && in_array($_GET['s'], ["mr", "mp", "md", "mf", "r", "rf"])) {
	$browse_sort = $_GET['s'];
} else {
	$browse_sort = "mr";
}

if(isset($_GET['t']) && in_array($_GET['t'], ["t", "w", "m", "a"])) {
	$time = $_GET['t'];
} else {
	$time = "t";
}

$users = $conn->query("SELECT * FROM members ORDER BY last_login DESC LIMIT 20");
?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#CCCCCC">
	<tr>
		<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
		<td width="100%"><img src="img/pixel.gif" width="1" height="5"></td>
		<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
	</tr>
	<tr>
		<td><img src="img/pixel.gif" width="5" height="1"></td>
		<td>
			<div class="moduleTitleBar">
				<div class="moduleTitle">
				<!--<?php if($browse_sort !== "mr" && $browse_sort !== "r") { ?>
					<div style="float: right; font-weight: normal; font-size: 11px; color: #444444; padding-right: 5px;">
						<?php echo ($time == "t") ? '<strong>Today</strong>' : '<a href="browse.php?s='.$browse_sort.'&t=t">Today</a>'; ?>
						|
						<?php echo ($time == "w") ? '<strong>This Week</strong>' : '<a href="browse.php?s='.$browse_sort.'&t=w">This Week</a>'; ?>
						|
						<?php echo ($time == "m") ? '<strong>This Month</strong>' : '<a href="browse.php?s='.$browse_sort.'&t=m">This Month</a>'; ?>
						|
						<?php echo ($time == "a") ? '<strong>All Time</strong>' : '<a href="browse.php?s='.$browse_sort.'&t=a">All Time</a>'; ?>
					</div>
				<?php } ?>-->
				<?php
				switch($browse_sort) {
					case 'mp':
						echo "Most Viewed";
						break;
					case 'md':
						echo "Most Discussed";
						break;
					case 'mf':
						echo "Most Added to Favorites";
						break;
					case 'r':
						echo "Random";
						break;
				         case 'rf':
						echo "Recently Featured";
						break;
					default:
						echo "Last users online";
				}
				?></div>
			</div>
			<div class="moduleFeatured"> 
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<?php
					$i = 0;
					foreach($users as $user) {
						$i = $i + 1;
						if($i == 1) {
							echo '<tr valign="top">';
						}
						
						//$user['subs'] = $conn->prepare("SELECT COUNT(id) FROM subs WHERE towho = ?");
						//$user['subs']->execute([$user['member_id']]);
						//$user['subs'] = $video['views']->fetchColumn();
						
						//$video['comments'] = $conn->prepare("SELECT COUNT(comment_id) FROM comments WHERE video_id = ?");
						//$video['comments']->execute([$video['video_id']]);
						//$video['comments'] = $video['comments']->fetchColumn();

                        $profile_latest_video = $conn->prepare(
                            "SELECT * FROM videos
                            LEFT JOIN members ON members.member_id = videos.member_id
                            WHERE videos.member_id = ? AND videos.converted = 1
                            GROUP BY videos.video_id
                            ORDER BY videos.uploaded_at DESC LIMIT 1"
                        );
                        $profile_latest_video->execute([$user['member_id']]);

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
                        $user['url'] = ucfirst(strtolower($user['username']));

                        if(empty($profile['channelicon']) || $profile['channelicon'] == "none" && $profile_latest_video !== false) {
                            $profile['channelicon'] = $profile_latest_video['video_id'];
                        }
                        if(!empty($profile['channelicon']) && strpos($profile['channelicon'], 'watch?v=') !== false) {
                            // Use parse_url() to get the query string from the URL
                            $queryString = parse_url($profile['channelicon'], PHP_URL_QUERY);
                        
                            // Use parse_str() to parse the query string into an array
                            parse_str($queryString, $queryParams);
                        
                            // Get the video ID from the array
                            $profile['channelicon'] = $queryParams['v'];
                        
                            //$profile['channelicon'] = substr($profile['channelicon'], -11);
                        }

 						echo '<td width="20%" align="center">'.
						'<a href="/'.$user['url'].'"><img src="get_still.php?video_id='.$user['channelicon'].'" width="120" height="90" class="moduleFeaturedThumb"></a>'.
						'<div class="moduleFeaturedTitle"><a href="/'.$user['url'].'">'.htmlspecialchars($user['username']).'</a></div>'.
						'<div class="moduleFeaturedDetails">Signed up: '.date("F j, Y", strtotime($user['uploaded_at'])).
						
						'</td>';
						//'<div class="moduleFeaturedDetails" style="padding-bottom: 5px;">Views: '.$user['views'].' | Comments: '.$user['comments'].'</div>'.
						if($i == 5) {
							echo '</tr>';
							$i = 0;
						}
					}
					
					?>
				</table>
			</div>
		</td>
		<td><img src="img/pixel.gif" width="5" height="1"></td>
	</tr>
	<tr>
		<td><img src="img/box_login_bl.gif" width="5" height="5"></td>
		<td><img src="img/pixel.gif" width="1" height="5"></td>
		<td><img src="img/box_login_br.gif" width="5" height="5"></td>
	</tr>
</table>
<?php
require __DIR__ . "/includes/footer.php";
?>
