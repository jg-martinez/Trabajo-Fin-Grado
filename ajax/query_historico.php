<?php
/*-- query_historico.php ---------------------------------------------------------------------------

     Pagina que muestra una serie de accionss del historico en funcion de unos parametros.
---------------------------------------------------------------------------------------------------
     Copyright (c) 2009 Roberto Martin-Corral Mayoral
     Verbatim copying and distribution of this entire document is permitted in 
     any medium, provided this notice is preserved. 
----------------------------------------------------------------------------------------------- */

	session_start();header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);
   
	include ("../comun/permisos.php");
	include ("../historico/operaciones_historico.php");
	

	$usuario=$_REQUEST['usuario'];	// usuario
	$accion=$_REQUEST['accion'];	// accion
	$page=$_REQUEST['page'];		// pagina solicitada
	$pagesize=$_REQUEST['pagesize'];	// tamanyo de la pagina
	$fecha=$_REQUEST['fecha'];			// fecha de la accion
	$entidad=$_REQUEST['entidad'];		// entidad a la que hace referencia la accion
	$orden_campo=$_REQUEST['orden_campo'];	// Campo por el que se ordena
	$orden_sentido=$_REQUEST['orden_sentido'];	// Sentido de orden
	
	buscar_historicos ($accion, $usuario, $entidad, $fecha, $page, $orden_campo, $orden_sentido, $pagesize);

?>