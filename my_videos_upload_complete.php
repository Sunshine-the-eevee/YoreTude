<?php
require __DIR__ . "/includes/header.php";

if (!isset($_SESSION['member_id'])) {
    die(header("Location: /login.php"));
}

if (!isset($_GET['v'])) {
    die(header("Location: /"));
}

$stmt = $conn->prepare("SELECT * FROM videos WHERE video_id = :v");
$stmt->execute([':v' => $_GET['v']]);
if($stmt->rowCount() == 0) {
    die(header("Location: /"));
}
$video = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<div class="tableSubTitle">Thank You</div>
<div class="pageTable">
	<div class="success">Your video was successfully added!</div>
	<p>Your video is currently being processed and will be available to view in a few minutes.</p>
	
	<form name="linkForm" id="linkForm">
		<div style="padding: 15px 0px 10px 0px;">
			<table width="550" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#E5ECF9">
				<tr>
					<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
					<td width="100%"><img src="img/pixel.gif" width="1" height="5"></td>
					<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
				</tr>
				<tr>
					<td><img src="img/pixel.gif" width="5" height="1"></td>
					<td align="center">
						<div style="font-size: 11px; font-weight: bold; color: #CC6600; padding: 5px 0px 5px 0px;">Share your video link! Copy and paste below:</div>
						<div style="font-size: 11px; padding-bottom: 15px;"><input name="video_link" type="text" onClick="javascript:document.linkForm.video_link.focus();document.linkForm.video_link.select();" value="http://www.YouTube.com/?v=<?php echo htmlspecialchars($_GET['v']); ?>" size="50" readonly="true" style="font-size: 10px; text-align: center;"></div>
						<div style="font-size: 11px; font-weight: bold; color: #CC6600; padding: 5px 0px 5px 0px;">Play this video directly ON your website! Copy and paste the following snippet:</div>
						<div style="font-size: 11px; padding-bottom: 15px;"><input name="video_link" type="text" onClick="javascript:document.linkForm.video_link.focus();document.linkForm.video_link.select();" value="http://www.YouTube.com/YouTubeembed.swf?video_id=<?php echo htmlspecialchars($_GET['v']); ?>" size="50" readonly="true" style="font-size: 10px; text-align: center;"></div>
					</td>
					<td><img src="img/pixel.gif" width="5" height="1"></td>
				</tr>
				<tr>
					<td><img src="img/box_login_bl.gif" width="5" height="5"></td>
					<td><img src="img/pixel.gif" width="1" height="5"></td>
					<td><img src="img/box_login_br.gif" width="5" height="5"></td>
				</tr>
			</table>
		</div>
	</form>
</div>
<br><b>What would you like to do next?</b><br><table width="595" cellpadding="0" cellspacing="0" border="0">
			<tbody><tr>
				<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
				<td width="100%"><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
				<td style="padding: 5px 0px 10px 0px;">
				
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tbody><tr valign="top">
					<td width="33%" style="border-right: 1px dashed #369; padding: 0px 10px 10px 10px; color: #444;">
					<div style="font-weight: bold; margin-bottom: 5px;"><a href="browse.php">Watch some videos</a></div>Search and browse 1000's of videos.</td>
					<td width="33%" style="padding: 0px 10px 10px 10px; color: #444;">
					<div style="font-weight: bold; margin-bottom: 5px;"><a href="my_friends_invite.php">Invite your friends</a></div>Invite your friends to watch your videos.</td>
					
					</tr>
				<tr valign="top">
					<td width="33%" style="border-right: 1px dashed #369; padding: 0px 10px 10px 10px; color: #444;">
					<div style="font-weight: bold; margin-bottom: 5px;"><a href="my_friends_invite.php">Upload more videos</a></div>Start building your video collection.</td>
					<td width="33%" style="padding: 0px 10px 10px 10px; color: #444;">
					<div style="font-weight: bold; margin-bottom: 5px;"><a href="my_videos_edit.php?video_id=<?php echo htmlspecialchars($_GET['v']); ?>">Edit your video details</a></div>Add or change your video information or options.</td>
					
					</tr></tbody></table>
				
				</td>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
			</tr>
			<tr>
				<td><img src="/img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</tbody></table>
<?php
require __DIR__ . "/includes/footer.php";
?>
