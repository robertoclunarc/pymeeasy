<?php
 session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  $link=crearConexion();
  $query = "select * from tbl_parametros";
  $result = $link->query($query);
  $fila = $result->fetch_assoc();
 ?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Datos Empresa</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link href="../css/estilo.css" rel="stylesheet"> 

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

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

//(*--------------------------------------------*)
function GuardarRegistro()
{ 

if ($("#txtrut").val()=="")
  {
    alert("Ingrese el RIF");
    $("#txtrut").focus();
    return; 
  }

  if ($("#txtrazon_social").val()=="")
  {
    alert("Ingrese El Nombre");
    $("#txtrazon_social").focus();
    return; 
  }

    dir_url = "actualizar_parametros1_db.php";
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
            alert("Registro Actualizado Correctamente!");
            //location.href = "consultar_vendedores.php";
          }
         else
         {
          alert("La operación Generó un Error: " + data);
         }
         
          //recargar la página para limpiar controles
        //
        //location.reload(); //Recargar la página desde cero.
               
           }
         });
}
//(*--------------------------------------------*)

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
                    <h1 class="page-header">Datos Empresa</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Elementos Basicos de la Empresa
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               
                              <form role="form" id="formulario" method='post'>
                               <div class="col-lg-6">     
      
      <input name='txtlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session']; ?>'/>
      <input name='txtnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel']; ?>'/>
      <input name='hddidvendedor' id='hddidvendedor' type='hidden'/>
   
<table width="90%">     
    <tr>
      <td width="20%"><label>RIF:</label></td>
      <td width="10%"> &nbsp;</td>
      <td width="70%">
          <input name="txtrut" id="txtrut" type="text" value="<?php echo $fila["rif"]; ?>" class="form-control">
          
     </td>      
    </tr>
    
    <tr>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      
    </tr>

    <tr>
      <td><label>Razon Social:</label></td>
      <td > &nbsp;</td>
      <td ><INPUT type="text" id="txtnombre_empresa" name="txtnombre_empresa" value="<?php echo $fila["nombre_empresa"]; ?>" width="100%" class="form-control"/></td> 
      
    </tr>   
    <tr>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>         
    </tr>
    <tr>
      <td><label>Telefono:</label></td>
       <td> &nbsp;</td>
     <td><INPUT type="text" id="txtfono" name="txtfono" value="<?php echo $fila["fono"]; ?>"  width="100%" class="form-control"/></td> 
    </tr>
   
     <tr>
      <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>      
    </tr>   
    <tr>
      <td><label>Encargado:</label></td>
       <td> &nbsp;</td>
     <td><INPUT type="text" id="txtnombre_encargado" name="txtnombre_encargado" value="<?php echo $fila["nombre_encargado"]; ?>" width="100%" class="form-control"/></td> 
    </tr>
   
     <tr>
      <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>      
    </tr>
</table>
</div>
 <div class="col-lg-6">
<table  width="90%"> 
    
    <tr>
      <td><label>Region:</label></td>
       <td> &nbsp;</td>
     <td><INPUT type="text" id="txtregion" name="txtregion" value="<?php echo $fila["region"]; ?>"  width="100%" class="form-control"/></td> 
    </tr>
   
     <tr>
      <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>      
    </tr>
    <tr>
      <td><label>Ciudad:</label></td>
       <td> &nbsp;</td>
     <td><INPUT type="text" id="txtciudad" name="txtciudad" value="<?php echo $fila["ciudad"]; ?>"  width="100%" class="form-control"/></td> 
    </tr>
   
     <tr>
      <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>      
    </tr>
    <tr>
      <td><label>Direccion:</label></td>
       <td> &nbsp;</td>
     <td><INPUT type="text" id="txtdireccion" name="txtdireccion" value="<?php echo $fila["direccion"]; ?>" width="100%" class="form-control"/></td> 
    </tr>
   
     <tr>
      <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>      
    </tr>
    <tr>
      <td><label>IVA %:</label></td>
       <td> &nbsp;</td>
     <td><INPUT type="text" id="txtiva" name="txtiva" value="<?php echo $fila["iva"]; ?>" width="100%" class="form-control"/></td> 
    </tr>
   
     <tr>
      <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>      
    </tr>
    <tr>
      <td><label>Ganacia %:</label></td>
       <td> &nbsp;</td>
     <td><INPUT type="text" id="txtporc_ganancia" name="txtporc_ganancia" value="<?php echo $fila["porc_ganancia"]; ?>" width="100%" class="form-control"/></td> 
    </tr>
   
     <tr>
      <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>      
    </tr>
</table>
<p>&nbsp;</p> 
</div>
 
<table class="" width="90%" id="tblGuardar" align="center">  
  <tr>
    <td width="30%">&nbsp;</td>
    <td width="40%" align="center"><INPUT id="cmdGuardar" type="button" value="Actualizar Datos" class="btn btn-primary" onclick="GuardarRegistro();"/></td>
    <td width="30%">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
</div>
</form>  
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

    </div>
    <!-- /#wrapper -->

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