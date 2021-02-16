<?php
include("../BD/conexion.php");
require('auditoria.php');
session_start();     

$idprov= isset($_POST["hddidproveedor"])?$_POST["hddidproveedor"]:"NULL";
$rut= isset($_POST["txtrut"])?$_POST["txtrut"]:"0001";   // 
$razon_social= isset($_POST["txtrazon_social"])?$_POST["txtrazon_social"]:"";
$representante_legal= isset($_POST["txtrepresentante_legal"])?$_POST["txtrepresentante_legal"]:"";
$direccion= isset($_POST["txtdireccion"])?$_POST["txtdireccion"]:"NULL";     //
$fono= isset($_POST["txtfono"])?$_POST["txtfono"]:"NULL";   //
$email= isset($_POST["txtemail"])?$_POST["txtemail"]:"0";   //
$estatus_prov= isset($_POST["cboestatus_prov"])?$_POST["cboestatus_prov"]:"0";   //
$link=crearConexion(); 

$updateProv="UPDATE tbl_proveedores SET rif = '".$rut."', razon_social = '".$razon_social."', representante_legal = '".$representante_legal."', direccion = '".$direccion."',  fono = '".$fono."', email='".$email."', estatus_prov='".$estatus_prov."' WHERE idproveedor = ".$idprov;

    if ($link->query($updateProv) === TRUE){      

      $aud=auditar("Actualizacion del registro Proveedor: ".$razon_social, $_SESSION['user_session'],$link);
      $link->close();
      echo $idprov;
    }else{
       //el registro no existe
      echo("-1");
      //echo $link->error." \n ".$updateCli;
      $link->close();
      die("");
  }
?>