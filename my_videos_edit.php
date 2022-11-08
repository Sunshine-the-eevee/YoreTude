<?php
require __DIR__ . "/includes/header.php";

if(!isset($_SESSION['member_id'])) {
    die(header("Location: /login.php"));
}

if(!isset($_GET['video_id'])) {
	die(header("Location: /my_videos.php"));
}

$video = $conn->prepare("SELECT * FROM videos WHERE video_id = ? AND member_id = ?");
$video->execute([$_GET['video_id'], $session['member_id']]);

if($video->rowCount() == 0) {
	die(header("Location: /my_videos.php"));
} else {
	$video = $video->fetch(PDO::FETCH_ASSOC);
}

if($_SERVER['REQUEST_METHOD'] == "POST") {
	$update_video = $conn->prepare("UPDATE videos SET title = ?, description = ?, tags = ? WHERE video_id = ? AND member_id = ?");
	$update_video->execute([
		trim($_POST['title']),
		trim($_POST['description']),
		trim($_POST['tags']),
		$video['video_id'],
		$session['member_id']
	]);
	header("Location: /my_videos.php");
}

?>
<style>
   	bash{
    	text-align: right;
   </style>
<script>
function UploadHandler() {
	const form = document.getElementById('editForm');
	
	// TITLE
	if (form.elements['title'].value.length == 0 || form.elements['title'].value == null)
	{
		alert("You must enter a title!");
		form.elements['title'].focus();
		return false;
	}
	if (form.elements['title'].value.length > 64)
	{
		alert("Your title must be shorter than 64 characters!");
		form.elements['title'].focus();
		return false;
	}
	
	// DESCRIPTION
	if (form.elements['description'].value.length == 0 || form.elements['description'].value == null)
	{
		alert("You must enter a description!");
		form.elements['description'].focus();
		return false;
	}
	if (form.elements['description'].value.length > 500)
	{
		alert("Your description must be shorter than 500 characters!");
		form.elements['description'].focus();
		return false;
	}
	
	// TAGS
	if (form.elements['tags'].value.length == 0 || form.elements['tags'].value == null)
	{
		alert("You must enter tags!");
		form.elements['tags'].focus();
		return false;
	}
	if (form.elements['tags'].value.length > 300)
	{
		alert("Your tags must be shorter than 300 characters!");
		form.elements['tags'].focus();
		return false;
	}
	const regex = /(?:[\w]+ ){2}[\w]+/; // three or more words
	if (regex.test(form.elements['tags'].value) == false)
	{
		alert("You must have at least three tags!");
		form.elements['tags'].focus();
		return false;
	}
	
	return true;
}
</script>
<div class="bash"> <a align="right" href="my_videos.php">Back to "my videos"</a></div>
<div class="tableSubtitle">Video Details</div>

<div class="formTable">
    <form name="editForm" id="editForm" method="post" action="my_videos_edit.php?video_id=<?php echo htmlspecialchars($video['video_id']); ?>" enctype="multipart/form-data" onsubmit="return UploadHandler();">
        <table cellpadding="5" cellspacing="0" border="0" align="center">
				<tr>
					<td align="right"><span class="label">Title:</span></td>
					<td><input type="text" size="50" maxlength="64" name="title" value="<?php echo htmlspecialchars($video['title']); ?>"></td>
                </tr>
                <tr>
                    <td align="right"><span class="label">Description:</span></td>
                    <td><textarea name="description" maxlength="500" cols="55" rows="3"><?php echo htmlspecialchars($video['description']); ?></textarea></td>
                </tr>
                <tr>
                    <td align="right"><span class="label">Tags:</span></td>
					<td><input type="text" size="50" maxlength="255" name="tags" value="<?php echo htmlspecialchars($video['tags']); ?>"></td>
                </tr>
				<tr>
					<td></td>
					<td><b>Enter a list of three or more keywords, separated by spaces, describing your video.</b><br>It helps to use relevant keywords so that others can find your video!</td>
				</tr>
				<tr>
					<td colspan="2"><br></td>
				</tr>
                <tr>
                    <td></td>
                    <td><input type="submit" id="save" name="save" value="Save ->"></td>
                </tr>
                <?php if(isset($_SESSION['error'])) { ?>
                    <tr>
                        <td>
                            <p style="color: #ff0000;"><?php echo htmlspecialchars($_SESSION['error']) ?></p>
                        </td>
                    </tr>
                <?php unset($_SESSION['error']); } ?>
        </table>
    </form>
</div>

<?php
require __DIR__ . "/includes/footer.php";
?>
