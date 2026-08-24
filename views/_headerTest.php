<!DOCTYPE html>
<html lang="en">
	<head>
	<!-- Metas -->
		<meta charset="utf-8">
		<meta name="ROBOTS" content="NOARCHIVE">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>PHODA-Short Electronic Version</title>
	<!-- CSS & Fonts -->
		<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Droid+Sans+Mono" />
		<?php
		if ($login->isexpLoggedIn() == true){?>

			<link href="css/test.css" rel="stylesheet" />

	<!-- Scripts -->
		<script src="https://unpkg.com/interactjs@next/dist/interact.min.js"></script>
		<script src="js/drag.js"></script>
		
		<script>
		function instructions() {
				alert("At the center of the screen there is a deck with forty cards. Each card represents a daily activity that you may find potentially harmful. Place each card on the board so that the position will represent how much each activity may be harmful.\n You can see a full size version of the picture using double-click");
		}
		</script>

			<?php
		} else {
			echo '<link href="css/login.css" rel="stylesheet" />';
		}?>
		


</head>

<body<?php if ($login->isexpLoggedIn() == true) { ?> onload="instructions()"<?php } ?>>