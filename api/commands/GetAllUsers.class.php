<?php

// Class: GetAllUsers
//  Return all users from the database
//
//  Return: Array with 'Users' objects
class GetAllUsers extends Command
{

    public function getCommand()
    {
        return "GetAllUsers";
    }

    public function isAuthNeeded()
    {
        return true;
    }

    public function execute($parameter)
    {
        $query = "SELECT * FROM user;";
        $result = query($query);

        $users = array();

        while ($row = $result->fetch_assoc())
        {
            $user = (new GetUserById())->execute(new IdObject($row['UserID']));

            array_push($users, $user);
        }

        return $users;
    }
}