<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "usuarios";

$mysqli = new mysqli("localhost", "root", "", "usuarios");


if ($mysqli->connect_error) {
    die("Erro na conexão: " . $mysqli->connect_error);
}

?>
