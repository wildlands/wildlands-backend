<?php

// TODO Write summary comment
class GetQuestionById extends Command
{

    public function getCommand()
    {
        return "GetQuestionById";
    }

    public function isAuthNeeded()
    {
        return false;
    }

    public function execute($parameter)
    {
        $questionId = $parameter->id;
        $query = "SELECT * FROM question WHERE question.QuestionID = " . $questionId . ";";
        $result = query($query);

        $question = new Question();

        while ($row = $result->fetch_assoc())
        {
            $question->id = (int) $row['QuestionID'];
            $question->text = $row['Text'];
            $question->image = $row['Image'];

            $question->answers = (new GetAnswersByQuestionId())->execute($question->id);
        }

        return $question;
    }

}

?>