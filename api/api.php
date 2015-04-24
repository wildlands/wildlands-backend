<?php
/**
 * Author: INF2A
 * Date: 17.03.2015
 */

require_once('../database.php');

// Create command list
$commandList = array();
addAllCommands();

// Return error message if unable to connect to database
if ($mysqli->connect_errno > 0) {
    errorMessage("Unable to connect to database.");
}


// If 'c' parameter it will be assumed that the script is used
//  from 'outside' (like from an app)
// Check if 'c' parameter (command) is set
if (isset($_GET['c']) || isset($_POST['c'])) {
    if(isset($_POST['c'])) {
        // Set command from PHP POST parameter
        $command = $_POST['c'];
    } else {
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

    // Execute the command and return the 'return' as json
    returnJson($commandList[$command]->execute($parameter));
}

// Function: Generate an error message with given message,
//  return it as json and exit the script
function errorMessage($message)
{
    returnJson(array("error" => $message));
    exit;
}

// Function: Add all commands to the command list
function addAllCommands()
{
    new GetAllPinpoints();
    new GetAllQuestions();
    new GetAllTypes();
    new GetAnswersByQuestionId();
    new GetQuestionsByPinpointId();
    new GetQuestionById();
    new SetQuestion();
    new DeleteQuestion();
    new SetPinpoint();
    new DeletePinpoint();
    new SetType();
    new GetDatabaseChecksum();
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

// Class: GetAllPinpoints
//  Return all pinpoints from the database
//
//  Return: Json array with 'Pinpoint' objects
class GetAllPinpoints extends Command
{

    public function getCommand()
    {
        return "GetAllPinpoints";
    }

    public function execute($parameter)
    {
        $query = "SELECT PinID, TypeID, Name, Description, Xpos, Ypos, TypeID, Image FROM pinpoint;";
        $result = query($query);

        $pinpoints = array();

        while ($row = $result->fetch_assoc()) {
            $pinpoint = new Pinpoint();
            $pinpoint->id = (int)$row['PinID'];
            $pinpoint->name = $row['Name'];
            $pinpoint->description = $row['Description'];
            $pinpoint->xPos = (double)$row['Xpos'];
            $pinpoint->yPos = (double)$row['Ypos'];

            $type = new Type();

            $typeQuery = "SELECT TypeID, Name, Unit, Image FROM type WHERE type.TypeID = " . $row['TypeID'] . ";";
            $typeResult = query($typeQuery);

            while($typeRow = $typeResult->fetch_assoc()) {
                $type->id = (int)$typeRow['TypeID'];
                $type->name = $typeRow['Name'];
                $type->unit = $typeRow['Unit'];
                $type->image = $typeRow['Image'];
            }

            $pinpoint->type = $type;
            $pinpoint->image = $row['Image'];

            array_push($pinpoints, $pinpoint);
        }

        return $pinpoints;
    }

}

// Class: GetAllQuestions
//  Return all questions from the database
//
//  Return: Json array with 'Question' objects
class GetAllQuestions extends Command
{

    public function getCommand()
    {
        return "GetAllQuestions";
    }

    public function execute($parameter)
    {
        $query = "SELECT PinID FROM pinpoint;";
        $result = query($query);

        $questions = array();

        while ($row = $result->fetch_assoc()) {
            $pinpointId = (int)$row['PinID'];

            $questionsByPinpoint = (new GetQuestionsByPinpointId())->execute($pinpointId);

            foreach ($questionsByPinpoint as $question) {
                array_push($questions, $question);
            }
        }

        return $questions;
    }
}


// Class: GetDatabaseChecksum
//  Return checksum from database
//
// Return: Json 'Database' object
class GetDatabaseChecksum extends Command
{
    public function getCommand()
    {
        return "GetDatabaseChecksum";
    }

    public function execute($parameter)
    {
        $query = "CHECKSUM TABLE answer, pinpoint, question;";
        $result = query($query);

        $database = new Database();
        $database->checksum = 0;
        while ($row = $result->fetch_assoc())
        {
            $database->checksum += (int)$row['Checksum'];
        }
        return $database;
    }
}


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

        while ($row = $result->fetch_assoc()) {
            $type = new Type();

            $type->id = (int) $row['TypeID'];
            $type->name = $row['Name'];
            $type->unit = $row['Unit'];
            $type->image = $row['Image'];

            array_push($types, $type);
        }

        return $types;
    }
}

