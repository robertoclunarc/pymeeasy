<?php
if (!isset($_SESSION))
	session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
    $link=crearConexion();
    $listado="SELECT porc_ganancia FROM tbl_parametros";
    $result = $link->query($listado);
    $reg = $result->fetch_assoc();

 ?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Ajuste de Precios</title>
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
if ($("#hddfkproducto").val()=="")
  {
    alert("Sin Producto Especificado");   
    return; 
  }

if ($("#hddcontador").val()=="0")
  {
    alert("No hay Registros");    
    return; 
  }

    dir_url = "registrar_cuadres_precios_db.php";
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
            //location.href = "consulta_clientes.php";
            location.reload(); //Recargar la página desde cero.
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
  if ($("#txtdescripcion_precio").val()=="" || $("#txtprecio_blt").val()==""  || $("#txtprecio_unitario").val()=="" || $("#cbomoneda").val()=="null")
  {
     return; 
  }
  var cont=parseInt($("#hddcontador").val()) + 1;
  document.getElementById('hddcontador').value=cont;
  
  var valor_col1=$("#txtdescripcion_precio").val();
  var valor_col2=$("#txtprecio_blt").val();
  var valor_col3=$("#txtprecio_unitario").val();
  var valor_col4=$("#cbomoneda").val();

  var ctrl_col1="<input name='descripcion_precio[]' type='hidden' value='" + valor_col1 + "'/>";
  var ctrl_col2="<input name='precio_blt[]' type='hidden' value='" + valor_col2 + "'/>";
  var ctrl_col3="<input name='precio_unitario[]' type='hidden' value='" + valor_col3 + "'/>";
  var ctrl_col4="<input name='moneda[]' type='hidden' value='" + valor_col4 + "'/>";

  var tabla="<tr>";
  tabla=tabla+"<td width='35%'>"+valor_col1+ctrl_col1+"<td>";
  tabla=tabla+"<td width='5%'>"+$("#cbomoneda option:selected").text()+ctrl_col4+"<td>";
  tabla=tabla+"<td width='40%'>"+valor_col2+ctrl_col2+"<td>";
  tabla=tabla+"<td width='40%'>"+valor_col3+ctrl_col3+"<td>";
  tabla=tabla+"<td width='10%'><INPUT type='button' value='-' class='btn btn-primary' onclick='$(this).parent().parent().remove(); elimFilaProd();'/><td>";
  tabla=tabla+"<tr>";
    
  $("#tblDetallesPrecios").after(tabla);

  $("#txtprecio_unitario").val("0");
  $("#txtprecio_blt").val("0");
  $("#txtdescripcion_precio").val("");
}

//(*--------------------------------------------*)

function ventanaAct(){
    var wi = 800;
    var he = 400;
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(wi/2); 
    posicion_y=(screen.height/2)-(he/2);   
    var ventana = window.open('buscar_productos_cuadre.php', "Buscar Proveedor", "width="+wi+",height="+he+",menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+"");    
 }

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
function Calc_Prec_uni(prec)
{
  var tt = parseFloat(prec) / parseFloat($("#txtcantidad_unitaria").val());
  $("#txtprecio_unitario").val(Math.round(tt*100) / 100);  
}
//(*--------------------------------------------*)
function Calc_Prec_blt(prec)
{
  var tt = parseFloat(prec) * parseFloat($("#txtcantidad_unitaria").val());
  $("#txtprecio_blt").val(Math.round(tt*100) / 100);  
}
//(*--------------------------------------------*)
function Calc_Porc()
{
  var porc = prompt("Ingrese el porcentaje de aumento","0.00");
  if (porc != null){
     var aa=(parseFloat($("#txtcosto").val())*parseFloat(porc)/100)+parseFloat($("#txtcosto").val());
     $("#txtprecio_blt").val(Math.round(aa*100) / 100);
     
     $("#txtprecio_unitario").val(Math.round(aa/parseFloat($("#txtcantidad_unitaria").val())*100) / 100);

  }
    
}
//(*--------------------------------------------*)
$(document).ready(function(){

  CargarCombo($("#cbomoneda"), "cargar_combo_db.php?tabla=tbl_divisas&campo1=id_divisa&campo2=simbologia&selected=0&orderby=simbologia&firsttext=[Elija una moneda]");
 
});
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
                    <h1 class="page-header">Ajuste de Precios</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Elementos Basicos Para el Ajuste de Precios de Productos
                        </div>
                        <div class="panel-body">
                            <div class="row">
                              <form role="form" id="formulario" method='post'>  
                                <div class="col-lg-6">
      
      <input name='txtlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session']; ?>'/>
      <input name='txtnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel']; ?>'/>
 <input name='hddfkproducto' id='hddfkproducto' type='hidden' value=''/>
 <input name='hddporc_ganancia' id='hddporc_ganancia' type='hidden' value='<?php echo $reg['porc_ganancia']; ?>'/>

 <input name='hddcontador' id='hddcontador' type='hidden' value='0'/>

