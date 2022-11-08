<?php
require __DIR__ . "/includes/header.php";

$video_info = $conn->prepare("SELECT * FROM videos WHERE video_id = ? AND converted = 1");
$video_info->execute([$_GET['v']]);

if($video_info->rowCount() == 0) {
	header("Location: index.php");
	die();
} else {
	$video_info = $video_info->fetch(PDO::FETCH_ASSOC);
}

if($video_info['converted'] == 0) {
	die(require_once './includes/watch_converting.php');
}

$creator_info = $conn->prepare("SELECT * FROM members WHERE member_id = ?");
$creator_info->execute([$video_info['member_id']]);
$creator_info = $creator_info->fetch(PDO::FETCH_ASSOC);

$creator_videos = $conn->prepare("SELECT video_id FROM videos WHERE member_id = ? AND converted = 1");
$creator_videos->execute([$creator_info["member_id"]]);
$creator_favorites = $conn->prepare("SELECT favorite_id FROM favorites WHERE member_id = ?");
$creator_favorites->execute([$creator_info["member_id"]]);
					
$comments = $conn->prepare("SELECT * FROM comments LEFT JOIN members ON members.member_id = comments.member_id WHERE video_id = ? ORDER BY posted_at DESC");
$comments->execute([$video_info['video_id']]);


$views = $conn->prepare("SELECT COUNT(view_id) AS views FROM views WHERE video_id = ?");
$views->execute([$video_info['video_id']]);
$video_info['views'] = $views->fetchColumn();

// Create view if it hasn't already been made within the past 10 minutes (spam prevention)
$already_viewed = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE video_id = ? AND session_id = ? AND viewed_at > DATE_SUB(NOW(), INTERVAL 10 MINUTE)");
$already_viewed->execute([$video_info['video_id'], session_id()]);
if($already_viewed->fetchColumn() == 0) {
	$add_view = $conn->prepare("INSERT INTO views (view_id, video_id, session_id) VALUES (?, ?, ?)");
	$add_view->execute([generateId(26), $video_info['video_id'], session_id()]);
}
?>
<iframe id="invisible" name="invisible" src="" scrolling="yes" width="0" height="0" frameborder="0" marginheight="0" marginwidth="0"></iframe>   
<script type="text/javascript" src="swfobject.js"></script>
<script>
function CheckLogin()
{
	<?php if(!isset($session)) { ?>
		alert("You must be logged in to to perform this action!");
		return false;
	<?php } ?>
		
	return true;
}

function FavoritesHandler()
{
	if (CheckLogin() == false)
		return false;

	alert("Video has been added to Favorites!");
	return true;
}

function CommentHandler()
{

	var comment = document.comment_form.comment;
	var comment_button = document.comment_form.comment_button;

	if (comment.value.length == 0 || comment.value == null)
	{
		alert("You must enter a comment!");
		comment.focus();
		return false;
	}

	if (comment.value.length > 500)
	{
		alert("Your comment must be shorter than 500 characters!");
		comment.focus();
		return false;
	}

	comment_button.disabled='true';
	comment_button.value='Thanks for your comment!';

	return true;
}
</script>
<div style="font-size: 16px; font-weight: bold; color: #333333; padding: 5px; border-top: 1px dotted #CCCCCC;">
		<?php echo htmlspecialchars($video_info['title']); ?>		</div>
