<?php
function auditar($operacion, $user,$lag){
    $idaud=0; 
    $insertarAud="INSERT INTO tbl_auditorias (actividad,login,fecha) VALUES ('".$operacion."', '".$user."', NOW())";
    if ($lag->query($insertarAud) === TRUE){
       $idaud=$lag->insert_id;
    }
    return $idaud;
}

function Buscar_costo_producto($idprod,$lag){     
    $cantidades="SELECT costo FROM tbl_productos WHERE idproducto = ".$idprod;
    $result = $lag->query($cantidades);
    $row=$result->fetch_assoc();
    return $row['costo'];
}

function formatear_nro($nro){
	$numero=str_replace(".","",$nro);
	$numero=str_replace(",",".",$numero);
	return $numero;
}
?>