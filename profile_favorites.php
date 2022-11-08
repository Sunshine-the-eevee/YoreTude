<?php
require __DIR__ . "/includes/header.php";

if(!isset($_GET['user'])) require __DIR__ . "/errors/404.php";

$profile = $conn->prepare("SELECT * FROM members WHERE members.username = ?");
$profile->execute([$_GET['user']]);

if($profile->rowCount() == 0) {
	require __DIR__ . "/errors/404.php";
} else {
	$profile = $profile->fetch(PDO::FETCH_ASSOC);
}

$profile['videos'] = $conn->prepare("SELECT video_id FROM videos WHERE member_id = ? AND converted = 1");
$profile['videos']->execute([$profile["member_id"]]);
$profile['videos'] = $profile['videos']->rowCount();
$profile['favorites'] = $conn->prepare("SELECT favorite_id FROM favorites WHERE member_id = ?");
$profile['favorites']->execute([$profile["member_id"]]);
$profile['favorites'] = $profile['favorites']->rowCount();

$videos = $conn->prepare(
	"SELECT * FROM favorites
	LEFT JOIN videos ON favorites.video_id = videos.video_id
	LEFT JOIN members ON members.member_id = videos.member_id
	WHERE favorites.member_id = ? AND videos.converted = 1
	ORDER BY favorites.favorite_id DESC"
);
$videos->execute([$profile['member_id']]);

$related_tags = [];
?>

<div style="text-align:center; margin-bottom: 5px;">
<a href="profile.php?user=<?php echo htmlspecialchars($profile["username"]); ?>">Profile</a>
<span style="padding-right: 5px; padding-left: 5px;">|</span>
<a href="profile_videos.php?user=<?php echo htmlspecialchars($profile["username"]); ?>">Videos</a> (<?php echo $profile['videos']; ?>)
<span style="padding-right: 5px; padding-left: 5px;">|</span>
<a href="profile_favorites.php?user=<?php echo htmlspecialchars($profile["username"]); ?>" class="bold">Favorites</a> (<?php echo $profile['favorites']; ?>)
</div>

<div class="pageTable">
<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#CCCCCC">
	<tr>
		<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
		<td width="100%"><img src="img/pixel.gif" width="1" height="5"></td>
		<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
	</tr>
	<tr>
		<td><img src="img/pixel.gif" width="5" height="1"></td>
		<td>
			<div class="watchTitleBar">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr valign="top">
						<td><div class="watchTitle">Favorites // <span style="text-transform: capitalize;"><?php echo htmlspecialchars($profile['username']); ?></span></div></td>
					</tr>
				</table>
			</div>
			<?php if($videos !== false) { ?>
			<?php foreach($videos as $video) { ?>
			<?php
			$video['views'] = $conn->prepare("SELECT COUNT(view_id) AS views FROM views WHERE video_id = ?");
			$video['views']->execute([$video['video_id']]);
			$video['views'] = $video['views']->fetchColumn();
			
			$video['comments'] = $conn->prepare("SELECT COUNT(comment_id) AS comments FROM comments WHERE video_id = ?");
			$video['comments']->execute([$video['video_id']]);
			$video['comments'] = $video['comments']->fetchColumn();
			
			$tags = explode(" ", $video['tags']);
			?>
			<div class="moduleEntry"> 
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr valign="top">
						<td><a href="watch.php?v=<?php echo htmlspecialchars($video['video_id']); ?>"><img src="get_still.php?video_id=<?php echo htmlspecialchars($video['video_id']); ?>" class="moduleEntryThumb" width="120" height="90"></a></td>
						<td width="100%">
							<div class="moduleEntryTitle"><a href="watch.php?v=<?php echo htmlspecialchars($video['video_id']); ?>"><?php echo htmlspecialchars($video['title']); ?></a></div>
							<div class="moduleEntryDescription"><?php echo htmlspecialchars($video['description']); ?></div>
							<div class="moduleEntryTags">Tags // <?php
							foreach($tags as $tag) {
								echo '<a href="results.php?search='.htmlspecialchars($tag).'">'.htmlspecialchars($tag).'</a> : ';
								if(!in_array(strtolower($tag), array_map('strtolower', $related_tags))) { // To prevent repeated tags.
									array_push($related_tags, $tag);
								}
							}
							?></div>
							<div class="moduleEntryDetails">Added: <?php echo date("F j, Y", strtotime($video['uploaded_at']. ' - 17 years')); ?> by <a href="profile.php?user=<?php echo htmlspecialchars($video['username']); ?>"><?php echo htmlspecialchars($video['username']); ?></a></div>
							<div class="moduleEntryDetails">Runtime: <?php echo gmdate("i:s", $video['duration']); ?> | Views: <?php echo $video['views']; ?> | Comments: <?php echo $video['comments']; ?></div>
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
