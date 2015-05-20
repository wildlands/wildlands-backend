function addPinpoint()
{
    if(!validateForm())
    {
        return;
    }
    //arraylist voor pages
    var pages = [];

    var i = 0;

    $.each(CKEDITOR.instances, function(index, value){
        var page = {
            "levelId": $('#' + index).closest('div .tab-pane').attr('id'),
            "title": $('.paginas .page-title').eq(i).val(),
            "pageimage": $('.paginas .page-image').eq(i).val(),
            "text": value.getData()
        };
        pages.push(page);
        i++;
    });

    var parameter = {
        "name": $("#name").val(),
        "xPos": $("#xPos").text(),
        "yPos": $("#yPos").text(),
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

function fillEditPinpointFormWithData(pinpointId) {
    var parameter = {
        "id": pinpointId
    };

    api("GetPinpointById", parameter, function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }

        $('#name').val(data.name);
        $('#xPos').text(data.xPos);
        $('#yPos').text(data.yPos);
        $('#description').val(data.description);
        loadPinpointType(data.type.id);

        $('#spot').css('left', ((data.xPos / $('#myImgId').attr('data-scale')) + $('#myImgId')[0].offsetLeft - 12) + 'px');
        $('#spot').css('top', ((data.yPos / $('#myImgId').attr('data-scale')) + $('#myImgId')[0].offsetTop - 12) + 'px');

        if ($('#spot').css('display') === "none") {
            $('#spot').css('display', 'inline');
        }
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
    var str = "";
    if(pinpoint.description.length > 80 ) {
        var str = "...";
    }
    var row = "<tr id='" + pinpoint.id + "' class='pinpointRow'>";
    row += "<td>" + pinpoint.id + "</td>";
    row += "<td>" + pinpoint.name + "</td>";
    row += "<td>" + pinpoint.type.name + "</td>";
    row += "<td>" + pinpoint.description.substr(0, 80) + str + "</td>";
    row += "<td>" + "<a href='../edit/" + pinpoint.id + "' class='btn btn-warning pull-right'><i class='fa fa-pencil'></i></a>" + "</td>";
    row += "<td>" + "<a class='btn btn-danger pull-right' pinpointid='" + pinpoint.id + "' onclick='javascript: deletePinpoint(this);'><i class='fa fa-times'></i></a>" + "</td>";
    row += "</tr>";
    $("#pinpointsTable").append(row);
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
    var parameter = {
        "id": pinpointId,
        "name": $('#name').val(),
        "xPos": $('#xPos').val(),
        "yPos": $('#yPos').val(),
        "description": $('#description').val(),
        "typeId": $('#pinpointType').val()
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