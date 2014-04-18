<?php 
/*-- borrar_contexto.php ---------------------------------------------------------------------------

     Pagina que elimina un contexto via ajax

----------------------------------------------------------------------------------------------- */

	session_start();header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);
	session_id($_REQUEST['session_id']); // Recuperamos la sesion actual, ya que mediante ajax la sesion seria nueva.
    
	// Sistema de permisos.
	include ("../comun/permisos.php");
	include ("../historico/operaciones_historico.php");
	
	// Listado de los textos
	include ("../comun/conexion.php");

	$idcontexto=$_REQUEST['idcontexto']; // identificador de contexto
	
	if (tienePermisos("administrarglosario")) {
		// Obtenemos los datos para el historico
		$consulta = "SELECT a.id_texto, a.orden, a.contexto, b.termino FROM contexto a inner join glosario b on a.id_glosario=b.id_glosario WHERE id_contexto=$idcontexto";
		$res = mysql_query($consulta) or die("var error = true; var errorMensaje ='Error: No se pudo crear la acepci\u00f3n';");
		$obj = mysql_fetch_object($res);
		
		// Hacemos la consulta
		$consulta = "DELETE FROM contexto WHERE id_contexto=$idcontexto";
		mysql_query($consulta) or die("var error = true; var errorMensaje ='Error: No se pudo eliminar el contexto");  
		
		alta_historico ("eliminar", $_SESSION['username'], "contexto", "Identificador texto: ".$obj->id_texto."<br>T&eacute;rmino: ".$obj->termino."<br>Orden: ".$obj->orden."<br>Contexto: ".$obj->contexto);
		
		mysql_close($enlace);
		echo "document.getElementById('".$idcontexto."_contexto').style.display='none';";
	} else {
		echo "var error = true; var errorMensaje ='Error: No se pudo eliminar el contexto';";
	}
	
	mysql_close($enlace);
?>