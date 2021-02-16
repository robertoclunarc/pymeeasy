<?php
 session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
$link=crearConexion();
$query = "SELECT iva FROM tbl_parametros";
$result = $link->query($query);
if ($result->num_rows > 0)
{
  $fila = $result->fetch_assoc();
  $iva=$fila["iva"];
}
else
  $iva="0.00";

$query = "SELECT monto_actualizacion FROM vw_precio_actual_divisa";
$result = $link->query($query);
if ($result->num_rows > 0)
{
  $fila = $result->fetch_assoc();
  $divisaact=$fila["monto_actualizacion"];
}
else
  $divisaact="0.00";

$query = "SELECT
tbl_clientes.idcliente,
CONCAT(tbl_clientes.razon_social,'  ',tbl_clientes.rif) as cliente
FROM
tbl_clientes
WHERE tbl_clientes.razon_social='N/A'";
$result = $link->query($query);
if ($result->num_rows > 0)
{
  $fila = $result->fetch_assoc();
  $cliente=$fila["cliente"];
  $idcliente=$fila["idcliente"];
}
else
 {
  $cliente="";
  $idcliente="";
 }

$result->free();
$link->close();
 ?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Nueva Venta</title>
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

if ($("#cbofkvendedor").val()=="null")
  {
    alert("Debe Seleccionar un Vendedor");    
    return; 
  }

if ($("#hddfkcliente").val()=="")
  {
    alert("Debe Seleccionar Un Cliente Activo");    
    return; 
  }

