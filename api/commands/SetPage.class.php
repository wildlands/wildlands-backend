<?php

// Class: SetPage
//  Set a new page or edit an existing one
//
// Parameter: Page object (with or without a set id)
class SetPage extends Command
{

    public function getCommand()
    {
        return "SetPage";
    }

    public function isAuthNeeded()
    {
        return true;
    }

    public function execute($parameter)
    {
        $page = $parameter;

        if (isset($page->id))
        {
            $query = "UPDATE page SET PinID = '$page->pinpointId', LevelID = '$page->levelId', Title = '$page->title', Image = '$page->image', Text = '$page->text' WHERE PageID = '$page->id';";
            $successMessage = "Pagina is aangepast.";
        }
        else
        {
            $query = "INSERT INTO page (PinID, LevelID, Title, Image, Text) VALUES ('$page->pinpointId', '$page->levelId', '$page->title', '$page->image', '$page->text');";
            $successMessage = "Pagina is aangemaakt.";
        }

        $result = query($query);

        if (!$result)
        {
            $this->errorMessage("Er is iets fout gegaan.");
        }

        successMessage($successMessage);
    }

}