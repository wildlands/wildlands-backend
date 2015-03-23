<?php
/**
 * User: INF2A
 * Date: 18.03.2015
 */

require_once('api.php');

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