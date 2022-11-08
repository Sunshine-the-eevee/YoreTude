<?php
    $chance = rand(1, 1000000);
require __DIR__ . "/includes/header.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Video Converting</title>
    <body>
        This video is currently being processed. Check back in a few minutes.
        <?php if($chance == 8912) { ?>
            <video autoplay src="./YouTube_converting.mp4">
        <?php } ?>
    </body>
</html>
        <?php
        require __DIR__ . "/includes/footer.php";
?>
