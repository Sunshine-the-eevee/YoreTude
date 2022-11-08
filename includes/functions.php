<?php
ob_start(); // Makes redirects easier.
session_start(); // This allows sessions to work. If this is removed, you cannot log in.

$config = parse_ini_file(__DIR__ . "/../config.ini");

try {
	$conn = new PDO("mysql:host=".$config["servername"].";dbname=".$config["dbname"], $config["username"], $config["password"]);
	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}

// Set timezone so everything matches up.
date_default_timezone_set("UTC");
$conn->exec("SET time_zone = '+0:00'");

// Error reporting
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('error_reporting', 0);
error_reporting(-1);

// ip banning
$stmt = $conn->prepare("SELECT ip FROM ip_bans WHERE ip = :ip");
$stmt->execute([':ip' => $_SERVER['REMOTE_ADDR']]);

if($stmt->rowCount() != 0) {
 die(header("HTTP/1.1 500 Internal Server Error"));
}

/*
* -=-=-=-=-=-=-=-=-=-
* BEGIN THE FUNCTIONS
* -=-=-=-=-=-=-=-=-=-
*/

function generateId($length = 11) {
	$char_array = [];
	$char_range = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_';
	for($a = 0; $a < $length; $a++) {
		$char_array[$a] = $char_range[rand(0, strlen($char_range) - 1)];
	}
	return implode("", $char_array);
}

function timeAgo($datetime, $full = false) {
	$now = new DateTime;
	$ago = new DateTime($datetime);
	$diff = $now->diff($ago);

	$diff->w = floor($diff->d / 7);
	$diff->d -= $diff->w * 7;

	$string = array('y' => 'year', 'm' => 'month', 'w' => 'week', 'd' => 'day', 'h' => 'hour', 'i' => 'minute');
			
	foreach ($string as $k => &$v) {
		if ($diff->$k) {
			$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		} else {
			unset($string[$k]);
		}
	}

	if (!$full) $string = array_slice($string, 0, 1);
	return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function GetHostURL() {
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
}
?>
