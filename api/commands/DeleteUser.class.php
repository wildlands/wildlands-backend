<?php

// TODO Write summary comment
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

?>