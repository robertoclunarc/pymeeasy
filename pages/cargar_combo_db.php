<?PHP
$tabla = isset($_GET["tabla"])?$_GET["tabla"]:"";
$campo1 = isset($_GET["campo1"])?$_GET["campo1"]:"";        //Campo de la tabla que contiene el códigoo valor del elemento
$campo2 = isset($_GET["campo2"])?$_GET["campo2"]:"";		//campo que cintiene la descripcion del elemento
$selected = isset($_GET["selected"])?$_GET["selected"]:"";  //valor (campo1) seleccionado
$orderby= isset($_GET["orderby"])?$_GET["orderby"]:"";      //Campo(s) de ordenamiento, separados por ","
$where= isset($_GET["where"])?$_GET["where"]:"";
$condi= isset($_GET["condi"])?$_GET["condi"]:"";

$first= isset($_GET["first"])?$_GET["first"]:"";            //Primer Valor del 1er Elem opcional) NO DB. Ejm null 
$firsttext= isset($_GET["firsttext"])?$_GET["firsttext"]:"";    //Texto del 1er Elem (opcional) NO DB. Ejm <ninguno> <

include("../BD/conexion.php");

$link=crearConexion();

if ($campo2 != "") {
		$query = "select ". $campo1 . ", " . $campo2 . " from " . $tabla;

		if ($where!=""){
			$query=$query . " where " . $where . " = " . $condi ."";
		}

		if ($orderby!=""){
			$query=$query . " order by " . $orderby;
		}
		//Agrego el Primer elemento de Cabecera. Sino hay

		if ($first=="") $first="null";
		if ($firsttext!="")

		{
		echo "<option value=". $first . "" ;
		echo ">". $firsttext. "</option>"; 
		}

} else{
		$query = "select distinct ". $campo1 . " from " . $tabla;
		if ($where!=""){
			$query=$query . " where " . $where . " = " . $condi;
		}

		if ($orderby!=""){
			$query=$query . " order by " . $orderby;  
		}
		//Agrego el Primer elemento de Cabecera. Sino hay
		if ($first=="") 
			$first="null";
		if ($firsttext!="")
		{
		echo "<option value=". $first . "" ;
		echo ">". $firsttext. "</option>"; 
		}
}	

$result = $link->query($query);
$numReg = $result->num_rows;

if($numReg>0){

	while ($fila=$result->fetch_assoc()) 
	{
		echo "<option value=". urlencode(trim($fila[$campo1])) . "" ;
		if ($selected == $fila[$campo1])
		  echo " selected='selected'" ;
		if ($campo2 != "")
			echo ">". $fila[$campo2]. "</option>";
		else
			echo ">". $fila[$campo1]. "</option>";
	}
}
echo $query;
$result->free();
$link->close();
?>