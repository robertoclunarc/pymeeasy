<?php
 session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
    $link=crearConexion();
    $idven= isset($_GET["idven"])?$_GET["idven"]:"NULL";
    $listado="SELECT
tbl_precios_ventas_vendedores.fkventa,
tbl_precios_ventas_vendedores.fkproducto,
tbl_precios_ventas_vendedores.precio_vendedor,
tbl_precios_ventas_vendedores.cantidad,
tbl_precios_ventas_vendedores.total,
concat(tbl_productos.descripcion_prod,' ',tbl_productos.marca) as producto
FROM
tbl_precios_ventas_vendedores
INNER JOIN tbl_productos ON tbl_precios_ventas_vendedores.fkproducto = tbl_productos.idproducto WHERE tbl_precios_ventas_vendedores.fkventa  = ".$idven." ORDER BY tbl_precios_ventas_vendedores.fkproducto";
    $result = $link->query($listado);

$listado2="SELECT
tbl_vendedores.nombres,
tbl_ventas.fecha,
tbl_ventas.idventa,
tbl_clientes.razon_social
FROM
tbl_ventas
INNER JOIN tbl_vendedores ON tbl_ventas.fkvendedor = tbl_vendedores.idvendedor
INNER JOIN tbl_clientes ON tbl_ventas.fkcliente = tbl_clientes.idcliente WHERE tbl_ventas.idventa  = ".$idven;
    $result2 = $link->query($listado2);
    $reg2 = $result2->fetch_assoc();
    
$listado3="SELECT monto_pagar, tipo_deuda, estatus_ctapag FROM tbl_cuentas_pagar WHERE tipo_deuda='VENDEDOR' AND  fkdeuda =".$idven;
$result3 = $link->query($listado3);    
$reg3 = $result3->fetch_assoc();
$restante=$reg3['monto_pagar'];
$tipo_deuda=$reg3['tipo_deuda'];
$estado=$reg3['estatus_ctapag'];
$result3->free();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Pagar Vendedor</title>
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

if ($("#hddidventa").val()=="")
  {
    alert("Sin venta especificada");    
    return; 
  }

if ($("#hddcontador").val()=="0")
  {
    alert("No hay Registros de Formas de Pagos Registrados");    
    return; 
  }

    dir_url = "pagar_vendedor_db.php";
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
           location.href = "consultar_pagos.php?fkdeuda="+$("#hddidventa").val()+"&tipodeu="+$("#hddtipo_deuda").val();
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

  //var total = parseFloat($("#txtTotaln").val()) + parseFloat(valor_col3);  
  $("#txtrestante").val(restante);
  //$("#txtTotaln").val(total); 
}
//(*--------------------------------------------*)
function elimFilaProd(monto)
{
  var cont=parseInt($("#hddcontador").val()) - 1;
  document.getElementById('hddcontador').value=cont;

  var restante = parseFloat($("#txtrestante").val()) + monto;
  $("#txtrestante").val(restante);
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
function Calc_SubTotal(i)
{
  var suttotal = 0;
  var antes = parseFloat($("#txtsubtotal_"+i).val());
  var ahora = 0;
  var piva = parseFloat($("#txtivaporcentaje").val());
  var montoiva = 0;
  var monto_total = 0;
  subtotal=parseFloat($("#txtcantidad_"+i).val()) * parseFloat($("#txtprecio_"+i).val());
  $("#txtsubtotal_"+i).val(subtotal);
 
  ahora = parseFloat($("#txtsubtotal_sum").val())-antes + subtotal;

  $("#txtsubtotal_sum").val(ahora);
  montoiva=Math.round(piva * ahora) / 100;
  $("#txtbsiva").val(montoiva);

  monto_total = ahora + montoiva;
  $("#txtTotal").val(Math.round(monto_total*100) / 100);
}
//(*--------------------------------------------*)
function ReCalc_SubTotal(i)
{
  var antes = parseFloat($("#txtsubtotal_"+i).val());
  var ahora = 0;
  var piva = parseFloat($("#txtivaporcentaje").val());
  var montoiva = 0;
  var monto_total = 0;  
 
  ahora = parseFloat($("#txtsubtotal_sum").val())-antes;

  $("#txtsubtotal_sum").val(ahora);
  montoiva=Math.round(piva * ahora) / 100;
  $("#txtbsiva").val(montoiva);

  monto_total = ahora + montoiva;
  $("#txtTotal").val(Math.round(monto_total*100) / 100);
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
                    <h1 class="page-header">Pagar a Vendedor</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Elementos Basicos de la Venta ID. <?php echo $reg2['idventa'].' Fecha: '.$reg2['fecha']; ?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                              <form role="form" id="formulario" method='post'>  
                                <div class="col-lg-6">
      
      <input name='txtlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session']; ?>'/>
      <input name='txtnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel']; ?>'/>
 <input name='hddidventa' id='hddidventa' type='hidden' value="<?php echo $idven; ?>"/>
 <input name='hddtipo_deuda' id='hddtipo_deuda' type='hidden' value="<?php echo $tipo_deuda; ?>"/>
 <input name='hddcontador' id='hddcontador' type='hidden' value=""/>
<table class="table" width="90%">
  <tbody>
    <tr>
        <td width="10%"><label class="control-label">Vendedor:</label></td>
        <td width="90%"><input readonly="" name="txtnombre" id="txtnombre" type="text" class="form-control" style="z-index: 0;" value="<?php echo $reg2['nombres']; ?>" > </td>
    </tr>
    <tr>
        <td><label class="control-label">Cliente:</label></td>
        <td><input readonly="" name="txtcliente" id="txtcliente" type="text" class="form-control" style="z-index: 0;" value="<?php echo $reg2['razon_social']; ?>" ></td>       
    </tr>
    
   <tr>
  <td><label class="control-label">Credito:</label></td>
  <td><div class="form-group input-group">
       <input readonly="" name="txtrestante" id="txtrestante" placeholder="0.0" value="<?php echo $restante; ?>" onkeypress="return validar(event);" type="text" class="form-control">
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
</div>

<div class="col-lg-12"> 
<div class="panel panel-default">
      <div class="panel-heading">
          Detalles de la Venta
      </div>                       
        <div class="panel-body">
            <div class="table-responsive">
                <table width="100%" id="tblDetallesPedido" class="table table-striped">
                    <thead>
                        <tr>
                            <th width="50%">Producto</th>
                            <th width="10%">Cant.</th>                            
                            <th width="20%">Precio</th> 
                            <th width="20%">Total</th> 
                            
                        </tr>
                    </thead>

                    <tbody>
                      <?php
                                          
                         while ($reg = $result->fetch_assoc()) {                          
                      ?>
                          <tr>
                            <td><?php echo $reg['producto']; ?></td>
                            <td><?php echo $reg['cantidad']; ?></td>
                            <td><?php echo $reg['precio_vendedor']; ?></td>
                            <td><?php echo $reg['total']; ?></td>                        
                        </tr>
                    <?php                           
                          }
                    ?>    
                    </tbody>                    
                </table>
            </div>            
        </div>                        
  </div>
<p>&nbsp;</p>
<table class="" width="90%" id="tblGuardar" align="center">  
  <tr>
    <td width="30%">&nbsp;</td>
    <td width="40%" align="center"><?php if ($estado!='PAGADO'){ ?><INPUT id="cmdGuardar" type="button" value="Registrar"  class="btn btn-primary" onclick="GuardarRegistro();"/><?php } ?></td>
    <td width="30%">&nbsp;</td>
  </tr>
</table>
<?php

$result2->free();
$link->close();
?>
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