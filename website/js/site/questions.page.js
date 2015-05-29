function addAnswerFieldToForm() {
    var count = $('.antwoord').length;
    if (count === 7) {
        $('.addAnswerToForm').prop('disabled', true);
    }

    $('.antwoorden').append(generateNewAnswerTextField());
}

// Add a question.
function addQuestion() {
    if(!validateForm())
    {
        return;
    }
    
    var answers = new Array();

    $('.antwoord').each(function() {
        var answer = {
            "text": $(this).val(),
            "rightWrong": $(this).attr("answer-rightwrong") == 'true' ? 1 : 0
        }
        answers.push(answer);
    });

    var parameter = {
        "levelId": $("#questionLevel").val(),
        "text": $("#question").val(),
        "image": $("#image1_1").val(),
        "pinpointId": $("#pinpointID").val(),
        "typeId": $('#questionType').val(),
        "answers": answers
    }

    api("SetQuestion", parameter, function(data) {
        if(data.error) {
            createErrorMessage(data.error);
            console.log(data);
            return;
        }
        redirectTo(base_url + "questions/show/");
        createSuccessMessage(data.success);
    }, function(data) {
        createErrorMessage(data.error);
        console.log(data);
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
        "levelId": $('#questionLevel').val(),
        "text": $('#question').val(),
        "image": $('#image1-1').val(),
        "typeId": $('#questionType').val(),
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

        loadQuestionLevel(data.level.id);

        $('#question').val(data.text);
        $('#image1-1').val(data.image);

        loadQuestionType(data.type.id);

        // Add answers
        $.each(data.answers, function(key, value) {
            var newAnswer = generateAnswerTextField(value['id'], value['rightWrong'], value['text']);
            $('.antwoorden').append(newAnswer);
        });
    });
}

function fillLevelTabWithQuestions(questions) {

    api("GetAllLevels", function(data) {
        generateTablist(data);

        var firstElement = true;
        $.each(data, function (key, value) {
            var content = {
                id: 'level' + value['id'],
                levelId: value['id'],
                content: generateQuestionTable(value['id'], questions),
                firstElement: firstElement
            };

            var tab = Mark.up(templates['TabPanel'], content);

            $('#tabcontent').append(tab);
            firstElement = false;
        });


    }, function(data) {
        console.log(data);
    });
}

//the table will be filled with the retrieved questions
function generateQuestionTable(levelId, questions) {
    var tableRows = "";

    for (var i = 0; i < questions.length; i++) {
        if (questions[i].level.id == levelId) {
            tableRows += generateQuestionRow(questions[i]);
        }
    }

    var table = Mark.up(templates['QuestionTable'], {rows: tableRows});
    return table;
}

function generateQuestionRow(question) {
    if (question.text.length > 60) {
        question.text = question.text.substr(0, 60) + "...";
    }

    var tableRow = Mark.up(templates['QuestionRow'], question);
    return tableRow;
}

// Generate answer text field and return it.
function generateAnswerTextField(id, rightWrong, text) {
    var answer = {
        id: id,
        rightWrong: rightWrong,
        text: text
    };
    var div = Mark.up(templates['AnswerTextField'], answer);
    return div;
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

function generateTablist(levels) {
    var firstElement = true;
    $.each(levels, function (key, value) {
        var content = {
            id: 'level' + value['id'],
            firstElement: firstElement,
            name: value['name']
        };
        var listItem = Mark.up(templates['Tab'], content);
        $('#tablist').append(listItem);
        firstElement = false;
    });
}

// Retrieve all questions.
function getQuestions() {
    api("GetAllQuestions", function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }
        fillLevelTabWithQuestions(data);
    }, function(data) {
        createErrorMessage(data.error);
    });
}

function loadQuestionLevel(levelId) {
    api("GetAllLevels", function(data) {
        $.each(data, function (key, value) {
            $('#questionLevel').append($("<option></option>").attr("value", value["id"]).text(value["name"]));
        });

        $('#questionLevel option').eq(levelId - 1).attr('selected', '');
    });
}

// Load all the types into the dropdown menu when adding a new question
function loadQuestionType(typeId) {
    api("GetAllTypes", function(data) {
        $.each(data, function (key, value) {
            $('#questionType').append($("<option></option>").attr("value", value["id"]).text(value["name"]));
        });

        $('#questionType option').eq(typeId - 1).attr('selected', '');
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