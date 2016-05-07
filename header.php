<?
function getHeader($title = "")
{
	error_reporting(0);
	$backgrounds = array(73181, 94625, 192051, 481615, 539387, 576847, 592334, 627080, 644256, 665826, 1482880, 4456982, 6438291, 38529420, 111229977);
	$random = array_rand($backgrounds);
	?>

	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="UTF-8">
		<meta name="description" content="Mastery retriever from Riot Games API">
		<meta name="keywords" content="Anatomization, API, HTML, CSS, PHP, Bootstrap, Riot Games, League Of Legends, Mastery">
		<meta name="author" content="Nearance (Gabraba)">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="styles/style.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
		<script src="styles/scripts.js"></script>
		<script src="styles/ajaxRequests.js"></script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<script type="text/javascript" charset="UTF-8" src="http://chs03.cookie-script.com/s/06fdbdce666e883ff7ec4e6f9abf7fc8.js"></script>

		<title>Mastery Anatomization :: <?= $title ?></title>

		<style type="text/css">
		body {
			background: url('styles/images/backgrounds/<?= $backgrounds[$random] ?>.jpg');
			background-size: cover;
			background-attachment: fixed;
			background-repeat: no-repeat;
			background-color: #000000;
		}
		</style>

		<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-77262047-1', 'auto');
		ga('send', 'pageview');
		</script>
	</head>
	<body>

	<div id="wrapper" class="clearfix">
	
	<?
	require_once('menu.php');
	?>

	<div id="serverPanels"></div><Br>
	<?
}