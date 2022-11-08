<?php
require __DIR__ . "/includes/header.php";
ob_get_clean();

// Make sure the user is logged in.
if(!isset($session)) {
	header("Location: login.php", true, 401);
	die();
}

// Check if the video in question exists.
$video_exists = $conn->prepare("SELECT video_id FROM videos WHERE video_id = :video_id AND converted = 1");
$video_exists->execute([
	":video_id" => $_GET['video_id']
]);

if($video_exists->rowCount() == 0) {
	die();
}

// Check if the user has already favorited this video.
$favorite_exists = $conn->prepare("SELECT favorite_id FROM favorites WHERE member_id = :member_id AND video_id = :video_id");
$favorite_exists->execute([
	":member_id" => $session['member_id'],
	":video_id" => $_GET['video_id']
]);

if($favorite_exists->rowCount() > 0) {
	die();
}

// Add it to favorites!
$add_to_favorites = $conn->prepare("INSERT INTO favorites (member_id, video_id) VALUES (:member_id, :video_id)");
$add_to_favorites->execute([
	":member_id" => $session['member_id'],
	":video_id" => $_GET['video_id']
]);
?>