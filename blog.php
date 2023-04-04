<?php
require __DIR__ . "/includes/header.php";

if($session['administrator'] == 1 && isset($_POST['title'])) {
  // Prepare the SQL query to insert a new blog post
  $sql = "INSERT INTO blogs (title, content, author) VALUES (:title, :content, :author)";

  // Bind the parameters to the prepared statement
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(":title", $_POST['title']);
  $stmt->bindParam(":content", $_POST['content']);
  $stmt->bindParam(":author", $session['username']);
  
  // Execute the prepared statement
  try {
    $stmt->execute();
    echo "Blog post added successfully!";
  } catch (PDOException $e) {
    echo "Error adding blog post: " . $e->getMessage();
    exit;
  }
}
?>

<style>
.tableSubContent {
  padding: 0px 0px 15px 15px;
}
</style>

<div class="pageTable" bis_skin_checked="1">

  <?php if($session['administrator'] == 1) { ?>
    <h2>Add new blog post</h2>
    <form method="post">
      <div>
        <input type="text" name="title" id="title" required placeholder="Title">
      </div>
      <div>
        <textarea name="content" id="content" rows="5" required placeholder="Content"></textarea>
      </div>
      <button type="submit">Submit</button>
    </form>
    <hr>
  <?php } ?><?php
	echo '<!-- hi -->';
	$stmt = $conn->query("SELECT * FROM blogs ORDER BY date DESC");
	echo '<!-- hi1 -->';
	// Loop through the results and display each post
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$title = $row['title'];
		$content = $row['content'];
		$author = $row['author'];
	?>
	<div class="tableSubTitle"><?php echo $title; ?></div>
	<div class="tableSubContent"><?php echo $content; ?><br><blockquote>- <a href="/user/<?php echo $author ?>"><?php echo $author ?></a><blockquote></div>
	<?php }
	echo '<!-- hi2 -->';
	?>
		<div class="tableSubTitle" bis_skin_checked="1">April 1, 2006</div>
	<div class="tableSubContent" bis_skin_checked="1"><b>WE HAVE BEEN BOUGHT OUT!</b>
    <br>"Google is excited to announce the acquisition of Yoretude, a dynamic video streaming platform that we believe will greatly enhance the user experience of our customers. <br>In an effort to unify our brand and make our services more accessible, we have decided to rename Yoretude to Google Video. <br>The integration of Yoretude's cutting-edge technology and highly skilled team into Google will undoubtedly strengthen our position as a leading tech company and allow us to further innovate in the video streaming market. <br>With Google Video, we are confident that we will continue to provide our customers with the highest quality video content, and we look forward to the future of this exciting partnership."
<blockquote>- Sundar Pichai</blockquote>
</div>
		<div class="tableSubTitle" bis_skin_checked="1">March 25th, 2006</div>
	<div class="tableSubContent" bis_skin_checked="1">The revival that nobody needed but happened anyways.
    <br>That's right! After critical acclaim of what was <b>supposed</b> to be a joke with a friend, YoreTude is offically back for good. This time, I will promise to properly moderate it, keep it secure, and always be active.
    <br>My friend <b>qfx</b> now co-owns the site and me and them will be working to give you a great revival experience.
    <br>I'll remind you that the site was shut down circa October, so it'll take a while for us to catch up to the timeline.
    <br>I will try to implement some of the beta 2006 work I did.
    <br>Thanks everyone for the support through the dark times, and <s>stay tudy</s>.
    <br>-jr
</div>
		<div class="tableSubTitle" bis_skin_checked="1">October 1st, 2005</div>
	<div class="tableSubContent" bis_skin_checked="1">Hi! It's been 2 months since we last updated this thing. Since then, we have pushed out many updates - check them out!
		<ul>
<li><b>Actual embedding</b><br>
	The new embed code works the same way as the normal player now! The embed player was previously just a modified flash player, now just like the player you see on the watch page it gives you a compatible player depending on what your browser supports. Sorry for the inconvience!
