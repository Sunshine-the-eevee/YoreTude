<?php
require __DIR__ . "/functions.php";

preg_match('/^\/(\w+)/', $_SERVER['REQUEST_URI'], $matches, PREG_OFFSET_CAPTURE, 0);
if(empty($matches[0])) $matches[1][0] = "index"; // If the request URI is just "/", then we can just say it's "index"
$current_page = $matches[1][0]; // If the current page is "/index.php", $current_page will be "index".

// If a user is logged in, information about that user will be available in $session.
if(isset($_SESSION['member_id'])) {
	$session = $conn->prepare("SELECT * FROM members WHERE member_id = ?");
	$session->execute([$_SESSION['member_id']]);
	if($session->rowCount() == 0) {
		header("Location: logout.php");
		die();
	} else {
		$session = $session->fetch(PDO::FETCH_ASSOC);
	}
}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>YouTube - Rebroadcast Yourself.</title>
		<meta name="description" content="Share your videos with friends and family">
	<meta name="keywords" content="video,sharing,camera phone,video phone,revival, recreation, YouTube, 2005, YouTube ">
	<style>
		.tbody {
			position: relative
		}
	</style>
		
	<link href="./styles.css" rel="stylesheet" type="text/css">
	<table width="800" cellpadding="0" cellspacing="0" border="0" align="center">
	<tr>
		<td bgcolor="#FFFFFF" style="padding-bottom: 25px;">
		

