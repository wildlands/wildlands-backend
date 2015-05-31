<?php

// Class: GetAllLevels
//  Return all levels from the database
//
//  Return: Array with 'Level' objects
class GetAllLevels extends Command {

    public function getCommand()
    {
        return "GetAllLevels";
    }

    public function isAuthNeeded()
    {
        return false;
    }

    public function execute($parameter)
    {
        $query = "SELECT * FROM level";
        $result = query($query);

        $levels = array();

        while ($row = $result->fetch_assoc())
        {
            $level = (new GetLevelById())->execute(new IdObject($row['LevelID']));

            array_push($levels, $level);
        }

        return $levels;
    }

}