 <?php
 session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
    require_once('menu.php');
    require_once('menu2.php');
    include("../BD/conexion.php");
    $link=crearConexion(); 
    $desde= date("Y-m-d",time()-3600);
    $hasta= date("Y-m-d",time()-3600);
    $listado="SELECT * FROM vw_cuentas_cobrar WHERE fecha between '".$desde."' and '".$hasta."' ";
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
    <title>Ver Ctas x Cobrar</title>
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
function limpiar(){
    location.reload();  
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
                    <h1 class="page-header">Cuentas por Cobrar</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Data General de Cuentas por Cobrar
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form role="form" id="formulario" method='post'>
                            <div class="col-lg-12">
                            <table width="100%" class="table">
                                <tr> 
                                        <th>Desde</th>  
                                        <th><input type="date" class="form-control" name="txtdesde" value="<?php echo date("Y-m-d",time()-3600); ?>" id="txtdesde" ></th>        
                                        <th>Hasta</th>
                                        <th><input type="date" class="form-control" name="txthasta" value="<?php echo date("Y-m-d",time()-3600); ?>" id="txthasta" ></th>
                                        <th>Estatus</th>  
                                        <th><select id="cboestatus_venta" data-show-subtext="true" name="cboestatus_venta" class="form-control">
                                            <option value="">[Elige Estatus]</option>
                                              <option value="PAGADO">PAGADO</option>
                                              <option value="PENDIENTE">PENDIENTE</option>
                                              <option value="ANULADA">ANULADA</option>
                                            </select>
                                        </th>
                                </tr>
                                <tr>        
                                        <th>Vendedor</th>
                                        <th><select id="cbofkvendedor" name="cbofkvendedor" class="form-control"></select></th>
                                        <th>Cliente</th>
                                        <th><div class="form-group input-group">       
                                            <input readonly="" type="text" placeholder="Cliente" class="form-control"  id="txtrazon_social" >
                                            <span class="input-group-btn">
                                             <button class="btn btn-default" onclick="VerificarEnter(); ventanaAct();" type="button"><i class="fa fa-search"></i>
                                             </button>
                                            </span>
                                            </div>
                                                <input name='hddfkcliente' id='hddfkcliente' type='hidden' value=''/>
                                        </th>
                                        <th><INPUT title="Buscar" id="cmdAgregar" type="button" value="Q" class="btn btn-primary" onclick="IrCuentas();" /></th>
                                        <th><INPUT title="Reiniciar Busqueda" id="cmdAgregar" type="button" value="#" class="btn btn-primary" onclick="limpiar();" /></th>
                                    </tr>
                                    
                            </table>
                            </div>
                            <div id="resultado">    
                            <table width="100%" class="table table-striped table-bordered table-hover" >
                                
                                <thead>
                                    <tr> 
                                        <th>Fecha</th>  
                                        <th>ID Venta</th>        
                                        <th>Cliente</th>
                                        <th>Vendedor</th>
                                        <th>Monto Total</th>
                                        <th>Monto Debe</th>
                                        <th>Monto Haber</th>
                                        <th>Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>                               
                      <?php 
                         $total=0;
                         $debe=0;
                         $haber=0;                   
                         while ($reg = $result->fetch_assoc()) {
                      ?>                 
                        <tr class="gradeA">
                            <td><?php echo $reg['fecha'];?></td>
                            <td><?php echo $reg['fkventa'];?></td>
                            <td><?php echo $reg['cliente'];?></td>
                            <td><?php echo $reg['vendedor'];?></td>
                            <td><?php echo $reg['monto_total'];?></td>
                            <td><?php echo $reg['monto_debe'];?></td>
                            <td><?php echo $reg['monto_haber'];?></td>
                            <td><?php echo $reg['estatus'];?></td>
                        </tr>
                      <?php
                         $total+=$reg['monto_total'];
                         $debe+=$reg['monto_debe'];
                         $haber+=$reg['monto_haber'];                    
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
                                        <th>TOTALES:</th>
                                        <th class="warning"><?php echo number_format($total,2,',','.');?></th>
                                        <th class="warning"><?php echo number_format($debe,2,',','.');?></th>
                                        <th class="warning"><?php echo number_format($haber,2,',','.');?></th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                            <!-- /.table-responsive -->
                        </form>    
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
//(*--------------------------------------------*)        
    $(document).ready(function() {
       CargarCombo($("#cbofkvendedor"), "cargar_combo_db.php?tabla=tbl_vendedores&campo1=idvendedor&campo2=nombres&selected=0&orderby=nombres&firsttext=[Elija un Vendedor]");
    });
//(*--------------------------------------------*)
    function ventanaAct(){
    var wi = 800;
    var he = 400;
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(wi/2); 
    posicion_y=(screen.height/2)-(he/2);   
    var ventana = window.open('buscar_clientes_cta_cobrar.php', "Buscar Cliente", "width="+wi+",height="+he+",menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+"");    
 }
 //(*--------------------------------------------*)
 function CargarCombo(nombcombo, url)
{
  $.ajax(url).done(function(data){
      $(nombcombo).empty();
      $(nombcombo).append(data);      
      }
  );  
}
//(*--------------------------------------------*)
function VerificarEnter()
{
 $("#hddfkcliente").val("");
 $("#txtrazon_social").val("");
 IrCuentas();  
}
function IrCuentas()
{                                                      
  $.ajax({
        type: "POST",
        url: "buscar_ctas_cob.php",
        data: $("#formulario").serialize(),
        dataType: "html",
        beforeSend: function(){
              //imagen de carga
              $("#resultado").html("<p align='center'><img src='images/preloader-01.gif' /></p>");
        },
        error: function(){
              alert("error petición ajax");
        },
        success: function(data){               
              $("#resultado").empty();
              $("#resultado").append(data);
        }
  });
} 
//(*--------------------------------------------*)
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