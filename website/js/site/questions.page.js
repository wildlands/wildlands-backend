// Add an answerField to the form
function addAnswerFieldToForm() {
    // Get the current count of answers
    var count = $('.antwoord').length;

    // If the current count is '7', then disable the 'add answer' button
    if (count === 7) {
        $('.addAnswerToForm').prop('disabled', true);
    }

    // Add an answer field
    $('.antwoorden').append(generateNewAnswerTextField());
}

// Add a question
function addQuestion() {
    // Validate the form and return if it isn't valid
    if(!validateForm())
    {
        return;
    }

    // Create an array for all answers
    var answers = new Array();

    // Get the data of all answers and push it to the array
    $('.antwoord').each(function() {
        var answer = {
            "text": $(this).val(),
            "rightWrong": $(this).attr("answer-rightwrong") === 'true' ? 1 : 0
        };
        answers.push(answer);
    });

    // Set the question data as the parameter
    var parameter = {
        "levelId": $("#questionLevel").val(),
        "text": $("#question").val(),
        "image": $("#image1_1").val(),
        "pinpointId": $("#pinpointID").val(),
        "typeId": $('#questionType').val(),
        "answers": answers
    };

    // Send a request to the api using the 'SetQuestion' command
    api("SetQuestion", parameter, function(data) {
        // Redirect to questions overview
        redirectTo(base_url + "questions/show/");
    });
}

// Delete a question with ajax
function deleteQuestionAjax(sender)
{
    // Get the table row of the question specified by the sender
    var tableRow = $(sender).closest('tr');

    // Set the id as the parameter
    var parameter = {
        "id": $(sender).attr('questionId')
    };

    // Send a request to the api using the 'DeleteQuestion' command
    api("DeleteQuestion", parameter, function(data) {
        // Show success message and fade out the corresponding table row
        createSuccessMessage(data.success);
        $(tableRow).animate({
            backgroundColor: 'red'
        }, 1000, function () {
            $(tableRow).fadeOut(1000);
        });
    });
}

// Delete a question (confirmation box).
function deleteQuestion(sender) {
    // Show a confirmation box for deleting a question
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
    // Validate the form and return if it isn't valid
    if(!validateForm())
    {
        return;
    }
    // Create an array for all answers
    var answers = new Array();

    // Add data from each answer to the array
    $('.antwoord').each(function(key, value) {
        var answer = {};
        if ($(this).attr('answer-id')) {
            answer.id = $(this).attr('answer-id');
        }
        if ($(this).attr('answer-rightwrong') && $(this).attr('answer-rightwrong') === "true") {
            answer.rightWrong = true;
        } else {
            answer.rightWrong = false;
        }

        answer.text = $(this).val();
        answers.push(answer);
    });

    // Set the data as the parameter
    var parameter = {
        "id": questionId,
        "levelId": $('#questionLevel').val(),
        "text": $('#question').val(),
        "image": $('#image1_1').val(),
        "typeId": $('#questionType').val(),
        "answers": answers
    };

    // Send a request to the api using the 'SetQuestion' command
    api("SetQuestion", parameter, function(data) {
        // Redirect to the questions overview
        redirectTo(base_url + "questions/show/");
    });
}

// Fill the 'edit question' form with data specified by the question id.
function fillEditQuestionFormWithData(questionId) {
    // Set the id as the parameter
    var parameter = {
        "id": questionId
    };

    // Send a request to the api using the 'GetQuestionById' command
    api("GetQuestionById", parameter, function(data) {
        // Set the received data
        var image = '<img src="' + data.image + '" style="width:200px;height:200px;">';
        
        loadQuestionLevel(data.level.id);

        $('#question').val(data.text);
        $('#image1_1').val(data.image);
        
        $('#image1_1').popover({placement: 'top', content: image, html: true});

        loadQuestionType(data.type.id);

        // Add answers
        $.each(data.answers, function(key, value) {
            var newAnswer = generateAnswerTextField(value['id'], value['rightWrong'], value['text']);
            $('.antwoorden').append(newAnswer);
        });
    });
}