<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<td width="130" rowspan="2" style="padding: 0px 5px 5px 5px;"><a href="index.php"><img src="./img/logo_sm.gif" width="120" height="48" alt="YouTube" border="0" style="vertical-align: middle; "></a></td>
		<td valign="top">
		
		<table width="670" cellpadding="0" cellspacing="0" border="0">
			<tr valign="top">
				<td style="padding: 0px 5px 0px 5px; font-style: italic;">Upload, tag and share your videos worldwide!</td>
				<td align="right">
				
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
		
							
						<?php if(isset($session)) { ?>	
						<td>Hello, <a href="profile.php?user=<?php echo htmlspecialchars($session['username']); ?>"><?php echo htmlspecialchars($session['username']); ?></a>!        <img src="/img/mail.gif" border="0"> (<a href="./my_messages.php">0</a>)</td>
						<td style="padding: 0px 5px 0px 5px;">|</td>
						<td><a href="logout.php?next=<?php echo $_SERVER['REQUEST_URI'] ?>">Log Out</a></td>
						<td style="padding: 0px 5px 0px 5px;">|</td>
						<td style="padding-right: 5px;"><a href="help.php">Help</a></td>
						<?php } else { ?>
						<td><a href="signup.php"><strong>Sign Up</strong></a></td>
						<td style="padding: 0px 5px 0px 5px;">|</td>
						<td><a href="login.php">Log In</a></td>
						<td style="padding: 0px 5px 0px 5px;">|</td>
						<td style="padding-right: 5px;"><a href="help.php">Help</a></td>
		                                <?php } ?>
		
										
					</tr>
				</table>
				
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr valign="bottom">
		<td>
		
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>
				<table style=" background-color: #BECEEE; <?php echo ($current_page == "index") ? 'background-color: #DDDDDD;' : ''; ?> margin: 5px 2px 1px 0px; "  cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td><img src="./img/box_login_tl.gif" width="5" height="5"></td>
						<td><img src="./img/pixel.gif" width="1" height="5"></td>
						<td><img src="./img/box_login_tr.gif" width="5" height="5"></td>
					</tr>
					<tr>
						<td><img src="./img/pixel.gif" width="5" height="1"></td>
						<td style="padding: 0px 20px 5px 20px; font-size: 13px; font-weight: bold;"><a href="index.php">Home</a></td>
						<td><img src="./img/pixel.gif" width="5" height="1"></td>
					</tr>
				</table>
				</td>
				<td>
				<table style=" background-color: #BECEEE; <?php echo ($current_page == "browse") ? 'background-color: #DDDDDD;' : '';  ?> <?php echo ($current_page == "watch") ? 'background-color: #DDDDDD;' : ''; ?> margin: 5px 2px 1px 0px; " cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td><img src="./img/box_login_tl.gif" width="5" height="5"></td>
						<td><img src="./img/pixel.gif" width="1" height="5"></td>
						<td><img src="./img/box_login_tr.gif" width="5" height="5"></td>
					</tr>
					<tr>
						<td><img src="./img/pixel.gif" width="5" height="1"></td>
						<td style="padding: 0px 20px 5px 20px; font-size: 13px; font-weight: bold;"><a href="browse.php">Videos</a></td>
						<td><img src="./img/pixel.gif" width="5" height="1"></td>
					</tr>
				</table>
				</td>
				<td>
				<table style=" background-color: #BECEEE; <?php echo ($current_page == "channels") ? 'background-color: #DDDDDD;' : ''; ?> margin: 5px 2px 1px 0px; " cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td><img src="./img/box_login_tl.gif" width="5" height="5"></td>
						<td><img src="./img/pixel.gif" width="1" height="5"></td>
						<td><img src="./img/box_login_tr.gif" width="5" height="5"></td>
					</tr>
					<tr>
						<td><img src="./img/pixel.gif" width="5" height="1"></td>
						<td style="padding: 0px 20px 5px 20px; font-size: 13px; font-weight: bold;"><a href="channels.php">Channels</a> <img src="./img/new.gif"></td>
						<td><img src="./img/pixel.gif" width="5" height="1"></td>
					</tr>
				</table>
				</td>
				<td>
				<table style=" background-color: #BECEEE; <?php echo ($current_page == "my_videos_upload") ? 'background-color: #DDDDDD;' : ''; ?> margin: 5px 2px 1px 0px; " cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td><img src="./img/box_login_tl.gif" width="5" height="5"></td>
						<td><img src="./img/pixel.gif" width="1" height="5"></td>
						<td><img src="./img/box_login_tr.gif" width="5" height="5"></td>
					</tr>
					<tr>
						<td><img src="./img/pixel.gif" width="5" height="1"></td>
						<td style="padding: 0px 20px 5px 20px; font-size: 13px; font-weight: bold;"><a href="my_videos_upload.php">Upload</a></td>
						<td><img src="./img/pixel.gif" width="5" height="1"></td>
					</tr>
				</table>
				</td>
				<td>
				<table style=" background-color: #BECEEE; <?php echo ($current_page == "my_friends_invite") ? 'background-color: #DDDDDD;' : ''; ?> margin: 5px 2px 1px 0px; " cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td><img src="./img/box_login_tl.gif" width="5" height="5"></td>
						<td><img src="./img/pixel.gif" width="1" height="5"></td>
						<td><img src="./img/box_login_tr.gif" width="5" height="5"></td>
					</tr>
					<tr>
						<td><img src="./img/pixel.gif" width="5" height="1"></td>
						<td style="padding: 0px 20px 5px 20px; font-size: 13px; font-weight: bold;"><a href="my_friends_invite.php">Invite Friends</a></td>
						<td><img src="./img/pixel.gif" width="5" height="1"></td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	
</table>

