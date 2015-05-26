<?php

class SetUser extends Command
{

    public function getCommand()
    {
        return "SetUser";
    }

    public function isAuthNeeded()
    {
        return true;
    }

    public function execute($parameter)
    {
        $user = $parameter;

        if (isset($user->id))
        {
            $query = "UPDATE user SET Screenname = '$user->name', Email = '$user->email'";
            if (isset($user->password))
            {
                $hashpass = password_hash($user->password, PASSWORD_DEFAULT);
                $query .= ", Password = '$hashpass'";
            }
            $query .= " WHERE UserID = '$user->id';";
            $successMessage = "Gebruiker is aangepast.";
        }
        else
        {
            $hashpass = password_hash($user->password, PASSWORD_DEFAULT);
            $query = "INSERT INTO user (Screenname, Email, Password) VALUES ('$user->name', '$user->email', '$hashpass');";
            $successMessage = "Gebruiker is aangemaakt.";
        }

        $result = query($query);

        if (!$result)
        {
            $this->errorMessage("Er is iets fout gegaan.");
        }

        successMessage($successMessage);
    }
}

?>