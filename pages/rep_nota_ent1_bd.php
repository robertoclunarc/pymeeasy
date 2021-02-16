<?php
 session_start();

$finicio= isset($_POST["txtfechaini"])?$_POST["txtfechaini"]:"NULL";
$ffin= isset($_POST["txtfechafin"])?$_POST["txtfechafin"]:"NULL";
$cliente= isset($_POST["hddidcliente"])?$_POST["hddidcliente"]:"NULL";
$estado= isset($_POST["hddestatus"])?$_POST["hddestatus"]:"NULL";
$vendedor= isset($_POST["hddidvendedor"])?$_POST["hddidvendedor"]:"NULL";
$producto= isset($_POST["hddidproducto"])?$_POST["hddidproducto"]:"NULL";
$idnota= isset($_POST["hddidnota"])?$_POST["hddidnota"]:"NULL";
$aplicadivisa= isset($_POST["chkdivisa"])?$_POST["chkdivisa"]:"N";
$otramoneda= isset($_POST["chkotramoneda"])?$_POST["chkotramoneda"]:"N";

$xls= isset($_GET["xls"])?$_GET["xls"]:0;

$qry="SELECT
tbl_notas_entrega.idnota,
tbl_notas_entrega.fkvendedor,
tbl_notas_entrega.fecha,
tbl_notas_entrega.fkcliente,
tbl_notas_entrega.iva,
tbl_notas_entrega.subtotal,
tbl_notas_entrega.total_neto,
tbl_notas_entrega.excento,
tbl_notas_entrega.estatus_nota,
tbl_notas_entrega.divisa,
tbl_notas_entrega.aplica_divisa,
tbl_notas_entrega.totaliva,
tbl_detalles_notas_entrega.cantidad,
tbl_detalles_notas_entrega.medida,
tbl_detalles_notas_entrega.precio,
tbl_detalles_notas_entrega.precio_total,
tbl_detalles_notas_entrega.fkproducto,
tbl_detalles_notas_entrega.estatus_nota as estatus_det_nota,
CONCAT(tbl_productos.descripcion_prod,' ',tbl_productos.marca) as descripcion_prod,
tbl_clientes.razon_social as clientes,
tbl_vendedores.nombres as vendedores,
tbl_ventas.idnota as enlaceventa
FROM
tbl_notas_entrega
INNER JOIN tbl_detalles_notas_entrega ON tbl_detalles_notas_entrega.fknota = tbl_notas_entrega.idnota
INNER JOIN tbl_productos ON tbl_detalles_notas_entrega.fkproducto = tbl_productos.idproducto 
INNER JOIN tbl_clientes ON tbl_notas_entrega.fkcliente = tbl_clientes.idcliente
INNER JOIN tbl_vendedores ON tbl_notas_entrega.fkvendedor = tbl_vendedores.idvendedor
LEFT JOIN tbl_ventas ON tbl_notas_entrega.idnota = tbl_ventas.idnota 
 WHERE date_format(tbl_notas_entrega.fecha,'%Y-%m-%d') BETWEEN '".$finicio."' AND '".$ffin."' AND tbl_detalles_notas_entrega.estatus_nota='ACTIVO' ";
$qry.="and tbl_notas_entrega.aplica_divisa= '".$aplicadivisa."' "; 

$trans="";
if ($otramoneda=='S')
  if ($aplicadivisa=='S')
    $trans="*tbl_notas_entrega.divisa";
  else
    $trans="/tbl_notas_entrega.divisa";

$qry2="SELECT ";
$qry2.="SUM(tbl_notas_entrega.subtotal".$trans.") as sum_subtotal,";
$qry2.="SUM(tbl_notas_entrega.total_neto".$trans.") sum_total_neto,";
$qry2.="SUM(tbl_notas_entrega.excento".$trans.") as sum_excento,";
$qry2.="SUM(tbl_notas_entrega.totaliva".$trans.") as sum_totaliva";
$qry2.=" FROM tbl_notas_entrega where date_format(tbl_notas_entrega.fecha,'%Y-%m-%d') BETWEEN '".$finicio."' AND '".$ffin."' ";
$qry2.="and tbl_notas_entrega.aplica_divisa= '".$aplicadivisa."' ";



