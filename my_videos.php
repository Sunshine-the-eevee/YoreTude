<?php
require __DIR__ . "/includes/header.php";

if(!isset($session)) {
	header("Location: login.php");
}

$videos = $conn->prepare(
	"SELECT * FROM videos
	LEFT JOIN members ON members.member_id = videos.member_id
	WHERE videos.member_id = ?
	ORDER BY videos.uploaded_at DESC"
);
$videos->execute([$session['member_id']]);
?>
<div style="padding: 0px 5px 0px 5px;">

<div>
<table align="center" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td><strong>Overview</strong></td>
		<td style="padding: 0px 5px 0px 5px;">|</td>
		<td><a href="sharing.php">Share</a></td>
		<td style="padding: 0px 5px 0px 5px;">|</td>
		<td><a href="my_videos_upload.php">Upload</a></td>
	</tr>
</table>
</div><br>
<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#CCCCCC">
	<tr>
		<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
		<td width="100%"><img src="img/pixel.gif" width="1" height="5"></td>
		<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
	</tr>
	<tr>
		<td><img src="img/pixel.gif" width="5" height="1"></td>
		<td>
			<div class="moduleTitleBar">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr valign="top">
						<td><div class="moduleTitle">My Videos</div></td>
					</tr>
				</table>
			</div>
			<?php if($videos !== false) { ?>
			<?php foreach($videos as $video) { ?>
			<?php
			$video['views'] = $conn->prepare("SELECT COUNT(view_id) AS views FROM views WHERE video_id = ?");
			$video['views']->execute([$video['video_id']]);
			$video['views'] = $video['views']->fetchColumn();
	
			$video['favorites'] = $conn->prepare("SELECT COUNT(favorite_id) AS favorites FROM favorites WHERE video_id = ?");
			$video['favorites']->execute([$video['video_id']]);
			$video['favorites'] = $video['favorites']->fetchColumn();
	
			$video['comments'] = $conn->prepare("SELECT COUNT(comment_id) AS comments FROM comments WHERE video_id = ?");
			$video['comments']->execute([$video['video_id']]);
			$video['comments'] = $video['comments']->fetchColumn();
			?>
			<div class="moduleEntry"> 
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr valign="top">
						<td>
							<a href="watch.php?v=<?php echo htmlspecialchars($video['video_id']); ?>"><img src="get_still.php?video_id=<?php echo htmlspecialchars($video['video_id']); ?>" class="moduleEntryThumb" width="120" height="90"></a>
							<table width="130" cellpadding="0" cellspacing="0" border="0">
								<tr align="center">
									<td width="100%">
										<br><form method="get" action="my_videos_edit.php">
											<input type="hidden" value="<?php echo $video['video_id']; ?>" name="video_id">
											<input type="submit" value="Edit Video">
										</form>
										<form method="get" action="remove_video.php" onsubmit="return confirm('Are you sure you want to remove this video? Theres no undo!');">
											<input type="hidden" value="<?php echo $video['video_id']; ?>" name="video_id">
											<input type="submit" value="Remove Video">
										</form>
									</td>
								</tr>
							</table>
						</td>
						<td width="100%">
							<div class="moduleEntryTitle"><a href="watch.php?v=<?php echo htmlspecialchars($video['video_id']); ?>"><?php echo htmlspecialchars($video['title']); ?></a></div>
							<div class="moduleEntryDescription"><?php echo htmlspecialchars($video['description']); ?></div>
							<div class="moduleEntryTags">Tags // <?php echo htmlspecialchars($video['tags']); ?></div>
							<div class="moduleEntryDetails">Added: <?php echo date("F j, Y, h:i a", strtotime($video['uploaded_at']. ' - 17 years')); ?></div>
							<div class="moduleEntryDetails">Runtime: <?php echo gmdate("i:s", $video['duration']); ?> | Views: <?php echo $video['views']; ?> | Comments: <?php echo $video['comments']; ?> | Fans: <?php echo $video['favorites']; ?></div> 
							<hr style="border: 0; border-bottom: 1px dashed #999999; margin: 1em 0;">
							<div class="moduleEntryDetails">File: <?php echo (!empty($video['file'])) ? htmlspecialchars($video['file']) : "No file found!"; ?></div>
							<div class="moduleEntryDetails">Broadcast: <span style="color:green;font-weight:bold"><?php echo ($video['private'] == 1) ? "Private Video" : "Public Video"; ?></div></span>
							<div class="moduleEntryDetails">Status: <?php echo ($video['converted'] == 1) ? "Live!" : "Processing..."; ?></div>
							<div class="moduleEntryDetails">
								<form name="linkForm_<?php echo htmlspecialchars($video['video_id']); ?>" id="linkForm_<?php echo htmlspecialchars($video['video_id']); ?>">
									<input name="video_link" type="text" onClick="document.linkForm_<?php echo htmlspecialchars($video['video_id']); ?>.video_link.focus();document.linkForm_<?php echo htmlspecialchars($video['video_id']); ?>.video_link.select();" value="http://www.YouTube.com/?v=<?php echo htmlspecialchars($video['video_id']); ?>" size="50" readonly="true" style="font-size: 10px; text-align: center;">
									<br>Share this video with friends! Copy and paste this link above to an email or website.
								</form>
							</div>
						</td>
					</tr>
				</table>
			</div>
			<?php } ?>
			<?php } ?>
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
<?php
require __DIR__ . "/includes/footer.php";
?>
