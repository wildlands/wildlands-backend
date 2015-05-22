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

        if (!isset($questionId))
        {
            $this->errorMessage("Geen QuestionID gevonden.");
        }

        $query = "SELECT * FROM question WHERE question.QuestionID = " . $questionId . ";";
        $result = query($query);

        $question = new Question();

        while ($row = $result->fetch_assoc())
        {
            $question->id = (int) $row['QuestionID'];
            $question->level = (new GetLevelById())->execute(new IdObject($row['LevelID']));
            $question->text = $row['Text'];
            $question->image = $row['Image'];
            $question->type = (new GetTypeById())->execute(new IdObject($row['TypeID']));

            $question->answers = (new GetAnswersByQuestionId())->execute($question->id);
        }

        return $question;
    }

}

?>