/******************************************
		Styling/Global functions START
******************************************/

function initializeDisplay()
{
	$('[data-toggle="sortByPoints"]').tooltip({placement: "top", html: true});
	$('[data-toggle="nameInput"]').tooltip({placement: "top", html: true});
	$('[data-toggle="serverInput"]').tooltip({placement: "top", html: true});
	$('[data-toggle="showAsTable"]').tooltip({placement: "top", html: true});

	$('.championImage').on({
		mousemove: function(e)
		{
			var chName = $(this).attr('class').split(' ')[1].split('-');
			if ($('.ch-' + chName[1]).height() < 100)
			{
				$('.ch-'+ chName[1]).css({
					top: e.pageY + 50,
					left: e.pageX + 10
				});
			}
			else
			{
				$('.ch-'+ chName[1]).css({
					top: e.pageY - 10,
					left: e.pageX + 10
				});
			}
		},
		mouseenter: function()
		{
			var chName = $(this).attr('class').split(' ')[1].split('-');
			$('.ch-' + chName[1]).show();
		},
		mouseleave: function()
		{
			var chName = $(this).attr('class').split(' ')[1].split('-');
			$('.ch-' + chName[1]).hide();
		}
	});

	$('.championImage_2').on({
		mousemove: function(e)
		{
			var chName = $(this).attr('class').split(' ')[1].split('-');
			if ($('.ch_2-' + chName[1]).height() < 100)
			{
				$('.ch_2-'+ chName[1]).css({
					top: e.pageY + 50,
					left: e.pageX + 10
				});
			}
			else
			{
				$('.ch_2-'+ chName[1]).css({
					top: e.pageY - 10,
					left: e.pageX + 10
				});
			}
		},
		mouseenter: function()
		{
			var chName = $(this).attr('class').split(' ')[1].split('-');
			$('.ch_2-' + chName[1]).show();
		},
		mouseleave: function()
		{
			var chName = $(this).attr('class').split(' ')[1].split('-');
			$('.ch_2-' + chName[1]).hide();
		}
	});
}

function applyFadeIn(id, time)
{
    setTimeout(function()
				{
		$('#' + id).fadeOut(1000);
	}, time * 1000);

    setTimeout(function()
				{
		$('#' + id).remove();
	}, time * 2000);
}

function createAlert(message)
{
	$('#masteryFrame').html('<div class="panel panel-danger master_panel"><div class="panel-heading"><h3 class="panel-title">Uh oh...</h3></div><div class="panel-body">' + message + '</div></div></div>');
}

function displayInformation()
{
	$('#moreInfoMenu').click(function()
	{
		$('#moreInfo').fadeToggle(1000);
	});
}

function getServerStatus()
{
	if (document.getElementById('serverPanels').innerHTML == "")
	{
		$('#serverPanels').html('<img src="styles/images/loading_dots.gif" class="loading_image" alt="Loading...">');
		new Ajax.Request('ajaxFiles/serverStatus.php',
		{
			method: 'post',
			cache: true,
			parameters: $H({
						id: 1
					}),
			onSuccess: function(transport)
			{
				$('#serverPanels').html(transport.responseText);
			},
			onFailure: function(transport)
			{
				createAlert('Oh no, something went wrong. Could not access file or retrieve its content.');
			}
		});
	}
	else if (document.getElementById('serverPanels').offsetHeight == 0)
		document.getElementById('serverPanels').style.display = 'inherit';
	else
		document.getElementById('serverPanels').style.display = 'none';
}

window.onload = function()
{
	initializeDisplay();
	displayInformation();
	writeDataToStorage();
};

/******************************************
		Styling functions END
******************************************/

/******************************************
		Data writing functions START
******************************************/

function writeDataToStorage(name, server, sort, table, data)
{
	localStorage.setItem(name + "-" + server + "-" + sort + "-" + table, data);
	localStorage.setItem(name + "--" + server + "--" + sort + "--" + table, new Date().getTime());
}

function loadDataFromStorage(name, server, sort, table)
{
	if (localStorage.getItem(name + "-" + server + "-" + sort + "-" + table) == null)
		return false;
	else if (new Date().getTime() - localStorage.getItem(name + "--" + server + "--" + sort + "--" + table) >= 10800000)	// 3 hours.
		return false;
	else
	{
		var content = document.getElementById('masteryFrame');
		content.innerHTML = localStorage.getItem(name + "-" + server + "-" + sort + "-" + table);
		initializeDisplay();
		return true;
	}
}

function writeDataToStorage(name, name2, server, server2, table, data)
{
	localStorage.setItem(name + "-" + name2 + "-" + server + "-" + server2 + "-" + table, data);
	localStorage.setItem(name + "--" + name2 + "-" + server + "--" + server2 + "--" + table, new Date().getTime());
}

function loadDataFromStorage(name, name2, server, server2, table)
{
	if (localStorage.getItem(name + "-" + name2 + "-" + server + "-" + server2 + "-" + table) == null)
		return false;
	else if (new Date().getTime() - localStorage.getItem(name + "--" + name2 + "-" + server + "--" + server2 + "--" + table) >= 10800000)	// 3 hours.
		return false;
	else
	{
		var content = document.getElementById('masteryFrame');
		content.innerHTML = localStorage.getItem(name + "-" + name2 + "-" + server + "-" + server2 + "-" + table);
		initializeDisplay();
		return true;
	}
}

