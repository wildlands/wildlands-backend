<?php

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
            $successMessage = "Gebruiker is aangepast.";
        }
        else
        {
            $query = "INSERT INTO user (Screenname, Email, Password) VALUES ('" . $user->name . "', '" . $user->email . "', '" . $user->password . "');";
            $successMessage = "Gebruiker is aangemaakt.";
        }

        $result = query($query);

        if (!$result)
        {
            errorMessage("Er is iets fout gegaan.");
        }

        successMessage($successMessage);
    }
}

?>