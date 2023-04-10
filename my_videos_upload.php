<?php
require __DIR__ . "/includes/header.php";

if (!isset($_SESSION['member_id'])) {
    die(header("Location: /login.php"));
}

?>

<script>

function UploadHandler() {
	const form = document.getElementById('uploadForm');
	
	// TITLE
	if (form.elements['title'].value.length == 0 || form.elements['title'].value == null)
	{
		alert("You must enter a title!");
		form.elements['title'].focus();
		return false;
	}
	if (form.elements['title'].value.length > 64)
	{
		alert("Your title must be shorter than 64 characters!");
		form.elements['title'].focus();
		return false;
	}
	
	// DESCRIPTION
	if (form.elements['description'].value.length == 0 || form.elements['description'].value == null)
	{
		alert("You must enter a description!");
		form.elements['description'].focus();
		return false;
	}
	if (form.elements['description'].value.length > 500)
	{
		alert("Your description must be shorter than 500 characters!");
		form.elements['description'].focus();
		return false;
	}
	
	// TAGS
	if (form.elements['tags'].value.length == 0 || form.elements['tags'].value == null)
	{
		alert("You must enter tags!");
		form.elements['tags'].focus();
		return false;
	}
	if (form.elements['tags'].value.length > 300)
	{
		alert("Your tags must be shorter than 300 characters!");
		form.elements['tags'].focus();
		return false;
	}
	
	return true;
}

</script>

<b style="font-size:150%">Video Upload (Step 1 of 2)</b>&ensp; (All fields required)<p>

<div class="formTable">
    <form name="uploadForm" id="uploadForm" method="post" action="my_videos_upload_2.php" enctype="multipart/form-data" onsubmit="return UploadHandler();">
        <table cellpadding="5" cellspacing="0" border="0" align="center">
				<tr>
					<td align="right"><span class="label">Title:</span></td>
					<td><input type="text" size="50" maxlength="64" name="title" value=""></td>
                </tr>
                <tr>
                    <td align="right"><span class="label">Description:</span></td>
                    <td><textarea name="description" maxlength="500" cols="55" rows="3"></textarea></td>
                </tr>
                <tr>
                    <td align="right"><span class="label">Tags:</span></td>
					<td><input type="text" size="50" maxlength="255" name="tags" value=""></td>
				<tr>
					<td></td>
					<td><b>Enter a list of one or more tags, separated by spaces.</b><br>Tags are keywords used to describe your video so it can be easily found by other users. For example, if you have a surfing video, you might tag it: <code>surfing beach waves</code></td>
				</tr><tr>
                    <td><span class="label">Video Category:</span></td>
                    <td>
<input type="radio" id="1" name="cat" value="1" checked="checked"><label for="1">Arts and Animation</label>&emsp;&emsp;&emsp;&emsp;<input type="radio" id="2" name="cat" value="2"><label for="2">Comedy</label><br>
<input type="radio" id="3" name="cat" value="3"><label for="3">Comedy</label>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;&emsp;<input type="radio" id="4" name="cat" value="4"><label for="4">Entertainment</label><br>
<input type="radio" id="5" name="cat" value="5"><label for="5">Music</label>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;&emsp;<input type="radio" id="6" name="cat" value="6"><label for="6">People</label><br>
<input type="radio" id="7" name="cat" value="7"><label for="7">Science and Technology</label>&emsp;&nbsp;<input type="radio" id="8" name="cat" value="8"><label for="8">Travel & Places</label><br>
<input type="radio" id="9" name="cat" value="9"><label for="9">News & Blogs</label>&nbsp;&nbsp;&nbsp;&emsp;&emsp;&emsp;&emsp;&emsp;<input type="radio" id="10" name="cat" value="10"><label for="10">Pets & Animals</label><br>
<input type="radio" id="11" name="cat" value="11"><label for="11">Sports</label>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<input type="radio" id="12" name="cat" value="12"><label for="12">Video Games</label><p>
<b>Select a category that best describes your video.</b>
</td>
				</tr>
				<tr>
					<td colspan="2"></td>
				</tr>
                <tr>
                    <td></td>
                    <td><h2>Do not upload copyrighted material for which you don't own the rights or have permission from the owner.</h2><input type="submit" id="continue" name="continue" value="Continue ->"></td>
                </tr>
                <?php if(isset($_SESSION['error'])) { ?>
                    <tr>
                        <td>
                            <p style="color: #ff0000;"><?php echo htmlspecialchars($_SESSION['error']) ?></p>
                        </td>
                    </tr>
                <?php unset($_SESSION['error']); } ?>
        </table>
    </form>
</div>

<?php
require __DIR__ . "/includes/footer.php";
?>
