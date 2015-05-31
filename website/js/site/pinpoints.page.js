// Add a pinpoint
function addPinpoint() {
    // Validate the form and return if it isn't valid
    if(!validateForm())
    {
        return;
    }

    //Array for collecting all pages
    var pages = [];

    // Getting the title, image and text for each page
    $.each(CKEDITOR.instances, function(index, value){
        var page = {
            "levelId": $('#' + index).closest('div .tab-pane').attr('levelId'),
            "title": $('.page-title', $('#' + index).closest('.pagina')).val(),
            "pageimage": $('.page-image', $('#' + index).closest('.pagina')).val(),
            "text": value.getData()
        };
        pages.push(page);
    });

    // Set all elements as the parameter
    var parameter = {
        "name": $("#name").val(),
        "xPos": $("#xPos").val(),
        "yPos": $("#yPos").val(),
        "description": $("#description").val(),
        "typeId": $("#pinpointType").val(),
        "pages": pages
    };

    // Send a request to the api using the 'SetLevel' command
    api("SetPinpoint", parameter, function(data) {
        // If there was an error, show it and abort
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }

        // Otherwise redirect to pinpoints overview
        redirectTo(base_url + "pinpoints/show/");
    }, function(data) {
        // If the ajax call fails, show the error
        createErrorMessage(data.error)
        console.log(data);
    });

}

// Add a pageField to the form
function addPageFieldToForm(sender) {

    // Get the closest div
    var closestDiv = $(sender).closest('div');
    var tabIndex;

    // Check every tabPane if the id is the one that is searched for
    $('.tab-pane').each(function(index) {
        if($(this).attr('id') == $(closestDiv).attr('id')) {
            tabIndex = index + 1;
        }
    });

    // Get the current count of pages
    var count = $('.pagina', closestDiv).length;

    // If the current count is 3, disable the add button
    if (count === 3) {
        $(sender).prop('disabled', true);
    }

    // Generate and add pageField
    $('.paginas .pagina:last-child', closestDiv).after(generatePageField(count + 1, tabIndex));

    // Replace editor
    var editor = 'editor' + (count + 1) + '_' + (tabIndex);
    CKEDITOR.replace(editor);
}

// Delete a pinpoint with ajax
function deletePinpointAjax(sender)
{
    // Get the next tableRow which represents the pinpoint row
    var tableRow = $(sender).closest('tr');

    // Set the id as the parameter
    var parameter = {
        "id": $(sender).attr('pinpointid')
    };

    // Send a request to the api using the 'DeletePinpoint' command
    api("DeletePinpoint", parameter, function(data) {
        // If there was an error, show it and abort
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }

        // Otherwise show a success message and fade out the table row
        createSuccessMessage(data.success);
        $(tableRow).animate({
            backgroundColor: '#FF8585'
        }, 1000, function () {
            $(this).fadeOut(1000);
        });
    }, function(data) {
        // If the ajax call fails, show the error
        createErrorMessage(data.error);
    });
}

