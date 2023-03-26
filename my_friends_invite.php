<?php
require __DIR__ . "/includes/header.php";

if (!isset($_SESSION['member_id'])) {
    die(header("Location: /login.php"));
}

?>
At YoreTude we want our community to grow. This is why we added invites! Simply copy the link given to you, and and give it to a friend. When they click the link, it will give them the sign up form and tell them that you invited them. Try it- it really works!
<center>
<span class="highlight">Copy and Paste This To A Friend</span>
<br>
<br>
<textarea cols="65" rows="6" id="snippet_iframe" wrap="soft">https://yoretude.com/invite.php?user=<?php echo htmlspecialchars($session['username']); ?></textarea>
</center>
<?php
require __DIR__ . "/includes/footer.php";
?>