if ($producto!="NULL" && $producto!="")
{
  $qry.=" and tbl_detalles_notas_entrega.fkproducto in (".$producto.") ";
  $qry2="SELECT
    Sum(tbl_detalles_notas_entrega.precio_total".$trans.") AS sum_subtotal,
    CASE tbl_notas_entrega.totaliva
    WHEN 0 THEN
    Sum(tbl_detalles_notas_entrega.precio_total".$trans.")
    ELSE
    Sum(tbl_detalles_notas_entrega.precio_total".$trans.") 
    END + tbl_notas_entrega.totaliva AS sum_total_neto,

    0 as sum_excento,
    CASE tbl_productos.excepto_iva 
    WHEN 'N' THEN
    Sum(tbl_detalles_notas_entrega.precio_total".$trans.") + (Sum(tbl_detalles_notas_entrega.precio_total".$trans.")*tbl_notas_entrega.iva/100)
    ELSE
       0 END AS sum_totaliva
    FROM
    tbl_detalles_notas_entrega
    INNER JOIN tbl_notas_entrega ON tbl_detalles_notas_entrega.fknota = tbl_notas_entrega.idnota
    INNER JOIN tbl_productos ON tbl_detalles_notas_entrega.fkproducto = tbl_productos.idproducto
    WHERE date_format(tbl_notas_entrega.fecha,'%Y-%m-%d') BETWEEN '".$finicio."' AND '".$ffin."' ";
    $qry2.="and tbl_notas_entrega.aplica_divisa= '".$aplicadivisa."' ";
    $qry2.=" and tbl_detalles_notas_entrega.fkproducto in (".$producto.") ";
}

if ($cliente!="NULL" && $cliente!=""){
  $qry.=" and tbl_notas_entrega.fkcliente=".$cliente." ";
  $qry2.=" and tbl_notas_entrega.fkcliente=".$cliente." ";
}

if ($vendedor!="NULL" && $vendedor!=""){
  $qry.=" and tbl_notas_entrega.fkvendedor=".$vendedor." ";
  $qry2.=" and tbl_notas_entrega.fkvendedor=".$vendedor." ";
}

if ($idnota!="NULL"){
  $qry.=" and tbl_notas_entrega.idnota=".$idnota." ";
  $qry2.=" and tbl_notas_entrega.idnota=".$idnota." ";
}

if ($estado!="NULL"  && $estado!=""){
  $array_estado = explode(",", $estado);
  $west="";
  if (count($array_estado)>0){
    foreach($array_estado as $i=>$est)
    {
      $west.="'".$est."',";
    }
    $west = substr($west, 0, -1);
  }else{
    $west="'".$array_estado."'";
  }

  $qry.=" and tbl_notas_entrega.estatus_nota in (".$west.") ";
  $qry2.=" and tbl_notas_entrega.estatus_nota in (".$west.") ";
}

$qry.=" ORDER BY tbl_notas_entrega.idnota DESC, tbl_notas_entrega.fecha desc";


buscar($qry, $qry2, $xls, $otramoneda, $aplicadivisa);     
       
