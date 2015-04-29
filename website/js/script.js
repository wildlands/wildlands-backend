$(document).ready(function () {

    var height = $('body').height();
    $('.background-image').height(height + 75);

    $('.addAnswer').click(function (event) {

        event.preventDefault();

        var aantal = $('.antwoord').length;

        /*if (aantal === 0) {
            //$('.antwoorden').html(answerFieldHTML(aantal + 1));
            $('.antwoorden').append(generateNewAnswerTextField(aantal + 1));
            return;
        }*/

        if (aantal === 7) {
            $(this).prop('disabled', true);
        }

        //$('.input-group:last-child').after(answerFieldHTML(aantal + 1));
        $('.antwoorden').append(generateNewAnswerTextField(aantal + 1));

    });

    $('.addPage').click(function (event) {

        event.preventDefault();

        var aantal = $('.pagina').length;

        var editor = 'editor' + (aantal + 1);

        if (aantal === 3) {
            $(this).prop('disabled', true);
        }

        $('.paginas .pagina:last-child').after(pageFieldHTML(aantal + 1));

        CKEDITOR.replace(editor);

    });

    $('.antwoorden').on('click', '.removeAnswer', function (event) {

        event.preventDefault();

        var aantal = $('.antwoord').length;
        if (aantal === 1) {
            createErrorMessage('Er is minimaal een antwoord verplicht');
            return;
        }

        $(this).closest('.input-group').remove();

        $.each($('.input-group'), function (key, value) {

            $(this).find('.antwoord').attr('placeholder', 'Antwoord ' + (key + 1));

        });

        if (aantal <= 8) {
            $('.addAnswer').prop('disabled', false);
        }

    });

    $('.paginas').on('click', '.removePage', function (event) {

        event.preventDefault();

        var aantal = $('.pagina').length;
        if (aantal === 1) {
            createErrorMessage('Er is minimaal een pagina verplicht');
            return;
        }

        $(this).closest('.form-group').remove();

        $.each($('.form-group'), function (key, value) {

            $(this).find('small').text('Pagina ' + key);
            $(this).find('textarea').attr('id', 'editor' + key);
            $(this).find('textarea').attr('name', 'editor' + key);

        });

        if (aantal <= 4) {
            $('.addPage').prop('disabled', false);
        }

    });

    $('#pinpoint').click(function (e) {
        e.preventDefault();
        addPinpoint();
    });
    
    $('.updateUser').click(function (e) {
        e.preventDefault();
        updateUser();
    });

    $('#usersTable').on('click', '.deleteUser', function (e) {
        deleteUser(e, this);
    });

    $('#pinpointsTable').on('click', '.deletePinpoint', function (e) {
        deletePinpoint(e, this);
    });

    $('#questionsTable').on('click', '.deleteQuestion', function (e) {
        deleteQuestion(e, this);
    });
});

