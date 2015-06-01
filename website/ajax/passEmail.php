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
	$email = $mysqli->real_escape_string($_POST['email']);
	
	// Gegevens van de gebruikersnaam ophalen
	$query = $mysqli->query("SELECT `Email` FROM `user` WHERE Email='" . $email . "' LIMIT 1");
	$fetch = $query->fetch_assoc();
	
	// Checken of er een gebruiker is gevonden
	if ($query->num_rows == 0)
	{
		$message['code'] = 0;
		$message['message'] = 'Email is niet gevonden.';
	}
	else
	{
            // Unieke hash maken voor deze sessie
            $hash = @crypt(uniqid() . SALT);
            
            // Hash opslaan in de spass_recovery table
            $mysqli->query("INSERT INTO pass_recovery (Email, Random_Hash) VALUES ('" . $fetch['Email'] . "', '" . $hash . "')");

            $message['code'] = 1;
            $message['message'] = '';
            
            $emailTo = $fetch['Email'];
            $subject = 'Wachtwoord vergeten';
            $body = "Geachte " . $fetch['Screenname'] . ",\n " . BASE_URL . "changePass.php?e=". $fetch['Email'] ."&h=" . $hash . "\n" . "Door middel van deze link kunt u uw wachtwoord opnieuw instellen.";

            mail($emailTo, $subject, $body);
	}
	
	// JSON bericht uitpringen voor de AJAX request
	echo json_encode($message);
	
}

?>