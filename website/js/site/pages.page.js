// Add a page to the form.
function addPageFieldToForm(sender) {
    
    var closestDiv = $(sender).closest('div');
    var tabIndex;
    
    $('.tab-pane').each(function(index) {
        if($(this).attr('id') == $(closestDiv).attr('id')) {
            tabIndex = index + 1;
        }
    });
    
    var count = $('.pagina', closestDiv).length;
    var editor = 'editor' + (count + 1) + (tabIndex);

    if (count === 3) {
        $(sender).prop('disabled', true);
    }

    $('.paginas .pagina:last-child', closestDiv).after(generatePageField(count + 1, tabIndex));

    CKEDITOR.replace(editor);
}

function deletePageAjax(sender)
{
    var tableRow = $(sender).closest('tr');
    
    var parameter = {
        "id": $(sender).attr('pageid')
    };

    api("DeletePage", parameter, function(data) {
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

// Delete a page
function deletePage(sender) {
    
    bootbox.dialog({
        message: "Wilt u deze page zeker weten verwijderen?",
        title: "Page verwijderen",
        buttons: {
          success: {
            label: "Ja",
            className: "btn-success",
            callback: function() {
              deletePageAjax(sender);
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

function fillEditPageFormWithData(pageId) {
    var parameter = {
        "id": pageId
    }

    api("GetPageById", parameter, function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }

        $('#pinId').val(data.pinpointId);
        $('#title').val(data.title);
        $('#image').val(data.image);
        $('#editor').val(data.text);
    });
}

//the table will be filled with the retrieved pinpoints
function fillPageTable(pages) {
    for (var i = 0; i < pages.length; i++) {
        fillPageRow(pages[i]);
    }
}

// Fill page row
function fillPageRow(page) {

    var row = "<tr id='" + page.id + "' class='pageRow'>";
    row += "<td>" + page.id + "</td>";
    row += "<td>" + page.title + "</td>";
    row += "<td class='pagePinId'>" + page.pinpointId + "</td>";
    row += "<td>" + page.text + "</td>";
    row += "<td>" + "<a href='../../page/edit/" + page.id + "' class='btn btn-warning pull-right'><i class='fa fa-pencil'></i></a>" + "</td>";
    row += "<td>" + "<a class='btn btn-danger pull-right' pageid='" + page.id + "' onclick='javascript: deletePage(this);'><i class='fa fa-times'></i></a>" + "</td>";
    row += "</tr>";
    $("#pagesTable").append(row);
}

function generatePageField(number, numbertabs) {

    return '<div class="form-group pagina">' +
        '<h1><small> Pagina '+ number +'</small></h1><a class="removePage"><i class="fa fa-trash-o"></i></a>' +
        '<br>' +
        '<label>Titel</label><input class="form-control page-title" type="text"/>' +
        '<br>' +
        '<label>Afbeelding</label>' +
        '<div class="input-group">' +
        '<input class="form-control page-image" type="text" id="image' + number + numbertabs + '" readonly/>' +
        '<div class="input-group-addon">' +
        '<a href="javascript:void(0);" data-toggle="modal" data-target="#myModal'+ number + numbertabs +'">Kies afbeelding</a>' +
        '</div>' +
        '</div>' +
        '<br>' +
        '<label>Tekst</label><textarea id="editor'+ number + numbertabs + '" name="editor'+ number + numbertabs +'"></textarea>' +
        '<hr>' +
        createFileManagerModal(number) +
        '</div>';

}

// Retrieve the pages from the database
function getPages() {
    api("GetAllPages", function(data) {
        fillPageTable(data);
    }, function(data) {
        console.log(data);
    });
}

function removePageFieldFromForm(sender) {
    var count = $('.pagina').length;

    if (count === 1) {
        createErrorMessage('Er is minimaal een pagina verplicht.');
        return;
    }

    $(sender).closest('.form-group').remove();

    $.each($('.form-group'), function (key, value) {

        $(sender).find('small').text('Pagina ' + key);
        $(sender).find('textarea').attr('id', 'editor' + key);
        $(sender).find('textarea').attr('name', 'editor' + key);

    });

    $('.addPage').prop('disabled', false);
}

function updatePage(pageId) {
    var parameter = {
        "id": pageId,
        "pinpointId": $('#pinId').val(),
        "title": $('#title').val(),
        "image": $('#image').val(),
        "text": CKEDITOR.instances.editor.getData()
    }

    api("SetPage", parameter, function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }

        redirectTo(base_url + "pinpoints/show/");
        createSuccessMessage(data.success);
    }, function(data) {
        createErrorMessage(data.error);
    })
}