<?php
require __DIR__ . "/includes/header.php";
?>
<table align="center" cellpadding="0" cellspacing="0" border="0">
	<tbody><tr>
		<td><strong>About Us</strong></td>
		<td style="padding: 0px 5px 0px 5px;">|</td>
		<td><a href="about_news.php">In the News</a></td>
	</tr>
</tbody></table>
<div class="tableSubTitle">About Us</div>
<div class="pageTable">

<span class="highlight">What is YoreTude?</span>

<br><br>
YoreTude is a way to get your videos to the people who matter to you. With YoreTude you can:

<ul>
<li> Show off your favorite videos to the world
</li><li> Take videos of your dogs, cats, and other pets
</li><li> Blog the videos you take with your digital camera or cell phone
</li><li> Securely and privately show videos to your friends and family around the world
</li><li> ... and much, much more!
</li></ul>
<?php if(isset($session)) { ?>
				
				<?php } else { ?>
				<br><span class="highlight"><a href="signup.php">Sign up now</a> and open a free account.</span><br><br><br>
				<?php } ?>
To learn more about our service, please see our <a href="help.php">Help</a> section.<br><br>

Please feel free to <a href="contact.php">contact us</a>!<br><br><br>

</div>

<?php
require __DIR__ . "/includes/footer.php";
?>
