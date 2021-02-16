<?php
include("BD/conexion.php");
session_start();
$link=crearConexion();
$user= isset($_GET["user"])?$_GET["user"]:"";
$query = "select * from tbl_usuarios where login='" . $user . "' and estatus_user='ACTIVO'";	
$result = $link->query($query);
if ($result->num_rows > 0)	
{	
	$fila = $result->fetch_assoc();			
	
	echo "$(\"#pregunta_secreta_1\").val(\"" . $fila["pregunta_secreta_1"] . "\");\n";
	echo "$(\"#pregunta_secreta_2\").val(\"" . $fila["pregunta_secreta_2"] . "\");\n";
}
else
	echo "0";
$result->free();
$link->close();
?>