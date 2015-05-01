<?php

// Class: GetAllTypes
//  Return all types from the database
//
// Return: Json array with 'Type' objects
class GetAllTypes extends Command
{

    public function getCommand()
    {
        return "GetAllTypes";
    }

    public function execute($parameter)
    {
        $query = "SELECT TypeID, Name, Unit, Image FROM type;";
        $result = query($query);

        $types = array();

        while ($row = $result->fetch_assoc())
        {
            $type = (new GetTypeById())->execute(new IdObject($row['TypeID']));

            array_push($types, $type);
        }

        return $types;
    }
}

?>