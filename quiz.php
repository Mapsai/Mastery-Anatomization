<?
require_once('header.php');

getHeader('Mastery Quizz');
?>

<script src="styles/scripts_quiz.js"></script>

<div id="mainFrame">
	<div class="panel panel-info">
		<div class="panel-heading"><h3 class="panel-title">Question #1</h3></div>
		<div class="panel-body" style="text-align: center">
			<b>There are 10 question in this quiz and all of them related to Mastery system. Only 1 answer can be correct in any question. After choosing you will see incorrect/correct answer(s). Information was taken from LoL wikipedia and Riot Support site... Good luck!</b><br><br>
			From which level Summoner is able to receive Mastery points?<br><br>
			<input type="hidden" id="h" value="1">
			<input type="button" id="b1" class="btn btn-info" style="width: 20%" onclick="retrieveAnswer(1)" value="From level 1">
			<input type="button" id="b2" class="btn btn-info" style="width: 20%" onclick="retrieveAnswer(2)" value="From level 5">
			<input type="button" id="b3" class="btn btn-info" style="width: 20%" onclick="retrieveAnswer(3)" value="From level 10">
			<input type="button" id="b4" class="btn btn-info" style="width: 20%" onclick="retrieveAnswer(4)" value="Only at level 30">
			<br><br><input type="button" id="n" class="btn btn-info hidden" style="width: 20%" onclick="getQuestion(2)" value="Go to next question"><br><br>
			<div id="explain"></div>
		</div>
	</div>
</div>

<div id="mainFrame2" class="hidden">
	<div class="panel panel-info">
		<div class="panel-heading"><h3 class="panel-title">Results</h3></div>
		<div class="panel-body" id="resDiv" style="text-align: center">0/10 points gained through this test.</div>
</div>