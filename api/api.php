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
    returnJson($commandList[$command]->execute(json_decode($parameter)));
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

// Function: Add all commands to the command list
function addAllCommands()
{
    new GetAllPinpoints();
    new GetAllQuestions();
    new GetAllTypes();
    new GetAnswersByQuestionId();
    new GetQuestionById();
    new GetPinpointById();
    new SetQuestion();
    new DeleteQuestion();
    new SetPinpoint();
    new DeletePinpoint();
    new SetType();
    new GetDatabaseChecksum();
    new GetAllUsers();
    new GetUserById();
    new DeleteUser();
    new SetUser();
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
        $query = "SELECT PinID, TypeID, Name, Description, Xpos, Ypos, TypeID FROM pinpoint;";
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
            
            
            $pageQuery = "SELECT PageID, PinID, Title, Image, Text FROM page WHERE PinID = " . (int)$row['PinID'] . ";";
            $pageResult = query($pageQuery);

            $pages = array();
            
            while($pageRow = $pageResult->fetch_assoc()) {
                
                $page = new Page();
                
                $page->id = (int)$pageRow['PageID'];
                $page->title = $pageRow['Title'];
                $page->image = $pageRow['Image'];
                $page->text = $pageRow['Text'];
                
                array_push($pages, $page);
            }

            $pinpoint->pages = $pages;

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
        $query = "SELECT QuestionID FROM question;";
        $result = query($query);

        $questions = array();

        while ($row = $result->fetch_assoc()) {
            $questionId = $row['QuestionID'];

            $questionObject = new Question();
            $questionObject->id = $questionId;
            
            $questionById = (new GetQuestionById())->execute($questionObject);

            array_push($questions, $questionById);
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
        $query = "CHECKSUM TABLE answer, pinpoint, question, page;";
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

class GetQuestionById extends Command
{
 
    public function getCommand()
    {
        return "GetQuestionById";
    }
        
    public function execute($parameter)
    {
        $questionId = $parameter->id;
        $query = "SELECT * FROM question WHERE question.QuestionID = " . $questionId . ";";
        $result = query($query);
        
        $question = new Question();
        
        while ($row = $result->fetch_assoc())
        {
            $question->id = (int) $row['QuestionID'];
            $question->text = $row['Text'];
            $question->image = $row['Image'];
            
            $question->answers = (new GetAnswersByQuestionId())->execute($question->id);
        }
            
        return $question;
    }
    
}

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
        //shuffle($answers);

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
        $question = $parameter;

        if (isset($question->id)) {
            $query = "UPDATE question SET Text = '" . $question->text . "', Image = '" . $question->image . "' WHERE QuestionID = '" . $question->id . "';";
            $questionUpdateResult = query($query);
        } else {
            $query = "INSERT INTO question (Text, Image) VALUES ('" . $question->text . "', '" . $question->image . "');";
            $questionUpdateResult = query($query);
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

        if (!$questionUpdateResult) {
            errorMessage("Er is iets fout gegaan.");
        }
        
        return array("success" => "Vraag is veranderd.");
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
        $question = $parameter;
        
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
        global $mysqli;
        
        $pinpoint = $parameter;

        if (isset($pinpoint->pinID)) {
            $query = "UPDATE pinpoint SET TypeID = '" . $pinpoint->typeId . "', Name = '" . $pinpoint->name . "', Xpos = '" . $pinpoint->xPos . "', Ypos = '" . $pinpoint->yPos . "', Description = '" . $pinpoint->description . "' WHERE PinID = '" . $pinpoint->pinID . "';";
            $result = query($query);
        } else {
            $query = "INSERT INTO pinpoint (TypeID, Name, Xpos, Ypos, Description) VALUES ('" . $pinpoint->typeId . "', '" . $pinpoint->name . "', '" . $pinpoint->xPos . "', '" . $pinpoint->yPos . "', '" . $pinpoint->description . "');";
            
            $result = query($query);
         
            $pinId = $mysqli->insert_id;
            
            foreach($pinpoint->pages as $page)
            {
                $query = "INSERT INTO page (PinID, Title, Image, Text) VALUES ('" . $pinId . "', '" . $page->title . "', '" . $page->pageimage . "', '" . $page->text . "');";

                $result = query($query);
            }
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
        $pinpoint = $parameter;

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
        $type = $parameter;

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

class GetAllUsers extends Command
{
    
    public function getCommand()
    {
        return "GetAllUsers";
    }
    
    public function execute($parameter)
    {
        $query = "SELECT * FROM user;";
        $result = query($query);
        
        $users = array();
        
        while ($row = $result->fetch_assoc())
        {
            $user = new User();
            $user->id = $row['UserID'];
            $user->name = $row['Screenname'];
            $user->email = $row['Email'];
            
            array_push($users, $user);
        }
        
        return $users;
    }
}

class GetUserById extends Command
{
    
    public function getCommand()
    {
        return "GetUserById";
    }
    
    public function execute($parameter)
    {
        $user = $parameter;
        
        if (!isset($user->id))
        {
            errorMessage("Geen UserID gevonden.");
        }
        
        $query = "SELECT * FROM user WHERE UserID = '" . $user->id . "';";
        $result = query($query);
        
        $row = $result->fetch_assoc();
        
        if (!$row)
        {
            errorMessage("Kon geen user met UserID '" . $user->id . "' vinden.");
        }
        
        $user = new User();
        $user->id = $row['UserID'];
        $user->name = $row['Screenname'];
        $user->email = $row['Email'];
        
        return $user;
    }
}

class SetUser extends Command
{
    
    public function getCommand()
    {
        return "SetUser";
    }
    
    public function execute($parameter)
    {
        $user = $parameter;
        
        if (isset($user->id))
        {
            $query = "UPDATE user SET Screenname = '" . $user->name . "', Email = '" . $user->email . "'";
            if (isset($user->password))
            {
                $query .= ", Password = '" . $user->password . "'";
            }
            $query .= " WHERE UserID = '" . $user->id . "';";
        }
        else
        {
            $query = "INSERT INTO user (Screenname, Email, Password) VALUES ('" . $user->name . "', '" . $user->email . "', '" . $user->password . "');";
        }
        
        $result = query($query);
        
        if (!$result)
        {
            errorMessage("Er is iets fout gegaan.");
        }
        
        successMessage("Gebruiker is aangepast.");
    }
}

class DeleteUser extends Command
{
    
    public function getCommand()
    {
        return "DeleteUser";
    }
    
    public function execute($parameter)
    {
        $user = $parameter;
        
        if (!isset($user->id))
        {
            errorMessage("Geen UserID gevonden.");
        }
        
        $query = "DELETE FROM user WHERE UserID = '" . $user->id . "';";
        $result = query($query);
        
        if (!$result)
        {
            errorMessage("Er is iets fout gegaan.");
        }
        
        successMessage("Gebruiker is verwijderd.");
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
    public $pages;

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

class Page
{
    public $id;
    public $title;
    public $image;
    public $text;
}

class User
{
    public $id;
    public $name;
    public $email;
    public $password;
}

// Utility function: Run the specified query and the result
function query($query)
{
    global $mysqli;
    return $mysqli->query($query);
}