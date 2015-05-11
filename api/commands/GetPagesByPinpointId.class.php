<?php

class GetPagesByPinpointId extends Command
{

    public function getCommand()
    {
        return "GetPagesByPinpointId";
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
            errorMessage("Geen PinpointID gevonden.");
        }

        $query = "SELECT * FROM page WHERE PinID = '$pinpoint->id';";
        $result = query($query);

        $pages = array();

        while($row = $result->fetch_assoc())
        {
            $page = new Page();

            $page->id = (int) $row['PageID'];
            $page->pinpointId = $row['PinID'];
            $page->title = $row['Title'];
            $page->image = $row['Image'];
            $page->text = $row['Text'];

            array_push($pages, $page);
        }

        return $pages;
    }

}

?>