<?PHP
include("../BD/conexion.php");
$link=crearConexion();
$rut= isset($_GET["rut"])?$_GET["rut"]:""; 
$query = "select * from tbl_vendedores where rif='" . $rut . "'";
$result = $link->query($query);

if ($result->num_rows > 0)	

{	

	$fila = $result->fetch_assoc();

	echo "$(\"#txtrazon_social\").val(\"" . $fila["nombres"] . "\");\n" ;

	echo "$(\"#hddidvendedor\").val(\"" . $fila["idvendedor"] . "\");\n";

	echo "$(\"#txtfono\").val(\"" . $fila["fono"] . "\");\n" ;

	echo "$(\"#txtemail\").val(\"" . $fila["email"] . "\");\n" ;

	echo "$(\"#cboestatus_vend\").val(\"" . $fila["estatus_vend"] . "\");\n" ;
}

else

	echo "0";

$result->free();

$link->close();

?>

