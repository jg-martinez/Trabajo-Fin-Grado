<?php
	session_start();header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);
	include ("../comun/permisos.php");
	
	$id_glosario = $_POST['id_glosario']; // identificador de termino
	$orden = $_POST['orden']; // orden de la acepcion
	if (isset($_POST['termino'])) $termino = $_POST['termino']; // termino cuyos contextos se buscan
	else $termino = "";
	$arg_op = $_POST['arg_op']; // operacion
	if (isset($_POST['documento'])) $documento = $_POST['documento']; // lista de doucmentos
	else $documento = "";
	$idioma = $_POST['termino_idioma']; // idioma
	
?><!-- operacion_nuevos_contextos.php --------------------------------------------------------------------------

     Realiza la busqueda de contextos y la operacion de adicion.

----------------------------------------------------------------------------------------------- -->
<html>
<head>
   <title>Buscador de nuevos contextos</title>
   <link rel="stylesheet" type="text/css" href="../comun/estilo.css">
   <meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
   <meta content="MSHTML 6.00.2800.1498" name="GENERATOR" />
   <script>
   function show (id) {
       if (document.getElementById(id).style.display == "") {
     	  document.getElementById(id).style.display = "none";
       } else {
     	  document.getElementById(id).style.display = "";
       }
   }

   function checkContextos () {
	   
   }
   </script>
</head>
<body>
<?php
	if (tienePermisos("administrarglosario")) {
		if ($arg_op == "buscar_contextos") {
		    include("func_glosario.php");
?>
<form name="formulario" method="post" action="operacion_nuevos_contextos.php">
	<input type="hidden" name="arg_op" value="anadir_contextos" />
	<input type="hidden" name="orden_campo" value="a.h_title" />
	<input type="hidden" name="orden_sentido" value="asc" />
	<input type="hidden" name="id_glosario" value="<?php echo $id_glosario;?>" />
	<input type="hidden" name="orden" value="<?php echo $orden;?>" />
	<input type="hidden" name="termino_idioma" value="<?php echo $idioma;?>" />

	<p align="center">
		<table border="0" width="700" cellpadding="5" cellspacing="5"> <!-- bgcolor="#FFFF99"  -->
<?php
	$encontrados = buscar_contextos($termino, $documento, $idioma);
?>
		</table>
<?php
	if ($encontrados >0) {
?>
		<table border="0" width="700" cellpadding="5" cellspacing="5">
		   <tr>
		      <td align="center"><input type="button" class="boton" value=" Incorporar contextos " onclick="document.formulario.submit();"/></td>
		      <td align="left"><input type="button" class="boton" value=" Cerrar " onclick="window.close();"/></td>
		   </tr>
		</table>
<?php
	} else {
?>
		<table border="0" width="100%" cellpadding="5" cellspacing="5">
		   <tr>
			  <td colspn="2"><p class="Resultado"><img border="0" src="../imagenes/info.gif"><br>No se han encontrado contextos.</p></td>
		   </tr>
		   <tr>
		      <td colspan="2" align="center"><input type="button" class="boton" value=" Cerrar " onclick="window.close();"/></td>
		   </tr>
		</table>
<?php
	} 
?>
	</p>
</form>
<?php
		} else if ($arg_op == "anadir_contextos") {
			$i = 0;
			$j = 0;
			$contextos_arr = $_POST['contextos']; // operacion
			
			/* Consulta a la base de datos */
			include ("../comun/conexion.php");
			include ("../historico/operaciones_historico.php");
			
			while($obj = each($contextos_arr)) { // Busqueda en los textos de la BD
				$contexto_datos = preg_split("/#/",$obj[1]);
				
				$id_texto = $contexto_datos[0];
				$contexto = $contexto_datos[1];
				
				if (count($contexto_datos) > 2) {
					// Con esto evitamos el caso en que el contexto lleve el caracter de separacion #
					for ($i=2; $i < count($contexto_datos); $i++)
						$contexto.="#".$contexto_datos[$i];
				}
				
				// Cambiamos las ' por ''
				$contexto = preg_replace ("/\'/i","''", $contexto);
				
				$consulta = "SELECT termino FROM glosario WHERE id_glosario=$id_glosario";
				$res = mysql_query($consulta) or die("var error = true; var errorMensaje ='Error: No se pudo crear el contexto';");
				$obj = mysql_fetch_object($res);
				$termino = $obj->termino;
				
				$aux_j = $j;
				$consulta = "INSERT INTO contexto (contexto, id_texto, id_glosario, orden, fecha_alta, usuario_alta) VALUES ('".$contexto."',".$id_texto.",".$id_glosario.",".$orden.",now(),'".$_SESSION['username']."')";
				mysql_query($consulta) or $j++;
				
				if ($aux_j == $j)
					alta_historico ("alta", $_SESSION['username'], "contexto", "Identificador texto: ".$id_texto."<br>T&eacute;rmino: ".$termino."<br>Orden: ".$orden."<br>Contexto: ".$contexto);
				
				$i++;
			}
			
			mysql_close($enlace);		
			
			if ($j == 0)
				echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>Se han a&ntilde;adido ".($i)." contextos satisfactoriamente.";
			else {
				echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>Se han a&ntilde;adido ".($i-$j)." contextos satisfactoriamente.";
				if ($j > 0)
					echo "<br>No se han podido a&ntilde;adir ".$j." contextos.";
			}
			echo "</p>";
			echo "<p align=\"center\"><input type='button' class='boton' value=' Cerrar ' onclick='window.close();' /></p>";
			// Refrescamos en la pagina inicial.
			echo "<script>window.opener.refrescar_contextos('".$id_glosario."','".$orden."','".$termino."','".$idioma."')</script>";
		} else {
			echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>Operaci&oacute;n no permitida</p>";
			echo "<p align=\"center\"><input type='button' class='boton' value=' Cerrar ' onclick='window.close();' /></p>";
		}
	} else {
		echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>ACCESO INV&Aacute;LIDO a la p&aacute;gina.</p>";
		echo "<p align=\"center\"><input type='button' class='boton' value=' Cerrar ' onclick='window.close();' /></p>";
	}
?>
</body>
</html>