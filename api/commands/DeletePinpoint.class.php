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

        if (isset($pinpoint->id)) {
            $query = "DELETE FROM pinpoint WHERE PinID = '" . $pinpoint->id . "';";
            $result = query($query);
        } else {
            errorMessage("PinID niet gevonden");
        }

        if (!$result) {
            errorMessage("Er is iets fout gegaan.");
        }

        return array("success" => "Pinpoint is verwijderd.");
    }
}

?>