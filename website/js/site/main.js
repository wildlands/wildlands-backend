/**
 * Project: Wildlands Education App
 * Author: INF2A (02/2015 - 06/2015)- Stenden University of Applied Sciences
 *
 * Project members:
 *  Jan Doornbos
 *  Stefan Koopman
 *  Martin Schoonbeek
 *  Arwin Strating
 *  Jahdah Thelen
 *  Maaike Veurink
 *  Maximilian Wiedemann
 */

// Global variables
var templates;

// Event handler for jQuery event 'Document.ready'
$(document).ready(function () {
    var height = $('body').height();
    $('.background-image').height(height + 75);

    // Set events for adjusting the sidebar height
    new ResizeSensor($('.content'), function() {
        readjustHeight();
    });

    setTimeout(function() {
        readjustHeight();
    }, 500);
});

// Call the api with the specified command and parameter
function api(command, parameter, doneCallback) {
    // Set the command
    var data = {
        c: command
    }

    // If there is no 'parameter' parameter given, then shift
    // the parameters
    if (typeof parameter == "function") {
        doneCallback = parameter;
        parameter = null;
    } else {
        data.p = JSON.stringify(parameter);
    }

    // Send the ajax call with the specified data
    $.ajax({
        url: ajax_url + 'api/api.php',
        method: 'post',
        data: data,
        cache: false
    }).done(function (data) {
        // If there was an error, show it and log the whole
        // object to the console. Then abort.
        if (data.error) {
            createErrorMessage('API: ' + data.error);
            console.error('The api had an error.\nReceived object:');
            console.log(data);
            return;
        }
        // Otherwise call the callback function
        doneCallback(data);
    }).fail(function (data) {
        // Show a generic error message
        createErrorMessage('Er is iets fout gegaan met de API php script.');
        // Log everything to console
        console.error('There was an error while calling the api.\n' +
                    'Received object:');
        console.log(data);
    });
}

// Create error message (bootstrap).
function createErrorMessage(errorMessage) {
    $.notify({
        icon: 'fa fa-exclamation-triangle',
        message: errorMessage
    }, {
        type: 'danger'
    });
}

// Create file manager modal
function createFileManagerModal(number, tabNumber) {
    var content = {
        id: 'fileManager' + number + '_' + tabNumber,
        textFieldId: 'image' + number + '_' + tabNumber,
        base_url: base_url
    };

    var fileManager = Mark.up(templates['FileManager'], content);
    return fileManager;
}

// Create success message (bootstrap).
function createSuccessMessage(successMessage) {
    $.notify({
        icon: 'fa fa-check',
        message: successMessage
    }, {
        type: 'success'
    });
}

// Redirect to another page.
function redirectTo(pageLink) {
    window.location.replace(pageLink);
}

// Callback function for the filemanager.
function responsive_filemanager_callback() {
    $('.modal').modal('hide');
}

// Readjust the height of the sidebar according to the content height.
function readjustHeight() {
    var contentHeight = $('.content').outerHeight();
    $('.menu').css('min-height', contentHeight+'px');
}

// Load all templates for Markup.js
function loadTemplates() {
    templates = {};

    var text = $('#templates').text();
    $('#templates').remove();
    var chunks = text.split("=====").splice(1);
    var i, key;

    chunks.forEach(function (chunk) {
        i = chunk.indexOf("\n");
        key = chunk.substr(0, i).trim();
        templates[key] = chunk.substr(i).trim();
    });

    console.log("Templates loaded.");
}

// Validate the form
function validateForm() {
    // Specify the elements that are going to checked on validity
    var elementsToBeChecked = 'input:not(.optional), select';

    var errorMessage = "";
    var valid = true;

    // Remove all 'has-error' classes to reset the site error state
    $(elementsToBeChecked).closest('.input-group, .form-group').removeClass('has-error');

    // Check every element
    $(elementsToBeChecked).each(function(index) {
        var field = $(this).val()
        // If the element is empty or attempt to inject tags, do nothing but return
        if (field !== "" && !(field.indexOf("<") > -1)) {
            return;
        }

        // If the class is 'antwoord' (answer), add the 'has-error'
        // class to the input-group, otherwise to the form-group
        if ($(this).hasClass('antwoord')) {
            $(this).closest('.input-group').addClass('has-error');
        } else {
            $(this).closest('.form-group').addClass('has-error');
        }

        // Get the label of the invalid element
        var name = $(this).closest('.form-group').find('label').text();
        var tab = "";
        if ($(this).closest('.paginas').length > 0) {
            var tabId = $(this).closest('.tab-pane').attr('id');
            var tabName = $('#tablist').find('a[aria-controls|="' + tabId + '"]').text();
            var tab = " (" + tabName + ")";
        }
        errorMessage += name + tab + " niet ingevuld <br>";

        // Set validity variable to false
        valid = false;
    });

    // If the form isn't valid, then create and display an error message
    if(!valid) {
        createErrorMessage(errorMessage);
    }

    // Return the result
    return valid;
}