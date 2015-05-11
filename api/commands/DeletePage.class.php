<?php

class DeletePage extends Command
{

    public function getCommand()
    {
        return "DeletePage";
    }

    public function isAuthNeeded()
    {
        return true;
    }

    public function execute($parameter)
    {
        $page = $parameter;

        if (!isset($page->id))
        {
            errorMessage("Geen PageID gevonden.");
        }

        $query = "DELETE FROM page WHERE PageID = '$page->id';";
        $result = query($query);

        if (!$result)
        {
            errorMessage("Er is iets fout gegaan");
        }

        successMessage("Pagina is verwijderd.");
    }

}

?>