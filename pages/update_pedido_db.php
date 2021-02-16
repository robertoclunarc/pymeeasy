<?php
include("../BD/conexion.php");
require('auditoria.php');
session_start();     
 
$idpedido= isset($_POST["hddidpedido"])?$_POST["hddidpedido"]:"NULL";   // 
$contador= isset($_POST["hddcontador"])?$_POST["hddcontador"]:"NULL";   // 
$forma_pago= isset($_POST["cboforma_pago"])?$_POST["cboforma_pago"]:"";   // 
$nro_operacion= isset($_POST["txtnro_operacion"])?$_POST["txtnro_operacion"]:"";   // 
$estado= isset($_POST["cboestado"])?$_POST["cboestado"]:"NULL";   // 
$iva= isset($_POST["txtivaporcentaje"])?$_POST["txtivaporcentaje"]:"NULL";   // 
$subtotal= isset($_POST["txtsubtotal_sum"])?$_POST["txtsubtotal_sum"]:"NULL";   //
$Total= isset($_POST["txtTotal"])?$_POST["txtTotal"]:"NULL";   // 

$link=crearConexion();

$insertarVen="";
$insertDet="";
 
$UpdatePed="UPDATE tbl_pedidos SET ";
$UpdatePed.="fecha_llegada=NOW(),";
$UpdatePed.="forma_pago='".$forma_pago."',";
$UpdatePed.="nro_operacion='".$nro_operacion."',";
$UpdatePed.="subtotal='".$subtotal."',";
$UpdatePed.="iva='".$iva."',";
$UpdatePed.="total_pedido='".$Total."',";
$UpdatePed.="estatus_pedido='".$estado."' ";
$UpdatePed.="WHERE idpedido=".$idpedido;

    if ($link->query($UpdatePed) === TRUE){        
        $aud=auditar("Actualizacion de Pedido: ".$idpedido, $_SESSION['user_session'],$link);
        //Inserta el registro de detalle venta
        $i=0;
        if(array_key_exists('hddiddetalle_ped',$_POST))
        {
          $fkproductos = $_POST['hddfkproducto'];          
          $cantidades = $_POST['txtcantidad'];
          //$medidas = $_POST['medida'];
          $precios = $_POST['txtprecio'];
          $subtotales = $_POST['txtsubtotal'];
          $iddetalles_pedidos = $_POST['hddiddetalle_ped'];
          $UpdateDet="";
          $idDetPed="(";
          foreach ($iddetalles_pedidos as $i => $iddetalle_ped)
          {
            $fkproducto=$fkproductos[$i];
            $cantidad = $cantidades[$i];
            //$medida=$medidas[$i];
            $precio=$precios[$i];
            $subtotal=$subtotales[$i];
            
            $UpdateDet = "UPDATE tbl_detalles_pedidos SET ";
            $UpdateDet .= "cantidad = '".$cantidad."', ";
            $UpdateDet .= "precio_blt = '".$precio."', ";          
            $UpdateDet .= "subtotal = '".$subtotal."', "; 
            $UpdateDet .= "estatus_det_pedido = '".$estado."' ";
            $UpdateDet .= "WHERE iddetalle_ped = ".$iddetalle_ped.";";            

            $upd=$link->query($UpdateDet);
            $idDetPed.=$iddetalle_ped.",";

            if ($estado=='POR PAGAR'){
              $precio_iva=($precio*$iva/100)+$precio;
              $updateProd="update tbl_productos set costo=".$precio_iva." where idproducto=".$fkproducto;
              $upd=$link->query($updateProd);
            }
            
          }
          $idDetPed = substr($idDetPed, 0, -1);
          $idDetPed.=")";
          $delete="DELETE FROM tbl_detalles_pedidos WHERE fkpedido = ".$idpedido." AND iddetalle_ped not in ".$idDetPed;
          $resul=$link->query($delete);
        }
      $link->close();
      echo $idpedido;
     //print_r($iddetalles_pedidos);
    }else{
       //el registro no existe
      echo("-1");
      echo($UpdatePed." / ".$insertDet);
      $link->close();      
      die("");
  }
?>