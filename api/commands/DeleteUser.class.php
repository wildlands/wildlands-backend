<?php

// Class: DeleteUser
//  Delete a user
//
// Parameter: User object with a set id
class DeleteUser extends Command
{

    public function getCommand()
    {
        return "DeleteUser";
    }

    public function isAuthNeeded()
    {
        return true;
    }

    public function execute($parameter)
    {
        $user = $parameter;

        if (!isset($user->id))
        {
            $this->errorMessage("Geen UserID gevonden.");
        }

        $query = "DELETE FROM user WHERE UserID = '" . $user->id . "';";
        $result = query($query);

        if (!$result)
        {
            $this->errorMessage("Er is iets fout gegaan.");
        }

        successMessage("Gebruiker is verwijderd.");
    }
}