<?php
include("../BD/conexion.php");
require('auditoria.php');
session_start();

$fkproducto= isset($_POST["hddidproducto"])?$_POST["hddidproducto"]:"";
$cantidad_unidades= isset($_POST["txtcantidad_unitaria"])?$_POST["txtcantidad_unitaria"]:"";
$cantidad_blt= isset($_POST["txtcantidad_blt"])?$_POST["txtcantidad_blt"]:"";
$cant_unidades_en_blt= isset($_POST["txtcant_unidades_en_blt"])?$_POST["txtcant_unidades_en_blt"]:"";
$observacion= isset($_POST["txtobservacion"])?$_POST["txtobservacion"]:"";

$link=crearConexion(); 

$insertarProd="INSERT INTO tbl_cierres_diarios_inv( fecha,";
$insertarProd.="fkproducto, ";
$insertarProd.="cantidad_unidades ,";
$insertarProd.="cantidad_blt, ";
$insertarProd.="cant_unidades_en_blt, ";
$insertarProd.="observacion ,";
$insertarProd.="responsable, ";
$insertarProd.="estatus_cierre ";
$insertarProd.=") VALUES ( NOW(), ";
$insertarProd.=$fkproducto.", ";
$insertarProd.=$cantidad_unidades.", ";
$insertarProd.=$cantidad_blt.", ";
$insertarProd.=$cant_unidades_en_blt.", ";
$insertarProd.="'".$observacion."', ";
$insertarProd.="'".$_SESSION['user_session']."', ";
$insertarProd.="'CERRADO' ";
$insertarProd.=");";    

if ($link->query($insertarProd) === TRUE){
  $aud=auditar("Registro de Cierre de Inventario: Producto Id:".$fkproducto.'. '.$observacion, $_SESSION['user_session'],$link);
  $link->close();
  echo $fkproducto;
}else{
   //el registro no existe
  echo("-1".$insertarProd.$link->error);
  //echo($query);
  $link->close(); 
  die("");
}
?>