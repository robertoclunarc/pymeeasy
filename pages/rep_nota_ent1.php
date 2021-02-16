<?php
 session_start();
if (isset($_SESSION['username']) && isset($_SESSION['userid']) ){
  require_once('menu.php');
  require_once('menu2.php');
  include("../BD/conexion.php");
  $link=crearConexion();

  $nota= isset($_GET["id_nota"])?$_GET["id_nota"]:"NULL";
  $chkdivisa= isset($_GET["chkdivisa"])?$_GET["chkdivisa"]:"N";

  if  ($chkdivisa=='S') 
     $chkdivisa='checked';
   else $chkdivisa='';

  $query="SELECT DISTINCT tbl_detalles_notas_entrega.fkproducto,
tbl_productos.descripcion_prod
FROM
tbl_detalles_notas_entrega
INNER JOIN tbl_productos ON tbl_detalles_notas_entrega.fkproducto = tbl_productos.idproducto
ORDER BY tbl_productos.descripcion_prod";
  $result = $link->query($query);
  $option2="";  
  if ($result->num_rows > 0)
  {     
    while ($fila = $result->fetch_assoc()) 
    {
        $option2.= "<option value='". $fila['fkproducto']."'" ;
        $option2.= ">".$fila['descripcion_prod']. "</option>";
    }
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
    <title>Rep. Notas de Ent.</title>
    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

     <link href="../css/estilo.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="../bootstrap/bootstrap-select-1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="../bootstrap/bootstrap-select-1.13.14/dist/css/bootstrap-select.css">    
    
    <script src="../js/jquery-1.11.1.min.js"></script>
<script  language="javascript">
//(*--------------------------------------------*)
function Buscar()
{
 var estado=$("#cboestatus").val(); 
 $("#hddestatus").val(estado);
 
 var product=$("#cbofkproducto").val();
 $("#hddidproducto").val(product);
 
 $.ajax({
          type: "POST",
          url: "rep_nota_ent1_bd.php",
          data: $("#formulario").serialize(),
          dataType: "html",
          beforeSend: function(){
                //imagen de carga
                $("#capa1").html("<p align='center'><img src='images/ajax_loader.gif' /></p>");
          },
          error: function(){
                alert("error petición ajax");
          },
          success: function(data){                                                   
                //alert(data); 
                $("#capa1").empty();
                $("#capa1").append(data);
                $("#hddidnota").val("NULL");
          }
    });
}
//(*--------------------------------------------*)
function imprimir(idnota)
{
    var wi = 800;
    var page = "imp_nota_entrega01.php?idnota="+idnota;
    var he = 400;
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(wi/2); 
    posicion_y=(screen.height/2)-(he/2);   
    var ventana = window.open(page, "Imprimir", "width="+wi+",height="+he+",menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+"");          
   
}
//(*--------------------------------------------*)
function detalles(idnota){
    window.location="update_nota_entrega.php?idnota="+idnota;  
 }
//(*--------------------------------------------*)
function abonar(idventa){
    window.location="abonar.php?vista=vw_notas_entregas&idventa="+idventa;  
 }
 //(*--------------------------------------------*)
 function enlazar_aventa(idventa){
    window.location="update_ventas.php?vista=vw_notas_entregas&idventa="+idventa;  
 }
 //(*--------------------------------------------*)
function exportar_excel(){

  var estado=$("#cboestatus").val(); 
 $("#hddestatus").val(estado);
 
 var product=$("#cbofkproducto").val();
 $("#hddidproducto").val(product);

    var desde=$("#txtfechaini").val();
    var hasta=$("#txtfechafin").val();
    var namexls="NotasdeCredito_"+desde+"al"+hasta+".xls";
    var inpt = '<table><thead><tr><th>'+'Notas de Credito '+'</th><th>'+'Fecha: '+desde+' al '+hasta+'</th></tr></thead></table>';

       $.ajax({
              type: "POST",
              url: "rep_nota_ent1_bd.php?xls=1",
              data: $("#formulario").serialize(),
              dataType: "html",
              success: function(data){
                    $("#capa1").empty();
                    $("#capa1").append(data);
                    var link = document.createElement('a');
                    document.body.appendChild(link); // Firefox requires the link to be in the body
                    link.download = namexls;
                    link.href = 'data:application/vnd.ms-excel,' + escape(inpt+data);
                    link.click();
                    document.body.removeChild(link);                
              }
        });  
}
//(*--------------------------------------------*)
function limpiar(campo)
{
 $('#txt'+campo).val("");
 $('#hddid'+campo).val("");  
}
//(*--------------------------------------------*)
function ventanaAct2(page){
  
  var wi = 800;
    var he = 400;
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(wi/2); 
    posicion_y=(screen.height/2)-(he/2);   
    var ventana = window.open(page, "Buscar", "width="+wi+",height="+he+",menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+"");
     
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
 $('.selectpicker').selectpicker();
  //CargarCombo($("#cboformapago"), "cargar_combo_db.php?tabla=tbl_modalidades_pagos&campo1=descripcion&selected=0&orderby=descripcion&firsttext=[Elija forma de pago]");
  if ($("#hddidnota").val()!="NULL")
     Buscar();
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
                    <h1 class="page-header">Reporte de Notas de Entrega</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Elementos Basicos de Busqueda
                        </div>
                        <div class="panel-body">
                            <div class="row">
                               
                              <form role="form" id="formulario" method='post'>
                               <div class="col-lg-6">     
      
      <input name='txtlogin' id='txtlogin' type='hidden' value='<?php echo $_SESSION['user_session']; ?>'/>
      <input name='txtnivel' id='txtnivel' type='hidden' value='<?php echo $_SESSION['nivel']; ?>'/>
      <input name='hddidcliente' id='hddidcliente' type='hidden'/>
      <input name='hddidvendedor' id='hddidvendedor' type='hidden'/>
      <input name='hddidproducto' id='hddidproducto' type='hidden'/>
      <input name='hddestatus' id='hddestatus' type='hidden'/>
      <input name='hddidnota' id='hddidnota' value="<?php echo $nota; ?>" type='hidden'/>
   
<table width="90%">     
    <tr>
      <td width="10%"><label>Cliente:</label></td>
      <td width="10%"> &nbsp;</td>
      <td width="70%"><div class="form-group input-group">
          <input readonly="" name="txtcliente" id="txtcliente" type="text" class="form-control">
          <span class="input-group-btn">
              <button class="btn btn-default" onclick="ventanaAct2('buscar_clientes_rep1.php');" type="button"><i class="fa fa-search"></i>
              </button>
          </span>          
      </div></td>
    <td width="10%"><A onclick="limpiar('cliente');" href="#" title="Limpiar"><IMG SRC="images/limpiar.png" WIDTH="33px" HEIGHT="33px"></A></td>
    </tr>
    
   
    <tr>
      <td><label>Vendedor:</label></td>
      <td> &nbsp;</td>
      <td><div class="form-group input-group">
          <input readonly="" name="txtvendedor" id="txtvendedor" type="text" class="form-control">
          <span class="input-group-btn">
              <button class="btn btn-default" onclick="ventanaAct2('buscar_vendedor_rep1.php');" type="button"><i class="fa fa-search"></i>
              </button>
          </span>
      </div></td>
      <td><A onclick="limpiar('vendedor');" href="#" title="Limpiar"><IMG SRC="images/limpiar.png" WIDTH="33px" HEIGHT="33px"></A></td>      
    </tr>   
        
  <tr>
      <td><label>Producto:</label></td>
      <td> &nbsp;</td>
      <td><select id="cbofkproducto" name="cbofkproducto" data-width="70%" data-size="5" class="selectpicker" data-actions-box="true" multiple data-live-search="true">
  <option value="NULL" disabled selected>Seleccione uno o mas Productos</option>
    <?php echo $option2;?>
</select>

    </td>
      <td> &nbsp;</td>
    </tr>  
  <tr>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>           
  </tr>
    
</table>
</div>
<div class="col-lg-6">
  <table width="90%">
    <tr>
    <td><label>Fecha Desde:</label></td>
    <td> &nbsp;</td>
     <td><INPUT type="date" id="txtfechaini" name="txtfechaini" value="<?php

echo date("Y-m-d",time()-3600);

?>"  width="100%" class="form-control"/></td> 
  </tr>
  <tr>
       <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>         
    </tr>
  <tr>
    <td><label>Fecha Hasta:</label></td>
    <td> &nbsp;</td>
    <td><INPUT type="date" id="txtfechafin" name="txtfechafin"  width="100%" value="<?php

echo date("Y-m-d",time()-3600);

?>" class="form-control"/></td>
  </tr>
  <tr>
      <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>      
  </tr>
  <tr>
      <td><label>Estatus:</label></td>
      <td> &nbsp;</td>
      <td><select id="cboestatus" name="cboestatus" data-actions-box="true" multiple class="selectpicker" data-live-search="false">
  <option value="NULL" disabled selected>Seleccione Estatus</option>
   <option selected value="PENDIENTE">PENDIENTE</option>
   <option selected value="PAGADA">PAGADA</option>
   <option selected value="ANULADA">ANULADA</option>
</select></td>
  </tr>
  <tr>
      <td> &nbsp;</td>
       <td> &nbsp;</td>
       <td> &nbsp;</td>      
  </tr>
  
  </table>
 </div> 
 
<table class="" width="90%" id="tblGuardar" align="center">  
  <tr>
    <td align="right" width="25%"><A onclick="exportar_excel()" href="#" title="Exportar a Excel"><IMG SRC="images/factura.png" WIDTH="33px" HEIGHT="33px"></A></td>
    <td width="25%" align="center"><INPUT id="cmdGuardar" type="button" value="Consultar" class="btn btn-primary" onclick="Buscar();"/></td>
    <td align="left" width="25%"><input id="chkdivisa" <?php echo $chkdivisa; ?> name="chkdivisa" type="checkbox" value="S"><label>&nbsp;Aplica Divisa</label></td>
    <td align="left" width="25%"><input id="chkotramoneda" name="chkotramoneda" type="checkbox" value="S"><label>&nbsp;Convertir a otra Moneda</label></td>
  </tr>
</table>
<p>&nbsp;</p>
<div id="capa1" class="col-lg-12">
</form>
</div>  
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

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    <script src="../bootstrap/bootstrap-select-1.13.14/dist/js/bootstrap-select.js"></script>
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