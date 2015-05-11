<?php

// Class: GetAllQuestions
//  Return all questions from the database
//
//  Return: Json array with 'Question' objects
class GetAllQuestions extends Command
{

    public function getCommand()
    {
        return "GetAllQuestions";
    }

    public function isAuthNeeded()
    {
        return false;
    }

    public function execute($parameter)
    {
        $query = "SELECT QuestionID FROM question;";
        $result = query($query);

        $questions = array();

        while ($row = $result->fetch_assoc()) {
            $question = (new GetQuestionById())->execute(new IdObject($row['QuestionID']));

            array_push($questions, $question);
        }

        return $questions;
    }
}

?>