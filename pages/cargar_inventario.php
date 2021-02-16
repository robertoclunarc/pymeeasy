<?php
 session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  $link=crearConexion();
  $idprod= isset($_GET["idprod"])?$_GET["idprod"]:"";
    $listado="SELECT * FROM tbl_productos WHERE idproducto=".$idprod;
    $result = $link->query($listado);
    $reg = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Cierre Diario</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

     <link href="../css/estilo.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

     <script src="../js/jquery-1.11.1.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

<script  language="javascript">
function number_format(campo) {
  $(campo).keyup(function(event) {

  // skip for arrow keys
  if(event.which >= 37 && event.which <= 40){
    event.preventDefault();
  }

  $(this).val(function(index, value) {
    return value
      .replace(/\D/g, "")
      .replace(/([0-9])([0-9]{2})$/, '$1,$2')  
      .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".")
    ;
  });
});
}  
//(*--------------------------------------------*)
function GuardarRegistro()
{ 

  if ($("#hddidproducto").val()=="")
  {
    alert("Sin Producto Especificado");    
    return; 
  }

  if ($("#txtcantidad_blt").val()=="")
  {
    alert("Ingrese la cant. de Blt.");
    $("#txtcantidad_blt").focus();
    return; 
  }

  if ($("#txtcant_unidades_en_blt").val()=="")
  {
    alert("Ingrese la Cant. de Unidades en Blt.");
    $("#txtcant_unidades_en_blt").focus();
    return; 
  }
  
    dir_url = "cargar_inventario_db.php";
    $.ajax({
           type: "POST",
           url: dir_url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               //OJO.
               //alert(data); // Mostrar la respuestas del script PHP.
               if (data>0)
                {
                  alert("El producto a sido registrado Correctamente!");
                  //location.reload();
                  location.href = "consultar_productos.php";
                }
               else
               {
                alert("La operación Generó un Error: " + data);
               }
                       
           }
    });  

}
//(*--------------------------------------------*)

function validar(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8) return true; //Tecla de retroceso (para poder borrar)
    if (tecla==46) return true; //Coma ( En este caso para diferenciar los decimales )
    if (tecla==48) return true;
    if (tecla==49) return true;
    if (tecla==50) return true;
    if (tecla==51) return true;
    if (tecla==52) return true;
    if (tecla==53) return true;
    if (tecla==54) return true;
    if (tecla==55) return true;
    if (tecla==56) return true;
    if (tecla==57) return true;
    patron = /1/; //ver nota
    te = String.fromCharCode(tecla);
    return patron.test(te);  
}
//(*--------------------------------------------*)
function Calc_SubTotal()
{
  var cantidad_blt = parseInt($("#txtcantidad_blt").val());  
  var cant_unidades_en_blt = parseInt($("#txtcant_unidades_en_blt").val());
  $("#txtcantidad_unitaria").val(cantidad_blt*cant_unidades_en_blt);
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

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">                
                <a class="navbar-brand" href="index..php">PYME EASY</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
          <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
         <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Cierre Diario de Inventario</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Elementos Basicos del Producto
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" id="formulario" method='post'>     
      
      <input  name='hddlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session']; ?>'/>
      <input  name='hddnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel']; ?>'/> 
      <input  name='hddidproducto' id='hddidproducto' type='hidden' value='<?php echo $reg['idproducto']; ?>'/>
<p>&nbsp;</p>
   
<table width="90%">     
    <tr>
      <td width="10%"><label>Descripcion:</label></td>
      <td width="40%"><INPUT type="text" id="txtdescripcion_prod" readonly name="txtdescripcion_prod" value='<?php echo $reg['descripcion_prod']; ?>'
         width="100%" class="form-control"/></td>
      <td width="10%"> &nbsp;</td> 
      <td width="10%"><label>Marca:</label></td>
      <td width="30%"><input id="txtmarca" name="txtmarca" width="100%" readonly type="text" class="form-control" value='<?php echo $reg['marca']; ?>'></td>       
    </tr>
    <tr>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>    
    </tr>
    
    
    <tr>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>    
    </tr>
    <tr>
       <td><label>Cant. Blt:</label></td>
       <td><input id="txtcantidad_blt" name="txtcantidad_blt" onkeypress="return validar(event)" onkeyup="Calc_SubTotal()" width="100%" type="text" class="form-control" value='<?php echo $reg['cantidad_blt']; ?>'></td>
       <td> &nbsp;</td>
       <td><label>Cant. Unid. en Blt:</label></td>
       <td><input id="txtcant_unidades_en_blt" name="txtcant_unidades_en_blt" onkeypress="return validar(event)" onkeyup="Calc_SubTotal()" width="100%" type="text" class="form-control" value='<?php echo $reg['cant_unidades_en_blt']; ?>'></td>
    </tr>
    <tr>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>    
    </tr>
    <tr>
      <td><label>Cant. Unidades:</label></td>
       <td>
          <input id="txtcantidad_unitaria" name="txtcantidad_unitaria" readonly="" value='<?php echo $reg['cantidad_unitaria']; ?>' onkeyup="number_format(this);" type="text" class="form-control">
 
      </td>
      <td> &nbsp;</td>
      <td><label>Observacion:</label></td>
      <td><input id="txtobservacion" name="txtobservacion" value='' type="text" class="form-control"></td>       
    </tr>
    <tr>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>    
    </tr>
</table>

<p>&nbsp;</p>
<table class="" width="90%" id="tblGuardar" align="center">  
  <tr>
    <td width="30%">&nbsp;</td>
    <td width="40%" align="center"><INPUT id="cmdGuardar" type="button" value="Registrar Cierre"  class="btn btn-primary" onclick="GuardarRegistro();"/></td>
    <td width="30%">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>

</form>
</div>
                                </div>
                                <!-- /.col-lg-6 (nested) -->
                               
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
 <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

</body>

</html>
<?php 
$result->free(); 
$link->close();
}else{
    //header('Location: /login/index.php');
echo "<body>
<script type='text/javascript'>
window.location='../index.php';
</script>
</body>";
}
?>