<table class="table" width="90%">
  <tbody>
    <tr>
        <td width="30%"><label class="control-label">Producto</label></td>
        <td colspan="4" width="70%">
          <div class="form-group input-group">       
              <input type="text" placeholder="Producto" readonly="" class="form-control"  id="txtdescripcion_producto" >
              <span class="input-group-btn">
                  <button class="btn btn-default" onclick="ventanaAct();" type="button"><i class="fa fa-search"></i>
                  </button>
              </span>
          </div> 
        </td>
        
    </tr>
    <tr>
        <td width="30%"><label class="control-label">Marca</label></td>
        <td colspan="4" width="70%"><input name="txtmarca" id="txtmarca" readonly="" type="text" class="form-control" style="z-index: 0;"></td>
    </tr>
    <tr>
        <td width="30%"><label class="control-label">Cant. Unidades en Blt.</label></td>
        <td width="15%"><input name="txtcantidad_unitaria" id="txtcantidad_unitaria" value="0" readonly="" type="text" class="form-control" style="z-index: 0;"></td>
        <td width="5%">&nbsp;</td>
        <td width="15%"><label class="control-label">Costo + Iva</label></td>
        <td width="35%"><div class="form-group input-group">
                          <input id="txtcosto" name="txtcosto" value="0"  width="100%" readonly="" type="text" class="form-control">
                          <span class="input-group-addon">Bs.</span>
                      </div>
        </td>
        
    </tr>
	<tr>
        <td width="30%"><label class="control-label">Moneda</label></td>
        <td width="15%"><select id="cbomoneda" name="cbomoneda" onchange="" class="form-control input-sm">
               			
                	</select></td>
        <td width="5%">&nbsp;</td>
        <td width="15%"></td>
        <td width="35%"></td>
        
    </tr>
    
  </tbody>
</table>

<div class="table-responsive table-bordered">
    <table class="table">
        
        <tbody>
            <tr> 
                  <td width="5%" ><label class="control-label">Descripcion Precio:</label></td>
                  <td width="95%" colspan="4" >   
                      <input type="text" class="form-control" name="txtdescripcion_precio"  id="txtdescripcion_precio">
                  </td>
                  <td>&nbsp;</td>
                  <td><INPUT id="cmdPorc" title="Aumento Porcentual" type="button" value="%" class="btn btn-primary" onclick="Calc_Porc();" /></td>
            </tr>
             <tr>
                  
                  <td><label class="control-label">Precio Blt.:</label></td>
                  <td><div class="form-group input-group">
                          <input id="txtprecio_blt" name="txtprecio_blt" value="0"  width="100%" onkeyup="Calc_Prec_uni(this.value)" onkeypress="return validar(event)" type="text" class="form-control">
                          <span class="input-group-addon">Bs.</span>
                      </div>
                  </td>
                  <td>&nbsp;</td>
                  <td><label class="control-label">Precio Unit.:</label></td>
                  <td><div class="form-group input-group">
                          <input id="txtprecio_unitario" name="txtprecio_unitario" value="0"  width="100%" onkeyup="Calc_Prec_blt(this.value)" onkeypress="return validar(event)" type="text" class="form-control">
                          <span class="input-group-addon">Bs.</span>
                      </div>
                  </td>
                  <td>&nbsp;</td>

                  <td><INPUT id="cmdAgregar" type="button" value="+" class="btn btn-primary" onclick="AgregarFilaProd();" /></td>
            </tr>
        </tbody>
    </table>
 </div>
</div>
<div class="col-lg-6"> 
<div class="panel panel-default">
      <div class="panel-heading">
          Precios a Registrar
      </div>
                       
        <div class="panel-body">
            <div class="table-responsive">
                <table width="100%" id="tblDetallesPrecios" class="table table-striped">
                    <thead>
                        <tr>                            
                         <!--   <th>Cant.</th>  -->
                            <th width="35%">Descripcion</th>
							<th width="5%">Mnd.</th> 
                            <th width="25%">Precio Blt.</th> 
                            <th width="25%">Precio Unit.</th>   
                            <th width="10%">Del.</th>
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