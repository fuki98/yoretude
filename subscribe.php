<?php
session_start();
require __DIR__ . "/includes/header.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $memberId = $_POST['member_id'];
    $action = $_POST['action'];

    $checkSubscription = $conn->prepare("SELECT * FROM subscriptions WHERE subscriber_id = ? AND subscribed_to_id = ?");
    $checkSubscription->execute([$session['member_id'], $memberId]);

    if ($checkSubscription->rowCount() == 0) {
        $conn->prepare("INSERT INTO subscriptions (subscriber_id, subscribed_to_id) VALUES (?, ?)")
            ->execute([$session['member_id'], $memberId]);
        echo 'success';
    } else if ($checkSubscription->rowCount() > 0) {
        $conn->prepare("DELETE FROM subscriptions WHERE subscriber_id = ? AND subscribed_to_id = ?")
            ->execute([$session['member_id'], $memberId]);
        echo 'success';
    } else {
        echo 'failed';
    }
}
