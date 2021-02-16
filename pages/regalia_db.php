<?php
include("../BD/conexion.php");
require('auditoria.php');
session_start();     
 
$fkpedido= isset($_POST["hddfkpedido"])?$_POST["hddfkpedido"]:"NULL";   // 
$fecha= isset($_POST["txtfecha"])?$_POST["txtfecha"]:"NULL";   // 
$beneficiario= isset($_POST["txtbeneficiario"])?$_POST["txtbeneficiario"]:"NULL";   // 
$responsable= isset($_POST["txtresponsable"])?$_POST["txtresponsable"]:"NULL";   // 
$observacion= isset($_POST["txtobservacion"])?$_POST["txtobservacion"]:"NULL";   // 
$fkpedido= isset($_POST["hddfkpedido"])?$_POST["hddfkpedido"]:"NULL";   // 

$link=crearConexion();

$insertDet=""; 
        $i=0;
        if(array_key_exists('hddfkproducto',$_POST))
        {
          $fkproductos = $_POST['hddfkproducto'];          
          $cantidades = $_POST['cant'];
          $medidas = $_POST['medida'];

          foreach ($fkproductos as $i => $idprod)
          {
            $cantidad = $cantidades[$i];
            $med=$medidas[$i];       
            
            $insertDet = "INSERT INTO tbl_regalias (";
            $insertDet .= "fkpedido,";
            $insertDet .= "fecha,";
            $insertDet .= "beneficiario,";
            $insertDet .= "responsable,";
            $insertDet .= "observacion,";
            $insertDet .= "fkproducto,";
            $insertDet .= "cantidad,";
            $insertDet .= "medicion,"; 
            $insertDet .= "estatus_reg";            
            $insertDet .= ") VALUES (";
            $insertDet .= $fkpedido.",";
            $insertDet .= "'".$fecha."',";
            $insertDet .= "'".$beneficiario."',";
            $insertDet .= "'".$responsable."',";
            $insertDet .= "'".$observacion."',";            
            $insertDet .= $idprod.",";
            $insertDet .= $cantidad.",";
            $insertDet .= "'".$med."',";
            $insertDet .= "'ENTREGADO'";            
            $insertDet .= "); ";

            if ($link->query($insertDet) === TRUE){
              $idregalia=$link->insert_id;
              $aud=auditar("Registro de Regalia: ".$idregalia, $_SESSION['user_session'],$link);
            } else {                
                $error=$link->error;
                $link->close();      
                die($error.' ('.$insertDet.')');
            }
          }                    
        }
      $link->close();
      echo $fkpedido;
?>