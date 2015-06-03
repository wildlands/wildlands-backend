// Edit a layer.
function editLayer(layerId) {
    // Set the id and the name of the level as the parameter
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

// Fill the 'Edit level' form with data specified by the level id.
function fillEditLayerFormWithData(layerId) {
    // Set the id as the parameter
    var parameter = {
        "id": layerId
    };

    // Send a request to the api using the 'SetLevel' command
    api("GetLayerById", parameter, function(data) {
        // Set both fields with the received data
        $('#layerId').val(data.id);
        $('#typeId').val(data.type.id);
        $('#image').val(data.image);
    });
}

// Fill the level table with the specified levels.
function fillLayerTable(layers) {
    // For every layer, generate and append a new layer row with
    // the specified data
    for (var i = 0; i < layers.length; i++) {
        fillLayerRow(layers[i]);
    }
}

// Generate row filled with layer data and append it to '#levelsTable'
function fillLayerRow(layer) {
    // Get and fill the template 'LevelRow' and append it to the table
    var tableRow = Mark.up(templates['LayerRow'], layer);
    $("#layersTable tbody").append(tableRow);
}

// Retrieve all levels.
function getLayers() {
    // Send a request to the api using the 'GetAllLayers' command
    api("GetAllLayers", function(data) {
        // Fill the table with the received data
        fillLayerTable(data);
    });
}