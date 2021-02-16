<?php
include("../BD/conexion.php");
require('auditoria.php');
if (!isset($_SESSION))
	session_start();     
 
$fkproducto= isset($_POST["hddfkproducto"])?$_POST["hddfkproducto"]:"NULL";   // 
$contador= isset($_POST["hddcontador"])?$_POST["hddcontador"]:"NULL";   // 

$link=crearConexion();
$insertarPrec="";

$i=0;
if(array_key_exists('descripcion_precio',$_POST))
{
  $descripciones_precios = $_POST['descripcion_precio'];          
  $precios_blt = $_POST['precio_blt'];
  $precios_unitarios = $_POST['precio_unitario'];
  $monedas = $_POST['moneda'];

  foreach ($descripciones_precios as $i => $descrip)
  {
    $precio_blt = $precios_blt[$i];
    $precio_unitario=$precios_unitarios[$i];
	$mnd=$monedas[$i];
    
    $insertarPrec="INSERT INTO tbl_precios_productos (";
    $insertarPrec.="fkproducto,";
    $insertarPrec.="descripcion_precio,";
    $insertarPrec.="precio_unitario,";
    $insertarPrec.="precio_blt,";
    $insertarPrec.="estatus_precio, ";
    $insertarPrec.="moneda,";
	$insertarPrec.="fecha_ultim_actualizacion";
    $insertarPrec.=") VALUES (";
    $insertarPrec.=$fkproducto.", ";
    $insertarPrec.="'".$descrip."', ";
    $insertarPrec.="'".$precio_unitario."', ";
    $insertarPrec.="'".$precio_blt."', ";
    $insertarPrec.="'ACTIVO', ";
	$insertarPrec.=$mnd.", ";
    $insertarPrec.="NOW() ";
    $insertarPrec.=");";
    
    if ($link->query($insertarPrec) === TRUE){              
        $aud=auditar("Registro de Precio Producto ID: ".$fkproducto.".- Bs.".$precio_blt, $_SESSION['user_session'],$link);
        $actua="update tbl_precios_productos set estatus_precio = 'INACTIVO' where fkproducto = ".$fkproducto." and date_format(fecha_ultim_actualizacion, 'Y-%m-%d') < date_format(now(), 'Y-%m-%d');";
        $link->query($actua);
    } else {
        echo("-1");
        $error=$link->error;
        $link->close();      
        die($error.' ('.$insertarPrec.')');
    }

  }
  $link->close();
  echo $fkproducto;
} else {
  echo("-1");
  $link->close();
}
?>