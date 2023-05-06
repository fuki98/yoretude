<?php
$down = "0";
if(!isset($_COOKIE['debug']) AND $down == "1") {
	require 'errors/maintenance.php';
	die();
}
require __DIR__ . "/functions.php";

preg_match('/^\/(\w+)/', $_SERVER['REQUEST_URI'], $matches, PREG_OFFSET_CAPTURE, 0);
if(empty($matches[0])) $matches[1][0] = "index"; // If the request URI is just "/", then we can just say it's "index"
$current_page = $matches[1][0]; // If the current page is "/index.php", $current_page will be "index".

// If a user is logged in, information about that user will be available in $session.
if(isset($_SESSION['member_id'])) {
	$session = $conn->prepare("SELECT * FROM members WHERE member_id = ?");
	$session->execute([$_SESSION['member_id']]);
	if($session->rowCount() == 0) {
		header("Location: logout.php");
		die();
	} else {
		$session = $session->fetch(PDO::FETCH_ASSOC);
	}
}
// Banning in general
 if($session['banned'] == 1) {
    die("You are banned!");
}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>YoreTude - Rebroadcast Yourself.</title>
		<meta name="description" content="Share your videos with friends and family">
	<meta name="keywords" content="video,sharing,camera phone,video phone,revival, recreation, YoreTude, 2005, yoretude, nostalgia, youtube in 2005, 2006, youtube in 2006, youtube in 2008, pre-google youtube, star ratings, zippcast, youtube, old websites, yesterweb, 2008, 2009, 2010, 2007, youtube evolution, time machine, youtube time machine, google, jawed karim, chad hurley, steve chen, me at the zoo, my snowboarding skillz, bootiechrist dance">
	
		<link rel="stylesheet" href="/styles.css" type="text/css">
	<link rel="stylesheet" href="/base.css" type="text/css">

<div id="baseDiv">
<div id="logoTagDiv"><a href="/"><img src="/img/logo_tagline_sm.gif" alt="YoreTude" width="250" height="48" border="0"></a></div>
<div id="utilDiv">
    <?php if(isset($session)) { ?>	
						<b>Hello, <a href="profile?user=<?php echo htmlspecialchars($session['username']); ?>"><?php echo htmlspecialchars($session['username']); ?></a></b>    
    <img id="mailico" src="/img/mail.gif" border="0"> (<a href="/my_messages">0</a>)
    <span class="utilDelim">|</span>
	<a href="//logout?next=<?php echo $_SERVER['REQUEST_URI'] ?>">Log Out</a>
	<span class="utilDelim">|</span>
	<a href="/t/help_center">Help</a> <?php if($session['administrator'] == 1) {?><a href="admin.php" class="bold">Admin</a> <?php } ?>
						<?php } else { ?>
							<a href="/signup"><strong>Sign Up</strong></a>
	<span class="utilDelim">|</span>
	<a href="/login">Log In</a>
	<span class="utilDelim">|</span>
	<a href="/t/help_center">Help</a>
		                                <?php } ?>
</div><!-- end utilDiv -->

	<div id="searchDiv">
		<form name="searchForm" id="searchForm" method="GET" action="/results">
			<input tabindex="1" type="text" name="search" maxlength="128" id="searchField" value="<?php echo (isset($_GET['search'])) ? htmlspecialchars($_GET['search']) : ''; ?>">
			<select name="search_type">
			<option value="search_videos">Videos</option>
			<option value="search_users">Members</option>
			<option value="search_groups">Groups</option>
			<option value="search_playlists">Playlists</option>
			</select>
			<input type="submit" value="Search">
		</form>
	</div> <!-- end searchDiv -->


<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr>
<td><div id="gNavDiv">

