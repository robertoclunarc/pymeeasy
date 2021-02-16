<?php
include("BD/conexion.php");
require('pages/auditoria.php');
session_start();
$link=crearConexion();
$user= isset($_POST["user"])?$_POST["user"]:"";

$respuesta_secreta_1= isset($_POST["respuesta_secreta_1"])?$_POST["respuesta_secreta_1"]:"";

$respuesta_secreta_2= isset($_POST["respuesta_secreta_2"])?$_POST["respuesta_secreta_2"]:"";

$password= isset($_POST["password"])?$_POST["password"]:"";

$query = "select * from tbl_usuarios where login='" . $user . "' and estatus_user='ACTIVO' and respuesta_secreta_1='".$respuesta_secreta_1."' and respuesta_secreta_2 = '".$respuesta_secreta_2."'";	

$result = $link->query($query);
if ($result->num_rows > 0)	
{	
	$update="UPDATE tbl_usuarios SET ";
	$update.="passw=MD5('".$password."') ";
	$update.="WHERE login='".$user."' ";
	$update.="AND respuesta_secreta_1='".$respuesta_secreta_1."' ";
	$update.="AND respuesta_secreta_2='".$respuesta_secreta_2."' ";
	$update.="AND estatus_user='ACTIVO';";

	if ($link->query($update) === TRUE){  
  		$aud=auditar("Actualizacion del password", $user, $link);  		
  		echo "1";      
	}else{   		
 		 echo("-1");
  		//echo($query);
  		$link->close();      
  		die($update.$link->error);
	}
}
else
	echo "0";
$result->free();
$link->close();
?>