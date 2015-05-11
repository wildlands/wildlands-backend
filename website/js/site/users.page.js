// Add an user to the database.
function addUser()
{
    var parameter = {
        "name": $("#name").val(),
        "email": $("#email").val(),
        "password": $("#pass").val()
    }

    api("SetUser", parameter, function(data) {
        redirectTo(base_url + "users/show/");
    }, function(data) {
        console.log(data);
    });
}

// Delete an user.
function deleteUser(sender) {
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

    var row = "<tr id='" + user.id + "' class='userRow'>";
    row += "<td>" + user.id + "</td>";
    row += "<td>" + user.name + "</td>";
    row += "<td>" + user.email + "</td>";
    row += "<td>" + "<a href='../edit/" + user.id + "' class='btn btn-warning pull-right'><i class='fa fa-pencil'></i></a>" + "<a class='btn btn-danger pull-right deleteUser' userId='" + user.id + "' onclick='javascript: deleteUser(this);'><i class='fa fa-times'></i></a>" + "</td>";
    row += "</tr>";
    $("#usersTable").append(row);
}

// Retrieve all users.
function getUsers() {
    api("GetAllUsers", function(data) {
        console.log("Success");
        console.log(data);
        fillUserTable(data);
    }, function(data) {
        console.log("Fail");
        console.log(data);
    });
}

// Updates an user.
function updateUser() {
    var parameter = {
        "id": $('#userId').val(),
        "name": $('#name').val(),
        "email": $('#email').val(),
        "password": $('#pass').val()
    }

    api("SetUser", parameter, function(data) {
        if (data.error) {
            createErrorMessage(data.error);
            return;
        }

        redirectTo(base_url + "users/show/");
        createSuccessMessage(data.success);
    }, function(data) {
        createErrorMessage(data.error);
    });
}