<!-- thank you Spiffy Corners (http://www.spiffycorners.com/) for the rounded corner goodness.  RESPECT!  -->

		
		<div class="ltab">
		<b class="rc<?php if ($current_page == "index") { ?>s<?php } ?>">
		<b class="rc<?php if ($current_page == "index") { ?>s<?php } ?>1"><b></b></b>
		<b class="rc<?php if ($current_page == "index") { ?>s<?php } ?>2"><b></b></b>
		<b class="rc<?php if ($current_page == "index") { ?>s<?php } ?>3"></b>
		<b class="rc<?php if ($current_page == "index") { ?>s<?php } ?>4"></b>
		<b class="rc<?php if ($current_page == "index") { ?>s<?php } ?>5"></b>
		</b> <div class="tabContent <?php if ($current_page == "index") { ?> selected <?php } ?>">
		<a href="/index">Home</a>
		</div>
		</div>
		
		
		<div class="tab">
		<b class="rc<?php if ($current_page == "browse" OR $current_page == "watch") { ?>s<?php } ?>">
		<b class="rc<?php if ($current_page == "browse" OR $current_page == "watch") { ?>s<?php } ?>1"><b></b></b>
		<b class="rc<?php if ($current_page == "browse" OR $current_page == "watch") { ?>s<?php } ?>2"><b></b></b>
		<b class="rc<?php if ($current_page == "browse" OR $current_page == "watch") { ?>s<?php } ?>3"></b>
		<b class="rc<?php if ($current_page == "browse" OR $current_page == "watch") { ?>s<?php } ?>4"></b>
		<b class="rc<?php if ($current_page == "browse" OR $current_page == "watch") { ?>s<?php } ?>5"></b>
		</b> <div class="tabContent <?php if ($current_page == "browse" OR $current_page == "watch") { ?> selected <?php } ?>">
		<a href="/browse?s=mp">Videos</a>
		</div>
		</div>
		
		
		<div class="tab">
		<b class="rc<?php if ($current_page == "categories") { ?>s<?php } ?>">
		<b class="rc<?php if ($current_page == "categories") { ?>s<?php } ?>1"><b></b></b>
		<b class="rc<?php if ($current_page == "categories") { ?>s<?php } ?>2"><b></b></b>
		<b class="rc<?php if ($current_page == "categories") { ?>s<?php } ?>3"></b>
		<b class="rc<?php if ($current_page == "categories") { ?>s<?php } ?>4"></b>
		<b class="rc<?php if ($current_page == "categories") { ?>s<?php } ?>5"></b>
		</b> <div class="tabContent<?php if ($current_page == "categories") { ?> selected <?php } ?>">
		<a href="/categories">Categories</a>
		</div>
		</div>
		
		
		<div class="tab">
		<b class="rc<?php if ($current_page == "groups") { ?>s<?php } ?>">
		<b class="rc<?php if ($current_page == "groups") { ?>s<?php } ?>1"><b></b></b>
		<b class="rc<?php if ($current_page == "groups") { ?>s<?php } ?>2"><b></b></b>
		<b class="rc<?php if ($current_page == "groups") { ?>s<?php } ?>3"></b>
		<b class="rc<?php if ($current_page == "groups") { ?>s<?php } ?>4"></b>
		<b class="rc<?php if ($current_page == "groups") { ?>s<?php } ?>5"></b>
		</b> <div class="tabContent<?php if ($current_page == "groups") { ?> selected <?php } ?>">
		<a href="/groups_main">Groups</a>
		</div>
		</div>
		
		
		<div class="tab">
		<b class="rc<?php if ($current_page == "members") { ?>s<?php } ?>">
		<b class="rc<?php if ($current_page == "members") { ?>s<?php } ?>1"><b></b></b>
		<b class="rc<?php if ($current_page == "members") { ?>s<?php } ?>2"><b></b></b>
		<b class="rc<?php if ($current_page == "members") { ?>s<?php } ?>3"></b>
		<b class="rc<?php if ($current_page == "members") { ?>s<?php } ?>4"></b>
		<b class="rc<?php if ($current_page == "members") { ?>s<?php } ?>5"></b>
		</b> <div class="tabContent">
		<a href="/members?ms30">Members</a>
		</div>
		</div>
		
		
		<div class="rtab">
		<b class="rc<?php if ($current_page == "my_videos_upload" OR $current_page == "my_videos_upload_2") { ?>s<?php } ?>">
		<b class="rc<?php if ($current_page == "my_videos_upload" OR $current_page == "my_videos_upload_2") { ?>s<?php } ?>1"><b></b></b>
		<b class="rc<?php if ($current_page == "my_videos_upload" OR $current_page == "my_videos_upload_2") { ?>s<?php } ?>2"><b></b></b>
		<b class="rc<?php if ($current_page == "my_videos_upload" OR $current_page == "my_videos_upload_2") { ?>s<?php } ?>3"></b>
		<b class="rc<?php if ($current_page == "my_videos_upload" OR $current_page == "my_videos_upload_2") { ?>s<?php } ?>4"></b>
		<b class="rc<?php if ($current_page == "my_videos_upload" OR $current_page == "my_videos_upload_2") { ?>s<?php } ?>5"></b>
		</b> <div class="tabContent <?php if ($current_page == "my_videos_upload" OR $current_page == "my_videos_upload_2") { ?> selected <?php } ?>">
		<a href="/my_videos_upload">Upload</a>
		</div>
		</div>
		
</div></td> <!-- end gNavDiv -->
</tr><tr>
<td><div id="gSubNavDiv">
	&nbsp;
		<?php if($current_page === "index") { ?>				
				<span style="  "><a href="my_videos.php">My Videos</a></span>
				<span style="padding: 0px 10px 0px 10px;">|</span>
				<span style="  "><a href="my_favorites.php">My Favorites</a></span>
				<span style="padding: 0px 10px 0px 10px;">|</span>
				<span style="  "><a href="my_friends.php">My Friends</a></span>
				<!-- <span>&nbsp;|&nbsp;</span>
				<span style="  "><a href="my_groups.php">My Groups</a></span> -->
				<span style="padding: 0px 10px 0px 10px;">|</span>
				<span style="  "><a href="my_messages.php" >My Messages</a></span>
				<span style="padding: 0px 10px 0px 10px;">|</span>
				<span style="  "><a href="my_profile.php">My Profile</a></span>
                <?php if($session['administrator'] == 1) {?>
                <span style="padding: 0px 10px 0px 10px;">|</span>
				<span style="  "><strong><a href="admin.php">Admin Panel</a></strong></span>
				<?php } ?>
                <?php } ?>
                <?php if($current_page === "browse") { ?>				
				<span style="font-size: 10px;">&nbsp;</span>
				<span><a href="/browse.php?s=mr">Most Recent</a></span>
					<span style="padding: 0px 10px 0px 10px;">|</span>
				<span><a href="/browse.php?s=mp">Most Viewed</a></span>
					<span style="padding: 0px 10px 0px 10px;">|</span>
				<span><a href="/browse.php?s=md">Most Discussed</a></span>
					<span style="padding: 0px 10px 0px 10px;">|</span>
				<span><a href="/browse.php?s=mf" >Top Favorites</a></span>
					<span style="padding: 0px 10px 0px 10px;">|</span>
				<span><a href="/browse.php?s=tr">Top Rated</a></span>
					<span style="padding: 0px 10px 0px 10px;">|</span>
				<span><a href="/browse.php?s=rf">Recently Featured</a></span>
					<span style="padding: 0px 10px 0px 10px;">|</span>
				<span><a href="/browse.php?s=r">Random</a></span>
				<span style="font-size: 10px;">&nbsp;</span>
                <?php } ?>
                <?php if($current_page === "watch") { ?>				
				
                   
			
				<span style="font-size: 10px;">&nbsp;</span>
				<span><a href="/browse.php?s=mr">Most Recent</a></span>
					<span style="padding: 0px 10px 0px 10px;">|</span>
				<span><a href="/browse.php?s=mp">Most Viewed</a></span>
					<span style="padding: 0px 10px 0px 10px;">|</span>
				<span><a href="/browse.php?s=md">Most Discussed</a></span>
					<span style="padding: 0px 10px 0px 10px;">|</span>
				<span><a href="/browse.php?s=mf">Top Favorites</a></span>
					<span style="padding: 0px 10px 0px 10px;">|</span>
				<span><a href="/browse.php?s=tr">Top Rated</a></span>
					<span style="padding: 0px 10px 0px 10px;">|</span>
				<span><a href="/browse.php?s=rf">Recently Featured</a></span>
					<span style="padding: 0px 10px 0px 10px;">|</span>
				<span><a href="/browse.php?s=r">Random</a></span>
				<span style="font-size: 10px;">&nbsp;</span>
                <?php } ?>
	&nbsp;
</div></td> <!-- end gSubNavDiv -->
</tr></table>
