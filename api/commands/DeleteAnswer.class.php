<?php

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
			errorMessage("Geen AnswerID gevonden.");
		}
		
		$query = "DELETE FROM answer WHERE AnswerID = '$answer->id';";
		$result = query($query);
		
		if (!$result)
		{
			errorMessage("Er is iets fout gegaan.");
		}
		
		successMessage("Antwoord is verwijderd.");
	}
	
}
	
?>