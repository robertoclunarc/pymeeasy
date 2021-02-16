<?php

include("../BD/conexion.php");

require('auditoria.php');

session_start();     

$idvendedor= isset($_POST["hddidvendedor"])?$_POST["hddidvendedor"]:"NULL";         

$rut= isset($_POST["txtrut"])?$_POST["txtrut"]:"0001";   // 

$nombres= isset($_POST["txtrazon_social"])?$_POST["txtrazon_social"]:"";   

$fono= isset($_POST["txtfono"])?$_POST["txtfono"]:"NULL";   //

$email= isset($_POST["txtemail"])?$_POST["txtemail"]:"0";   //

$estatus_vend= isset($_POST["cboestatus_vend"])?$_POST["cboestatus_vend"]:"0";   //

$link=crearConexion(); 

$updateVend="UPDATE tbl_vendedores SET rif = '".$rut."', nombres = '".$nombres."', fono = '".$fono."', email='".$email."', estatus_vend='".$estatus_vend."' WHERE idvendedor = ".$idvendedor;

    if ($link->query($updateVend) === TRUE){      

      $aud=auditar("Actualizacion del registro Vendedor: ".$nombres, $_SESSION['user_session'],$link);

      $link->close();

      echo $idvendedor;      

    }else{

       //el registro no existe

      echo("-1");

      //echo $link->error." \n ".$updateCli;

      $link->close();      

      die("");

  }

?>