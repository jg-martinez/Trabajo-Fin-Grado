<?php 
/*-- query_texto.php ---------------------------------------------------------------------------

     Pagina que muestra una serie de textos en funcion de unos parametros.

----------------------------------------------------------------------------------------------- */

	session_start();header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);
   
	//include ("../comun/permisos.php");
   
	// Listado de los textos
	include ("../comun/conexion.php");

	$language=$_REQUEST['language'];	// idioma
	$nombre=$_REQUEST['name'];			// nombre por el que buscar textos
	$page=$_REQUEST['page'];			// pagina solicitada
	$pagesize=$_REQUEST['pagesize'];	// tamanyo de la pagina
	$field=$_REQUEST['field'];			// campo al que pertenece el texto
	$tipo=$_REQUEST['tipo'];			// tipo al que pertenece el texto
	$year_desde=$_REQUEST['year_desde'];	// anyo de publicacion del texto desde
	$year_hasta=$_REQUEST['year_hasta'];	// anyo de publicacion del texto hasta
	$autor=$_REQUEST['autor'];	// Autor de los textos
	$usuario=$_REQUEST['usuario'];	// Autor de los textos
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
	$_SESSION["language"] = $language;
	$_SESSION["name"] = $nombre;
	$_SESSION["page"] = $page;
	$_SESSION["pagesize"] = $pagesize;
	$_SESSION["field"] = $field;
	$_SESSION["tipo"] = $tipo;
	$_SESSION["year_desde"] = $year_desde;
	$_SESSION["year_hasta"] = $year_hasta;
	$_SESSION["autor"] = $autor;
	$_SESSION["usuario"] = $usuario;
	
	// Hacemos la consulta
	$consulta = "SELECT a.id_texto, a.h_title,a.edition_stmt, a.word_count, a.byte_count, a.lang_usage, a.id_tipo, a.id_campo, a.id_fuente, a.usuario_alta, a.usuario_modificacion, a.fecha_alta, a.fecha_modificacion FROM texto a";
	$consulta2 = "";
	
	// Filtro por lenguaje
	if ($language != "")
		$consulta2 = " where a.lang_usage='".$language."'";

	// Filtro por nombre de texto.
	if ($nombre != "") {
		if ($consulta2 != "")
			$consulta2 .= " and ";
		else
			$consulta2 .= " where ";
		
		$consulta2 .= "a.h_title like '".$nombre."'";
	}
	
	// Filtro por el ambito del texto.
	if ($field != "") {
		if ($consulta2 != "")
			$consulta2 .= " and ";
		else
			$consulta2 .= " where ";
		
		$consulta2 .= "a.id_campo like '".$field."'";
	}
	
	// Filtro por el tipo del texto.
	if ($tipo != "") {
		if ($consulta2 != "")
			$consulta2 .= " and ";
		else
			$consulta2 .= " where ";
		
		$consulta2 .= "a.id_tipo like '".$tipo."'";
	}
	
	// Filtro por usuario.
	if ($usuario != "") {
		if ($consulta2 != "")
			$consulta2 .= " and ";
		else
			$consulta2 .= " where ";
		
		$consulta2 .= "(a.usuario_alta like '".$usuario."' or a.usuario_modificacion like '".$usuario."')";
	}
	
	//Filtro por fechas
	if ($year_desde != "") {
		$consulta .= " INNER JOIN fuente b ON a.id_fuente=b.id_fuente";
		
		if ($year_hasta != "")
			$consulta .= " AND YEAR (b.pub_date) BETWEEN " . $year_desde . " AND " . $year_hasta;
		else
			$consulta .= " AND YEAR (b.pub_date) >= " . $year_desde;
	} else if ($year_hasta != "") {
		$consulta .= " INNER JOIN fuente b ON a.id_fuente=b.id_fuente AND YEAR (b.pub_date) <= " . $year_hasta;
	} 
	
	// Filtro por autor.
	if ($autor != "") {
		if ($year_desde == "" && $year_hasta == "")
			$consulta .= " INNER JOIN fuente b ON a.id_fuente=b.id_fuente";
		
		$consulta .= " AND b.h_author LIKE '" . $autor ."'";
	}
	
	
	if ($orden_campo != "")
		$consulta .= $consulta2 . " order by ".$orden_campo." ".$orden_sentido;
	else
		$consulta .= $consulta2 . " order by a.h_title asc";
	
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
			// id del texto
			// titulo
			// formato texto
			// numero de palabras
			// tamanyo en bytes
			// idioma
			// tipo de texto
			// campo del texto
			// fuente del texto
			// usuario de alta
			// fecha de alta
			// usuario de modificacion
			// fecha de modificacion
			$fecha_alta = "";
			$fecha_modificacion = "";
			if ($obj->fecha_alta != "0000-00-00")
				$fecha_alta = implode('/',array_reverse(explode('-',$obj->fecha_alta)));
			if ($obj->fecha_modificacion != "0000-00-00") 
				$fecha_modificacion = implode('/',array_reverse(explode('-',$obj->fecha_modificacion)));
			echo "textosarray[textosarray.length] = [\"".htmlentities($obj->id_texto)."\",\"".htmlentities($obj->h_title)."\",\"".
			htmlentities($obj->edition_stmt)."\",\"".htmlentities($obj->word_count)."\",\"".htmlentities($obj->byte_count)."\",\"".
			htmlentities($obj->lang_usage)."\",\"".htmlentities($obj->id_tipo)."\",\"".htmlentities($obj->id_campo)."\",\"".
			htmlentities($obj->id_fuente)."\",\"$obj->usuario_alta\",\"$fecha_alta\",\"$obj->usuario_modificacion\",\"$fecha_modificacion\"];";
			$num++;
		}
	}
	
	mysql_close($enlace);
?>