<?php
if(isset($_GET['format']) && $_GET['format'] == "webm") {
	$video_file = __DIR__ . "/videos/" . $_GET['video_id'] . ".webm";
} else {
	$video_file = __DIR__ . "/videos/" . $_GET['video_id'] . ".flv";
}

if(file_exists($video_file)) {
	header("Content-Type: ".mime_content_type($video_file));
	$resource = fopen($video_file, "rb");
	fpassthru($resource);
}
?>
