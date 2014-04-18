<?php
	session_start();
	header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);
//	session_id($_REQUEST['session_id']); // Recuperamos la sesion actual, ya que mediante ajax la sesion seria nueva.

	include ("../comun/permisos.php");
	include ("../historico/operaciones_historico.php");
	
	if (tienePermisos("administrarglosario"))
	{
		// Listado de los textos
		include ("../comun/conexion.php");

		$cat_gramatical = preg_replace("/\'/i","''",$_REQUEST['cat_gramatical']); // categoria gramatical
		$definicion = preg_replace("/\'/i","''",$_REQUEST['definicion']); // definicion
		$traduccion = preg_replace("/\'/i","''",$_REQUEST['traduccion']); // traduccion
		$id_glosario = $_REQUEST['id_glosario']; // glosario
		//$img = $_REQUEST['archivo'];
		//$binario_nombre=$_REQUEST['archivo']['name'];
		//$binario_tamano=$_REQUEST['archivo']['size'];
		//$binario_tipo=$_REQUEST['archivo']['type'];
		//$binario_temporal= $_REQUEST['archivo']['tmp_name'];
		
		$consulta = "SELECT termino FROM glosario WHERE id_glosario=$id_glosario";
		$res = mysql_query($consulta) or die("var error = true; var errorMensaje ='Error: No se pudo crear la acepci\u00f3n';");
		$obj = mysql_fetch_object($res);
		$termino = $obj->termino;
		
		$consulta = "INSERT INTO ACEPCION (id_glosario,orden,definicion,cat_gramatical,traduccion) SELECT $id_glosario,MAX(orden)+1,'$definicion','$cat_gramatical','$traduccion' FROM ACEPCION WHERE ID_GLOSARIO=$id_glosario";
		mysql_query($consulta) or die("var error = true; var errorMensaje ='Error: No se pudo crear la acepci\u00f3n';");
		
		$consulta = "SELECT MAX(orden) ord FROM ACEPCION WHERE ID_GLOSARIO=$id_glosario";
		$res = mysql_query($consulta) or die("var error = true; var errorMensaje ='Error: No se pudo crear la acepci\u00f3n';");
		$obj = mysql_fetch_object($res);
		
		alta_historico ("modificar", $_SESSION['username'], "termino", "Alta acepci&oacute;n:<br>T&eacute;rmino: ".$termino."<br>Orden: ".$obj->ord."<br>Definici&oacute;n: ".$definicion."<br>Cat gramatical: ".$cat_gramatical.
		"<br>Traducci&oacute;n: ".$traduccion);
		
		echo "var orden = {'id_glosario':$id_glosario,'orden':".$obj->ord."}";
		mysql_close($enlace);
	}
?>