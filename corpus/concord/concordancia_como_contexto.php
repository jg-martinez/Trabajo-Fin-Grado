<?php 
	session_start();header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);
	include ("../../comun/permisos.php");
	
	if(isset($_GET['lg']))
	{
		$lg = $_GET['lg'];
		$_SESSION['lg'] = $lg;
		include ("../../idioma/".$lg.".php");
	}
	else if(isset($_SESSION['lg']))
	{
		$lg = $_SESSION['lg'];
		include ("../../idioma/".$lg.".php");
	}

/*-------------------------------------------------------------------------------------------------
Funcion que indica si una cadena empieza por un texto.

ENT: $hsystack   - Cadena de texto en la que se busca
     $needle     - Inicio del texto
SAL: Booleano indicando si es cierto o no.
-------------------------------------------------------------------------------------------------*/

function startswith($haystack, $needle) {
    if (is_array($haystack)) {
        foreach($haystack as $hay) {
            if(substr($hay, 0, strlen($needle)) == $needle) {
                return true;
            }
        }
        //return in_array($needle, $haystack);
        return false;
    } else {
        return (substr($haystack, 0, strlen($needle)) == $needle);
    }
}
?>
<!-- concordancia_como_contexto.php ---------------------------------------------------------------------------

     Pagina donde se incluye una concordancia encontrada como un contexto de un termino

----------------------------------------------------------------------------------------------- -->
<html>
<head>
   <link rel="stylesheet" type="text/css" href="<?php  echo $_SESSION['application_url']; ?>/comun/estilo.css">
</head>
<body>
	<p align="center">
		<span class="titulo titulo_rojo"><?php echo $admin_term_glosario ?></span><br>
		<img border="0" src="<?php  echo $_SESSION['application_url']; ?>/imagenes/linea_horiz.gif" >
	</p>
<?php 
	if (tienePermisos("administrarglosario"))
	{
		/* Consulta a la base de datos */
		include ("../../comun/conexion.php");
		
		$resultados = array();
		// Pasamos los elementos a un array
		while($elemento = each($_POST))
		{
			if (startswith($elemento[0],"resultado"))
				$resultados[] = $elemento[1];
		}
	   	   
		$i = 0;
		$salir = false; // Si se detecta que la concordancia no existe, se ejecuta solo una vez el bucle.
		while ( $i < count ($resultados) && !$salir)
		{
			// Separamos los elementos. La estrucutura queda:
			// 0 - id_texto
			// 1 - pre
			// 2 - concordancia
			// 3 - pro
			// 4 - aparicion
			$elementos = preg_split("/#/",$resultados[$i]);
			
			// Creamos el texto del contexto
			$contexto = trim($elementos[1])." ".trim($elementos[2])." ".trim($elementos[3]);
			
			/* Buscar ocurrencias y crear contextos */
			$consulta = "SELECT id_termino FROM glosario WHERE id_termino='".trim($elementos[2])."'";
			$res = mysql_query($consulta);
		
			if (mysql_num_rows($res) != 0)
			{
				$consulta2 = "INSERT INTO contexto (contexto, id_texto, id_termino,fecha_alta,usuario_alta) VALUES ('$contexto', '".$elementos[0]."', '".$elementos[2]."',now(),'".$_SESSION['username']."')";
				mysql_query($consulta2) or die ("$consulta2");
			}
			else
			{
				echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$el_term." <b>".$elementos[2]."</b> ".$mensaje66."<br>".$mensaje67."</p>";
				$salir = true;
			}
		   
		   $i++; // Siguiente iteracion.
		}
		
		// Si llegamos al final, es que se procesaron todos.
		if (count ($resultados) > 0)
		{
			if (!$salir)
			{
				echo "<p class=\"Resultado\"><img border=\"0\" src=\"../../imagenes/info.gif\"><br>".$mensaje68." <i>'".$elementos[2]."'</i>.</p>";
			}
		}
		else
		{
				echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$el_term." <b>".$elementos[2]."</b> ".$mensaje66."<br>".$mensaje67."</p>";
		}
	}
	else
	{
		echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$mensaje69."</p>";
	}
			echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      Aceptar \" onclick=\"history.go(-2)\" /></p>";
?>
	<table border="0" width="100%" style="border-top: 1 solid #FF0000">
		<tr>
			<td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
			<td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_acceso_corpus.php">CORPUS</A> > <u><?php echo $anadir_contexto ?></u></td>
		</tr>
	</table>
</body>
</html>