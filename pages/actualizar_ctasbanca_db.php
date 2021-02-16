<?php
include("../BD/conexion.php");
require('auditoria.php');
session_start();     

$nro_cuenta_new= isset($_POST["txtnro_cuenta_new"])?$_POST["txtnro_cuenta_new"]:"0";
$tipo_new= isset($_POST["cbotipo_new"])?$_POST["cbotipo_new"]:"0";
$banco_new= isset($_POST["txtbanco_new"])?$_POST["txtbanco_new"]:"0";

$link=crearConexion();
if (($nro_cuenta_new!="") && ($tipo_new!="") && ($banco_new!="")){
$insertarCta="INSERT INTO tbl_cuentas_bancarias(nro_cuenta, tipo, banco) VALUES ('".$nro_cuenta_new."', '".$tipo_new."', '".$banco_new."')";

    if ($link->query($insertarCta) === TRUE){

      $idcta=$link->insert_id;

      $aud=auditar("Registro de Nueva Cta. Bancaria: ".$nro_cuenta_new, $_SESSION['user_session'],$link);
    }
    else {
      echo $link->error." \n ".$insertarCta;
      $link->close();
      die("");
    }
}

$i=0;

        if(array_key_exists('hddidcuenta_bnc',$_POST))

        {

          $idcuentas = $_POST['hddidcuenta_bnc'];          
          $nro_cuentas = $_POST['txtnro_cuenta'];
          $tipos = $_POST['cbotipo'];                   
          $bancos = $_POST['txtbanco'];
          foreach ($idcuentas as &$idcuenta)

          {

            $nro_cuenta = $nro_cuentas[$i];            
            $tipo = $tipos[$i];           
            $banco = $bancos[$i];

            if (($nro_cuenta!="") && ($tipo!="") && ($banco!="")){

            $query = "UPDATE tbl_cuentas_bancarias SET ";
            $query .= "nro_cuenta = '".$nro_cuenta."', ";
            $query .= "tipo = '".$tipo."', ";
            $query .= "banco = '".$banco."' ";
            $query .= "WHERE idcuenta_bnc = ".$idcuenta."; ";

            if ($link->query($query) === TRUE)
               $aud=auditar("Actualizacion de Cta. Bancaria: ".$nro_cuenta, $_SESSION['user_session'],$link);        
            }

            $i++;

          }

        }

    

       //el registro no existe
      echo("1");
      //echo $link->error." \n ".$updateCli;
      $link->close();
?>