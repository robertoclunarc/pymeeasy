<?php
include("../BD/conexion.php");
require('auditoria.php');
if (!isset($_SESSION))
  session_start();     
  
$fkvendedor= isset($_POST["cbofkvendedor"])?$_POST["cbofkvendedor"]:"NULL";   //
$fkcliente= isset($_POST["hddfkcliente"])?$_POST["hddfkcliente"]:"NULL";     //
$subtotal= isset($_POST["txtsubTotal"])?$_POST["txtsubTotal"]:"NULL";       //
$total_neto= isset($_POST["txtTotal"])?$_POST["txtTotal"]:"NULL";          //
$excento= isset($_POST["txtexcento"])?$_POST["txtexcento"]:"NULL";        //
$estatus_venta= isset($_POST["cboestatus_venta"])?$_POST["cboestatus_venta"]:"PENDIENTE";        //
$idnota= isset($_GET["idnota"])?$_GET["idnota"]:"NULL";        //

$piva= isset($_POST["chkiva"])?$_POST["chkiva"]:"0";        //
$total_iva= isset($_POST["txttotaliva"])?$_POST["txttotaliva"]:"0";        //
$divisa= isset($_POST["hdddivisa"])?$_POST["hdddivisa"]:"0";        //
$aplicadivisa= isset($_POST["chkdivisa"])?$_POST["chkdivisa"]:"N";        //

$link=crearConexion();

$insertarVen="";
$insertDet="";
 
$insertarVen="INSERT INTO tbl_ventas (";
$insertarVen.="fkvendedor, ";
$insertarVen.="fecha, ";
$insertarVen.="fkcliente, ";
$insertarVen.="iva, ";
$insertarVen.="subtotal, ";
$insertarVen.="total_neto, ";
$insertarVen.="excento, ";
$insertarVen.="estatus_venta,";
$insertarVen.="totaliva, ";
$insertarVen.="divisa, ";
$insertarVen.="aplica_divisa, ";
$insertarVen.="idnota ";
$insertarVen.=") VALUES (";
$insertarVen.=$fkvendedor.", ";
$insertarVen.="NOW(),";
$insertarVen.=$fkcliente.", ";
$insertarVen.="(select iva from tbl_parametros), ";
$insertarVen.="'".$subtotal."', ";
$insertarVen.="'".$total_neto."', ";
$insertarVen.="'".$excento."', ";
$insertarVen.="'".$estatus_venta."', ";
$insertarVen.="'".round($total_iva,2)."', ";
$insertarVen.="'".round($divisa,2)."', ";
$insertarVen.="'".$aplicadivisa."', ";
$insertarVen.=$idnota;
$insertarVen.=");";
    if ($link->query($insertarVen) === TRUE){
        $idVen=$link->insert_id;
        $aud=auditar("Registro de Venta: ".$idVen, $_SESSION['user_session'],$link);
        /////////////////////////////////////////////////////////////

        ////////////////////////////////////////////////////////////
        //Inserta el registro de detalle venta
        $i=0;
        if(array_key_exists('fkproducto',$_POST))
        {
          $fkproductos = $_POST['fkproducto'];          
          $cantidades = $_POST['cantidad'];
          $precios = $_POST['precio'];
          $medidas = $_POST['medida'];
          $pvs = $_POST['preciove'];
          $precfact = $_POST['preciofact'];

          foreach ($fkproductos as $i => $idprod)
          {
            $cantidad = $cantidades[$i];
            $med=$medidas[$i];
            $precio=$precios[$i];
            $precio_total=$precio*$cantidad;
            $pven=$pvs[$i];
            $precio_factura=$precfact[$i];

            $costo_prod=Buscar_costo_producto($idprod,$link);
            
            $insertDet = "INSERT INTO tbl_detalles_ventas (";
            $insertDet .= "fkventa,";
            $insertDet .= "fkproducto,";
            $insertDet .= "cantidad,";
            $insertDet .= "medida,";             
            $insertDet .= "precio,";
            $insertDet .= "precio_total,";
            $insertDet .= "costo_prod,";
            $insertDet .= "precio_factura";
            $insertDet .= ") VALUES (";
            $insertDet .= $idVen.",";
            $insertDet .= $idprod.",";
            $insertDet .= $cantidad.",";
            $insertDet .= "'".$med."',";
            $insertDet .= "'".$precio."',";
            $insertDet .= "'".$precio_total."',";
            $insertDet .= "'".$costo_prod."',";
            $insertDet .= "'".$precio_factura."'";            
            $insertDet .= "); ";

            $insertPrec = "INSERT INTO tbl_precios_ventas_vendedores (";
            $insertPrec .= "fkventa, ";
            $insertPrec .= "fkproducto, ";
            $insertPrec .= "cantidad, ";
            $insertPrec .= "total, ";                       
            $insertPrec .= "precio_vendedor";            
            $insertPrec .= ") VALUES (";
            $insertPrec .= $idVen.", ";
            $insertPrec .= $idprod.", ";
            $insertPrec .= $cantidad.", ";
            $insertPrec .= "'".$pven*$cantidad."', ";
            $insertPrec .= "'".$pven."'";                        
            $insertPrec .= "); ";

            if ($link->query($insertDet) === TRUE){
               $insertDet = "";
               $link->query($insertPrec);
               $insertPrec = "";
            } else {
                $error=$link->error;
                $link->close();      
                die($error.' ('.$insertDet.')');
            }

          }
                    
        }
      $link->close();
      echo $idVen;
    }else{
       //el registro no existe
      echo("-1");
      echo($insertarVen." / ".$insertDet);
      $link->close();      
      die("");
  }
?>