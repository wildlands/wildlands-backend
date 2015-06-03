// Add an user to the database
function addUser()
{
    // Validate the form and return if it isn't valid
    if(!validateForm())
    {
        return;
    }

    // Set the name, email and password as the parameter
    var parameter = {
        "name": $("#name").val(),
        "email": $("#email").val(),
        "password": $("#pass").val()
    };

    // Send a request to the api using the 'SetUser' command
    api("SetUser", parameter, function(data) {
        // Redirect to the users overview
        redirectTo(base_url + "users/show/");
    });
}

// Delete an user (confirmation box)
function deleteUser(sender) {
    // Show a confirmation box for deleting an user
    bootbox.dialog({
        title: "Gebruiker verwijderen?",
        message: '<div class="row">  ' +
                    '<div class="col-md-12"> ' +
                    '<form class="form-horizontal"> ' +
                    '<div class="form-group"> ' +
                    '<label class="col-md-4 control-label" for="name">Wachtwoord</label> ' +
                    '<div class="col-md-4"> ' +
                    '<input id="password" name="password" type="text" class="form-control input-md"> ' +
                    '</div> ' +
                    '</form>',
        buttons: {
            success: {
                label: "Ja",
                className: "btn-success",
                callback: function() {
                    deleteUserAjax(sender);
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

// Delete an user with ajax
function deleteUserAjax(sender) {
    // Get the tableRow for the user specified by the sender
    var tableRow = $(sender).closest('tr');

    // Set the id as the parameter
    var parameter = {
        "id": $(sender).attr('userId'),
        "password": $("#password").val()
    };

    // Send a request to the api using the 'DeleteUser' command
    api("DeleteUser", parameter, function(data) {
        // Show success message and fade out the table row corresponding
        // to the specified user
        createSuccessMessage(data.success);
        $(tableRow).animate({
            backgroundColor: '#FF8585'
        }, 1000, function () {
            $(tableRow).fadeOut(1000);
        });
    });
}

// Edit an user
function editUser(userId) {
    // Validate the form and return if it isn't valid
    if(!validateForm())
    {
        return;
    }
    // Set the data as the parameter
    var parameter = {
        "id": userId,
        "name": $('#name').val(),
        "email": $('#email').val(),
        "oldPassword": $('#oldpassword').val(),
        "password": $('#password').val()
    };

    // Send a request to the api using the 'SetUser' command
    api("SetUser", parameter, function(data) {
        // Redirect to the users overview
        redirectTo(base_url + "users/show/");
    });
}

// Fill the 'Edit user' form with data specified by the user id
function fillEditUserFormWithData(userId) {
    // Set the id as the parameter
    var parameter = {
        "id": userId
    };

    // Send a request to the api using the 'GetUserById' command
    api("GetUserById", parameter, function(data) {
        // Set the data
        $('#userId').val(data.id);
        $('#name').val(data.name);
        $('#email').val(data.email);
    });
}

// Fill the user table with the specified users.
function fillUserTable(users) {
    // Generate and fill each user row
    for (var i = 0; i < users.length; i++) {
        fillUserRow(users[i]);
    }
}

// Generate row filled with user data and append it to '#usersTable'
function fillUserRow(user) {
    // Fill the template with the specified user data
    var tableRow = Mark.up(templates['UserRow'], user);

    // Append the table row to the table
    $("#usersTable tbody").append(tableRow);
}

// Retrieve all users.
function getUsers() {
    // Send a request to the api using the 'GetAllUsers' command
    api("GetAllUsers", function(data) {
        // Fill the user table with the received users
        fillUserTable(data);
    });
}