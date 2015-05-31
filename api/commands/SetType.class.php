<?php

// Class: SetType
//  Set a new type or edit an existing one
//
// Parameter: Type object (with or without a set id)
class SetType extends Command
{

    public function getCommand()
    {
        return "SetType";
    }

    public function isAuthNeeded()
    {
        return true;
    }

    public function execute($parameter)
    {
        $type = $parameter;

        if (isset($type->id)) {
            $query = "UPDATE type SET TypeID = '$type->id', Name = '$type->name', Unit = '$type->unit' WHERE TypeID = '$type->id';";
            $result = query($query);
        } else {
            $query = "INSERT INTO type (TypeID, Name, Unit) VALUES ('$type->id', '$type->name', '$type->unit');";
            $result = query($query);
        }

        return $result;
    }
}