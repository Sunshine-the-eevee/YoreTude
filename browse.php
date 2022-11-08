<?php
require __DIR__ . "/includes/header.php";

if(isset($_GET['s']) && in_array($_GET['s'], ["mr", "mp", "md", "mf", "r", "rf"])) {
	$browse_sort = $_GET['s'];
} else {
	$browse_sort = "mr";
}

if(isset($_GET['t']) && in_array($_GET['t'], ["t", "w", "m", "a"])) {
	$time = $_GET['t'];
} else {
	$time = "t";
}

if($browse_sort == "rf") {
	$videos = $conn->query("SELECT * FROM featured LEFT JOIN videos ON videos.video_id = featured.video_id LEFT JOIN members ON members.member_id = videos.member_id WHERE videos.converted = 1 ORDER BY featured.feature_id DESC LIMIT 20");
} elseif($browse_sort == "mr") {
	$videos = $conn->query("SELECT * FROM videos LEFT JOIN members ON members.member_id = videos.member_id WHERE videos.converted = 1 ORDER BY uploaded_at DESC LIMIT 20");
} elseif($browse_sort == "mp") {
	if($time == "t") {
		$videos = $conn->query(
			"SELECT * FROM views
			LEFT JOIN videos ON videos.video_id = views.video_id
			LEFT JOIN members ON members.member_id = videos.member_id
			WHERE videos.converted = 1 AND videos.private = 0 AND videos.uploaded_at > DATE_SUB(NOW(), INTERVAL 1 DAY) GROUP BY views.video_id
			ORDER BY COUNT(views.view_id) DESC LIMIT 20"
		);
	} elseif($time == "w") {
		$videos = $conn->query(
			"SELECT * FROM views
			LEFT JOIN videos ON videos.video_id = views.video_id
			LEFT JOIN members ON members.member_id = videos.member_id
			WHERE videos.converted = 1 AND videos.private = 0 AND videos.uploaded_at > DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY views.video_id
			ORDER BY COUNT(views.view_id) DESC LIMIT 20"
		);
	} elseif($time == "m") {
		$videos = $conn->query(
			"SELECT * FROM views
			LEFT JOIN videos ON videos.video_id = views.video_id
			LEFT JOIN members ON members.member_id = videos.member_id
			WHERE videos.converted = 1 AND videos.private = 0 AND videos.uploaded_at > DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY views.video_id
			ORDER BY COUNT(views.view_id) DESC LIMIT 20"
		);
	} elseif($time == "a") {
		$videos = $conn->query(
			"SELECT * FROM views
			LEFT JOIN videos ON videos.video_id = views.video_id
			LEFT JOIN members ON members.member_id = videos.member_id
			WHERE videos.converted = 1  AND videos.private = 0 GROUP BY views.video_id
			ORDER BY COUNT(views.view_id) DESC LIMIT 20"
		);
	}
} elseif($browse_sort == "md") {
	if($time == "t") {
		$videos = $conn->query(
			"SELECT * FROM comments
			LEFT JOIN videos ON videos.video_id = comments.video_id
			LEFT JOIN members ON members.member_id = videos.member_id
			WHERE videos.converted = 1 AND videos.private = 0 AND videos.uploaded_at > DATE_SUB(NOW(), INTERVAL 1 DAY) GROUP BY comments.video_id
			ORDER BY COUNT(comments.comment_id) DESC LIMIT 20"
		);
	} elseif($time == "w") {
		$videos = $conn->query(
			"SELECT * FROM comments
			LEFT JOIN videos ON videos.video_id = comments.video_id
			LEFT JOIN members ON members.member_id = videos.member_id
			WHERE videos.converted = 1 AND videos.private = 0  AND videos.uploaded_at > DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY comments.video_id
			ORDER BY COUNT(comments.comment_id) DESC LIMIT 20"
		);
	} elseif($time == "m") {
		$videos = $conn->query(
			"SELECT * FROM comments
			LEFT JOIN videos ON videos.video_id = comments.video_id
			LEFT JOIN members ON members.member_id = videos.member_id
			WHERE videos.converted = 1 AND videos.uploaded_at > DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY comments.video_id
			ORDER BY COUNT(comments.comment_id) DESC LIMIT 20"
		);
	} elseif($time == "a") {
		$videos = $conn->query(
			"SELECT * FROM comments
			LEFT JOIN videos ON videos.video_id = comments.video_id
			LEFT JOIN members ON members.member_id = videos.member_id
			WHERE videos.converted = 1 AND AND videos.private = 0 GROUP BY comments.video_id
			ORDER BY COUNT(comments.comment_id) DESC LIMIT 20"
		);
	}
} elseif($browse_sort == "mf") {
	if($time == "t") {
		$videos = $conn->query(
			"SELECT * FROM favorites
			LEFT JOIN videos ON videos.video_id = favorites.video_id
			LEFT JOIN members ON members.member_id = videos.member_id
			WHERE videos.converted = 1 AND videos.private = 0 AND videos.uploaded_at > DATE_SUB(NOW(), INTERVAL 1 DAY) GROUP BY favorites.video_id
			ORDER BY COUNT(favorites.favorite_id) DESC LIMIT 20"
		);
	} elseif($time == "w") {
		$videos = $conn->query(
			"SELECT * FROM favorites
			LEFT JOIN videos ON videos.video_id = favorites.video_id
			LEFT JOIN members ON members.member_id = videos.member_id
			WHERE videos.converted = 1 AND videos.private = 0 AND videos.uploaded_at > DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY favorites.video_id
			ORDER BY COUNT(favorites.favorite_id) DESC LIMIT 20"
		);
	} elseif($time == "m") {
		$videos = $conn->query(
			"SELECT * FROM favorites
			LEFT JOIN videos ON videos.video_id = favorites.video_id
			LEFT JOIN members ON members.member_id = videos.member_id
			WHERE videos.converted = 1 AND videos.private = 0 AND videos.uploaded_at > DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY favorites.video_id
			ORDER BY COUNT(favorites.favorite_id) DESC LIMIT 20"
		);
	} elseif($time == "a") {
		$videos = $conn->query(
			"SELECT * FROM favorites
			LEFT JOIN videos ON videos.video_id = favorites.video_id
			LEFT JOIN members ON members.member_id = videos.member_id
			WHERE videos.converted = 1 AND videos.private = 0 GROUP BY favorites.video_id
			ORDER BY COUNT(favorites.favorite_id) DESC LIMIT 20"
		);
	}
} elseif($browse_sort == "r") {
	$videos = $conn->query("SELECT * FROM videos LEFT JOIN members ON members.member_id = videos.member_id WHERE videos.converted = 1 AND videos.private = 0 ORDER BY RAND() DESC LIMIT 20");
}
?>

