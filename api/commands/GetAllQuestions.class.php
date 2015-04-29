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

    public function execute($parameter)
    {
        $query = "SELECT QuestionID FROM question;";
        $result = query($query);

        $questions = array();

        while ($row = $result->fetch_assoc()) {
            $questionId = $row['QuestionID'];

            $questionObject = new Question();
            $questionObject->id = $questionId;

            $questionById = (new GetQuestionById())->execute($questionObject);

            array_push($questions, $questionById);
        }

        return $questions;
    }
}

?>