<!DOCTYPE html>
<html lang="en">
<!-- Author: Dmitri Popov, dmpop@linux.com
	 License: GPLv3 https://www.gnu.org/licenses/gpl-3.0.txt -->

<head>
	<meta charset="utf-8">
	<title>Sifaca</title>
	<link rel="shortcut icon" href="img/favicon.png" />
	<link rel="stylesheet" href="water.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
	<script>
		if (window.history.replaceState) {
			window.history.replaceState(null, null, window.location.href);
		}
	</script>
	<img style="display: inline; height: 1.5em;" src="img/favicon.png" alt="logo" />
	<h1 style="display: inline; margin-left: 0.3em; letter-spacing: 3px; color: rgb(200, 113, 55);">SIFACA</h1>
	<hr style="margin-bottom: 2em;">

	<?php
	echo '<p>';
	echo "IP address: <strong>";
	passthru('hostname -I');
	echo '</strong></p>';
	echo "<p style='margin-top: 2em;'>";
	$CAMERA = shell_exec("gphoto2 --auto-detect | grep usb | cut -b 36-42 | sed 's/,/\//'");
	if (!empty($CAMERA)) {
		unlink("capture_preview.jpg");
		shell_exec("gphoto2 --capture-preview");
		if (file_exists("capture_preview.jpg")) {
			echo '<img style="border-radius: 9px;" src="capture_preview.jpg">';
		}
		echo "</p>";
	} else {
		echo '<img style="display: inline; height: 1.5em; margin-right: 0.5em; vertical-align: middle;" src="img/alert.svg" alt="alert" />';
		echo "<em>Camera is not detected.</em>";
	}
	?>
	<p>
		<button style="background-color: #cce6ff; margin-top: 2em;" onClick="history.go(0)" role="button">REFRESH</button>
	</p>
	<form style="margin-top: 2em;" action='index.php' method='POST'>
		<select name='parameter'>
			<option value=''>Select command</option>
			<option value='--capture-image-and-download --keep --filename photos/%Y%m%d-%H%M%S-%03n.%C'>Capture and download</option>
			<option value='--get-all-files --skip-existing'>Download all files</option>
			<option disabled>-----</option>
			<option value='--abilities'>Show camera's abilities</option>
			<option value='--list-config'>List configurable parameters</option>
			<option disabled>-----</option>
			<option value='--help'>gPhoto2 help</option>
			<option value='--version'>Version</option>
		</select>
		<p>Aperture:</p>
		<input style="margin-bottom: 1.5em;" type="text" name="fn">
		<p>ISO:</p>
		<input style="margin-bottom: 1.5em;" type="text" name="iso">
		<p>gPhoto2 parameters: <em style="color:lightgray">(example: --list-config)</em></p>
		<input style="margin-bottom: 1.5em;" type="text" name="cmd">
		<input style="background-color: #ccffcc;" type='submit' value='OK' />
	</form>

	<?php

	if (!file_exists("photos")) {
		mkdir("photos", 0777, true);
	}

	if (!empty($_POST["fn"])) {
		echo '<pre>';
		passthru("gphoto2 --set-config f-number=" . $_POST["fn"]);
		echo '</pre>';
	}

	if (!empty($_POST["iso"])) {
		echo '<pre>';
		passthru('gphoto2 --set-config iso=' . $_POST["iso"]);
		echo '</pre>';
	}

	if (!empty($_POST["cmd"])) {
		echo '<pre>';
		passthru('gphoto2 ' . $_POST["cmd"]);
		echo '</pre>';
	}

	if (isset($_POST["parameter"])) {
		$command = 'gphoto2 ' . $_POST['parameter'];
		echo '<hr style="margin-top: 2em;"><pre>';
		passthru($command);
		echo '</pre>';
	}
	?>
	<hr style="margin-top: 2em;">
	<p>This is <a href="https://github.com/dmpop/sifaca">Sifaca</a></p>
</body>

</html>