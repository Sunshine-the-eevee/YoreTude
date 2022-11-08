<?php
require __DIR__ . "/includes/header.php";

if(!isset($_GET['user'])) require __DIR__ . "/index_down.php";

$profile = $conn->prepare("SELECT * FROM members WHERE members.username = ?");
$profile->execute([$_GET['user']]);

if($profile->rowCount() == 0) {
	header("Location: index_down.php");
} else {
	$profile = $profile->fetch(PDO::FETCH_ASSOC);
}

$profile['videos'] = $conn->prepare("SELECT video_id FROM videos WHERE member_id = ? AND converted = 1");
$profile['videos']->execute([$profile["member_id"]]);
$profile['videos'] = $profile['videos']->rowCount();
$profile['favorites'] = $conn->prepare("SELECT favorite_id FROM favorites WHERE member_id = ?");
$profile['favorites']->execute([$profile["member_id"]]);
$profile['favorites'] = $profile['favorites']->rowCount();

$profile_latest_video = $conn->prepare(
	"SELECT * FROM videos
	LEFT JOIN members ON members.member_id = videos.member_id
	WHERE videos.member_id = ? AND videos.converted = 1
	GROUP BY videos.video_id
	ORDER BY videos.uploaded_at DESC LIMIT 1"
);
$profile_latest_video->execute([$profile['member_id']]);

if($profile_latest_video->rowCount() == 0) {
	$profile_latest_video = false;
} else {
	$profile_latest_video = $profile_latest_video->fetch(PDO::FETCH_ASSOC);
	
	$profile_latest_video['views'] = $conn->prepare("SELECT COUNT(view_id) AS views FROM views WHERE video_id = ?");
	$profile_latest_video['views']->execute([$profile_latest_video['video_id']]);
	$profile_latest_video['views'] = $profile_latest_video['views']->fetchColumn();
	
	$profile_latest_video['comments'] = $conn->prepare("SELECT COUNT(comment_id) AS comments FROM comments WHERE video_id = ?");
	$profile_latest_video['comments']->execute([$profile_latest_video['video_id']]);
	$profile_latest_video['comments'] = $profile_latest_video['comments']->fetchColumn();
}
?>

<table align="center" width="800" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 10px;">
	<tr>
		<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
		<td><img src="/img/pixel.gif" width="1" height="5"></td>
		<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
	</tr>
	<tr>
		<td><img src="/img/pixel.gif" width="5" height="1"></td>
		<td width="790" align="center" style="padding: 2px;">

		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td style="font-size: 10px;">&nbsp;</td>
				
								
								
								
								
				<td style="font-size: 10px;">&nbsp;</td>
			</tr>
		</table>
			
		</td>
		<td><img src="/img/pixel.gif" width="5" height="1"></td>
	</tr>
	<tr>
	</tr>
</table>

<div style="padding: 0px 5px 0px 5px;">

<div>
<table align="center" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td><strong>Profile</strong></td>
		<td style="padding: 0px 5px 0px 5px;">|</td>
		<td><a href="profile_videos.php?user=<?php echo htmlspecialchars($profile["username"]); ?>">Public Videos</a> (<?php echo $profile['videos']; ?>)</td>
		<td style="padding: 0px 5px 0px 5px;">|</td>
		<td><a href="profile_videos_private.php?user=<?php echo htmlspecialchars($profile["username"]); ?>">Private Videos</a> (0)</td>
		<td style="padding: 0px 5px 0px 5px;">|</td>
		<td><a href="profile_favorites.php?user=<?php echo htmlspecialchars($profile["username"]); ?>">Favorites</a> (<?php echo $profile['favorites']; ?>)</td>
		<td style="padding: 0px 5px 0px 5px;">|</td>
		<td><a href="profile_friends.php?user=<?php echo htmlspecialchars($profile["username"]); ?>">Friends</a> (0)</td>
	</tr>
</table>
</div><br><br>