// Delete a pinpoint (confirmation box)
function deletePinpoint(sender) {
    // Show a confirmation box for deleting an pinpoint
    bootbox.dialog({
        message: "Wilt u deze pinpoint zeker weten verwijderen? Hiermee worden tevens alle bijbehorende pagina's verwijderd.",
        title: "Pinpoint verwijderen",
        buttons: {
          success: {
            label: "Ja",
            className: "btn-success",
            callback: function() {
              deletePinpointAjax(sender);
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

// Edit a pinpoint with the data specified in the form
function editPinpoint(pinpointId) {
    // Validate the form and return if it isn't valid
    if(!validateForm())
    {
        return;
    }

    // Array for collecting all pages
    var pages = [];

    // Getting the title, image and text for each page
    $.each(CKEDITOR.instances, function(index, value){
        var page = {
            "id": $('#' + index).closest('.pagina').attr('pageId'),
            "levelId": $('#' + index).closest('div .tab-pane').attr('levelId'),
            "title": $('.page-title', $('#' + index).closest('.pagina')).val(),
            "pageimage": $('.page-image', $('#' + index).closest('.pagina')).val(),
            "text": value.getData()
        };
        pages.push(page);
    });

    // Set all elements as the parameter
    var parameter = {
        "id": pinpointId,
        "name": $('#name').val(),
        "xPos": $('#xPos').val(),
        "yPos": $('#yPos').val(),
        "description": $('#description').val(),
        "typeId": $('#pinpointType').val(),
        "pages": pages
    };

    // Send a request to the api using the 'SetPinpoint' command
    api("SetPinpoint", parameter, function(data) {
        // If there was an error, show it and abort
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }

        // Otherwise redirect to pinpoints overview
        redirectTo(base_url + "pinpoints/show/");
    }, function(data) {
        // If the ajax call fails, show the error
        createErrorMessage(data.error);
    });
}

// Fill the editPageForm with the specified data
function fillEditPageFormWithData(pages) {
    // Initializing counter
    var counter = [];

    // Fill every pageField with specified page data
    pages.forEach(function(page) {
        if (counter['' + page.level.id] === undefined) {
            // If it is the first page, just initialize the counter to 1
            counter['' + page.level.id] = 1;
        } else {
            // Otherwise add a new pageField
            addPageFieldToForm($('#level' + page.level.id + '>button'));
        }

        // Get the pageId
        var id = counter['' + page.level.id] + '_' + page.level.id;

        // Set the pageId
        $('#title' + id).closest('div.pagina').attr('pageId', page.id);

        // Set the data
        $('#title' + id).val(page.title);
        $('#image' + id).val(page.image);
        $('#editor' + id).val(page.text);

        // Increase the counter of pages in the specified level
        counter['' + page.level.id]++;
    });
}

// Fill the editPinpointForm with the specified data
function fillEditPinpointFormWithData(pinpointId) {
    // Send a request to the api using the 'SetLevel' command
    api("GetAllLevels", function(levelData) {
        generateTablist(levelData);
        generateTabPane(levelData);
        replaceEditors();

        // Set the id as the parameter
        var parameter = {
            "id": pinpointId
        };

        // Send a request to the api using the 'GetPinpointById' command
        api("GetPinpointById", parameter, function(data) {
            // If there was an error, show it and abort
            if (data.error) {
                createErrorMessage(data.error);
                return;
            }

            // Set the data
            $('#name').val(data.name);
            $('#xPos').val(data.xPos);
            $('#yPos').val(data.yPos);
            $('#description').val(data.description);
            loadPinpointType(data.type.id);
            fillEditPageFormWithData(data.pages);

            // Set the spot on the map
            $('#spot').css('left', ((data.xPos / $('#myImgId').attr('data-scale')) + $('#myImgId')[0].offsetLeft - 12) + 'px');
            $('#spot').css('top', ((data.yPos / $('#myImgId').attr('data-scale')) + $('#myImgId')[0].offsetTop - 12) + 'px');

            // If the spot is hidden, display it
            if ($('#spot').css('display') === "none") {
                $('#spot').css('display', 'inline');
            }
        });
    });
}

// The table will be filled with the retrieved pinpoints
function fillPinpointTable(pinpoints) {
    // Generate a pinpoint row for every pinpoint in the specified array
    for (var i = 0; i < pinpoints.length; i++) {
        fillPinpointRow(pinpoints[i]);
    }
}

// Generate pinpoint row and append it to '#pinpointsTable'
function fillPinpointRow(pinpoint) {
    // If the description is too long, cut it and append a '...'
    if (pinpoint.description.length > 60) {
        pinpoint.description = pinpoint.description.substr(0, 60) + "...";
    }

    // Fill the template and append it to the table
    var tableRow = Mark.up(templates['PinpointRow'], pinpoint);
    $('#pinpointsTable').append(tableRow);
}

// Generate a pageField with the given number and tabNumber
function generatePageField(number, tabNumber) {
    // Generate a modal for the file manager
    var fileManager = createFileManagerModal(number, tabNumber);

    // Set the content for the template
    var content = {
        number: number,
        fileManager: fileManager,
        fileManagerId: 'fileManager' + number + '_' + tabNumber,
        textFieldId: 'image' + number + '_' + tabNumber,
        titleId: 'title' + number + '_' + tabNumber,
        editorId: 'editor' + number + '_' + tabNumber
    };
    // Fill the template with the specified content
    var pageField = Mark.up(templates['PageField'], content);

    // Return the html
    return pageField;
}

// Generate the tablist with the specified level data
function generateTablist(levels) {
    // Set the initial value to true
    var firstElement = true;
    $.each(levels, function (key, value) {
        // Set the content for the template
        var content = {
            id: 'level' + value['id'],
            firstElement: firstElement,
            name: value['name']
        };
        // Fill the template with the specified content
        var listItem = Mark.up(templates['Tab'], content);

        // Append it to the tab list
        $('#tablist').append(listItem);

        // Set the value to false, so only the first time it is true
        firstElement = false;
    });
}

// Generate the tabPane (content of the tabs) with initial content
function generateTabPane(levels) {
    // Set the initial value to true
    var firstElement = true;

    $.each(levels, function(key, value) {
        // Get the tab number
        var tabNumber = value['id'];

        // Generate a pageField with number = 1 and the received tab number
        var pageField = generatePageField(1, tabNumber);

        // Get the template for an 'add page' button
        var addPageFormButton = Mark.up(templates['AddPageFormButton']);

        // Set the content for the pageDiv template
        var pageDivContent = {
            content: pageField
        };

        // Fill the PageDiv template with the specified content
        var pageDiv = Mark.up(templates['PageDiv'], pageDivContent);

        // Set the content for the tabPanel template
        var tabPanelContent = {
            content: pageDiv + addPageFormButton,
            firstElement: firstElement,
            id: 'level' + value['id'],
            levelId: value['id']
        };
        // Fill the tabPanel template with the specified content
        var tabPanel = Mark.up(templates['TabPanel'], tabPanelContent);

        // Append it to the 'tab content' div
        $('.tab-content').append(tabPanel);

        // Set the value to false, so only the first time it is true
        firstElement = false;
    });
}

// Retrieve the pinpoints from the database
function getPinpoints() {
    // Send a request to the api using the 'GetAllPinpoints' command
    api("GetAllPinpoints", function (data) {
        // If there was an error, show it and abort
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }

        // Fill the pinpoint table with the received data
        fillPinpointTable(data);
    }, function (data) {
        // If the ajax call fails, show the error
        createErrorMessage(data.error);
        console.log(data);
    });
}

// Load PageLevel
function loadPageLevel() {
    // Send a request to the api using the 'GetAllLevels' command
    api("GetAllLevels", function(levelData) {
        // Generate a tab list, tab panel and then replace all given editor textfields
        generateTablist(levelData);
        generateTabPane(levelData);
        replaceEditors();
    });
}

// Load all the types into the dropdown menu when adding a new pinpoint
function loadPinpointType(typeId) {
    // Send a request to the api using the 'GetAllTypes' command
    api("GetAllTypes", function(data) {
        // Append an 'option' element for each type
        $.each(data, function (key, value) {
            $('#pinpointType').append($("<option></option>").attr("value", value["id"]).text(value["name"]));
        });

        // Pre-select the specified element
        $('#pinpointType option').eq(typeId - 1).attr('selected', '');
    });
}

// Remove a pageField from the form defined by the sender
function removePageFieldFromForm(sender) {
    // Get the count of pages in the current level
    var count = $('.tab-pane.active .pagina').length;

    // If the current count is '1' then do nothing but throw an error message and return
    if (count === 1) {
        createErrorMessage('Er is minimaal een pagina verplicht.');
        return;
    }

    // Destroy the editor of the specified page and then remove it
    CKEDITOR.instances[$(sender).closest('.pagina').find('textarea.editor').attr('id')].destroy();
    $(sender).closest('.form-group').remove();

    // Rename the pages to have the appropriate number
    $.each($('.tab-pane.active .form-group'), function (key, value) {
        $(this).find('small').text('Pagina ' + (key + 1));
    });

    // Re-activate the 'add page' button
    $('.addPage').prop('disabled', false);
}

// Replace all textfields with class 'editor' with CKEDTIOR instances
function replaceEditors() {
    $('.editor').each(function(element) {
        CKEDITOR.replace($(this).attr('id'));
    });
}

// Set coordinates
function setCoordinates(event) {
    // Set the xPos and yPos text fields to the new values
    $('#xPos').val((event.pageX - this.x) * $(this).attr('data-scale'));
    $('#yPos').val((event.pageY - this.y) * $(this).attr('data-scale'));

    // Set the spot to the position the user clicked on
    $('#spot').css('left', (event.pageX - this.x + this.offsetLeft - 12) + 'px');
    $('#spot').css('top', (event.pageY - this.y + this.offsetTop - 12) + 'px');

    // If the spot was hidden, display it
    if ($('#spot').css('display') == "none") {
        $('#spot').css('display', 'inline');
    }
}