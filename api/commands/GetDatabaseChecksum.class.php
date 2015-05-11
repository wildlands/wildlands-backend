<?php

// Class: GetDatabaseChecksum
//  Return checksum from database
//
// Return: Json 'Database' object
class GetDatabaseChecksum extends Command
{
    public function getCommand()
    {
        return "GetDatabaseChecksum";
    }

    public function isAuthNeeded()
    {
        return true;
    }

    public function execute($parameter)
    {
        $query = "CHECKSUM TABLE answer, pinpoint, question, page;";
        $result = query($query);

        $database = new Database();
        $database->checksum = 0;
        while ($row = $result->fetch_assoc())
        {
            $database->checksum += (int)$row['Checksum'];
        }
        return $database;
    }
}

?>