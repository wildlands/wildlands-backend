function addAnswerFieldToForm() {
    var count = $('.antwoord').length;
    if (count === 7) {
        $('.addAnswerToForm').prop('disabled', true);
    }

    $('.antwoorden').append(generateNewAnswerTextField());
}

// Add a question.
function addQuestion() {
    var answers = new Array();

    $('.antwoord').each(function() {
        var answer = {
            "text": $(this).val(),
            "rightWrong": $(this).attr("answer-rightwrong") == 'true' ? 1 : 0
        }
        answers.push(answer);
    });

    var parameter = {
        "text": $("#question").val(),
        "image": $("#image1").val(),
        "pinpointId": $("#pinpointID").val(),
        "answers": answers
    }

    api("SetQuestion", parameter, function(data) {
        if(data.error) {
            createErrorMessage(data.error);
        } else {
            redirectTo(base_url + "questions/show/");
            createSuccessMessage(data.success);
        }
    }, function(data) {
        createErrorMessage(data.error);
    });
}

function deleteQuestionAjax(sender)
{
    var tableRow = $(sender).closest('tr');

    var parameter = {
        "id": $(sender).attr('questionId')
    }
    
    api("DeleteQuestion", parameter, function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }

        createSuccessMessage(data.success);
        $(tableRow).animate({
            backgroundColor: 'red'
        }, 1000, function () {
            $(tableRow).fadeOut(1000)
        });
    }, function(data) {
        createErrorMessage(data.error);
    });
}

// Delete a question.
function deleteQuestion(sender) {
    
    bootbox.dialog({
        message: "Wilt u deze vraag zeker weten verwijderen?",
        title: "Vraag verwijderen",
        buttons: {
          success: {
            label: "Ja",
            className: "btn-success",
            callback: function() {
              deleteQuestionAjax(sender);
            }
          },
          danger: {
            label: "Annuleren",
            className: "btn-danger",
            callback: function() {
              del = false;
            }
          }
        }
      });
}

// Edit the question with the specified question id.
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

    var parameter = {
        "id": questionId,
        "text": $('#question').val(),
        "image": $('#image1').val(),
        "answers": answers
    }

    api("SetQuestion", parameter, function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }
        createSuccessMessage(data.success);
        redirectTo(base_url + "questions/show/");
    }, function(data) {
        createErrorMessage(data.error);
    });
}

// Fill the 'edit question' form with data specified by the question id.
function fillEditQuestionFormWithData(questionId) {
    var parameter = {
        "id": questionId
    }

    api("GetQuestionById", parameter, function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }

        $('#question').val(data.text);
        $('#image').attr("value", data.image);

        // Add answers
        $.each(data.answers, function(key, value) {
            var newAnswer = generateAnswerTextField(value['id'], value['rightWrong'], value['text']);
            $('.antwoorden').append(newAnswer);
        });
    });
}

//the table will be filled with the retrieved questions
function fillQuestionTable(questions) {
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
    row += "<td>" + "<a href='../edit/" + question.id + "' class='btn btn-warning col-md-offset-3'><i class='fa fa-pencil'></i></a>" + "<a class='btn btn-danger pull-right' questionId='" + question.id + "' onclick='javascript: deleteQuestion(this);'><i class='fa fa-times'></i></a>" + "</td>";
    row += "</tr>";
    $("#questionsTable").append(row);
}

// Generate answer text field and return it.
function generateAnswerTextField(id, rightWrong, text) {
    var newAnswer_div = $('<div></div>').addClass('input-group');
    var newAnswer_div_input = $('<input></input>').addClass('form-control').addClass('antwoord').attr('type', 'text').attr('placeholder', 'Antwoord ' + (rightWrong ? '(correct)' : '(fout)')).attr('answer-id', id).attr('answer-rightwrong', rightWrong).val(text);
    var newAnswer_div_div = $('<div></div>').addClass('input-group-addon');
    var newAnswer_div_div_a = $('<a></a>').attr('onclick', 'javascript: removeAnswerTextField(this);');
    var newAnswer_div_div_a_i = $('<i></i>').addClass('fa').addClass('fa-trash-o');

    newAnswer_div.append(newAnswer_div_input);
    newAnswer_div.append(newAnswer_div_div);


    if (rightWrong) {
        newAnswer_div_input.css('background-color', '#E8FFE8');
        newAnswer_div_div_a_i.css('color', 'darkred');
        newAnswer_div_div.append(newAnswer_div_div_a_i);
    } else {
        newAnswer_div_input.css('background-color', '#FFE8E8');
        newAnswer_div_div.append(newAnswer_div_div_a);
        newAnswer_div_div_a.append(newAnswer_div_div_a_i);
    }

    return newAnswer_div;
}

// Generate four default answer text fields and append them to '.antwoorden'.
function generateDefaultAnswerTextFields() {
    $('.antwoorden').append(generateAnswerTextField(undefined, true, undefined));
    $('.antwoorden').append(generateAnswerTextField(undefined, false, undefined));
    $('.antwoorden').append(generateAnswerTextField(undefined, false, undefined));
    $('.antwoorden').append(generateAnswerTextField(undefined, false, undefined));
}

// Generate new answer text field.
function generateNewAnswerTextField() {
    return generateAnswerTextField(undefined, undefined, undefined);
}

// Retrieve all questions.
function getQuestions() {
    api("GetAllQuestions", function(data) {
        console.log(data);
        fillQuestionTable(data);
    }, function(data) {
        console.log(data);
    });
}

// Remove answer text field.
function removeAnswerTextField(sender) {
    var count = $('.antwoord').length;
    if (count === 1) {
        createErrorMessage('Er is minimaal een antwoord verplicht');
        return;
    }

    $(sender).closest('.input-group').remove();

    $('.addAnswerToForm').prop('disabled', false);
}