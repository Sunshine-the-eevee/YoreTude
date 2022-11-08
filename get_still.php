<?php
if(!isset($_GET['still_id'])) $_GET['still_id'] = 2;
if(!isset($_GET['video_id'])) $_GET['video_id'] = false;

$still_file = __DIR__ . "/stills/".$_GET['video_id']."_".$_GET['still_id'].".jpg";

if(isset($_GET['video_id']) && file_exists($still_file)) {
	header("Content-Type: ".mime_content_type($still_file));
	$resource = fopen($still_file, "rb");
	fpassthru($resource);
} else {
	header("Content-Type: image/png");
	$resource = fopen(__DIR__ . "/img/get_still.png", 'rb');
	fpassthru($resource);
}
?>
