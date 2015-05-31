<?php

// Class: DeleteAnswer
//  Delete an answer
//
// Parameter: Answer object with a set id
class DeleteAnswer extends Command
{
	
	public function getCommand()
	{
		return "DeleteAnswer";
	}

	public function isAuthNeeded()
	{
		return true;
	}
	
	public function execute($parameter)
	{
		$answer = $parameter;
		
		if (!isset($answer->id))
		{
			$this->errorMessage("Geen AnswerID gevonden.");
		}
		
		$query = "DELETE FROM answer WHERE AnswerID = '$answer->id';";
		$result = query($query);
		
		if (!$result)
		{
			$this->errorMessage("Er is iets fout gegaan.");
		}
		
		successMessage("Antwoord is verwijderd.");
	}
	
}