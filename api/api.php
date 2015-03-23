<?php
/**
 * User: INF2A
 * Date: 17.03.2015
 */

require_once('../database.php');

$commandList = array();
addAllCommands();

if ($mysqli->connect_errno > 0) {
    errorMessage("Unable to connect to database.");
}

if (isset($_GET['c'])) {
    $command = $_GET['c'];
    if (isset($_GET['p'])) {
        $parameter = $_GET['p'];
    } else {
        $parameter = null;
    }

    executeCommand($command, $parameter);
}

function executeCommand($command, $parameter)
{
    global $commandList;

    if (!isset($commandList[$command])) {
        errorMessage("Command not found.");
    }

    returnJson($commandList[$command]->execute($parameter));
}

function errorMessage($message)
{
    returnJson(array("error" => $message));
    exit;
}

function addAllCommands()
{
    new GetAllPinpoints();
    new GetAllQuestions();
    new GetAllTypes();
    new GetAnswersByQuestionId();
    new GetQuestionsByPinpointId();
    new SetQuestion();
    new SetPinpoint();
    new SetType();
}

function returnJson($output)
{
    $return = json_encode($output);
    if(isset($_GET['c'])) {
        echo $return;
    } else {
        return $return;
    }

    //echo var_dump($output);
}

// Commands

abstract class Command
{

    public function __construct()
    {
        global $commandList;

        $newCommand = array($this->getCommand() => $this);
        $commandList = array_merge($commandList, $newCommand);
    }

    abstract public function getCommand();

    abstract public function execute($parameter);

}

class GetAllPinpoints extends Command
{

    public function getCommand()
    {
        return "GetAllPinpoints";
    }

    public function execute($parameter)
    {
        $query = "SELECT pinpoint.PinID, pinpoint.TypeID, pinpoint.Name, pinpoint.Description, pinpoint.Xpos, pinpoint.Ypos FROM pinpoint;";
        $result = query($query);

        $pinpoints = array();

        while ($row = $result->fetch_assoc()) {
            $pinpoint = new Pinpoint();
            $pinpoint->id = (int)$row['PinID'];
            $pinpoint->name = $row['Name'];
            $pinpoint->description = $row['Description'];
            $pinpoint->xPos = (double)$row['Xpos'];
            $pinpoint->yPos = (double)$row['Ypos'];
            $pinpoint->typeId = (int)$row['TypeID'];

            array_push($pinpoints, $pinpoint);
        }

        return $pinpoints;
    }

}

class GetAllQuestions extends Command
{

    public function getCommand()
    {
        return "GetAllQuestions";
    }

    public function execute($parameter)
    {
        $query = "SELECT pinpoint.PinID FROM pinpoint;";
        $result = query($query);

        $questions = array();

        while ($row = $result->fetch_assoc()) {
            $pinpointId = (int)$row['PinID'];

            $questionsByPinpoint = (new GetQuestionsByPinpointId())->execute($pinpointId);

            foreach ($questionsByPinpoint as $question) {
                array_push($questions, $question);
            }
        }

        //echo var_dump($questions);

        return $questions;
    }
}

class GetAllTypes extends Command
{

    public function getCommand()
    {
        return "GetAllTypes";
    }

    public function execute($parameter)
    {
        $query = "SELECT type.TypeID, type.Name, type.Unit FROM type;";
        $result = query($query);

        $types = array();

        while ($row = $result->fetch_assoc()) {
            $type = new Type();

            $type->id = (int) $row['TypeID'];
            $type->name = $row['Name'];
            $type->unit = $row['Unit'];

            array_push($types, $type);
        }

        return $types;
    }
}

class GetQuestionsByPinpointId extends Command
{

    public function getCommand()
    {
        return "GetQuestionsByPinpointId";
    }

    public function execute($parameter)
    {
        $query = "SELECT question.QuestionID, question.Text, question.Image, question.PinID FROM question WHERE question.PinID = " . $parameter . ";";
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

        //echo var_dump($questions);

        return $questions;
    }

}

class GetAnswersByQuestionId extends Command
{

    public function getCommand()
    {
        return "GetAnswersByQuestionId";
    }

    public function execute($parameter)
    {
        $query = "SELECT answer.AnswerID, answer.RightWrong, answer.Text FROM answer WHERE answer.QuestionID = " . $parameter . ";";
        $result = query($query);

        $answers = array();

        while ($row = $result->fetch_assoc()) {
            $answer = new Answer();
            $answer->id = (int)$row['AnswerID'];
            $answer->rightWrong = (boolean)$row['RightWrong'];
            $answer->text = $row['Text'];

            array_push($answers, $answer);
        }

        //echo var_dump($answers);

        return $answers;
    }
}

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

class SetPinpoint extends Command
{

    public function getCommand()
    {
        return "SetPinpoint";
    }

    public function execute($parameter)
    {
        $pinpoint = json_decode($parameter);

        if (isset($pinpoint->id)) {
            $query = "UPDATE pinpoint SET TypeID = '" . $pinpoint->typeId . "', Name = '" . $pinpoint->name . "', Xpos = '" . $pinpoint->xPos . "', Ypos = '" . $pinpoint->yPos . "', Description = '" . $pinpoint->description . "' WHERE PinID = '" . $pinpoint->id . "';";
            $result = query($query);
        } else {
            $query = "INSERT INTO pinpoint (TypeID, Name, Xpos, Ypos, Description) VALUES ('" . $pinpoint->typeId . "', '" . $pinpoint->name . "', '" . $pinpoint->xPos . "', '" . $pinpoint->yPos . "', '" . $pinpoint->description . "');";
            $result = query($query);
        }

        return $result;
    }
}

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
            $query = "UPDATE type SET TypeID = '" . $type->id . "', Name = '" . $type->name . "', Unit = '" . $type->unit . "' WHERE PinID = '" . $pinpoint->id . "';";
            $result = query($query);
        } else {
            $query = "INSERT INTO type (TypeID, Name, Unit) VALUES ('" . $type->id . "', '" . $type->name . "', '" . $type->unit . "');";
            $result = query($query);
        }

        return $result;
    }
}

// Model objects

class Pinpoint
{

    public $id;
    public $name;
    public $description;
    public $xPos;
    public $yPos;
    public $typeId;

}

class Type
{

    public $id;
    public $name;
    public $unit;

}

class Question
{

    public $id;
    public $pinpointId;
    public $text;
    public $image;
    public $answers;

}

class Answer
{

    public $id;
    public $rightWrong;
    public $text;

}

// Utility functions

function query($query)
{
    global $mysqli;
    return $mysqli->query($query);
}