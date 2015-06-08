// Edit a layer.
function editLayer(layerId) {
    // Set the id and the name of the layer as the parameter
    var parameter = {
        "id": layerId,
        "typeId": $('#typeId').val(),
        "image": $('#image').val()
    };

    // Send a request to the api using the 'SetLayer' command
    api("SetLayer", parameter, function(data) {
        // Redirect to the levels overview
        redirectTo(base_url + "layers/show/");
    });
}

// Fill the 'Edit layer' form with data specified by the layer id.
function fillEditLayerFormWithData(layerId) {
    // Set the id as the parameter
    var parameter = {
        "id": layerId
    };

    // Send a request to the api using the 'SetLayer' command
    api("GetLayerById", parameter, function(data) {
        // Set both fields with the received data
        var image = '<img src="' + data.image + '" style="margin-left:auto;margin-right:auto;max-width:200px;max-height:250px;">';
        
        $('#layerId').val(data.id);
        $('#typeId').val(data.type.name);
        $('#image').val(data.image);
        $('#image').popover({placement: 'top', content: image, html: true});
    });
}

// Fill the layer table with the specified layers.
function fillLayerTable(layers) {
    // For every layer, generate and append a new layer row with
    // the specified data
    for (var i = 0; i < layers.length; i++) {
        fillLayerRow(layers[i]);
    }
}

// Generate row filled with layer data and append it to '#layersTable'
function fillLayerRow(layer) {
    // Get and fill the template 'LayerRow' and append it to the table
    var tableRow = Mark.up(templates['LayerRow'], layer);
    $("#layersTable tbody").append(tableRow);
}

// Retrieve all layers.
function getLayers() {
    // Send a request to the api using the 'GetAllLayers' command
    api("GetAllLayers", function(data) {
        // Fill the table with the received data
        fillLayerTable(data);
    });
}