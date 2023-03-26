<?php
require __DIR__ . "/includes/header.php";

$profile = $conn->prepare("SELECT * FROM members WHERE members.username = ?");
$profile->execute([$_GET['user']]);

if($profile->rowCount() == 0) {
	require __DIR__ . "/errors/404.php";
} else {
	$profile = $profile->fetch(PDO::FETCH_ASSOC);
}

$profile['videos'] = $conn->prepare("SELECT video_id FROM videos WHERE member_id = ? AND converted = 1");
$profile['videos']->execute([$profile["member_id"]]);
$profile['videos'] = $profile['videos']->rowCount();
$profile['favorites'] = $conn->prepare("SELECT favorite_id FROM favorites WHERE member_id = ?");
$profile['favorites']->execute([$profile["member_id"]]);
$profile['favorites'] = $profile['favorites']->rowCount();

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
}
if(!empty($_SESSION)) {
	header("Location: index.php");
}

// Define variables and initialize with empty values
$username = $password = $confirm_password = $email = "";
$username_err = $password_err = $confirm_password_err = $email_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else if(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else {
        // Prepare a select statement and bind variables to the prepared statement as parameters
        $param_username = trim($_POST["username"]);
        $stmt = $conn->prepare("SELECT member_id FROM members WHERE username = :username");
        $stmt->execute([
            ':username' => $param_username,
        ]);
        if($stmt->rowCount() > 0){
            $username_err = "This username is already taken.";
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 8){
        $password_err = "Password must have atleast 8 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
	
	// Validate email
	if(empty(trim($_POST['email']))) {
		$email_err = "Please enter an email.";
	} elseif(!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
		$email_err = "Please enter a valid email.";
	} else {
		$param_email = trim($_POST['email']);
		
		// Prepare a select statement and bind variables to the prepared statement as parameters
		$email_in_use = $conn->prepare("SELECT member_id FROM members WHERE email = ?");
		$email_in_use->execute([$param_email]);
		if($email_in_use->rowCount() > 0) {
			$email_err = "This email address is already in use.";
		}
	}
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)){ 
		// Set parameters
		$param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

		$stmt = $conn->prepare("INSERT INTO members (username, password, email, ip) VALUES (:username, :password, :email, :ip)");
		$stmt->execute([
			':username' => $param_username,
			':password' => $param_password,
			':email' => $param_email,
			':ip' => $_SERVER['REMOTE_ADDR'] 
		]);

		// Redirect to login page
		header("location: login.php");
    }
}
?>
<center><b><?php echo htmlspecialchars($profile["username"]); ?></b> has invited you to sign up for YoreTude!</center>
<div class="formTable">				
	<form method="post" action="signup.php">
		<table width="720" cellpadding="5" cellspacing="0" border="0">
			<tr>
				<td width="200" align="right"><span class="label">Email Address:</span></td>
				<td><input type="text" size="30" maxlength="60" name="email" value=""></td>
			</tr>
			<tr>
				<td align="right"><span class="label">User Name:</span></td>
				<td><input type="text" size="20" maxlength="20" name="username" value=""></td>
			</tr>
			<tr>
				<td align="right"><span class="label">Password:</span></td>
				<td><input type="password" size="20" maxlength="20" name="password" value=""></td>
			</tr>
			<tr>
				<td align="right"><span class="label">Retype Password:</span></td>
				<td><input type="password" size="20" maxlength="20" name="confirm_password" value=""></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><br>- I certify I am over 13 years old.
				<br>- I agree to the <a href="terms.php" target="_blank">terms of use</a> and <a href="privacy.php" target="_blank">privacy policy</a>.</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" value="Sign Up"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><br>Or, <a href="index.php">return to the homepage</a>.</td>
			</tr>
		</table>
	</form>
</div>    
<?php
require __DIR__ . "/includes/footer.php";
?>
	<footer>
