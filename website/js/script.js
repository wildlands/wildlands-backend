$(document).ready(function() {
    
	var height = $('body').height();
	$('.background-image').height(height + 75);
	
	$('.addAnswer').click(function(event) {
		
		event.preventDefault();
		
		var aantal = $('.antwoord').length;
		
		if (aantal === 0)
		{
			$('.antwoorden').html(answerFieldHTML(aantal + 1));
			return;
		}
		
		if (aantal === 7)
		{
			$(this).prop('disabled', true);
		}
		
		$('.input-group:last-child').after(answerFieldHTML(aantal + 1));
		
	});
        
        $('.addPage').click(function(event) {
		
		event.preventDefault();
                
                var aantal = $('.pagina').length;
                
                var editor = 'editor'+ (aantal + 1);
                
                if (aantal === 3)
		{
			$(this).prop('disabled', true);
		}
		
		$('.paginas .pagina:last-child').after(pageFieldHTML(aantal + 1));
                
                CKEDITOR.replace(editor);
		
	});
        
	$('.antwoorden').on('click', '.removeAnswer' , function(event) {
		
		event.preventDefault();
		
		var aantal = $('.antwoord').length;
		if (aantal === 1)
		{
			createErrorMessage('Er is minimaal een antwoord verplicht');
			return;
		}
				
		$(this).closest('.input-group').remove();
		
		$.each($('.input-group'), function(key, value) {
			
			$(this).find('.antwoord').attr('placeholder', 'Antwoord ' + (key + 1));
			
		});
		
		if (aantal <= 8)
		{
			$('.addAnswer').prop('disabled', false);
		}
		
	});
        
        $('.paginas').on('click', '.removePage' , function(event) {
		
		event.preventDefault();
		
		var aantal = $('.pagina').length;
		if (aantal === 1)
		{
			createErrorMessage('Er is minimaal een pagina verplicht');
			return;
		}
				
		$(this).closest('.form-group').remove();
		
		$.each($('.form-group'), function(key, value) {
			
			$(this).find('small').text('Pagina ' + (key + 1));
			
		});
		
		if (aantal <= 4)
		{
			$('.addPage').prop('disabled', false);
		}
		
	});
        
        $('#pinpoint').click(function() {
            addPinpoint();
        });
        
        $('#pinpointsTable').on('click', '.deletePinpoint', function(e) {
    
            e.preventDefault();
            var jsonData = JSON.stringify(
                        {
                            "pinID": $(this).attr('pinpointid')
                        });

            var tableRow = $(this).closest('tr');
            
            $.ajax({
                url: ajax_url + 'api/api.php',
                method: 'post',
                data: {
                    c: 'DeletePinpoint',
                    p: jsonData   
                },
                cache: false

            }).done(function(data) {
                
                console.log(data);
                
                if(data.error){
                    createErrorMessage(data.error);
                }
                else{
                    createSuccessMessage(data.success);
                    $(tableRow).animate({
                        backgroundColor: 'red'
                    },1000,function(){$(tableRow).fadeOut(1000)});
                }

            }).fail(function (data) {

                createErrorMessage("API IS KAPOOOT");
                console.log(data);

            });
        });
});

//retrieve all the questions
function getQuestions()
{

    $.ajax({
        url: ajax_url + 'api/api.php',
        method: 'get',
        data: {
            c: 'GetAllQuestions'
        },
        cache: false

    }).done(function (data) {

        console.log(data);
        //the retrieved data from the database will be sent to the function fillTable()
        fillTable(data);

    }).fail(function (data) {

        console.log(data);

    });
}

//retrieve the pinpoints from the database
function getPinpoints()
{
   
    $.ajax({
        url: ajax_url + 'api/api.php',
        method: 'get',
        data: {
            c: 'GetAllPinpoints'
        },
        cache: false

    }).done(function (data) {
        //the retrieved data from the database will be sent to the function fillPinpointTable()
        console.log(data);
        fillPinpointTable(data);

    }).fail(function (data) {

        console.log(data);

    });
}

//creating error message
function createErrorMessage(errorMessage) {

    $.notify({
        icon: 'fa fa-exclamation-triangle',
        message: errorMessage

    }, {
        type: 'danger'

    });

}

//creating success message
function createSuccessMessage(successMessage) {

    $.notify({
        icon: 'fa fa-check',
        message: successMessage

    }, {
        type: 'success'

    });

}

function answerFieldHTML(nummer) {

    return '<div class="input-group"><input class="form-control antwoord" type="text" placeholder="Antwoord ' + nummer + '" name="answer[]" /><div class="input-group-addon"><a href="javascript:void(0);" class="removeAnswer"><i class="fa fa-trash-o"></i></a></div></div>';

}

