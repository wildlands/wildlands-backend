<?php

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

    public function isAuthNeeded()
    {
        return true;
    }

    public function execute($parameter)
    {
        $question = $parameter;

        if (isset($question->id)) {
            $query = "UPDATE question SET Text = '$question->text', Image = '$question->image', TypeID = '$question->typeId' WHERE QuestionID = '$question->id';";
            $questionUpdateResult = query($query);
            
            $query = "SELECT AnswerID FROM answer WHERE QuestionID = '$question->id';";
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
                    $query = "DELETE FROM answer WHERE AnswerID = '$answerId';";
                    query($query);
                }
            }
        } else {
            $query = "INSERT INTO question (Text, Image, TypeID) VALUES ('$question->text', '$question->image', '$question->typeId');";
            $questionUpdateResult = query($query);
            $question->id = getInsertId();
        }

        foreach ($question->answers as $answer) {
            if (isset($answer->id)) {
                $query = "UPDATE answer SET RightWrong = '$answer->rightWrong', Text = '$answer->text', QuestionID = '$question->id' WHERE AnswerID = '$answer->id';";
                query($query);
            } else {
                $query = "INSERT INTO answer (RightWrong, Text, QuestionID) VALUES ('$answer->rightWrong', '$answer->text', '$question->id');";
                query($query);
            }
        }

        if (!$questionUpdateResult) {
            errorMessage("Er is iets fout gegaan.");
        }

        successMessage("Vraag is veranderd.");
    }

}

?>