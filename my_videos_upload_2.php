<?php
require __DIR__ . "/includes/header.php";

if (!isset($_SESSION['member_id'])) {
    die(header("Location: /login.php"));
}

if($_SERVER['REQUEST_METHOD'] != 'POST') {
    die(header("Location: /my_videos_upload.php"));
} 
?>


<h1>Video Upload (Step 2 of 2)</h1>

<div class="formTable">
    <form name="uploadForm" id="uploadForm" method="POST" action="my_videos_upload_post.php" enctype="multipart/form-data">
        <input type="hidden" name="title" value="<?php echo htmlspecialchars($_POST["title"]); ?>" hidden>
        <input type="hidden" name="description" value="<?php echo htmlspecialchars($_POST["description"]); ?>">
        <input type="hidden" name="tags" value="<?php echo htmlspecialchars($_POST["tags"]); ?>">
        <input type="hidden" name="cat" value="<?php echo htmlspecialchars($_POST["cat"]); ?>">
        <table cellpadding="5" cellspacing="0" border="0" align="center">
			<tr>
				<td align="right" valign="top"><span class="label">File:</span></td>
				<td style="background-color: #e0eeff;padding:8px;border-style: dotted;border: 1;border: 2px dotted #0026b9;">
					<input type="file" name="fileToUpload" id="fileToUpload" style="color:black;background-color:white;border: 1px solid #000000;border-radius:0px;" accept="video/*" required="">
				<p><a href="/">Click here</a> if you are having problems with the uploader.<p><b>Max size: 100 MB. Max length: 10 minutes.</b>
                <p><b>Do not upload copyrighted, obscene or any other material which violates YoreTude's Terms of Use.</b> By clicking "Upload Video", you are representing that this video does <br>not violate YoreTude's terms of use and that you own all copyrights in this video or <br>have express permission from the copyright owner(s) to upload it.<p>Read <a href="/">Copyright Tips</a> for more information about copyright and YoreTude's policy.
				</td>
			</tr>
			<tr>
				<td colspan="2"></td>
			</tr>
			<tr>
				<td></td>
				<td><h2>Do not upload any TV shows, music videos, music concerts, or commercials<br> without permission unless they consist entirely of <br>content you created yourself.</h2></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" id="upload" name="upload" value="Upload Video"></td>
			</tr>
            <tr>
				<td></td>
				<td><h3>PLEASE BE PATIENT, THIS MAY TAKE SEVERAL MINUTES.<br>ONCE COMPLETED, YOU WILL SEE A CONFIRMATION MESSAGE.</h3></td>
			</tr>
        </table>
    </form>
</div>

<?php
require __DIR__ . "/includes/footer.php";
?>
