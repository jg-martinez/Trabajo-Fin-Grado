<?php
	session_start();
	header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);
//	session_id($_REQUEST['session_id']); // Recuperamos la sesion actual, ya que mediante ajax la sesion seria nueva.

	include ("../comun/permisos.php");
	include ("../historico/operaciones_historico.php");
	
	if (tienePermisos("administrarglosario"))
	{
		// Modificacion del termino
		include ("../comun/conexion.php");
		
		$cat_gramatical = preg_replace("/\'/i","''",$_REQUEST['cat_gramatical']); // categoria gramatical
		$definicion = preg_replace("/\'/i","''",$_REQUEST['definicion']); // definicion
		$traduccion = preg_replace("/\'/i","''",$_REQUEST['traduccion']); // traduccion
		$id_glosario = $_REQUEST['id_glosario']; // glosario
		$orden = $_REQUEST['orden']; // glosario
		
		$consulta = "SELECT termino FROM glosario WHERE id_glosario=$id_glosario";
		$res = mysql_query($consulta) or die("var error = true; var errorMensaje ='Error: No se pudo crear la acepci\u00f3n';");
		$obj = mysql_fetch_object($res);
		$termino = $obj->termino;
		
		$consulta = "UPDATE ACEPCION SET definicion='$definicion',traduccion='$traduccion',cat_gramatical='$cat_gramatical' WHERE id_glosario=$id_glosario AND orden=$orden";
		mysql_query($consulta) or die("var error = true; var errorMensaje ='Error: No se pudo modificar la acepci\u00f3n';");
		
		alta_historico ("modificar", $_SESSION['username'], "termino", "Modificar acepci&oacute;n:<br>T&eacute;rmino: ".$termino."<br>Orden: ".$orden."<br>Definici&oacute;n: ".$definicion."<br>Cat gramatical: ".$cat_gramatical.
		"<br>Traducci&oacute;n: ".$traduccion);
		
		mysql_close($enlace);
	}
?>