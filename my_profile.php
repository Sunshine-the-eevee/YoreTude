<?php
require __DIR__ . "/includes/header.php";

if(!isset($_SESSION['member_id'])) {
    die(header("Location: login.php"));
}

$member = $conn->prepare("SELECT * FROM members WHERE member_id = ?");
$member->execute([$session['member_id']]);
$member = $member->fetch(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD'] == "POST") {
	$_POST['birthday'] = date("Y-m-d", strtotime($_POST['birthday']));
	
	if($_POST['birthday'] == "1970-01-01") {
		$update_video = $conn->prepare("UPDATE members SET name = ?, birthday = NULL, gender = ?, relationship_status = ?, about_me = ?, personal_website = ?, hometown = ?, current_city = ?, current_country = ?, occupations = ?, companies = ?, schools = ?, interests_and_hobbies = ?, favorite_movies_and_shows = ?, favorite_music = ?, favorite_books = ? WHERE member_id = ?");
		$update_video->execute([
			trim($_POST['name']),
			trim($_POST['gender']),
			trim($_POST['relationship_status']),
			trim($_POST['about_me']),
			trim($_POST['personal_website']),
			trim($_POST['hometown']),
			trim($_POST['current_city']),
			trim($_POST['current_country']),
			trim($_POST['occupations']),
			trim($_POST['companies']),
			trim($_POST['schools']),
			trim($_POST['interests_and_hobbies']),
			trim($_POST['favorite_movies_and_shows']),
			trim($_POST['favorite_music']),
			trim($_POST['favorite_books']),
			$session['member_id']
		]);
	} else {
		$update_video = $conn->prepare("UPDATE members SET name = ?, birthday = ?, gender = ?, relationship_status = ?, about_me = ?, personal_website = ?, hometown = ?, current_city = ?, current_country = ?, occupations = ?, companies = ?, schools = ?, interests_and_hobbies = ?, favorite_movies_and_shows = ?, favorite_music = ?, favorite_books = ? WHERE member_id = ?");
		$update_video->execute([
			trim($_POST['name']),
			trim($_POST['birthday']),
			trim($_POST['gender']),
			trim($_POST['relationship_status']),
			trim($_POST['about_me']),
			trim($_POST['personal_website']),
			trim($_POST['hometown']),
			trim($_POST['current_city']),
			trim($_POST['current_country']),
			trim($_POST['occupations']),
			trim($_POST['companies']),
			trim($_POST['schools']),
			trim($_POST['interests_and_hobbies']),
			trim($_POST['favorite_movies_and_shows']),
			trim($_POST['favorite_music']),
			trim($_POST['favorite_books']),
			$session['member_id']
		]);
	}
	$_SESSION['alert'] = "Changes successfully saved!";
	header("Location: my_profile.php");
}

