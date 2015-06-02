// Add a level to the database.
function addLevel() {
    // Validate the form and return if it isn't valid
    if(!validateForm())
    {
        return;
    }

    // Get the name specified in the form
    var parameter = {
        "name": $("#name").val()
    };

    // Send a request to the api using the 'SetLevel' command
    api("SetLevel", parameter, function() {
        // Redirect to the levels overview
        redirectTo(base_url + "levels/show/");
    });
}

// Delete a level (confirmation box).
function deleteLevel(sender) {
    // Show a confirmation box for deleting an level
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
                    var del = false;
                }
            }
        }
    });
}

// Delete a level.
function deleteLevelAjax(sender) {
    // Get the next tableRow which represents the level row
    var tableRow = $(sender).closest('tr');

    // Set the id as the parameter
    var parameter = {
        "id": $(sender).attr('levelId')
    };

    // Send a request to the api using the 'DeleteLevel' command
    api("DeleteLevel", parameter, function(data) {
        // Show success message and fade out the corresponding table row
        createSuccessMessage(data.success);
        $(tableRow).animate({
            backgroundColor: '#FF8585'
        }, 1000, function () {
            $(tableRow).fadeOut(1000);
        });
    });
}

// Edit a level.
function editLevel(levelId) {
    // Validate the form and return if it isn't valid
    if(!validateForm())
    {
        return;
    }
    // Set the id and the name of the level as the parameter
    var parameter = {
        "id": levelId,
        "name": $('#name').val()
    };

    // Send a request to the api using the 'SetLevel' command
    api("SetLevel", parameter, function(data) {
        // Redirect to the levels overview
        redirectTo(base_url + "levels/show/");
    });
}

// Fill the 'Edit level' form with data specified by the level id.
function fillEditLevelFormWithData(levelId) {
    // Set the id as the parameter
    var parameter = {
        "id": levelId
    };

    // Send a request to the api using the 'SetLevel' command
    api("GetLevelById", parameter, function(data) {
        // Set both fields with the received data
        $('#levelId').val(data.id);
        $('#name').val(data.name);
    });
}

// Fill the level table with the specified levels.
function fillLevelTable(levels) {
    // For every level, generate and append a new level row with
    // the specified data
    for (var i = 0; i < levels.length; i++) {
        fillLevelRow(levels[i]);
    }
}

// Generate row filled with level data and append it to '#levelsTable'
function fillLevelRow(level) {
    // Get and fill the template 'LevelRow' and append it to the table
    var tableRow = Mark.up(templates['LevelRow'], level);
    $("#levelsTable tbody").append(tableRow);
}

// Retrieve all levels.
function getLevels() {
    // Send a request to the api using the 'GetAllLevels' command
    api("GetAllLevels", function(data) {
        // Fill the table with the received data
        fillLevelTable(data);
    });
}