<?php

// Abstract Class: Command
abstract class Command
{

    // Constructor
    public function __construct()
    {
        // Get the global variable '$commandList' to be accessible
        //  in this function
        global $commandList;

        // Add this command to the global commandlist
        $newCommand = array($this->getCommand() => $this);
        $commandList = array_merge($commandList, $newCommand);
    }

    // Function: Return the command name as string
    abstract public function getCommand();

    // Function: Execute the command
    abstract public function execute($parameter);

}

?>