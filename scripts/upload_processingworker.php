<?php
require_once 'includes/functions.php';

$request = (object) [
    "targetdir" => $argv[1],
    "vext" => $argv[2],
    "v_id" => $argv[3],
    "thumbdir" =>  "./stills/",
];
try {
    // FLV
    exec($config['ffmpeg'] . ' -i '.$request->targetdir.$request->v_id.'_temp.'.$request->vext.' -c:v flv -vf scale=320x240 -c:v flv1 -b:a 80k  -c:a mp3 -ar 22050 videos/'.$request->v_id.'.flv');

    // WEBM
    exec($config['ffmpeg'] . ' -i '.$request->targetdir.$request->v_id.'_temp.'.$request->vext.' -c:v libvpx -vf scale=-2:240,format=yuv420p -b:v 200k -c:a libopus -b:a 24k -ar 24000 -ac 1 videos/'.$request->v_id.'.webm');

    // Duration
    $duration = round(exec($config['ffprobe'] . ' -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 '.$request->targetdir.$request->v_id.'_temp.'.$request->vext));
    
    // Still 1
    exec($config['ffmpeg'] . ' -i '.$request->targetdir.$request->v_id.'_temp.'.$request->vext.' -c:v mjpeg -ss '.($duration*0.25).' -vframes 1 -vf scale=120:90 -an -q:v 5 stills/'.$request->v_id.'_1.jpg');
    
    // Still 2
    exec($config['ffmpeg'] . ' -i '.$request->targetdir.$request->v_id.'_temp.'.$request->vext.' -c:v mjpeg -ss '.($duration*0.50).' -vframes 1 -vf scale=120:90 -an -q:v 5 stills/'.$request->v_id.'_2.jpg');

    // Still 3
    exec($config['ffmpeg'] . ' -i '.$request->targetdir.$request->v_id.'_temp.'.$request->vext.' -c:v mjpeg -ss '.($duration*0.75).' -vframes 1 -vf scale=120:90 -an -q:v 5 stills/'.$request->v_id.'_3.jpg');

	$stmt = $conn->prepare('UPDATE videos SET duration = :duration, converted = 1 WHERE video_id = :video_id');
	$stmt->execute([':duration' => $duration, ':video_id' => $request->v_id]);
	unlink($request->targetdir.$request->v_id."_temp.".$request->vext);
} catch (Exception $e) {
	echo "Something went wrong!: ". $e->getMessage();
    unlink($request->targetdir.$request->v_id."_temp.".$request->vext);
}
?>
