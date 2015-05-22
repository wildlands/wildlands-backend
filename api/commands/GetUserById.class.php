<?php

// TODO Write summary comment
class GetUserById extends Command
{

    public function getCommand()
    {
        return "GetUserById";
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

        $query = "SELECT * FROM user WHERE UserID = '" . $user->id . "';";
        $result = query($query);

        $row = $result->fetch_assoc();

        if (!$row)
        {
            $this->errorMessage("Kon geen user met UserID '" . $user->id . "' vinden.");
        }

        $user = new User();
        $user->id = (int) $row['UserID'];
        $user->name = $row['Screenname'];
        $user->email = $row['Email'];

        return $user;
    }
}

?>