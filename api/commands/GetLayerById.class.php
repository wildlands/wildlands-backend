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
        $layerId = $parameter->id;

        if (!isset($layerId))
        {
            $this->errorMessage("Geen LayerID gevonden.");
        }

        $query = "SELECT * FROM layer WHERE layer.LayerID = " . $layerId . ";";
        $result = query($query);
        
        $layer = new Layer();
        
        while ($row = $result->fetch_assoc())
        {
            $layer->id = (int) $row['LayerID'];
            $layer->type = (new GetTypeById())->execute(new IdObject($row['TypeID']));
            $layer->image = $row['Image'];
        }

        return $layer;
    }

}