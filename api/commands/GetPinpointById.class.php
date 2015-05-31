<?php

// Class: GetPinpointById
//  Return a pinpoint from the database that is associated with
//  the specified id
//
// Parameter: pinpointId (Int)
//
//  Return: Pinpoint object
class GetPinpointById extends Command
{

    public function getCommand()
    {
        return "GetPinpointById";
    }

    public function isAuthNeeded()
    {
        return false;
    }

    public function execute($parameter)
    {
        $pinpoint = $parameter;

        if (!isset($pinpoint->id))
        {
            $this->errorMessage("Geen PinpointID gevonden.");
        }

        $query = "SELECT * FROM pinpoint WHERE PinID = '$pinpoint->id';";
        $result = query($query);

        $row = $result->fetch_assoc();

        if (!$row)
        {
            $this->errorMessage("Er is iets fout gegaan.");
        }

        $pinpoint = new Pinpoint();

        $pinpoint->id = (int)$row['PinID'];
        $pinpoint->name = $row['Name'];
        $pinpoint->description = $row['Description'];
        $pinpoint->xPos = (double)$row['Xpos'];
        $pinpoint->yPos = (double)$row['Ypos'];
        $pinpoint->type = (new GetTypeById())->execute(new IdObject($row['TypeID']));
        $pinpoint->pages = (new GetPagesByPinpointId())->execute(new IdObject($row['PinID']));

        return $pinpoint;
    }

}