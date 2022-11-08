	<?php
    require __DIR__ . "/functions.php";
$views = $conn->query("SELECT * FROM views LEFT JOIN videos ON videos.video_id = views.video_id  ORDER BY views.viewed_at DESC LIMIT 5");
$vista = $conn->prepare("SELECT * FROM views ORDER BY views.viewed_at DESC LIMIT 5");
?>
<!DOCTYPE html>
	<link href="http://YouTube.com/styles.css" rel="stylesheet" type="text/css">
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
        </html>
