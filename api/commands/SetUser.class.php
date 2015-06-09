<?php

// Class: SetUser
//  Set a new user or edit an existing one
//
// Parameter: User object (with or without a set id)
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
    
    public function isEmailInDatabase($email, $userId)
    {
        if (isset($userId))
        {
            $query = query("SELECT Email FROM user WHERE Email = '" . $email . "' AND UserID = '" . $userId . "'");
            $result = $query->fetch_assoc();
            if($query->num_rows > 0)
            {
                //if there's a match
                return false;
            }
            else
            {
                //no match
                return true;
            }
        }
        else
        {
            $query = query("SELECT Email FROM user WHERE Email = '" . $email . "'");
            $result = $query->fetch_assoc();
            
            if($query->num_rows > 0)
            {
                //if there's a match
                return true;
            }
            else
            {
                //no match
                return false;
            }
        }
    }

    public function execute($parameter)
    {
        $user = $parameter;
        
        if (isset($user->id))
        {
            if(!$this->isEmailInDatabase($user->email, $user->id))
            {
                $query = "UPDATE user SET Screenname = '$user->name', Email = '$user->email'";
                if (isset($user->oldPassword))
                {
                    $checkPasswordQuery = "SELECT Password FROM user WHERE UserID = '$user->id'";
                    $result = query($checkPasswordQuery);

                    if(password_verify($user->oldPassword, $result->fetch_assoc()['Password']))
                    {
                        if (isset($user->password) && $user->password != "")
                        {
                            $hashedPassword = password_hash($user->password, PASSWORD_DEFAULT);
                            $query .= ", Password = '$hashedPassword'";
                            $result = query($query);
                        }
                        $query .= " WHERE UserID = '$user->id';";
                        $successMessage = "Gebruiker is aangepast.";
                        $result = query($query);
                    } 
                    else
                    {
                        $this->errorMessage("Oude wachtwoord komt niet overeen!");
                    }
                }
                else
                {
                    $this->errorMessage("Oude wachtwoord komt niet overeen!");
                }
            }
            else
            {
                $this->errorMessage("Email bestaat al!");
            }
        }
        else
        {
            if(!$this->isEmailInDatabase($user->email, null))
            {
                $hashedPassword = password_hash($user->password, PASSWORD_DEFAULT);
                $query = "INSERT INTO user (Screenname, Email, Password) VALUES ('$user->name', '$user->email', '$hashedPassword');";
                $successMessage="Gebruiker is aangemaakt.";
                
                $result = query($query);

                if (!$result)
                {
                    $this->errorMessage("Er is iets fout gegaan.");
                }
            }
            else
            {
                $this->errorMessage("Email bestaat al!");
            }
        }
        
        successMessage($successMessage);
       
    }
}