<br>
	</li>
<li><b>Monthly Contests!</b><br>
	A video of a man dancing certainly inspired us alot! That's right, our first contest is here! Simply upload a video of you dancing in various locations and you can have a very great chance of being featured!
<br>
	</li>
		</ul>
	</div>
	<div class="tableSubTitle" bis_skin_checked="1">August 12th, 2005</div>
	<div class="tableSubContent" bis_skin_checked="1"> Whew! A couple busy, busy weeks. These are some of the changes we've made:
		<ul>
<li><b>New front page</b><br>
	We have redesigned the front page to better capture what YoreTude is about. The featured videos lend an instant snapshot of the cool videos added recently to the site. Upload <i>your</i> interesting video and watch it get featured!
<br>
	</li>
<li><b>Monthly Contests!</b><br>
	A video of a man dancing certainly inspired us alot! That's right, our first contest is here! Simply upload a video of you dancing in various locations and you can have a very great chance of being featured!
<br>
	</li>
		</ul>
	</div>
	<div class="tableSubTitle" bis_skin_checked="1">August 11th, 2005</div>
	<div class="tableSubContent" bis_skin_checked="1">Yesterday, a lot happened. We now have to use a new database - so if you don't seem to be able to login, clear your cookies for YoreTude.com and create a new account. Also, the layout was updated early yesterday.
	</div>
	<div class="tableSubTitle" bis_skin_checked="1">August 10th, 2005</div>
	<div class="tableSubContent" bis_skin_checked="1">
		There has been no updates for a while, but we do want to tell you that in 2 days, the layout will change! It will be updated to show off the best of YoreTude and be more precise. Some new features will come with this update.
		<ul>
			<li><b>Public &amp; Private Videos</b><br>Apparently this feature has existed since launch. Weird. But it will be introduced with the update here since Friends are coming also with it (and this makes it way, way easier to view Private Videos).</li><br>
			<li><b>Video Sharing</b><br>On YoreTude you've always been able to upload Public and Private videos. Public videos are for everyone to see, and Private videos are only seen by the people that get the secret link URL to that video.
<br>
Now you can share videos more easily with friends and family by inviting them directly through the My Friends tab, and by adding them to your Friends or to your Family groups.

