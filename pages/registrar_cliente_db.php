<?php
include("../BD/conexion.php");
require('auditoria.php');
if (!isset($_SESSION))
  session_start();         

$rut= isset($_POST["txtrut"])?$_POST["txtrut"]:"0001";   // 
$razon_social= isset($_POST["txtrazon_social"])?$_POST["txtrazon_social"]:"";
$representante_legal= isset($_POST["txtrepresentante_legal"])?$_POST["txtrepresentante_legal"]:"";   //
$direccion= isset($_POST["txtdireccion"])?$_POST["txtdireccion"]:"NULL";     //
$fono= isset($_POST["txtfono"])?$_POST["txtfono"]:"NULL";   //
$email= isset($_POST["txtemail"])?$_POST["txtemail"]:"NULL";   //

$link=crearConexion(); 

$insertarCli="INSERT INTO tbl_clientes(rif, razon_social,  representante_legal, direccion, fono, email) VALUES ('".$rut."', '".$razon_social."', '".$representante_legal."', '".$direccion."', '".$fono."', '".$email."')";

    if ($link->query($insertarCli) === TRUE){
      $idclie=$link->insert_id;
      $aud=auditar("Registro de Nuevo Cliente: ".$razon_social, $_SESSION['user_session'],$link);
      $link->close();
      echo $idclie;
    }else{
       //el registro no existe
      echo("-1");
      //echo($query);
      $link->close();
      die("");
    }
?>