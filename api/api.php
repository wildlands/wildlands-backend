<?php
/**
 * Author: INF2A
 * Date: 17.03.2015
 */

require_once('../database.php');
require_once('commands/_Command.class.php');

// Directories that contain class files.
$directories = array(
    'commands/',
    'models/'
);

// PHP5 Autoloader for classes that are not included yet.
function __autoload($class_name)
{
    global $directories;

    // Check every directory.
    foreach($directories as $directory)
    {
        // If the file exists, then load it.
        if(file_exists($directory . $class_name . '.class.php'))
        {
            require_once($directory . $class_name . '.class.php');
            return;
        }
    }
}

// Return error message if unable to connect to database
if ($mysqli->connect_errno > 0)
{
    errorMessage("Unable to connect to database.");
}

// Create command list
$commandList = array();
addAllCommands();

if (isset($_POST['auth']) || isset($_GET['auth']))
{
    if(isset($_POST['auth']))
    {
        // Set authToken from PHP POST parameter
        $authToken = $_POST['auth'];
    }
    else
    {
        // Set authToken from PHP GET parameter
        $authToken = $_GET['auth'];
    }
}

// If 'c' parameter is set it will be assumed that the script is used
//  from 'outside' (like from an app)
// Check if 'c' parameter (command) is set
if (isset($_GET['c']) || isset($_POST['c']))
{
    if(isset($_POST['c']))
    {
        // Set command from PHP POST parameter
        $command = $_POST['c'];
    }
    else
    {
        // Set command from PHP GET parameter
        $command = $_GET['c'];
    }

    // Check if 'p' parameter (parameter) is set
    if (isset($_POST['p'])) {
        // Set parameter from PHP POST parameter
        $parameter = $_POST['p'];
    } else if(isset($_GET['p'])) {
        // Set parameter from PHP POST parameter
        $parameter = $_GET['p'];
    } else {
        // If no 'p' parameter isn't set, then set 'parameter' to null
        //  to indicate 'no parameter'
        $parameter = null;
    }

    // Execute the given command (with the given parameter)
    executeCommand($command, $parameter);
}

// Function: Execute the given command with the given parameter
function executeCommand($command, $parameter)
{
    // Get the global variable '$commandList' to be accessible
    //  in this function
    global $commandList;

    // Check if the command exists at all
    if (!isset($commandList[$command])) {
        errorMessage("Command not found.");
    }

    if ($commandList[$command]->isAuthNeeded() && !isAuthValid())
    {
        errorMessage("Forbidden - No authentication!");
    }

    // Execute the command and return the 'return' as json
    returnJson($commandList[$command]->execute(json_decode($parameter)));
}

// Function: Add all commands to the command list
function addAllCommands()
{
    new DeleteAnswer();
    new DeleteLevel();
    new DeletePage();
    new DeletePinpoint();
    new DeleteQuestion();
    new DeleteUser();
    new GetAllLevels();
    new GetAllPages();
    new GetAllPinpoints();
    new GetAllQuestions();
    new GetAllTypes();
    new GetAllUsers();
    new GetAnswersByQuestionId();
    new GetCount();
    new GetDatabaseChecksum();
    new GetLevelById();
    new GetPageById();
    new GetPagesByPinpointId();
    new GetPinpointById();
    new GetQuestionById();
    new GetTypeById();
    new GetUserById();
    new SetLevel();
    new SetPage();
    new SetPinpoint();
    new SetQuestion();
    new SetType();
    new SetUser();
}

// Function: Generate an error message with given message,
//  return it as json and exit the script
function errorMessage($message)
{
    returnJson(array("error" => $message));
    exit;
}

// Function: Generate an success message with given message,
//  return it as json and exit the script
function successMessage($message)
{
    returnJson(array("success" => $message));
    exit;
}

// Function: Generate json from '$output' and print it
//  (if the script is used from 'outside') or return it
function returnJson($output)
{
    $return = json_encode($output);
    if(isset($_GET['c']) || isset($_POST['c'])) {
        header('Content-Type: application/json');
        echo $return;
    } else {
        return $return;
    }
}

// Function: Check if authToken is valid
function isAuthValid()
{
    session_start();

    // Check if a hash can be found in the session
    if (isset($_SESSION['hash']))
    {
        $hash = $_SESSION['hash'];
        $query = "SELECT * FROM session WHERE Hash='$hash' LIMIT 1";
        $result = query($query);

        if ($result->num_rows >= 0)
        {
            return true;
        }
    }

    return false;
}

// Utility function: Run the specified query and the result
function query($query)
{
    global $mysqli;
    return $mysqli->query($query);
}

function getInsertId()
{
    global $mysqli;
    return $mysqli->insert_id;
}
