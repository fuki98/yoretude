<?php
session_start();
session_destroy();
if(isset($_GET['next']) && !empty($_GET['next'])) {
    header("Location: ".$_GET['next']);
} else {
    header("Location: /");
}

?>