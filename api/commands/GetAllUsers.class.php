<?php

// TODO Write summary comment
class GetAllUsers extends Command
{

    public function getCommand()
    {
        return "GetAllUsers";
    }

    public function execute($parameter)
    {
        $query = "SELECT * FROM user;";
        $result = query($query);

        $users = array();

        while ($row = $result->fetch_assoc())
        {
            $user = new User();
            $user->id = $row['UserID'];
            $user->name = $row['Screenname'];
            $user->email = $row['Email'];

            array_push($users, $user);
        }

        return $users;
    }
}

?>