<?php
require __DIR__ . "/includes/header.php";

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
<?php if(isset($_GET['error'])) { ?>
	 <div class="errorBox"><?php if($_GET['error'] == 1) { echo 'This page was not found.'; } ?><br>
		</div>
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
<div class="errorBox">If this homepage looks broken, clear your browser cache. Your browser is still loading the outdated stylesheets for YoreTude.</div></br>
<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<td style="padding-right: 15px;">

<?php
$views = $conn->query("SELECT * FROM views LEFT JOIN videos ON videos.video_id = views.video_id  ORDER BY views.viewed_at DESC LIMIT 4");
$vista = $conn->prepare("SELECT * FROM views ORDER BY views.viewed_at DESC LIMIT 4");
?>	

<div id="standardContent" style="width: 685px;">
<?php if(!isset($session)) { ?>	
		<div class="contentBox" style="margin-bottom: 15px;">
			<table cellpadding="6" cellspacing="0" border="0">
				<tr valign="top">
					<td width="33%">
						<h1><a href="/browse">Watch</a></h1>
						Instantly find and watch 1000's of fast streaming videos.
					</td>
					<td width="33%">
						<h1><a href="my_videos_upload">Upload</a></h1>
						Quickly upload and tag videos in almost any video format.
					</td>
					<td width="34%">
						<h1><a href="my_friends_invite">Share</a></h1>
						Easily share your videos with family, friends, or co-workers.
					</td>
				</tr>
			</table>
		</div>
 <?php } ?>	
         <?php if(isset($session)) { ?>	
<!-- Logged In index -->
<div class="welcomebox">
        <div class="welcomemsg">Welcome, <?php echo htmlspecialchars($session['username']); ?></div>
        <div class="statcontainer">
            <div class="statbox">
            <div class="statbox_header">
                <p>My Stats</p>
            </div>
            <div class="statbox_content">
                <div class="stattext">
                <div><a href="/my_videos">My Videos</a> have been viewed 0 times</div>
                <div>I have 0 <a href="#">Friends</a></div>
                <div>I've watched 0 videos</div>
                <div>My Profile has been viewed 0 times</div>
                </div>

            </div>
        </div>
        <div class="statbox">
            <div class="statbox_header">
                <p>My Inbox</p>
            </div>
            <div class="statbox_content">
                <div class="stattext">
                    <div><img class="mailico" src="/img/mail.png">You have <a href="#">0 new messages</a></div>
                    <div>You have <a href="#">0 friend request</a></div>
                </div>
                <div class="weeklytude">
                    <div><a href="#">Sign up for "The Weekly Tude" e-mail</a></div>
                    <div>The best YoreTude videos delivered to you!</div>
                </div>
            </div>
        </div></div>
    </div><br>
    <?php } ?>
		<div class="headerRCBox">
	<b class="rch">
	<b class="rch1"><b></b></b>
	<b class="rch2"><b></b></b>
	<b class="rch3"></b>
	<b class="rch4"></b>
	<b class="rch5"></b>
	</b> <div class="content">		<div class="headerTitle">
			<div class="headerTitleRight">
				<a href="/browse?s=mp">See More Videos</a>
			</div>
			<span>Today's Featured Videos...</span>
		</div>
</div>
	</div>

	<div class="contentListBox" style="margin-bottom: 15px;">
    <?php
$featured_videos = $conn->query("SELECT * FROM featured LEFT JOIN videos ON videos.video_id = featured.video_id LEFT JOIN members ON members.member_id = videos.member_id WHERE videos.converted = 1 ORDER BY featured.feature_id DESC LIMIT 10");
?>
    <?php foreach($featured_videos as $video) { ?>
				<?php
				$video['views'] = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE video_id = ?");
				$video['views']->execute([$video['video_id']]);
				$video['views'] = $video['views']->fetchColumn();
						
				$video['comments'] = $conn->prepare("SELECT COUNT(comment_id) FROM comments WHERE video_id = ?");
				$video['comments']->execute([$video['video_id']]);
				$video['comments'] = $video['comments']->fetchColumn();

                $video['rating'] = $conn->prepare("SELECT AVG(rating) FROM star_rating WHERE video_id = ?");
				$video['rating']->execute([$video['video_id']]);
				$stars = $video['rating']->fetchColumn();
                $average_rating = substr($stars, 0, 1); // Removes all characters after the first one
                $video['ratings'] = $conn->prepare("SELECT COUNT(r_id) FROM star_rating WHERE video_id = ?");
				$video['ratings']->execute([$video['video_id']]);
				$video['ratings'] = $video['ratings']->fetchColumn();
				?>
		<div class="contentListStandard">
			<div class="vDetailEntry">
				<table cellpadding="0" cellspacing="0">
					<tr valign="top">
						<td>
							<table class="image" cellpadding="0" cellspacing="0">
								<tr>
									<td><a href="/watch?v=<?php echo $video['video_id']; ?>"><img src="get_still?still_id=2&video_id=<?php echo $video['video_id']; ?>" class="vimg" style="margin-left: 6px;" /></a></td>
								</tr>
							</table>
						</td>
						<td>
							<div class="vListInfo1Still">
								<div class="title">
									<b><a href="/watch?v=<?php echo $video['video_id']; ?>"><?php echo htmlspecialchars($video['title']); ?></a></b><br/>
									<span class="runtime"><?php echo gmdate("i:s", $video['duration']); ?></span>
								</div>
								<div class="desc"><?php echo htmlspecialchars($video['description']); ?></div>
								<div class="facets">
									<table class="tagTable" cellpadding="0" cellspacing="0">
										<tr>
											<td><span class="grayText">Tags:</span>&nbsp;</td>
											<td><?php foreach(explode(" ", $video['tags']) as $tag) echo '<a href="results.php?search='.htmlspecialchars($tag).'" class="dg">'.htmlspecialchars($tag).'</a> : '; ?></td>
										</tr>
									</table>
									<span class="grayText">Added:</span> <?php echo (timeAgo($video['uploaded_at'])); ?> <br/>
									<span class="grayText">From:</span> <a href="/profile?user=<?php echo htmlspecialchars($video['username']); ?>" class="dg"><?php echo htmlspecialchars($video['username']); ?></a><br/>
									<span class="grayText">Views:</span> <?php echo $video['views']; ?>
								</div>
											
	<nobr>
			<?php if ($average_rating == 1) { ?>
														<img  src="/img/star_sm.gif">
														<img  src="/img/star_sm_bg.gif">
														<img  src="/img/star_sm_bg.gif">
														<img  src="/img/star_sm_bg.gif">
														<img  src="/img/star_sm_bg.gif">
                                                       
                                                        <?php } ?>
                                                        <?php if (!$average_rating) { ?>
														<img  src="/img/star_sm_bg.gif">
														<img  src="/img/star_sm_bg.gif">
														<img  src="/img/star_sm_bg.gif">
														<img  src="/img/star_sm_bg.gif">
														<img  src="/img/star_sm_bg.gif">
                                                       
                                                        <?php } ?>
                                                        <?php if ($average_rating == 0) { ?>
														<img  src="/img/star_sm_bg.gif">
														<img  src="/img/star_sm_bg.gif">
														<img  src="/img/star_sm_bg.gif">
														<img  src="/img/star_sm_bg.gif">
														<img  src="/img/star_sm_bg.gif">
                                                       
                                                        <?php } ?>
                                                         <?php if ($average_rating == 2) { ?>
														<img  src="/img/star_sm.gif">
														<img  src="/img/star_sm.gif">
														<img  src="/img/star_sm_bg.gif">
														<img  src="/img/star_sm_bg.gif">
														<img  src="/img/star_sm_bg.gif">
                                                        
                                                        <?php } ?>
                                                        <?php if ($average_rating == 3) { ?>
														<img  src="/img/star_sm.gif">
														<img  src="/img/star_sm.gif">
														<img  src="/img/star_sm.gif">
														<img  src="/img/star_sm_bg.gif">
														<img  src="/img/star_sm_bg.gif">
                                                        
                                                        <?php } ?>
                                                        <?php if ($average_rating == 4) { ?>
														<img  src="/img/star_sm.gif">
														<img  src="/img/star_sm.gif">
														<img  src="/img/star_sm.gif">
														<img  src="/img/star_sm.gif">
														<img  src="/img/star_sm_bg.gif">
                                                        <?php } ?>
                                                        <?php if ($average_rating == 5) { ?>
														<img  src="/img/star_sm.gif">
														<img  src="/img/star_sm.gif">
														<img  src="/img/star_sm.gif">
														<img  src="/img/star_sm.gif">
														<img  src="/img/star_sm.gif">
                                                        <?php } ?>
	</nobr>
	<div class="rating"><?php echo $video['ratings']; ?> ratings</div>


							</div>
						</td>
						
					</tr>
				</table>
			</div>
		</div>
	<?php } ?>

	</div>
</div>



<div id="standardSidebar" style="width: 165px;">
	

	
		<div class="headerRCBox">
	<b class="rch">
	<b class="rch1"><b></b></b>
	<b class="rch2"><b></b></b>
	<b class="rch3"></b>
	<b class="rch4"></b>
	<b class="rch5"></b>
	</b> <div class="content">		<div style="text-align: center;">
			<img src="/img/new_features.gif" border="0" />
		</div>
</div>
	</div>

	<div class="contentBox" style="text-align: center; margin-bottom: 15px;">
		<!--
		<div id="set_of_links"><a href="javascript:;"><strong>New Video Watch Page</strong><span>Just choose the video you want to see, and then click on "Watch this video on our new Video Watch Page" link in the top right corner to see how it will look with the new design.</span></a><br />Check out the new design&#8212;we'd love your feedback!</div> 
		-->
		<!-- <b><a href="/blog">New Video Watch Page</a></b><br/>
		Check out the new design&#8212;we'd love your feedback!
		<p> -->
		<strong><a href="/t/howto_copyright">Learn About Copyright</a></strong><br/>
		Answers to your questions about copyright and YoreTude's policy
		</p>
		<span class="largeText"><strong><a href="/t/explore_YoreTude">Explore YoreTude</a></strong></span>
	</div>
	
	
	
	
	<h3>Recent Tags:</h3>
	<div class="tagCloud">
	<?php foreach($tag_list as $tag => $frequency	) {
        $taggie = $frequency*2;
        $taggie2 = $taggie+10;
        if ($taggie2 > 20) {
            $taggie2 = 20;
        }
					echo '<a style="font-size: '.htmlspecialchars($taggie2).'px;" href="results.php?search='.htmlspecialchars($tag).'">'.htmlspecialchars($tag).'</a> :'."\r\n";
				} ?>
	</div>
	<h4><a href="/tags">See More Tags</a></h4>
</div>
<?php
require __DIR__ . "/includes/footer.php";
?>
