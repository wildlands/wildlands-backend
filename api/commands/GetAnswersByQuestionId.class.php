<?php

// Class: GetAnswersByQuestionId
//  Return all answers (randomized order) from the database that belong to
//  the specified question id
//
// Parameter: questionId (Int)
//
//  Return: Json array with 'Answer' objects
class GetAnswersByQuestionId extends Command
{

    public function getCommand()
    {
        return "GetAnswersByQuestionId";
    }

    public function isAuthNeeded()
    {
        return true;
    }

    public function execute($parameter)
    {
        $query = "SELECT AnswerID, RightWrong, Text FROM answer WHERE answer.QuestionID = " . $parameter . ";";
        $result = query($query);

        $answers = array();

        while ($row = $result->fetch_assoc()) {
            $answer = new Answer();
            $answer->id = (int)$row['AnswerID'];
            $answer->rightWrong = (boolean)$row['RightWrong'];
            $answer->text = $row['Text'];

            array_push($answers, $answer);
        }

        return $answers;
    }
}

?>