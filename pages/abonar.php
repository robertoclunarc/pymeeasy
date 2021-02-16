<?php
if (!isset($_SESSION))
  session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
    $idventa= isset($_GET["idventa"])?$_GET["idventa"]:"NULL";
    $vista = isset($_GET["vista"])?$_GET["vista"]:"vw_ventas";
    $link=crearConexion();

    $listado="SELECT * FROM ".$vista." WHERE idventa=".$idventa;
    
    $result = $link->query($listado);
    $reg = $result->fetch_assoc();
    $idventa = $reg['idventa'];
    $idvendedor = $reg['idvendedor'];
    $rif_vendedor = $reg['rif_vendedor'];
    $nombres = $reg['nombres'];
    $idcliente = $reg['idcliente'];
    $rif_cliente = $reg['rif_cliente'];
    $razon_social = $reg['razon_social'];
    $fecha = $reg['fecha'];
    $subtotal = $reg['subtotal'];
    $total_neto = $reg['total_neto'];
    $excento = $reg['excento'];
    $estatus_venta = $reg['estatus_venta'];
    $result->free();

    $listado1="SELECT fkventa, monto, modalidad_pago, fecha, nro_referencia FROM tbl_abonos WHERE fkventa=".$idventa;
    $result1 = $link->query($listado1);

 ?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Abonos</title>
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
function GuardarRegistro(vista)
{
    var consul='';
    if ($("#hddcontador").val()=="0")
    {
      alert("No hay Registros de Detalles");    
      return; 
    }

    dir_url = "abonar_db.php?vista="+vista;
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
                  
                  if (vista=='vw_notas_entregas')
                    consul='vw_pagos_notas_entregas';
                  else
                    consul='vw_abonos';

                  alert("Registrado Correctamente!");
                  location.href = "consultar_abonos.php?idventa="+data+"&vista="+consul;
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
  var subtotal = parseFloat($("#txtTotaln").val()) - parseFloat(subtot);
  $("#txtTotaln").val(subtotal);

  var rest = parseFloat($("#txtrestante").val())+parseFloat(subtot);
  $("#txtrestante").val(rest);

} 
//(*--------------------------------------------*)
function AgregarFilaProd()
{

if ($("#txtmonto").val()=="" || parseFloat($("#txtcantidad").val())==0)
  {
     alert ("No Hay Monto Registrado");
     $("#txtmonto").focus();
     return; 
  }

if (parseFloat($("#txtmonto").val())>parseFloat($("#txtrestante").val()))
  {
     alert ("El monto no debe ser mayor al restante a pagar");
     $("#txtmonto").focus();
     return; 
  }

  var cont=parseInt($("#hddcontador").val()) + 1;
  document.getElementById('hddcontador').value=cont;

  var valor_col1=$("#cbomodalidad_pago").val();  
  var valor_col2=$("#txtnro_referencia").val();
  if (valor_col2=='')
    valor_col2='N/A';
  var valor_col3=$("#txtmonto").val(); 
  var rest = $("#txtrestante").val();
  var restante = Math.round((rest-parseFloat(valor_col3))*100) / 100;

  var ctrl_col1="<input name='modalidad_pago[]' type='hidden' value='" + valor_col1 + "'/>";
  var ctrl_col2="<input name='nro_referencia[]' type='hidden' value='" + valor_col2 + "'/>";
  var ctrl_col3="<input name='monto[]' type='hidden' value='" + valor_col3 + "'/>";
  
  var tabla="<tr>";
  tabla=tabla+"<td width='45%'>"+valor_col2+ctrl_col2+"<td>";
  tabla=tabla+"<td width='40%'>"+valor_col1+ctrl_col1+"<td>";
  tabla=tabla+"<td width='40%'>"+valor_col3+ctrl_col3+"<td>";
  tabla=tabla+"<td width='5%'><INPUT type='button' value='-' class='btn btn-primary' onclick='$(this).parent().parent().remove(); elimFilaProd("+valor_col3+");'/><td>";
  tabla=tabla+"<tr>";
    
  $("#tblDetallesPrecios").after(tabla);

  $("#txtnro_referencia").val("");
  $("#txtmonto").val("");

  var total = parseFloat($("#txtTotaln").val()) + parseFloat(valor_col3);  
  $("#txtrestante").val(restante);
  $("#txtTotaln").val(total); 
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
$(document).ready(function(){

  CargarCombo($("#cbomodalidad_pago"), "cargar_combo_db.php?tabla=tbl_modalidades_pagos&campo1=descripcion&orderby=descripcion");
});
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
                    <h1 class="page-header">Abonos</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Elementos Basicos para el Abono de la Venta ID <?php echo  $idventa.'. FECHA: '.$fecha; ?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                              <form role="form" id="formulario" method='post'>  
                                <div class="col-lg-12">
      
      <input name='txtlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session']; ?>'/>
      <input name='hddnivel' id='hddnivel' type='hidden' value='<?php echo $_SESSION['nivel']; ?>'/>
 <input name='hddidventa' id='hddidventa' type='hidden' value='<?php echo $idventa; ?>'/>
 <input name='hddcontador' id='hddcontador' type='hidden' value='0'/>

