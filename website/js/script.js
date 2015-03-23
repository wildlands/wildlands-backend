$(document).ready(function() {
	getQuestions();
        
	var height = $('body').height();
	$('.background-image').height(height + 75);
	
	// Voeg een extra antworod veld toe
	$('.addAnswer').click(function(event) {
		
		// Zorgen dat de button geen rare dingen gaat doen
		event.preventDefault();
		
		// Kijken hoeveel antwoordvelden er zijn
		var aantal = $('.antwoord').length;
		
		if (aantal == 0)
		{
			$('.antwoorden').html(answerFieldHTML(aantal + 1));
			return;
		}
		
		// Als er 8 antwoorden zijn mogen er geen nieuwe antwoorden worden toegevoegd
		if (aantal == 7)
		{
			$(this).prop('disabled', true);
		}
		
		// Antwoord veld toevoegen
		$('.input-group:last-child').after(answerFieldHTML(aantal + 1));
		
	});
	
	// Een antwoord veld verwijderen
	$('.antwoorden').on('click', '.removeAnswer' , function(event) {
		
		// Zorgen dat de button geen rare dingen gaat doen
		event.preventDefault();
		
		// Aantal antwoord velden
		var aantal = $('.antwoord').length;
		
		// Voor zorgen dat er altijd 1 antwoord veld is
		if (aantal == 1)
		{
			createErrorMessage('Er is minimaal een antwoord verplicht');
			return;
		}
			
		// Antwoordveld verwijderen van scherm
		$(this).closest('.input-group').remove();
		
		// Antwoordvelden opnieuw nummeren
		$.each($('.input-group'), function(key, value) {
			
			$(this).find('.antwoord').attr('placeholder', 'Antwoord ' + (key + 1));
			
		});
		
		if (aantal <= 8)
		{
			$('.addAnswer').prop('disabled', false);
		}
		
	});
	
});

/**
 * Genereer een error noticiatie rechtsboven in het scherm
 *
 * @param string		Het bericht
 */
function createErrorMessage(errorMessage) {
	
	$.notify({
				
		icon: 'fa fa-exclamation-triangle',
		message : errorMessage
		
	}, {
		
		type : 'danger'
		
	});
	
}

/**
 * Maak een antwoord veld aan
 *
 * @param int		Het antwoord veld nummer
 * @return string	HTML output van het antwoordveld
 */
function answerFieldHTML(nummer) {
	
	return '<div class="input-group"><input class="form-control antwoord" type="text" placeholder="Antwoord ' + nummer + '" name="answer[]" /><div class="input-group-addon"><a href="javascript:void(0);" class="removeAnswer"><i class="fa fa-trash-o"></i></a></div></div>';
	
}

function getQuestions()
{
    $.ajax({
        url: "api.php",
        contentType: 'application/json',
        type: "POST",
        dataType: "json",
        data: JSON.stringify(
                {
                    "method": "GetAllQuestions"
                }),
        success: function(data) {
            if (data["result"] === "success")
            {
                fillTable(data["questions"]);
            }
            else
            {
                showCustomAlert(data["message"], "BOING!");
            }
        }
    });
}

function fillTable(questions)
{
    for (var i = 0; i < questions.length; i++)
    {
        fillTeacherRow(questions[i]);
    }
}

function fillTeacherRow(question)
{
    var row = "<tr id='" + question["id"] + "' class='teacherRow'>";
    row += "<td>" + question["username"] + "</td>";
    row += "</tr>";
    $("#questionsTable").append(row);
}