// Fill levelTabs with question rows
function fillLevelTabWithQuestions(questions) {
    // Send a request to the api using the 'GetAllLevels' api
    api("GetAllLevels", function(data) {
        // Generate a tab list with the specified data
        generateTablist(data);

        // Set the initial value to true
        var firstElement = true;
        $.each(data, function (key, value) {
            // Specify the content
            var content = {
                id: 'level' + value['id'],
                levelId: value['id'],
                content: generateQuestionTable(value['id'], questions),
                firstElement: firstElement
            };

            // Fill the template with the specified content
            var tab = Mark.up(templates['TabPanel'], content);

            // Append it to the tab content div
            $('#tabcontent').append(tab);

            // Set the value to false, so that only the first time it is true
            firstElement = false;
        });
    });
}

//the table will be filled with the retrieved questions
function generateQuestionTable(levelId, questions) {
    // Initialize the tableRows string variable
    var tableRows = "";

    // Generate a question row for every question
    for (var i = 0; i < questions.length; i++) {
        if (questions[i].level.id === levelId) {
            tableRows += generateQuestionRow(questions[i]);
        }
    }

    // Fill the template with the specified data
    var table = Mark.up(templates['QuestionTable'], {rows: tableRows});

    // Return the html
    return table;
}

// Generate a question row
function generateQuestionRow(question) {
    // If the description is too long, cut it and append '...'
    if (question.text.length > 60) {
        question.text = question.text.substr(0, 60) + "...";
    }

    // Fill the template with the specified data
    var tableRow = Mark.up(templates['QuestionRow'], question);

    // Return the html
    return tableRow;
}

// Generate answer text field and return it
function generateAnswerTextField(id, rightWrong, text) {
    // Set the content for the template
    var answer = {
        id: id,
        rightWrong: rightWrong,
        text: text
    };

    // Fill the template
    var div = Mark.up(templates['AnswerTextField'], answer);

    // Return the html
    return div;
}

// Generate four default answer text fields and append them to '.antwoorden'
function generateDefaultAnswerTextFields() {
    $('.antwoorden').append(generateAnswerTextField(undefined, true, undefined));
    $('.antwoorden').append(generateAnswerTextField(undefined, false, undefined));
    $('.antwoorden').append(generateAnswerTextField(undefined, false, undefined));
    $('.antwoorden').append(generateAnswerTextField(undefined, false, undefined));
}

// Generate new answer text field
function generateNewAnswerTextField() {
    return generateAnswerTextField(undefined, undefined, undefined);
}

// Generate a tab list
function generateTablist(levels) {
    // Set the initial value to true
    var firstElement = true;

    // Append each level to the tab list
    $.each(levels, function (key, value) {
        // Specify the content for the template
        var content = {
            id: 'level' + value['id'],
            firstElement: firstElement,
            name: value['name']
        };

        // Fill the template with the specified content
        var listItem = Mark.up(templates['Tab'], content);

        // Append it to the tab list
        $('#tablist').append(listItem);

        // Set the value to false, so that only the first time it is true
        firstElement = false;
    });
}

// Retrieve all questions
function getQuestions() {
    // Send a request to the api using the 'GetAllQuestions' command
    api("GetAllQuestions", function(data) {
        // Fill the tab panels with the received questions
        fillLevelTabWithQuestions(data);
    });
}

// Load levels for comboBox
function loadQuestionLevel(levelId) {
    // Send a request to the api using the 'GetAllLevels' command
    api("GetAllLevels", function(data) {
        // Append an 'option' tag for each level
        $.each(data, function (key, value) {
            $('#questionLevel').append($("<option></option>").attr("value", value["id"]).text(value["name"]));
        });

        // Pre-select the specified element
        $('#questionLevel option').eq(levelId - 1).attr('selected', '');
    });
}

// Load all the types into the dropdown menu when adding a new question
function loadQuestionType(typeId) {
    // Send a request to the api using the 'GetAllLevels' command
    api("GetAllTypes", function(data) {
        // Append an 'option' tag for each type
        $.each(data, function (key, value) {
            $('#questionType').append($("<option></option>").attr("value", value["id"]).text(value["name"]));
        });

        // Pre-select the specified element
        $('#questionType option').eq(typeId - 1).attr('selected', '');
    });
}

// Remove answer text field.
function removeAnswerTextField(sender) {
    // Get the current count of answer text fields
    var count = $('.antwoord').length;

    // If the current count is '0', then show an error and return
    if (count === 1) {
        createErrorMessage('Er is minimaal een antwoord verplicht');
        return;
    }

    // Remove the answer text-field
    $(sender).closest('.input-group').remove();

    // Enable the 'add answer' button
    $('.addAnswerToForm').prop('disabled', false);
}