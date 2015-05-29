// Add an user to the database.
function addUser()
{
    if(!validateForm())
    {
        return;
    }
    
    var parameter = {
        "name": $("#name").val(),
        "email": $("#email").val(),
        "password": $("#pass").val()
    }

    api("SetUser", parameter, function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }
        redirectTo(base_url + "users/show/");
    }, function(data) {
        createErrorMessage(data.error);
    });
}

// Delete an user (confirmation box).
function deleteUser(sender) {

    bootbox.dialog({
        message: "Wilt u deze gebruiker zeker weten verwijderen?",
        title: "Gebruiker verwijderen",
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

// Delete an user.
function deleteUserAjax(sender) {
    var tableRow = $(sender).closest('tr');

    var parameter = {
        "id": $(sender).attr('userId')
    }

    api("DeleteUser", parameter, function(data) {
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

// Updates an user.
function editUser(userId) {
    var parameter = {
        "id": userId,
        "name": $('#name').val(),
        "email": $('#email').val(),
        "oldPassword": $('#oldpassword').val(),
        "password": $('#password').val()
    }

    api("SetUser", parameter, function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            console.log(data);
            return;
        }

        redirectTo(base_url + "users/show/");
        createSuccessMessage(data.success);
    }, function(data) {
        createErrorMessage(data.error);
        console.log(data);
    });
}

// Fill the 'Edit user' form with data specified by the user id.
function fillEditUserFormWithData(userId) {
    var parameter = {
        "id": userId
    }

    api("GetUserById", parameter, function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }

        $('#userId').val(data.id);
        $('#name').val(data.name);
        $('#email').val(data.email);
    });
}

// Fill the user table with the specified users.
function fillUserTable(users) {
    for (var i = 0; i < users.length; i++) {
        fillUserRow(users[i]);
    }
}

// Generate row filled with user data and append it to '#usersTable'
function fillUserRow(user) {
    var tableRow = Mark.up(templates['UserRow'], user);
    $("#usersTable tbody").append(tableRow);
}

// Retrieve all users.
function getUsers() {
    api("GetAllUsers", function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }
        fillUserTable(data);
    }, function(data) {
        createErrorMessage(data.error);
    });
}