<?php
    include("../BD/conexion.php");
    $idventa= isset($_GET["idventa"])?$_GET["idventa"]:"NULL";
    $link=crearConexion();
    $listado1="SELECT
tbl_clientes.rif,
tbl_clientes.razon_social,
tbl_clientes.direccion,
tbl_clientes.fono,
date_format(tbl_ventas.fecha,'%d') as dia,
date_format(tbl_ventas.fecha,'%m') as mes,
date_format(tbl_ventas.fecha,'%Y') as anho,
tbl_ventas.total_neto,
tbl_ventas.subtotal
FROM
tbl_ventas
INNER JOIN tbl_clientes ON tbl_ventas.fkcliente = tbl_clientes.idcliente
WHERE tbl_ventas.idventa=".$idventa;
    $result1 = $link->query($listado1);
    $reg1 = $result1->fetch_assoc();

    $listado2="SELECT
concat(tbl_detalles_ventas.cantidad,tbl_detalles_ventas.medida) as cantidades,
tbl_detalles_ventas.precio_factura as precio,
tbl_detalles_ventas.precio_factura * tbl_detalles_ventas.cantidad as total,
concat(tbl_productos.descripcion_prod,' ', tbl_productos.marca) as producto
FROM
tbl_detalles_ventas
INNER JOIN tbl_productos ON tbl_detalles_ventas.fkproducto = tbl_productos.idproducto
WHERE tbl_detalles_ventas.fkventa=".$idventa;
    $result2 = $link->query($listado2);
    $cont = $result2->num_rows;

$listado3="SELECT modalidad_pago FROM tbl_abonos WHERE fkventa=".$idventa;
$result3 = $link->query($listado3);
$contFpago = $result3->num_rows;
$Fpago = array();
while ($reg3 = $result3->fetch_assoc())
  if ($contFpago>1)
    $Fpago[]=$reg3['modalidad_pago'];
  else
    array_push($Fpago, $reg3['modalidad_pago']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Factura</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 48px;
	color: #000000;
}
-->
</style></head>

<body>
<table width="2091px" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right"><table width="697px" border="0" cellspacing="0" cellpadding="0">     
      <tr>
        <td width="232.33px" align="center"><strong><?php echo $reg1['dia'];?></strong></td>
        <td width="232.33px" align="center"><strong><?php echo $reg1['mes'];?></strong></td>
        <td width="232.33px" align="center"><strong><?php echo $reg1['anho'];?></strong></td>
      </tr>
       <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="right"><table width="886px" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="221.5px">&nbsp;</td>
        <td width="221.5px">&nbsp;</td>
        <td width="221.5px" align="right"><strong>&nbsp;</strong></td>
        <td width="221.5px" align="right"><strong>X</strong></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="right"><strong><?php echo in_array('CHEQUE', $Fpago)?"X":"";?> </strong></td>
        <td align="right"><strong><?php echo in_array('TDD', $Fpago)?"X":"";?></strong></td>
        <td align="right"><strong><?php echo in_array('TDC', $Fpago)?"X":"";?></strong></td>
        <td align="right"><strong><?php echo in_array('TRANSFERENCIA', $Fpago)?"X":"";?></strong></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="left"><table width="2091px" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="2091px" colspan="2" align="center"><strong><?php echo $reg1['razon_social'];?></strong></td>
        </tr>
      <tr>
        <td width="2091px" colspan="2" align="center"><strong><?php echo $reg1['direccion'];?></strong></td>
        </tr>
      <tr>
        <td width="1045.5px" align="center"><strong><?php echo $reg1['rif'];?></strong></td>
        <td width="1045.5px" align="center"><strong><?php echo $reg1['fono'];?></strong></td>
      </tr>
    </table></td>
  </tr>  
  <tr>
    <td><table width="2091px" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="500px">&nbsp;</td>
        <td width="591px">&nbsp;</td>
        <td width="500px">&nbsp;</td>
        <td width="500px">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?php
        $totales=0;                    
         while ($reg2 = $result2->fetch_assoc()) {
      ?>
      <tr>
        <td align="left"><strong><?php echo $reg2['cantidades'];?></strong></td>
        <td align="left"><strong><?php echo $reg2['producto'];?></strong></td>
        <td align="center"><strong><?php echo number_format($reg2['precio'],2,',','.');?></strong></td>
        <td align="center"><strong><?php echo number_format($reg2['total'],2,',','.');?></strong></td>
      </tr>
      <?php
         $totales+=$reg2['total'];                   
        }
      if ($cont<8) 
        for ($i=$cont+1;$i<=8;$i++){
        ?>
        <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
       <?php   
        }
      $result1->free();  
      $result2->free();
      $link->close();

      ?>
    </table></td>
  </tr>
  <tr>
    <td><table width="2091" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="522.75px">&nbsp;</td>
        <td width="522.75px">&nbsp;</td>
        <td width="522.75px">&nbsp;</td>
        <td width="522.75px" align="center"><strong><?php echo number_format($totales,2,',','.');?></strong></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="center"><strong>&nbsp;</strong></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="center"><strong><?php echo number_format($totales,2,',','.'); ?></strong></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
