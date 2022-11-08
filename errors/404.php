<?php
ob_get_clean(); // Cleans source
http_response_code(404);   
$uri = $_SERVER['REQUEST_URI'];
?>
<h1>Not Found</h1>
<p>The requested URL <?php echo $uri; ?> was not found on this server.</p>
<hr>
<address><?php
print $_SERVER["SERVER_SOFTWARE"];
?> Server at www.youtube.com Port 80</address>
</body></html>
