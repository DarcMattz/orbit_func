<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "project_orbit";

$conn = new mysqli($host, $user, $password, $db);

if (!$conn) {
    die("hindi ako maka connect");
}
