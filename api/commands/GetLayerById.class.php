<?php

// Class: GetLayerById
//  Return a layer from the database that is associated with
//  the specified id
//
// Parameter: layerId (Int)
//
//  Return: Layer object
class GetLayerById extends Command
{

    public function getCommand()
    {
        return "GetLayerById";
    }

    public function isAuthNeeded()
    {
        return false;
    }

    public function execute($parameter)
    {
        $layer = $parameter;

        if (!isset($layer->id))
        {
            $this->errorMessage("Geen LayerID gevonden.");
        }

        $query = "SELECT layer.Image, layer.LayerID, type.Name FROM layer, type WHERE LayerID = '$layer->id' AND layer.LayerID = type.TypeID;";
        $result = query($query);

        $row = $result->fetch_assoc();

        if (!$row)
        {
            $this->errorMessage("Er is iets fout gegaan.");
        }

        $layer = new Layer();
        $layer->id = (int) $row['LayerID'];
        $layer->typeId = $row['Name'];
        $layer->image = $row['Image'];

        return $layer;
    }

}