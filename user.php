<?php
$args = $_SERVER["REQUEST_URI"];
$user = explode("/",$args);
print_r($user);
header('Location: http://www.YouTube.com/profile.php?user=$user');
?>
