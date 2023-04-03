<?php
require __DIR__ . "/functions.php";

if(!isset($_SESSION)) {
	session_start();
	echo '<!-- fucking your mother -->';
}else{
	echo '<!-- doing your mother 2 -->';
}

$header_loaded = true;

preg_match('/^\/(\w+)/', $_SERVER['REQUEST_URI'], $matches, PREG_OFFSET_CAPTURE, 0);
if(empty($matches[0])) $matches[1][0] = "index"; // If the request URI is just "/", then we can just say it's "index"
$current_page = $matches[1][0]; // If the current page is "/index.php", $current_page will be "index".

// If a user is logged in, information about that user will be available in $session.
if(isset($_SESSION['member_id'])) {
	echo '<!-- logged in -->';
	$session = $conn->prepare("SELECT * FROM members WHERE member_id = ?");
	$session->execute([$_SESSION['member_id']]);
	if($session->rowCount() == 0) {
		unset($session);
		unset($_SESSION['member_id']);
		die();
	} else {
		$session = $session->fetch(PDO::FETCH_ASSOC);
	}
}else{
	echo '<!-- what the fuck -->';
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
	<meta name="keywords" content="video,sharing,camera phone,video phone,revival, recreation, YoreTude, 2005, yoretude ">
	
		<link rel="stylesheet" href="styles.css" type="text/css">
	<link rel="stylesheet" href="base.css" type="text/css">

<div id="baseDiv">
		
<div id="logoDiv"><a href="/"><img src="/img/logo_sm.gif" width="120" height="48" alt="YouTube" border="0"></a></div>
<div id="taglineDiv"><img src="/img/tagline-20pt.gif"></div>
<div id="utilDiv">
	<?php if(isset($session)) { ?>	
						<td><b>Hello, <a href="profile.php?user=<?php echo htmlspecialchars($session['username']); ?>"><?php echo htmlspecialchars($session['username']); ?></a></b>        
						| <img src="/img/mail.gif" border="0"> (<a href="./my_messages.php">0</a>)</td>
						<td style="padding: 0px 5px 0px 5px;">|</td>
						<td><a href="logout.php?next=<?php echo $_SERVER['REQUEST_URI'] ?>">Log Out</a></td>
						<td style="padding: 0px 5px 0px 5px;">|</td>
						<td style="padding-right: 5px;"><a href="help.php">Help</a></td>
	<?php } else { ?>
						<td><a href="signup.php"><strong>Sign Up</strong></a></td>
						<td style="padding: 0px 5px 0px 5px;">|</td>
						<td><a href="login.php">Log In</a></td>
						<td style="padding: 0px 5px 0px 5px;">|</td>
						<td style="padding-right: 5px;"><a href="help.php">Help</a></td>
	<?php } ?>
</div>
	<div id="searchDiv">
		<form name="searchForm" id="searchForm" method="GET" action="/results">
			<input tabindex="1" type="text" name="search" maxlength="128" id="searchField" value="<?php echo (isset($_GET['search'])) ? htmlspecialchars($_GET['search']) : ''; ?>">
			<select name="search_type">
			<option value="search_videos">Videos</option>
			<option value="search_users">Members</option>
			<option value="search_groups">Groups</option>
			<option value="search_playlists">Playlists</option>
			</select>
			<input type="submit" value="Search Videos">
		</form>
	</div>

<div id="globalNavDiv">
	<table width="875" cellpadding="0" cellspacing="0" border="0">
	<tbody><tr>
		<td>
		<table style=" background-color: #BECEEE; <?php echo ($current_page == "index") ? 'background-color: #DDDDDD;' : ''; ?> margin: 5px 2px 1px 0px; " cellpadding="0" cellspacing="0" border="0">
			<tbody><tr>
				<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
				<td><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
				<td style="padding-bottom: 5px; font-size: 13px; font-weight: bold; width:134px; text-align:center;"><a href="/index">Home</a></td>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
			</tr>
		</tbody></table>
		</td>
		<td>
		<table style=" background-color: #BECEEE; <?php echo ($current_page == "browse") ? 'background-color: #DDDDDD;' : ''; ?> margin: 5px 2px 1px 0px; " cellpadding="0" cellspacing="0" border="0">
			<tbody><tr>
				<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
				<td><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
				<td style="padding-bottom: 5px; font-size: 13px; font-weight: bold; width:134px; text-align:center;"><a href="/browse">Videos</a></td>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
			</tr>
		</tbody></table>
		</td>
		<td>
		<table style=" background-color: #BECEEE; <?php echo ($current_page == "channels") ? 'background-color: #DDDDDD;' : ''; ?> margin: 5px 2px 1px 0px; " cellpadding="0" cellspacing="0" border="0">
			<tbody><tr>
				<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
				<td><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
				<td style="padding-bottom: 5px; font-size: 13px; font-weight: bold; width:134px; text-align:center;"><a href="/channels">Channels</a></td>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
			</tr>
		</tbody></table>
		</td>
		<td>
		<table style=" background-color: #BECEEE; <?php echo ($current_page == "groups_main") ? 'background-color: #DDDDDD;' : ''; ?> margin: 5px 2px 1px 0px; " cellpadding="0" cellspacing="0" border="0">
			<tbody><tr>
				<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
				<td><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
				<td style="padding-bottom: 5px; font-size: 13px; font-weight: bold; width:134px; text-align:center;"><a href="/groups_main">Groups</a></td>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
			</tr>
		</tbody></table>
		</td>
		<td>
		<table style=" background-color: #BECEEE; <?php echo ($current_page == "members") ? 'background-color: #DDDDDD;' : ''; ?> margin: 5px 2px 1px 0px; " cellpadding="0" cellspacing="0" border="0">
			<tbody><tr>
				<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
				<td><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
				<td style="padding-bottom: 5px; font-size: 13px; font-weight: bold; width:134px; text-align:center;"><a href="/members">Members</a></td>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
			</tr>
		</tbody></table>
		</td>
		<td>
		<table style=" background-color: #BECEEE; <?php echo ($current_page == "my_videos_upload") ? 'background-color: #DDDDDD;' : ''; ?> margin: 5px 2px 1px 0px; " cellpadding="0" cellspacing="0" border="0">
			<tbody><tr>
				<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
				<td><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
				<td style="padding-bottom: 5px; font-size: 13px; font-weight: bold; width:135px; text-align:center;"><a href="/my_videos_upload">Upload</a></td>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
			</tr>
		</tbody></table>
		</td>
	</tr>
	</tbody></table>
</div> <!-- end globalNavDiv -->
<div id="globalSubNavDiv">
<table valign="top" align="center" width="875" bgcolor="#DDDDDD" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 10px;">
	<tbody><tr>
		<td><img src="/img/pixel.gif" width="5" height="5"></td>
		<td><img src="/img/pixel.gif" width="1" height="5"></td>
		<td><img src="/img/pixel.gif" width="5" height="5"></td>
	</tr>
	<tr>
		<td><img src="/img/pixel.gif" width="5" height="1"></td>
		<td width="865" align="center" style="padding: 2px;">
	
		<table cellpadding="0" cellspacing="0" border="0">
				<tbody><tr>
					<td style="font-size: 10px;">&nbsp;</td>
						<?php if($current_page === "index") { ?>				
				<td style="  "><a href="my_videos.php">My Videos</a></td>
				<td style="padding: 0px 10px 0px 10px;">|</td>
				<td style="  "><a href="my_favorites.php">My Favorites</a></td>
				<td style="padding: 0px 10px 0px 10px;">|</td>
				<td style="  "><a href="my_friends.php">My Friends</a></td>
				<!-- <td>&nbsp;|&nbsp;</td>
				<td style="  "><a href="my_groups.php">My Groups</a></td> -->
				<td style="padding: 0px 10px 0px 10px;">|</td>
				<td style="  "><a href="my_messages.php" >My Messages</a></td>
				<td style="padding: 0px 10px 0px 10px;">|</td>
				<td style="  "><a href="my_profile.php">My Profile</a></td>
                <?php if($session['administrator'] == 1) {?>
                <td style="padding: 0px 10px 0px 10px;">|</td>
				<td style="  "><strong><a href="admin.php">Admin Panel</a></strong></td>
				<?php } ?>
                <?php } ?>
                <?php if($current_page === "browse") { ?>				
				<td style="font-size: 10px;">&nbsp;</td>
				<td><a href="/browse.php?s=mr">Most Recent</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=mp">Most Viewed</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=md">Most Discussed</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=mf" >Top Favorites</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=tr">Top Rated</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=rf">Recently Featured</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=r">Random</a></td>
				<td style="font-size: 10px;">&nbsp;</td>
                <?php } ?>
                <?php if($current_page === "watch") { ?>				
				
                   
			
				<td style="font-size: 10px;">&nbsp;</td>
				<td><a href="/browse.php?s=mr">Most Recent</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=mp">Most Viewed</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=md">Most Discussed</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=mf">Top Favorites</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=tr">Top Rated</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=rf">Recently Featured</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=r">Random</a></td>
				<td style="font-size: 10px;">&nbsp;</td>
                <?php } ?>
					<td style="font-size: 10px;">&nbsp;</td>
				</tr>
		</tbody></table>
				
		</td>
		<td><img src="/img/pixel.gif" width="5" height="1"></td>
	</tr>
	<tr>
		<td style="border-bottom: 1px solid #FFFFFF"><img src="/img/box_login_bl.gif" width="5" height="5"></td>
		<td style="border-bottom: 1px solid #BBBBBB"><img src="/img/pixel.gif" width="1" height="5"></td>
		<td style="border-bottom: 1px solid #FFFFFF"><img src="/img/box_login_br.gif" width="5" height="5"></td>
	</tr>
</tbody></table>
</div> <!-- end globalSubNavDiv -->
