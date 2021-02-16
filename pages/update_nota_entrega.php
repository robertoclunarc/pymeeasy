<?php
 session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
    $idnota= isset($_GET["idnota"])?$_GET["idnota"]:"NULL";
    $link=crearConexion();
    $listado="SELECT
    tbl_notas_entrega.idnota,    
    tbl_notas_entrega.fecha,    
    tbl_notas_entrega.iva,
    tbl_notas_entrega.subtotal,
    tbl_notas_entrega.total_neto,
    tbl_notas_entrega.excento,
    tbl_notas_entrega.estatus_nota,
    tbl_notas_entrega.divisa,
    tbl_notas_entrega.aplica_divisa,
    tbl_notas_entrega.totaliva,
    tbl_clientes.razon_social AS clientes,
    tbl_clientes.idcliente,
    tbl_vendedores.nombres AS vendedores,
    tbl_vendedores.idvendedor,
    tbl_clientes.rif as rif_cliente,
    tbl_vendedores.rif as rif_vendedor,
    tbl_clientes.direccion as direccion_cliente,
    tbl_clientes.fono as fono_cliente
    FROM
    tbl_notas_entrega
    INNER JOIN tbl_clientes ON tbl_notas_entrega.fkcliente = tbl_clientes.idcliente
    INNER JOIN tbl_vendedores ON tbl_notas_entrega.fkvendedor = tbl_vendedores.idvendedor WHERE tbl_notas_entrega.idnota=".$idnota;

    $result = $link->query($listado);
    $reg = $result->fetch_assoc();
    $idnota = $reg['idnota'];
    $idvendedor = $reg['idvendedor'];
    $rif_vendedor = $reg['rif_vendedor'];
    $nombres = $reg['vendedores'];
    $idcliente = $reg['idcliente'];
    $rif_cliente = $reg['rif_cliente'];
    $razon_social = $reg['clientes'];
    $fecha = $reg['fecha'];
    $subtotal = $reg['subtotal'];
    $total_neto = $reg['total_neto'];
    $excento = $reg['excento'];
    $estatus_venta = $reg['estatus_nota'];
    $iva = $reg['iva'];
    $totaliva= $reg['totaliva'];
    $aplica_divisa= $reg['aplica_divisa'];
    $divisa= $reg['divisa'];

    if ($aplica_divisa=='S')
      {$aplica_divisa='checked'; $simbolo='$';}
    else
     { $aplica_divisa='';$simbolo='Bs.';}

    if ($totaliva>0)
      $aplica_iva='checked';
    else
      $aplica_iva='';

    $result->free();

    $listado1="SELECT
    tbl_detalles_notas_entrega.cantidad,
    tbl_detalles_notas_entrega.medida,
    tbl_detalles_notas_entrega.precio,
    tbl_detalles_notas_entrega.precio_total,
    tbl_detalles_notas_entrega.fkproducto,
    tbl_detalles_notas_entrega.estatus_nota AS estatus_det_nota,
    CONCAT(tbl_productos.descripcion_prod,' ',tbl_productos.marca) AS descripcion_prod,
    tbl_productos.excepto_iva,
    tbl_detalles_notas_entrega.fknota
    FROM
    tbl_detalles_notas_entrega
    INNER JOIN tbl_productos ON tbl_detalles_notas_entrega.fkproducto = tbl_productos.idproducto WHERE tbl_detalles_notas_entrega.fknota=".$idnota;
    $result1 = $link->query($listado1);
    $cant=$result1->num_rows;
 ?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Nota Entrega</title>
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

if ($("#hddcontador").val()=="0")
  {
    alert("No hay Registros de Detalles");    
    return; 
  }

    dir_url = "update_ventas_db.php";
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
  $("#txtTotal").val(subtotal-excent);

} 
//(*--------------------------------------------*)
function AgregarFilaProd()
{
if (valida_repetido($("#cbofkproducto").val())){
        alert ("Producto Ya Incluido");
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

  var ctrl_col1="<input name='fkproducto[]' type='hidden' value='" + valor_col1 + "'/>";
  var ctrl_col2="<input name='cantidad[]' type='hidden' value='" + valor_col2 + "'/>";
  var ctrl_col3="<input name='medida[]' type='hidden' value='" + valor_col3 + "'/>";
  var ctrl_col4="<input name='precio[]' type='hidden' value='" + valor_col4 + "'/>";
  
  var tabla="<tr>";
  tabla=tabla+"<td width='40%'>"+text_col1+ctrl_col1+"<td>";
  tabla=tabla+"<td width='10%'>"+valor_col2+valor_col3+ctrl_col2+ctrl_col3+"<td>";
  tabla=tabla+"<td width='25%'>"+valor_col4+ctrl_col4+"<td>";
  tabla=tabla+"<td width='25%'>"+valor_col5+"<td>";
  tabla=tabla+"<td width='5%'><INPUT type='button' value='-' class='btn btn-primary' onclick='$(this).parent().parent().remove(); elimFilaProd("+valor_col5+");'/><td>";
  tabla=tabla+"<tr>";
    
  $("#tblDetallesPrecios").after(tabla);

  $("#txtcantidad").val("0");

  var subtotal = parseFloat($("#txtsubTotal").val()) + valor_col5;
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
      if ($("#hddexcepto_iva_"+i).val()=='S')
         if($("#hddprecio_total_"+i).length != 0) {
           stt=parseFloat($("#hddprecio_total_"+i).val());           
           excp=excp+stt;
        }          
    }
    return excp;
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
                    <h1 class="page-header">Nota Entrega ID.<?php echo  $idnota; ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Elementos Basicos para la Nota de Entrega ID <?php echo  $idnota.'. FECHA: '.$fecha; ?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                              <form role="form" id="formulario" method='post'>  
                                <div class="col-lg-12">      
      <input name='txtlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session']; ?>'/>
      <input name='hddnivel' id='hddnivel' type='hidden' value='<?php echo $_SESSION['nivel']; ?>'/>
 <input name='hddidventa' id='hddidventa' type='hidden' value='<?php echo $idventa; ?>'/>
 <input name='hddcontador' id='hddcontador' type='hidden' value='<?php echo $cant; ?>'/>
