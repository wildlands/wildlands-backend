<?php

// Class: DeleteLevel
//  Delete a level
//
// Parameter: Level object with a set id
class DeleteLevel extends Command
{

    public function getCommand()
    {
        return "DeleteLevel";
    }

    public function isAuthNeeded()
    {
        return true;
    }

    public function execute($parameter)
    {
        $level = $parameter;

        if (!isset($level->id))
        {
            $this->errorMessage("Geen LevelID gevonden.");
        }

        $query = "DELETE FROM page WHERE LevelID = '$level->id';";
        query($query);

        $query = "SELECT QuestionID FROM question WHERE LevelID = '$level->id';";
        $result = query($query);

        while ($row = $result->fetch_assoc()) {
            $questionId = $row['QuestionID'];

            $query = "DELETE FROM answer WHERE QuestionID = '$questionId';";
            query($query);
        }

        $query = "DELETE FROM question WHERE LevelID = '$level->id';";
        query($query);

        $query = "DELETE FROM level WHERE LevelID = '$level->id';";
        $result = query($query);

        if (!$result)
        {
            $this->errorMessage("Er is iets fout gegaan");
        }

        successMessage("Niveau is verwijderd.");
    }

}