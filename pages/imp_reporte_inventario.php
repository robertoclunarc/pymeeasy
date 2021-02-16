 <?php
 session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
    include("../BD/conexion.php");
    $link=crearConexion();
    $fechaini= isset($_GET["fechaini"])?$_GET["fechaini"]:"";
    $fechafin= isset($_GET["fechafin"])?$_GET["fechafin"]:"";
    $idcliente= isset($_GET["idc"])?$_GET["idc"]:"";
    $idvendedor= isset($_GET["idv"])?$_GET["idv"]:"";
    $idproducto= isset($_GET["idp"])?$_GET["idp"]:"";
    $formapago= isset($_GET["formapago"])?$_GET["formapago"]:"";

    $listado="SELECT fkproducto, descripcion_prod, marca, cantidad_unidades, cantidad_blt, cant_unidades_en_blt, fecha FROM vw_cierre_inventario WHERE fecha BETWEEN '".$fechaini."' AND '".$fechafin."'";
    
    if ($idproducto!='NULL')
        $listado.=" AND fkproducto=".$idproducto;
    $listado.=" ORDER BY fecha, fkproducto";
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

    <title>Reporte Inventario</title>

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
                             <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>                                   
                                    <tr>
                                        <th class="info">Fecha:</th>
                                        <th style='text-align:right' class="info">Desde</th>
                                        <th class="info"><?php echo $fechaini;?></th>
                                        <th style='text-align:right' class="info">Hasta</th> 
                                        <th class="info"><?php echo $fechafin;?></th>
                                    </tr>
                                </thead>                      
                              </table>      
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>                                                   
                                        <th class="info">Fecha</th>                                               
                                        <th class="info">Producto</th>
                                        <th class="info">Marcar</th>
                                        <th class="info">Cant. en Btl</th>
                                        <th class="info">Cant. Blt.</th>
                                        <th class="info">Cant. Unid.</th>                                        
                                    </tr>

                                </thead>
                                <tbody>
         
                      <?php 
                                        
                         while ($reg = $result->fetch_assoc()) {
                            
                      ?>                 
                        <tr>                           
                            <td><?php echo $reg['fecha'];?></td>
                            <td><?php echo $reg['descripcion_prod'];?></td>
                            <td><?php echo $reg['marca'];?></td>
                            <td><?php echo $reg['cant_unidades_en_blt'];?></td>
                            <td><?php echo $reg['cantidad_blt'];?></td>                            
                            <td><?php echo $reg['cantidad_unidades'];?></td>                            
                        </tr>
                      <?php                    
                         }
                         $result->free();                         
                         $link->close();
                      ?>              
                                </tbody>
                                <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>  
                                        <th>&nbsp;</th>        
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        
                                    </tr>
                                </thead>
                            </table>
                            <!-- /.table-responsive -->
                            
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