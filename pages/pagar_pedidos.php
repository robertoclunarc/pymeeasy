<?php
 session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
    $link=crearConexion();
    $idpedido= isset($_GET["idped"])?$_GET["idped"]:"NULL";
    $listado="SELECT
tbl_pedidos.idpedido,
tbl_pedidos.fecha_pedido,
tbl_pedidos.fkproveedor,
tbl_pedidos.forma_pago,
tbl_pedidos.nro_operacion,
tbl_pedidos.fecha_llegada,
tbl_pedidos.subtotal,
tbl_pedidos.iva,
tbl_pedidos.total_pedido,
tbl_pedidos.estatus_pedido,
tbl_proveedores.razon_social,
tbl_proveedores.rif
FROM
tbl_pedidos
INNER JOIN tbl_proveedores ON tbl_pedidos.fkproveedor = tbl_proveedores.idproveedor
WHERE tbl_pedidos.idpedido = ".$idpedido." ORDER BY tbl_pedidos.fecha_pedido DESC";
    $result = $link->query($listado);
    $reg = $result->fetch_assoc();
    $estado=$reg['estatus_pedido'];

    $listado1="SELECT
tbl_detalles_pedidos.iddetalle_ped,
tbl_detalles_pedidos.fkpedido,
tbl_detalles_pedidos.fkproducto,
tbl_detalles_pedidos.cantidad,
tbl_detalles_pedidos.medida,
tbl_detalles_pedidos.precio_unitario,
tbl_detalles_pedidos.precio_blt,
tbl_detalles_pedidos.subtotal,
tbl_detalles_pedidos.estatus_det_pedido,
tbl_productos.idproducto,
tbl_productos.descripcion_prod,
tbl_productos.marca
FROM
tbl_productos
INNER JOIN tbl_detalles_pedidos ON tbl_detalles_pedidos.fkproducto = tbl_productos.idproducto
WHERE tbl_detalles_pedidos.fkpedido=".$idpedido;
    $result1 = $link->query($listado1);
    $row_cnt = $result1->num_rows;

$listado2="SELECT iva FROM tbl_parametros";
$result2 = $link->query($listado2);    
$reg2 = $result2->fetch_assoc();

$listado3="SELECT monto_pagar, tipo_deuda FROM tbl_cuentas_pagar WHERE tipo_deuda='PEDIDO' AND  fkdeuda =".$idpedido;
$result3 = $link->query($listado3);    
$reg3 = $result3->fetch_assoc();
$restante=$reg3['monto_pagar'];
$tipo_deuda=$reg3['tipo_deuda'];
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
    <title>Pagar Pedido</title>
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

if ($("#hddidpedido").val()=="")
  {
    alert("Sin Pedido Especificado");    
    return; 
  }

if ($("#hddcontador").val()=="0")
  {
    alert("No hay Registros de Formas de Pagos Registrados");    
    return; 
  }

    dir_url = "pagar_pedidos_db.php";
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
           location.href = "consultar_pagos.php?fkdeuda="+$("#hddidpedido").val()+"&tipodeu="+$("#hddtipo_deuda").val();
          }
         else
         {
          alert("La operaci??n Gener?? un Error: " + data);
         }
         
          //recargar la p??gina para limpiar controles
        //
        //location.reload(); //Recargar la p??gina desde cero.
               
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
                    <h1 class="page-header">Pagar Pedido</h1>
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
 <input name='hddidpedido' id='hddidpedido' type='hidden' value="<?php echo $reg['idpedido']; ?>"/>
 <input name='hddtipo_deuda' id='hddtipo_deuda' type='hidden' value="<?php echo $tipo_deuda; ?>"/>
 <input name='hddcontador' id='hddcontador' type='hidden' value="<?php echo $row_cnt; ?>"/>