//retrieve all the questions
function getQuestions() {

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

//retrieve all the users
function getUsers() {

    $.ajax({
        url: ajax_url + 'api/api.php',
        method: 'get',
        data: {
            c: 'GetAllUsers'
        },
        cache: false

    }).done(function (data) {

        console.log(data);
        //the retrieved data from the database will be sent to the function fillUserTable()
        fillUserTable(data);

    }).fail(function (data) {

        console.log(data);

    });
}

//retrieve the pinpoints from the database
function getPinpoints() {

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

    return '<div class="input-group"><input class="form-control antwoord" type="text" placeholder="Antwoord ' + nummer + '" /><div class="input-group-addon"><a href="javascript:void(0);" class="removeAnswer"><i class="fa fa-trash-o"></i></a></div></div>';

}

function pageFieldHTML(nummer) {
    
    return '<div class="form-group pagina"><h1><small> Pagina '+ nummer +'</small></h1><a href="javascript:void(0);" class="removePage"><i class="fa fa-trash-o"></i></a><br><label>Titel</label><input class="form-control page-title" type="text"/><br><label>Afbeelding</label><div class="input-group"><input class="form-control page-image" type="text" id="picture'+ nummer +'" readonly/><div class="input-group-addon"><a href="javascript:void(0);" data-toggle="modal" data-target="#myModal'+ nummer +'">Kies afbeelding</a></div></div><br><label>Tekst</label><textarea id="editor'+ nummer +'" name="editor'+ nummer +'"></textarea><hr>' + createModal(nummer) + '</div>';
 
}

function createModal(nummer) {
    
    return '<div class="modal fade" id="myModal'+ nummer + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Modal title</h4></div><div class="modal-body"><iframe width="580" height="500" src="'+ajax_url+'website/filemanager/dialog.php?type=1&field_id=picture'+ nummer +'&fldr=" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: hidden;"></iframe></div></div></div></div>';
    
}


//the table will be filled with the retrieved questions
function fillTable(questions) {
    console.log(questions.length);
    for (var i = 0; i < questions.length; i++) {
        fillQuestionRow(questions[i]);
    }
}

function fillQuestionRow(question) {
    var str = "";
    if(question.text.length > 60 ) {
        var str = "...";
    }
    
    var row = "<tr id='" + question.id + "' class='questionRow'>";
    row += "<td>" + question.id + "</td>";
    row += "<td>" + question.text.substr(0,60) + str + "</td>";
    row += "<td>" + "<a href='../aanpassen/" + question.id + "' class='btn btn-warning pull-right'><i class='fa fa-pencil'></i></a>" + "<a href='javascript:void(0)' class='btn btn-danger pull-right deleteQuestion' questionid='" + question.id + "'><i class='fa fa-times'></i></a>" + "</td>";
    row += "</tr>";
    $("#questionsTable").append(row);
}

//the table will be filled with the retrieved pinpoints
function fillPinpointTable(pinpoints) {
    console.log(pinpoints.length);
    for (var i = 0; i < pinpoints.length; i++) {
        fillPinpointRow(pinpoints[i]);
    }
}

function fillPinpointRow(pinpoint) {
    var str = "";
    if(pinpoint.description.length > 80 ) {
        var str = "...";
    }
    
    var row = "<tr id='" + pinpoint.id + "' class='questionRow'>";
    row += "<td>" + pinpoint.name + "</td>";
    row += "<td>" + pinpoint.id + "</td>";
    row += "<td>" + pinpoint.description.substr(0, 80) + str + "</td>";
    row += "<td>" + "<a href='../aanpassen/" + pinpoint.id + "' class='btn btn-warning pull-right'><i class='fa fa-pencil'></i></a>" + "<a href='javascript:void(0)' class='btn btn-danger pull-right deletePinpoint' pinpointid='" + pinpoint.id + "'><i class='fa fa-times'></i></a>" + "</td>";
    row += "</tr>";
    $("#pinpointsTable").append(row);
}

//the table will be filled with the retrieved users
function fillUserTable(users) {
    console.log(users.length);
    for (var i = 0; i < users.length; i++) {
        fillUserRow(users[i]);
    }
}

function fillUserRow(user) {
    
    var row = "<tr id='" + user.id + "' class='userRow'>";
    row += "<td>" + user.id + "</td>";
    row += "<td>" + user.name + "</td>";
    row += "<td>" + user.email + "</td>";
    row += "<td>" + "<a href='../aanpassen/" + user.id + "' class='btn btn-warning pull-right'><i class='fa fa-pencil'></i></a>" + "<a href='javascript:void(0)' class='btn btn-danger pull-right deleteUser' userid='" + user.id + "'><i class='fa fa-times'></i></a>" + "</td>";
    row += "</tr>";
    $("#usersTable").append(row);
}

//load all the pinpoints into the dropdown menu when adding a new question
function loadPinpoints() {
    $.ajax({
        url: ajax_url + 'api/api.php',
        method: 'get',
        data: {
            c: 'GetAllPinpoints'
        },
        cache: false

    }).done(function (data) {

        var pinpoints = data;
        $.each(pinpoints, function (key, value) {
            $('#pinpointID').append($("<option></option>").attr("value", value["id"]).text(value["id"]));

        });
    });
}

//load all the types into the dropdown menu when adding a new pinpoint
function loadPinpointType() {
    $.ajax({
        url: ajax_url + 'api/api.php',
        method: 'get',
        data: {
            c: 'GetAllTypes'
        },
        cache: false

    }).done(function (data) {

        var pinpointtype = data;
        $.each(pinpointtype, function (key, value) {
                $('#pinpointType').append($("<option></option>").attr("value", value["id"]).text(value["name"]));

            }) //.fail(function (data) {

        //console.log(data);

        //})
        ;
    });
}

function addQuestion() {
    answer = [];
    answer[0] = $("#answer1");
    answer[1] = $("#answer2");
    answer[2] = $("#answer3");
    answer[3] = $("#answer4");
    var jsonData = JSON.stringify({
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



function deleteQuestion(event, sender) {
    event.preventDefault();

    var jsonData = JSON.stringify({
        "id": $(sender).attr('questionid')
    });

    console.log("Delete Question: " + jsonData);

    var tableRow = $(sender).closest('tr');

    $.ajax({
        url: ajax_url + 'api/api.php',
        method: 'post',
        data: {
            c: 'DeleteQuestion',
            p: jsonData
        },
        cache: false

    }).done(function (data) {

        console.log(data);

        if (data.error) {
            createErrorMessage(data.error);
        } else {
            createSuccessMessage(data.success);
            $(tableRow).animate({
                backgroundColor: 'red'
            }, 1000, function () {
                $(tableRow).fadeOut(1000)
            });
        }

    }).fail(function (data) {

        createErrorMessage("API IS KAPOOOT");
        console.log(data);

    });
}

function addPinpoint()
{
    //arraylist voor pages
    var pages = [];
    
    var i = 0;
    
    $.each(CKEDITOR.instances, function(index, value){
        var page = {
            "title": $('.paginas .page-title').eq(i).val(),
            "pageimage": $('.paginas .page-image').eq(i).val(),
            "text": value.getData()
        };
        pages.push(page);
        i++;
    });
    
                var jsonData = JSON.stringify(
                {
                    //pinpoint
                    "name": $("#name").val(),
                    "xPos": $("#xPos").text(),
                    "yPos": $("#yPos").text(),
                    "description": $("#description").val(),
                    "typeId": $("#pinpointType").val(),
                    //pages
                    "pages": pages
                });
                console.log(jsonData);

    $.ajax({
        url: ajax_url + 'api/api.php',
        method: 'post',
        data: {
            c: 'SetPinpoint',
            p: jsonData
        },
        cache: false

    }).done(function (data) {
        
        redirectTo(base_url + "pinpoints/show/");

    }).fail(function (data) {

        console.log(data);

    });
}

function addUser()
{
        var jsonData = JSON.stringify(
        {
            //pinpoint
            "name": $("#name").val(),
            "email": $("#email").val(),
            "password": $("#pass").val()
        });
        console.log(jsonData);

    $.ajax({
        url: ajax_url + 'api/api.php',
        method: 'post',
        data: {
            c: 'SetUser',
            p: jsonData
        },
        cache: false

    }).done(function (data) {
        
        redirectTo(base_url + "users/show/");

    }).fail(function (data) {

        console.log(data);

    });
}

function deleteUser(event, sender) {

    event.preventDefault();
    var jsonData = JSON.stringify({
        "id": $(sender).attr('userid')
    });

    var tableRow = $(sender).closest('tr');

    $.ajax({
        url: ajax_url + 'api/api.php',
        method: 'post',
        data: {
            c: 'DeleteUser',
            p: jsonData
        },
        cache: false

    }).done(function (data) {

        console.log(data);

        if (data.error) {
            createErrorMessage(data.error);
        } else {
            createSuccessMessage(data.success);
            $(tableRow).animate({
                backgroundColor: 'red'
            }, 1000, function () {
                $(tableRow).fadeOut(1000);
            });
        }

    }).fail(function (data) {

        createErrorMessage("API IS KAPOOOT");
        console.log(data);

    });
}

function updateUser() {
    
    var jsonData = JSON.stringify({
        "id": $('#userId').val(),
        "name": $('#name').val(),
        "email": $('#email').val(),
        "password": $('#pass').val()
    });
    
    $.ajax({
        url: ajax_url + 'api/api.php',
        method: 'post',
        data: {
            c: 'SetUser',
            p: jsonData
        },
        cache: false
    }).done(function (data) {
        if (data.error) {
            createErrorMessage(data.error);
        } else {
            redirectTo(base_url + "users/show/");
            createSuccessMessage(data.success);
        }
        
    }).fail(function (data) {
        console.log(data);
        createErrorMessage(data.responseText);
    });
}

function updatePinpoint(pinpointId) {
    
    var jsonData = JSON.stringify({
        "pinID": pinpointId,
        "name": $('#name').val(),
        "xPos": $('#xPos').val(),
        "yPos": $('#yPos').val(),
        "description": $('#description').val(),
        "pinpointType": $('#pinpointType').val()
    });
    
    $.ajax({
        url: ajax_url + 'api/api.php',
        method: 'post',
        data: {
            c: 'SetPinpoint',
            p: jsonData
        },
        cache: false
    }).done(function (data) {
        if (data.error) {
            createErrorMessage(data.error);
        } else {
            redirectTo(base_url + "pinpoints/show/");
            createSuccessMessage(data.success);
        }
        
    }).fail(function (data) {
        createErrorMessage(data.error);
    });
}

function deletePinpoint(event, sender) {

    event.preventDefault();
    var jsonData = JSON.stringify({
        "id": $(sender).attr('pinpointid')
    });

    var tableRow = $(sender).closest('tr');

    $.ajax({
        url: ajax_url + 'api/api.php',
        method: 'post',
        data: {
            c: 'DeletePinpoint',
            p: jsonData
        },
        cache: false

    }).done(function (data) {

        console.log(data);

        if (data.error) {
            createErrorMessage(data.error);
        } else {
            createSuccessMessage(data.success);
            $(tableRow).animate({
                backgroundColor: '#FF8585'
            }, 1000, function () {
                $(tableRow).fadeOut(1000);
            });
        }

    }).fail(function (data) {

        createErrorMessage("API IS KAPOOOT");
        console.log(data);

    });

    if (aantal <= 8) {
        $('.addAnswer').prop('disabled', false);
    }
}

function fillEditQuestionFormWithData(questionId) {
    var jsonData = JSON.stringify({
        "id": questionId
    });
    
    $.ajax({
        url: ajax_url + 'api/api.php',
        method: 'post',
        data: {
            c: 'GetQuestionById',
            p: jsonData
        },
        cache: false
    }).done(function (data) {
        if (data.error) {
            
            return;
        }
        
        $('#question').val(data.text);
        
        $('#image_preview').attr('db-src', data.image);
        $('#image_preview').attr('src', base_url + 'images/' + data.image);
        
        // Add answers
        var i = 1;
        $.each(data.answers, function(key, value) {
            var newAnswer = generateAnswerTextField(i, value['id'], value['rightWrong'], value['text']);
            $('.antwoorden').append(newAnswer);
        
            i++;
        });
    });
}

function generateNewAnswerTextField(index) {
    return generateAnswerTextField(index, undefined, undefined, undefined);
}

function generateAnswerTextField(index, id, rightWrong, text) {
    var newAnswer_div = $('<div></div>').addClass('input-group');
    var newAnswer_div_input = $('<input></input>').addClass('form-control').addClass('antwoord').attr('type', 'text').attr('placeholder', 'Antwoord ' + index).attr('answer-id', id).attr('answer-rightwrong', rightWrong).val(text);
    var newAnswer_div_div = $('<div></div>').addClass('input-group-addon');
    var newAnswer_div_div_a = $('<a></a>').addClass('removeAnswer').attr('href', 'javascript:void(0);');
    var newAnswer_div_div_a_i = $('<i></i>').addClass('fa').addClass('fa-trash-o');
    
    newAnswer_div.append(newAnswer_div_input);
    newAnswer_div.append(newAnswer_div_div);
    newAnswer_div_div.append(newAnswer_div_div_a);
    newAnswer_div_div_a.append(newAnswer_div_div_a_i);
    
    return newAnswer_div;
}

function editQuestion(questionId) {
    
    var answers = new Array();
    
    $('.antwoord').each(function(key, value) {
        var answer = {}
        if ($(this).attr('answer-id')) {
            answer.id = $(this).attr('answer-id');
        }
        if ($(this).attr('answer-rightwrong')) {
            answer.rightWrong = $(this).attr('answer-rightwrong');
        } else {
            answer.rightWrong = "false";
        }
        
        answer.text = $(this).val();
        answers.push(answer);
    });
    
    var jsonData = JSON.stringify({
        "id": questionId,
        "text": $('#question').val(),
        "image": $('#image_preview').attr('db-src'),
        "answers": answers
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
        if (data.error) {
            createErrorMessage(data.error);
        } else {
            redirectTo(base_url + "questions/show/");
            createSuccessMessage(data.success);
        }
        
    }).fail(function (data) {
        createErrorMessage(data.error);
    });
    
}

function redirectTo(pageLink) {
    window.location.replace(pageLink);
}