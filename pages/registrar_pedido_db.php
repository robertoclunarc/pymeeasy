<?php
include("../BD/conexion.php");
require('auditoria.php');
session_start();     
 
$fkproveedor= isset($_POST["hddfkproveedor"])?$_POST["hddfkproveedor"]:"NULL";   // 

$link=crearConexion();

$insertarVen="";
$insertDet="";
 
$insertarPed="INSERT INTO tbl_pedidos (";
$insertarPed.="fecha_pedido,";
$insertarPed.="fkproveedor";
$insertarPed.=") VALUES (";
$insertarPed.="NOW(),";
$insertarPed.=$fkproveedor;
$insertarPed.=");";
    if ($link->query($insertarPed) === TRUE){
        $idPed=$link->insert_id;
        $aud=auditar("Registro de Pedido: ".$idPed, $_SESSION['user_session'],$link);
        //Inserta el registro de detalle venta
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
            
            $insertDet = "INSERT INTO tbl_detalles_pedidos (";
            $insertDet .= "fkpedido,";
            $insertDet .= "fkproducto,";
            $insertDet .= "cantidad,";
            $insertDet .= "medida,"; 
            $insertDet .= "estatus_det_pedido";            
            $insertDet .= ") VALUES (";
            $insertDet .= $idPed.",";
            $insertDet .= $idprod.",";
            $insertDet .= $cantidad.",";
            $insertDet .= "'".$med."',";
            $insertDet .= "'EN ESPERA'";            
            $insertDet .= "); ";

            if ($link->query($insertDet) === TRUE){
              $idDetPed=$link->insert_id;
            } else {
                $resul=$link->query("DELETE FROM tbl_pedidos WHERE idpedido=".$idPed);
                $error=$link->error;
                $link->close();      
                die($error.' ('.$insertDet.')');
            }

          }
                    
        }
      $link->close();
      echo $idPed;
    }else{
       //el registro no existe
      echo("-1");
      echo($insertarPed." / ".$insertDet);
      $link->close();      
      die("");
  }
?>