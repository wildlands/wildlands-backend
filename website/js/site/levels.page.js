// Add an user to the database.
function addLevel()
{
    if(!validateForm())
    {
        return;
    }
    
    var parameter = {
        "name": $("#name").val()
    }

    api("SetLevel", parameter, function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }
        redirectTo(base_url + "levels/show/");
    }, function(data) {
        createErrorMessage(data.error);
    });
}

// Delete an user (confirmation box).
function deleteLevel(sender) {

    bootbox.dialog({
        message: "Wilt u dit niveau zeker weten verwijderen? Hiermee worden tevens alle bijbehorende pagina's en vragen verwijderd.",
        title: "Niveau verwijderen",
        buttons: {
            success: {
                label: "Ja",
                className: "btn-success",
                callback: function() {
                    deleteLevelAjax(sender);
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

// Delete an user.
function deleteLevelAjax(sender) {
    var tableRow = $(sender).closest('tr');

    var parameter = {
        "id": $(sender).attr('levelId')
    }

    api("DeleteLevel", parameter, function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }

        createSuccessMessage(data.success);
        $(tableRow).animate({
            backgroundColor: '#FF8585'
        }, 1000, function () {
            $(tableRow).fadeOut(1000);
        });
    }, function(data) {
        createErrorMessage(data.error);
    });
}

// Updates an user.
function editLevel(levelId) {
    var parameter = {
        "id": levelId,
        "name": $('#name').val()
    }

    api("SetLevel", parameter, function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }

        redirectTo(base_url + "levels/show/");
        createSuccessMessage(data.success);
    }, function(data) {
        createErrorMessage(data.error);
    });
}

// Fill the 'Edit user' form with data specified by the user id.
function fillEditLevelFormWithData(levelId) {
    var parameter = {
        "id": levelId
    }

    api("GetLevelById", parameter, function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }

        $('#levelId').val(data.id);
        $('#name').val(data.name);
    }, function(data) {
        createErrorMessage(data.error);
    });
}

// Fill the user table with the specified users.
function fillLevelTable(levels) {
    for (var i = 0; i < levels.length; i++) {
        fillLevelRow(levels[i]);
    }
}

// Generate row filled with user data and append it to '#usersTable'
function fillLevelRow(level) {
    var tableRow = Mark.up(templates['LevelRow'], level);
    $("#levelsTable tbody").append(tableRow);
}

// Retrieve all users.
function getLevels() {
    api("GetAllLevels", function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }
        fillLevelTable(data);
    }, function(data) {
        createErrorMessage(data.error);
    });
}