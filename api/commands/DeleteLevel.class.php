<?php

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

        $query = "DELETE FROM level WHERE LevelID = '$level->id';";
        $result = query($query);

        if (!$result)
        {
            $this->errorMessage("Er is iets fout gegaan");
        }

        successMessage("Niveau is verwijderd.");
    }

}

?>