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
   
	// Listado de los textos
	include ("../comun/conexion.php");

	$termino = $_REQUEST['termino']; // identificador de contexto
	if (isset($_REQUEST['limite'])) $limite = $_REQUEST['limite']; // identificador de contexto
	else $limite = "";
	
	if ($limite == "")
		$limite = 5; // Limite de 5
	
	if (tienePermisos("administrarglosario")) {
		// Hacemos la consulta
		$consulta = "SELECT definicion FROM acepcion WHERE id_glosario = (SELECT id_glosario FROM glosario where termino='".strtolower($termino)."' LIMIT 1) ORDER BY cat_gramatical LIMIT ".$limite;
		$res = mysql_query($consulta) or die("error = true; var errorMensaje ='Error: ".$no_buscar_acepciones."';");

		echo "var lineas = new Array();";
		if (mysql_num_rows($res) == 0)
			echo "error = true; var errorMensaje ='".$no_encontrar_acepciones."';"; // Se trata como un error aunque no lo sea.
		else
			while($obj = mysql_fetch_object($res)) {
				echo "lineas[lineas.length]= \"<li>".preg_replace("/(<br><br>)+/i","<br>",preg_replace("/[\n\r|\n|\r]/i","<br>",htmlentities (preg_replace('/\"/i','\\"',$obj->definicion))))."</li>\";";
			}
		mysql_close($enlace);
	} else {
		echo "error = true; var errorMensaje ='".$no_permisos_operacion."';";
	}
?>