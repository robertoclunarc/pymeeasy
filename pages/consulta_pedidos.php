 <?php
 session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
    require_once('menu.php');
    require_once('menu2.php');
    include("../BD/conexion.php");
    $link=crearConexion();
    $listado="SELECT
tbl_pedidos.idpedido,
tbl_pedidos.fecha_pedido,
tbl_pedidos.fkproveedor,
tbl_pedidos.forma_pago,
tbl_pedidos.nro_operacion,
tbl_pedidos.fecha_llegada,
tbl_pedidos.subtotal,
tbl_pedidos.iva,
tbl_pedidos.total_pedido,
tbl_pedidos.estatus_pedido,
tbl_proveedores.razon_social,
tbl_proveedores.rif
FROM
tbl_pedidos
INNER JOIN tbl_proveedores ON tbl_pedidos.fkproveedor = tbl_proveedores.idproveedor
ORDER BY
tbl_pedidos.fecha_pedido DESC";
    $result = $link->query($listado);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Ver Pedidos</title>
    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
     <link href="../css/estilo.css" rel="stylesheet">
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
<script  language="javascript">
function enviar(idped){
    window.location="update_pedidos.php?idped="+idped;  
 }
 function flete(idped)
{  
    window.location="pagos_flete.php?idped="+idped;
}
</script>
</head>
<body>
<header id="titulo">      
<div class="portada">
    <div class="text">
      <IMG SRC="<?php echo $_SESSION['logo'];?>" width="230px" height="200px" >
    </div>
</div>
</header>
    <div id="wrapper">
         <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">                
                <a class="navbar-brand" href="index.html">PYME EASY</a>
            </div>
            <!-- /.navbar-header -->
            <?php  echo barra_menu2(); ?>
          <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
            <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Pedidos</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Data General de Pedidos
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th width="5%">Sel.</th>
                                        <th width="5%">Id Pedido</th>
                                        <th width="11%">Fecha Pedido</th>
                                        <th width="13%">Proveedor</th>  
                                        <th width="11%">RIF</th>
                                        <th width="11%">Fecha Llegada</th>
                                        
                                        <th width="11%">Subtotal</th>
                                        <th width="11%">Iva</th>
                                        <th width="11%">Total Pedido</th>
                                        <th width="11%">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>                                   
                      <?php                    
                         while ($reg = $result->fetch_assoc()) {
                      ?>                 
                                    <tr class="gradeA">
                                        <td>
                                            <A href="#" onclick="enviar(<?php echo $reg['idpedido'];?>)" title="Consultar"><IMG SRC="images/note.png" WIDTH="30px" HEIGHT="30px"></A>
                                            <?php
                                            if ($reg['estatus_pedido']=='POR PAGAR'){
                                            ?>
                                                <A href="#" onclick="flete(<?php echo $reg['idpedido'];?>)" title="Pagar Flete"><IMG SRC="images/camion.png" WIDTH="30px" HEIGHT="30px"></A>
                                            <?php
                                            }
                                            ?>
                                        </td> 
                                        <td><?php echo $reg['idpedido'];?></td>
                                        <td><?php echo $reg['fecha_pedido'];?></td>
                                        <td><?php echo $reg['razon_social'];?></td>
                                        <td><?php echo $reg['rif'];?></td>
                                        <td><?php echo $reg['fecha_llegada'];?></td>
                                        
                                        <td><?php echo $reg['subtotal'];?></td>
                                        <td><?php echo $reg['iva'];?></td>
                                        <td><?php echo $reg['total_pedido'];?></td>
                                        <td><?php echo $reg['estatus_pedido'];?></td>
                                    </tr>
                      <?php                    
                         }
                         $result->free();
                         $link->close();
                      ?>              
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->            
            <!-- /.row -->           
        </div>
        <!-- /#page-wrapper -->
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
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
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