// Class: GetQuestionsByPinpointId
//  Return all questions from the database that belong to
//  the specified pinpoint id
//
// Parameter: pinpointId (Int)
//
// Return: Json array with 'Question' objects
class GetQuestionsByPinpointId extends Command
{

    public function getCommand()
    {
        return "GetQuestionsByPinpointId";
    }

    public function execute($parameter)
    {
        $query = "SELECT QuestionID, Text, Image, PinID FROM question WHERE question.PinID = " . $parameter . ";";
        $result = query($query);

        $questions = array();

        while ($row = $result->fetch_assoc()) {
            $question = new Question();
            $question->id = (int)$row['QuestionID'];
            $question->text = $row['Text'];
            $question->image = $row['Image'];
            $question->pinpointId = (int)$row['PinID'];

            $question->answers = (new GetAnswersByQuestionId())->execute($question->id);

            array_push($questions, $question);
        }

        return $questions;
    }

}

class GetQuestionById extends Command
{
 
    public function getCommand()
    {
        return "GetQuestionById";
    }
        
    public function execute($parameter)
    {
        $questionId = json_decode($parameter)->id;
        $query = "SELECT * FROM question WHERE question.QuestionID = " . $questionId . ";";
        $result = query($query);
        
        $question = new Question();
        
        while ($row = $result->fetch_assoc())
        {
            $question->id = (int) $row['QuestionID'];
            $question->text = $row['Text'];
            $question->image = $row['Image'];
            $question->pinpointId = (int) $row['PinID'];
            
            $question->answers = (new GetAnswersByQuestionId())->execute($question->id);
        }
            
        return $question;
    }
    
}

// Class: GetAnswersByQuestionId
//  Return all answers (randomized order) from the database that belong to
//  the specified question id
//
// Parameter: questionId (Int)
//
//  Return: Json array with 'Answer' objects
class GetAnswersByQuestionId extends Command
{

    public function getCommand()
    {
        return "GetAnswersByQuestionId";
    }

    public function execute($parameter)
    {
        $query = "SELECT AnswerID, RightWrong, Text FROM answer WHERE answer.QuestionID = " . $parameter . ";";
        $result = query($query);

        $answers = array();

        while ($row = $result->fetch_assoc()) {
            $answer = new Answer();
            $answer->id = (int)$row['AnswerID'];
            $answer->rightWrong = (boolean)$row['RightWrong'];
            $answer->text = $row['Text'];

            array_push($answers, $answer);
        }

        // Randomize answers
        shuffle($answers);

        return $answers;
    }
}

// Class: SetQuestion
//  Set a new question or edit an existing one
//
// Parameter: Json array with 'Question' objects
class SetQuestion extends Command
{

    public function getCommand()
    {
        return "SetQuestion";
    }

    public function execute($parameter)
    {
        $question = json_decode($parameter);

        if (isset($question->id)) {
            $query = "UPDATE question SET Text = '" . $question->text . "', Image = '" . $question->image . "', PinID = '" . $question->pinpointId . "' WHERE QuestionID = '" . $question->id . "';";
            query($query);
        } else {
            $query = "INSERT INTO question (Text, Image, PinID) VALUES ('" . $question->text . "', '" . $question->image . "', '" . $question->pinpointId . "');";
            query($query);
        }

        $query = "SELECT AnswerID FROM answer WHERE QuestionID = '" . $question->id . "';";
        $result = query($query);
        $toBeDeletedAnswers = array();
        while ($row = $result->fetch_assoc()) {
            $toBeDeleted = true;
            foreach ($question->answers as $answer) {
                if (isset($answer->id) && $row['AnswerID'] == $answer->id) {
                    $toBeDeleted = false;
                }
            }
            if ($toBeDeleted == true) {
                array_push($toBeDeletedAnswers, $row['AnswerID']);
            }
        }
        if (count($toBeDeletedAnswers) > 0) {
            foreach ($toBeDeletedAnswers as $answerId) {
                $query = "DELETE FROM answer WHERE AnswerID = '" . $answerId . "';";
                query($query);
            }
        }

        foreach ($question->answers as $answer) {
            if (isset($answer->id)) {
                $query = "UPDATE answer SET RightWrong = '" . $answer->rightWrong . "', Text = '" . $answer->text . "', QuestionID = '" . $question->id . "' WHERE AnswerID = '" . $answer->id . "';";
                query($query);
            } else {
                $query = "INSERT INTO answer (RightWrong, Text, QuestionID) VALUES ('" . $answer->rightWrong . "', '" . $answer->text . "', '" . $question->id . "');";
                query($query);
            }
        }

        return $result;
    }

}

