<?php
session_start();
require('../../database.php');


// Headers om een JSON response te genereren
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

$message = array();

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
if ($mysqli->connect_error)
{
	echo json_encode(array("code" => 0, "message" => "Kon niet verbinden met database."));
}
else
{
	// Tegen SQL hacking
	$username = $mysqli->real_escape_string($_POST['username']);
	$password = $mysqli->real_escape_string($_POST['password']);
	
	// Gegevens van de gebruikersnaam ophalen
	$query = $mysqli->query("SELECT `UserID`, `Password` FROM `user` WHERE Email='" . $username . "' LIMIT 1");
	$fetch = $query->fetch_assoc();
	
	// Checken of er een gebruiker is gevonden
	if ($query->num_rows == 0)
	{
		$message['code'] = 0;
		$message['message'] = 'Gebruiker is niet gevonden.';
	}
	else
	{
		// Password controleren
		if (password_verify($password, $fetch['Password']))
		{
			// Alle sessies van de gebruiker verwijderen
			$mysqli->query("DELETE FROM session WHERE UserID=" . $fetch['UserID']);
			// Unieke hash maken voor deze sessie
			$hash = @crypt($_SERVER['HTTP_USER_AGENT'] . uniqid() . SALT);
			// Hash opslaan in de session table
			$mysqli->query("INSERT INTO session (UserID, Hash) VALUES ('" . $fetch['UserID'] . "', '" . $hash . "')");
			
			$message['code'] = 1;
			$message['message'] = '';
			$_SESSION['hash'] = $hash;
		}
		// Oei, wachtwoord klopt niet
		else
		{
			$message['code'] = 0;
			$message['message'] = 'Wachtwoord klopt niet.';
		}
	}
	
	// JSON bericht uitpringen voor de AJAX request
	echo json_encode($message);
	
}

?>