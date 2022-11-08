<?php
require_once './includes/functions.php';

function randstr($len, $charset = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-"){
    return substr(str_shuffle($charset),0,$len);
}

if(!isset($_SESSION['member_id'])) {
    die(header("Location: /login.php"));
}

if($_SERVER['REQUEST_METHOD'] != 'POST') {
    die(header("Location: /my_videos_upload.php"));
} 

$request = (object) [
    "targetdir" => "./videos/",
    "vfile" => $_FILES["fileToUpload"],
    "vext" => strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION)),
    "vtitle" => trim($_POST['title']),
    "vdesc" => trim($_POST['description']),
    "vtags" => trim($_POST['tags']),
    "v_id" =>  randstr(11),
    "error" => (object) [
        "status" => "OK",
        "msg" => ""
    ],
];

$cooldown = $conn->prepare("SELECT * FROM members WHERE member_id = :member_id AND upload_cooldown >= NOW() - INTERVAL 30 MINUTE LIMIT 1");
$cooldown->bindParam(":member_id", $_SESSION['member_id']);
$cooldown->execute();

switch(true) {
    case $cooldown->rowCount() === 1:
        $request->error->msg = "You must wait 30 minutes before uploading"; $request->error->status = "";
        break;
    case $request->vfile["error"] == 4:
        $request->error->msg = "You must select a file"; $request->error->status = "";
        break;
    case empty(trim($request->vtitle)):
        $request->error->msg = "You must specify a title"; $request->error->status = "";
        break;
    case strpos(mime_content_type($_FILES['fileToUpload']['tmp_name']), "video") != false:
        $request->error->msg = "That isn't a video file"; $request->error->status = "";
        break;
}

if($request->error->status == "OK") {
    if(move_uploaded_file($request->vfile['tmp_name'], $request->targetdir.$request->v_id."_temp.".$request->vext)) {
        $stmt = $conn->prepare("INSERT INTO videos (video_id, member_id, title, description, tags, file, duration) 
        VALUES (:video_id, :member_id, :title, :desc, :tags, :file, '0')");
        $stmt->execute([
            ':video_id' => $request->v_id,
            ':member_id' => $_SESSION['member_id'],
            ':title' => htmlspecialchars($request->vtitle),
            ':desc' => $request->vdesc,
            ':tags' => $request->vtags,
			':file' => trim($_FILES["fileToUpload"]["name"])
        ]);
        system(sprintf('php scripts/upload_processingworker.php "%s" "%s" "%s" > %s 2>&1 &', $request->targetdir, $request->vext, $request->v_id, './videos/.log'));
        
        
        header("Location: /my_videos_upload_complete.php?v=". $request->v_id);
    }
} else {
    $_SESSION['error'] = $request->error->msg;
    header("Location: /my_videos_upload.php");
}
?>