function buscar($b, $c, $xls, $otramoneda, $aplicadivisa){
 // echo $b.'<br>'.$c;
       include("../BD/conexion.php");
       //require_once('funciones_var.php');
       
       $link=crearConexion(); 
       $result_b = $link->query($b);
       $result_c = $link->query($c);      
       $contar = $result_b->num_rows;
       
        if($contar == 0){
              $inpt = "No se han encontrado resultados!";
              
        }else{
              $inpt = '<table width="80%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
              
            $inpt = $inpt.'<thead>              
            <tr>
                <th width="3%" class="info">Oper.</th>
                <th width="3%" class="info">Nro.</th>
                <th width="5%" class="info">Fecha</th>
                <th width="10%" class="info">Cliente</th>              
                <th width="10%" class="info">Vendedor</th> 
                <th width="10%" class="info">Producto</th>                
                <th width="3%" class="info">Cant.</th>              
                <th width="3%" class="info">Medida</th>
                <th width="3%" class="info">Estatus</th>
                <th width="3%" class="info">Divisa</th>
                <th width="10%" class="info">Precio</th>              
                <th width="10%" class="info">Total</th>                
            </tr>
        </thead>
        <tbody>';
            $contar=0;
            $sum_subtotal=0;
            $sum_excento=0;
            $sum_totaliva=0;
            $sum_total_neto=0;
            $mnd='';              
             while ($row = $result_b->fetch_assoc()) 
             {     
                    $contar++;
                    $clase="label label-info";                    
                    
                    $fecha = substr($row['fecha'], 0,10); 

                    $precio=$row['precio'];
                    $precio_total=$row['precio_total'];
                    if ($row['divisa']==0 || $row['divisa']=='')
                      $divisa=1;
                    else
                      $divisa=$row['divisa'];

                    if ($otramoneda=='S')
                      if ($row['aplica_divisa']=='S')                      
                      {    
                          $precio=$precio*$divisa;  
                          $precio_total=$precio_total*$divisa;
                          $mnd='Bs. ';
                      }                    
                      else
                       {   
                          $precio=$precio/$divisa; 
                          $precio_total=$precio_total/$divisa;
                          $mnd='';
                       }

                    $inpt .='<tr>';                    
                    if ($xls==0)
                    {
                      $inpt .='<td><A href="#" onclick="detalles('.$row['idnota'].');" title="Ver Detalles"><IMG SRC="images/note.png" WIDTH="25px" HEIGHT="25px"></A>';
                      $inpt .='<A href="#" onclick="imprimir('.$row['idnota'].');" title="Imprimir"><IMG SRC="images/imprimir.png" WIDTH="25px" HEIGHT="25px"></A>';
                      if ($row['estatus_nota']=='PENDIENTE') {
                        $inpt .='<A href="#" onclick="abonar('.$row["idnota"].');" title="Abonar"><IMG SRC="images/abonar.png" WIDTH="25px" HEIGHT="25px"></A>';
                      }
                      if ($row['estatus_nota']!='ANULADA' && $row['enlaceventa']=='') {
                        $inpt .='<A href="#" onclick="enlazar_aventa('.$row["idnota"].');" title="Abonar"><IMG SRC="images/enlace.png" WIDTH="25px" HEIGHT="25px"></A>';
                      }
                      $inpt .='</td>'; 
                    }
                    else
                      $inpt .='<td>'.$contar.'</td>';

                    $inpt .='<td>'.$row['idnota'].'</td>';
                    $inpt .='<td>'.$fecha.'</td>';
                    $inpt .='<td>'.$row['clientes'].'</td>';
                    $inpt .='<td>'.$row['vendedores'].'</td>';
                    $inpt .='<td>'.$row['descripcion_prod'].'</td>';
                    $inpt .='<td>'.$row['cantidad'].'</td>';                    
                    $inpt .='<td>'.$row['medida'].'</td>';
                    $inpt .='<td>'.$row['estatus_nota'].'</td>';
                    $inpt .='<td>'.number_format($row['divisa'],2,',','.').'</td>';                  
                    $inpt .='<td>'.$mnd.number_format($precio,2,',','.').'</td>';
                    $inpt .='<td>'.$mnd.number_format($precio_total,2,',','.').'</td>';                    
                    $inpt .='</tr>';

                    $precio=0; 
                    $precio_total=0;                                            
              }

              $reg_c = $result_c->fetch_assoc();
              $sum_subtotal=$reg_c['sum_subtotal'];
              $sum_excento=$reg_c['sum_excento'];
              $sum_totaliva=$reg_c['sum_totaliva'];
              $sum_total_neto=$reg_c['sum_total_neto'];
             
              $inpt .=' </tbody>
              <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>                        
                        <th><span class="label label-success">SubTotal:</span></th>
                        <th><span class="label label-success">'.$mnd.number_format($sum_subtotal,2,',','.').'</span></th>
                        
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>                        
                        <th><span class="label label-success">Excepto:</span></th>
                        <th><span class="label label-success">'.$mnd.number_format($sum_excento,2,',','.').'</span></th>
                        
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>                        
                        <th><span class="label label-success">Total Iva:</span></th>
                        <th><span class="label label-success">'.$mnd.number_format($sum_totaliva,2,',','.').'</span></th>
                        
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th> 
                        <th></th>                       
                        <th><span class="label label-success">Total Neto:</span></th>
                        <th><span class="label label-success">'.$mnd.number_format($sum_total_neto,2,',','.').'</span></th>
                        
                    </tr>
                </tfoot>
                </table>';
        }
$result_b->free();
$result_c->free();
$link->close();
echo $inpt;
//print_r($row);
}         
?>