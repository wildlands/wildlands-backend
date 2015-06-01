<?php

// Class: SetPassword
//  Set a new password
//
// Parameter: Password and email
class SetPassword extends Command
{

    public function getCommand()
    {
        return "SetPassword";
    }

    public function isAuthNeeded()
    {
        return false;
    }

    public function execute($parameter)
    {
        $pass = $parameter;
        
        $hashedPassword = password_hash($pass->password, PASSWORD_DEFAULT);

        $query = "UPDATE user SET Password = '$hashedPassword' WHERE Email = '$pass->email'";

        $result = query($query);

        if (!$result)
        {
            $this->errorMessage("Er is iets fout gegaan.");
        }
        else
        {
            //verwijderen van eerdere hash entry
            $query = "DELETE FROM pass_recovery WHERE Email = '$pass->email'";
            
            $result = query($query);
            
            $successMessage = "Paswoord is aangepast.";
        }

        successMessage($successMessage);
       
    }
}