/******************************************
		Data writing functions END
******************************************/

/******************************************
	Single retriever functions START
******************************************/

function validEntry()
{
	var input = document.getElementsByName('summonerName')[0];
	var inputServer = document.getElementsByName('serverType')[0];

	if (!input)
	{
		createAlert('Web element not found... Something went wrong.');
		return false;
	}
	else if (input.value.length < 4 || input.value.length > 24)
	{
		createAlert('Summoner\'s name must be between 4 and 24 characters long. Your input is ' + input.value.length + ' characters long.');
		return false;
	}

	if (!inputServer)
	{
		createAlert('Web element not found... Something went wrong.');
		return false;
	}
	else if (inputServer.options[inputServer.selectedIndex].value == 0)
	{
		createAlert('Server was not chosen. Please select server.');
		return false;
	}

	return true;
}

function getMasteryStatus()
{
	if (!validEntry())
		return;

	var name = $("input[name=summonerName]").val();
	var server = $("select[name=serverType]").val();

	if ($('[name="sort"]:checkbox').prop('checked'))
		var sort = 1;
	else
		var sort = 0;

	if ($('[name="table"]:checkbox').prop('checked'))
		var table = 1;
	else
		var table = 0;

	if (loadDataFromStorage(name, server, sort, table))
		return;

	$('#masteryFrame').html('<img src="styles/images/loading_dots.gif" class="loading_image" alt="Loading...">');
	$('input[type="button"]').prop('disabled', true);
	new Ajax.Request('ajaxFiles/retrieveMasteries.php',
	{
		method: 'post',
		cache: true,
		parameters: $H({
					summName: name,
					server: server,
					sortByPoints: sort,
					showAsTable: table
				}),
		onSuccess: function(transport)
		{
			if (transport.responseText == 'F')
			{
				createAlert('Server returned an error. Invalid input. Please refresh a page and try again.');
			}
			else if (transport.responseText == 'N')
				createAlert('It seems there are no Summoners with this nickname. Please double check your input name and server.');
			else
			{
				$('#masteryFrame').html(transport.responseText);
				initializeDisplay();
				writeDataToStorage(name, server, sort, table, transport.responseText);
			}

			$('input[type="button"]').prop('disabled', false);
		},
		onFailure: function(transport)
		{
			createAlert('Oh no, something went wrong. Could not access file or retrieve its content.');
			$('input[type="button"]').prop('disabled', false);
		}
	});
}

/******************************************
		Single retriever functions END
******************************************/

/******************************************
		Comparision functions
******************************************/

function comp_validEntry()
{
	var input = document.getElementsByName('summonerName')[0];
	var input2 = document.getElementsByName('summonerName2')[0];
	var inputServer = document.getElementsByName('serverType')[0];
	var inputServer2 = document.getElementsByName('serverType2')[0];

	if (!input || !input2)
	{
		createAlert('Web element(s) not found... Something went wrong.');
		return false;
	}
	else if (input.value.length < 4 || input.value.length > 24 || input2.value.length < 4 || input2.value.length > 24)
	{
		createAlert('Summoner\'s name(s) must be between 4 and 24 characters long (both fields). Your input is ' + input.value.length + ' and ' + input2.value.length + ' characters long.');
		return false;
	}

	if (!inputServer || !inputServer2)
	{
		createAlert('Web element(s) not found... Something went wrong.');
		return false;
	}
	else if (inputServer.options[inputServer.selectedIndex].value == 0 || inputServer2.options[inputServer2.selectedIndex].value == 0)
	{
		createAlert('Server(s) was not chosen. Please select server(s).');
		return false;
	}

	return true;
}

function comp_getMasteryStatus()
{
	if (!comp_validEntry())
		return;

	var name = $("input[name=summonerName]").val();
	var name2 = $("input[name=summonerName2]").val();
	var server = $("select[name=serverType]").val();
	var server2 = $("select[name=serverType2]").val();

	if ($('[name="table"]:checkbox').prop('checked'))
		var table = 1;
	else
		var table = 0;

	if (loadDataFromStorage(name, name2, server, server2, table))
		return;

	$('#masteryFrame').html('<img src="styles/images/loading_dots.gif" class="loading_image" alt="Loading...">');
	$('input[type="button"]').prop('disabled', true);
	new Ajax.Request('ajaxFiles/compareMasteries.php',
	{
		method: 'post',
		cache: true,
		parameters: $H({
					summName: name,
					summName2: name2,
					server: server,
					server2: server2,
					showAsTable: table
				}),
		onSuccess: function(transport)
		{
			if (transport.responseText == 'F')
			{
				createAlert('Server returned an error. Invalid input. Please refresh a page and try again.');
			}
			else if (transport.responseText == 'N')
			{
				createAlert('It seems there are no Summoners with this/these nickname(s). Please double check your input name and server.');
			}	
			else
			{
				$('#masteryFrame').html(transport.responseText);
				initializeDisplay();
				writeDataToStorage(name, name2, server, server2, table, transport.responseText);
			}

			$('input[type="button"]').prop('disabled', false);
		},
		onFailure: function(transport)
		{
			createAlert('Oh no, something went wrong. Could not access file or retrieve its content.');
			$('input[type="button"]').prop('disabled', false);
		}
	});
}

/******************************************
		Comparision functions END
******************************************/