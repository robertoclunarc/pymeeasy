<?PHP
include("../BD/conexion.php");
$link=crearConexion();
$rut= isset($_GET["rut"])?$_GET["rut"]:""; 
$query = "select * from tbl_proveedores where rif='" . $rut . "' and estatus_prov='ACTIVO'";
$result = $link->query($query);

if ($result->num_rows > 0)
{	

	$fila = $result->fetch_assoc();
	echo "$(\"#txtrazon_social\").val(\"" . $fila["razon_social"] . "\");\n" ;
	echo "$(\"#hddfkproveedor\").val(\"" . $fila["idproveedor"] . "\");\n" ;
	
}
else
	echo "0";

$result->free();
$link->close();
?>

