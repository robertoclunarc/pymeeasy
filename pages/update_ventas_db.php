<?php
include("../BD/conexion.php");
require('auditoria.php');
if (!isset($_SESSION))
  session_start();     
  
$idventa= isset($_POST["hddidventa"])?$_POST["hddidventa"]:"NULL";   //
$subtotal= isset($_POST["txtsubTotal"])?$_POST["txtsubTotal"]:"NULL";       //
$total_neto= isset($_POST["txtTotal"])?$_POST["txtTotal"]:"NULL";          //
$excento= isset($_POST["txtexcento"])?$_POST["txtexcento"]:"NULL";        //
$estatus_venta= isset($_POST["cboestatus_venta"])?$_POST["cboestatus_venta"]:"NULL";        //
$total_iva= isset($_POST["txttotaliva"])?$_POST["txttotaliva"]:"0";        //
$link=crearConexion();

$updateVen=""; 
$updateVen="UPDATE tbl_ventas SET ";
$updateVen.="subtotal='".$subtotal."', ";
$updateVen.="total_neto='".$total_neto."', ";
$updateVen.="excento='".$excento."', ";
$updateVen.="estatus_venta='".$estatus_venta."', ";
$updateVen.="totaliva='".$total_iva."' ";
$updateVen.="WHERE idventa=".$idventa;
    if ($link->query($updateVen) === TRUE){
        $aud=auditar("Actualizacion de Venta: ".$idventa, $_SESSION['user_session'],$link);
        //Inserta el registro de detalle venta
        
      $link->close();
      echo $idventa;
    }else{
       //el registro no existe
      echo("-1");
      echo($updateVen);
      $link->close();      
      die("");
  }
?>