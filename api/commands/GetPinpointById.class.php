<?php

// TODO Write summary comment
class GetPinpointById extends Command
{

    public function getCommand()
    {
        return "GetPinpointById";
    }

    public function execute($parameter)
    {
        $pinpointId = $parameter->id;
        $query = "SELECT * FROM pinpoint WHERE pinpoint.PinpointID = " . $pinpointId . ";";
        $result = query($query);

        $pinpoint = new Pinpoint();

        while ($row = $result->fetch_assoc())
        {
            $pinpoint->id = (int) $row['PinpointID'];
            $pinpoint->xPos = $row['xPos'];
            $pinpoint->yPos = $row['yPos'];
            $pinpoint->description = $row['description'];
            $pinpoint->pinpointType = $row['pinpointType'];
        }

        return $pinpoint;
    }

}

?>