<?php
$servername= "localhost";
$username = "ds62025";
$password= "1234";
$database= "ds6";

//Se crea la conexión
$conexion = new mysqli($servername, $username, $password, $database);
//Verificar la conexión
if ($conexion->connect_error){
    die("Conexion Fallida: " . $conexion->connect_error);
}
?>