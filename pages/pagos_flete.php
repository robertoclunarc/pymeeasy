<?php
 session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
  require_once('menu.php');
  require_once('menu2.php');
  $nombre="";
  $rif="";
  $fkpedido= isset($_GET["idped"])?$_GET["idped"]:"";
  if ($fkpedido!=""){
    include("../BD/conexion.php");
    $link=crearConexion();
    $link=crearConexion();
    $listado="SELECT tbl_pedidos.idpedido, tbl_proveedores.rif, tbl_proveedores.razon_social FROM tbl_pedidos INNER JOIN tbl_proveedores ON tbl_pedidos.fkproveedor = tbl_proveedores.idproveedor WHERE tbl_pedidos.idpedido=".$fkpedido;
    $result = $link->query($listado);
    $reg = $result->fetch_assoc();
    $nombre=$reg['razon_social'];
    $rif=$reg['rif'];
    $result->free();
    $link->close();
  }
 ?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Pago de Flete</title>

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

if ($("#txtrut").val()=="")
  {
    alert("Ingrese el Rif");
    $("#txtrut").focus();
    return; 
  }

if ($("#hddfkproveedor").val()=="")
  {
    alert("Sin Proveedor Especificado");
    $("#txtrut").focus();
    return; 
  }

if ($("#hddcontador").val()=="0")
  {
    alert("No hay Registros de Detalles");    
    return; 
  }

    dir_url = "pagos_flete_db.php";
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
            location.href = "consultar_fletes.php";
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
function elimFilaProd(monto)
{
  var cont=parseInt($("#hddcontador").val()) - 1;
  document.getElementById('hddcontador').value=cont;

  var costo = parseFloat($("#txtcosto").val()) - monto;
  $("#txtcosto").val(costo);
} 
//(*--------------------------------------------*)
function AgregarFilaProd()
{

if ($("#txtmonto").val()=="" || parseFloat($("#txtmonto").val())<=0)
  {
     alert ("Ingrese Monto");
     $("#txtmonto").focus();
     return; 
  }

if (parseFloat($("#txtmonto").val())>parseFloat($("#txtrestante").val()))
  {
     alert ("El monto no debe ser mayor al credito");
     $("#txtmonto").focus();
     return; 
  }

  var cont=parseInt($("#hddcontador").val()) + 1;
  document.getElementById('hddcontador').value=cont;

  var valor_col1=$("#cbomodalidad_pago").val();  
  var valor_col2=$("#txtnro_operacion").val();
  if (valor_col2=='')
    valor_col2='N/A';
  var valor_col3=$("#txtmonto").val();
  var valor_col4=$("#txtbanco").val(); 
  var rest = $("#txtrestante").val();
  var restante = Math.round((rest-parseFloat(valor_col3))*100) / 100;

  var ctrl_col1="<input name='modalidad_pago[]' type='hidden' value='" + valor_col1 + "'/>";
  var ctrl_col2="<input name='nro_referencia[]' type='hidden' value='" + valor_col2 + "'/>";
  var ctrl_col3="<input name='monto[]' type='hidden' value='" + valor_col3 + "'/>";
  var ctrl_col4="<input name='banco[]' type='hidden' value='" + valor_col4 + "'/>";
  
  var tabla="<tr>";
  tabla=tabla+"<td width='30%'>"+valor_col1+ctrl_col1+"<td>";
  tabla=tabla+"<td width='30%'>"+valor_col2+ctrl_col2+"<td>";
  tabla=tabla+"<td width='30%'>"+valor_col4+ctrl_col4+"<td>";
  tabla=tabla+"<td width='30%'>"+valor_col3+ctrl_col3+"<td>";
  tabla=tabla+"<td width='5%'><INPUT type='button' value='-' class='btn btn-primary' onclick='$(this).parent().parent().remove(); elimFilaProd("+valor_col3+");'/><td>";
  tabla=tabla+"<tr>";
    
  $("#tblDetallesPago").after(tabla);

  $("#txtnro_operacion").val("");
  $("#txtbanco").val("");
  $("#txtmonto").val("");

  var costo = parseFloat($("#txtcosto").val()) + parseFloat(valor_col3);
  $("#txtcosto").val(costo);
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
    var ventana = window.open('buscar_pedidos_fletes.php', "Buscar Proveedor", "width="+wi+",height="+he+",menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+"");    
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
CargarCombo($("#cbomodalidad_pago"), "cargar_combo_db.php?tabla=tbl_modalidades_pagos&campo1=descripcion&selected="+$("#hddforma_pago").val()+"&orderby=descripcion");
});
//(*--------------------------------------------*)
function VerificAgregar(objeto)
{
  if ($(objeto).val()!='null'){ $('#cmdAgregar').removeAttr('disabled');} else $('#cmdAgregar').attr('disabled','disabled');
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
                    <h1 class="page-header">Pago de Flete</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Elementos Basicos del Pedido
                        </div>
                        <div class="panel-body">
                            <div class="row">
                              <form role="form" id="formulario" method='post'>  
                                <div class="col-lg-6">
      
      <input name='txtlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session']; ?>'/>
      <input name='txtnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel']; ?>'/>
 <input name='hddfkpedido' id='hddfkpedido' type='hidden' value='<?php echo $fkpedido; ?>'/>

 <input name='hddcontador' id='hddcontador' type='hidden' value='0'/>