function pageFieldHTML(nummer) {

    return '<div class="form-group pagina"><h1><small> Pagina '+ nummer +'</small></h1><a href="javascript:void(0);" class="removePage"><i class="fa fa-trash-o"></i></a><br><label>Titel</label><input class="form-control" type="text" id="page-title"/><br><label>Afbeelding</label><input type="file"  id="page-image" name="picture"/><br><label>Tekst</label><textarea id="editor'+ nummer +'" name="editor1"></textarea><hr></div>';

}


//the table will be filled with the retrieved questions
function fillTable(questions)
{
    console.log(questions.length);
    for (var i = 0; i < questions.length; i++)
    {
        fillQuestionRow(questions[i]);
    }
}

function fillQuestionRow(question)
{
    var row = "<tr id='" + question.id + "' class='questionRow'>";
    row += "<td>" + question.id + "</td>";
    row += "<td>" + question.text + "<a href='http://localhost/wildlands-backend/website/questions/aanpassen' class='btn btn-warning pull-right'><i class='fa fa-pencil'></i></a>" + "<a href='http://localhost/wildlands-backend/website/questions/verwijder' class='btn btn-danger pull-right'><i class='fa fa-times'></i></a>" + "</td>";
    row += "</tr>";
    $("#questionsTable").append(row);
}

//the table will be filled with the retrieved pinpoints
function fillPinpointTable(pinpoints)
{
    console.log(pinpoints.length);
    for (var i = 0; i < pinpoints.length; i++)
    {
        fillPinpointRow(pinpoints[i]);
    }
}

function fillPinpointRow(pinpoint)
{
    var row = "<tr id='" + pinpoint.id + "' class='questionRow'>";
    row += "<td>" + pinpoint.name + "</td>";
    row += "<td>" + pinpoint.id + "</td>";
    row += "<td>" + pinpoint.xPos + "</td>";
    row += "<td>" + pinpoint.yPos + "<a href='aanpassen/"+pinpoint.id+"' class='btn btn-warning pull-right'><i class='fa fa-pencil'></i></a>" + "<a href='javascript:void(0)' class='btn btn-danger pull-right deletePinpoint' pinpointid='"+pinpoint.id+"'><i class='fa fa-times'></i></a>" + "</td>";
    row += "</tr>";
    $("#pinpointsTable").append(row);
}

//load all the pinpoints into the dropdown menu when adding a new question
function loadPinpoints()
{
    $.ajax({
        url: ajax_url + 'api/api.php',
        method: 'get',
        data: {
            c: 'GetAllPinpoints'
        },
        cache: false

    }).done(function (data) {

        var pinpoints = data;
        $.each(pinpoints, function(key, value)
        {
            $('#pinpointID').append($("<option></option>").attr("value", value["id"]).text(value["id"]));

    });
  });
}

//load all the types into the dropdown menu when adding a new pinpoint
function loadPinpointType()
{
    $.ajax({
        url: ajax_url + 'api/api.php',
        method: 'get',
        data: {
            c: 'GetAllTypes'
        },
        cache: false

    }).done(function (data) {

        var pinpointtype = data;
        $.each(pinpointtype, function(key, value)
        {
            $('#pinpointType').append($("<option></option>").attr("value", value["id"]).text(value["name"]));
            
    })//.fail(function (data) {

        //console.log(data);

    //})
    ;
  });
}

function addQuestion()
{   
    answer = [];
    answer[0] = $("#answer1");
    answer[1] = $("#answer2");
    answer[2] = $("#answer3");
    answer[3] = $("#answer4");
    var jsonData = JSON.stringify(
        {
            "text": $("#question").val(),
            "image": $("#image").val(),
            "pinpointId": $("#pinpointID").val(),
            "textAnswer": answer
        });
    $.ajax({
        url: ajax_url + 'api/api.php',
        method: 'post',
        data: {
            c: 'SetQuestion',
            p: jsonData
        },
        cache: false

    }).done(function (data) {

       
            
    }).fail(function (data) {

        console.log(data);
        

    });
}

function addPinpoint()
{
    var jsonData = JSON.stringify(
                {
                    "name": $("#name").val(),
                    "xPos": $("#xPos").val(),
                    "yPos": $("#yPos").val(),
                    "description": $("#description").val(),
                    "typeId": $("#pinpointType").val(),
                    
                    "title": $("#page-title").val(),
                    "image": $("#page-image").val(),
                    "text": $("#editor1").val()
                });

    createErrorMessage('Boing!');
    $.ajax({
        url: ajax_url + 'api/api.php',
        method: 'post',
        data: {
            c: 'SetPinpoint',
            p: jsonData   
        },
        cache: false

    }).done(function (data) {

       loadHtml("<?php echo BASE_URL; ?>pinpoint/show/");
            
    }).fail(function (data) {

        console.log(data);
        
    });
}