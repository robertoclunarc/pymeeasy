<?php 
if (!isset($_SESSION))
	session_start();
function crearConexion(){
	$link= new mysqli("127.0.0.1","root","",$_SESSION['conexion']);
	if ($link->connect_errno) {
    	printf("Falló la conexión: %s\n", $link->connect_errno);
    	echo "Errno: " . $link->connect_errno . "\n";
    	exit();
	}
return $link;
}
?>