<table class="table" width="90%">
  <tbody>
    <tr>
        <td width="10%"><label class="control-label">Cliente:</label></td>
        <td width="40%">
          <input type="text" class="form-control" readonly="" value="<?php echo $razon_social; ?>"  id="txtrazon_social" > 
        </td>
        <td style="text-align: right;" width="10%"><label class="control-label">Vendedor:</label></td>
        <td width="35%"><input type="text" class="form-control" value="<?php echo $nombres; ?>" readonly=""  id="txtvendedor" ></td>
        <td width="5%">&nbsp;</td>
    </tr>    
    <tr>
        <td><label class="control-label">Forma de Pago</label></td>
        <td><select id="cbomodalidad_pago" name="cbomodalidad_pago" class="form-control"></select>
        </td>
        <td style="text-align: right;"><label class="control-label">TOTAL:</label></td> 
        <td><div class="form-group input-group">
                  <input id="txtTotal" name="txtTotal" value="<?php echo $total_neto; ?>" readonly="" width="100%" type="text" class="form-control">
                  <span class="input-group-addon">Bs.</span>
              </div>
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
        <td><label class="control-label">N° Referencia:</label></td>
        <td >
          <input type="text" class="form-control" name="txtnro_referencia" value=""  id="txtnro_referencia" > 
        </td>
        <td style="text-align: right;"><label class="control-label">Monto:</label></td>
        <td><div class="form-group input-group">
                  <input id="txtmonto" name="txtmonto" placeholder="0.00" onkeypress="return validar(event)" value="" width="100%" type="text" class="form-control">
                  <span class="input-group-addon">Bs.</span>
              </div></td>
       <td><INPUT id="cmdAgregar" type="button" value="+" class="btn btn-primary" onclick="AgregarFilaProd();" /></td>       
    </tr>
  </tbody>
</table>
</div>

<div class="col-lg-12"> 
<div class="panel panel-default">
      <div class="panel-heading">
          Detalles del Pago
      </div>
                       
        <div class="panel-body">
            <div class="table-responsive">
                <table width="100%" id="tblDetallesPrecios" class="table table-striped">
                    <thead>
                        <tr>                     
                            <th width="20%">Fecha&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| N° Referencia</th>
                            <th width="20%">Forma de Pago</th>
                            <th width="10%">Monto</th>                            
                        </tr>
                    </thead>
                    <tbody>                       
                       <?php
                        $montorest=0;
                        while ($reg1 = $result1->fetch_assoc()) { ?>
                           <tr>
                            <td><?php echo $reg1['fecha'].' | '.$reg1['nro_referencia']; ?></td>
                            <td><?php echo $reg1['modalidad_pago']; ?></td>
                            <td><?php echo $reg1['monto']; ?></td>
                           </tr> 
                       <?php 
                       $montorest+=$reg1['monto']; 
                     } ?>                             
                    </tbody>
                </table>
                <table width="100%" class="table table-striped">
                    <thead>
                        <tr>
                            <td width="20%">&nbsp;</td>
                            <td width="20%" style="text-align: right;"><label class="control-label">TOTAL:</label></td> 
                            <td width="10%"><div class="form-group input-group">
                                      <input id="txtTotaln" name="txtTotaln" value="<?php echo $montorest; ?>" readonly="" width="100%" type="text" class="form-control">
                                      <span class="input-group-addon">Bs.</span>
                                  </div>
                          </td>   
                          <tr>
                            <td width="20%">&nbsp;</td>
                            <td width="20%" style="text-align: right;"><label class="control-label">Restante:</label></td> 
                            <td width="10%"><div class="form-group input-group">
                                      <input id="txtrestante" name="txtrestante" value="<?php echo round($total_neto-$montorest,2); ?>" readonly="" width="100%" type="text" class="form-control">
                                      <span class="input-group-addon">Bs.</span>
                                  </div>
                          </td>  
                        </tr>
                    </thead>
                    
                </table>
            </div>
        </div>          
  </div>

<p>&nbsp;</p>
<?php if($estatus_venta=='PENDIENTE'){ ?>
<table class="" width="90%" id="tblGuardar" align="center">  
  <tr>
    <td width="30%">&nbsp;</td>
    <td width="40%" align="center"><INPUT id="cmdGuardar" type="button" value="Registrar" class="btn btn-primary" onclick="GuardarRegistro('<?php echo $vista ?>');"/></td>
    <td width="30%">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<?php } ?>
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