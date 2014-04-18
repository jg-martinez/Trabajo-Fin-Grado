<?php 
/*-- query_fuente.php ---------------------------------------------------------------------------

     Pagina que muestra una serie de textos en funcion de unos parametros.
---------------------------------------------------------------------------------------------------
     Copyright (c) 2009 Roberto Martin-Corral Mayoral
     Verbatim copying and distribution of this entire document is permitted in 
     any medium, provided this notice is preserved. 
----------------------------------------------------------------------------------------------- */

	session_start();header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);
   
	//include ("../comun/permisos.php");
   
	// Listado de los textos
	include ("../comun/conexion.php");

	$id_fuente=$_REQUEST['id_fuente'];	// isbn
	$edition=$_REQUEST['edition'];	// edicion
	$h_title=$_REQUEST['h_title'];	// titulo
	$h_author=$_REQUEST['h_author'];// autor
	$pub_place=$_REQUEST['pub_place'];	// lugar de publicacion
	$publisher=$_REQUEST['publisher'];	// editorial
	$page=$_REQUEST['page'];			// pagina solicitada
	$pagesize=$_REQUEST['pagesize'];	// tamanyo de la pagina
	$orden_campo=$_REQUEST['orden_campo'];	// Campo por el que se ordena
	$orden_sentido=$_REQUEST['orden_sentido'];	// Sentido de orden
	$num=0;								// numero de registros leidos.
	
	// Si la pagina no se especifica, servimos la primera.
	if ($page == "")
		$page = 1;
	
	// El tamanyo por defecto de la pagina es de 15 registros.
	if ($pagesize == "")
		$pagesize = $maxpage;
	
	// Colocamos el filtro en sesion para posteriores usos.
	$_SESSION["id_fuente"] = $id_fuente;
	$_SESSION["edition"] = $edition;
	$_SESSION["h_title"] = $h_title;
	$_SESSION["page"] = $page;
	$_SESSION["pagesize"] = $pagesize;
	$_SESSION["h_author"] = $h_author;
	$_SESSION["pub_place"] = $pub_place;
	$_SESSION["publisher"] = $publisher;
	$_SESSION["orden_campo"] = $orden_campo;
	$_SESSION["orden_sentido"] = $orden_sentido;
	
	// Hacemos la consulta
	$consulta = "SELECT id_fuente, h_title, edition, h_author, pub_place, publisher, pub_date FROM fuente";
	$consulta2 = "";
	
	// Filtro por lenguaje
	if ($h_title != "")
		$consulta2 = " where h_title='".$h_title."'";

	// Filtro por nombre de texto.
	if ($edition != "") {
		if ($consulta2 != "")
			$consulta2 .= " and ";
		else
			$consulta2 .= " where ";
		
		$consulta2 .= "edition like '".$edition."'";
	}
	
	// Filtro por isbn.
	if ($id_fuente != "") {
		if ($consulta2 != "")
			$consulta2 .= " and ";
		else
			$consulta2 .= " where ";
		
		$consulta2 .= "id_fuente like '".$id_fuente."'";
	}
	
	// Filtro por el ambito del texto.
	if ($h_author != "") {
		if ($consulta2 != "")
			$consulta2 .= " and ";
		else
			$consulta2 .= " where ";
		
		$consulta2 .= "h_author like '".$h_author."'";
	}
	
	// Filtro por el tipo del texto.
	if ($pub_place != "") {
		if ($consulta2 != "")
			$consulta2 .= " and ";
		else
			$consulta2 .= " where ";
		
		$consulta2 .= "pub_place like '".$pub_place."'";
	}
	
	// Filtro por usuario.
	if ($publisher != "") {
		if ($consulta2 != "")
			$consulta2 .= " and ";
		else
			$consulta2 .= " where ";
		
		$consulta2 .= "publisher like '".$publisher."'";
	}
	
	if ($orden_campo != "")
		$consulta .= $consulta2 . " order by ".$orden_campo." ".$orden_sentido;
	else
		$consulta .= $consulta2 . " order by id_fuente asc";
	
	$res = mysql_query($consulta) or die("Lectura de textos incorrecta.");
	
	// Si se especifica un pagesize de -1, seleccionamos todos.
	if ($pagesize == -1) {
		$page = 1;
		$pagesize = mysql_num_rows($res);
	}

	if ($pagesize == 0)
		$pagesize = 1;

	// Calculamos la pagina de inicio
	$pagina_inicio = ($page-1) * $pagesize;
	
	// Calculamos el numero maximo de paginas
	$maxpages = floor(mysql_num_rows($res)/$pagesize);
	
	if (mysql_num_rows($res) % $pagesize > 0)
		$maxpages++;

	// Si se solicita una pagina que no existe, escogemos la pagina tope.
	if ($page > $maxpages)
		$page=$maxpages;
	
	$pagina_inicio = ($page-1) * $pagesize;
	
	echo "var textosarray = new Array();"; // Array de textos en javascript.
	echo "var paginaactual = $page;";
	echo "var maxpaginas = $maxpages;";
	echo "var registrosencontrados = ".mysql_num_rows($res).";";
	
	if (mysql_num_rows($res) != 0) {
		mysql_data_seek($res, $pagina_inicio);
		
		while(($obj = mysql_fetch_object($res)) && $num < $pagesize)
		{
			// Se guardan los datos en un array:
			// id de la fuente
			// edicion
			// autor
			// lugar de publicacion
			// editorial
			$fecha = "";
			if ($obj->pub_date != "0000-00-00")
				$fecha = implode('/',array_reverse(explode('-',$obj->pub_date)));
			echo "textosarray[textosarray.length] = [\"".htmlentities($obj->id_fuente)."\",\"".htmlentities($obj->h_title)."\",\"".
			htmlentities($obj->edition)."\",\"".htmlentities($obj->h_author)."\",\"".htmlentities($obj->pub_place)."\",\"".
			htmlentities($obj->publisher)."\",\"$fecha\"];";
			$num++;
		}
	}
	
	mysql_close($enlace);
?>