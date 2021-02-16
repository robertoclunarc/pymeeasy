 <?php
 session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
    require_once('menu.php');
    require_once('menu2.php');
    include("../BD/conexion.php");
    $link=crearConexion();
    $listado="SELECT 
concat(tbl_productos.descripcion_prod,' ',tbl_productos.marca) as descripcion_prod,
tbl_productos.cantidad_unitaria,
tbl_productos.cantidad_blt,
tbl_productos.cant_unidades_en_blt,
tbl_productos.costo,
tbl_productos.excepto_iva,
tbl_productos.estatus_prod,
tbl_precios_productos.descripcion_precio,
tbl_precios_productos.precio_unitario,
tbl_precios_productos.precio_blt,
tbl_precios_productos.moneda,
tbl_precios_productos.fecha_ultim_actualizacion,
tbl_divisas.nombre_divisa
FROM
tbl_precios_productos
INNER JOIN tbl_productos ON tbl_precios_productos.fkproducto = tbl_productos.idproducto
INNER JOIN tbl_divisas ON tbl_precios_productos.moneda = tbl_divisas.id_divisa
ORDER BY
tbl_precios_productos.fecha_ultim_actualizacion DESC,
tbl_productos.idproducto ASC";
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

    <title>Ver Precios</title>

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
function enviar(idc){
   var wi = 800;
    var he = 400;
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(wi/2); 
    posicion_y=(screen.height/2)-(he/2);   
    var ventana = window.open('cargar_clientes.php?idc='+idc, "Ver Cliente", "width="+wi+",height="+he+",menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+""); 
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
                <a class="navbar-brand" href="index.php">PYME EASY</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
          <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
         <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Precios</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Histrorial de Ajustes de Precios de Productos
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th width="11%">Ult. Actualizacion</th>
                                        <th width="17%">Producto</th>  
                                        <th width="11%">Cant. Unit</th>
                                        <th width="11%">Cant. Blt.</th>                                        
                                        <th width="11%">Desc. Precio</th>
                                        <th width="11%">Precio Unit.</th>
                                        <th width="11%">Precio Blt.</th>
                                        <th width="5%">Moneda</th>
                                        <th width="5%">Excepto Iva</th>
                                    </tr>
                                </thead>
                                <tbody>                                   
                      <?php                    
                         while ($reg = $result->fetch_assoc()) {
                      ?>                 
                                    <tr class="gradeA">
                                        <td><?php echo $reg['fecha_ultim_actualizacion'];?></td>
                                        <td><?php echo $reg['descripcion_prod'];?></td>                                        
                                        <td><?php echo number_format($reg['cantidad_unitaria'],2,',','.');?></td>
                                        <td><?php echo number_format($reg['cantidad_blt'],2,',','.');?></td>
                                        <td><?php echo $reg['descripcion_precio'];?></td>
                                        <td><?php echo number_format($reg['precio_unitario'],2,',','.');?></td>
                                        <td><?php echo number_format($reg['precio_blt'],2,',','.');?></td>
                                        <td><?php echo $reg['nombre_divisa'];?></td>
                                        <td><?php echo $reg['excepto_iva'];?></td>

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
            responsive: true,
            "order": [[ 0, "desc" ]]
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