<?php

// Class: SetPinpoint
//  Set a new pinpoint or edit an existing one
//
// Parameter: Pinpoint object (with or without a set id)
class SetPinpoint extends Command
{
    public function getCommand()
    {
        return "SetPinpoint";
    }

    public function isAuthNeeded()
    {
        return true;
    }

    public function execute($parameter)
    {
        $pinpoint = $parameter;

        if (isset($pinpoint->id)) {
            $query = "UPDATE pinpoint SET TypeID = '$pinpoint->typeId', Name = '$pinpoint->name', Xpos = '$pinpoint->xPos', Ypos = '$pinpoint->yPos', Description = '$pinpoint->description' WHERE PinID = '$pinpoint->id';";
            $result = query($query);
            
            $query = "SELECT PageID FROM page WHERE PinID = '$pinpoint->id';";
            $result = query($query);
            $toBeDeletedPages = array();
            while ($row = $result->fetch_assoc()) {
                $toBeDeleted = true;
                foreach ($pinpoint->pages as $page) {
                    if (isset($page->id) && $row['PageID'] == $page->id) {
                        $toBeDeleted = false;
                    }
                }
                if ($toBeDeleted == true) {
                    array_push($toBeDeletedPages, $row['PageID']);
                }
            }
            if (count($toBeDeletedPages) > 0) {
                foreach ($toBeDeletedPages as $pageId) {
                    $query = "DELETE FROM page WHERE PageID = '$pageId';";
                    query($query);
                }
            }
            
            
        } else {
            $query = "INSERT INTO pinpoint (TypeID, Name, Xpos, Ypos, Description) VALUES ('$pinpoint->typeId', '$pinpoint->name', '$pinpoint->xPos', '$pinpoint->yPos', '$pinpoint->description');";
            $result = query($query);
            $pinpoint->id = getInsertId();
        }
        
        foreach($pinpoint->pages as $page)
        {
            if (isset($page->id))
            {
                $query = "UPDATE page SET PinID = '$pinpoint->id', LevelID = '$page->levelId', Title = '$page->title', Image = '$page->pageimage', Text = '$page->text' WHERE PageID = '$page->id';";
                $result = query($query);
            }
            else
            {
                $query = "INSERT INTO page (PinID, LevelID, Title, Image, Text) VALUES ('$pinpoint->id', '$page->levelId', '$page->title', '$page->pageimage', '$page->text');";
                $result = query($query);
            }
        }
        
        if (!$result)
        {
            $this->errorMessage("Er is iets fout gegaan.");
        }
        
        successMessage("Pinpoint is gewijzigd.");
    }
}