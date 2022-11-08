<?php
require __DIR__ . "/includes/header.php";

if(isset($_GET['v'])) {
	header("Location: watch.php?v=".$_GET['v'], true, 303);
	die();
}
?>
<?php if(isset($_GET['unavail'])) { ?>
	 <table width="100%" align="center" bgcolor="#FF0000" cellpadding="6" cellspacing="3" border="0">
		<tbody><tr>
			<td align="center" bgcolor="#FFFFFF"><span class="error">The video you have requested is not available.<br>
<br>
If you have recently uploaded this video, you may need to wait a few minutes for the video to process.</span></td>
		</tr>
	</tbody></table><br>
<?php } ?>
<div id="searchbox">
  <!-- ... -->
</div>
<script>
document.getElementById("searchbox").focus();
</script>
<?php
$tags_strings = $conn->query("SELECT tags FROM videos WHERE converted = 1 ORDER BY uploaded_at DESC LIMIT 30");
$tag_list = [];
foreach($tags_strings as $result) $tag_list = array_merge($tag_list, explode(" ", $result['tags']));
$tag_list = array_slice(array_count_values($tag_list), 0, 50);
?>
<div style="padding: 0px 5px 0px 5px;">
		

<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<td style="padding-right: 15px;">
		<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#E5ECF9">
			<tr>
			     <td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
				<td width="100%"><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			
			<tr>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
				<td style="padding: 5px 0px 10px 0px;">
				
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr valign="top">
					<td width="33%" style="border-right: 1px dashed #369; padding: 0px 10px 10px 10px; color: #444;">
						<?php if(isset($session)) { ?>
						<b><div style="font-size: 16px; color: #0c3768; style=">My Account Overview</div><b></b></b><b>User Name : </b> <a href="profile.php?user=<?php echo htmlspecialchars($session['username']); ?>"><?php echo htmlspecialchars($session['username']); ?></a><br>
<b>Email : </b><?php echo htmlspecialchars($session['email']); ?><br>
<b>Videos watched : </b> None!<br><br>
					</td>
					<td style="border-right: 0px dashed #369; padding: 0px 10px 10px 10px; color: #444;" width="33%"><img src="/img/mail.gif" border="0"> You have <a href="my_messages.php">0 new messages.</a><br><b>ToDo List...</b><br><img src="/img/icon_todo.gif" style="vertical-align: text-bottom; padding-left: 2px; padding-right: 1px;"><a href="my_friends_invite.php">Invite Your Friends</a><br><img src="/img/icon_todo.gif" style="vertical-align: text-bottom; padding-left: 2px; padding-right: 1px;"><a href="my_profile.php">Update Your Profile</a>
					</td>
						<?php } else { ?>
					<div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;"><a href="browse.php">Watch</a></div>
					Instantly find and watch 1000's of fast streaming videos.
					</td>
					<td width="33%" style="border-right: 1px dashed #369; padding: 0px 10px 10px 10px; color: #444;">
					<div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;"><a href="my_videos_upload.php">Upload</a></div>
					Quickly upload and tag videos in almost any video format.
					</td>
					<td width="33%" style="padding: 0px 10px 10px 10px; color: #444;">
					<div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;"><a href="my_friends_invite.php">Share</a></div>
					Easily share your videos with family, friends, or co-workers.
					</td>
					</tr>
						<?php } ?>
					</td>
					</tr>
				</table>
				
				</td>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
			</tr>
			<tr>
				<td><img src="/img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</table>
<?php
$views = $conn->query("SELECT * FROM views LEFT JOIN videos ON videos.video_id = views.video_id  ORDER BY views.viewed_at DESC LIMIT 5");
$vista = $conn->prepare("SELECT * FROM views ORDER BY views.viewed_at DESC LIMIT 5");
?>	
<!-- begin recently viewed -->
		<div style="padding: 10px 0px 10px 0px;">
		<table width="595" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#EEEEDD">
			<tr>
				<td><img src="./img/box_login_tl.gif" width="5" height="5"></td>
				<td><img src="./img/pixel.gif" width="1" height="5"></td>
				<td><img src="./img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="./img/pixel.gif" width="5" height="1"></td>
				<td width="585">
				<div style="padding: 2px 5px 8px 5px;">
				<div style="float: right; padding: 1px 5px 0px 0px; font-weight: bold; font-size: 12px;"><a href="browse.php?s=mp">More Recently Viewed</a></div>
				<div style="font-size: 14px; font-weight: bold; color: #666633;">Recently Viewed...</div>
				
				<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
				<tr>
						<?php foreach($views as $video) { ?>	
						<td width="20%" align="center">
		
						<a href="index.php?v=<?php echo $video['video_id']; ?>"><img src="/get_still.php?video_id=<?php echo $video['video_id']; ?>" width="80" height="60" style="border: 5px solid #FFFFFF; margin-top: 10px;"></a>
						<div class="moduleFeaturedDetails" style="padding-top: 2px;"><?php echo str_replace("ago", "ago", timeAgo($vistas['viewed_at'])); ?></div>
		
						</td>
		<?php } ?>
		
										</tr>
				</table>
				
				</div>
				</td>
				<td><img src="./img/pixel.gif" width="5" height="1"></td>
			</tr>
			<tr>
				<td><img src="./img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="./img/pixel.gif" width="1" height="5"></td>
				<td><img src="./img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</table>
		</div>
		<!-- end recently viewed -->
		<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#CCCCCC">
			<tr>
				<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
				<td width="100%"><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
				<td>
				<div class="moduleTitleBar">
				<div class="moduleTitle"><div style="float: right; padding: 1px 5px 0px 0px; font-size: 12px;"><a href="browse.php">Please You Gotta See More!</a></div>
				We Love These Videos!
				</div>
				</div>
		
				<?php
