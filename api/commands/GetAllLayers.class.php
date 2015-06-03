<?php

// Class: GetAllLayers
//  Return all layers from the database
//
//  Return: Array with 'Layer' objects
class GetAllLayers extends Command {

    public function getCommand()
    {
        return "GetAllLayers";
    }

    public function isAuthNeeded()
    {
        return false;
    }

    public function execute($parameter)
    {
        $query = "SELECT LayerID FROM layer";
        $result = query($query);

        $layers = array();

        while ($row = $result->fetch_assoc())
        {
            $layer = (new GetLayerById())->execute(new IdObject($row['LayerID']));

            array_push($layers, $layer);
        }

        return $layers;
    }

}