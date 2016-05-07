<?
function getServerStatus()
{
	$response = '';
	$servers = array('br', 'eune', 'euw', 'lan', 'las', 'na', 'oce', 'ru', 'tr');

	$response .= '<div class="panel panel-success serv_panel">';
	$response .= '<div class="panel-heading"><h3 class="panel-title">Servers online:</h3></div>';
	$response .= '<div class="panel-body">';

	foreach($servers as $serv)
	{
		$url_source = file_get_contents('http://status.leagueoflegends.com/shards/'.$serv.'');
		$converter = json_decode($url_source, true);
		if ($converter['services'][0]['status'] == 'online')
			$response .= '<a href="http://'.$converter['slug'].'.leagueoflegends.com"><b>'.$converter['name'].'</b></a><br>';
	}
	$response .= '</div></div>';

	$response .= '<div class="panel panel-danger serv_panel">';
	$response .= '<div class="panel-heading"><h3 class="panel-title">Servers offline or with problems:</h3></div>';
	$response .= '<div class="panel-body">';

	foreach($servers as $serv)
	{
		$url_source = file_get_contents('http://status.leagueoflegends.com/shards/'.$serv.'');
		$converter = json_decode($url_source, true);
		if ($converter['services'][0]['status'] != 'online')
			$response .= ''.$converter['name'].' | ';
	}
	$response .= '</div></div>';

	die($response);
}

if (!empty($_POST))
	getServerStatus();
?>

