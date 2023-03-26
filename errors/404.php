<?php
ob_get_clean(); // Cleans source
http_response_code(404);
die("We are currently experiencing technical difficulties.");
?>