<?php

class GetAllPages extends Command {

    public function getCommand()
    {
        return "GetAllPages";
    }

    public function execute($parameter)
    {
        $query = "SELECT * FROM page";
        $result = query($query);

        $pages = array();

        while ($row = $result->fetch_assoc())
        {
            $page = new Page();
            $page->id = $row['PageID'];
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