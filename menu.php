<?
if (strpos($_SERVER['REQUEST_URI'], basename(__FILE__)) !== false)
	header("Location: index.php");
?>

<a id="backTop" class="hidden" href="#"></a>

<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index.php">Mastery Anatomization</a>
		</div>
		<div class="collapse navbar-collapse" id="myNavbar">
			<ul class="nav navbar-nav">
				<li <?= (basename($_SERVER['SCRIPT_NAME']) == "index.php" ? 'class="active"' : '') ?>><a href="index.php"><span class="glyphicon glyphicon-zoom-out"></span> Mastery retriever</a></li>
				<li <?= (basename($_SERVER['SCRIPT_NAME']) == "comparison.php" ? 'class="active"' : '') ?>><a href="comparison.php"><span class="glyphicon glyphicon-zoom-in"></span> Compare 2 Summoners</a></li>
				<li <?= (basename($_SERVER['SCRIPT_NAME']) == "quiz.php" ? 'class="active"' : '') ?>><a href="quiz.php"><span class="glyphicon glyphicon-check"></span> Mastery quiz</a></li>
				<li><a href="#" onclick="getServerStatus()"><span class="glyphicon glyphicon-stats"></span> Check servers status</a></li>
				<li <?= (basename($_SERVER['SCRIPT_NAME']) == "about.php" ? 'class="active"' : '') ?>><a href="about.php"><span class="glyphicon glyphicon-file"></span> About this website</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right"><li><a href="#" id="moreInfoMenu"><span class="glyphicon glyphicon-tag"></span> More info</a></li></ul>
		</div>
	</div>
</nav>
<div id="moreInfo">
	<div class="panel panel-default" style="width: 100%">
		<div class="panel-heading">Some information</div>
		<div class="panel-body">
			<ul>
				<li>This website uses localStorage, not cookies (but there is still cookie policy added, just in case).</li><br>
				<li>For now this website has the options to show masteries for specific Summoner, compare 2 Summoner's and show server status. In future this list should be expanded.</li><br>
				<li>It is essential to have JavaScript enabled on browser because requests and results display are processed with JavaScript.</li><br>
				<li>Results are saved in localStorage for 3 hours, therefore, similar requests will display the same results (unless you clean localStorage).</li><br>
				<li>There is a bug in Riot Games API that does not show gained highest grade for champions when selecting in alphabetical order.</li><br>
				<li>Another bug in Riot Games API is that if chest was granted outside of Normal/Ranked games (example, Featured Game Modes), it shows as "not granted".</li><br>
				<li>Since this requires to retrieve all data about masteries (from either 1 or 2 Summoners), it could take some time. Please be patient.</li><br>
			</ul>
		</div>
	</div>
</div>

<br><br>