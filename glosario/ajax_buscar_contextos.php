<?php 
/*-- buscar_contexto.php ---------------------------------------------------------------------------

     Pagina que devuelve una lista de contextos
----------------------------------------------------------------------------------------------- */

	session_start();header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);
	session_id($_REQUEST['session_id']); // Recuperamos la sesion actual, ya que mediante ajax la sesion seria nueva.
	
	if(isset($_GET['lg']))
	{
		$lg = $_GET['lg'];
		$_SESSION['lg'] = $lg;
		include ("../idioma/".$lg.".php");
	}
	else if(isset($_SESSION['lg']))
	{
		$lg = $_SESSION['lg'];
		include ("../idioma/".$lg.".php");
	}
    
	// Sistema de permisos.
	include ("../comun/permisos.php");
	include ("func_glosario.php");
	
	$termino = $_REQUEST['termino']; // identificador de contexto
	$texto = $_REQUEST['texto']; // identificador de contexto
	if (isset($_REQUEST['limite'])) $limite = $_REQUEST['limite']; // identificador de contexto
	else $limite = "";
	$id = $_REQUEST['idioma'];
	
	if ($limite == "")
		$limite = 5; // Limite de 5
	
	if (tienePermisos("administrarglosario")) {
		// Listado de los textos
		include ("../comun/conexion.php");

		/* Buscar ocurrencias y crear contextos */
		$consulta2 = "SELECT id_texto,h_title,body FROM texto WHERE id_texto = ".$texto;
		$res2 = mysql_query($consulta2) or die("error = true; var errorMensaje ='Error: ".$no_leer_texto_contextos.";'");
	
		$obj = mysql_fetch_object($res2);  // Busqueda en el texto de la BD
		$vector = extraerElementos($obj->body);
		$contextos = buscar_contextos_como_lista ($termino, $vector, $id);
		
		echo "var termino='".$termino."';var base='".$texto."_".$termino."';var lineas = new Array();";
		for ($i = 0; $i < $limite && $i < count($contextos); $i++)
		{
			echo "lineas[lineas.length]= \"<li>".preg_replace("/(<br><br>)+/i","<br>",preg_replace("/[\n\r|\n|\r]/i","<br>",htmlentities (preg_replace('/\"/i','\\"',$contextos[$i]))))."</li>\";";
		}
		if (count($contextos) == 0)
			echo "error = true; var errorMensaje ='".$no_encontrar_contextos."';"; // Se trata como un error aunque no lo sea.
		mysql_close($enlace);
	} else {
		echo "error = true; var errorMensaje ='Error: ".$no_permisos_operacion."';";
	}
?>