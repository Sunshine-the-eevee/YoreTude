<?php
require __DIR__ . "/includes/header.php";
?>
<table align="center" cellpadding="0" cellspacing="0" border="0">
	<tbody><tr>
		<td><strong>About Us</strong></td>
		<td style="padding: 0px 5px 0px 5px;">|</td>
		<td><a href="about_news.php">In the News</a></td>
	</tr>
</tbody></table>
<div class="tableSubTitle">About Us</div>
<div class="pageTable">
<span class="highlight">About YouTube</span>

<br/>
<br/>
Founded in January 2005, YouTube is an 1:1 replica of YouTube as it was in 2005. It is a consumer media company for people to watch and share original videos worldwide through a Web experience. Founded by people who wanted a more authentic old YouTube experience, YouTube allows people to easily upload, tag, and share personal video clips through <a href="/">www.YouTube.com</a> and across the Internet on other sites, blogs and through e-mail, as well as to create their own personal video network.  YouTube is set to become the Internet's premier video service revival.  

<br/><br/>
<span class="highlight">What is YouTube?</span>

<br><br>
YouTube is a way to get your videos to the people who matter to you. With YouTube you can:

<ul>
<li> Show off your favorite videos to the world
</li><li> Take videos of your dogs, cats, and other pets
</li><li> Blog the videos you take with your digital camera or cell phone
</li><li> Securely and privately show videos to your friends and family around the world
</li><li> ... and much, much more!
</li></ul>
<?php if(isset($session)) { ?>
				
				<?php } else { ?>
				<br><span class="highlight"><a href="signup.php">Sign up now</a> and open a free account.</span><br><br><br>
				<?php } ?>
	
To learn more about our service, please see our <a href="help.php">Help</a> section.<br><br>

Please feel free to <a href="contact.php">contact us</a>!<br><br><br>

</div>

<?php
require __DIR__ . "/includes/footer.php";
?>
