<?php
include("../BD/conexion.php");
require('auditoria.php');
session_start();         

$rut= isset($_POST["txtrut"])?$_POST["txtrut"]:"0001";   // 

$nombres= isset($_POST["txtrazon_social"])?$_POST["txtrazon_social"]:"";  // 

$fono= isset($_POST["txtfono"])?$_POST["txtfono"]:"NULL";   //

$email= isset($_POST["txtemail"])?$_POST["txtemail"]:"NULL";   //

$link=crearConexion(); 

$insertarVend="INSERT INTO tbl_vendedores(rif, nombres, fono, email) VALUES ('".$rut."', '".$nombres."', '".$fono."', '".$email."')";

    if ($link->query($insertarVend) === TRUE){

      $idvend=$link->insert_id;

      $aud=auditar("Registro de Nuevo Vendedor: ".$nombres, $_SESSION['user_session'],$link);

      $link->close();

      echo $idvend;      

    }else{
       //el registro no existe
      echo("-1");
      //echo($query);
      $link->close();
      die("");
    }
?>