<?php

class GetLevelById extends Command
{

    public function getCommand()
    {
        return "GetLevelById";
    }

    public function isAuthNeeded()
    {
        return false;
    }

    public function execute($parameter)
    {
        $level = $parameter;

        if (!isset($level->id))
        {
            $this->errorMessage("Geen LevelID gevonden.");
        }

        $query = "SELECT * FROM level WHERE LevelID = '$level->id';";
        $result = query($query);

        $row = $result->fetch_assoc();

        if (!$row)
        {
            $this->errorMessage("Er is iets fout gegaan.");
        }

        $level = new Level();
        $level->id = (int) $row['LevelID'];
        $level->name = $row['Name'];

        return $level;
    }

}