<table class="table" width="90%">
  <tbody>
    <tr>
        <td width="10%"><label class="control-label">Proveedor:</label></td>
        <td width="90%"><input readonly="" name="txtrazon_social" id="txtrazon_social" type="text" class="form-control" style="z-index: 0;" value="<?php echo $reg['razon_social']; ?>" > </td>
    </tr>
    <tr>
        <td><label class="control-label">Rif:</label></td>
        <td><input readonly="" name="txtrut" id="txtrut" type="text" class="form-control" style="z-index: 0;" value="<?php echo $reg['rif']; ?>" ></td>       
    </tr>
    <tr>
     <td><label class="control-label">Estatus Pedido:</label></td>
    <td><select disabled="disabled" name="cboestado" id="cboestado" class="selectpicker show-tick form-control">
                    
                    <option <?php echo ($estado == 'POR PAGAR') ? ' selected="selected"' : '';?> value="POR PAGAR">POR PAGAR</option>
                    <option <?php echo ($estado == 'ANULADO') ? ' selected="selected"' : '';?> value="ANULADO">ANULADO</option>
          </select></td>   
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
          Detalles del Pedido
      </div>                       
        <div class="panel-body">
            <div class="table-responsive">
                <table width="100%" id="tblDetallesPedido" class="table table-striped">
                    <thead>
                        <tr>
                            <th width="50%">Producto</th>
                            <th width="5%">Cant.</th>
                            <th width="5%">&nbsp;</th>
                            <th width="20%">Precio</th> 
                            <th width="20%">Sub-Total</th> 
                            
                        </tr>
                    </thead>

                    <tbody>
                      <?php
                         $i=0;                  
                         while ($reg1 = $result1->fetch_assoc()) {
                          $precio = ($reg1['precio_blt'] == '') ? 0 : $reg1['precio_blt'];
                          $subtotales = ($reg1['subtotal'] == '') ? 0 : $reg1['subtotal'];
                      ?>
                          <tr>
                            <td>
                              <input name='hddiddetalle_ped[]' type='hidden' value='<?php echo $reg1['iddetalle_ped']; ?>'/>
                              <input name='hddfkproducto[]' type='hidden' value='<?php echo $reg1['fkproducto']; ?>'/>
                              <?php echo $reg1['descripcion_prod'].' '.$reg1['marca']; ?>
                            </td>
                            <td><input readonly="" type="text" onkeypress="return validar(event)" onkeyup="Calc_SubTotal(<?php echo $i; ?>)" class="form-control" value="<?php echo $reg1['cantidad']; ?>" ></td>
                            <td><input name='hddmedida[]' type='hidden' value='<?php echo $reg1['medida']; ?>'/><?php echo $reg1['medida']; ?></td>
                            <td><div class="form-group input-group">
                          <input readonly="" name="txtprecio[]" id="txtprecio_<?php echo $i; ?>" value="<?php echo $precio; ?>"  onkeypress="return validar(event);" onkeyup="Calc_SubTotal(<?php echo $i; ?>)" type="text" class="form-control">
                          <span class="input-group-addon">Bs.</span>
                                </div>
                            </td>

                            <td><div class="form-group input-group">
                          <input readonly="" name="txtsubtotal[]" id="txtsubtotal_<?php echo $i; ?>" value="<?php echo $subtotales; ?>" onkeypress="return validar(event)" type="text" class="form-control">
                          <span class="input-group-addon">Bs.</span>
                                </div>
                            </td>
                                                       
                        </tr>
                    <?php
                            $i++; 
                          } 
                          $iva = ($reg['iva'] == '') ? $reg2['iva'] : $reg['iva'];                          
                          $subtotal = ($reg['subtotal'] == '') ? 0 : $reg['subtotal'];
                          $total_pedido = ($reg['total_pedido'] == '') ? 0 : $reg['total_pedido'];
                    ?>    
                    </tbody>
                    <tfoot>
                      <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="text-align: right;" >Sub-Total:</th>
                        <th>

                          <div class="form-group input-group">
                          <input readonly="" name="txtsubtotal_sum" id="txtsubtotal_sum" value="<?php echo $subtotal; ?>" type="text" class="form-control">
                          <span class="input-group-addon">Bs.</span>
                          </div>
                        </th>
                                                                   
                    </tr>
                    <tr>
                        
                        <th style="text-align: right;">Iva:</th>
                        <th colspan="2">

                          <div class="form-group input-group">
                          <input readonly="" type="text" class="form-control" name="txtivaporcentaje" id="txtivaporcentaje" value="<?php echo $iva;?>" >
                          <span class="input-group-addon">%</span>
                          </div>

                        </th>
                        <th style="text-align: right;">Bs. Iva:</th>
                        <th colspan=""><div class="form-group input-group">
                         <input type="text" class="form-control" readonly="" name="txtbsiva" id="txtbsiva" value="<?php echo $iva*$subtotal/100; ?>" >
                          <span class="input-group-addon">Bs.</span>
                          </div>

                        </th>
                                                                     
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="text-align: right;">Total:</th>
                        <th><div class="form-group input-group">
                         <input type="text" class="form-control" readonly="" name="txtTotal" id="txtTotal" value="<?php echo $total_pedido; ?>" >
                          <span class="input-group-addon">Bs.</span>
                          </div>

                        </th>
                                                                    
                    </tr>
                    </tfoot>
                </table>
            </div>            
        </div>                        
  </div>
<p>&nbsp;</p>
<table class="" width="90%" id="tblGuardar" align="center">  
  <tr>
    <td width="30%">&nbsp;</td>
    <td width="40%" align="center"><?php if ($estado!='PAGADO' || $estado!='POR PAGAR'){ ?><INPUT id="cmdGuardar" type="button" value="Registrar"  class="btn btn-primary" onclick="GuardarRegistro();"/><?php } ?></td>
    <td width="30%">&nbsp;</td>
  </tr>
</table>
<?php
$result1->free();
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