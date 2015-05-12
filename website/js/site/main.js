$(document).ready(function () {
    var height = $('body').height();
    $('.background-image').height(height + 75);
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
function createFileManagerModal(number) {

    var html = "<div class='modal fade' id='myModal" + number + "' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>";
    html += "<div class='modal-dialog'>";
    html += "<div class='modal-content'>";
    html += "<div class='modal-header'>";
    html += "<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
    html += "<h4 class='modal-title' id='myModalLabel'>Media Gallery</h4>";
    html += "</div>";
    html += "<div class='modal-body'>";
    html = html + "<iframe width='580' height='500' src='" + base_url + "filemanager/dialog.php?type=1&field_id=image" + number + "&fldr=' frameborder='0' style='overflow: scroll; overflow-x: hidden; overflow-y: hidden;'></iframe>";
    html += "</div>";
    html += "</div>";
    html += "</div>";
    html += "</div>";

    return html;
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