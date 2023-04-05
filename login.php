<?php
require __DIR__ . "/includes/header.php";

if(!empty($_SESSION)) {
	header("Location: browse");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
	if(isset($_POST['username']) && isset($_POST['password'])) {
		$member = $conn->prepare("SELECT member_id, username, password FROM members WHERE username LIKE :username");
		$member->execute([":username" => trim($_POST['username'])]);
		if($member->rowCount() > 0) {
			$member = $member->fetch(PDO::FETCH_ASSOC);
			if(password_verify(trim($_POST['password']), $member['password'])) {
				$_SESSION['member_id'] = $member['member_id'];
				header("Location: browse");
				$last_login = $conn->prepare("UPDATE members SET last_login = CURRENT_TIMESTAMP WHERE member_id = ?");
				$last_login->execute([$member['member_id']]);
			} else {
				$password_err = "Username or password is incorrect!";
			}
		} else {
			$username_err = "That user doesn't exist!";
		}
	}
}
?>
<div class="tableSubTitle">Log In</div>

<?php if (!empty($password_err)) { ?>
<table width="100%" align="center" bgcolor="#FF0000" cellpadding="6" cellspacing="3" border="0">
		<tbody><tr>
			<td align="center" bgcolor="#FFFFFF"><span class="error"><?php echo htmlspecialchars($password_err); ?><br>
		</span></td></tr>
	</tbody></table>
    <br>
<? } ?>
<?php if (!empty($username_err)) { ?>
<table width="100%" align="center" bgcolor="#FF0000" cellpadding="6" cellspacing="3" border="0">
		<tbody><tr>
			<td align="center" bgcolor="#FFFFFF"><span class="error"><?php echo htmlspecialchars($username_err); ?><br>
		</span></td></tr>
	</tbody></table>
    <br>
<? } ?>

<div id="siSignupDiv">
	<h4>New to YoreTude?</h4>
	
	<div id="siSignupDesc">
		YoreTude is a way to get your videos to the people who matter to you.<br/>
		With YoreTude you can:
		<ul>			
			<li>Upload, tag and share your videos worldwide
			<li>Browse thousands of original videos uploaded by community members
			<li>Find, join and create video groups to connect with people with similar interests
			<li>Customize your experience with playlists and subscriptions
			<li>Integrate YoreTude with your website using video embeds or APIs</li>
		</ul>
		
		<div id="siSignupLinkDiv" class="highlight"><a href="/signup">Sign up now</a>
		and open a free account.
		</div>
		
		To learn more about our service, please see our <a href="help">Help</a> section.
	</div>
</div>



<div id="siSigninDiv" class="lightFocusBox">
	<h4>YoreTude Members</h4>
	<div id="siSigninSubhead">
	Login to access your account
	</div>
	<table id="sisuLoginTable">
		<form name="loginForm" id="loginForm" method="post">
		<input type="hidden" name="current_form" value="loginForm" />
			
		
	
		
	
		
	
		
	
		
	

		<tr>
			<td align="right" class="label">	<nobr>User Name</nobr>
:</td>
			<td><input tabindex="1" type="text" size="20" name="username" value=""></td>
		</tr>
		<tr>
			<td align="right" class="label">	<nobr>Password</nobr>
:</td>
			<td><input tabindex="2" type="password" size="20" name="password"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="action_login" value="Log In"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
			<div id="sisuForgotDiv">
			<b>Forgot:</b>&nbsp;
			<a href="forgot_username">username</a> |
			<a href="/forgot">password</a>
			</div>
			</td>
		</tr>
		</form>

	</table>
</div>


<script type="text/javascript">
document.loginForm.username.focus();
</script></div> <!-- end baseDiv -->

<?php
require __DIR__ . "/includes/footer.php";
?>
