<?php

// Class: DeletePinpoint
//  Delete a pinpoint
//
// Parameter: Json array with 'Pinpoint' objects
class DeletePinpoint extends Command
{

    public function getCommand()
    {
        return "DeletePinpoint";
    }

    public function execute($parameter)
    {
        $pinpoint = $parameter;

        if (!isset($pinpoint->id))
        {
            errorMessage("PinID niet gevonden");
        }

        $query = "DELETE FROM pinpoint WHERE PinID = '" . $pinpoint->id . "';";
        $result = query($query);

        if (!$result) {
            errorMessage("Er is iets fout gegaan.");
        }

        successMessage("Pinpoint is verwijderd.");
    }
}

?>