 <?php
 session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
    include("../BD/conexion.php");
    $link=crearConexion();
    $idnota= isset($_GET["idnota"])?$_GET["idnota"]:"NULL";

    $listado1="SELECT
    tbl_notas_entrega.idnota,    
    tbl_notas_entrega.fecha,    
    tbl_notas_entrega.iva,
    tbl_notas_entrega.subtotal,
    tbl_notas_entrega.total_neto,
    tbl_notas_entrega.excento,
    tbl_notas_entrega.estatus_nota,
    tbl_notas_entrega.divisa,
    tbl_notas_entrega.aplica_divisa,
    tbl_notas_entrega.totaliva,
    tbl_clientes.razon_social AS clientes,
    tbl_vendedores.nombres AS vendedores,
    tbl_clientes.rif as rif_cliente,
    tbl_vendedores.rif as rif_venededor,
    tbl_clientes.direccion as direccion_cliente,
    tbl_clientes.fono as fono_cliente
    FROM
    tbl_notas_entrega
    INNER JOIN tbl_clientes ON tbl_notas_entrega.fkcliente = tbl_clientes.idcliente
    INNER JOIN tbl_vendedores ON tbl_notas_entrega.fkvendedor = tbl_vendedores.idvendedor WHERE tbl_notas_entrega.idnota=".$idnota;    
    $result1 = $link->query($listado1);
    $reg1 = $result1->fetch_assoc();

    $idnota =   $reg1['idnota'];
    $fecha =    $reg1['fecha'];
    $iva =  $reg1['iva'];
    $subtotal =     $reg1['subtotal'];
    $total_neto =   $reg1['total_neto'];
    $excento =  $reg1['excento'];
    $estatus_nota =     $reg1['estatus_nota'];
    $divisa =   $reg1['divisa'];
    $aplica_divisa =    $reg1['aplica_divisa'];
    $totaliva =     $reg1['totaliva'];
    $clientes =     $reg1['clientes'];
    $vendedores =   $reg1['vendedores'];
    $rif_cliente =  $reg1['rif_cliente'];
    $rif_venededor =    $reg1['rif_venededor'];
    $direccion_cliente =    $reg1['direccion_cliente'];
    $fono_cliente =    $reg1['fono_cliente'];

    $result1->free();

    $listado2="SELECT
    tbl_detalles_notas_entrega.cantidad,
    tbl_detalles_notas_entrega.medida,
    tbl_detalles_notas_entrega.precio,
    tbl_detalles_notas_entrega.precio_total,
    tbl_detalles_notas_entrega.fkproducto,
    tbl_detalles_notas_entrega.estatus_nota AS estatus_det_nota,
    CONCAT(tbl_productos.descripcion_prod,' ',tbl_productos.marca) AS descripcion_prod,
    tbl_productos.excepto_iva,
    tbl_detalles_notas_entrega.fknota
    FROM
    tbl_detalles_notas_entrega
    INNER JOIN tbl_productos ON tbl_detalles_notas_entrega.fkproducto = tbl_productos.idproducto WHERE tbl_detalles_notas_entrega.fknota=".$idnota;
    
    $result2 = $link->query($listado2);

    $listado3="SELECT    
    tbl_parametros.nombre_empresa,
    tbl_parametros.rif,
    tbl_parametros.direccion,
    tbl_parametros.ciudad,
    tbl_parametros.region,
    tbl_parametros.nombre_encargado,
    tbl_parametros.fono,
    tbl_parametros.email
    FROM
    tbl_parametros";    
    
    $result3 = $link->query($listado3);
    
    $reg3 = $result3->fetch_assoc();
    $nombre_empresa=$reg3['nombre_empresa'];
    $rif=$reg3['rif'];
    $direccion=$reg3['direccion'];
    $ciudad=$reg3['ciudad'];
    $region=$reg3['region'];
    $nombre_encargado=$reg3['nombre_encargado'];
    $fono=$reg3['fono'];
    $email=$reg3['email'];

    $result3->free();

    $modalidad="";
    $listado4="SELECT DISTINCT modalidad_pago FROM vw_pagos_notas_entregas WHERE fkventa=".$idnota;    
    $result4 = $link->query($listado4);    
    while ($reg4 = $result4->fetch_assoc())
    {
        $modalidad.=$reg4['modalidad_pago']."; ";
    }
    $result4->free();

    $listado5="SELECT nro_cuenta, banco, tipo FROM tbl_cuentas_bancarias WHERE idcuenta_bnc=1";    
    $result5 = $link->query($listado5);    
    while ($reg5 = $result5->fetch_assoc())
    {
        $nro_cuenta = $reg5['nro_cuenta'];
        $banco =$reg5['banco'];
        $tipo=$reg5['tipo'];
    }
    $result5->free();
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Nota Entrega</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    <div id="wrapper">
         
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        
                        <div class="panel-body">
                             <table width="100%" class="" id="">
                                <thead>
                                    <tr>
                                        <th width="20%" rowspan="5"><IMG SRC="<?php echo $_SESSION['logo'];?>" width="230px" height="200px" ></th>
                                        <th width="80%" style='text-align:center'><?php echo $nombre_empresa;?></th>
                                      </tr>
                                      <tr>
                                        <th style='text-align:center'><?php echo $ciudad.' '.$direccion;?></th>
                                      </tr>
                                      <tr>
                                        <th style='text-align:center'><?php echo $region;?></th>
                                      </tr>
                                      <tr>
                                        <th style='text-align:center'><?php echo $fono;?></th>
                                      </tr>
                                      <tr>
                                        <th style='text-align:center'><?php echo $email;?></th>
                                      </tr>                                   
                              
                              </table>
                              <p>&nbsp;</p>
                            <div class="panel panel-default">                        
                                <div class="panel-body">
                                    <div class="table-responsive">
                                      <table class="table table-hover" width="100%">
                                        <thead>
                                          <tr>
                                            <th>NOMBRE/RAZON SOCIAL:</th>
                                            <th><?php echo $clientes;?></th>
                                            <th>&nbsp;</th>
                                            <th>NOTA DE ENTREGA NRO.:</th>
                                            <th><?php echo $idnota;?></th>
                                          </tr>
                                        
                                        
                                          <tr>
                                            <th>RIF/C.I:</th>
                                            <th><?php echo $rif_cliente;?></th>
                                            <th>&nbsp;</th>
                                            <th>CONDICION DE PAGO:</th>
                                            <th><?php echo $modalidad;?></th>
                                          </tr>
                                          <tr>
                                            <th>DOMICILIO FISCAL:</th>
                                            <th><?php echo $direccion_cliente;?></th>
                                            <th>&nbsp;</th>
                                            <th>EMISION:</th>
                                            <th><?php echo $fecha;?></th>
                                          </tr>
                                          <tr>
                                            <th>TRANSPORTE:</th>
                                            <th><?php echo 'NO APLICA';?></th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                          </tr>
                                          
                                          <tr>
                                            <th>TELEFONO</th>
                                            <th><?php echo $fono_cliente;?></th>
                                            <th>&nbsp;</th>
                                            <th></th>
                                            <th><?php echo '';?></th>
                                          </tr>
                                        </thead>
                                        </table>
                                    </div>                       
                                </div>                   
                            </div>                                 
                            <table width="100%" class="table table-striped table-bordered table-hover" id="">
                                <thead>
                                    <tr>
                                        <th >Nro.</th>
                                        <th >Producto</th>             
                                        <th >Cant.</th>  
                                        <th >Unidad</th>        
                                        <th >Precio Unit.</th>
                                        <th >Subtotal</th>
                                    </tr>

                                </thead>
                                <tbody>
         
                      <?php 
                         $i=0;                 
                         while ($reg2 = $result2->fetch_assoc()) {
                         $i++;                            
                      ?>                 
                        <tr>        
                            <td><?php echo $i;?></td>
                            <td><?php echo $reg2['descripcion_prod'];?></td>
                            <td><?php echo $reg2['cantidad'];?></td>
                            <td><?php echo $reg2['medida'];?></td>                            
                            <td><?php echo number_format($reg2['precio'],2,',','.');?></td>
                            <td><?php echo number_format($reg2['precio_total'],2,',','.');?></td>
                            
                        </tr>
                      <?php                    
                         }
                         $result2->free();                         
                         $link->close();
                      ?>  

         
                                </tbody>
                                <thead>
                                    <tr>                                        
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th class="warning">SUBTOTAL:</th>
                                        <th class="warning"><?php echo number_format($subtotal,2,',','.');?></th>
                                    </tr>
                                    <tr>                                        
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th class="warning">DESCUENTO:</th>
                                        <th class="warning"><?php echo number_format($excento,2,',','.');?></th>
                                    </tr>
                                    <tr>                                        
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th class="warning">IVA&nbsp;<?php echo number_format($iva,2,',','.')."%:";?></th>
                                        <th class="warning"><?php echo number_format($totaliva,2,',','.');?></th>
                                    </tr>
                                    <tr>                                        
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th class="warning">TOTAL NETO</th>
                                        <th class="warning"><?php echo number_format($total_neto,2,',','.');?></th>
                                    </tr>
                                </thead>
                            </table>
                            <!-- /.table-responsive -->
                              <p>&nbsp;</p>
                            <div class="panel panel-default">                        
                                <div class="panel-body">
                                    <div class="table-responsive">
                                      <table class="" width="100%">
                                        <thead>
                                          <tr>
                                            <th width="20%">TIEMPO DE ENTREGA:&nbsp;</th>
                                            <th width="20%">INMEDIATA</th>
                                            <th width="10%">&nbsp;</th>
                                            <th width="20%">&nbsp;</th>
                                            <th width="20%">&nbsp;</th>
                                          </tr>
                                           
                                       
                                          <tr>
                                            <th>Para pagos se debe realizar a traves de la cuenta:</th>
                                            <th>Banco&nbsp;<?php echo $banco;?></th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                          </tr>
                                          <tr>
                                            <th>Tipo:</th>
                                            <th><?php echo $tipo;?></th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                          </tr>
                                          <tr>
                                            <th>Cuenta:</th>
                                            <th><?php echo $nro_cuenta;?></th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                          </tr>                                          
                                          <tr>
                                            <th><?php echo $nombre_empresa;?></th>
                                            <th>RIF:&nbsp;<?php echo $rif;?></th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                          </tr>
                                          <tr >
                                            <th>Telefono:</th>
                                            <th><?php echo $fono;?></th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                          </tr>
                                          <tr>
                                            <th><?php echo $email;?></th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                          </tr>
                                        </thead> 
                                        </table>
                                    </div>                       
                                </div>                   
                            </div>


                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    window.print();
    </script>

</body>

</html>
<?php 
}else{
    //header('Location: /login/index.php');
echo "<body>
<script type='text/javascript'>
window.location='../index.php';
</script>
</body>";
}
?>