$(document).ready(function() {
    main();
});

function main()
{
    $.ajax({
        url: "<?php echo BASE_URL; ?>api/api.php",
        contentType: 'application/json',
        type: "POST",
        dataType: "json",
        data: JSON.stringify(
                {
                    "class": "GetAllQuestions"
                }),
        success: function(data) {
            if (data["result"] === "success")
            {
                fillTable(data["questions"]);
            }
            else
            {
                showCustomAlert(data["message"], "BOING!");
            }
        }
    });
}

function fillTable(questions)
{
    for (var i = 0; i < questions.length; i++)
    {
        fillTeacherRow(questions[i]);
    }
}

function fillTeacherRow(question)
{
    var row = "<tr id='" + question["id"] + "' class='teacherRow'>";
    row += "<td>" + question["username"] + "</td>";
    row += "</tr>";
    $("#questionsTable").append(row);
}


