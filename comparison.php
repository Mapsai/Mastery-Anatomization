<?
require_once('header.php');

getHeader('Mastery Comparision');
?>

<div id="mainFrame">
	<div class="panel panel-info">
		<div class="panel-heading"><h3 class="panel-title">Compare masteries between 2 Summoners:</h3></div>
		<div class="panel-body">
			<div class="form-group">
				<input type="text" class="form-control" name="summonerName" placeholder="Summoner's #1 Name" data-toggle="nameInput" title="Please enter Summoner's name. It must be 4 - 24 characters long and contain only numbers, letters and spaces.">
			</div>
			<select name="serverType" class="form-control" data-toggle="serverInput" title="Select a server for Summoner #1">
				<option value="0" disabled selected>Select a server...</option>
				<option value="BR1">Brazil</option>
				<option value="EUN1">Europe Nordic & East</option>
				<option value="EUW1">Europe West</option>
				<option value="JP1">Japan</option>
				<option value="KR">Korean</option>
				<option value="LA1">Latin America North</option>
				<option value="LA2">Latin America South</option>
				<option value="NA1">North America</option>
				<option value="OC1">Oceania</option>
				<option value="RU">Russia</option>
				<option value="TR1">Turkey</option>
			</select><hr>

			<div class="form-group">
				<input type="text" class="form-control" name="summonerName2" placeholder="Summoner's #2 Name" data-toggle="nameInput" title="Please enter Summoner's name. It must be 4 - 24 characters long and contain only numbers, letters and spaces.">
			</div>
			<select name="serverType2" class="form-control" data-toggle="serverInput" title="Select a server for Summoner #2">
				<option value="0" disabled selected>Select a server...</option>
				<option value="BR1">Brazil</option>
				<option value="EUN1">Europe Nordic & East</option>
				<option value="EUW1">Europe West</option>
				<option value="JP1">Japan</option>
				<option value="KR">Korean</option>
				<option value="LA1">Latin America North</option>
				<option value="LA2">Latin America South</option>
				<option value="NA1">North America</option>
				<option value="OC1">Oceania</option>
				<option value="RU">Russia</option>
				<option value="TR1">Turkey</option>
			</select><br>

			<li class="list-group-item" style="display: none">
				<label style="cursor: pointer">&nbsp;&nbsp; <p class="inline_paragraph" data-toggle="sortByPoints" title="This option is disabled. Can be displayed only in alphabetical order.">Sort by mastery points (descending)?</p>
				<div class="material-switch pull-left">
					<input type="checkbox" id="checkbox2" name="sort" value="1" disabled>
					<label for="checkbox2" class="label-info"></label></label>
				</div>
			</li>
			<li class="list-group-item">
				<label style="cursor: pointer">&nbsp;&nbsp; <p class="inline_paragraph" data-toggle="showAsTable" title="Show the results in table format or leave on-picture-hover format?">Sort in table format?</p>
				<div class="material-switch pull-left">
					<input type="checkbox" id="checkbox3" name="table" value="1">
					<label for="checkbox3" class="label-info"></label></label>
				</div>
			</li><br>
			<input type="button" class="btn btn-info" onclick="comp_getMasteryStatus()" value="Let's check">
		</div>
	</div>

	<div id="masteryFrame"></div>
</div>