<link rel="stylesheet" href="player.css">
<table width="795" align="center" cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<td width="515" style="padding-right: 15px;">
			
			<div style="text-align: center; padding-bottom: 10px;">
				<div id="flashcontent">
					<div style="padding: 20px; font-size:14px; font-weight: bold;">
						Hello, you either have JavaScript turned off or an old version of Macromedia's Flash Player, <a href="https://archive.org/details/fp6_archive">click here</a> to get the latest flash player.
					</div>
				</div>
			</div>
			
			<script type="text/javascript">
			if(swfobject.hasFlashPlayerVersion("6")) {	
				swfobject.embedSWF("player.swf?video_id=<?php echo $video_info['video_id']; ?>&l=<?php echo ceil($video_info['duration']); ?>", "flashcontent", "425", "350", "6");
			} else if(typeof(document.createElement('video').canPlayType) != 'undefined' && document.createElement('video').canPlayType('video/webm;codecs="vp8,opus"') == "probably") {
				document.getElementById('flashcontent').innerHTML = '<figure id="videoContainer" oncontextmenu="return false;"><video id="video" controls preload="metadata" src="/get_video.php?video_id=<?php echo $video_info['video_id'] ?>&format=webm"></video><img class="watermark" src="img/watermark.png"><br><a href="mailto:?subject=<?php echo htmlspecialchars($video_info['title']); ?>&body=http://www.YouTube.com/?v=<?php echo htmlspecialchars($video_info['video_id']); ?>" class="bold">Share This Video</a><div id="video-controls" class="controls" data-state="hidden"><button id="playpause" type="button" data-state="play">Play/Pause</button><div class="progress"><progress id="progress" value="0" min="0"><span id="progress-bar"></span></progress></div><button id="mute" type="button" data-state="mute">Mute/Unmute</button></div></figure></div>';
			}
			</script>
			<script src="player.js"></script>
			<table width="425" cellpadding="0" cellspacing="0" border="0" align="center"><div style="font-size: 12px; font-weight: bold; text-align: center; padding-bottom: 10px;">
			        <a href="#comment">Post Comments</a>
				// <a href="add_favorites.php?video_id=<?php echo htmlspecialchars($video_info['video_id']); ?>" target="invisible" onClick="return FavoritesHandler();">Add to Favorites</a>
				// <a href="outbox.php?user=<?php echo htmlspecialchars($creator_info['username']); ?>&subject=Re: <?php echo htmlspecialchars($video_info['title']); ?>">Contact Me</a><br>
		<table width="400" cellpadding="0" cellspacing="0" border="0" align="center">
			<tr>
				<td style="padding-bottom: 15px;"> 
								
										<div style="float:left; margin-left:5em; padding-right: 18px;">
							<span>Average (230 votes)</span><br/>
								
	<nobr>
									<img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star.gif">
														<img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star.gif">
														<img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star.gif">
														<img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star.gif">
														<img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star_half.gif">
							</nobr>
		
						</div>
														<div id="ratingDiv" style="float:right; margin-right:5em;">
							<span id="ratingMessage">Rate this video!</span><br/>
															<?php if(isset($session)) { ?>
															<a href="rate_video.php?am=1" style="text-decoration:none;"><img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star_bg.gif">
														<a href="rate_video.php?am=2" style="text-decoration:none;"><img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star_bg.gif">
														<a href="rate_video.php?am=3" style="text-decoration:none;"><img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star_bg.gif">
														<a href="rate_video.php?am=4" style="text-decoration:none;"><img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star_bg.gif">
														<a href="rate_video.php?am=5" style="text-decoration:none;"><img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star_bg.gif">
															<?php } else { ?>
																
														<a href="signup.php" style="text-decoration:none;" title="Please sign up and login to rate this video.">
															<img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star_bg.gif">
														<img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star_bg.gif">
														<img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star_bg.gif">
														<img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star_bg.gif">
														<img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star_bg.gif">
															<?php } ?>
	<nobr>
									
							</nobr>
		