if ($("#hddcontador").val()=="0")
  {
    alert("No hay Registros de Detalles");    
    return; 
  }

    dir_url = "registrar_ventas_db.php";
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
            location.href = "consultar_ventas.php";
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
function elimFilaProd(subtot)
{
  var cont=parseInt($("#hddcontador").val()) - 1;
  document.getElementById('hddcontador').value=cont;
  var subtotal = parseFloat($("#txtsubTotal").val()) - subtot;
  $("#txtsubTotal").val(subtotal);

  var excent = 0;
  if ($("#txtexcento").val()!="")
    excent=parseFloat($("#txtexcento").val());
  //$("#txtTotal").val(subtotal-excent);
  aplicar_iva();
} 
//(*--------------------------------------------*)
function AgregarFilaProd()
{
if (valida_repetido($("#cbofkproducto").val())){
        alert ("Producto Ya Incluido");
        return;
    }

if ($("#cbocant").val()=="BLT.")
    if (parseInt($("#txtcantidad").val())>parseInt($("#hddcantblt").val())){
        alert ("La Cantidad en BTL el Inventario que Dispone para este Producto es "+$("#hddcantblt").val());
        $("#txtcantidad").focus();
        return;
    }

 if ($("#cbocant").val()=="UNID.")
    if (parseInt($("#txtcantidad").val())>parseInt($("#hddcantunit").val())){
        alert ("La Cantidad en UNIDAD el Inventario que Dispone para este Producto es "+$("#hddcantunit").val());
        $("#txtcantidad").focus();
        return;
    }      

  if ($("#cbofkproducto").val()=="null" || $("#txtcantidad").val()=="" || parseFloat($("#txtcantidad").val())==0 || $("#txtprecio").val()=="" || parseFloat($("#txtprecio").val())==0 || $("#txtprecio").val()=="null")
  {
     return; 
  }

  var cont=parseInt($("#hddcontador").val()) + 1;
  document.getElementById('hddcontador').value=cont;

  var text_col1=$("#cbofkproducto option:selected").text();
  var valor_col1=$("#cbofkproducto").val();  
  var valor_col2=$("#txtcantidad").val();
  var valor_col3=$("#cbocant").val();
  var valor_col4=$("#txtprecio").val();  
  var valor_col5 = Math.round(parseFloat(valor_col2)*parseFloat(valor_col4)*100) / 100;
  var valor_col6=$("#txtprecioVend").val();
  var valor_col7=$("#txtprecio_factura").val();
  var valor_col8=$("#hddmnd").val();
  var valor_col9=$("#hddexceptoiva").val();

  var ctrl_col1="<input name='fkproducto[]' type='hidden' value='" + valor_col1 + "'/>";
  var ctrl_col2="<input name='cantidad[]' id='cantidad_"+cont+"' type='hidden' value='" + valor_col2 + "'/>";
  var ctrl_col3="<input name='medida[]' type='hidden' value='" + valor_col3 + "'/>";
  var ctrl_col4="<input name='precio[]' id='precio_"+cont+"' type='hidden' value='" + valor_col4 + "'/>";
  var ctrl_col6="<input name='preciove[]' type='hidden' value='" + valor_col6 + "'/>";
  var ctrl_col7="<input name='preciofact[]' type='hidden' value='" + valor_col7 + "'/>";
  var ctrl_col8="<input name='mnds[]' type='hidden' value='" + valor_col8 + "'/>";
  var ctrl_col9="<input name='exceptoiva[]' id='exceptoiva_"+cont+"' type='hidden' value='" + valor_col9 + "'/>";
  
  var tabla="<tr>";
  tabla=tabla+"<td width='40%'>"+text_col1+ctrl_col1+"<td>";
  tabla=tabla+"<td width='10%'>"+valor_col2+valor_col3+ctrl_col2+ctrl_col3+"<td>";
  tabla=tabla+"<td width='25%'>"+valor_col4+ctrl_col4+"<td>";
  tabla=tabla+"<td width='25%'>"+valor_col5+"<td>";
  tabla=tabla+"<td width='5%'>"+ctrl_col6+ctrl_col7+ctrl_col8+ctrl_col9+"<INPUT type='button' value='-' class='btn btn-primary' onclick='$(this).parent().parent().remove(); elimFilaProd("+valor_col5+");'/><td>";
  tabla=tabla+"<tr>";
    
  $("#tblDetallesPrecios").after(tabla);

  $("#txtcantidad").val("0");

  var subtotal = Math.round((parseFloat($("#txtsubTotal").val()) + valor_col5)*100) / 100;

  var excent = 0;
  if ($("#txtexcento").val()!="")
    excent=parseFloat($("#txtexcento").val());

  var iva = 0;
  if( $('#chkiva').prop('checked') ) {
    iva = parseFloat($("#chkiva").val());
  }

  var excent = 0;
  if ($("#txtexcento").val()!="")
    excent=parseFloat($("#txtexcento").val());
  $("#txtsubTotal").val(subtotal);
  $("#txtTotal").val(subtotal-excent);
 
}
//(*--------------------------------------------*)
function aplicar_iva(){
  var iva = 0;
  var excent = 0;
  var subtotal = parseFloat($("#txtsubTotal").val());
  if( $('#chkiva').prop('checked') ) {
      iva = parseFloat($("#chkiva").val());
  } 
  if ($("#txtexcento").val()!="")
    excent=parseFloat($("#txtexcento").val());  

  var totaliva = (iva/100) * (subtotal-productos_excentos_iva());
  $("#txttotaliva").val(totaliva);
  $("#txtsubTotal").val(subtotal);
  $("#txtTotal").val(subtotal+totaliva-excent);

}
//(*--------------------------------------------*)
function productos_excentos_iva()
{ 
    var i=0;
    var excp=0.0;
    var stt=0.0;
    var cont=parseInt($("#hddcontador").val());
    for (i=1; i<=cont; i++) {           
      if ($("#exceptoiva_"+i).val()=='S')
         if($("#cantidad_"+i).length != 0) {
           stt=parseFloat($("#cantidad_"+i).val())*parseFloat($("#precio_"+i).val());           
           excp=excp+stt;
        }          
    }
    return excp;
}
//(*--------------------------------------------*)
function valida_monedas_diferentes(mon){
  var j=parseInt($("#hddcontador").val());
  var entro = false;
  if (j>0){
    $('input[type=hidden]').each(function(){
      var cb=$(this);      
      if ((cb.attr('name')=='mnds[]') && (cb.attr('value')!=mon))
           entro = true;
    });    
  }    
  return entro;
}
//(*--------------------------------------------*)
function valida_repetido(prod){
  var j=parseInt($("#hddcontador").val());
  var entro = false;
  if (j>0){
    $('input[type=hidden]').each(function(){
      var cb=$(this);      
      if ((cb.attr('name')=='fkproducto[]') && (cb.attr('value')==prod))
           entro = true;
    });    
  }    
  return entro;
}
//(*--------------------------------------------*)
function Calc_Total(excent){
  if (excent=='')
    excent=0;

  var subtotal = parseFloat($("#txtsubTotal").val());
  $("#txtTotal").val(subtotal-excent);
}
//(*--------------------------------------------*)
function validar_exc(excent){
  if (excent=='')
    $("#txtexcento").val("0.00");
}
//(*--------------------------------------------*)
function ventanaAct(){
    var wi = 800;
    var he = 400;
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(wi/2); 
    posicion_y=(screen.height/2)-(he/2);   
    var ventana = window.open('buscar_clientes_ventas.php', "Buscar Cliente", "width="+wi+",height="+he+",menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+"");    
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
function verPrecios1(idprod)
{
    var tabla = 'vw_precios_productos_unit';
    if ($("#cbocant").val()=='BLT.')
      tabla='vw_precios_productos_blt';
    CargarCombo($("#txtprecio"), "cargar_combo_db.php?tabla="+tabla+"&campo1=precio&campo2=descripcion_precio&selected=0&where=fkproducto&condi="+idprod+"&firsttext=[Elija un Precio]");
    buscar_cant_inventario(idprod);
}
//(*--------------------------------------------*)
function buscar_cant_inventario(idprod)
{
    url="cargar_cant_inventario.php?idprod=" + idprod; 
      $.ajax(url).done(function(data)
       {
          
          if (data != "0")
          {     
            eval(data); //aquí vienen los datos de la pagina php
            
            /*document.getElementById('txtsaldorest').value =formatearNumero($("#txtsaldorest").val());
            IrVentas($(hddfkcliente).val());          
            $("#txtnumeracion").focus();*/
          }
          /*else
            { 
                alert("Cliente No Existe o No Tiene Vehiculo(s) Activo(s)");          
            } */
       });
}
//(*--------------------------------------------*)
function verPrecios2(medida)
{
    var tabla = 'vw_precios_productos_unit';
    if (medida=='BLT.')
      tabla='vw_precios_productos_blt';
    CargarCombo($("#txtprecio"), "cargar_combo_db.php?tabla="+tabla+"&campo1=precio&campo2=descripcion_precio&selected=0&where=fkproducto&condi="+$("#cbofkproducto").val()+"&firsttext=[Elija un Precio]");
}
//(*--------------------------------------------*)
$(document).ready(function(){
VerificAgregar($("#cbofkproducto"));

  CargarCombo($("#cbofkproducto"), "cargar_combo_db.php?tabla=vw_productos&campo1=idproducto&campo2=descripcion&where=estatus_prod&condi='ACTIVO'&selected=0&orderby=descripcion&firsttext=[Elija un producto]");

  CargarCombo($("#cbofkvendedor"), "cargar_combo_db.php?tabla=tbl_vendedores&campo1=idvendedor&campo2=nombres&where=estatus_vend&condi='ACTIVO'&selected=3&orderby=nombres&firsttext=[Elija un Vendedor]");
});
//(*--------------------------------------------*)
function VerificAgregar(objeto)
{
  if ($(objeto).val()!='null')
  { 
    $('#cmdAgregar').removeAttr('disabled');
    var comboprec=$("#txtprecio option:selected").text();
    var arr=comboprec.split('(');
    var mnd=' ';
    if (arr.length>1)
      mnd=arr[1].substr(0,arr[1].indexOf(")",0));
        
    $('#spantxtprecioVend').text(mnd);
    $('#spantxtsubTotal').text(mnd);
    $('#spantxtexcento').text(mnd);
    $('#spantxtTotal').text(mnd);
    $('#spantxtprecio_factura').text(mnd);  
    $('#spantotaliva').text(mnd);
    $('#hddmnd').val(mnd);

    if (mnd!='Bs.')
      $('#chkdivisa').prop("checked", true);
    else
      $('#chkdivisa').prop("checked", false);
  
  } 
  else 
    $('#cmdAgregar').attr('disabled','disabled');
  
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
/*function VerificarEnter(e)
{
  if (e.keyCode == 13) {      
    IrProveedor($(txtrut).val());
  }
}*/
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
                    <h1 class="page-header">Nueva Venta</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Elementos Basicos para la Venta
                        </div>
                        <div class="panel-body">
                            <div class="row">
                              <form role="form" id="formulario" method='post'>  
                                <div class="col-lg-6">
      
      <input name='txtlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session']; ?>'/>
      <input name='hddnivel' id='hddnivel' type='hidden' value='<?php echo $_SESSION['nivel']; ?>'/>
 <input name='hddfkcliente' id='hddfkcliente' type='hidden' value='<?php echo $idcliente; ?>'/>

 <input name='hddcontador' id='hddcontador' type='hidden' value='0'/>
 <input name='hddcantblt' id='hddcantblt' type='hidden' value='0'/>
 <input name='hddcantunit' id='hddcantunit' type='hidden' value='0'/>
 <input name='hddexceptoiva' id='hddexceptoiva' type='hidden' value=''/>  
 <input name='hddmnd' id='hddmnd' type='hidden' value=''/>

<table class="table" width="90%">
  <tbody>
    <tr>
        <td width="10%"><label class="control-label">Cliente</label></td>
        <td width="80%">
          <div class="form-group input-group">       
              <input type="text" placeholder="Cliente" class="form-control" readonly="" value="<?php echo $cliente; ?>"  id="txtrazon_social" >
              <span class="input-group-btn">
                  <button class="btn btn-default" onclick="ventanaAct();" type="button"><i class="fa fa-search"></i>
                  </button>
              </span>
          </div> 
        </td>
        
    </tr>
    <tr>
        <td width="10%"><label class="control-label">Vendedor</label></td>
        <td width="80%"><select id="cbofkvendedor" name="cbofkvendedor" class="form-control"></select></td>
    </tr>
    <tr>
        <td width="10%"><label class="control-label">Estatus</label></td>
        <td width="80%"><select id="cboestatus_venta" data-show-subtext="true" name="cboestatus_venta" class="form-control">
                          <option value="PENDIENTE">PENDIENTE</option>
                        </select>
        </td>
    </tr>
  </tbody>
</table>
</div>
<div class="col-lg-6">

    <table width="100%" class="table">
        
        <tbody>
            <tr>
                <td><label class="control-label">Producto</label></td>
                <td><select id="cbofkproducto" onblur="VerificAgregar(this);" name="cbofkproducto" class="form-control input-sm" onchange="verPrecios1(this.value); VerificAgregar(this);" >
                    </select></td>
                
                
                <td>
                  <?php
                    if ($_SESSION['nivel']==1){
                  ?>
                      <select id="txtprecio" name="txtprecio" class="form-control input-sm" onchange="VerificAgregar(this);" >
                    </select>
                  <?php
                    } else {
                  ?>
                  <div class="form-group input-group">
                          <input id="txtprecio" placeholder="0.00" name="txtprecio" onkeypress="return validar(event)" width="100%" type="text" class="form-control input-sm">
                          <span class="input-group-addon">Bs.</span>
                      </div>
                  <?php
                    } 
                  ?>   
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>    
               <td><label class="control-label">Cant.:</label></td>
               <td><input type="text" placeholder="0" onkeypress="return validar(event)" class="form-control" name="txtcantidad"  id="txtcantidad"></td>
               
               
               <td><select id="cbocant" name="cbocant" onchange="verPrecios2(this.value); VerificAgregar(this);" class="form-control input-sm">
               			<option value="BLT.">Bulto(s)</option>
                  		<option value="UNID.">Unidad(es)</option>
                	</select>
               </td>
               <td><INPUT id="cmdAgregar" type="button" value="+" class="btn btn-primary" onclick="AgregarFilaProd();" /></td>
            </tr>
            <?php
              if ($_SESSION['nivel']==1){
            ?>
            <tr>    
               <td><label class="control-label">Precio Vendedor:</label></td>
               <td><div class="form-group input-group">
                          <input id="txtprecioVend" placeholder="0.00" name="txtprecioVend" onkeypress="return validar(event)" width="100%" type="text" class="form-control input-sm">
                          <span id="spantxtprecioVend" class="input-group-addon">&nbsp;</span>
                      </div></td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>               
            </tr>
            <tr>    
               <td><label class="control-label">Precio Factura:</label></td>
               <td><div class="form-group input-group">
                          <input id="txtprecio_factura" placeholder="0.00" name="txtprecio_factura" onkeypress="return validar(event)" width="100%" type="text" class="form-control input-sm">
                          <span  id="spantxtprecio_factura" class="input-group-addon">&nbsp;</span>
                      </div></td>
               <td><input id="chkdivisa" name="chkdivisa" type="checkbox" value="S"><label>&nbsp;Aplica Divisa Actualizada: <?php echo number_format($divisaact,2,',','.'); ?></label></td>
               <td><input name='hdddivisa' id='hdddivisa' type='hidden' value='<?php echo $divisaact; ?>'/></td>   
            </tr>
            <?php
              }
            ?>              
        </tbody>
    </table>

</div>
<div class="col-lg-12"> 
<div class="panel panel-default">
      <div class="panel-heading">
          Detalles de la Venta
      </div>
                       
        <div class="panel-body">
            <div class="table-responsive">
                <table width="100%" id="tblDetallesPrecios" class="table table-striped">
                    <thead>
                        <tr>                     
                            <th width="35%">Producto.</th>
                            <th width="10%">Cant.</th>
                            <th width="25%">Precio</th> 
                            <th width="25%">Subtotal</th>   
                            <th width="5%">Del.</th>
                        </tr>
                    </thead>
                    <tbody>                        
                    </tbody>
                </table>
                <table width="100%"  class="table table-striped">
                    <thead>
                        <tr>                     
                            <td width="35%">&nbsp;</td>
                            <td width="10%">&nbsp;</td>
                            <td width="25%">&nbsp;</td> 
                            <td width="25%">&nbsp;</td>   
                            <td width="5%">&nbsp;</td>
                        </tr>
                        
                        <tr>                     
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="text-align: right;"><label class="control-label">SUB-TOTAL:</label></td> 
                            <td><div class="form-group input-group">
                          						<input id="txtsubTotal" name="txtsubTotal" value="0.00" readonly="" width="100%" type="text" class="form-control">
                          						<span  id="spantxtsubTotal" class="input-group-addon">&nbsp;</span>
                      						</div>
                      		</td>   
                            <td>&nbsp;</td>
                        </tr>
                        <tr>                     
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="text-align: right;"><label class="control-label">Descuento:</label></td> 
                            <td><div class="form-group input-group">
                                      <input id="txtexcento" onkeyup="Calc_Total(this.value)" onblur="validar_exc(this.value)" onkeypress="return validar(event)" name="txtexcento" value="0.00" width="100%" type="text" class="form-control">
                                      <span id="spantxtexcento" class="input-group-addon">&nbsp;</span>
                                  </div>
                          </td>   
                            <td>&nbsp;</td>
                        </tr>
                        <tr>                     
                            <td>&nbsp;</td>
                            <td><div class="checkbox">
                                                <label>
                                                    <input id="chkiva" name="chkiva" type="checkbox" onclick="aplicar_iva()" value="<?php echo $iva; ?>">Aplicar Iva <?php echo $iva.'%'; ?>
                                                </label>
                                            </div></td>
                            <td style="text-align: right;"><label class="control-label">Total Iva:</label></td> 
                            <td><div class="form-group input-group">
                                      <input id="txttotaliva"  name="txttotaliva" value="0.00" width="100%" type="text" readonly="" class="form-control">
                                      <span id="spantotaliva" class="input-group-addon">&nbsp;</span>
                                  </div>
                          </td>   
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <tr>                     
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="text-align: right;"><label class="control-label">TOTAL:</label></td> 
                            <td><div class="form-group input-group">
                                      <input id="txtTotal" name="txtTotal" value="0.00" readonly="" width="100%" type="text" class="form-control">
                                      <span id="spantxtTotal" class="input-group-addon">&nbsp;</span>
                                  </div>
                          </td>   
                            <td>&nbsp;</td>
                        </tr>
                    </thead>
                    
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