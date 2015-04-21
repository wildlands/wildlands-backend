<?php
/**
 * User: INF2A
 * Date: 17.03.2015
 */

require_once('globals.php');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);

if($mysqli->connect_error)
{
    die($mysqli->connect_error);
}