<?php
require __DIR__ . "/includes/header.php";

if(!isset($_SESSION['member_id'])) {
    die(header("Location: login.php"));
}
?>
<div class="pageTitle">My Messages</div>
