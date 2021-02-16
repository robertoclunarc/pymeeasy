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

    <title>User Setting</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

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

  if ($("#txtnombre").val()=="")
  {
    alert("Ingrese Nombre - Apellido");
    $("#txtnombre").focus();
    return; 
  }

  if ($("#txtmail").val()=="")
  {
    alert("Ingrese el Mail");
    $("#txtmail").focus();
    return; 
  }

  if ($("#txtuser").val()=="")
  {
    alert("Ingrese el Usuario");
    $("#txtuser").focus();
    return; 
  }

  if ($("#txtpassword").val()=="")
  {
    alert("Ingrese el Password");
    $("#txtPassword").focus();
    return; 
  }

   if ($("#txtpregunt1").val()=="")
  {
    alert("Ingrese pregunta 1");
    $("#txtpregunt1").focus();
    return; 
  }

  if ($("#txtresp1").val()=="")
  {
    alert("Ingrese la respuesta pregunta 1");
    $("#txtresp1").focus();
    return; 
  }

  if ($("#txtpregunt2").val()=="")
  {
    alert("Ingrese la Pregunta 2");
    $("#txtpregunt2").focus();
    return; 
  }

  if ($("#txtresp2").val()=="")
  {
    alert("Ingrese la respuesta Pregunta 2");
    $("#txtresp2").focus();
    return; 
  }
      if ($("#txtnivel").val()=="")
  {
    alert("Seleccione Nivel");
    $("#txtnivel").focus();
    return; 
  }
  if ($("#txtestatus").val()=="")
  {
    alert("Seleccione Estatus");
    $("#txtestatus").focus();
    return; 
  }
  
    dir_url = "registrar_usuario_db.php";
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
            alert("Usuario Registrado Correctamente!");
            location.href = "consulta_user.php";
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
function CargarCombo(nombcombo, url)
{
  $.ajax(url).done(function(data){
      $(nombcombo).empty();
      $(nombcombo).append(data);      
      }
  );  
}
//(*--------------------------------------------*)
$(document).ready(function(){

  CargarCombo($("#cbonombre"), "cargar_combo_db.php?tabla=vw_nuevos_usuarios&campo1=idtrabajador&campo2=nombres&where=fksucursal&condi="+$("#hddidsucursal").val()+"&selected=0&orderby=nombres&firsttext=[Elija un Trabajador]");  
});
//(*--------------------------------------------*)
function copiar_nombre()
{
  document.getElementById('hddnombre').value=$("#cbonombre option:selected").text();
}
//(*--------------------------------------------*)
</script>
</head>

<body>
<header id="titulo">      
      <IMG SRC="images/header.jpg" width="100%" height="200px" >
</header>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">                
                <a class="navbar-brand" href="index.php">Estacion de Servicio</a>
            </div>
            <!-- /.navbar-header -->
           <?php  echo barra_menu2(); ?>
          <!-- /. AQUI VA EL MUNU DESPLEGABLE -->
         <?php  echo barra_menu(); ?>
        </nav>

        <div id="page-wrapper">
            
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Registro de Usuarios
                        </div>
                        <div class="panel-body">
                         <div class="row">
          <div class="col-lg-6">
             <form role="form" id="formulario" method='post'>  
       <input name='hddidsucursal' id='hddidsucursal' type='hidden' value='<?php echo $_SESSION['idsucursal']; ?>'/>

       <input name='hddnombre' id='hddnombre' type='hidden' value=''/>                                
            <div class="form-group">
                <label>Nombre / Apellido del Usuario</label>
                
                <select onchange="copiar_nombre();" id="cbonombre" name="cbonombre" class="form-control" ></select>

            </div>
            <div class="form-group">
                <label>Email</label>
                <input class="form-control" name="txtmail" placeholder="Ejemp@ejemplo.cl">
            </div>
            <div class="form-group">
                <label>User</label>
                <input maxlength="6" class="form-control" name="txtuser" placeholder="Usuario del sistema">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="txtPassword" maxlength="32" placeholder="Debe Contener letras y numeros">
            </div>
           <div class="form-group">
              <label>Nivel del Usuario</label>
                  <select class="form-control" name="txtnivel">
                  <option value="1">1 Admin.</option>
                  <option value="2">2 Oper.</option>
                  <option value="3">3 Caja</option>
                  <option value="4">4 Invent.</option>
                  <option value="5">5 No Defin.</option>
              </select>
           </div>
            </div>
            <!-- /.col-lg-6 (nested) -->
            <div class="col-lg-6">
                <div>Preguntas Validacion de Cuenta</div>
                <div></div>
               
                <div class="form-group">
                        <label>Pregunta secreta 1</label>
                        <input maxlength="30" class="form-control" name="txtpregunt1" placeholder="Enter text">
                    </div>
                    <div class="form-group">
                        <label>Respuesta Secreta 1</label>
                        <input maxlength="30" class="form-control" name="txtresp1" placeholder="Enter text">
                    </div>
                    <div class="form-group">
                        <label>Pregunta Secreta 2</label>
                        <input maxlength="30" class="form-control" name="txtpregunt2" placeholder="Enter text">
                    </div>
                    <div class="form-group">
                        <label>Respuesta sereta 2</label>
                        <input maxlength="30" class="form-control" name="txtresp2" placeholder="Enter text">
                    </div>
                     <div class="form-group">
                        <label>Estatus Usuario</label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="estatus" id="Activado" value="ACTIVO" checked>Activado
                            </label>
                                                                
                            <label>
                                <input type="radio" name="estatus" id="Desactivado" value="INACTIVO">INACTIVO
                            </label>
                        </div>
                        
                    </div>   
                
            </div>
                                <!-- /.col-lg-6 (nested) -->
    </div>                                       
      
      <input  name='txtlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session']; ?>'/>
      <input  name='txtnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel']; ?>'/>

<p>&nbsp;</p>
<table class="" width="90%" id="tblGuardar" align="center">  
  <tr>
    <td width="30%">&nbsp;</td>
    <td width="40%" align="center"><INPUT id="cmdGuardar" type="button" value="Registrar"  class="btn btn-primary" onclick="GuardarRegistro();"/></td>
    <td width="30%">&nbsp;</td>
  </tr>
</table>
</form>
                              
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            </div>
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
window.location='index.php';
</script>
</body>";
}
?>