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

	// is it past date or... future?
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

function retrieveMasteries($name, $name_2, $server, $server_2, $table)
{
	$serversM = array('BR1', 'EUN1', 'EUW1', 'JP1', 'KR1', 'LA1', 'LA2', 'NA1', 'OC1', 'RU', 'TR1');
	$serversP = array('BR', 'EUNE', 'EUW', 'JP', 'KR', 'LAN', 'LAS', 'NA', 'OCE', 'RU', 'TR');

	if (!in_array($server, $serversM) || !in_array($server_2, $serversM) || strlen($name) < 4 || strlen($name) > 24 || strlen($name_2) < 4 || strlen($name_2) > 24)
		die('F');

	if (preg_match('/^[A-Za-z0-9\s]+$/', $name) == 0 || preg_match('/^[A-Za-z0-9\s]+$/', $name_2) == 0)
		die('F');

	$serversID = $serversP[array_search($server, $serversM)];
	$serversID_2 = $serversP[array_search($server_2, $serversM)];
	$key = '[KEY CODE]';
	$response = '';
	$perfectScores = array('S-', 'S', 'S+');
	$totalScore = 0;
	$totalScore_2 = 0;
	$totalLevel = 0;
	$totalLevel_2 = 0;

	// For Summoner #1
	$source = file_get_contents('https://eune.api.pvp.net/api/lol/'.$serversID.'/v1.4/summoner/by-name/'.str_replace(' ', '%20', $name).'?api_key='.$key.'');
	if (!$source)
		die('N');

	$conv = json_decode($source, true); // Get player ID.
	$playerID = $conv[strtolower(preg_replace('/\s+/', '', $name))]['id'];

	// For Summoner #2
	$source = file_get_contents('https://eune.api.pvp.net/api/lol/'.$serversID_2.'/v1.4/summoner/by-name/'.str_replace(' ', '%20', $name_2).'?api_key='.$key.'');
	if (!$source)
		die('N');

	$conv = json_decode($source, true); // Get player ID.
	$playerID_2 = $conv[strtolower(preg_replace('/\s+/', '', $name_2))]['id'];

	$response .= '<div class="panel panel-success master_panel '.($table == 1 ? 'panel_ignore_width' : '').'">';
	$response .= '<div class="panel-heading"><h3 class="panel-title">Champions list and mastery information:</h3></div>';
	$response .= '<div class="panel-body">';

	if ($table == 0)
	{
		// For Summoner #1
		$source = file_get_contents('https://eune.api.pvp.net/api/lol/'.$serversID.'/v1.2/champion?api_key='.$key.'');
		$conv = json_decode($source, true); // Get all champions.
		$count = count($conv['champions']);

		// For Summoner #2
		$source_2 = file_get_contents('https://eune.api.pvp.net/api/lol/'.$serversID_2.'/v1.2/champion?api_key='.$key.'');
		$conv_2 = json_decode($source_2, true); // Get all champions.
		$j = 0;

		for ($i = 0; $i < $count; $i++)
		{
			$j++;

			// For Summoner #1
			$source2 = file_get_contents('https://global.api.pvp.net/api/lol/static-data/eune/v1.2/champion/'.$conv['champions'][$i]['id'].'?api_key='.$key.'');
			$conv2 = json_decode($source2, true);	// Get champion name and picture.

			$source3 = file_get_contents('https://eune.api.pvp.net/championmastery/location/'.$server.'/player/'.$playerID.'/champion/'.$conv['champions'][$i]['id'].'?api_key='.$key.'');
			$conv3 = json_decode($source3, true);	// Get champion mastery.
	
			$lastPlayTime = $conv3['lastPlayTime'] / 1000;
			$pointsForNextLevel = ($conv3['championLevel'] < 5) ? "<br>To reach level <b>".($conv3['championLevel'] + 1)."</b>, ".$conv2['name']." needs additional <b>".$conv3['championPointsUntilNextLevel']."</b> points." : "";
			$approxTime = (abs(time() - $lastPlayTime) / 2592000 > 1) ? "<font color=\"#FF0000\">".nicetime(date('Y-m-d H:i', $lastPlayTime))."</font>" : "<font color=\"#01CB01\">".nicetime(date('Y-m-d H:i', $lastPlayTime))."</font>";


			// For Summoner	#2
			$source2_2 = file_get_contents('https://global.api.pvp.net/api/lol/static-data/eune/v1.2/champion/'.$conv_2['champions'][$i]['id'].'?api_key='.$key.'');
			$conv2_2 = json_decode($source2_2, true);	// Get champion name and picture.

			$source3_2 = file_get_contents('https://eune.api.pvp.net/championmastery/location/'.$server_2.'/player/'.$playerID_2.'/champion/'.$conv_2['champions'][$i]['id'].'?api_key='.$key.'');
			$conv3_2 = json_decode($source3_2, true);	// Get champion mastery.
	
			$lastPlayTime_2 = $conv3_2['lastPlayTime'] / 1000;
			$pointsForNextLevel_2 = ($conv3_2['championLevel'] < 5) ? "<br>To reach level <b>".($conv3_2['championLevel'] + 1)."</b>, ".$conv2_2['name']." needs additional <b>".$conv3_2['championPointsUntilNextLevel']."</b> points." : "";
			$approxTime_2 = (abs(time() - $lastPlayTime_2) / 2592000 > 1) ? "<font color=\"#FF0000\">".nicetime(date('Y-m-d H:i', $lastPlayTime_2))."</font>" : "<font color=\"#01CB01\">".nicetime(date('Y-m-d H:i', $lastPlayTime_2))."</font>";

			if ($conv3['championLevel'] != NULL || $conv3_2['championLevel'] != NULL)
			{
				if ($conv3['championLevel'] != NULL)
				{
					$response .= '<img class="championImage champion-'.$conv2['key'].'" src="styles/images/championPortraits/'.$conv2['key'].'_Square_0.png"> ';
					$response .= '<div class="popupInfo ch-'.$conv2['key'].'"><h3>'.$conv2['name'].'</h3>';
					$response .= 'This champion for '.$name.' is level <b>'.$conv3['championLevel'].'</b> with <b>'.$conv3['championPoints'].'</b> champion points (additional <b>'.$conv3['championPointsSinceLastLevel'].'</b> points were gained after reaching current level).';
					$response .= $pointsForNextLevel;
					//$response .= 'Highest grade gained on this champion is '.$perfectScore.'.<br>';
					$response .= '<br>A chest for '.$conv2['name'].' has '.($conv3['chestGranted'] == false ? "<font color=\"#FF0000\">not been granted yet</font>" : "<font color=\"#01CB01\">been granted</font>").'.<br>';
					$response .= 'The last time '.$name.' played this champion was on <b>'.date('jS F Y h:i:s A (T)', $lastPlayTime).'</b> ('.$approxTime.').<br>';
					$response .= '</div>';
				}
				else
					$response .= '<img class="championImage champion-'.$conv2_2['key'].'" src="styles/images/championPortraits/'.$conv2_2['key'].'_Square_0.png"> <div class="popupInfo ch-'.$conv2_2['key'].'"><h3>'.$conv2_2['name'].'</h3><font color="#FF0000">'.$name.' has not earned any mastery points for '.$conv2['name'].' yet.</font></div>';

				if ($conv3_2['championLevel'] != NULL)
				{
					$response .= '<img class="championImage_2 champion-'.$conv2_2['key'].'" src="styles/images/championPortraits/'.$conv2_2['key'].'_Square_0.png">&nbsp;&nbsp;&nbsp;&nbsp;';
					$response .= '<div class="popupInfo ch_2-'.$conv2_2['key'].'"><h3>'.$conv2_2['name'].'</h3>';
					$response .= 'This champion for '.$name_2.' is level <b>'.$conv3_2['championLevel'].'</b> with <b>'.$conv3_2['championPoints'].'</b> champion points (additional <b>'.$conv3_2['championPointsSinceLastLevel'].'</b> points were gained after reaching current level).';
					$response .= $pointsForNextLevel_2;
					//$response .= 'Highest grade gained on this champion is '.$perfectScore.'.<br>';
					$response .= '<br>A chest for '.$conv2_2['name'].' has '.($conv3_2['chestGranted'] == false ? "<font color=\"#FF0000\">not been granted yet</font>" : "<font color=\"#01CB01\">been granted</font>").'.<br>';
					$response .= 'The last time '.$name_2.' played this champion was on <b>'.date('jS F Y h:i:s A (T)', $lastPlayTime_2).'</b> ('.$approxTime_2.').<br>';
					$response .= '</div>';
				}
				else
					$response .= '<img class="championImage_2 champion-'.$conv2['key'].'" src="styles/images/championPortraits/'.$conv2['key'].'_Square_0.png">&nbsp;&nbsp;&nbsp;&nbsp;<div class="popupInfo ch_2-'.$conv2['key'].'"><h3>'.$conv2['name'].'</h3><font color="#FF0000">'.$name_2.' has not earned any mastery points for '.$conv2_2['name'].' yet.</font></div>';
			}
			else
				continue;

			if ($j % 2 == 0)
				$response .= '<br><br>';
		}

		$response .= '</div></div>';
	}
	else
	{
		// For Summoner #1
		$source = file_get_contents('https://eune.api.pvp.net/api/lol/'.$serversID.'/v1.2/champion?api_key='.$key.'');
		if (!$source)
			die('N');

		$conv = json_decode($source, true); // Get all champions.
		$count = count($conv['champions']);

		// For Summoner #2
		$source_2 = file_get_contents('https://eune.api.pvp.net/api/lol/'.$serversID_2.'/v1.2/champion?api_key='.$key.'');
		if (!$source)
			die('N');

		$conv_2 = json_decode($source_2, true); // Get all champions.

		$response .= '<table class="table bordered">';
		$response .= '<tr class="info">';
		$response .= '<th>#</th><th>Champion name</th><th>Summoner\'s name and mastery information</th></tr>';

		for ($i = 0; $i < $count; $i++)
		{
			// For Summoner #1
			$source2 = file_get_contents('https://global.api.pvp.net/api/lol/static-data/eune/v1.2/champion/'.$conv['champions'][$i]['id'].'?api_key='.$key.'');
			$conv2 = json_decode($source2, true);	// Get champion name and picture.

			$source3 = file_get_contents('https://eune.api.pvp.net/championmastery/location/'.$server.'/player/'.$playerID.'/champion/'.$conv['champions'][$i]['id'].'?api_key='.$key.'');
			$conv3 = json_decode($source3, true);	// Get champion mastery.
	
			$lastPlayTime = $conv3['lastPlayTime'] / 1000;
			$pointsForNextLevel = ($conv3['championLevel'] < 5) ? "<br>To reach level <b>".($conv3['championLevel'] + 1)."</b>, ".$name." needs additional <b>".$conv3['championPointsUntilNextLevel']."</b> points." : "";
			$approxTime = (abs(time() - $lastPlayTime) / 2592000 > 1) ? "<font color=\"#FF0000\">".nicetime(date('Y-m-d H:i', $lastPlayTime))."</font>" : "<font color=\"#01CB01\">".nicetime(date('Y-m-d H:i', $lastPlayTime))."</font>";

			// For Summoner #2
			$source2_2 = file_get_contents('https://global.api.pvp.net/api/lol/static-data/eune/v1.2/champion/'.$conv_2['champions'][$i]['id'].'?api_key='.$key.'');
			$conv2_2 = json_decode($source2_2, true);	// Get champion name and picture.

			$source3_2 = file_get_contents('https://eune.api.pvp.net/championmastery/location/'.$server.'/player/'.$playerID_2.'/champion/'.$conv_2['champions'][$i]['id'].'?api_key='.$key.'');
			$conv3_2 = json_decode($source3_2, true);	// Get champion mastery.
	
			$lastPlayTime_2 = $conv3_2['lastPlayTime'] / 1000;
			$pointsForNextLevel_2 = ($conv3_2['championLevel'] < 5) ? "<br>To reach level <b>".($conv3_2['championLevel'] + 1)."</b>, ".$name_2." needs additional <b>".$conv3_2['championPointsUntilNextLevel']."</b> points." : "";
			$approxTime_2 = (abs(time() - $lastPlayTime_2) / 2592000 > 1) ? "<font color=\"#FF0000\">".nicetime(date('Y-m-d H:i', $lastPlayTime_2))."</font>" : "<font color=\"#01CB01\">".nicetime(date('Y-m-d H:i', $lastPlayTime_2))."</font>";

			if ($conv3['championLevel'] != NULL || $conv3_2['championLevel'] != NULL)
			{
				$j = $i + 1;

				$totalScore += $conv3['championPoints'];
				$totalScore_2 += $conv3_2['championPoints'];
				$totalLevel += $conv3['championLevel'];
				$totalLevel_2 += $conv3_2['championLevel'];
	
				$response .= '<tr>';
				$response .= '<td rowspan="2" style="vertical-align: middle">'.$j.'</td>';
				$response .= '<td rowspan="2" style="vertical-align: middle"><img src="styles/images/championPortraits/'.($conv3['championLevel'] != NULL ? $conv2['key'] : $conv2_2['key']).'_Square_0.png"><br><a href="http://gameinfo.na.leagueoflegends.com/en/game-info/champions/'.strtolower($conv3['championLevel'] != NULL ? $conv2['key'] : $conv2_2['key']).'">'.($conv3['championLevel'] != NULL ? $conv2['name'] : $conv2_2['name']).'</a></td>';

				if ($conv3['championLevel'] != NULL)
				{
					$response .= '<td><h4>'.$name.'</h4>';
					$response .= ''.$name.' has level <b>'.($conv3['championLevel'] >= 5 ? '<font color="#01CB01">'.$conv3['championLevel'].'</font>' : '<font color="#FF0000">'.$conv3['championLevel'].'</font>').'</b> with <b>'.$conv3['championPoints'].'</b> mastery points on this champion.';
					$response .= ' Additional <b>'.$conv3['championPointsSinceLastLevel'].'</b> points were gained after reaching current level. '.$pointsForNextLevel.''.'<br>';
					$response .= 'A chest for this champion '.($conv3['chestGranted'] == false ? '<font color="#FF0000">has not been granted yet</font>' : '<font color="#01CB01">has been granted</font>').'.<br>';
					$response .= 'Last time this champion was played by '.$name.' (and gained mastery points) was on <b>'.date('jS F Y h:i:s A (T)', $lastPlayTime).'</b> ('.$approxTime.')'.'</td></tr>';
				}
				else
					$response .= '<td><b>'.$name.'</b><br><font color="#FF0000">'.$name.' has not earned any mastery points for '.$conv2_2['name'].' yet</font>.</td></tr>';

				if ($conv3_2['championLevel'] != NULL)
				{
					$response .= '<td><h4>'.$name_2.'</h4>';
					$response .= ''.$name_2.' has level <b>'.($conv3_2['championLevel'] >= 5 ? '<font color="#01CB01">'.$conv3_2['championLevel'].'</font>' : '<font color="#FF0000">'.$conv3_2['championLevel'].'</font>').'</b> with <b>'.$conv3_2['championPoints'].'</b> mastery points on this champion.';
					$response .= ' Additional <b>'.$conv3['championPointsSinceLastLevel'].'</b> points were gained after reaching current level. '.$pointsForNextLevel_2.''.'<br>';
					$response .= 'A chest for this champion '.($conv3_2['chestGranted'] == false ? '<font color="#FF0000">has not been granted yet</font>' : '<font color="#01CB01">has been granted</font>').'.<br>';
					$response .= 'Last time this champion was played by '.$name_2.' (and gained mastery points) was on <b>'.date('jS F Y h:i:s A (T)', $lastPlayTime_2).'</b> ('.$approxTime_2.')'.'</td></tr>';
				}
				else
					$response .= '<td><b>'.$name_2.'</b><br><font color="#FF0000">'.$name_2.' has not earned any mastery points for '.$conv2['name'].' yet</font>.</td></tr>';

				if ($conv3['championPoints'] > $conv3_2['championPoints'])
					$morePoints = "<b>".$name."</b> has ".($conv3['championPoints'] - $conv3_2['championPoints'])." more points than <b>".$name_2."</b>";
				elseif ($conv3['championPoints'] < $conv3_2['championPoints'])
					$morePoints = "<b>".$name_2."</b> has ".($conv3_2['championPoints'] - $conv3['championPoints'])." more points than <b>".$name."</b>";
				else
					$morePoints = "Both Summoners have an equal amount of mastery points";
				
				if ($conv3['chestGranted'] == false || $conv3['chestGranted'] == NULL && $conv3_2['chestGranted'] == false || $conv3_2['chestGranted'] == NULL)
					$chestGranted = "Neither of 2 Summoners received chests";
				elseif ($conv3['chestGranted'] == true && $conv3_2['chestGranted'] == false || $conv3_2['chestGranted'] == NULL)
					$chestGranted = "Only ".$name." received a chest.";
				elseif ($conv3['chestGranted'] == false || $conv3['chestGranted'] == NULL && $conv3_2['chestGranted'] == true)
					$chestGranted = "Only ".$name_2." received a chest.";
				else
					$chestGranted = "Both Summoners received chests.";

				$response .= '<tr>';
				$response .= '<td colspan="3"><h4>Conclusion</h4>';
				$response .= $morePoints.'. '.$chestGranted.'.';
				$response .= '</td></tr>';
			}
			else
				continue;
		}

		$response .= '</table>';
		$response .= '<b>'.$name.'</b> has a total of <b>'.$totalScore.'</b> points while <b>'.$name_2.'</b> has a total of <b>'.$totalScore_2.'</b> points. Difference: <b>'.abs(($totalScore - $totalScore_2)).'</b> points.<br>';
		$response .= '<b>'.$name.'</b> has a total of <b>'.$totalLevel.'</b> levels while <b>'.$name_2.'</b> has a total of <b>'.$totalLevel_2.'</b> levels. Difference: <b>'.abs(($totalLevel - $totalLevel_2)).'</b> levels.';
	}

	die($response);
}

if (!empty($_POST))
	retrieveMasteries($_POST['summName'], $_POST['summName2'], $_POST['server'], $_POST['server2'], $_POST['showAsTable']);
