<?php

// Class: DeletePinpoint
//  Delete a pinpoint
//
// Parameter: Pinpoint object with a set id
class DeletePinpoint extends Command
{

    public function getCommand()
    {
        return "DeletePinpoint";
    }

    public function isAuthNeeded()
    {
        return true;
    }

    public function execute($parameter)
    {
        $pinpoint = $parameter;

        if (!isset($pinpoint->id))
        {
            $this->errorMessage("PinID niet gevonden");
        }

        $query = "DELETE FROM pinpoint WHERE PinID = '" . $pinpoint->id . "';";
        $result = query($query);
        $query = "DELETE FROM page WHERE PinID = '" . $pinpoint->id . "';";
        $result = query($query);

        if (!$result) {
            $this->errorMessage("Er is iets fout gegaan.");
        }

        successMessage("Pinpoint is verwijderd.");
    }
}