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
    <title>Reporte Reg. Contable</title>
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
//(*--------------------------------------------*)
function Buscar(page)
{
 var wi = 800;
    var he = 400;
    var posicion_x; 
    var posicion_y; 
    posicion_x=(screen.width/2)-(wi/2); 
    posicion_y=(screen.height/2)-(he/2);   
    var ventana = window.open(page, "Buscar", "width="+wi+",height="+he+",menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+"");          
   
}
//(*--------------------------------------------*)
function limpiar(campo)
{
 $('#txt'+campo).val("");
 $('#hddid'+campo).val("");  
}
//(*--------------------------------------------*)
function ventanaAct2(){
  
  var fechaini=$("#txtfechaini").val();
  var fechafin=$("#txtfechafin").val();
  
  var idc='NULL';
  if ($("#hddidcliente").val()!='')
      idc=$("#hddidcliente").val();

  var idv='NULL';
  if ($("#hddidvendedor").val()!='')
     idv=$("#hddidvendedor").val();

  var idp='NULL';
  if ($("#hddidproducto").val()!='')
      idp=$("#hddidproducto").val();

  var formapago =$("#cboformapago").val();       
  var wi = 800;
  var he = 400;
  var posicion_x; 
  var posicion_y; 
  posicion_x=(screen.width/2)-(wi/2); 
  posicion_y=(screen.height/2)-(he/2);   
  var ventana = window.open('imp_reporte_contable1.php?idc='+idc+'&fechaini='+fechaini+'&fechafin='+fechafin+'&formapago='+formapago+'&idv='+idv+'&idp='+idp, "Reporte Contable", "width="+wi+",height="+he+",menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=YES,left="+posicion_x+",top="+posicion_y+""); 
     
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
  CargarCombo($("#cboformapago"), "cargar_combo_db.php?tabla=tbl_modalidades_pagos&campo1=descripcion&selected=0&orderby=descripcion&firsttext=[Elija forma de pago]");
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
                    <h1 class="page-header">Reporte de Registro Contable</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Elementos Basicos
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
   
<table width="90%">     
    <tr>
      <td width="10%"><label>Cliente:</label></td>
      <td width="10%"> &nbsp;</td>
      <td width="70%"><div class="form-group input-group">
          <input readonly="" name="txtcliente" id="txtcliente" type="text" class="form-control">
          <span class="input-group-btn">
              <button class="btn btn-default" onclick="Buscar('buscar_clientes_rep1.php');" type="button"><i class="fa fa-search"></i>
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
              <button class="btn btn-default" onclick="Buscar('buscar_vendedor_rep1.php');" type="button"><i class="fa fa-search"></i>
              </button>
          </span>
      </div></td>
      <td><A onclick="limpiar('vendedor');" href="#" title="Limpiar"><IMG SRC="images/limpiar.png" WIDTH="33px" HEIGHT="33px"></A></td>      
    </tr>   
        
  <tr>
      <td><label>Producto:</label></td>
      <td> &nbsp;</td>
      <td><div class="form-group input-group">
          <input readonly="" name="producto" id="txtproducto" type="text" class="form-control">
          <span class="input-group-btn">
              <button class="btn btn-default" onclick="Buscar('buscar_productos_rep1.php');" type="button"><i class="fa fa-search"></i>
              </button>
          </span>
      </div></td>
      <td><A onclick="limpiar('producto');" href="#" title="Limpiar"><IMG SRC="images/limpiar.png" WIDTH="33px" HEIGHT="33px"></A></td>
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
      <td><label>Forma de Pago:</label></td>
      <td> &nbsp;</td>
      <td><select id="cboformapago" name="cboformapago"  class="form-control"></select></td>
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
    <td width="30%">&nbsp;</td>
    <td width="40%" align="center"><INPUT id="cmdGuardar" type="button" value="Consultar" class="btn btn-primary" onclick="ventanaAct2();"/></td>
    <td width="30%">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>

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