</a>
									</div>
					<!-- <br clear="all" />
				</div> -->
						</td>
		</tr>
	</table>
			
	<table width="485" cellpadding="0" cellspacing="0" border="0" align="center">
		<tr>
			<td>
	
			<div class="watchDescription">
			<?php echo htmlspecialchars($video_info['description']); ?>			</div>
			
			<div style="font-size: 11px; padding-bottom: 18px;">
			Added on <?php echo date("F j, Y, h:i a", strtotime($video_info ['uploaded_at']. ' - 17 years')); ?> by <a href="profile.php?user=<?php echo htmlspecialchars($creator_info['username']); ?>"><?php echo htmlspecialchars($creator_info['username']);
            ?></a>
			</div>
			
			</td>
		</tr>
	</table>
			
	<table width="485" cellpadding="0" cellspacing="0" border="0" align="center">
		<tr valign="top">
			<td width="245" style="border-right: 1px dotted #AAAAAA; padding-right: 5px;">
			<div style="font-weight: bold; color:#003399; padding-bottom: 7px;">Video Details //</div>
			
			<div style="font-size: 11px; padding-bottom: 10px;">
			Runtime: <?php echo gmdate("i:s", $video_info['duration']); ?> | Views: <?php echo $video_info['views']; ?> | <a href="#comment">Comments</a>: <?php echo $comments->rowCount(); ?>				</div>
			
			<div style="padding-bottom: 10px;"><span style="background-color: #FFFFAA; padding: 2px;">Tags:</span>&nbsp; <?php
						foreach(explode(" ", $video_info['tags']) as $tag) {
							echo '<a href="results.php?search='.htmlspecialchars($tag).'">'.htmlspecialchars($tag).'</a>, ';
						}
						?>			</div>

			<div style="padding-bottom: 10px;"><span style="background-color: #FFFFAA; padding: 2px;">Channels:</span>&nbsp; 
						<a href="channels.php?c=5">Entertainment</a>

						, 
						<a href="channels.php?c=8">Hobbies &amp; Interests</a>

						, 
						<a href="channels.php?c=9">Humor</a>

						, 
						<a href="channels.php?c=10">Music</a>

						, 
						<a href="channels.php?c=12">Odd &amp; Outrageous</a>

						</div>			
			<div style="font-size: 11px; padding-bottom: 10px;">
						</div>
			
			</td>
			<td width="240" style="padding-left: 10px;">
			<div style="font-weight: bold; font-size: 12px; color:#003399; padding-bottom: 7px;">User Details //</div>
			
						
			<div style="font-size: 11px; padding-bottom: 10px;">
			 <a href="profile_videos.php?user=<?php echo htmlspecialchars($creator_info['username']); ?>">Videos</a>: <?php echo $creator_videos->rowCount(); ?>			 | <a href="profile_favorites.php?user=<?php echo htmlspecialchars($creator_info['username']); ?>">Favorites</a>: <?php echo $creator_favorites->rowCount(); ?>			 | <a href="profile_friends.php?user=<?php echo htmlspecialchars($creator_info['username']); ?>">Friends</a>: 0			</div>
			
			<div style="padding-bottom: 5px;">
			<span style="background-color: #FFFFAA; padding: 2px;">User Name:</span>&nbsp; <a href="profile.php?user=<?php echo htmlspecialchars($creator_info['username']); ?>"><?php echo htmlspecialchars($creator_info['username']); ?></a>
			</div>
			
			<div style="padding-bottom: 10px;">
									<div style="padding-bottom: 10px;">I was on the site <?php echo str_replace("ago", "ago", timeAgo($creator_info['last_login'])); ?>.
							</div>
			
			<div style="font-weight: bold; padding-bottom: 10px;">
						<a href="outbox.php?user=<?php echo htmlspecialchars($creator_info['username']); ?>">Send Me a Private Message!</a>
						</div>

		</td>
	</tr>
