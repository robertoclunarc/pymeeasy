<?php
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){ 
 require_once('menu.php');
 require_once('menu2.php');
 include("../BD/conexion.php");
    $link=crearConexion();
    $listado="SELECT * FROM vw_graficos_1";
    $result = $link->query($listado);

    $listado2="SELECT dato FROM vw_graficos_2";
    $result2 = $link->query($listado2);    
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Pyme Easy - Inicio</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../css/estilo.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <!-- Morris Charts CSS -->
    <link href="../vendor/morrisjs/morris.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<header id="titulo">      
<div class="portada">
    <div class="text">
      <IMG SRC="<?php echo $_SESSION['logo'];?>" width="230px" height="200px" >
    </div>
</div>
</header>
<body>

    <div id="wrapper">

        <!-- Navigation -->
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
                    <h1 class="page-header">Inicio</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">

            <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Leyenda
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table width="20%" class="table">
                                    
                                    <tbody>
                                         <tr style="background: #6b9dfa;" >
                                            <td>Ctas. por Pagar</td>
                                            
                                        </tr>
                                        <tr style="background: #e9e225;">
                                            <td>Ctas. por Cobrar</td>
                                           
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Registro Ctas. por Pagar VS Ctas. por Cobrar 
                        </div>
                        <!-- /.panel-heading -->
                        <script src="../js/Chart.js"></script>
                        <div id="canvas-holder">                           
                            <canvas id="chart-area3" width="600" height="300"></canvas>
                            <canvas id="chart-area4" width="600" height="300"></canvas>
                        </div>

<script>

    var databd =  [];
    var databd2 =  [];
    var labelsbd =  [];
                <?php
                    while ($reg = $result->fetch_assoc()) {
                ?>
                databd.push([<?php echo $reg['dato'];?>]);
                labelsbd.push(['<?php echo $reg['label'];?>']);
                <?php }                
                    while ($reg2 = $result2->fetch_assoc()) {
                ?>
                databd2.push([<?php echo $reg2['dato'];?>]);
                <?php } 
                $result->free();
                $result2->free();
                $link->close();
                ?>
    var barChartData = {
        labels : labelsbd,
        datasets : [
            {
                fillColor : "#6b9dfa",
                strokeColor : "#ffffff",
                highlightFill: "#1864f2",
                highlightStroke: "#ffffff",
                data : databd
            },
            {
                fillColor : "#e9e225",
                strokeColor : "#ffffff",
                highlightFill : "#ee7f49",
                highlightStroke : "#ffffff",
                data : databd2
            }
        ]

    };

   /* var barChartData2 = {
        labels : ["Lun","Mar","Mie","Jue","Vie","Sab","Dom"],
        datasets : [
            {
                fillColor : "#6b9dfa",
                strokeColor : "#ffffff",
                highlightFill: "#1864f2",
                highlightStroke: "#ffffff",
                data : [90,30,10,80,15,5,15]
            },
            {
                fillColor : "#e9e225",
                strokeColor : "#ffffff",
                highlightFill : "#ee7f49",
                highlightStroke : "#ffffff",
                data : [40,50,70,40,85,55,15]
            }
        ]

    }  */

var ctx3 = document.getElementById("chart-area3").getContext("2d");
//var ctx4 = document.getElementById("chart-area4").getContext("2d");
              
window.myPie = new Chart(ctx3).Bar(barChartData, {responsive:true});
//window.myPie = new Chart(ctx4).Bar(barChartData2, {responsive:true});
</script>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
            </div>
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
    <!-- Morris Charts JavaScript -->
    <script src="../vendor/raphael/raphael.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
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