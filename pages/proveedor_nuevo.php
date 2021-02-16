<?php
 session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
  require_once('menu.php');
  require_once('menu2.php');
 ?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Nuevo Proveedor</title>

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
    alert("Ingrese la Razon Social");
    $("#txtrazon_social").focus();
    return; 
  }
  
    dir_url = "registrar_proveedor_db.php";
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
            alert("El Proveedor Ha Sido Registrado Correctamente!");
            location.href = "consulta_proveedores.php";
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
                    <h1 class="page-header">Proveedores</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Elementos Basicos del Proveedor
                        </div>
                        <div class="panel-body">
                            <div class="row">                                
                              <form role="form" id="formulario" method='post'>
                              <div class="col-lg-6">
      <input  name='txtlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session']; ?>'/>
      <input  name='txtnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel']; ?>'/> 
<table width="90%">     
    <tr>
      <td width="20%"><label>RIF:</label></td>
      <td width="10%"> &nbsp;</td>
      <td width="70%"><INPUT type="text" id="txtrut" name="txtrut"  width="100%" class="form-control"/></td>      
    </tr>
    
    <tr>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      <td> &nbsp;</td>
      
    </tr>

    <tr>
      <td><label>Razon Social:</label></td>
      <td > &nbsp;</td>
      <td ><INPUT type="text" id="txtrazon_social" name="txtrazon_social" width="100%" class="form-control"/></td> 
      
    </tr>   
    <tr>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>         
    </tr>
    <tr>
      <td><label>Telefono:</label></td>
       <td> &nbsp;</td>
     <td colspan="5"><INPUT type="text" id="txtfono" name="txtfono"  width="100%" class="form-control"/></td> 
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
      <td width="20%"><label>Representante Legal:</label></td>
      <td width="10%"> &nbsp;</td>
      <td width="70%"><INPUT type="text" id="txtrepresentante_legal" name="txtrepresentante_legal"  width="100%" class="form-control"/></td>
    </tr>
    <tr>
      <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>              
    </tr>
    <tr>      
      <td><label>Direccion:</label></td>
       <td> &nbsp;</td>
     <td><INPUT type="text" id="txtdireccion" name="txtdireccion"  width="100%" class="form-control"/></td> 
    </tr> 
    <tr>
      <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>      
    </tr>
    
    <tr>
      <td><label>E-Mail:</label></td>
       <td> &nbsp;</td>
     <td colspan="5"><INPUT type="text" id="txtemail" name="txtemail"  width="100%" class="form-control"/></td> 
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
    <td width="40%" align="center"><INPUT id="cmdGuardar" type="button" value="Registrar Proveedor" class="btn btn-primary" onclick="GuardarRegistro();"/></td>
    <td width="30%">&nbsp;</td>
  </tr>
</table>
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
}else{
    //header('Location: /login/index.php');
echo "<body>
<script type='text/javascript'>
window.location='../index.php';
</script>
</body>";
}
?>