?>
<div class="formTable">
	<?php echo (isset($_SESSION['alert'])) ? '<div class="success">'.$_SESSION['alert'].'</div>' : ''; ?>
    <form method="post" action="my_profile.php">
        <table cellpadding="5" width="700" cellspacing="0" border="0" align="center">
				<tr valign="top">
					<td colspan="2"><div class="tableSubTitle">Personal Information</div></td>
				</tr>
				<tr valign="top">
					<td align="right"><span class="label">Name:</span></td>
					<td><input type="text" size="20" maxlength="500" name="name" value="<?php echo (!empty($member['name'])) ? htmlspecialchars($member['name']) : ""; ?>"></td>
                </tr>
				<tr valign="top">
					<td align="right"><span class="label">Birthday:</span></td>
					<td><input type="date" size="20" maxlength="500" max='2010-01-01' name="birthday" value="<?php echo (!empty($member['birthday'])) ? htmlspecialchars($member['birthday']) : ""; ?>"></td>
                </tr>
				<tr valign="top">
					<td align="right"><span class="label">Gender:</span></td>
					<td>
						<select name="gender">
							<option value="0" <?php echo ($member['gender'] == 0) ? "selected" : ""; ?>>Prefer not to say</option>
							<option value="1" <?php echo ($member['gender'] == 1) ? "selected" : ""; ?>>Male</option>
							<option value="2" <?php echo ($member['gender'] == 2) ? "selected" : ""; ?>>Female</option>
						</select>
					</td>
                </tr>
				<tr valign="top">
					<td align="right"><span class="label">Relationship Status:</span></td>
					<td>
						<select name="relationship_status">
							<option value="0" <?php echo ($member['relationship_status'] == 0) ? "selected" : ""; ?>>Prefer not to say</option>
							<option value="1" <?php echo ($member['relationship_status'] == 1) ? "selected" : ""; ?>>Single</option>
							<option value="2" <?php echo ($member['relationship_status'] == 2) ? "selected" : ""; ?>>Taken</option>
						</select>
					</td>
                </tr>
				<tr valign="top">
					<td align="right" valign="top"><span class="label">About Me:</span></td>
					<td><textarea maxlength="500" name="about_me" cols="55" rows="3"><?php echo (!empty($member['about_me'])) ? htmlspecialchars($member['about_me']) : ""; ?></textarea></td>
                </tr>
				<tr valign="top">
					<td align="right"><span class="label">Personal Website:</span></td>
					<td><input type="text" size="20" maxlength="500" name="personal_website" value="<?php echo (!empty($member['personal_website'])) ? htmlspecialchars($member['personal_website']) : ""; ?>"></td>
                </tr>
				<tr valign="top">
					<td colspan="2"><br><div class="tableSubTitle">Location Information</div></td>
				</tr>
				<tr valign="top">
					<td align="right"><span class="label">Hometown:</span></td>
					<td><input type="text" size="50" maxlength="500" name="hometown" value="<?php echo (!empty($member['hometown'])) ? htmlspecialchars($member['hometown']) : ""; ?>"></td>
                </tr>
				<tr valign="top">
					<td align="right"><span class="label">Current City:</span></td>
					<td><input type="text" size="50" maxlength="500" name="current_city" value="<?php echo (!empty($member['current_city'])) ? htmlspecialchars($member['current_city']) : ""; ?>"></td>
                </tr>
				<tr valign="top">
					<td align="right"><span class="label">Current Country:</span></td>
					<td><input type="text" size="50" maxlength="500" name="current_country" value="<?php echo (!empty($member['current_country'])) ? htmlspecialchars($member['current_country']) : ""; ?>"></td>
                </tr>
				<tr valign="top">
					<td colspan="2"><br><div class="tableSubTitle">Random Information</div></td>
				</tr>
				<tr valign="top">
					<td align="right"><span class="label">Occupations:</span></td>
					<td><textarea maxlength="500" name="occupations" cols="55" rows="3"><?php echo (!empty($member['occupations'])) ? htmlspecialchars($member['occupations']) : ""; ?></textarea></td>
                </tr>
				<tr valign="top">
					<td align="right"><span class="label">Companies:</span></td>
					<td><textarea maxlength="500" name="companies" cols="55" rows="3"><?php echo (!empty($member['companies'])) ? htmlspecialchars($member['companies']) : ""; ?></textarea></td>
                </tr>
				<tr valign="top">
					<td align="right"><span class="label">Schools:</span></td>
					<td><textarea maxlength="500" name="schools" cols="55" rows="3"><?php echo (!empty($member['schools'])) ? htmlspecialchars($member['schools']) : ""; ?></textarea></td>
                </tr>
				<tr valign="top">
					<td align="right"><span class="label">Interests & Hobbies:</span></td>
					<td><textarea maxlength="500" name="interests_and_hobbies" cols="55" rows="3"><?php echo (!empty($member['interests_and_hobbies'])) ? htmlspecialchars($member['interests_and_hobbies']) : ""; ?></textarea></td>
                </tr>
				<tr valign="top">
					<td align="right"><span class="label">Favorite Movies & Shows:</span></td>
					<td><textarea maxlength="500" name="favorite_movies_and_shows" cols="55" rows="3"><?php echo (!empty($member['favorite_movies_and_shows'])) ? htmlspecialchars($member['favorite_movies_and_shows']) : ""; ?></textarea></td>
                </tr>
				<tr valign="top">
					<td align="right"><span class="label">Favorite Music:</span></td>
					<td><textarea maxlength="500" name="favorite_music" cols="55" rows="3"><?php echo (!empty($member['favorite_music'])) ? htmlspecialchars($member['favorite_music']) : ""; ?></textarea></td>
                </tr>
				<tr valign="top">
					<td align="right"><span class="label">Favorite Books:</span></td>
					<td><textarea maxlength="500" name="favorite_books" cols="55" rows="3"><?php echo (!empty($member['favorite_books'])) ? htmlspecialchars($member['favorite_books']) : ""; ?></textarea></td>
                </tr>
				<tr valign="top">
                    <td></td>
                    <td><input type="submit" id="save" name="save" value="Save ->"></td>
                </tr>
                <?php if(isset($_SESSION['error'])) { ?>
                    <tr valign="top">
                        <td>
                            <p style="color: #ff0000;"><?php echo htmlspecialchars($_SESSION['error']) ?></p>
                        </td>
                    </tr>
                <?php unset($_SESSION['error']); } ?>
        </table>
    </form>
</div>

<?php
unset($_SESSION['alert']);

require __DIR__ . "/includes/footer.php";
?>
