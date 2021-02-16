<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Recuperar Usuario</title>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen"> 
<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
<script type="text/javascript" src="js/validation.min.js"></script>
<link href="css/style_login.css" rel="stylesheet" type="text/css" media="screen">
<script>
function GuardarRegistro()
{ 

if ($("#user").val()=="")
  {
    alert("Ingrese el login");
    $("#user").focus();
    return;
  }

if ($("#pregunta_secreta_1").val()=="")
  {
    alert("No hay pregunta secreta 1");
    $("#user").focus();    
    return;
  }

if ($("#respuesta_secreta_1").val()=="")
  {
    alert("No hay respuesta secreta 1");
    $("#respuesta_secreta_1").focus();    
    return;
  }

if ($("#pregunta_secreta_2").val()=="")
  {
    alert("No hay pregunta secreta 2");
    $("#user").focus();    
    return;
  }

if ($("#respuesta_secreta_2").val()=="")
  {
    alert("No hay respuesta secreta 2");
    $("#respuesta_secreta_2").focus();    
    return;
  }
    dir_url = "update_login.php";
    $.ajax({
           type: "POST",
           url: dir_url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
                if (data==1)
                {
                    alert("Password Actualizado Correctamente!");
                    location.href = "index.php";
                }
                else
                 {
                    if (data==-1)
                        alert("La operación Generó un Error: " + data);
                    else
                       alert("Los Datos Suministrados Son Incorrectos"); 
                 }
           }
         });
}
//(*--------------------------------------------*)
function Iruser(login)
{
  url = "cargar_datos_login.php?user=" + login;
    $.ajax(url).done(function(data)
       {           
            if (data!=0)
            {
                eval(data);
                $("#respuesta_secreta_1").focus();
            }
            else
            {
            alert("Los Datos Suministrados No Coinciden Con Los Registrado En Sistema");
            }     
       });
}
//(*--------------------------------------------*)    
function VerificarEnter(e)
{
  if (e.keyCode == 13) {      
    Iruser($("#user").val());
  }
}
//(*--------------------------------------------*)
</script>
</head>

<body>
    
<div class="signin-form">

	<div class="container">
     
        
       <form class="form-signin"  method="post" id="formulario">
      
        <h2 class="form-signin-heading">Recuperar Contraseña</h2><hr />
        
        <div id="error">
        <!-- error will be shown here ! -->
        </div>
        
        <div class="form-group">
        <input  type="text" class="form-control" placeholder="Escriba su LOGIN y presione la tecla ENTER" onkeypress="VerificarEnter(event);" name="user" id="user" />
        <span id="check-e"></span>
        </div>
        
        <div class="form-group">
        <input  type="text" class="form-control" placeholder="Pregunta Secreta 1" readonly name="pregunta_secreta_1" id="pregunta_secreta_1" />
        </div>
        <div class="form-group">
        <input  type="text" class="form-control" placeholder="Respuesta Secreta 1"  name="respuesta_secreta_1" id="respuesta_secreta_1" />
        </div>
        <div class="form-group">
        <input  type="text" class="form-control" placeholder="Pregunta Secreta 2" readonly name="pregunta_secreta_2" id="pregunta_secreta_2" />
        </div>
        <div class="form-group">
        <input  type="text" class="form-control" placeholder="Respuesta Secreta 2" name="respuesta_secreta_2" id="respuesta_secreta_2" />
        </div>
        <div class="form-group">
        <input  type="password" class="form-control" placeholder="Nuevo Password" name="password" id="password" />
        </div>
     	<hr />
        
        <table class="" width="90%" id="tblGuardar" align="center">  
          <tr>
            <td width="30%">&nbsp;</td>
            <td width="40%" align="center"><INPUT id="cmdGuardar" type="button" value="Actualizar"  class="btn btn-primary" onclick="GuardarRegistro();"/></td>
            <td width="30%">&nbsp;</td>
          </tr>
        </table>  
        <a style="text-decoration:none;color:#DAA520;" href="index.php"> << ATRAS << </a>
      </form>

    </div>
    
</div>
    
<script src="bootstrap/js/bootstrap.min.js"></script>

</body>
</html>
