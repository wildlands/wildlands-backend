<?php

// Class: SetPinpoint
//  Set a new pinpoint or edit an existing one
//
// Parameter: Json array with 'Pinpoint' objects
class SetPinpoint extends Command
{
    public function getCommand()
    {
        return "SetPinpoint";
    }

    public function execute($parameter)
    {
        global $mysqli;

        $pinpoint = $parameter;

        if (isset($pinpoint->pinID)) {
            $query = "UPDATE pinpoint SET TypeID = '" . $pinpoint->typeId . "', Name = '" . $pinpoint->name . "', Xpos = '" . $pinpoint->xPos . "', Ypos = '" . $pinpoint->yPos . "', Description = '" . $pinpoint->description . "' WHERE PinID = '" . $pinpoint->pinID . "';";
            $result = query($query);
        } else {
            $query = "INSERT INTO pinpoint (TypeID, Name, Xpos, Ypos, Description) VALUES ('" . $pinpoint->typeId . "', '" . $pinpoint->name . "', '" . $pinpoint->xPos . "', '" . $pinpoint->yPos . "', '" . $pinpoint->description . "');";

            $result = query($query);

            $pinId = $mysqli->insert_id;

            foreach($pinpoint->pages as $page)
            {
                $query = "INSERT INTO page (PinID, Title, Image, Text) VALUES ('" . $pinId . "', '" . $page->title . "', '" . $page->pageimage . "', '" . $page->text . "');";

                $result = query($query);
            }
        }

        return $result;
    }
}

?>