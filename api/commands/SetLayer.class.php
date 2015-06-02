<?php

// Class: SetLayer
//  Set a new layer or edit an existing one
//
// Parameter: Layer object (with or without a set id)
class SetLayer extends Command
{

    public function getCommand()
    {
        return "SetLayer";
    }

    public function isAuthNeeded()
    {
        return true;
    }

    public function execute($parameter)
    {
        $layer = $parameter;

        if (isset($layer->id))
        {
            $query = "UPDATE layer SET TypeID = '$layer->typeId', Image = '$layer->image' WHERE LayerID = '$layer->id';";
            $successMessage = "Layer is aangepast.";
        }
        else
        {
            $query = "INSERT INTO layer (TypeID, Image) VALUES ('$layer->typeId', '$layer->image');";
            $successMessage = "Layer is aangemaakt.";
        }

        $result = query($query);

        if (!$result)
        {
            $this->errorMessage("Er is iets fout gegaan.");
        }

        successMessage($successMessage);
    }

}