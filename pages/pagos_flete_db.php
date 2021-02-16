<?php
include("../BD/conexion.php");
require('auditoria.php');
session_start();     
 
$fkpedido= isset($_POST["hddfkpedido"])?$_POST["hddfkpedido"]:"NULL";   // 
$contador= isset($_POST["hddcontador"])?$_POST["hddcontador"]:"NULL";   //
$transportista= isset($_POST["txtTransportista"])?$_POST["txtTransportista"]:"NULL";   // 
$costo= isset($_POST["txtcosto"])?$_POST["txtcosto"]:"NULL";   // 
$link=crearConexion();
 

$InsertFlete="INSERT INTO tbl_fletes (";
$InsertFlete.="fecha, ";
$InsertFlete.="transportista, ";
$InsertFlete.="fkpedido, ";
$InsertFlete.="costo, ";
$InsertFlete.="estatus_flete ";
$InsertFlete.=") VALUES (";
$InsertFlete.="NOW(),";
$InsertFlete.="'".$transportista."', ";
$InsertFlete.=$fkpedido.", ";
$InsertFlete.="'".$costo."', ";
$InsertFlete.="'PAGADO'";
$InsertFlete.=")";

    if ($link->query($InsertFlete) === TRUE){        
        $idflete=$link->insert_id;
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
            $InsertPago .= $idflete.", ";
            $InsertPago .= "'FLETE', ";          
            $InsertPago .= "'".$monto."', ";  
            $InsertPago .= "'".$modpago."', ";              
            $InsertPago .= "NOW(), ";  
            $InsertPago .= "'".$nro_referencia."', ";
            $InsertPago .= "'".$banco."', ";  
            $InsertPago .= "'PAGADO' ";  
            $InsertPago .= ");";            

            if ($link->query($InsertPago) === TRUE)           
                $aud=auditar("Pago de Flete: ".$fkpedido, $_SESSION['user_session'],$link);
            else{
               echo("-1");
               die($InsertPago);
            }    
          }
         $link->close();
          echo $idflete; 
        }
      
     //print_r($iddetalles_pedidos);
    }else{
       //el registro no existe
      echo("-1");
      echo($InsertFlete);
      $link->close();      
      die("");
  }
?>