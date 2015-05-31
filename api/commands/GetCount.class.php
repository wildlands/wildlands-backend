<?php

// Class: GetCount
//  Return count of objects from each table from database
//
// Return: Count object
class GetCount extends Command
{
    public function getCommand()
    {
        return "GetCount";
    }

    public function isAuthNeeded()
    {
        return false;
    }

    public function execute($parameter)
    {
        $query = "SELECT COUNT(*) AS answer, (SELECT COUNT(*) FROM level) AS level, (SELECT COUNT(*) FROM page) AS page, (SELECT COUNT(*) FROM pinpoint) AS pinpoint, (SELECT COUNT(*) FROM question) AS question FROM answer;";
        $result = query($query);

        $row = $result->fetch_assoc();

        if (!$row)
        {
            $this->errorMessage("Er is iets fout gegaan.");
        }

        $count = new Count();
        $count->answer = $row['answer'];
        $count->level = $row['level'];
        $count->page = $row['page'];
        $count->pinpoint = $row['pinpoint'];
        $count->question = $row['question'];

        return $count;
    }
}