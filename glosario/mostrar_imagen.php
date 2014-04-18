<?php
	/* Consulta a la base de datos */
    include ("../comun/conexion.php");
	$termino = $_GET['termino'];
	$orden = $_GET['orden'];
	$consulta = "SELECT imagen, formato FROM acepcion WHERE id_glosario = '$termino' AND orden = '$orden'";
	$res = mysql_query($consulta) or die (mysql_error());
	$obj = mysql_fetch_array($res);
	$img = $obj[0];
	$mime = $obj[1];
	header("Content-Type: ".$mime);
	echo $img;
?>