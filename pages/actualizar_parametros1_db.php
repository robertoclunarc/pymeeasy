<?php
include("../BD/conexion.php");
require('auditoria.php');
session_start();

$iva= isset($_POST["txtiva"])?$_POST["txtiva"]:"NULL";
$nombre_empresa= isset($_POST["txtnombre_empresa"])?$_POST["txtnombre_empresa"]:"0001";   //
$rif= isset($_POST["txtrut"])?$_POST["txtrut"]:"";
$direccion= isset($_POST["txtdireccion"])?$_POST["txtdireccion"]:"";   //
$ciudad= isset($_POST["txtciudad"])?$_POST["txtciudad"]:"NULL";     // 
$fono= isset($_POST["txtfono"])?$_POST["txtfono"]:"NULL";   //
$region= isset($_POST["txtregion"])?$_POST["txtregion"]:"0";   //
$nombre_encargado= isset($_POST["txtnombre_encargado"])?$_POST["txtnombre_encargado"]:"0";   //
$porc_ganancia= isset($_POST["txtporc_ganancia"])?$_POST["txtporc_ganancia"]:"0";   //

$link=crearConexion(); 

$updateParam="UPDATE tbl_parametros SET ";
$updateParam.= "rif = '".$rif."', ";
$updateParam.= "nombre_empresa = '".$nombre_empresa."', ";
$updateParam.= "region = '".$region."', ";
$updateParam.= "direccion = '".$direccion."', ";
$updateParam.= "fono = '".$fono."', ";
$updateParam.= "ciudad='".$ciudad."', ";
$updateParam.= "nombre_encargado='".$nombre_encargado."', ";
$updateParam.= "iva='".$iva."', ";
$updateParam.= "porc_ganancia='".$porc_ganancia."';";

    if ($link->query($updateParam) === TRUE){      

      $aud=auditar("Actualizacion de Datos de Empresa", $_SESSION['user_session'],$link);

      $link->close();

      echo "1";      

    }else{

       //el registro no existe

      echo("-1 ".$updateParam);

      //echo $link->error." \n ".$updateCli;

      $link->close();      

      die("");

  }

?>