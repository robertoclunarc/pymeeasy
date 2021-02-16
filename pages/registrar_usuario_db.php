<?php
include("../BD/conexion.php");
require('auditoria.php');
session_start();     
         
$login= isset($_POST["txtuser"])?$_POST["txtuser"]:"";   // 
$passw= isset($_POST["txtpassword"])?$_POST["txtpassword"]:"";   // 
$user_name= isset($_POST["hddnombre"])?$_POST["hddnombre"]:"NULL";   // 
$nivel= isset($_POST["txtnivel"])?$_POST["txtnivel"]:"NULL";  // 
$email= isset($_POST["txtmail"])?$_POST["txtmail"]:"NULL";     
$pregunta_secreta_1= isset($_POST["txtpregunt1"])?$_POST["txtpregunt1"]:"";   //
$respuesta_secreta_1= isset($_POST["txtresp1"])?$_POST["txtresp1"]:"";     // 
$pregunta_secreta_2= isset($_POST["txtpregunt2"])?$_POST["txtpregunt2"]:"";   //
$respuesta_secreta_2= isset($_POST["txtresp2"])?$_POST["txtresp2"]:"";   //
$estatus_user= isset($_POST["estatus"])?$_POST["estatus"]:"NULL";   //
$fktrabajador= isset($_POST["cbonombre"])?$_POST["cbonombre"]:"NULL";   //

$link=crearConexion(); 
$insertaruser="INSERT INTO tbl_usuarios (";
$insertaruser.="login, ";
$insertaruser.="passw, ";
$insertaruser.="user_name, ";
$insertaruser.="nivel, ";
$insertaruser.="email, ";
$insertaruser.="pregunta_secreta_1, ";
$insertaruser.="pregunta_secreta_2, ";
$insertaruser.="respuesta_secreta_1, ";
$insertaruser.="respuesta_secreta_2, ";
$insertaruser.="estatus_user,";
$insertaruser.="fktrabajador";
$insertaruser.=") VALUES (";
$insertaruser.="'".$login."', ";
$insertaruser.="MD5('".$passw."'), ";
$insertaruser.="'".$user_name."', ";
$insertaruser.="'".$nivel."', ";
$insertaruser.="'".$email."', ";
$insertaruser.="'".$pregunta_secreta_1."', ";
$insertaruser.="'".$pregunta_secreta_2."', ";
$insertaruser.="'".$respuesta_secreta_1."', ";
$insertaruser.="'".$respuesta_secreta_2."', ";
$insertaruser.="'".$estatus_user."', ";
$insertaruser.=$fktrabajador;
$insertaruser.=")";
if ($link->query($insertaruser) === TRUE){      
  $aud=auditar("Registro de Nuevo usuario: ".$user_name, $_SESSION['user_session'],$link);
  //Inserta el registro de vehiculo
  $link->close();
  echo("1");    
}else{       
  echo("-1");
  //echo($query);
  $link->close();      
  die($insertaruser);
}
?>