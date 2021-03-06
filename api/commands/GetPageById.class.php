<?php

// Class: GetPageById
//  Return a page from the database that is associated with
//  the specified id
//
// Parameter: pageId (Int)
//
//  Return: Page object
class GetPageById extends Command
{

    public function getCommand()
    {
        return "GetPageById";
    }

    public function isAuthNeeded()
    {
        return false;
    }

    public function execute($parameter)
    {
        $page = $parameter;

        if (!isset($page->id))
        {
            $this->errorMessage("Geen PageID gevonden.");
        }

        $query = "SELECT * FROM page WHERE PageID = '$page->id';";
        $result = query($query);

        $row = $result->fetch_assoc();

        if (!$row)
        {
            $this->errorMessage("Er is iets fout gegaan.");
        }

        $page = new Page();
        $page->id = (int) $row['PageID'];
        $page->pinpointId = $row['PinID'];
        $page->level = (new GetLevelById())->execute(new IdObject($row['LevelID']));
        $page->title = $row['Title'];
        $page->image = $row['Image'];
        $page->text = $row['Text'];

        return $page;
    }

}