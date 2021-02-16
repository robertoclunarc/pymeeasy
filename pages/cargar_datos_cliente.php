<?PHP
include("../BD/conexion.php");
$link=crearConexion();
$rut= isset($_GET["rut"])?$_GET["rut"]:""; 
$query = "select * from tbl_clientes where rif='" . $rut . "'";
$result = $link->query($query);

if ($result->num_rows > 0)	

{	

	$fila = $result->fetch_assoc();			

	

	echo "$(\"#txtrazon_social\").val(\"" . $fila["razon_social"] . "\");\n" ;

	echo "$(\"#hddidcliente\").val(\"" . $fila["idcliente"] . "\");\n" ;

	echo "$(\"#txtrepresentante_legal\").val(\"" . $fila["representante_legal"] . "\");\n" ;

	echo "$(\"#txtfono\").val(\"" . $fila["fono"] . "\");\n" ;

	echo "$(\"#txtdireccion\").val(\"" . $fila["direccion"] . "\");\n" ;

	echo "$(\"#txtemail\").val(\"" . $fila["email"] . "\");\n" ;

	echo "$(\"#cboestatus_cliente\").val(\"" . $fila["estatus_cliente"] . "\");\n" ;
}

else

	echo "0";

$result->free();

$link->close();

?>

