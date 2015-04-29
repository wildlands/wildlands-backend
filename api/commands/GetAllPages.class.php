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
            $page = (new GetPageById())->execute(new IdObject($row['PageID']));

            array_push($pages, $page);
        }

        return $pages;
    }

}

?>