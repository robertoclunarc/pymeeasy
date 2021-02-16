<?php
include("../BD/conexion.php");
require('auditoria.php');
if (!isset($_SESSION))
  session_start();     
  
$idventa= isset($_POST["hddidventa"])?$_POST["hddidventa"]:"NULL";   //
$vista= isset($_GET["vista"])?$_GET["vista"]:"NULL";   //
$restante= isset($_POST["txtrestante"])?$_POST["txtrestante"]:"NULL";   //

$tabla="tbl_abonos";
if ($vista=='vw_notas_entregas')
  $tabla="tbl_pagos_notas_entregas";

$link=crearConexion();

if(array_key_exists('modalidad_pago',$_POST))
{
    $modalidades_pagos = $_POST['modalidad_pago'];          
    $nros_referencias = $_POST['nro_referencia'];
    $montos = $_POST['monto'];
    
    foreach ($modalidades_pagos as $i => $modpag)
    {
      $nro_referencia = $nros_referencias[$i];
      $monto=$montos[$i];           
      
      $insertDet = "INSERT INTO ".$tabla." (";
      $insertDet .= "fkventa,";
      $insertDet .= "monto,";
      $insertDet .= "modalidad_pago,";
      $insertDet .= "nro_referencia,";             
      $insertDet .= "fecha,";
      $insertDet .= "estatus_abono";
      $insertDet .= ") VALUES (";
      $insertDet .= $idventa.",";
      $insertDet .= "'".$monto."',";
      $insertDet .= "'".$modpag."',";
      $insertDet .= "'".$nro_referencia."',";
      $insertDet .= "NOW(),";
      $insertDet .= "'PAGADO'";            
      $insertDet .= "); ";

      if ($link->query($insertDet) === TRUE){
         if ($restante==0)
         {
            $insertDet = "UPDATE tbl_notas_entrega SET estatus_nota='PAGADA' WHERE idnota=".$idventa;
            $link->query($insertDet);
         }

      } else {
          $error=$link->error;
          $link->close();      
          die($error.' ('.$insertDet.')');
      }

    }
    $link->close();
    echo $idventa;                    
}else {
  $link->close();
  echo "0";
}  
?>