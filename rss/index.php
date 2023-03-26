<?php
require __DIR__ . "/../includes/functions.php";

if($_SERVER['REQUEST_URI'] == "/rss/global/recently_added.rss") {
	$feed_title = $feed_description = "Recently Added Videos";
	$feed_link = "http://www.yoretude.com/rss/global/recently_added.rss";
	$videos = $conn->query("SELECT * FROM videos LEFT JOIN members ON members.member_id = videos.member_id WHERE videos.converted = 1 ORDER BY videos.uploaded_at DESC LIMIT 15");
} elseif (preg_match_all('/^\/rss\/user\/(\w+)\/my_videos\.rss$/m', $_SERVER['REQUEST_URI'], $matches, PREG_SET_ORDER, 0)) {
	$member = $conn->prepare("SELECT * FROM members WHERE username = ?");
	$member->execute([$matches['0']['1']]);
	if($member->rowCount() > 0) {
		$member = $member->fetch(PDO::FETCH_ASSOC);
		$feed_title = "Videos by ".htmlspecialchars($member['username']);
		$feed_description = "Videos uploaded by ".htmlspecialchars($member['username'])." hosted at http://www.yoretude.com.";
		$feed_link = "http://youtube.com/rss/user/".htmlspecialchars($member['username'])."/my_videos.rss";
		$videos = $conn->prepare("SELECT * FROM videos LEFT JOIN members ON members.member_id = videos.member_id WHERE videos.member_id = ? AND videos.converted = 1 ORDER BY videos.uploaded_at DESC LIMIT 15");
		$videos->execute([$member['member_id']]);
	} else {
		require __DIR__ . "/../errors/404.php";
	}
} elseif (preg_match_all('/^\/rss\/tag\/(.*).rss$/m', $_SERVER['REQUEST_URI'], $matches, PREG_SET_ORDER, 0)) {
	$matches['0']['1'] = str_replace("+", " ", $matches['0']['1']);
	$search = str_replace(" ", "|", $matches['0']['1']);
	$videos = $conn->prepare("SELECT * FROM videos LEFT JOIN members ON members.member_id = videos.member_id WHERE videos.tags REGEXP ? AND videos.converted = 1 ORDER BY videos.uploaded_at DESC"); // Regex!
	$videos->execute([$search]);
	
	if(strlen($matches['0']['1']) < 2) {
		class videos { // stupid
			function rowCount() {
				return 0;
			}
		}
		$videos = new videos;
	}
	
	$feed_title = "Tag // ".htmlspecialchars($matches['0']['1']);
	$feed_description = "Videos tagged with ".htmlspecialchars($matches['0']['1']);
	$feed_link = "http://youtube.com/rss/tag/".urlencode($matches['0']['1']).".rss";
} else {
	require __DIR__ . "/../errors/404.php";
}

header("Content-Type: text/xml");
echo '<?xml version="1.0" encoding="utf-8"?>'; // Have to echo this or else dreamhost throws a fit...
?>
<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss">
	<channel>
		<title>YoreTude :: <?php echo $feed_title; ?></title>
		<link><?php echo $feed_link; ?></link>
		<description><?php echo $feed_description; ?></description>
		<?php foreach ($videos as $video) { ?>
		<item>
			<author>rss@yoretude.com (<?php echo htmlspecialchars($video['username']); ?>)</author>
			<title><?php echo htmlspecialchars($video['title']); ?></title>
			<link>http://www.yoretude.com/?v=<?php echo htmlspecialchars($video['video_id']); ?></link>
			<description><![CDATA[
				<img src="http://www.yoretude.com/get_still.php?video_id=<?php echo htmlspecialchars($video['video_id']); ?>" align="right" border="0" width="120" height="90" vspace="4" hspace="4" />
				<p><?php echo htmlspecialchars($video['description']); ?></p>
				<p>
					Author: <a href="http://www.yoretude.com/profile.php?user=<?php echo htmlspecialchars($video['username']); ?>"><?php echo htmlspecialchars($video['username']); ?></a><br/>
					Keywords: <?php foreach(explode(" ", $video['tags']) as $tag) echo '<a href="http://www.yoretude.com/results.php?search='.htmlspecialchars($tag).'">'.htmlspecialchars($tag).'</a> '; ?><br/>
					Added: <?php echo date("F j, Y", strtotime($video['uploaded_at'])); ?><br/>
				</p>
			]]></description>
			<guid isPermaLink="true">http://www.yoretude.com/?v=<?php echo htmlspecialchars($video['video_id']); ?></guid>
			<pubDate><?php echo date("r", strtotime($video['uploaded_at'])); ?></pubDate>

			<media:player url="http://www.yoretude.com/?v=<?php echo htmlspecialchars($video['video_id']); ?>" />
			<media:thumbnail url="http://www.yoretude.com/get_still.php?video_id=<?php echo htmlspecialchars($video['video_id']); ?>" width="120" height="90" />
			<media:title><?php echo htmlspecialchars($video['title']); ?></media:title>
			<media:category label="Tags"><?php echo htmlspecialchars($video['tags']); ?></media:category>
			<media:credit><?php echo htmlspecialchars($video['username']); ?></media:credit>
		</item>
		<?php } ?>
	</channel>
</rss>