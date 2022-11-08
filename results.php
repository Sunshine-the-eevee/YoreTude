<?php
require __DIR__ . "/includes/header.php";

$search = str_replace(" ", "|", $_GET['search']);
$videos = $conn->prepare("SELECT * FROM videos LEFT JOIN members ON members.member_id = videos.member_id WHERE videos.tags REGEXP ? AND videos.converted = 1 AND videos.private = 0 ORDER BY videos.uploaded_at DESC"); // Regex!
$videos->execute([$search]);

if(strlen(trim($_GET['search'])) < 2) {
	class videos { // stupid
		function rowCount() {
			return 0;
		}
	}
	$videos = new videos;
}

$related_tags = [];
?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<td width="100%">
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
						<td><div class="moduleTitle">Tag // <?php echo htmlspecialchars(trim($_GET['search'])); ?> (<?php echo $videos->rowCount(); ?> results)</div></td>
						<td align="right"><a href="http://www.YouTube.com/rss/tag/<?php echo urlencode($_GET['search']); ?>.rss"><img src="img/rss.gif" width="36" height="14" border="0" style="vertical-align: text-top;"></a> 
						<span style="font-size: 11px; margin-right: 3px;"><a href="http://www.YouTube.com/rss/tag/<?php echo urlencode($_GET['search']); ?>.rss">Feed For Tag // <?php echo htmlspecialchars($_GET['search']); ?></a></span></td>
					</tr>
				</table>
				</div>
				
				<?php foreach($videos as $video) { ?>
				<?php
				$related_tags = array_merge($related_tags, explode(" ", $video['tags']));
				
				$video['views'] = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE video_id = ?");
				$video['views']->execute([$video['video_id']]);
				$video['views'] = $video['views']->fetchColumn();
						
				$video['comments'] = $conn->prepare("SELECT COUNT(comment_id) FROM comments WHERE video_id = ?");
				$video['comments']->execute([$video['video_id']]);
				$video['comments'] = $video['comments']->fetchColumn();
				?>
				<div class="moduleEntry"> 
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr valign="top">
							<td>
							<table cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td><a href="watch.php?v=<?php echo $video['video_id']; ?>&search=<?php echo urlencode($_GET['search']); ?>"><img src="get_still.php?video_id=<?php echo $video['video_id']; ?>&still_id=1" class="moduleEntryThumb" width="100" height="75"></a></td>
									<td><a href="watch.php?v=<?php echo $video['video_id']; ?>&search=<?php echo urlencode($_GET['search']); ?>"><img src="get_still.php?video_id=<?php echo $video['video_id']; ?>&still_id=2" class="moduleEntryThumb" width="100" height="75"></a></td>
									<td><a href="watch.php?v=<?php echo $video['video_id']; ?>&search=<?php echo urlencode($_GET['search']); ?>"><img src="get_still.php?video_id=<?php echo $video['video_id']; ?>&still_id=3" class="moduleEntryThumb" width="100" height="75"></a></td>
								</tr>
							</table>
							
							</td>
							<td width="100%"><div class="moduleEntryTitle"><a href="watch.php?v=<?php echo $video['video_id']; ?>&search=<?php echo urlencode($_GET['search']); ?>"><?php echo htmlspecialchars($video['title']); ?></a></div>
							<div class="moduleEntryDescription"><?php echo htmlspecialchars($video['description']); ?></div>
							<div class="moduleEntryTags">Tags // <?php foreach(explode(" ", $video['tags']) as $tag) echo '<a href="results.php?search='.htmlspecialchars($tag).'">'.htmlspecialchars($tag).'</a> : '; ?></div>
							<div class="moduleEntryDetails">Added: <?php echo date("F j, Y", strtotime($video['uploaded_at']. ' - 17 years')); ?> by <a href="profile.php?user=<?php echo htmlspecialchars($video['username']); ?>"><?php echo htmlspecialchars($video['username']); ?></a></div>
							<div class="moduleEntryDetails">Runtime: <?php echo gmdate("i:s", $video['duration']); ?> | Views: <?php echo $video['views']; ?> | Comments: <?php echo $video['comments']; ?></div>
		
							</td>
						</tr>
					</table>
				</div>
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
		</td>
		
		<td width="15"><img src="img/pixel.gif" width="15" height="1"></td>
		<td width="160">
		<div style="font-weight: bold; margin-bottom: 3px; width: 160px;">Related Tags:</div>
			<?php $related_tags = array_unique($related_tags); ?>
			<?php foreach($related_tags as $tag) { ?>
			<div style="padding: 0px 0px 5px 0px; color: #999;">&#187; <a href="results.php?search=<?php echo htmlspecialchars($tag); ?>"><?php echo htmlspecialchars($tag); ?></a></div>
			<?php } ?>
		</td>
		
	</tr>
</table>

<?php
if(empty($videos) || $videos->rowCount() == 0) {
?>
<br>
<div class="moduleTitle">Found no videos matching "<?php echo htmlspecialchars($_GET['search']); ?>". Do you have one? <a href="my_videos_upload.php">Upload</a> it!</div>
<?php
}
?>

<?php 
require __DIR__ . "/includes/footer.php";
?>