$featured_videos = $conn->query("SELECT * FROM featured LEFT JOIN videos ON videos.video_id = featured.video_id LEFT JOIN members ON members.member_id = videos.member_id WHERE videos.converted = 1 ORDER BY featured.feature_id DESC LIMIT 10");
?>
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr valign="top">
					</tr>
				</table>
				</div>
				
				<?php foreach($featured_videos as $video) { ?>
				<?php
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
									<a href="index.php?v=<?php echo $video['video_id']; ?>"><img src="get_still.php?still_id=2&video_id=<?php echo $video['video_id']; ?>" class="moduleFeaturedThumb" width="120" height="90"></a>
								</tr>
							</table>
							
							</td>
							<td width="100%"><div class="moduleEntryTitle"><a href="watch.php?v=<?php echo $video['video_id']; ?>&search=<?php echo urlencode($_GET['search']); ?>"><?php echo htmlspecialchars($video['title']); ?></a></div>
							<div class="moduleEntryDescription"><?php echo htmlspecialchars($video['description']); ?></div>
							<div class="moduleEntryTags">Tags // <?php foreach(explode(" ", $video['tags']) as $tag) echo '<a href="results.php?search='.htmlspecialchars($tag).'">'.htmlspecialchars($tag).'</a> : '; ?></div>
							<div class="moduleEntryDetails">Added: <?php echo str_replace("ago", "ago", timeAgo($video['uploaded_at'])); ?> by <a href="profile.php?user=<?php echo htmlspecialchars($video['username']); ?>"><?php echo htmlspecialchars($video['username']); ?></a></div>
							<div class="moduleEntryDetails"> Runtime: <?php echo gmdate("i:s", $video['duration']); ?> | Views: <?php echo $video['views']; ?> | Comments: <?php echo $video['comments']; ?></div>
		<br>
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
		
		<td width="180">
		
		<table width="180" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFEEBB">
			<tr>
				<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
				<td><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
				<td width="170">
		<?php if(isset($session)) { ?>
								
				<div style="font-size: 16px; font-weight: bold; text-align: center; padding: 5px 5px 10px 5px;"><a href="my_friends_invite.php">Invite your friends to join YouTube!</a></div>
				 <?php } else { ?>
				<div style="font-size: 16px; font-weight: bold; text-align: center; padding: 5px 5px 10px 5px;"><a href="signup.php">Sign up for your free account!</a></div>	
					<?php } ?>
				</td>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
			</tr>
			<tr>
				<td><img src="/img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</table>
		
		<div style="margin-top: 10px;">
		<table width="180" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#89cff0">
			<tr>
				<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
				<td><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			

				<?php if(isset($session)) { ?>
		                <div style="font-size: 12px; font-weight: bold; margin-bottom: 7px;"><a href="my_videos_upload.php">Learn more</a></div>
				<?php } else { ?>				
				<div style="font-size: 12px; font-weight: bold; margin-bottom: 7px;"><a href="login.php">Join the contest now!</a></div>
				<?php } ?>				
				</td>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
			</tr>
			<tr>
				<td><img src="/img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</table>
		</div>
		
		<div style="margin: 10px 0px 5px 0px; font-size: 12px; font-weight: bold; color: #333;">Recent Tags:</div>
		<div style="font-size: 13px; color: #333333;">
		
				<?php foreach($tag_list as $tag => $frequency	) {
					echo '<a style="font-size: 13px;" href="results.php?search='.htmlspecialchars($tag).'">'.htmlspecialchars($tag).'</a> :'."\r\n";
				} ?>
		
					
		<div style="font-size: 14px; font-weight: bold; margin-top: 10px;"><a href="tags.php">See More Tags</a></div>
		
		</div>

	<?php
require __DIR__ . "/includes/whos_on_now.php";
?>

		</div>
		</td>
	</tr>
</table>

<?php
require __DIR__ . "/includes/footer.php";
?>
