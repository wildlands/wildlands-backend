<?php

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
            $query = "UPDATE page SET PinID = '$page->pinpointId', Title = '$page->title', Image = '$page->image', Text = '$page->text' WHERE PageID = '$page->id';";
            $successMessage = "Pagina is aangepast.";
        }
        else
        {
            $query = "INSERT INTO page (PinID, Title, Image, Text) VALUES ('$page->pinpointId', '$page->title', '$page->image', '$page->text');";
            $successMessage = "Pagina is aangemaakt.";
        }

        $result = query($query);

        if (!$result)
        {
            errorMessage("Er is iets fout gegaan.");
        }

        successMessage($successMessage);
    }

}

?>