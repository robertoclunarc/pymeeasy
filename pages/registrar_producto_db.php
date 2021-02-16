<?php
include("../BD/conexion.php");
require('auditoria.php');
if (!isset($_SESSION))
	session_start();

$descripcion_prod= isset($_POST["txtdescripcion_prod"])?$_POST["txtdescripcion_prod"]:"";   // 

$marca= isset($_POST["txtmarca"])?$_POST["txtmarca"]:"";

$cantidad_blt= isset($_POST["txtcantidad_blt"])?$_POST["txtcantidad_blt"]:"0";

$cant_unidades_en_blt= isset($_POST["txtcant_unidades_en_blt"])?$_POST["txtcant_unidades_en_blt"]:"0";

$excepto_iva= isset($_POST["chkexcepto_iva"])?$_POST["chkexcepto_iva"]:"N";

$cantidad_unitaria=$cantidad_blt*$cant_unidades_en_blt;

$link=crearConexion(); 

$insertarProd="INSERT INTO tbl_productos(";
$insertarProd.="descripcion_prod, ";
$insertarProd.="marca ,";
$insertarProd.="cantidad_blt, ";
$insertarProd.="cant_unidades_en_blt ,";
$insertarProd.="cantidad_unitaria, ";
$insertarProd.="estatus_prod, ";
$insertarProd.="excepto_iva ";
$insertarProd.=") VALUES (";
$insertarProd.="'".$descripcion_prod."', ";
$insertarProd.="'".$marca."', ";
$insertarProd.="".$cantidad_blt.", ";
$insertarProd.="".$cant_unidades_en_blt.", ";
$insertarProd.="".$cantidad_unitaria.", ";
$insertarProd.="'ACTIVO', ";
$insertarProd.="'".$excepto_iva."' ";
$insertarProd.=");";    

if ($link->query($insertarProd) === TRUE){

  $idprod=$link->insert_id;

  $aud=auditar("Registro de producto: ".$descripcion_prod, $_SESSION['user_session'],$link); 

  $link->close();

  echo $idprod;      

}else{

   //el registro no existe

  echo("-1".$insertarProd.$link->error);

  //echo($query);

  $link->close();      

  die("");

}

?>