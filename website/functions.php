<?php
require_once('../globals.php');
require_once('../database.php');
session_start();

//$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);

function generateSessionHash($userID)
{
	return $userID;
}	

/**
 * De UserID ophalen uit een bepaalde hash
 *
 * @param string		Hash
 * @return int			De user id
 */
function getUserID($hash)
{
	global $mysqli;
	
	$query = $mysqli->query("SELECT `UserID` FROM `session` WHERE Hash='" . $hash . "' LIMIT 1");
	$fetch = $query->fetch_assoc();
	
	return $fetch['UserID'];
}

/**
 * Checken of een gebruiker is ingelogd.
 *
 * @return boolean			True als gebruiker is ingelogd
 */
function isLoggedIn()
{
	global $mysqli;
	
	// Checken of er een hash gevonden is in de sessie
	if (isset($_SESSION['hash']))
	{
		// Checken of de hash ook in de session table staat
		$query = $mysqli->query("SELECT `UserID` FROM `session` WHERE Hash='" . $_SESSION['hash'] . "' LIMIT 1");
		// Hash is niet gevonden...
		if ($query->num_rows == 0)
		{
			return false;
		}
		// Hash is gevonden!
		else
		{
			return true;
		}
	}
	else
	{
		return false;
	}
	
}

/**
 * Haal de schermnaam op van de ingelogde gebruiker
 *
 * @return string		De schermnaam
 */
function getScreenName()
{
	
	global $mysqli;
	
	// UserID ophalen uit de session hash
	$userID = getUserID($_SESSION['hash']);
	$query = $mysqli->query("SELECT `Screenname` FROM `user` WHERE UserID=" . $userID . " LIMIT 1");
	$fetch = $query->fetch_assoc();
	
	return $fetch['Screenname'];
}

/**
 * De gebruiker uitloggen
 */
function logout()
{
	
	unset($_SESSION['hash']);
	session_destroy();
	// Terug sturen naar de inlogpagina
	header('Location: login.php');
	
}
	
?>