class DeleteQuestion extends Command
{
    public function getCommand()
    {
        return "DeleteQuestion";
    }
    
    public function execute($parameter)
    {
        $question = json_decode($parameter);
        
        if (isset($question->id)) {
            $query = "DELETE FROM question WHERE QuestionID = '" . $question->id . "';";
            $result = query($query);
            $query = "DELETE FROM answer WHERE QuestionID = '" . $question->id . "';";
            $result = $result & query($query);
        } else {
            errorMessage("Question ID niet gevonden!");
        }
            
        if (!$result) {
            errorMessage("Er is iets fout gegaan.");
        }
            
        return array("success" => "Vraag is verwijderd.");
    }
}

// Class: SetPinpoint
//  Set a new pinpoint or edit an existing one
//
// Parameter: Json array with 'Pinpoint' objects
class SetPinpoint extends Command
{

    public function getCommand()
    {
        return "SetPinpoint";
    }

    public function execute($parameter)
    {
        $pinpoint = json_decode($parameter);

        if (isset($pinpoint->pinID)) {
            $query = "UPDATE pinpoint SET TypeID = '" . $pinpoint->typeId . "', Name = '" . $pinpoint->name . "', Xpos = '" . $pinpoint->xPos . "', Ypos = '" . $pinpoint->yPos . "', Description = '" . $pinpoint->description . "', Image = '" . $pinpoint->image . "' WHERE PinID = '" . $pinpoint->pinID . "';";
            $result = query($query);
        } else {
            $query = "INSERT INTO pinpoint (TypeID, Name, Xpos, Ypos, Description, Image) VALUES ('" . $pinpoint->typeId . "', '" . $pinpoint->name . "', '" . $pinpoint->xPos . "', '" . $pinpoint->yPos . "', '" . $pinpoint->description . "', '" . $pinpoint->image . "');";
            //$query = "INSERT INTO page (PageID, PinID, Title, Image, Text) VALUES ('" . $pinpoint->title . "', '" . $pinpoint->pinID . "', '" . $pinpoint->image . "', '" . $pinpoint->text . "');";
            $result = query($query);
        }

        return $result;
    }
}

// Class: DeletePinpoint
//  Delete a pinpoint
//
// Parameter: Json array with 'Pinpoint' objects
class DeletePinpoint extends Command
{

    public function getCommand()
    {
        return "DeletePinpoint";
    }

    public function execute($parameter)
    {
        $pinpoint = json_decode($parameter);

        if (isset($pinpoint->id)) {
            $query = "DELETE FROM pinpoint WHERE PinID = '" . $pinpoint->id . "';";
            $result = query($query);
        } else {
            errorMessage("PinID niet gevonden");
        }
        
        if (!$result) {
            errorMessage("Er is iets fout gegaan.");
        }
        
        return array("success" => "Pinpoint is verwijderd.");
    }
}

// Class: SetType
//  Set a new type or edit an existing one
//
// Parameter: Json array with 'Type' objects
class SetType extends Command
{

    public function getCommand()
    {
        return "SetType";
    }

    public function execute($parameter)
    {
        $type = json_decode($parameter);

        if (isset($type->id)) {
            $query = "UPDATE type SET TypeID = '" . $type->id . "', Name = '" . $type->name . "', Unit = '" . $type->unit . "' WHERE TypeID = '" . $type->id . "';";
            $result = query($query);
        } else {
            $query = "INSERT INTO type (TypeID, Name, Unit) VALUES ('" . $type->id . "', '" . $type->name . "', '" . $type->unit . "');";
            $result = query($query);
        }

        return $result;
    }
}

// Model: Pinpoint
class Pinpoint
{

    public $id;
    public $name;
    public $description;
    public $xPos;
    public $yPos;
    public $type;
    public $image;

}

// Model: Type
class Type
{

    public $id;
    public $name;
    public $unit;
    public $image;

}

// Model: Question
class Question
{

    public $id;
    public $pinpointId;
    public $text;
    public $image;
    public $answers;

}

// Model: Answer
class Answer
{

    public $id;
    public $rightWrong;
    public $text;

}

// Model: Database
class Database
{

    public $checksum;

}

// Utility function: Run the specified query and the result
function query($query)
{
    global $mysqli;
    return $mysqli->query($query);
}