<?php
include("../BD/conexion.php");
require('auditoria.php');
session_start();     

$idcliente= isset($_POST["hddidcliente"])?$_POST["hddidcliente"]:"NULL";
$rut= isset($_POST["txtrut"])?$_POST["txtrut"]:"0001";   // 
$razon_social= isset($_POST["txtrazon_social"])?$_POST["txtrazon_social"]:"";
$representante_legal= isset($_POST["txtrepresentante_legal"])?$_POST["txtrepresentante_legal"]:"";   //
$direccion= isset($_POST["txtdireccion"])?$_POST["txtdireccion"]:"NULL";     //
$fono= isset($_POST["txtfono"])?$_POST["txtfono"]:"NULL";   //
$email= isset($_POST["txtemail"])?$_POST["txtemail"]:"0";   //
$estatus_cliente= isset($_POST["cboestatus_cliente"])?$_POST["cboestatus_cliente"]:"0";   //
$link=crearConexion(); 

$updateCli="UPDATE tbl_clientes SET rif = '".$rut."', razon_social = '".$razon_social."', representante_legal = '".$representante_legal."', direccion = '".$direccion."',  fono = '".$fono."', email='".$email."', estatus_cliente='".$estatus_cliente."' WHERE idcliente = ".$idcliente;

    if ($link->query($updateCli) === TRUE){      

      $aud=auditar("Actualizacion del registro Cliente: ".$razon_social, $_SESSION['user_session'],$link);
      $link->close();
      echo $idcliente;
    }else{
       //el registro no existe
      echo("-1");
      //echo $link->error." \n ".$updateCli;
      $link->close();
      die("");
  }
?>