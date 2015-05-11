<?php

// TODO Write summary comment
class DeleteQuestion extends Command
{
    public function getCommand()
    {
        return "DeleteQuestion";
    }

    public function isAuthNeeded()
    {
        return true;
    }

    public function execute($parameter)
    {
        $question = $parameter;

        if (!isset($question->id))
        {
            errorMessage("Question ID niet gevonden!");
        }

        $query = "DELETE FROM question WHERE QuestionID = '" . $question->id . "';";
        $result = query($query);
        $query = "DELETE FROM answer WHERE QuestionID = '" . $question->id . "';";
        $result &= query($query);

        if (!$result) {
            errorMessage("Er is iets fout gegaan.");
        }

        return array("success" => "Vraag is verwijderd.");
    }
}

?>