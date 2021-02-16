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

    <title>Regalias</title>

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

if ($("#hddfkpedido").val()=="")
  {
    alert("Sin Pedido Especificado");    
    return; 
  }

if ($("#hddcontador").val()=="0")
  {
    alert("No hay Registros de Detalles");    
    return; 
  }

if ($("#txtbeneficiario").val()=="")
  {
    alert("Coloque nombre del beneficiario");
    $("#txtbeneficiario").focus();    
    return; 
  }

if ($("#txtresponsable").val()=="")
  {
    alert("Coloque nombre del responsable");
    $("#txtresponsable").focus();    
    return; 
  }

if ($("#txtfecha").val()=="")
  {
    alert("Coloque nombre la fecha");
    $("#txtfecha").focus();    
    return; 
  }    

    dir_url = "regalia_db.php";
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
            alert("Registrado Correctamente!");
            location.href = "consulta_regalias.php?fkpedido="+data;
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
function elimFilaProd()
{
  var cont=parseInt($("#hddcontador").val()) - 1;
  document.getElementById('hddcontador').value=cont;
} 
//(*--------------------------------------------*)
function AgregarFilaProd()
{

if (valida_repetido($("#cbofkproducto").val())){
        alert ("Producto Ya Incluido");
        return;
    } 
  if ($("#cbofkproducto").val()=="null" || $("#txtcantidad").val()=="")
  {
     return; 
  }
  var cont=parseInt($("#hddcontador").val()) + 1;
  document.getElementById('hddcontador').value=cont;   
  
  $("#tblDetallesPedido").after("<tr><td><input style='border:none' size='20' name='producto[]' readonly='readonly' type='text' value='" + $("#cbofkproducto option:selected").text() + "'/> <input name='hddfkproducto[]' type='hidden' value='" + $("#cbofkproducto").val() + "'/>&nbsp;&nbsp;</td><td>&nbsp;<input style='border:none; text-align:right' size='10' name='cant[]' readonly='readonly' type='text' value='" + $("#txtcantidad").val() + "'/></td><td><input style='border:none' size='5' name='medida[]' readonly='readonly' type='text' value='" + $("#cbocant").val() + "'/></td><td>&nbsp;&nbsp;&nbsp;&nbsp;<INPUT type='button' value='-' class='btn btn-primary' onclick='$(this).parent().parent().remove(); elimFilaProd();'/></td></tr>");  
}
//(*--------------------------------------------*)
function valida_repetido(prod){
  var j=parseInt($("#hddcontador").val());
  var entro = false;
  if (j>0){
    $('input[type=hidden]').each(function(){
      var cb=$(this);      
      if ((cb.attr('name')=='hddfkproducto[]') && (cb.attr('value')==prod))
           entro = true;
    });    
  }    
  return entro;
}
//(*--------------------------------------------*)

