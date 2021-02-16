 <?php
 session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
    include("../BD/conexion.php");
    $link=crearConexion();
    $listado="SELECT * FROM tbl_productos WHERE estatus_prod='ACTIVO' order by descripcion_prod";
    $result = $link->query($listado);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Buscar Producto</title>

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
<script  language="javascript">
//(*--------------------------------------------*)
function enviar(id,desc,cant,mar, prec)
{  
    window.opener.document.getElementById('hddfkproducto').value = id;
    window.opener.document.getElementById('txtdescripcion_producto').value = desc;
    window.opener.document.getElementById('txtcantidad_unitaria').value = cant;
    window.opener.document.getElementById('txtmarca').value = mar;
    window.opener.document.getElementById('txtcosto').value = prec;
    var regulado = (parseFloat(prec) * parseFloat(window.opener.document.getElementById('hddporc_ganancia').value)/100) + parseFloat(prec);
    regulado=Math.round(regulado*100) / 100;
    window.opener.document.getElementById('txtprecio_blt').value = regulado;
    window.opener.document.getElementById('txtprecio_unitario').value = Math.round((regulado / parseInt(cant))*100) / 100;
    window.opener.document.getElementById('txtdescripcion_precio').value = "Precio Regulado";
        //window.opener.IrVehiculo(idcli);
    window.close();
}
//(*--------------------------------------------*)    
</script>    
<body>

    <div id="wrapper">
         
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Proveedores Activos
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Sel.</th>
                                        <th>Descripcion</th>  
                                        <th>Marca</th>
                                        <th>Cant. Btl</th>
                                        <th>Precio Reg.</th>
                                    </tr>
                                </thead>
                                <tbody>                                   
                      <?php                    
                         while ($reg = $result->fetch_assoc()) {
                      ?>                 
                        <tr class="gradeA">
                            <td><?php echo $reg['idproducto'];?></td>
                            <td><A onclick="enviar(<?php echo $reg['idproducto'];?>,'<?php echo $reg['descripcion_prod'];?>','<?php echo $reg['cant_unidades_en_blt'];?>','<?php echo $reg['marca'];?>','<?php echo $reg['costo'];?>')" href="#" title="Seleccionar"><IMG SRC="images/note.png" WIDTH="20px" HEIGHT="20px"></A></td>
                            <td><?php echo $reg['descripcion_prod'];?></td>
                            <td><?php echo $reg['marca'];?></td>
                            <td><?php echo $reg['cantidad_blt'];?></td> 
                            <td><?php echo $reg['costo'];?></td>                            
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
window.location='index.php';
</script>
</body>";
}
?>