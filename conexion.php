<?php
// conexion PDO a base de datos
$servername = "localhost";
$DBname = "municipios";
$username = "root";
$password = "";
$conn = new PDO("mysql:host=$servername;dbname=$DBname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);