<?php

// Fecha de creacion: 21/01/2011
// Operaciones a realizar con la tabla de historicos (alta, baja, busqueda)
// Operacion para dar de alta una entrada en el historico

function alta_historico ($accion, $usuario, $entidad, $datos) {
	$error = "";
	
	$datos_aux = str_replace("'","\\i",str_replace(array("\r\n", "\n", "\r"),'<br />',$datos));
	
	$consulta = "INSERT INTO HISTORICO (ACCION, USUARIO, ENTIDAD, DATOS, FECHA) VALUES ('".$accion."','".$usuario."','".$entidad."','".$datos_aux."',now())";
	
	
	mysql_query($consulta) or $error = $no_alta_historico.": ". mysql_error();
	
	// Devolvemos el error.
	return $error;
}

// Operacion para dar de baja una entrada en el historico
function baja_historico ($id) {
	
	$error = "";
	
	$consulta = "DELETE FROM HISTORICO WHERE ID=".$id;
	
	include ("../comun/conexion.php");
	
	mysql_query($consulta) or $error = $no_eliminar_historico.": ". mysql_error();
	
	close($enlace);
	
	// Devolvemos el error.
	return $error;
}

// Busqueda de historicos
function buscar_historicos ($accion, $usuario, $entidad, $fecha, $page, $orden_campo, $orden_sentido, $pagesize) {
	$num=0; // numero de registros leidos.
	$consulta2 = "";
	include ("../comun/conexion.php");
	
	// Si la pagina no se especifica, servimos la primera.
	if ($page == "")
		$page = 1;
	
	// El tamanyo por defecto de la pagina es de 15 registros.
	if ($pagesize == "")
		$pagesize = $maxpage;

	$_SESSION["historico_accion"] = $accion;
	$_SESSION["historico_usuario"] = $usuario;
	$_SESSION["historico_entidad"] = $entidad;
	$_SESSION["historico_fecha"] = $fecha;
	$_SESSION["historico_hora"] = "";
	$_SESSION["historico_page"] = $page;
	$_SESSION["historico_pagesize"] = $pagesize;
		
	$consulta = "SELECT id, accion, usuario, entidad, datos, fecha FROM historico ";
	
	// Filtro de accion.
	if ($accion != "")
		$consulta2 = "where accion = '".$accion."'";
	
	// Filtro de usuario.
	if ($usuario != "") {
		if ($consulta2 != "")
			$consulta2 .= " and ";
		else
			$consulta2 .= " where ";
		
		$consulta2 .= "usuario like '".$usuario."'";
	}
	
	// Filtro de entidad.
	if ($entidad != "") {
		if ($consulta2 != "")
			$consulta2 .= " and ";
		else
			$consulta2 .= " where ";
		
		$consulta2 .= "entidad = '".$entidad."'";
	}
	
	//Filtro por fechas
	if ($fecha != "") {
		
		if ($consulta2 != "")
			$consulta2 .= " and ";
		else
			$consulta2 .= " where ";
		
		// Conversion de la fecha a formato mysql
		$fecha_aux = "";
		$fecha_array = preg_split(" ",$fecha);
		$fecha_aux = implode('-',array_reverse(explode('/',$fecha_array[0])));
		if (strstr($fecha,":")) {
			$fecha_aux .= " ".$fecha_array[1];
			$consulta2 .= " fecha >= '".$fecha_aux."' and fecha < date_add('".$fecha_aux."',interval 1 minute)";
			$_SESSION["historico_fecha"] = $fecha_array[0];
			$_SESSION["historico_hora"] = $fecha_array[1];
		} else {
			$consulta2 .= " fecha >= '".$fecha_aux."' and fecha < date_add('".$fecha_aux."',interval 1 day)";
		}
		
	}
	
	if ($orden_campo == "")
		$orden_campo = "id";
	
	if ($orden_sentido == "")
		$orden_sentido = "asc";
	
	$consulta .= $consulta2." order by ".$orden_campo." ".$orden_sentido;
	
	$res = mysql_query($consulta) or die("alert (\"".$error_leer_historico.": ".$consulta."\");");
	
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
	
	echo "var historicoarray = new Array();"; // Array de textos en javascript.
	echo "var paginaactual = $page;";
	echo "var maxpaginas = $maxpages;";
	echo "var registrosencontrados = ".mysql_num_rows($res).";";
	
	if (mysql_num_rows($res) != 0) {
		mysql_data_seek($res, $pagina_inicio);
		
		while(($obj = mysql_fetch_object($res)) && $num < $pagesize)
		{
			// Se guardan los datos en un array:
			// id del historico
			// accion
			// entidad
			// usuario
			// fecha
			// datos
			$fecha = "";
			if ($obj->fecha != "0000-00-00 00:00") {
				$fecha_array = preg_split ("/ /",$obj->fecha);
				$fecha = implode('/',array_reverse(explode('-',$fecha_array[0])))." ".$fecha_array[1];
			}
			
			echo "historicoarray[historicoarray.length] = [\"".$obj->id."\",\"".htmlentities($obj->accion)."\",\"".
			htmlentities($obj->entidad)."\",\"".htmlentities($obj->usuario)."\",\"".$fecha."\",'".preg_replace('/\'/i','\\\'"',$obj->datos)."'];";
			$num++;
		}
	}
	
	mysql_close($enlace);
}
?>