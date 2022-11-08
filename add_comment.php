<?php
require __DIR__ . "/includes/header.php";
ob_get_clean();

// Make sure the user is logged in.
if(!isset($session)) {
	header("Location: login.php", true, 401);
	die();
}

// Make sure variables are set
if(!isset($_POST['video_id']) || !isset($_POST['comment'])) {
	die();
}

// Check if the video in question exists.
$video_exists = $conn->prepare("SELECT video_id FROM videos WHERE video_id = :video_id AND converted = 1");
$video_exists->execute([
	":video_id" => $_POST['video_id']
]);

if($video_exists->rowCount() == 0) {
	die();
}

// Check if the user has already commented on this video within the past 5 minutes.
$comment_exists = $conn->prepare("SELECT comment_id FROM comments WHERE member_id = :member_id AND video_id = :video_id AND posted_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)");
$comment_exists->execute([
	":member_id" => $session['member_id'],
	":video_id" => $_POST['video_id']
]);

if($comment_exists->rowCount() > 0) {
	die();
}

// If a comment is all caps or the length of the comment is less than three characters, quietly discard it.
if(strtoupper($_POST['comment']) == $_POST['comment'] || strlen($_POST['comment']) <= 3) {
	die();
}

// Post that comment!
$post_comment = $conn->prepare("INSERT INTO comments (comment_id, video_id, member_id, body) VALUES (:comment_id, :video_id, :member_id, :body)");
$post_comment->execute([
	":comment_id" => generateId(26),
	":video_id" => $_POST['video_id'],
	":member_id" => $session['member_id'],
	":body" => trim($_POST['comment'])
]);
?>