<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		
		<td width="595" style="padding-right: 15px;">
		<div style="border: 1px solid #CCCCCC; padding: 15px 15px 30px 15px;">
		<div style="font-size: 18px; font-weight: bold; color: #CC6633; margin-bottom: 2px;">Hello. I'm <?php echo htmlspecialchars($profile["username"]); ?>.</div>
				
		<div style="font-size: 14px; font-weight: bold; color: #999999;">I have watched 0 videos!</div>

			<!-- Personal Information: -->
			
			<div class="profileLabel">Last Login:</div>
		 <?php echo str_replace("ago", "ago", timeAgo($profile['last_login'])); ?>			
			<div class="profileLabel">Signed up:</div>
			 <?php echo str_replace("ago", "ago", timeAgo($profile['created_at'])); ?>
				<?php if(!empty($profile['name'])) { ?>		
				<div class="profileLabel">Name:</div>
				<?php echo htmlspecialchars($profile['name']); ?> 			
				<?php } ?>	
			    <?php if(!empty($profile['birthday'])) { ?>
				<div class="profileLabel">Age:</div>
				<?php echo str_replace("ago", "old", timeAgo($profile['birthday'])); ?>			
				<?php } ?>	
				<?php if(!empty($profile['gender']) && $profile['gender'] !== 0) { ?>		
				<div class="profileLabel">Gender:</div>
				<?php
					switch($profile['gender']) {
						case '0':
							break;
						case '1':
							echo "Male";
							break;
						case '2':
							echo "Female";
							break;
					}
				?>
                <?php } ?>			
				<?php if(!empty($profile['about_me'])) { ?>			
				<div class="profileLabel">About Me:</div>
				<?php echo htmlspecialchars($profile['about_me']); ?>			
				<?php } ?>
               <?php if(!empty($profile['relationship_status']) && $profile['relationship_status'] !== 0) { ?>
                <div class="profileLabel">Relationship:</div>
				<?php
					switch($profile['gender']) {
						case '0':
							break;
						case '1':
							echo "Single";
							break;
						case '2':
							echo "Taken";
							break;
					}
				?>
                <?php } ?>
                 <?php if(!empty($profile['personal_website'])) { ?>	
                <div class="profileLabel">Personal Website:</div>
				<?php echo htmlspecialchars($profile['personal_website']); ?>
                <?php } ?>
						
			<!-- Location Information -->
		
					<?php if(!empty($profile['hometown'])) { ?>		
				<div class="profileLabel">Hometown:</div>
				<?php echo htmlspecialchars($profile['hometown']); ?>
                <?php } ?>			
					<?php if(!empty($profile['current_country'])) { ?>		
				<div class="profileLabel">Current Country:</div>
				<?php echo htmlspecialchars($profile['current_country']); ?>
                <?php } ?>			
					
					
				<?php if(!empty($profile['current_city'])) { ?>		
				<div class="profileLabel">Current City:</div>
				<?php echo htmlspecialchars($profile['current_city']); ?>
                <?php } ?>			
				<?php if(!empty($profile['schools'])) { ?>		
				<div class="profileLabel">Schools:</div>
				<?php echo htmlspecialchars($profile['schools']); ?>
                <?php } ?>	
                <?php if(!empty($profile['companies'])) { ?>		
				<div class="profileLabel">Companies:</div>
				<?php echo htmlspecialchars($profile['companies']); ?>
                <?php } ?>
				<?php if(!empty($profile['occupations'])) { ?>		
				<div class="profileLabel">Occupations:</div>
				<?php echo htmlspecialchars($profile['occupations']); ?>	
                <?php } ?>		
					
					
				
				<?php if(!empty($profile['interests_and_hobbies'])) { ?>		
				<div class="profileLabel">Interests &amp; Hobbies:</div>
				<?php echo htmlspecialchars($profile['interests_and_hobbies']); ?>			
				<?php } ?>	
				<?php if(!empty($profile['favorite_movies_and_shows'])) { ?>		
				<div class="profileLabel">Favorite Movies &amp; Shows:</div>
				<?php echo htmlspecialchars($profile['favorite_movies_and_shows']); ?>			
				<?php } ?>	
                <?php if(!empty($profile['favorite_music'])) { ?>	
				<div class="profileLabel">Favorite Music:</div>
				<?php echo htmlspecialchars($profile['favorite_music']); ?>				
				<?php } ?>	
                <?php if(!empty($profile['favorite_books'])) { ?>	
				<div class="profileLabel">Favorite Books:</div>
				<?php echo htmlspecialchars($profile['favorite_books']); ?>				
				<?php } ?>	
					
		</div>
		</td>
		
		<td width="180">
		
		<table width="180" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#E5ECF9">
			<tr>
				<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
				<td width="170"><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
				<td align="center" style="padding: 5px;">
				<?php if($profile_latest_video !== false) { ?>
					<div style="font-size: 14px; font-weight: bold; color: #003366;">Latest Video Added</div>
				<div style="padding: 5px; text-align: center;">
					<div style="padding-bottom: 10px;">
			
				<a href="watch.php?v=<?php echo htmlspecialchars($profile_latest_video['video_id']); ?>"><img src="get_still.php?video_id=<?php echo htmlspecialchars($profile_latest_video['video_id']); ?>" class="moduleFeaturedThumb" width="120" height="90"></a>
				<div class="moduleFeaturedTitle"><a href="watch.php?v=<?php echo htmlspecialchars($profile_latest_video['video_id']); ?>"><?php echo htmlspecialchars($profile_latest_video['title']); ?></a></div>
				<div class="moduleFeaturedDetails">Added: <?php echo str_replace("ago", "ago", timeAgo($profile_latest_video['uploaded_at'])); ?></div>
				<div class="moduleFeaturedDetails">Views: <?php echo $profile_latest_video['views']; ?></div>
				<div class="moduleFeaturedDetails">Comments: <?php echo $profile_latest_video['comments']; ?></div><br>
				<?php } ?>
				
					</div>
		
									
				
						<?php if(isset($session)) { ?>	
					<form method="post" action="my_friends_add.php?user=<?php echo htmlspecialchars($profile["username"]); ?>">
				<div style="padding-bottom: 10px;"> <input type="submit" value="Add Me!"><br>
					<?php } else { ?>
					<div style="padding-bottom: 10px;"><a href="signup.php">Sign up</a> or <a href="login.php">log in</a> to add <?php echo htmlspecialchars($profile["username"]); ?> as friend.<br><br>
						<?php } ?>
				
										
				<form method="post" action="outbox.php?user=<?php echo htmlspecialchars($profile["username"]); ?>">
				<input type="submit" value="Send Message">
				</form>
				
				</div>
				
				</td>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
			</tr>
			<!-- Placeholder, replace this when we get a real Who's On Now -->
            <div style="padding-top: 15px;">

					</div>
					</td>
					<td><img src="/img/pixel.gif" width="5" height="1"></td>
				</tr>
				<tr>
					<td><img src="/img/box_login_bl.gif" width="5" height="5"></td>
					<td><img src="/img/pixel.gif" width="1" height="5"></td>
					<td><img src="/img/box_login_br.gif" width="5" height="5"></td>
				</tr>
				</tbody></table>
		</div>
        <!-- End of placeholder -->
<tbody><tr>
		<td align="center" valign="center"><span class="footer"><?php
require __DIR__ . "/includes/footer.php";
?> </span></td>
	</tr>
</tbody>

</center>