function ventanaAct(){
    var wi = 800;
    var he = 400;
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(wi/2); 
    posicion_y=(screen.height/2)-(he/2);   
    var ventana = window.open('buscar_pedidos_regalia.php', "Buscar Proveedor", "width="+wi+",height="+he+",menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+"");

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
VerificAgregar($("#cbofkproducto"));
});
//(*--------------------------------------------*)
function VerificAgregar(objeto)
{
  if ($(objeto).val()!='null'){ $('#cmdAgregar').removeAttr('disabled');} else $('#cmdAgregar').attr('disabled','disabled');
}
//(*--------------------------------------------*)
function IrProveedor(rut)
{
  url="cargar_datos_proveedor_ped.php?rut=" + rut; 
  $.ajax(url).done(function(data)
   {
      
      if (data != "0")
      {     
        eval(data); //aquí vienen los datos de la pagina php
        //$("#txtcantidad").focus();                 

      }
      else
        { 
          alert("Proveedor No Existe");
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
function VerificarEnter(e)
{
  if (e.keyCode == 13) {      
    IrProveedor($(txtrut).val());
  }
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
                    <h1 class="page-header">Regalias</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Elementos Basicos de la Regalia
                        </div>
                        <div class="panel-body">
                            <div class="row">
                              <form role="form" id="formulario" method='post'>  
                                <div class="col-lg-6">
      
      <input name='txtlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session']; ?>'/>
      <input name='txtnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel']; ?>'/>
 <input name='hddfkpedido' id='hddfkpedido' type='hidden' value=''/>
 <input name='hddcontador' id='hddcontador' type='hidden' value='0'/>
<table class="table" width="90%">
  <tbody>
    <tr>
        <td width="10%"><label class="control-label">Fecha:</label></td>
        <td width="80%"><input name="txtfecha" id="txtfecha" type="date" class="form-control" style="z-index: 0;"></td>
    </tr>
    <tr>
        <td width="10%"><label class="control-label">Pedido:</label></td>
        <td width="80%">
          <div class="form-group input-group">       
              <input type="text" class="form-control" readonly=""  id="txtrazon_social" >
              <span class="input-group-btn">
                  <button class="btn btn-default" onclick="ventanaAct();" type="button"><i class="fa fa-search"></i>
                  </button>
              </span>
          </div> 
        </td>
        
    </tr>
    <tr>
        <td width="10%"><label class="control-label">Beneficiario:</label></td>
        <td width="80%"><input name="txtbeneficiario" id="txtbeneficiario" type="text" class="form-control" style="z-index: 0;"></td>
    </tr>
    <tr>
        <td width="10%"><label class="control-label">Responsable:</label></td>
        <td width="80%"><input name="txtresponsable" id="txtresponsable" type="text" class="form-control" style="z-index: 0;"></td>
    </tr>
    <tr>
        <td width="10%"><label class="control-label">Observacion:</label></td>
        <td width="80%"><input name="txtobservacion" id="txtobservacion" type="text" class="form-control" style="z-index: 0;"></td>
    </tr>
    </tr>
    
  </tbody>
</table>

<div class="table-responsive table-bordered">
    <table class="table">
        
        <tbody>
            <tr>
                <td><label class="control-label">Producto</label></td>
                <td><select id="cbofkproducto" onblur="VerificAgregar(this);" name="cbofkproducto" class="form-control input-sm" onchange="VerificAgregar(this);" >
                    </select></td>

                <td>&nbsp;</td>
               <td><label class="control-label">Cant.:</label></td>
                <td>   
                      <input type="text" onkeypress="return validar(event)" class="form-control" name="txtcantidad"  id="txtcantidad">
                  </td>
                  <td>   
                      <select id="cbocant" name="cbocant" class="form-control input-sm">
                  <option value="BLT.">Bulto(s)</option>
                  <option value="UNID.">Unidad(es)</option>
                </select>
                  </td>
                  <td><INPUT id="cmdAgregar" type="button" value="+" class="btn btn-primary" onclick="AgregarFilaProd();" /></td>
            </tr>
        </tbody>
    </table>
 </div>
</div>
<div class="col-lg-6"> 
<div class="panel panel-default">
      <div class="panel-heading">
          Detalles del Pedido
      </div>
                       
        <div class="panel-body">
            <div class="table-responsive">
                <table width="100%" id="tblDetallesPedido" class="table table-striped">
                    <thead>
                        <tr>                            
                         <!--   <th>Cant.</th>  -->
                            <th width="34%">Producto.</th>
                            <th width="33%">Cant.</th>    
                            <th width="33%">Del.</th>
                        </tr>
                    </thead>
                    <tbody>                        
                    </tbody>
                </table>
            </div>
            
        </div>
                        
  </div>

<p>&nbsp;</p>
<table class="" width="90%" id="tblGuardar" align="center">  
  <tr>
    <td width="30%">&nbsp;</td>
    <td width="40%" align="center"><INPUT id="cmdGuardar" type="button" value="Registrar"  class="btn btn-primary" onclick="GuardarRegistro();"/></td>
    <td width="30%">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
</form>
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