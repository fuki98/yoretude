<?php
require __DIR__ . "/includes/header.php";

$video_info = $conn->prepare("SELECT * FROM videos WHERE video_id = ? AND converted = 1");
$video_info->execute([$_GET['v']]);

if($video_info->rowCount() == 0) {
	header("Location: /?error=1");
	die();
} else {
	$video_info = $video_info->fetch(PDO::FETCH_ASSOC);
}

if($video_info['converted'] == 0) {
	die(require_once './includes/watch_converting.php');
}

$creator_info = $conn->prepare("SELECT * FROM members WHERE member_id = ?");
$creator_info->execute([$video_info['member_id']]);
$creator_info = $creator_info->fetch(PDO::FETCH_ASSOC);

$creator_videos = $conn->prepare("SELECT video_id FROM videos WHERE member_id = ? AND converted = 1");
$creator_videos->execute([$creator_info["member_id"]]);
$creator_favorites = $conn->prepare("SELECT favorite_id FROM favorites WHERE member_id = ?");
$creator_favorites->execute([$creator_info["member_id"]]);
					
$comments = $conn->prepare("SELECT * FROM comments LEFT JOIN members ON members.member_id = comments.member_id WHERE video_id = ? ORDER BY posted_at DESC LIMIT 14");
$comments->execute([$video_info['video_id']]);

$views = $conn->prepare("SELECT COUNT(view_id) AS views FROM views WHERE video_id = ?");
$views->execute([$video_info['video_id']]);
$video_info['views'] = $views->fetchColumn();

$fav = $conn->prepare("SELECT COUNT(favorite_id) AS favorites FROM favorites WHERE video_id = ?");
$fav->execute([$video_info['video_id']]);
$video_info['favcount'] = $fav->fetchColumn();

$ratingscount = $conn->prepare("SELECT COUNT(rating) FROM star_rating WHERE video_id = ?");
$ratingscount->execute([$video_info['video_id']]);
$video_info['ratnum'] = $ratingscount->fetchColumn();

$avg = $conn->prepare("SELECT AVG(rating) FROM star_rating WHERE video_id = ?");
$avg->execute([$video_info['video_id']]);
$average = $avg->fetchColumn();
$average_rating = substr($average, 0, 1); // Removes all characters after the first one


// Create view if it hasn't already been made within the past 10 minutes (spam prevention)
$already_viewed = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE video_id = ? AND session_id = ? AND viewed_at > DATE_SUB(NOW(), INTERVAL 10 MINUTE)");
$already_viewed->execute([$video_info['video_id'], session_id()]);
if($already_viewed->fetchColumn() == 0) {
    if(!$_SESSION) {
     $add_view = $conn->prepare("INSERT INTO views (view_id, video_id, session_id) VALUES (?, ?, ?)");
	$add_view->execute([generateId(26), $video_info['video_id'], session_id()]);   
    }
     if($_SESSION) {
	$add_view = $conn->prepare("INSERT INTO views (view_id, video_id, session_id, member) VALUES (?, ?, ?, ?)");
	$add_view->execute([generateId(26), $video_info['video_id'], session_id(), $session['member_id']]);	
     }
}
$director_videos = $conn->query("SELECT * FROM videos LEFT JOIN members ON members.member_id = videos.member_id WHERE members.ctype = 2 ORDER BY videos.uploaded_at DESC LIMIT 4");
$favorite_exists = $conn->prepare("SELECT r_id FROM star_rating WHERE member_id = :member_id AND video_id = :video_id");
$favorite_exists->execute([
	":member_id" => $session['member_id'],
	":video_id" => $_GET['video_id']
]);
 if($favorite_exists->rowCount() > 0) { 
     $average_rating = $favorite_exists['rating'];
 }

// categories
$_CHANNELS = [
"0" => "This video does not have any categories!",
"1" => "Arts and Animation",
"2" => "Autos & Vehicles",
"3" => "Comedy",
"4" => "Entertainment",
"5" => "Music",
"6" => "People",
"7" => "Science and Technology",
"8" => "Travel & Places",
"9" => "News & Blogs",
"10" => "Pets & Animals",
"11" => "Sports",
"12" => "Video Games",
];
$CHANNELNUM = $video_info['category'];
?>
<title> YoreTude - <?php echo htmlspecialchars($video_info['title']); ?></title>
<link rel="stylesheet" href="/watch.css" type="text/css">
</head>
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

