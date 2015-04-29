<?php

// TODO Write summary comment
class DeleteQuestion extends Command
{
    public function getCommand()
    {
        return "DeleteQuestion";
    }

    public function execute($parameter)
    {
        $question = $parameter;

        if (isset($question->id)) {
            $query = "DELETE FROM question WHERE QuestionID = '" . $question->id . "';";
            $result = query($query);
            $query = "DELETE FROM answer WHERE QuestionID = '" . $question->id . "';";
            $result = $result & query($query);
        } else {
            errorMessage("Question ID niet gevonden!");
        }

        if (!$result) {
            errorMessage("Er is iets fout gegaan.");
        }

        return array("success" => "Vraag is verwijderd.");
    }
}

?>