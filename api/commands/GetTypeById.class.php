<?php

class GetTypeById extends Command
{

    public function getCommand()
    {
        return "GetTypeById";
    }

    public function isAuthNeeded()
    {
        return false;
    }

    public function execute($parameter)
    {
        $type = $parameter;

        if (!isset($type->id))
        {
            errorMessage("Geen TypeID gevonden.");
        }

        $query = "SELECT * FROM type WHERE TypeID = '$type->id';";
        $result = query($query);

        $row = $result->fetch_assoc();

        if (!$row)
        {
            errorMessage("Er is iets fout gegaan.");
        }

        $type = new Type();
        $type->id = (int) $row['TypeID'];
        $type->name = $row['Name'];
        $type->unit = $row['Unit'];
        $type->image = $row['Image'];

        return $type;
    }

}

?>