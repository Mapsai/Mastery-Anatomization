/******************************************
		The quiz function START
******************************************/

function createAlertQuiz(message)
{
	var warningMessage = document.createElement('div');
	warningMessage.id = 'warning_Message';
	warningMessage.innerHTML = '<div id="warning_Message" class="alert alert-danger fade in"><b>' + message + '</b></div>';
	document.body.appendChild(warningMessage);

	applyFadeIn('warning_Message', 2);
}

function retrieveAnswer(selected)
{
	var questionNo = $("#h").val();

	$('input[type="button"]').prop('disabled', true);
	$('#n').prop('disabled', false);

	new Ajax.Request('ajaxFiles/quiz_answers.php',
	{
		method: 'post',
		parameters: $H({
					question: questionNo,
					selectedA: selected
				}),
		onSuccess: function(transport)
		{
			if (transport.responseText == '-1')
				createAlertQuiz('Whoops, invalid input.');
			else if (transport.responseText.substr(0, transport.responseText.indexOf('-')) == 'Y')
				$('#b' + selected).toggleClass('btn-info btn-success');
			else
			{
				$('#b' + selected).toggleClass('btn-info btn-danger');
				$('#b' + transport.responseText.substr(0, transport.responseText.indexOf('-'))).toggleClass('btn-info btn-success');
			}

			$('#explain').html(transport.responseText.split("-").pop());
			$('#n').removeClass('hidden');

			if (transport.responseText.substr(0, transport.responseText.indexOf('-')) == 'Y')
			{
				var score = parseInt($('#resDiv').text().substr(0, $('#resDiv').text().indexOf('/')));
				score = score + 1;

				if (score < 3)
					$('#resDiv').html(score + '/10 points gained through this test. The questions were medium-hard, so don\'t be sad. :)');
				else if (score < 6)
					$('#resDiv').html(score + '/10 points gained through this test. The questions were medium-hard and you performed well!');
				else if (score < 10)
					$('#resDiv').html(score + '/10 points gained through this test. The questions were medium-hard; your score is very good, congratulations. :)');
				else
					$('#resDiv').html(score + '/10 points gained through this test. The questions were medium-hard... yet you answered all questions correctly. Perfect!');
			}

			if (questionNo == 10)
				$('#mainFrame2').removeClass('hidden');
		},
		onFailure: function(transport)
		{
			createAlert('Oh no, something went wrong. Could not access file or retrieve its content.');
			$('input[type="button"]').prop('disabled', false);
		}
	});
}

function getQuestion(number)
{
	new Ajax.Request('ajaxFiles/quiz_answers.php',
	{
		method: 'post',
		parameters: $H({
					newQuestion: number
				}),
		onSuccess: function(transport)
		{
			if (transport.responseText == '-1')
				createAlert('Something went wrong and we couldn\'t retrieve new question...');
			else
				$('#mainFrame').html(transport.responseText);
		},
		onFailure: function(transport)
		{
			createAlert('Oh no, something went wrong. Could not access file or retrieve its content.');
			$('input[type="button"]').prop('disabled', false);
		}
	});
}

/******************************************
			The quiz function END
******************************************/