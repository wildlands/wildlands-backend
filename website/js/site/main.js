var templates;

$(document).ready(function () {
    var height = $('body').height();
    $('.background-image').height(height + 75);

    new ResizeSensor($('.content'), function() {
        readjustHeight();
    });

    setTimeout(function() {
        readjustHeight();
    }, 500);
});

function api(command, parameter, doneCallback, failCallback) {
    var data = {
        c: command
    }

    if (typeof parameter == "function") {
        failCallback = doneCallback;
        doneCallback = parameter;
        parameter = null;
    } else {
        data.p = JSON.stringify(parameter);
    }

    $.ajax({
        url: ajax_url + 'api/api.php',
        method: 'post',
        data: data,
        cache: false
    }).done(function (data) {
        doneCallback(data);
    }).fail(function (data) {
        failCallback(data);
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

function readjustHeight() {
    var contentHeight = $('.content').outerHeight();
    $('.menu').css('min-height', contentHeight+'px');
}

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

function validateForm() {
    var elementsToBeChecked = 'input, select';

    var string = "";
    var valid = true;

    $(elementsToBeChecked).closest('.input-group, .form-group').removeClass('has-error');
    $(elementsToBeChecked).each(function(index) {
        if ($(this).val() !== "") {
            return;
        }
        if ($(this).hasClass('antwoord')) {
            $(this).closest('.input-group').addClass('has-error');
        } else {
            $(this).closest('.form-group').addClass('has-error');
        }


        var name = $(this).closest('.form-group').find('label').text();
        var tab = "";
        if ($(this).closest('.paginas').length > 0) {
            var tabId = $(this).closest('.tab-pane').attr('id');
            var tabName = $('#tablist').find('a[aria-controls|="' + tabId + '"]').text();
            var tab = " (" + tabName + ")";
        }
        string += name + tab + " niet ingevuld <br>";

        valid = false;
    });

    if(!valid) {
        createErrorMessage(string);
    }
        
    return valid;
}
