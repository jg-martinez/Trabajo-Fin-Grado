<?php 
/*-- borrar_contexto.php ---------------------------------------------------------------------------

     Pagina que elimina un contexto via ajax

----------------------------------------------------------------------------------------------- */

	session_start();header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);
	session_id($_REQUEST['session_id']); // Recuperamos la sesion actual, ya que mediante ajax la sesion seria nueva.
    
	// Sistema de permisos.
	include ("../comun/permisos.php");
   
	// Incluimos funciones
	include("func_glosario.php");
	
	// Listado de los contextos
	$id_termino =$_REQUEST['id_termino']; // identificador de termino
	$orden =$_REQUEST['orden']; // Orden
	$termino =$_REQUEST['termino']; // Termino
	$idioma =$_REQUEST['idioma']; // Idioma
		
	if (tienePermisos("administrarglosario")) {
		mostrar_terminos_administrar ($id_termino, $orden, $termino, $idioma);
	} else {
		echo "Error: No tiene permisos para esa operaci&oacute;n";
	}
?>