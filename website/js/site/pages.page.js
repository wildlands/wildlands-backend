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
    var editor = 'editor' + (count + 1) + '-' + (tabIndex);

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
    var tableRow = Mark.up(templates['PageRow'], page);
    $('#pagesTable').append(tableRow);
}

function generatePageField(number, tabNumber) {
    var fileManager = createFileManagerModal(number, tabNumber);

    var content = {
        number: number,
        fileManager: fileManager,
        fileManagerId: 'fileManager' + number + '-' + tabNumber,
        textFieldId: 'image' + number + '-' + tabNumber,
        editorId: 'editor' + number + '-' + tabNumber
    };
    var pageField = Mark.up(templates['PageField'], content);

    console.log("Generated PageField");

    return pageField;
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

function generateTabPane(levels) {
    var firstElement = true;
    $.each(levels, function(key, value) {
        var tabNumber = value['id'];

        var pageField = generatePageField(1, tabNumber);
        var addPageFormButton = Mark.up(templates['AddPageFormButton']);

        var pageDivContent = {
            content: pageField
        };
        var pageDiv = Mark.up(templates['PageDiv'], pageDivContent);

        var tabPanelContent = {
            content: pageDiv + addPageFormButton,
            firstElement: firstElement,
            id: 'level' + value['id'],
            levelId: value['id']
        };
        var tabPanel = Mark.up(templates['TabPanel'], tabPanelContent);
        $('.tab-content').append(tabPanel);

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
        generateTabPane(data);
        replaceEditors();
    });
}

function replaceEditors() {
    $('.editor').each(function(element) {
        CKEDITOR.replace($(this).attr('id'));
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