For each video that you uploaded to YoreTude, you can decide to share the video with Friends, Family or both.<br>
			</li><li><b>Show related video in comments</b><br>While browsing through the site, we've seen that many users reference videos they've posted when they leave comments in other people's videos. To help this effort, we will push out a new feature that allows you to reference one of your videos directly from the comments section.</li><br>
			<p></p>
	</ul></div>
	<div class="tableSubTitle" bis_skin_checked="1">August 5th, 2005</div>
	<div class="tableSubContent" bis_skin_checked="1">
		<p>You know, I really can't believe the pace that we're releasing new features. I mean, it really does seem like like just yesterday we launched something and, yet, here we are again... back with more.
			<br>And since the last two days, here they are:
		</p><ul>
			<li><b>Editing Videos</b><br>You can now easily go to My Videos in the header. You can edit your video information and remove videos you want to forget. Handy, huh?</li><br>
			<li><b>RSS</b><br>There is now a RSS feed so you can look at the latest videos on a certain subject without <i>being</i> on YoreTude.</li><br>
			<li><b>Search</b><br>You can now search for videos by tags! For example, search up Scratch and you'll see my two videos on the first versions of Scratch.</li><br>
			<p></p>
	</ul></div>
	<div class="tableSubTitle" bis_skin_checked="1">August 2nd, 2005</div>
	<div class="tableSubContent" bis_skin_checked="1">
		<p>Related videos have been implemented and the logo has been given a slight makeover. Also tags were added to the index so this outside doesn't look unfinished af on the outside anymore.</p>
	</div>
	
	<div class="tableSubTitle" bis_skin_checked="1">August 1st, 2005</div>
	<div class="tableSubContent" bis_skin_checked="1">
		<p>More things added, more sleepless nights. <b>Jacob</b> has added some more things to this website:
		</p><ul>
			<li><b>Videos</b><br>That's right, videos have finally been implemented <i>(which is ironic since we're running a video sharing website)</i>. Not only does the video page finally work, but many things related to it now do too - featured videos now work, including comments, you can even share videos now for all we care - and now a convenient <a href="browse.php">browse</a> page. From here, you can find the <a href="browse.php">most recent</a> videos, the <a href="browse.php?s=mp">most popular</a> videos, the <a href="browse.php?s=md">most discussed</a> videos, the <a href="browse.php?s=mf">most added to favorites</a> videos, and a <a href="browse.php?s=r">random</a> browse page. And now, as of 6:19 PM, you can now upload! Woohoo!</li><br>
			<li><b>Profiles</b><br>That's right! You can now see channels! <a href="profile.php?user=jr">Here is mine as an example.</a> It contains everything you need to know about someone!</li><br>
			<li><b>Sessions</b><br>That's right! You can finally log in. So if you have an account, log in to see this stuff in action!</li>
		</ul>
		<p>That's all for now. Keep following all the stuff we do, this thing is actually supposed to come out this month! As always, thank you for visiting YoreTude. Your support means everything to us</p>
	</div>

	<div class="tableSubTitle" bis_skin_checked="1">July 30th, 2005</div>
	<div class="tableSubContent" bis_skin_checked="1">
		<p>This is the first update from Billygoat891. Anyways, we now use PDO instead of MySQLi since it is much better, and also some graphics were updated to be local. Pretty much a cleanup, that's all for Billygoat.<br>Jacob is our new developer! He is known for his website https://blips.club which is an incredibly accurate Twitter clone! Anyways, he mostly did code cleanup. So on the outsiders side, he contributed an old YouTube 2005 player he made for modern browser people. There is also a thumbnail generator to be made soon and featured videos have arrived! Hooray! </p>
	</div>
	
	<div class="tableSubTitle" bis_skin_checked="1">July 29th, 2005</div>
	<div class="tableSubContent" bis_skin_checked="1">
		<p>Today we are adding accounts. Currently you can sign up, i will work on logging in and sessions a bit later.</p>
	</div>
	
	<div class="tableSubTitle" bis_skin_checked="1">July 28th, 2005</div>
	<div class="tableSubContent" bis_skin_checked="1">
		<p>Greetings everyone!</p>
		<p>Thanks for visiting YoreTude! We are currently in development. In an effort to communicate improvements and changes, this blog was created.</p>
		<p>So, first off, we are extremely happy with the feedback we've received thus far. We are continuously working towards our goal of making YoreTude <i>the</i> YouTube revival for the Internet. That said, please let us know if there's something you'd like us to address -- we really, really, really do value <i>any</i> input our users send. We will try to read every e-mail that is sent to <a href="contact.php">us</a>. Go ahead, try it; we'll prove it!</p>
		<p>Anyway, back to the recent changes. We have done a complete refresh of the site to align with it's current month. It's now more aesthetically pleasing and accurate. Please enjoy. This update added this news page, a refreshed header, soon a footer, and more. I will work on half of the site, while Billy will work on the unfinished list i give to him. Now in a dead revival scene, we are striving to be <i>the</i> revival, so please enjoy the site and if you see any bugs, unfinished pages or the like, feel free to contact us at help@yoretude.com! We hope to top all revivals and provide the fullfilling, truly authentic old YouTube experience <i>no</i> revival has captured before, so the support, developers, everything counts. If you want to develop send DeIeted User#0060 on Discord your username. Thank you for visiting YoreTude.</p>
	</div>
	
</div>

<?php
require __DIR__ . "/includes/footer.php";
?>