<table width="80%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#CCCCCC">
	<tr>
		<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
		<td width="100%"><img src="img/pixel.gif" width="1" height="5"></td>
		<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
	</tr>
	<tr>
		<td><img src="img/pixel.gif" width="5" height="1"></td>
		<td>
			<div class="moduleTitleBar">
				<div class="moduleTitle">
				<?php if($browse_sort !== "mr" && $browse_sort !== "r") { ?>
					<div style="float: right; font-weight: normal; font-size: 11px; color: #444444; padding-right: 5px;">
						<?php echo ($time == "t") ? '<strong>Today</strong>' : '<a href="browse.php?s='.$browse_sort.'&t=t">Today</a>'; ?>
						|
						<?php echo ($time == "w") ? '<strong>This Week</strong>' : '<a href="browse.php?s='.$browse_sort.'&t=w">This Week</a>'; ?>
						|
						<?php echo ($time == "m") ? '<strong>This Month</strong>' : '<a href="browse.php?s='.$browse_sort.'&t=m">This Month</a>'; ?>
						|
						<?php echo ($time == "a") ? '<strong>All Time</strong>' : '<a href="browse.php?s='.$browse_sort.'&t=a">All Time</a>'; ?>
					</div>
				<?php } ?>
				<?php
				switch($browse_sort) {
					case 'mp':
						echo "Most Viewed";
						break;
					case 'md':
						echo "Most Discussed";
						break;
					case 'mf':
						echo "Most Added to Favorites";
						break;
					case 'r':
						echo "Random";
						break;
				         case 'rf':
						echo "Recently Featured";
						break;
					default:
						echo "Most Recent";
				}
				?></div>
			</div>
			<div class="moduleFeatured"> 
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<?php
					$i = 0;
					foreach($videos as $video) {
						$i = $i + 1;
						if($i == 1) {
							echo '<tr valign="top">';
						}
						
						$video['views'] = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE video_id = ?");
						$video['views']->execute([$video['video_id']]);
						$video['views'] = $video['views']->fetchColumn();
						
						$video['comments'] = $conn->prepare("SELECT COUNT(comment_id) FROM comments WHERE video_id = ?");
						$video['comments']->execute([$video['video_id']]);
						$video['comments'] = $video['comments']->fetchColumn();
						
						echo '<td width="20%" align="center">'.
						'<a href="watch.php?v='.$video['video_id'].'"><img src="get_still.php?video_id='.$video['video_id'].'" width="120" height="90" class="moduleFeaturedThumb"></a>'.
						'<div class="moduleFeaturedTitle"><a href="watch.php?v='.$video['video_id'].'">'.htmlspecialchars($video['title']).'</a></div>'.
						'<div class="moduleFeaturedDetails">Added: '.str_replace("ago", "ago", timeAgo($video['uploaded_at'])).'<br>by <a href="profile.php?user='.htmlspecialchars($video['username']).'">'.htmlspecialchars($video['username']).'</a></div>'.
						'<div class="moduleFeaturedDetails" style="padding-bottom: 5px;">Runtime: '.gmdate("i:s", $video['duration']).'<br>Views: '.$video['views'].' | Comments: '.$video['comments'].'</div>'.
						'</td>';
						
						if($i == 5) {
							echo '</tr>';
							$i = 0;
						}
					}
					
					?>
				</table>
			</div>
		</td>
		
		<td><img src="img/pixel.gif" width="5" height="1"></td>
	</tr>
	<tr>
		<td><img src="img/box_login_bl.gif" width="5" height="5"></td>
		<td><img src="img/pixel.gif" width="1" height="5"></td>
		<td><img src="img/box_login_br.gif" width="5" height="5"></td>
	</tr>
</table>
<?php
require __DIR__ . "/includes/footer.php";
?>
