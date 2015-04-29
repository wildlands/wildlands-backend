<?php

// Class: GetAllPinpoints
//  Return all pinpoints from the database
//
//  Return: Json array with 'Pinpoint' objects
class GetAllPinpoints extends Command
{

    public function getCommand()
    {
        return "GetAllPinpoints";
    }

    public function execute($parameter)
    {
        $query = "SELECT PinID, TypeID, Name, Description, Xpos, Ypos, TypeID FROM pinpoint;";
        $result = query($query);

        $pinpoints = array();

        while ($row = $result->fetch_assoc()) {
            $pinpoint = new Pinpoint();
            $pinpoint->id = (int)$row['PinID'];
            $pinpoint->name = $row['Name'];
            $pinpoint->description = $row['Description'];
            $pinpoint->xPos = (double)$row['Xpos'];
            $pinpoint->yPos = (double)$row['Ypos'];

            $type = new Type();

            $typeQuery = "SELECT TypeID, Name, Unit, Image FROM type WHERE type.TypeID = " . $row['TypeID'] . ";";
            $typeResult = query($typeQuery);

            while($typeRow = $typeResult->fetch_assoc()) {
                $type->id = (int)$typeRow['TypeID'];
                $type->name = $typeRow['Name'];
                $type->unit = $typeRow['Unit'];
                $type->image = $typeRow['Image'];
            }

            $pinpoint->type = $type;


            $pageQuery = "SELECT PageID, PinID, Title, Image, Text FROM page WHERE PinID = " . (int)$row['PinID'] . ";";
            $pageResult = query($pageQuery);

            $pages = array();

            while($pageRow = $pageResult->fetch_assoc()) {

                $page = new Page();

                $page->id = (int)$pageRow['PageID'];
                $page->title = $pageRow['Title'];
                $page->image = $pageRow['Image'];
                $page->text = $pageRow['Text'];

                array_push($pages, $page);
            }

            $pinpoint->pages = $pages;

            array_push($pinpoints, $pinpoint);
        }

        return $pinpoints;
    }

}

?>