</table>
<br/>
		<!-- watchTable -->

		<table width="485" cellpadding="0" cellspacing="0" border="0" align="center" style="table-layout: fixed;">
          <tr>
            <td>
				<form name="linkForm" id="linkForm">
				  <table width="485" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
                    <tr>
                      <td width="33%">
					  <div align="left" style="font-weight: bold; font-size: 12px; color:#003399; padding-bottom: 7px;">
					  	Share Details // &nbsp;<a href="sharing.php">Help? </a>
					  </div>					  </td>
                      <td width="67%">&nbsp;</td>
                    </tr>
                    <tr>
                      <td valign="top"><span style="background-color: #FFFFAA; padding: 2px;">Video URL (Permalink):</span><font style="color: #000000;">&nbsp;&nbsp;</font> </td>
                      <td valign="top"><input name="video_link" type="text" onclick="javascript:document.linkForm.video_link.focus();document.linkForm.video_link.select();" value="http://www.YouTube.com/?v=<?php echo htmlspecialchars($video_info['video_id']); ?>" style="width: 300px;" readonly="true" style="font-size: 10px;">
                        
                        <div style="font-size: 11px;">(E-mail or link it)<br>
                          <br>
                        </div>                      
					  </td>
                    </tr>
                    <tr>
                      <td valign="top"><span style="background-color: #FFFFAA; padding: 2px;">Embeddable Player:</span><font style="color: #000000;">&nbsp;&nbsp;</font> </td>
                      <td valign="top"><input name="video_play" type="text" onclick="javascript:document.linkForm.video_play.focus();document.linkForm.video_play.select();" value="<iframe src=&quot;http://www.YouTube.com/embed.php?v=<?php echo htmlspecialchars($video_info['video_id']); ?>&l=<?php echo $video_info['duration']; ?>" style="width: 300px;" readonly="true" style="font-size: 10px; text-align: center;">
                      <div style="font-size: 11px;">(Put this video on your website. Works on Friendster, eBay, Blogger, MySpace!)<br>
                        <br>
                      </div></td>
                    </tr>
					
					
													<tr>
								<td colspan="2" valign="top">
								<span style="background-color: #FFFFAA; padding: 2px;">Sites linking to this video:</span>
									<div style="font-size: 11px; padding-bottom: 7px;"></div>
								
								&#187; <b>3866 clicks from </b><a href="r.php?u=http%3A%2F%2Fcuriosoperoinutil.blogspot.com%2F">http://curiosoperoinutil.blogspot.com/</a><br>&#187; <b>1988 clicks from </b><a href="r.php?u=http%3A%2F%2Fblog.suziexxx.com%2F">http://blog.suziexxx.com/</a><br>&#187; <b>1389 clicks from </b><a href="r.php?u=http%3A%2F%2Fwww.myspace.com%2F">http://www.myspace.com/</a><br>&#187; <b>904 clicks from </b><a href="r.php?u=http%3A%2F%2Fwww.foto-aficion.com%2Fblog%2F">http://www.foto-aficion.com/blog/</a><br>&#187; <b>652 clicks from </b><a href="r.php?u=http%3A%2F%2Fwww.basicthinking.de%2Fblog%2F">http://www.basicthinking.de/blog/</a><br>								</td>
								</tr>
								
              </table>
			</form>
		    </td>
          </tr>
        </table>

		<br>

			<a name="comment"></a>
			<div style="padding-bottom: 5px; font-weight: bold; color: #444;">Comment on this video:</div>
			<form name="comment_form" id="comment_form" method="post" action="add_comment.php" target="invisible" onSubmit="return CommentHandler();">
				<input type="hidden" name="video_id" value="<?php echo htmlspecialchars($video_info['video_id']); ?>">
				<textarea name="comment" cols="55" rows="3"></textarea>
				<br>
				<input type="submit" name="comment_button" value="Add Comment">
			</form><br>
			
			<div class="commentsTitle">Comments (<?php echo $comments->rowCount(); ?>):</div>
			<?php
			if($comments !== false) {
				foreach($comments as $comment) {
					$comment_videos = $conn->prepare("SELECT COUNT(video_id) FROM videos WHERE member_id = ? AND converted = 1");
					$comment_videos->execute([$comment["member_id"]]);
					
					$comment_favorites = $conn->prepare("SELECT COUNT(favorite_id) FROM favorites WHERE member_id = ?");
					$comment_favorites->execute([$comment["member_id"]]);
					
					echo '<div class="commentsEntry">"'.htmlspecialchars($comment['body']).'"<br>- <a href="profile.php?user='.htmlspecialchars($comment['username']).'">'.htmlspecialchars($comment['username']).'</a> // <a href="profile_videos.php?user='.htmlspecialchars($comment['username']).'">Videos</a> ('.$comment_videos->fetchColumn().') | <a href="profile_favorites.php?user='.htmlspecialchars($comment['username']).'">Favorites</a> ('.$comment_favorites->fetchColumn().') - ('.timeAgo($comment['posted_at']).')</div>';
				}
			}
			?><a name="flag"></a>
            <table width="495" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFEEBB" style="margin-top: 10px;">
			<tbody><tr>
				<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
				<td><img src="/img/pixel.gif" width="1" height="5"></td>
				<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="/img/pixel.gif" width="5" height="1"></td>
				<td width="485" style="padding: 5px 5px 10px 5px; text-align: center;">
				<div style="font-size: 14px; padding-bottom: 5px;">
				Please help keep this site <strong>FUN</strong>, <strong>CLEAN</strong>, and <strong>REAL</strong>.
				</div>
				
				<div style="font-size: 12px;">
				Flag this video:&nbsp;
				<a href="flag_video.php?v=<?php echo htmlspecialchars($video_info['video_id']); ?>&amp;flag=I">Inappropriate</a> &nbsp; 
				<a href="flag_video.php?v=<?php echo htmlspecialchars($video_info['video_id']); ?>&amp;flag=M">Miscategorized</a>
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
		</td>
		<td width="280">
		<div style="padding-bottom: 10px;">
						<table width="280" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#EEEEEE">
							<tbody><tr>
								<td><img src="/img/box_login_tl.gif" width="5" height="5"></td>
								<td><img src="/img/pixel.gif" width="1" height="5"></td>
								<td><img src="/img/box_login_tr.gif" width="5" height="5"></td>
							</tr>
							<tr>
								<td><img src="/img/pixel.gif" width="5" height="1"></td>
								<td width="270" style="padding: 5px 0px 5px 0px;">
							
								<table width="270" cellpadding="0" cellspacing="0" border="0">
									<tbody><tr>
																				<td align="center"><img src="/img/no_prev.gif" width="60" height="45" style="border: 5px solid #FFFFFF;">
										<div style="font-size: 10px; font-weight: bold; padding-top: 3px;">&lt; PREV</div></td>
																				<td align="center"><img src="/get_still.php?video_id=<?php echo htmlspecialchars($video_info['video_id']); ?>" width="80" height="60" style="border: 5px solid #FFFFFF;">
										<div style="font-size: 10px; font-weight: bold; padding-top: 3px;">NOW PLAYING</div></td>
										<td align="center"><a href="watch.php?v=aErcsRSmLjM"><img src="/img/no_after.gif" width="60" height="45" style="border: 5px solid #FFFFFF;"></a>
										<div style="font-size: 10px; font-weight: bold; padding-top: 3px;"><a href="watch.php?v=<?php echo htmlspecialchars($video_info['video_id']); ?>">NEXT &gt;</a></div></td>
									</tr>
								</tbody></table>
								
								</td>
								<td><img src="/img/pixel.gif" width="5" height="1"></td>
							</tr>
							<tr>
								<td><img src="/img/box_login_bl.gif" width="5" height="5"></td>
								<td><img src="/img/pixel.gif" width="1" height="5"></td>
								<td><img src="/img/box_login_br.gif" width="5" height="5"></td>
							</tr>
						</tbody></table>
						</div><br>
			<table width="280" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#CCCCCC">
				<tr>
					<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
					<td><img src="img/pixel.gif" width="1" height="5"></td>
					<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
				</tr>
				<tr>
					<td><img src="img/pixel.gif" width="5" height="1"></td>
					<td width="270">
					<div class="moduleTitleBar">
					<table width="270" cellpadding="0" cellspacing="0" border="0">
							<tr valign="top">
								<?php
								$search = str_replace(" ", "|", $video_info['tags']);
								$results = $conn->prepare("SELECT tags FROM videos LEFT JOIN members ON members.member_id = videos.member_id WHERE videos.tags REGEXP ? AND videos.converted = 1 ORDER BY videos.uploaded_at DESC"); // Regex!
								$results->execute([$search]);
								?>
								<td><div class="moduleFrameBarTitle">Related Videos (<?php echo $results->rowCount(); ?> of <?php echo $results->rowCount(); ?>)</div></td>
								<td align="right"><div style="font-size: 11px; margin-right: 5px;"><a href="results.php?&search=<?php echo htmlspecialchars($video_info['tags']); ?>" target="_parent">See All Results</a></div></td>
							</tr>
						</table>
					</div>
					<iframe id="side_results" name="side_results" src="include_results.php?v=<?php echo $video_info['video_id']; ?>&search=<?php echo htmlspecialchars($video_info['tags']); ?>#selected" scrolling="auto" width="270" height="400" frameborder="0" marginheight="0" marginwidth="0">
						[Content for browsers that don't support iframes goes here]
					</iframe>
					</td>
					<td><img src="img/pixel.gif" width="5" height="1"></td>
				</tr>
				<tr>
					<td><img src="img/box_login_bl.gif" width="5" height="5"></td>
					<td><img src="img/pixel.gif" width="1" height="5"></td>
					<td><img src="img/box_login_br.gif" width="5" height="5"></td>
				</tr>
			</table><br><div style="font-weight: bold; color: #333; margin: 10px 0px 5px 0px;">Honors:</div><br>
            <div style="padding: 0px 0px 5px 0px; color: #999;">» <a href="browse.php?s=rf">Recently Featured</a></div>
			<div style="font-weight: bold; color: #333; margin: 10px 0px 5px 0px;">Related tags:</div>
			<?php
			$related_tags = [];
			foreach($results as $result) $related_tags = array_merge($related_tags, explode(" ", $result['tags']));
			$related_tags = array_unique($related_tags);
			?>
			<?php foreach($related_tags as $tag) { ?>
			<div style="padding: 0px 0px 5px 0px; color: #999;">&#187; <a href="results.php?search=<?php echo htmlspecialchars($tag); ?>"><?php echo htmlspecialchars($tag); ?></a></div>
			<?php } ?>
		</td>
	</tr>
	</td>
</table>

<?php 
require __DIR__ . "/includes/footer.php";
?>
