<?php
session_start();
include("BD/conexion.php");
require('pages/auditoria.php');
$link=crearConexion();
$aud=auditar("Cierre de sesion", $_SESSION['user_session'],$link);
session_destroy();
$link->close();
header('Location: index.php');
exit(0);
?>
