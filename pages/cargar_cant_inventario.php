<?PHP
include("../BD/conexion.php");
$link=crearConexion();
$idprod= isset($_GET["idprod"])?$_GET["idprod"]:"";
$query = "SELECT cantidad_unitaria, cantidad_blt, excepto_iva FROM tbl_productos WHERE idproducto = " . $idprod;	

$result = $link->query($query);
if ($result->num_rows > 0)
{
	$fila = $result->fetch_assoc();

	echo "$(\"#hddcantblt\").val(\"" . $fila["cantidad_blt"] . "\");\n" ;
	echo "$(\"#hddcantunit\").val(\"" . $fila["cantidad_unitaria"] . "\");\n" ;
	echo "$(\"#hddexceptoiva\").val(\"" . $fila["excepto_iva"] . "\");\n" ;
}
else
	echo "0";
$result->free();
$link->close();
?>

