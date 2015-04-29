<?php
/**
 * User: INF2A
 * Date: 18.03.2015
 */

require_once('../api.php');

//testQuestionSetting();
//testSetImage();
testGetImage();


function testGetImage() {
    $query = "SELECT Image FROM Question WHERE QuestionID = 2;";
    $result = query($query);

    while ($row = $result->fetch_assoc()) {
        echo "<img src='data:image/png;base64," . base64_encode( $row['Image'] )."'/>";
    }

}

function testSetImage() {
    global $mysqli;

    $imagePath = "./test/test.png";
    $image = addslashes(file_get_contents($imagePath));

    //var_dump($image);

    $query = "UPDATE question SET Image = '" . $image . "' WHERE QuestionID = 2;";
    $result = query($query);

    var_dump($result);
    echo "<br>";
    echo mysqli_error($mysqli);
}

function testQuestionSetting() {
    $answer1 = new Answer();
    $answer1->rightWrong = 0;
    $answer1->text = "Test Answer 1";
    $answer1->id = 11;

    $answer2 = new Answer();
    $answer2->rightWrong = 1;
    $answer2->text = "Test Answer 2";
    $answer2->id = 21;

    $answer3 = new Answer();
    $answer3->rightWrong = 0;
    $answer3->text = "Test Answer 3";
    //$answer3->id = 21;

    $question = new Question();
    $question->pinpointId = 1;
    $question->answers = array($answer1, $answer2, $answer3);
    $question->id = 1;
    $question->image = "";
    $question->text = "Test vraag";

    executeCommand("SetQuestionByQuestionId", json_encode($question));
}