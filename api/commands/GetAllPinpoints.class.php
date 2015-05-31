<?php

// Class: GetAllPinpoints
//  Return all pinpoints from the database
//
//  Return: Array with 'Pinpoint' objects
class GetAllPinpoints extends Command
{

    public function getCommand()
    {
        return "GetAllPinpoints";
    }

    public function isAuthNeeded()
    {
        return false;
    }

    public function execute($parameter)
    {
        $query = "SELECT * FROM pinpoint;";
        $result = query($query);

        $pinpoints = array();

        while ($row = $result->fetch_assoc()) {
            $pinpoint = (new GetPinpointById())->execute(new IdObject($row['PinID']));

            array_push($pinpoints, $pinpoint);
        }

        return $pinpoints;
    }

}