<table class="table" width="90%">
  <tbody>
    <tr>
        <td width="10%">Cliente:</td>
        <td width="40%">
          <input type="text" class="form-control" readonly="" value="<?php echo $razon_social; ?>"  id="txtrazon_social" > 
        </td>
        <td width="10%">Vendedor:</td>
        <td width="40%"><input type="text" class="form-control" value="<?php echo $nombres; ?>" readonly=""  id="txtvendedor" ></td>
    </tr>    
    <tr>
        <td><label class="control-label">Estatus</label></td>
        <td><select <?php if($estatus_venta!='PENDIENTE') echo 'disabled'; ?> id="cboestatus_venta" data-show-subtext="true" name="cboestatus_venta" class="form-control">
                          <option <?php if($estatus_venta=='PENDIENTE') echo 'selected'; ?> value="PENDIENTE">PENDIENTE</option>
                           <option <?php if($estatus_venta=='PAGADA') echo 'selected'; ?> value="PAGADA">PAGADA</option>
                          <option <?php if($estatus_venta=='ANULADA') echo 'selected'; ?> value="ANULADA">ANULADA</option>          
                        </select>
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
</div>

<div class="col-lg-12"> 
<div class="panel panel-default">
      <div class="panel-heading">
          Detalles de la Nota Entrega
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
                        </tr>
                    </thead>
                    <tbody>                        
                    </tbody>
                </table>
                <table width="100%" class="table table-striped">
                    <thead>
                     <?php
                         $i=0;                  
                         while ($reg1 = $result1->fetch_assoc()) {
                          $i++;
                      ?>
                      

                        <tr>                     
                            <td width="40%"><?php echo $reg1['descripcion_prod']; ?></td>
                            <td width="10%"><?php echo $reg1['cantidad'].$reg1['medida']; ?></td>
                            <td width="25%"><?php echo $reg1['precio']; ?></td> 
                            <td width="25%"><?php echo $reg1['precio_total']; ?>
                              <input name='hddprecio_total[]' id='hddprecio_total_<?php echo $i; ?>' type='hidden' value='<?php echo $reg1['precio_total']; ?>'/>
                              <input name='hddexcepto_iva[]' id='hddexcepto_iva_<?php echo $i; ?>' type='hidden' value='<?php echo $reg1['excepto_iva']; ?>'/>
                            </td>
                        </tr>
                      <?php                    
                         }
                         $result1->free();
                         $link->close();
                      ?>  
                        <tr>                     
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="text-align: right;"><label class="control-label">SUB-TOTAL:</label></td> 
                            <td><div class="form-group input-group">
                          						<input id="txtsubTotal" name="txtsubTotal" readonly="" value="<?php echo $subtotal; ?>" width="100%" type="text" class="form-control">
                          						<span class="input-group-addon"><?php echo $simbolo; ?></span>
                      						</div>
                      		</td>   
                            
                        </tr>
                        <tr>                     
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="text-align: right;"><label class="control-label">Descuento:</label></td> 
                            <td><div class="form-group input-group">
                                      <input id="txtexcento" onkeyup="Calc_Total(this.value)" onblur="validar_exc(this.value)" onkeypress="return validar(event)" name="txtexcento" <?php if($estatus_venta!='PENDIENTE') echo 'readonly'; ?> value="<?php echo $excento; ?>" width="100%" type="text" class="form-control">
                                      <span class="input-group-addon"><?php echo $simbolo; ?></span>
                                  </div>
                          </td>   
                            
                        </tr>
                        <tr>                     
                            <td>&nbsp;</td>
                            <td><div class="checkbox">
                                                <label>
                                                    <input id="chkiva" name="chkiva" <?php echo $aplica_iva; ?> type="checkbox" onclick="aplicar_iva()" value="<?php echo $iva; ?>">Aplicar Iva <?php echo $iva.'%'; ?>
                                                </label>
                                            </div></td>
                            <td style="text-align: right;"><label class="control-label">Total Iva:</label></td> 
                            <td><div class="form-group input-group">
                                      <input id="txttotaliva"  name="txttotaliva" value="<?php echo $totaliva; ?>" width="100%" type="text" readonly="" class="form-control">
                                      <span id="spantotaliva" class="input-group-addon"><?php echo $simbolo; ?></span>
                                  </div>
                          </td>   
                            <td>&nbsp;</td>
                        </tr>
                        <tr>                     
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="text-align: right;"><label class="control-label">TOTAL:</label></td> 
                            <td><div class="form-group input-group">
                                      <input id="txtTotal" name="txtTotal" value="<?php echo $total_neto; ?>" readonly="" width="100%" type="text" class="form-control">
                                      <span class="input-group-addon"><?php echo $simbolo; ?></span>
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
    <td width="40%" align="center"><INPUT id="cmdGuardar" type="button" value="Registrar"  class="btn btn-primary" onclick="GuardarRegistro();"/></td>
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