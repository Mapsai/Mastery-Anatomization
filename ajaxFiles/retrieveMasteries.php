<?
function nicetime($date)
{
	if (empty($date))
		return "No date provided";

	$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	$lengths = array("60", "60", "24", "7", "4.35", "12", "10");

	$now = time();
	$unix_date = strtotime($date);

	// check validity of date
	if (empty($unix_date))
		return "Bad date";

	// is it future date or past date
	if ($now > $unix_date)
	{   
		$difference = $now - $unix_date;
		$tense = "ago";
	}
	else
	{
		$difference = $unix_date - $now;
		$tense = "from now";
	}

	for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++)
		$difference /= $lengths[$j];

	$difference = round($difference);

	if ($difference != 1)
		$periods[$j].= "s";

	return "".$difference." ".$periods[$j]." {$tense}";
}

function retrieveMasteries($name, $server, $sort, $table)
{
	error_reporting(0);

	$serversM = array('BR1', 'EUN1', 'EUW1', 'JP1', 'KR1', 'LA1', 'LA2', 'NA1', 'OC1', 'RU', 'TR1');
	$serversID = array('BR', 'EUNE', 'EUW', 'JP', 'KR', 'LAN', 'LAS', 'NA', 'OCE', 'RU', 'TR');

	if (!in_array($server, $serversM) || strlen($name) < 4 || strlen($name) > 24)
		die('F');

	if (preg_match('/^[A-Za-z0-9\s]+$/', $name) == 0)
		die('F');

	$serversID = $serversID[array_search($server, $serversM)];
	$key = '[KEY CODE]';
	$response = '';
	$perfectScores = array('S-', 'S', 'S+');
	$j = 0;

	$source = file_get_contents('https://eune.api.pvp.net/api/lol/'.$serversID.'/v1.4/summoner/by-name/'.str_replace(' ', '%20', $name).'?api_key='.$key.'');
	if (!$source)
		die('N');

	$conv = json_decode($source, true); // Get player ID.

	$playerID = $conv[strtolower(preg_replace('/\s+/', '', $name))]['id'];

	$response .= '<div class="panel panel-success master_panel '.($table == 1 ? 'panel_ignore_width' : '').'">';
	$response .= '<div class="panel-heading"><h3 class="panel-title">Champions list and mastery information:</h3></div>';
	$response .= '<div class="panel-body">';

	if ($sort == 0 && $table == 0)
	{
		$source = file_get_contents('https://eune.api.pvp.net/api/lol/'.$serversID.'/v1.2/champion?api_key='.$key.'');
		$conv = json_decode($source, true); // Get all champions.
		$count = count($conv['champions']);

		for ($i = 0; $i < $count; $i++)
		{
			$source2 = file_get_contents('https://global.api.pvp.net/api/lol/static-data/eune/v1.2/champion/'.$conv['champions'][$i]['id'].'?api_key='.$key.'');
			$conv2 = json_decode($source2, true);	// Get champion name and picture.

			$source3 = file_get_contents('https://eune.api.pvp.net/championmastery/location/'.$server.'/player/'.$playerID.'/champion/'.$conv['champions'][$i]['id'].'?api_key='.$key.'');
			$conv3 = json_decode($source3, true);	// Get champion mastery.
	
			$lastPlayTime = $conv3['lastPlayTime'] / 1000;
			$pointsForNextLevel = ($conv3['championLevel'] < 5) ? "<br>To reach level <u>".($conv3['championLevel'] + 1)."</u>, ".$conv2['name']." needs additional <u>".$conv3['championPointsUntilNextLevel']."</u> points." : "";
			$approxTime = (abs(time() - $lastPlayTime) / 2592000 > 1) ? "<font color=\"#FF0000\">".nicetime(date('Y-m-d H:i', $lastPlayTime))."</font>" : "<font color=\"#01CB01\">".nicetime(date('Y-m-d H:i', $lastPlayTime))."</font>";

			if ($conv3['championLevel'] != NULL)
			{
				$response .= '<img class="championImage champion-'.$conv2['key'].'" src="styles/images/championPortraits/'.$conv2['key'].'_Square_0.png">';
				$response .= '<div class="popupInfo ch-'.$conv2['key'].'"><h3>'.$conv2['name'].'</h3>';
				$response .= 'This champion for '.$name.' is level <u>'.$conv3['championLevel'].'</u> with <u>'.$conv3['championPoints'].'</u> champion points (additional <u>'.$conv3['championPointsSinceLastLevel'].'</u> points were gained after reaching current level).';
				$response .= $pointsForNextLevel;
				//$response .= 'Highest grade gained on this champion is '.$perfectScore.'.<br>';
				$response .= '<br>A chest for '.$conv2['name'].' has '.($conv3['chestGranted'] == false ? "<font color=\"#FF0000\">not been granted yet</font>" : "<font color=\"#01CB01\">been granted</font>").'.<br>';
				$response .= 'The last time '.$name.' played this champion was on <u>'.date('jS F Y h:i:s A (T)', $lastPlayTime).'</u> ('.$approxTime.').<br>';
				$response .= '</div>';
			}
			else
				continue;
		}

		$response .= '</div></div>';
	}
	elseif ($sort == 1 && $table == 0)
	{
		$source = file_get_contents('https://eune.api.pvp.net/championmastery/location/'.$server.'/player/'.$playerID.'/champions?api_key='.$key.'');
		$conv = json_decode($source, true);	// Get champion mastery.
		$count = count($conv);

		for ($i = 0; $i < $count; $i++)
		{
			$source2 = file_get_contents('https://global.api.pvp.net/api/lol/static-data/eune/v1.2/champion/'.$conv[$i]['championId'].'?api_key='.$key.'');
			$conv2 = json_decode($source2, true);	// Get champion name and picture.
			$lastPlayTime = $conv[$i]['lastPlayTime'] / 1000;
			$perfectScore = (array_search($conv[$i]['highestGrade'], $perfectScores)) ? "<font color=\"#01CB01\">".$conv[$i]['highestGrade']."</font>" : "".$conv[$i]['highestGrade']."";
			$approxTime = (abs(time() - $lastPlayTime) / 2592000 > 1) ? "<font color=\"#FF0000\">".nicetime(date('Y-m-d H:i', $lastPlayTime))."</font>" : "<font color=\"#01CB01\">".nicetime(date('Y-m-d H:i', $lastPlayTime))."</font>";
			$j = $i + 1;

			if ($conv[$i]['championLevel'] != NULL)
			{
				$response .= '<img class="championImage champion-'.$conv2['key'].'" src="styles/images/championPortraits/'.$conv2['key'].'_Square_0.png">';
				$response .= '<div class="popupInfo ch-'.$conv2['key'].'"><h3>'.$conv2['name'].'</h3>';
				$response .= 'This champion for '.$name.' is in <u>'.($j == 1 ? '1st' : ($j == 2 ? '2nd' : ($j == 3 ? '3rd' : ''.$j.'th'))).'</u> place (by mastery points).<br>';
				$response .= 'This champion for '.$name.' is level <u>'.$conv[$i]['championLevel'].'</u> with <u>'.$conv[$i]['championPoints'].'</u> champion points (additional <u>'.$conv[$i]['championPointsSinceLastLevel'].'</u> points were gained after reaching current level).<br>';
				$response .= ($conv[$i]['championLevel'] < 5 ? 'To reach level <u>'.($conv[$i]['championLevel'] + 1) .'</u>, '.$conv2['name'].' needs additional <u>'.$conv[$i]['championPointsUntilNextLevel'].'</u> points.<br>' : '');
				$response .= 'Highest grade gained on '.$conv2['name'].' is '.$perfectScore.'.<br>';
				$response .= 'A chest for '.$conv2['name'].' has '.($conv[$i]['chestGranted'] == false ? "<font color=\"#FF0000\">not been granted yet</font>" : "<font color=\"#01CB01\">been granted</font>").'.<br>';
				$response .= 'The last time '.$name.' played this champion was on <u>'.date('jS F Y h:i:s A (T)', $lastPlayTime).'</u> ('.$approxTime.').<br>';
				$response .= '</div>';
			}
			else
				continue;
		}
	}
	elseif ($sort == 0 && $table == 1)
	{
		$source = file_get_contents('https://eune.api.pvp.net/api/lol/'.$serversID.'/v1.2/champion?api_key='.$key.'');
		$conv = json_decode($source, true); // Get all champions.
		$count = count($conv['champions']);

		$response .= '<table class="table bordered">';
		$response .= '<tr class="info">';
		$response .= '<th>#</th><th>Champion name</th><th>Level and mastery points</th><th>Chest</th><th>Last gameplay on Rift</th></tr>';

		for ($i = 0; $i < $count; $i++)
		{
			$source2 = file_get_contents('https://global.api.pvp.net/api/lol/static-data/eune/v1.2/champion/'.$conv['champions'][$i]['id'].'?api_key='.$key.'');
			$conv2 = json_decode($source2, true);	// Get champion name and picture.

			$source3 = file_get_contents('https://eune.api.pvp.net/championmastery/location/'.$server.'/player/'.$playerID.'/champion/'.$conv['champions'][$i]['id'].'?api_key='.$key.'');
			$conv3 = json_decode($source3, true);	// Get champion mastery.
	
			$lastPlayTime = $conv3['lastPlayTime'] / 1000;
			$pointsForNextLevel = ($conv3['championLevel'] < 5) ? "<br>To reach level <u>".($conv3['championLevel'] + 1)."</u>, ".$conv2['name']." needs additional <u>".$conv3['championPointsUntilNextLevel']."</u> points." : "";
			$approxTime = (abs(time() - $lastPlayTime) / 2592000 > 1) ? "<font color=\"#FF0000\">".nicetime(date('Y-m-d H:i', $lastPlayTime))."</font>" : "<font color=\"#01CB01\">".nicetime(date('Y-m-d H:i', $lastPlayTime))."</font>";

			if ($conv3['championLevel'] != NULL)
			{
				$j++;

				$response .= '<tr>';
				$response .= '<td>'.$j.'</td>';
				$response .= '<td><img class="minimized_image" src="styles/images/championPortraits/'.$conv2['key'].'_Square_0.png"> <a href="http://gameinfo.na.leagueoflegends.com/en/game-info/champions/'.strtolower($conv2['key']).'">'.$conv2['name'].'</a></td>';
				$response .= '<td>Level <u>'.$conv3['championLevel'].'</u> with <u>'.$conv3['championPoints'].'</u> champion points';
				$response .= '<br>Additional <u>'.$conv3['championPointsSinceLastLevel'].'</u> points were gained after reaching current level. '.$pointsForNextLevel.''.'</td>';
				//$response .= 'Highest grade gained on this champion is '.$perfectScore.'.<br>';
				$response .= '<td>'.($conv3['chestGranted'] == false ? "<font color=\"#FF0000\">Has not been granted yet</font>" : "<font color=\"#01CB01\">Has been granted</font>").'</td>';
				$response .= '<td><u>'.date('jS F Y h:i:s A (T)', $lastPlayTime).'</u> ('.$approxTime.')'.'</td>';
				$response .= '</tr>';
			}
			else
				continue;
		}

		$response .= '</tr></table>';
	}
	else
	{
		$source = file_get_contents('https://eune.api.pvp.net/championmastery/location/'.$server.'/player/'.$playerID.'/champions?api_key='.$key.'');
		$conv = json_decode($source, true);	// Get champion mastery.
		$count = count($conv);

		$response .= '<table class="table bordered">';
		$response .= '<tr class="info">';
		$response .= '<th>#</th><th>Champion name</th><th>Level and mastery points</th><th>Highest grade</th><th>Chest</th><th>Last gameplay on Rift</th></tr>';

		for ($i = 0; $i < $count; $i++)
		{
			$source2 = file_get_contents('https://global.api.pvp.net/api/lol/static-data/eune/v1.2/champion/'.$conv[$i]['championId'].'?api_key='.$key.'');
			$conv2 = json_decode($source2, true);	// Get champion name and picture.

			$lastPlayTime = $conv[$i]['lastPlayTime'] / 1000;
			$pointsForNextLevel = ($conv[$i]['championLevel'] < 5) ? "<br>To reach level <u>".($conv[$i]['championLevel'] + 1)."</u>, ".$conv2['name']." needs additional <u>".$conv[$i]['championPointsUntilNextLevel']."</u> points." : "";
			$perfectScore = (array_search($conv[$i]['highestGrade'], $perfectScores)) ? "<font color=\"#01CB01\">".$conv[$i]['highestGrade']."</font>" : "".$conv[$i]['highestGrade']."";
			$approxTime = (abs(time() - $lastPlayTime) / 2592000 > 1) ? "<font color=\"#FF0000\">".nicetime(date('Y-m-d H:i', $lastPlayTime))."</font>" : "<font color=\"#01CB01\">".nicetime(date('Y-m-d H:i', $lastPlayTime))."</font>";

			if ($conv[$i]['championLevel'] != NULL)
			{
				$j++;
				$response .= '<tr>';
				$response .= '<td>'.$j.'</td>';
				$response .= '<td><img class="minimized_image" src="styles/images/championPortraits/'.$conv2['key'].'_Square_0.png"> <a href="http://gameinfo.na.leagueoflegends.com/en/game-info/champions/'.strtolower($conv2['key']).'">'.$conv2['name'].'</a></td>';
				$response .= '<td>Level <u>'.$conv[$i]['championLevel'].'</u> with <u>'.$conv[$i]['championPoints'].'</u> champion points';
				$response .= '<br>Additional <u>'.$conv[$i]['championPointsSinceLastLevel'].'</u> points were gained after reaching current level. '.$pointsForNextLevel.''.'</td>';
				$response .= '<td>'.$perfectScore.'</td>';
				$response .= '<td>'.($conv[$i]['chestGranted'] == false ? "<font color=\"#FF0000\">Has not been granted yet</font>" : "<font color=\"#01CB01\">Has been granted</font>").'</td>';
				$response .= '<td><u>'.date('jS F Y h:i:s A (T)', $lastPlayTime).'</u> ('.$approxTime.')'.'</td>';
				$response .= '</tr>';
			}
			else
				continue; //$response .= '<font color="#FF0000">An error has occured. Something went wrong and content was not returned from API.</font></div>';
		}

		$response .= '</tr></table>';
	}

	die($response);
}

if (!empty($_POST))
	retrieveMasteries($_POST['summName'], $_POST['server'], $_POST['sortByPoints'], $_POST['showAsTable']);