<table align="center" width="800" bgcolor="#DDDDDD" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 10px;">
	<tr>
		<td><img src="./img/box_login_tl.gif" width="5" height="5"></td>
		<td><img src="./img/pixel.gif" width="1" height="5"></td>
		<td><img src="./img/box_login_tr.gif" width="5" height="5"></td>
	</tr>
	<tr>
		<td><img src="./img/pixel.gif" width="5" height="1"></td>
		<td width="790" align="center" style="padding: 2px;">

		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td style="font-size: 10px;">&nbsp;</td>
				
								
				<?php if($current_page === "index") { ?>				
				<td style="  "><a href="my_videos.php">My Videos</a></td>
				<td style="padding: 0px 10px 0px 10px;">|</td>
				<td style="  "><a href="my_favorites.php">My Favorites</a></td>
				<td style="padding: 0px 10px 0px 10px;">|</td>
				<td style="  "><a href="my_friends.php">My Friends</a></td>
				<!-- <td>&nbsp;|&nbsp;</td>
				<td style="  "><a href="my_groups.php">My Groups</a></td> -->
				<td style="padding: 0px 10px 0px 10px;">|</td>
				<td style="  "><a href="my_messages.php" >My Messages</a></td>
				<td style="padding: 0px 10px 0px 10px;">|</td>
				<td style="  "><a href="my_profile.php">My Profile</a></td>
				<?php } ?>
                <?php if($current_page === "browse") { ?>				
				<td style="font-size: 10px;">&nbsp;</td>
				<td><a href="/browse.php?s=mr">Most Recent</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=mp">Most Viewed</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=md">Most Discussed</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=mf" >Top Favorites</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=tr">Top Rated</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=rf">Recently Featured</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=r">Random</a></td>
				<td style="font-size: 10px;">&nbsp;</td>
                <?php } ?>
                <?php if($current_page === "watch") { ?>				
				
                   
			
				<td style="font-size: 10px;">&nbsp;</td>
				<td><a href="/browse.php?s=mr">Most Recent</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=mp">Most Viewed</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=md">Most Discussed</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=mf">Top Favorites</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=tr">Top Rated</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=rf">Recently Featured</a></td>
					<td style="padding: 0px 10px 0px 10px;">|</td>
				<td><a href="/browse.php?s=r">Random</a></td>
				<td style="font-size: 10px;">&nbsp;</td>
                <?php } ?>
				
								
								
								
				<td style="font-size: 10px;">&nbsp;</td>
			</tr>
		</table>
			
		</td>
		<td><img src="./img/pixel.gif" width="5" height="1"></td>
	</tr>
	<tr>
		<td style="border-bottom: 1px solid #FFFFFF"><img src="./img/box_login_bl.gif" width="5" height="5"></td>
		<td style="border-bottom: 1px solid #BBBBBB"><img src="./img/pixel.gif" width="1" height="5"></td>
		<td style="border-bottom: 1px solid #FFFFFF"><img src="./img/box_login_br.gif" width="5" height="5"></td>
	</tr>
</table>

<div style="padding: 0px 5px 0px 5px;">

<table align="center" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 10px;">
	<tr>
		<?php if(in_array($current_page, ["watch", "browse", "results", "index"], true)) { ?>
		<form name="searchForm" id="searchForm" method="GET" action="results.php">
	 		<td style="padding-right: 5px;"><input tabindex="1" type="text" value="<?php echo (isset($_GET['search'])) ? htmlspecialchars($_GET['search']) : ''; ?>" name="search" maxlength="128" style="color:#ff3333; font-size: 12px; width: 300px;"></td>
			<td><input type="submit" value="Search Videos"></td>
		</form>
		<script language="javascript">
			onLoadFunctionList.push(function () { document.searchForm.search.focus(); });
		</script>
		<?php } ?>
	</tr>
</table>

<table width="790" align="center" cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<table width="595" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="">
			<tr>
				<td><img src="./img/box_login_tl.gif" width="5" height="5"></td>
				<td width="100%"><img src="./img/pixel.gif" width="1" height="5"></td>
				<td><img src="./img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="./img/pixel.gif" width="5" height="1"></td>
<?php if(isset($_SESSION['error'])) { ?>
				<table width="100%" align="center" bgcolor="#FF0000" cellpadding="6" cellspacing="3" border="0">
		<tbody><tr>
			<td align="center" bgcolor="#FFFFFF"><span class="error"><?php echo htmlspecialchars($_SESSION['error']) ?></span></td>
		</tr>
	</tbody></table>
                <?php unset($_SESSION['error']); } ?>
