<?php

// Class: SetLevel
//  Set a new level or edit an existing one
//
// Parameter: Level object (with or without a set id)
class SetLevel extends Command
{

    public function getCommand()
    {
        return "SetLevel";
    }

    public function isAuthNeeded()
    {
        return true;
    }

    public function execute($parameter)
    {
        $level = $parameter;

        if (isset($level->id))
        {
            $query = "UPDATE level SET Name = '$level->name' WHERE LevelID = '$level->id';";
            $successMessage = "Niveau is aangepast.";
        }
        else
        {
            $query = "INSERT INTO level (Name) VALUES ('$level->name');";
            $successMessage = "Niveau is aangemaakt.";
        }

        $result = query($query);

        if (!$result)
        {
            $this->errorMessage("Er is iets fout gegaan.");
        }

        successMessage($successMessage);
    }

}