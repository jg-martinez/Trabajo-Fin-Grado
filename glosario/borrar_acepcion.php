<?php
/*-- borrar_acepcion.php ---------------------------------------------------------------------------

     Pagina que elimina una acepcion via ajax

----------------------------------------------------------------------------------------------- */

	session_start();
	header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);
//	session_id($_REQUEST['session_id']); // Recuperamos la sesion actual, ya que mediante ajax la sesion seria nueva.

	include ("../comun/permisos.php");
	include ("../historico/operaciones_historico.php");
	
	if (tienePermisos("administrarglosario"))
	{
		// Modificacion del termino
		include ("../comun/conexion.php");

		$id_glosario = $_REQUEST['id_glosario']; // glosario
		$orden = $_REQUEST['orden']; // glosario
		
		$consulta = "SELECT termino FROM glosario WHERE id_glosario=$id_glosario";
		$res = mysql_query($consulta) or die("var error = true; var errorMensaje ='Error: No se pudo crear la acepci\u00f3n';");
		$obj = mysql_fetch_object($res);
		$termino = $obj->termino;
		
		$consulta = "DELETE FROM acepcion WHERE id_glosario=$id_glosario AND orden=$orden";
		mysql_query($consulta) or die("var error = true; var errorMensaje ='Error: No se pudo modificar la acepci\u00f3n';");
		
		alta_historico ("modificar", $_SESSION['username'], "termino", "Eliminar acepci&oacute;n:<br>T&eacute;rmino: ".$termino."<br>Orden: ".$orden);
		
		mysql_close($enlace);
	}
?>