<?php
require __DIR__ . "/includes/header.php";

if(isset($_GET['v'])) {
	header("Location: watch.php?v=".$_GET['v'], true, 303);
	die();
}
?>
<script>
  >
</script>
<!DOCTYPE HTML>
<div class="pageTitle">Contact Us</div>

<div class="pageTable">

<table width="780">
	<tr>
		<td width="100%" valign="top">
			
			<div style="padding-left: 23px;">
			YoreTude is located in sunny San Mateo, California, USA.
			<br><br>If you have questions or comments for us, please fill out the form below.
			<br><br>If this is a product or technical question, please provide as much detail as possible.
			<li>What is your YoreTude username?
			<li>What exactly were you trying to do? (provide full URL)
			<li>What is the exact page you received? (copy and paste any error messages)
			</div>
			
			<br/>
			
			<table width="100%" cellpadding="5" cellspacing="0" border="0">
			<form method="post" action="contact2.php">
			<tr>
				<td width="75" align="right"><span class="label">Your Email:</span></td>
				<td><input type="text" size="30" maxlength="60" name="contact_email" value=""></td>
			</tr>
			<tr>
				<td align="right"><span class="label">Subject:</span></td>

				<td>
					<select name="contact_subject">
					<option value="0">---</option>
					<option value="1">Product Question</option>
					<option value="2">Business Inquiry</option>
					<option value="3">Marketing and Advertising Inquiry</option>
					<option value="4">Developer Question</option>
					<option value="5">Press Inquiry</option>
					<option value="6">Job Inquiry</option>
					<option value="7">Other</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" valign="top"><span class="label">Message:</span></td>
				<td><textarea name="contact_message" cols="40" rows="10"></textarea></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="submit" value="Send Message"></td>
				</form>
			
		</table>
	
	</td>
</tr>
</table>
</div>
</html>

<?php
require __DIR__ . "/includes/footer.php";
?>