<table class="table" width="90%">
  <tbody>
    <tr>
        <td width="10%">&nbsp;</td>
        <td width="80%">
          <div class="form-group input-group">       
              <input type="text" placeholder="Pedido" class="form-control"  id="txtrazon_social" value="<?php echo $nombre; ?>" readonly="" >
              <span class="input-group-btn">
                  <button class="btn btn-default" onclick="ventanaAct();" type="button"><i class="fa fa-search"></i>
                  </button>
              </span>
          </div> 
        </td>
        
    </tr>
    <tr>
        <td width="10%"><label class="control-label">Rif</label></td>
        <td width="80%"><input name="txtrut" id="txtrut" type="text" class="form-control" value="<?php echo $rif; ?>" style="z-index: 0;" readonly="" ></td>
    </tr>
    <tr>
        <td width="10%"><label class="control-label">Transportista</label></td>
        <td width="80%"><input name="txtTransportista" id="txtTransportista" type="text" class="form-control" style="z-index: 0;" ></td>
    </tr>
    <tr>
        <td width="10%"><label class="control-label">Costo</label></td>
        <td width="80%"><div class="form-group input-group">
       <input name="txtcosto" id="txtcosto" placeholder="0.0" value="0.0"  onkeypress="return validar(event);" type="text" readonly="" class="form-control">
         <span class="input-group-addon">Bs.</span>
      </div></td>
    </tr>
  </tbody>
</table>
</div>
<div class="col-lg-6"> 
<table class="table" width="100%">
<tbody>
<tr>  
  <td width="20%"><label class="control-label">F. Pago</label></td>
  <td width="25%"><label class="control-label">Nro. Oper.</label></td>
  <td width="25%"><label class="control-label">Banco</label></td>
  <td width="25%"><label class="control-label">Monto</label></td>
  <td width="5%">&nbsp;</td>
</tr>
<tr>
  <td><select  name="cbomodalidad_pago" id="cbomodalidad_pago" class="selectpicker show-tick form-control"></select></td>
  <td><input name="txtnro_operacion" id="txtnro_operacion" type="text" class="form-control" style="z-index: 0;" value="" ></td>
  <td><input name="txtbanco" id="txtbanco" type="text" class="form-control" style="z-index: 0;" value="" ></td> 
  <td><div class="form-group input-group">
       <input name="txtmonto" id="txtmonto" placeholder="0.0" value=""  onkeypress="return validar(event);" type="text" class="form-control">
         <span class="input-group-addon">Bs.</span>
      </div></td>
  <td><INPUT id="cmdAgregar" type="button" value="+" class="btn btn-primary" onclick="AgregarFilaProd();" /></td>
</tr>
</tbody>
</table>
<table width="100%" id="tblDetallesPago" class="table table-striped">
</table>

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