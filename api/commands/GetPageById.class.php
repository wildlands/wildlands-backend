<?php

class GetPageById extends Command
{

    public function getCommand()
    {
        return "GetPageById";
    }

    public function execute($parameter)
    {
        $page = $parameter;

        if (!isset($page->id))
        {
            errorMessage("Geen PageID gevonden.");
        }

        $query = "SELECT * FROM page WHERE PageID = '$page->id';";
        $result = query($query);

        $row = $result->fetch_assoc();

        if (!$row)
        {
            errorMessage("Er is iets fout gegaan.");
        }

        $page = new Page();
        $page->id = (int) $row['PageID'];
        $page->pinpointId = $row['PinID'];
        $page->title = $row['Title'];
        $page->image = $row['Image'];
        $page->text = $row['Text'];

        return $page;
    }

}

?>