<?php
include("../BD/conexion.php");
require('auditoria.php');
session_start();     
 
$idpedido= isset($_POST["hddidpedido"])?$_POST["hddidpedido"]:"NULL";   // 
$contador= isset($_POST["hddcontador"])?$_POST["hddcontador"]:"NULL";   // 
$estado= isset($_POST["cboestado"])?$_POST["cboestado"]:"NULL";   // 

$link=crearConexion();
 
/*
$UpdatePed="UPDATE tbl_pedidos SET ";
$UpdatePed.="estatus_pedido='".$estado."' ";
$UpdatePed.="WHERE idpedido=".$idpedido;

    if ($link->query($UpdatePed) === TRUE){        
        $aud=auditar("Actualizacion de Pedido: ".$idpedido, $_SESSION['user_session'],$link);
    */
        $i=0;
        if(array_key_exists('modalidad_pago',$_POST))
        {
          $modalidad_pagos = $_POST['modalidad_pago'];          
          $nro_referencias = $_POST['nro_referencia'];
          $bancos = $_POST['banco'];          
          $montos = $_POST['monto'];
          $InsertPago="";
          
          foreach ($modalidad_pagos as $i => $modpago)
          {
            $nro_referencia = $nro_referencias[$i];
            $banco = $bancos[$i];
            $monto=$montos[$i];
            
            $InsertPago = "INSERT INTO tbl_pagos (";
            $InsertPago .= "fkdeuda, ";
            $InsertPago .= "tipo_pago, ";
            $InsertPago .= "monto, ";
            $InsertPago .= "modalidad_pago, ";
            $InsertPago .= "fecha, ";
            $InsertPago .= "nro_referencia, ";
            $InsertPago .= "banco, ";
            $InsertPago .= "estatus_pago ";
            $InsertPago .= ") VALUES ( ";
            $InsertPago .= $idpedido.", ";
            $InsertPago .= "'PEDIDO', ";          
            $InsertPago .= "'".$monto."', ";  
            $InsertPago .= "'".$modpago."', ";              
            $InsertPago .= "NOW(), ";  
            $InsertPago .= "'".$nro_referencia."', ";
            $InsertPago .= "'".$banco."', ";  
            $InsertPago .= "'PAGADO' ";  
            $InsertPago .= ");";            

            if ($link->query($InsertPago) === TRUE)           
                $aud=auditar("Pago de Pedido: ".$idpedido, $_SESSION['user_session'],$link);
            else{
               echo("-1");
               die($InsertPago);
            }    
          }
         $link->close();
          echo $idpedido; 
        }
      
     //print_r($iddetalles_pedidos);
/*    }else{
       //el registro no existe
      echo("-1");
      echo($UpdatePed);
      $link->close();      
      die("");
  }*/
?>