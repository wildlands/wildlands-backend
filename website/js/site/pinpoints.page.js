function addPinpoint()
{
    if(!validateForm())
    {
        return;
    }
    //arraylist voor pages
    var pages = [];

    $.each(CKEDITOR.instances, function(index, value){
        var page = {
            "levelId": $('#' + index).closest('div .tab-pane').attr('levelId'),
            "title": $('.page-title', $('#' + index).closest('.pagina')).val(),
            "pageimage": $('.page-image', $('#' + index).closest('.pagina')).val(),
            "text": value.getData()
        };
        pages.push(page);
    });

    var parameter = {
        "name": $("#name").val(),
        "xPos": $("#xPos").val(),
        "yPos": $("#yPos").val(),
        "description": $("#description").val(),
        "typeId": $("#pinpointType").val(),
        "pages": pages
    };

    api("SetPinpoint", parameter, function(data) {
        redirectTo(base_url + "pinpoints/show/");
    }, function(data) {
        console.log(data);
    });

}

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
    var editor = 'editor' + (count + 1) + '_' + (tabIndex);

    if (count === 3) {
        $(sender).prop('disabled', true);
    }

    $('.paginas .pagina:last-child', closestDiv).after(generatePageField(count + 1, tabIndex));

    CKEDITOR.replace(editor);
}

function deletePinpointAjax(sender)
{
    var rows = [];
    
    $('.pagePinId').each(function(index, element) {
        if($(element).text() == $(sender).attr('pinpointid')) {
            rows.push($(element).closest('tr'));
        }
    });
    
    rows.push($(sender).closest('tr'));

    var parameter = {
        "id": $(sender).attr('pinpointid')
    };
    
    api("DeletePinpoint", parameter, function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }

        createSuccessMessage(data.success);
        for(var i = 0; i < rows.length; i++) {
            $(rows[i]).animate({
                backgroundColor: '#FF8585'
            }, 1000, function () {
                $(this).fadeOut(1000);
            });
        }
    }, function(data) {
        createErrorMessage(data.error);
    });
}

// Delete a pinpoint
function deletePinpoint(sender) {

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

function fillEditPageFormWithData(pages) {
    counter = [];
    pages.forEach(function(page) {

        if (counter['' + page.level.id] === undefined) {
            counter['' + page.level.id] = 1;
        } else {
            addPageFieldToForm($('#level' + page.level.id + '>button'));
        }

        var id = counter['' + page.level.id] + '_' + page.level.id;

        $('#title' + id).closest('div.pagina').attr('pageId', page.id);

        $('#title' + id).val(page.title);
        $('#image' + id).val(page.image);
        $('#editor' + id).val(page.text);

        counter['' + page.level.id]++;
    });
}

function fillEditPinpointFormWithData(pinpointId) {
    api("GetAllLevels", function(levelData) {
        generateTablist(levelData);
        generateTabPane(levelData);
        replaceEditors();

        var parameter = {
            "id": pinpointId
        };
        api("GetPinpointById", parameter, function(data) {
            if (data.error) {
                createErrorMessage(data.error);
                return;
            }

            $('#name').val(data.name);
            $('#xPos').val(data.xPos);
            $('#yPos').val(data.yPos);
            $('#description').val(data.description);
            loadPinpointType(data.type.id);

            fillEditPageFormWithData(data.pages);

            $('#spot').css('left', ((data.xPos / $('#myImgId').attr('data-scale')) + $('#myImgId')[0].offsetLeft - 12) + 'px');
            $('#spot').css('top', ((data.yPos / $('#myImgId').attr('data-scale')) + $('#myImgId')[0].offsetTop - 12) + 'px');

            if ($('#spot').css('display') === "none") {
                $('#spot').css('display', 'inline');
            }
        });
    });
}

// The table will be filled with the retrieved pinpoints
function fillPinpointTable(pinpoints) {
    console.log(pinpoints.length);
    for (var i = 0; i < pinpoints.length; i++) {
        fillPinpointRow(pinpoints[i]);
    }
}

// Generate pinpoint row and append it to '#pinpointsTable'
function fillPinpointRow(pinpoint) {
    if (pinpoint.description.length > 60) {
        pinpoint.description = pinpoint.description.substr(0, 60) + "...";
    }

    var tableRow = Mark.up(templates['PinpointRow'], pinpoint);
    $('#pinpointsTable').append(tableRow);
}

function generatePageField(number, tabNumber) {
    var fileManager = createFileManagerModal(number, tabNumber);

    var content = {
        number: number,
        fileManager: fileManager,
        fileManagerId: 'fileManager' + number + '_' + tabNumber,
        textFieldId: 'image' + number + '_' + tabNumber,
        titleId: 'title' + number + '_' + tabNumber,
        editorId: 'editor' + number + '_' + tabNumber
    };
    var pageField = Mark.up(templates['PageField'], content);

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

// Retrieve the pinpoints from the database
function getPinpoints() {
    api("GetAllPinpoints", function (data) {
        fillPinpointTable(data);
    }, function (data) {
        console.log(data);
    });
}

// Load all the types into the dropdown menu when adding a new pinpoint
function loadPinpointType(typeId) {
    api("GetAllTypes", function(data) {
        $.each(data, function (key, value) {
            $('#pinpointType').append($("<option></option>").attr("value", value["id"]).text(value["name"]));
        });

        $('#pinpointType option').eq(typeId - 1).attr('selected', '');
    });
}

function removePageFieldFromForm(sender) {
    var count = $('.tab-pane.active .pagina').length;

    if (count === 1) {
        createErrorMessage('Er is minimaal een pagina verplicht.');
        return;
    }

    CKEDITOR.instances[$(sender).closest('.pagina').find('textarea.editor').attr('id')].destroy();
    $(sender).closest('.form-group').remove();

    $.each($('.tab-pane.active .form-group'), function (key, value) {
        $(this).find('small').text('Pagina ' + (key + 1));
    });

    $('.addPage').prop('disabled', false);
}

function replaceEditors() {
    $('.editor').each(function(element) {
        CKEDITOR.replace($(this).attr('id'));
    });
}

function setCoordinates(event) {
    $('#xPos').val((event.pageX - this.x) * $(this).attr('data-scale'));
    $('#yPos').val((event.pageY - this.y) * $(this).attr('data-scale'));

    $('#spot').css('left', (event.pageX - this.x + this.offsetLeft - 12) + 'px');
    $('#spot').css('top', (event.pageY - this.y + this.offsetTop - 12) + 'px');

    if ($('#spot').css('display') == "none") {
        $('#spot').css('display', 'inline');
    }
}

function updatePinpoint(pinpointId) {
    if(!validateForm())
    {
        return;
    }
    
    var pages = [];

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
    
    var parameter = {
        "id": pinpointId,
        "name": $('#name').val(),
        "xPos": $('#xPos').val(),
        "yPos": $('#yPos').val(),
        "description": $('#description').val(),
        "typeId": $('#pinpointType').val(),
        "pages": pages
    };

    api("SetPinpoint", parameter, function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }

        redirectTo(base_url + "pinpoints/show/");
        createSuccessMessage(data.success);
    }, function(data) {
        console.log(data);
        createErrorMessage(data.error);
    });
}