function FavoritesHandler()
{
	if (CheckLogin() == false)
		return false;

	alert("Video has been added to Favorites!");
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
	<script type="text/javascript" src="/js/ui.js"></script>
	<script language="javascript" type="text/javascript">
		onLoadFunctionList = new Array();
		function performOnLoadFunctions()
		{
			for (var i in onLoadFunctionList)
			{
				onLoadFunctionList[i]();
			}
		}
	</script>
	
		
	
	
	<script type="text/javascript" src="./flashobject.js"></script>
	<script type="text/javascript" src="./js/components_yts1146206499.js"></script>
	<script type="text/javascript" src="./js/AJAX_yts1146522477.js"></script>
	<script type="text/javascript" src="./js/ui_yts1146789259.js"></script>
	<script type="text/javascript" src="./js/comments_yts1146866571.js"></script>
<script language="javascript" type="text/javascript">

	

	
	
	function showRelatedVideosContent() {
		getAndShowNavContent('exRelated', '/watch_ajax?video_id=<?php echo $video_info['video_id']; ?>&action_get_related_videos_component&search=sorry');
	}
	
	function showRelatedPlaylistContent() {
		getAndShowNavContent('exPlaylist', '/watch_ajax?feature=PlayList&video_id=<?php echo $video_info['video_id']; ?>&action_get_related_playlist_component&p=&index=0');
	}
	function showRelatedUserContent() {
		getAndShowNavContent('exUser', '/watch_ajax?video_id=<?php echo $video_info['video_id']; ?>&action_get_user_videos_component&user_id=geV6Xb3A81s&video_count=6');
	}

	
	
	// This can be split out...
	var contentTab=new Array();	
	function getAndShowNavContent(nameprefix, url) {
		selectNavLink(nameprefix + "Link");
	
		if(contentTab[nameprefix]) {
			return;
		}
		
		self.nameprefix = nameprefix;
		self.showRelatedVideosResponse = showRelatedVideosResponse;
		getUrlXMLResponse(url, showRelatedVideosResponse);
	}
	function showRelatedVideosResponse(req) {
		document.getElementById(self.nameprefix + "Div").innerHTML=getNodeValue(req.responseXML, "html_content");
		setContentLoaded(self.nameprefix);
	}
		
	function setContentLoaded(nameprefix) {
		selectNavLink(nameprefix + "Link");
		contentTab[nameprefix] = 1;
	}
	

	
	function showAjaxDiv(divName, url) {
		if(CheckLogin()) {
			self.divName = divName
			self.showAjaxDivResponse = showAjaxDivResponse
			getUrlXMLResponse(url, self.showAjaxDivResponse);
		} else {
			alert("Please login to perform this operation.");
		}
	}
	function showAjaxDivResponse(req) {
		document.getElementById(self.divName).innerHTML=getNodeValue(req.responseXML, "html_content");
		openDiv(self.divName);
	}
	
	function postAjaxForm(divName, formName) {
		self.postAjaxFormCompleted = postAjaxFormCompleted;	
		self.divName = divName;
		
		postFormXMLResponse(formName, self.postAjaxFormCompleted);
	}
	function postAjaxFormCompleted(req) {
		if(self.divName != null)
			hideAjaxDiv(self.divName);
	}
	function hideAjaxDiv(divName) {
		closeDiv(divName);
	}
	
	
	
	
	
	// Player stuff
	
	function openFull()
	{
	  var fs = window.open( "/watch_fullscreen?video_id=<?php echo $video_info['video_id']; ?>&l=289&t=OEgsToPDskLuieoyXXnXI0sU38XTZpnr&fs=1&title=" + "Cueshe - Sorry (MYX)" ,
			   "FullScreenVideo", "toolbar=no,width=" + screen.availWidth  + ",height=" + screen.availHeight 
			 + ",status=no,resizable=yes,fullscreen=yes,scrollbars=no");
	  fs.focus();
	}
	
	function gotoNext()
	{
		window.location = "/watch?v=5yN5b9Yrz-g&playnext=1";
	}
	
	function hideDiv(d) 
	{                   
		d.style.display = "none";
	}               
	
	function showDiv(d)
	{
		d.style.display = "block";
	}
	
	function autoNext()
	{
		var p = document.getElementById("movie_player");
		p.SetVariable("playnext", "1");
	
		var pa = document.getElementById("playall");
		var pga = document.getElementById("playingall");
		hideDiv(pa);
		showDiv(pga);
	}

	
	
	
	
		function showCommentReplyForm(form_id, reply_parent_id, is_main_comment_form) {
		if(!CheckLogin()) {
			alert("Please login to post a comment.");
			return false;
		}
			
		printCommentReplyForm(form_id, reply_parent_id, is_main_comment_form);
	}
	function printCommentReplyForm(form_id, reply_parent_id, is_main_comment_form) {

		var div_id = "div_" + form_id;
		var reply_id = "reply_" + form_id;
		var reply_comment_form = "comment_form" + form_id;
		
		if (is_main_comment_form)
			discard_visible="style='display: none'";
		else
			discard_visible="";
		
		var innerHTMLContent = '\
		<form name="' + reply_comment_form + '" id="' + reply_comment_form + '" method="post" action="/add_comment" >\
			<input type="hidden" name="video_id" value="<?php echo $video_info['video_id']; ?>">\
			<input type="hidden" name="add_comment" value="">\
			<input type="hidden" name="form_id" value="' + reply_comment_form + '">\
			<input type="hidden" name="reply_parent_id" value="' + reply_parent_id + '">\
			<input type="hidden" name="comment_type" value="V">\
		<textarea tabindex="2" name="comment" cols="55" rows="3"></textarea>\
			<br>\
			<input align="right" type="button" name="add_comment_button" \
								value="Post Comment" \
								onclick="postThreadedComment(\'' + reply_comment_form + '\');">\
			<input align="right" type="button" name="discard_comment_button"\
								value="Discard" ' + discard_visible + '\
								onclick="hideCommentReplyForm(\'' + form_id + '\',false);">\
		</form></div>';
		if(!is_main_comment_form) {
			toggleVisibility(reply_id, false);
		}
		toggleVisibility(div_id, true);
		setInnerHTML(div_id, innerHTMLContent);
	}

	
	
	function blockUser(form) 
	{
        if (!confirm("Are you sure you want to block this user?"))
            return false;

		postFormByForm(form, true, execOnSuccess(function (xmlHttpRequest) { 
			response_str = xmlHttpRequest.responseText;
			if(response_str == "SUCCESS") {
				form.block_button.value = "User blocked"
			} else {
				alert ("An error occured while blocking the user.");
				form.block_button.value = "Block this user"
				form.block_button.disabled = false;
			}
		}));
		form.block_button.disabled = true
		form.block_button.value = "Please wait.."
		return true;
	}
	function unblockUser(form) 
	{
        if (!confirm("Are you sure you want to unblock this user?"))
            return false;

		postFormByForm(form, true, execOnSuccess(function (xmlHttpRequest) { 
			response_str = xmlHttpRequest.responseText;
			if(response_str == "SUCCESS") {
				form.unblock_button.value = "User unblocked"
			} else {
				alert ("An error occured while unblocking the user.");
				form.unblock_button.value = "Unblock this user"
				form.unblock_button.disabled = false;
			}
		}));

		form.unblock_button.disabled = true
		form.unblock_button.value = "Please wait.."
		return true;
	}
	
	function unblockUserLink(friend_id, url)
	{
        if (!confirm("Are you sure you want to unblock this user?"))
            return true;
		getUrlSync("/link_servlet?unblock_user=1&friend_id=" + friend_id);
	    window.location.href = url;
		return true;
	}
	function blockUserLink(friend_id, url)
	{
        if (!confirm("Are you sure you want to block this user?"))
            return true;
		getUrlSync("/link_servlet?block_user=1&friend_id=" + friend_id);
	    window.location.href = url;
		return true;
	}




	
	
		onLoadFunctionList.push(function() { setContentLoaded("exRelated"); } );
		
	</script>
<!-- begin presentation code -->
<body onload="performOnLoadFunctions();">
<h1><?php echo htmlspecialchars($video_info['title']); ?></h1>
<link rel="stylesheet" href="player.css">

<table id="watchTable"><tr>
<td>
<div id="interactDiv">

	<div id="playerDiv">
		<div id="flashcontent">
					<div style="padding: 20px; font-size:14px; font-weight: bold;">
						Hello, you either have JavaScript turned off or an old version of Macromedia's Flash Player, <a href="https://archive.org/details/fp6_archive">click here</a> to get the latest flash player.
					</div>
			<script type="text/javascript">
			if(swfobject.hasFlashPlayerVersion("6")) {	
				swfobject.embedSWF("/players/player2.swf?video_id=<?php echo $video_info['video_id']; ?>&l=<?php echo ceil($video_info['duration']); ?>", "flashcontent", "425", "350", "6");
			} else if(typeof(document.createElement('video').canPlayType) != 'undefined' && document.createElement('video').canPlayType('video/webm;codecs="vp8,opus"') == "probably") {
				document.getElementById('flashcontent').innerHTML = '<figure id="videoContainer" oncontextmenu="return false;"><video id="video" controls preload="metadata" src="/get_video.php?video_id=<?php echo $video_info['video_id'] ?>&format=webm"></video><img class="watermark" src="img/watermark.png"><br><div id="video-controls" class="controls" data-state="hidden"><button id="playpause" type="button" data-state="play">Play/Pause</button><div class="progress"><progress id="progress" value="0" min="0"><span id="progress-bar"></span></progress></div><button id="mute" type="button" data-state="mute">Mute/Unmute</button></div></figure></div>';
			}
			</script>
			<script src="player.js"></script>
		</div>
	</div>

	<div id="actionsAndStatsDiv" class="contentBox">
		<div id="ratingDivWrapper">
			<div id="ratingDiv">
					<div id="ratingMessage" class="label">Rate this video</div>
								
	<nobr>
			<?php if ($average_rating == 1) { ?>
														<img class="rating" src="/img/star.gif">
														<img class="rating" src="/img/star_bg.gif">
														<img class="rating" src="/img/star_bg.gif">
														<img class="rating" src="/img/star_bg.gif">
														<img class="rating" src="/img/star_bg.gif">
                                                       
                                                        <?php } ?>
                                                        <?php if (!$average_rating) { ?>
														<img class="rating" src="/img/star_bg.gif">
														<img class="rating" src="/img/star_bg.gif">
														<img class="rating" src="/img/star_bg.gif">
														<img class="rating" src="/img/star_bg.gif">
														<img class="rating" src="/img/star_bg.gif">
                                                       
                                                        <?php } ?>
                                                        <?php if ($average_rating == 0) { ?>
														<img class="rating" src="/img/star_bg.gif">
														<img class="rating" src="/img/star_bg.gif">
														<img class="rating" src="/img/star_bg.gif">
														<img class="rating" src="/img/star_bg.gif">
														<img class="rating" src="/img/star_bg.gif">
                                                       
                                                        <?php } ?>
                                                         <?php if ($average_rating == 2) { ?>
														<img class="rating" src="/img/star.gif">
														<img class="rating" src="/img/star.gif">
														<img class="rating" src="/img/star_bg.gif">
														<img class="rating" src="/img/star_bg.gif">
														<img class="rating" src="/img/star_bg.gif">
                                                        
                                                        <?php } ?>
                                                        <?php if ($average_rating == 3) { ?>
														<img class="rating" src="/img/star.gif">
														<img class="rating" src="/img/star.gif">
														<img class="rating" src="/img/star.gif">
														<img class="rating" src="/img/star_bg.gif">
														<img class="rating" src="/img/star_bg.gif">
                                                        
                                                        <?php } ?>
                                                        <?php if ($average_rating == 4) { ?>
														<img class="rating" src="/img/star.gif">
														<img class="rating" src="/img/star.gif">
														<img class="rating" src="/img/star.gif">
														<img class="rating" src="/img/star.gif">
														<img class="rating" src="/img/star_bg.gif">
                                                        <?php } ?>
                                                        <?php if ($average_rating == 5) { ?>
														<img class="rating" src="/img/star.gif">
														<img class="rating" src="/img/star.gif">
														<img class="rating" src="/img/star.gif">
														<img class="rating" src="/img/star.gif">
														<img class="rating" src="/img/star.gif">
                                                        <?php } ?>
                                                
	</nobr>


			</div> <!-- end ratingDiv -->
             <?php if($favorite_exists->rowCount() == 0) { ?>
			<?php if(isset($session)) { ?>
															<a href="rate_video.php?video_id=<?php echo htmlspecialchars($video_info['video_id']); ?>&am=1" style="text-decoration:none;"><img class="rating" src="/img/star_bg.gif">
														<a href="rate_video.php?video_id=<?php echo htmlspecialchars($video_info['video_id']); ?>&am=2" style="text-decoration:none;"><img class="rating" src="/img/star_bg.gif">
														<a href="rate_video.php?video_id=<?php echo htmlspecialchars($video_info['video_id']); ?>&am=3" style="text-decoration:none;"><img class="rating" src="/img/star_bg.gif">
														<a href="rate_video.php?video_id=<?php echo htmlspecialchars($video_info['video_id']); ?>&am=4" style="text-decoration:none;"><img class="rating" src="/img/star_bg.gif">
														<a href="rate_video.php?video_id=<?php echo htmlspecialchars($video_info['video_id']); ?>&am=5" style="text-decoration:none;"><img class="rating" src="/img/star_bg.gif">
                                                        <div class="rating">0 ratings</div>
															<?php } else { ?>
																
										
                                                        <div class="ratingNote">Please <a href="/signup?next=/watch.php%3Fv%3DfTETYgsO1jQ%26search%3Dsorry%26watch2">login</a> to rate this video.</div>
															<?php } ?>
				<?php } ?>
			
		</div> <!-- end ratingDivWrapper -->
	
		<div id="actionsDiv">
			<div class="actionRow">
			<a href="add_favorites.php?video_id=<?php echo htmlspecialchars($video_info['video_id']); ?>" class="noul"> <img src="/img/fav_w_icon.gif" border="0" class="alignMid" /> <span class="eLink">Save to Favorites</span></a>
			</div>
			<div class="actionRow">
			<a href="#" class="noul" onclick="showAjaxDiv('addGroupsDiv', '/watch_ajax?video_id=<?php echo htmlspecialchars($video_info['video_id']); ?>&action_get_groups_component=1', true); return false;"><img src="/img/groups_w_icon.gif" border="0" class="alignMid" /> <span class="eLink">Add to Groups</span></a>
			</div>
			<div class="actionRow">
			<a href="#" class="noul" onClick="window.open('/share?v=<?php echo htmlspecialchars($video_info['video_id']); ?>','Share','width=560,height=460,resizable=yes,scrollbars=yes,status=0'); return false;"><img src="/img/email_w_icon.gif" border="0" class="alignMid" /> <span class="eLink">Share This Video</span></a>
			</div>
			<!--
			<div class="actionRow">
			<a href="#" class="noul" onclick="openDiv('embedVidDiv'); return false;"><img src="/img/web_w_icon.gif" border="0" class="alignMid" /> <span class="eLink">Embed on website</span></a>
			</div>
			-->
			<div class="actionRow">
			<a href="#" class="noul" onclick="showAjaxDiv('inappropriateVidDiv', '/watch_ajax?video_id=<?php echo htmlspecialchars($video_info['video_id']); ?>&action_get_flag_video_component=1', true); return false;"><img src="/img/flag_w_icon.gif" border="0" class="alignMid" /> <span class="eLink">Flag as inappropriate</span></a>
			</div>
		</div>
		<div id="statsDiv">
			<div class="statRow">
			<span class="smallLabel">Views:</span>
			<?php echo $video_info['views']; ?>                                            
			</div>
			<div class="statRow">
			<span class="smallLabel">Comments:</span>
			<?php echo $comments->rowCount(); ?>
			</div>
			<div class="statRow">
			<span class="smallLabel">Favorited:</span>
			<?php echo $video_info['favcount']; ?>
				<span class="smallText">times</span>
			</div>
			<div class="statRow">
			<span class="smallLabel">Honors:</span>
			0
			</div>
			<div class="statRow">
				<div id="linksToLabel"><span class="smallLabel">Links to<br/>this video</span></div>
				<div id="linksToValue">
					<span><a href="#" class="eLink smallText" onclick="openDiv('linkstoDiv'); return false;">show</a></span>
				</div>
			</div>
			<!--
			<span class="smallLabel">Referers:</span>
				<span><a href="#" class="eLink smallText" onclick="openDiv('linkstoDiv'); return false;">show</a></span>
			<br/>
			-->
		</div>
		

		<div id="addFavsDiv" class="popupDiv"></div>

		
		<div id="addGroupsDiv" class="popupDiv"></div>
		
			
		<div id="inappropriateVidDiv" class="popupDiv"></div>
		
		
		<div id="embedVidDiv" class="popupDiv">
			<div class="closeDiv">(<a href="#" title="close this layer"  onclick="closeDiv('embedVidDiv'); return false;">close</a>)</div>
			<h1 style="margin-top: 0px;">Put This Video on Your Website</h1>
			<p>
			Put the following code on your website to embed the video in the page.<br/>
			<span class="smallText">(Works on Friendster, eBay, Blogger, MySpace!)</span>
			</p>
			<p>
			<textarea id="embedTextarea"><object width="425" height="350"><param name="movie" value="http://www.YoreTude.com/v/<?php echo htmlspecialchars($video_info['video_id']); ?>"></param><embed src="http://www.YoreTude.com/v/<?php echo htmlspecialchars($video_info['video_id']); ?>" type="application/x-shockwave-flash" width="425" height="350"></embed></object></textarea>
			</p>
			<table class="okTable"><tr><td>
			<form>
			<input type="submit" value="OK" onclick="closeDiv('embedVidDiv'); return false;">
			</form>
			</td></tr></table>
		</div>
		
		<div id="honorsDiv" class="popupDiv">
			<div class="closeDiv">(<a href="#;" title="close this layer" onclick="closeDiv('honorsDiv'); return false;">close</a>)</div>
			<h1 style="margin-top: 0px;">Honors for This Video</h1>
			<p>
		
		
		
		
		
		
		
		
		
			</p>
			<table class="okTable"><tr><td>
			<form>
			<input type="submit" value="OK" onclick="closeDiv('honorsDiv'); return false;">
			</form>
			</td></tr></table>
		</div>
		
		<div id="linkstoDiv" class="popupDiv">
			<div class="closeDiv">(<a href="#" title="close this layer" onclick="closeDiv('linkstoDiv'); return false;">close</a>)</div>
			<h1 style="margin-top: 0px;">Sites Linking to This Video</h1>
			<p>
				<span class="label">1 clicks from</span> <a href="http://www.yoretude.com/" target="_top">http://www.yoretude.com/</a><br>

			</p>
			<table class="okTable"><tr><td>
			<form>
			<input type="submit" value="OK" onclick="closeDiv('linkstoDiv'); return false;">
			</form>
			</td></tr></table>
		</div>

	</div> <!-- end actionsAndStatsDiv -->
	
	
	<div id="commentsDiv">
		<div id="recent_comments">
			<table><tr>
			<td><h2 class="commentHeading">Recent Comments (<?php echo $comments->rowCount(); ?> total)</h2></td>
			<td align="right"><div id="reply_main_comment2" class="commentHeading"><a href="#" class="eLink" onclick="showCommentReplyForm('main_comment2', '', false); return false;">Post a new comment</a></div></td>
			</tr></table>
			
			<div id="div_main_comment2"></div>

			<input type="button" id="all_comments_button" value="View All Comments" onclick="load_all_comments('<?php echo htmlspecialchars($video_info['video_id']); ?>', true);" />
            <?php
			if($comments !== false) {
				foreach($comments as $comment) {
					$comment_videos = $conn->prepare("SELECT COUNT(video_id) FROM videos WHERE member_id = ? AND converted = 1");
					$comment_videos->execute([$comment["member_id"]]);
					
					$comment_favorites = $conn->prepare("SELECT COUNT(favorite_id) FROM favorites WHERE member_id = ?");
					$comment_favorites->execute([$comment["member_id"]]);?>
                    <div id="div_<?php echo htmlspecialchars($comment['comment_id']); ?>"> <!-- comment_div_id -->
			<a name="<?php echo htmlspecialchars($comment['comment_id']); ?>"/></a>
			<div class="commentEntry" id="comment_<?php echo htmlspecialchars($comment['comment_id']); ?>">
				<div class="commentHead">
					<b><a href="/user/<?php echo htmlspecialchars($comment['username']); ?>"><?php echo htmlspecialchars($comment['username']); ?></a></b>
					<span class="smallText"> (<?php echo timeAgo($comment['posted_at']); ?>)</span>
				</div>
				<div class="commentBody">
					<?php echo htmlspecialchars($comment['body']); ?>
				</div>
					<div class="commentAction smallText">
						
	<div class="commentAction smallText" id="container_comment_form_id_<?php echo htmlspecialchars($comment['comment_id']); ?>" style="display: none">
	</div>
    <div class="commentAction smallText" id="reply_comment_form_id_<?php echo htmlspecialchars($comment['comment_id']); ?>">
				  (<a href="#" onclick="showCommentReplyForm('comment_form_id_<?php echo htmlspecialchars($comment['comment_id']); ?>', '<?php echo htmlspecialchars($comment['comment_id']); ?>', false); return false;" class="eLink">reply to this</a>) &nbsp; 
	</div>

	<div id="div_comment_form_id_<?php echo htmlspecialchars($comment['comment_id']); ?>"></div>

					</div>
			</div>
	</div> <!-- comment_div_id -->
				<?php }
			}
			?>




		</div>
	<div id="all_comments" style="display: none;">
		<table><tr>
		<td><h2 class="commentHeading">Comments (67)</h2></td>
		<td align="right"><div id="reply_main_comment2" class="commentHeading"><a href="#" class="eLink" onclick="showCommentReplyForm('main_comment2', '', false); return false;">Post a new comment</a></div></td>
		</tr></table>
		
		<div id="div_main_comment2"></div>
		
		<div id="all_comments_content">
		</div>
	</div>

	<?php if(isset($session)) { ?>
			<form name="comment_form" id="comment_form" method="post" action="add_comment" target="invisible" onSubmit="return CommentHandler();">
				<input type="hidden" name="video_id" value="<?php echo htmlspecialchars($video_info['video_id']); ?>">
				<textarea name="comment" cols="55" rows="3"></textarea>
				<br>
				<input type="submit" name="comment_button" value="Add Comment">
			</form><?php } ?>
			
			<!--
			Join YoreTude for free to comment on this video.
			Already a YoreTude member? Login to comment.
			-->
            <?php if(!isset($session)) { ?>
			<h2 class="commentHeading">Would you like to Comment?</h2>
			<div style="margin-top: 8px;">
			<a href="/signup">Join YoreTude</a> for a free account, or
			<a href="/signup_login">Login</a> if you are already a member.
			</div>
			</form><?php } ?>
		
		<div id="div_main_comment"></div>
	</div> <!-- end commentsDiv -->
	
	
	
</div> <!-- end interactDiv -->
</td>



<td>

<div id="aboutExploreDiv">
		<div class="headerRCBox">
	<b class="rch">
	<b class="rch1"><b></b></b>
	<b class="rch2"><b></b></b>
	<b class="rch3"></b>
	<b class="rch4"></b>
	<b class="rch5"></b>
	</b> <div class="content"><span class="headerTitleLite">About This Video</span></div>
	</div>

	<div id="aboutVidDiv" class="contentBox">
		<div id="uploaderInfo">
				<div>
				<span class="smallLabel">Added on</span> <b class="smallText"><?php echo date("F j, Y, h:i a", strtotime($video_info ['uploaded_at']. ' - 17 years')); ?></b><br/>
				<span class="smallLabel">by</span> <b><a href="profile.php?user=<?php echo htmlspecialchars($creator_info['username']); ?>"><?php echo htmlspecialchars($creator_info['username']);
            ?> </a></b>&nbsp;
				<span class="xsmallText">(<?php echo $creator_videos->rowCount(); ?> videos | <?php echo $creator_favorites->rowCount(); ?> favorites)</span>
				</div>


		</div> <!-- end uploaderInfo -->
		
		
        <div id="vidNameDescDiv">
			<span id="vidDescBegin">
            <?php echo htmlspecialchars($video_info['description']); ?>
			</span>
		</div>
		
		<div id="vidTagsWrapper">
			<div id="vidTagsLabel" class="label">Tags:</div>
			<div id="vidTagsDiv">
			<span id="vidTagsBegin">
<?php
						foreach(explode(" ", $video_info['tags']) as $tag) {
							echo '<a href="results.php?search='.htmlspecialchars($tag).'">'.htmlspecialchars($tag).'</a>&nbsp; ';
						}
						?>
			</div> <!-- end vidTagsDiv -->
		</div> <!-- end vidTagsWrapper -->
		
		<div id="vidURLDiv">
            <form name="urlForm" id="urlForm">
            <table id="vidURLTable">
            <tr><td><span class="label">URL</span></td>
            <td>
            <input name="video_link" type="text" value="http://www.YoreTude.com/watch?v=<?php echo htmlspecialchars($video_info['video_id']); ?>" class="vidURLField" onClick="javascript:document.urlForm.video_link.focus();document.urlForm.video_link.select();" readonly="true">
            </td>
            </tr>
            <tr><td><span class="smallLabel">Embed</span></td>
            <td>
            <input name="embed_code" type="text" value='<object width="425" height="350"><param name="movie" value="http://www.YoreTude.com/v/<?php echo htmlspecialchars($video_info['video_id']); ?>"></param><embed src="http://www.YoreTude.com/v/<?php echo htmlspecialchars($video_info['video_id']); ?>" type="application/x-shockwave-flash" width="425" height="350"></embed></object>' class="vidURLField" onClick="javascript:document.urlForm.embed_code.focus();document.urlForm.embed_code.select();" readonly="true">
            </td></tr>
            </table>
            </form>
        </div>
	</div>

        <?php
	$search = str_replace(" ", "|", $video_info['tags']);
								$results = $conn->prepare("SELECT * FROM videos LEFT JOIN members ON members.member_id = videos.member_id WHERE videos.tags REGEXP ? AND videos.converted = 1 ORDER BY videos.uploaded_at DESC"); // Regex!
								$results->execute([$video_info['tags']]);
?>
		<div class="headerRCBox">
	<b class="rch">
	<b class="rch1"><b></b></b>
	<b class="rch2"><b></b></b>
	<b class="rch3"></b>
	<b class="rch4"></b>
	<b class="rch5"></b>
	</b> <div class="content"><span class="headerTitleLite">Explore More Videos</span></div>
	</div>

	<div id="exploreDiv" class="contentBox">

		<table id="exSubNavTable"><tr>
		<td><a href="#" id="exRelatedLink" class="selectedNavLink" onclick="showRelatedVideosContent(); return false;">Related</a></td>
		</tr></table>
		

			<div id="exRelatedDiv" style="display: block;">
				<table class="showingTable"><tr>
	<td class="smallText">Showing 1-20 of 498</td>
	<td align="right" class="smallText"><a href="/results?related=sorry">See All Videos</a></td>
	</tr></table>


		<div id="side_results" class="exploreContent" name="side_results" onscroll="render_full_side()" >
<?php foreach($results as $result) { ?>
<?php
$result['views'] = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE video_id = ?");
$result['views']->execute([$result['video_id']]);
$result['views'] = $result['views']->fetchColumn();
						
$result['comments'] = $conn->prepare("SELECT COUNT(comment_id) FROM comments WHERE video_id = ?");
$result['comments']->execute([$result['video_id']]);
$result['comments'] = $result['comments']->fetchColumn();
?>
		<div class="vWatchEntry">
		<table><tr>
		<td><div class="img">
				<a href="/watch?v=<?php echo htmlspecialchars($result['video_id']); ?>"><img class="vimgSm" src="get_still.php?video_id=<?php echo htmlspecialchars($result['video_id']); ?>" /></a></div></td>
		<td><div class="title"><b><a href="/watch?v=<?php echo htmlspecialchars($result['video_id']); ?>"><?php echo htmlspecialchars($result['title']); ?></a></b><br/>
			<span class="runtime"><?php echo gmdate("i:s", $result['duration']); ?></span>
			</div>
			<div class="facets">
				<span class="grayText">From:</span> <a href="/user/<?php echo htmlspecialchars($result['username']); ?>"><?php echo htmlspecialchars($result['username']); ?></a><br/>
				<span class="grayText">Views:</span> <?php echo htmlspecialchars($result['views']); ?>
			</div>
			</div></td>
		</tr></table>
		</div> <!-- end vWatchEntry -->
	<?php } ?>
		
	
	</div>

			<table class="showingTable"><tr>
	<td class="smallText">Showing 1-20 of 498</td>
	<td align="right" class="smallText"><a href="/results?related=sorry">See All Videos</a></td>
	</tr></table>
			</div> <!-- end exRelatedDiv -->
			
			
			<div id="exPlaylistDiv"  style="display: none;">
				Loading...
			</div> <!-- end exPlaylistDiv -->
			
			
			<div id="exUserDiv"  style="display: none">
			Loading...
			</div> <!-- end exUserDiv -->

		
	</div> <!-- end exploreDiv -->
	
	
	
</div> <!-- end aboutExploreDiv -->
</td>



<td>
<div id="sideAdDiv">
	<div id="dVidsDiv">
	<div class="heading label">Director Videos</div>
    <?php foreach($director_videos as $dvid) { ?>
		<div class="dvidEntry">
			<div><a href="/watch?v=<?php echo htmlspecialchars($dvid['video_id']); ?>&dpos=0" target="_parent"><img src="/get_still?video_id=<?php echo htmlspecialchars($dvid['video_id']); ?>" class="vimgSm"></a></div>
			<div class="title">
			<b><a href="/watch?v=<?php echo htmlspecialchars($dvid['video_id']); ?>&dpos=0" target="_parent"><?php echo htmlspecialchars($dvid['title']); ?></a></b><br/>
			<span class="runtime"><?php echo gmdate("i:s", $dvid['duration']); ?></span>
			</div>
			<div class="facets">
			<span class="grayText">From:</span> <?php echo htmlspecialchars($dvid['username']); ?>
			</div>
		</div>
		<?php } ?>
		</div>
	</div> <!-- end dVidsDiv -->
</div>
</td>

</tr></table>


<div style="clear: both;">&nbsp;</div>


<div style="margin-top: 12px; text-align: center;">
<!-- google_ad_section_start -->
 
 		<script language="JavaScript" type="text/javascript">
		<!--
			ctxt_ad_partner = "9090183920";
			ctxt_ad_section = "25783";
			ctxt_ad_bg = "";
			ctxt_ad_width = 728;
			ctxt_ad_height = 90;
			ctxt_ad_section = "31320"
			ctxt_ad_bc = "FFFFFF";
			ctxt_ad_cc = "FFFFFF";
			ctxt_ad_lc = "0000FF";
			ctxt_ad_tc = "000000";
			ctxt_ad_uc = "999999";
			//-->
		</script>
		<script type="text/javascript"><!--
			google_ad_client = "pub-6219811747049371";
			google_ad_width = 728;
			google_ad_height = 90;
				google_ad_format = "728x90_as_new";
			
			google_ad_type = "text_image";
			google_alternate_ad_url = "http://www.YoreTude.com/static_ad_728_90_tf.html";
			google_ad_channel ="6816329055";
			google_color_border = "FFFFFF";
			google_color_bg = "FFFFFF";
			google_color_link = "0033CC";
			google_color_url = "0033CC";
			google_color_text = "444444";
			google_kw_type = "broad";

				google_kw = "Music";
			
		//--></script>
				<script type="text/javascript"
				  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
				  </script>
 
	 
		
</div>

<?php 
require __DIR__ . "/includes/footer.php";
?>
 
