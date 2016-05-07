<?
function displayAnswer($question, $selected)
{
	if ($question < 1 || $question > 10)
		die('-1');
	elseif ($selected < 1 || $selected > 4)
		die('-1');

	// Correct answers to each question.
	$answers = array(
		'1' => '2',
		'2' => '4',
		'3' => '2',
		'4' => '3',
		'5' => '1',
		'6' => '2',
		'7' => '3',
		'8' => '1',
		'9' => '4',
		'10' => '2');

	$explanations = array(
		'1' => 'All summoners who\'ve hit level five or higher will be eligible for Mastery points.',
		'2' => 'Party of 4: 6% of CP earned.',
		'3' => 'You can only achieve a max of Mastery Level 4 with free week champions unless you own that champion.',
		'4' => 'Mastery points do not decay.',
		'5' => '3 champions are shown as "top" and other 8 champions are shown as "in progress".',
		'6' => 'Tank role titles in ascending order: Grunt, Bruiser, Bulward, Enforcer, Brute.',
		'7' => 'For level 4: 12600; for level 5: 21600.',
		'8' => 'This badge belongs to level 6 mastery.',
		'9' => 'Only in Summoner\'s Rift you can get Champion points.',
		'10' => 'This badge in files is named "Cyan Hexagon" since this feature appeared.');

	if ($answers[$question] != $selected)
		die($answers[$question].'-'.$explanations[$question]);
	else
		die('Y-'.$explanations[$question]);
}

function getNewQuestion($question)
{
	if ($question < 1 || $question > 10)
		die('-1');

	$questions = array(
		'2' => 'How many additional percent you get from being in premade team of 4 members?',
		'3' => 'Up to which level you can get with free-to-play champion (assuming you don\'t own)?',
		'4' => 'How many percents Mastery points do you lose from decaying in 1 year?',
		'5' => 'How many champions in profile page can be seen as "in progress"?',
		'6' => 'Title "Brute" belongs to which role?',
		'7' => 'How many (total) CP you need to get level 5?',
		'8' => 'Which level is this badge?<br><img src="styles/images/chMasteries/Champ_Mastery_S2b.png">',
		'9' => 'What is the penalty getting Champion points in ARAM or Twisted Treeline?',
		'10' => 'This badge (in files) is known as ..?<br><img src="styles/images/chMasteries/Champ_Mastery_S2b.png">');

	$answers = array(
		'2' => array(
			'1' => 'Might differ',
			'2' => '20%',
			'3' => '12%',
			'4' => '6%'),
		'3' => array(
			'1' => 'Cannot get',
			'2' => '4',
			'3' => '3',
			'4' => '5'),
		'4' => array(
			'1' => '1%',
			'2' => '10%',
			'3' => 'They do not decay',
			'4' => 'Depends on activity'),
		'5' => array(
			'1' => '8',
			'2' => '6',
			'3' => '4',
			'4' => '3'),
		'6' => array(
			'1' => 'Fighter',
			'2' => 'Tank',
			'3' => 'Marksman',
			'4' => 'Assassin'),
		'7' => array(
			'1' => '12600',
			'2' => '33000',
			'3' => '21600',
			'4' => '6000'),
		'8' => array(
			'1' => '6',
			'2' => '5',
			'3' => '7',
			'4' => 'Not decided yet'),
		'9' => array(
			'1' => '-50%',
			'2' => '-75%',
			'3' => '0% (no penalty)',
			'4' => 'Cannot get'),
		'10' => array(
			'1' => 'Blue Hexagon',
			'2' => 'Cyan Hexagon',
			'3' => 'Cyan Heptagon',
			'4' => 'Teal Heptagon')
	);

	$html = '<div class="panel panel-info">';
	$html .= '<div class="panel-heading"><h3 class="panel-title">Question #'.$question.'</h3></div>';
	$html .= '<div class="panel-body" style="text-align: center">';
	$html .= ''.$questions[$question].'<br><br>';
	$html .= '<input type="hidden" id="h" value="'.$question.'">';
	$html .= '<input type="button" id="b1" class="btn btn-info" style="width: 20%" onclick="retrieveAnswer(1)" value="'.$answers[$question][1].'"> ';
	$html .= '<input type="button" id="b2" class="btn btn-info" style="width: 20%" onclick="retrieveAnswer(2)" value="'.$answers[$question][2].'"> ';
	$html .= '<input type="button" id="b3" class="btn btn-info" style="width: 20%" onclick="retrieveAnswer(3)" value="'.$answers[$question][3].'"> ';
	$html .= '<input type="button" id="b4" class="btn btn-info" style="width: 20%" onclick="retrieveAnswer(4)" value="'.$answers[$question][4].'">';
	$html .= ($question < 10) ? '<br><br><input type="button" id="n" class="btn btn-info hidden" style="width: 20%" onclick="getQuestion('.($question + 1).')" value="Go to next question"><br><br>' : '';
	$html .= '<div id="explain"></div>';
	$html .= '</div></div>';

	die($html);
}

if (!empty($_POST['selectedA']))
	displayAnswer($_POST['question'], $_POST['selectedA']);
elseif (!empty($_POST['newQuestion']))
	getNewQuestion($_POST['newQuestion']);
