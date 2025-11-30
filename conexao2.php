<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "loja";

$mysqli = new mysqli("localhost", "root", "", "loja");


if ($mysqli->connect_error) {
    die("Erro na conexão: " . $mysqli->connect_error);
}
?>
