<?
/*-- query_texto.php ---------------------------------------------------------------------------

     Pagina que elimina un contexto via ajax
---------------------------------------------------------------------------------------------------
     Copyright (c) 2009 Roberto Martin-Corral Mayoral
     Verbatim copying and distribution of this entire document is permitted in 
     any medium, provided this notice is preserved. 
----------------------------------------------------------------------------------------------- */

	session_start();header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);
//	session_id($_REQUEST['session_id']); // Recuperamos la sesion actual, ya que mediante ajax la sesion seria nueva.
    
	// Sistema de permisos.
	include ("../comun/permisos.php");
   
	// Listado de los textos
	include ("../comun/conexion.php");

	$idcontexto=$_REQUEST['idcontexto']; // identificador de contexto
	
	if (tienePermisos("glosarioresultadoadmin")) {
		// Obtenenemos los datos para rellenar el historico.
		$consulta2 = "SELECT id_texto,id_termino, contexto from contexto WHERE id_contexto='$idcontexto'";
		$res = mysql_query($consulta2) or die("alert ('No se pudo eliminar el contexto');");
		$obj = mysql_fetch_object($res);
		
		// Hacemos la consulta
		$consulta = "DELETE FROM contexto WHERE id_contexto='$idcontexto'";
		mysql_query($consulta) or die("alert ('No se pudo eliminar el contexto');");  

		alta_historico ("eliminar", $_SESSION['username'], "contexto", "Identificador texto: ".$obj->id_texto."<br>T&eacute;rmino: ".$obj->id_termino."<br>Contexto: ".$obj->contexto);
		
		mysql_close($enlace);
		
		echo "document.getElementById('$idcontexto').style.display='none';";
	} else {
		echo "alert ('No tiene permisos para realizar esta acci\u00f3n');";
	}
?>