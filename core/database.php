<?php

$env = parse_ini_file('.env');
$host = $env["HOST"];
$username = $env["USERNAME"];
$password = $env["PASSWORD"];
$name = $env["NAME"];


$dbc = new mysqli($host, $username, $password, $name);

if ($dbc === false){
    echo "Errore di connessione: ". $dbc->error;
}