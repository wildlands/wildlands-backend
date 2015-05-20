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
    };

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
        '<h1><small> Pagina '+ number +'</small><a onclick="javascript: removePageFieldFromForm(this);" class="btn btn-danger pull-right"><i class="fa fa-trash-o"></i></a></h1>' +
        '<br>' +
        '<div class="form-group">' +
        '<label>Titel</label><input class="form-control page-title" type="text"/>' +
        '</div>' +
        '<br>' +
        '<div class="form-group">' +
        '<label>Afbeelding</label>' +
        '<div class="input-group">' +
        '<input class="form-control page-image" type="text" id="image' + number + numbertabs + '" readonly/>' +
        '<div class="input-group-addon">' +
        '<a href="javascript:void(0);" data-toggle="modal" data-target="#myModal'+ number + numbertabs +'">Kies afbeelding</a>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<br>' +
        '<div class="form-group">' +
        '<label>Tekst</label><textarea id="editor'+ number + numbertabs + '" name="editor'+ number + numbertabs +'"></textarea>' +
        '<hr>' +
        createFileManagerModal(number) +
        '</div>' +
        '</div>';

}

function generateTablist(levels) {
    var firstElement = true;
    $.each(levels, function (key, value) {
        var id = value['id'];
        var element = '<li role="presentation"' + (firstElement ? ' class="active"' : '') + '><a href="#' + id + '" aria-controls="' + id + '" role="tab" data-toggle="tab">' + value['name'] + '</a></li>';
        $('#tablist').append(element);
        firstElement = false;
    });
}

function generateTabPane(levels) {
    var firstElement = true;
    $.each(levels, function (key, value) {
        var element = '<div role="tabpanel" class="tab-pane' + (firstElement ? ' active"' : '"') +' id="' + value['id'] + '"><div class="paginas"><div class="form-group pagina"><h1><small> Pagina 1</small><a onclick="javascript: removePageFieldFromForm(this);" class="btn btn-danger pull-right"><i class="fa fa-trash-o"></i></a></h1><br><div class="form-group"><label>Titel</label><input class="form-control page-title" type="text"/></div><br><div class="form-group"><label>Afbeelding</label><div class="input-group"><input class="form-control page-image" type="text" id="image1' + value['id'] + '" readonly/><div class="input-group-addon"><a data-toggle="modal" data-target="#myModal1' + value['id'] + '">Kies afbeelding</a></div></div><div class="fileManagerModal"><script>$(".fileManagerModal").append(createFileManagerModal(1));</script></div></div><br><div class="form-group"><label>Tekst</label><textarea id="editor1' + value['id'] + '" name="editor1' + value['id'] + '"></textarea><script type="text/javascript">CKEDITOR.replace("editor1' + value['id'] + '");</script><hr></div></div></div><button class="btn btn-default" type="button" onclick="javascript: addPageFieldToForm(this);">Pagina toevoegen</button><div class="form-group"></div></div>';
        $('.tab-content').append(element);
        firstElement = false;
    });
}

// Retrieve the pages from the database
function getPages() {
    api("GetAllPages", function(data) {
        fillPageTable(data);
    }, function(data) {
        console.log(data);
    });
}

function loadPageLevel() {
    api("GetAllLevels", function(data) {
        generateTablist(data);
    });
}

function loadPageLevelPane() {
    api("GetAllLevels", function(data) {
        generateTabPane(data);
    });
}

function removePageFieldFromForm(sender) {
    var count = $('.tab-pane.active .pagina').length;

    if (count === 1) {
        createErrorMessage('Er is minimaal een pagina verplicht.');
        return;
    }

    $(sender).closest('.form-group').remove();

    $.each($('.tab-pane.active .form-group'), function (key, value) {
        //var parent = $(sender).parent('.form-group');
        $(this).find('small').text('Pagina ' + (key + 1));
        $(this).find('textarea').attr('id', 'editor' + (key + 1));
        $(this).find('textarea').attr('name', 'editor' + (key + 1));

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
    };

    api("SetPage", parameter, function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }

        redirectTo(base_url + "pinpoints/show/");
        createSuccessMessage(data.success);
    }, function(data) {
        createErrorMessage(data.error);
    });
}