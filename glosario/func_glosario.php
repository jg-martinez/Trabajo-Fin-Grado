<?php 

// Vista de un termino para su alta y mnodificacion
function administrar_termino ($termino, $es_alta, $desde_lista, $id) {
	
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
	
	/* Consulta a la base de datos */
	include ("../comun/conexion.php");
	
	$id_termino = $termino; // Para el caso de modificacion. Para el alta, se sutituye por el nuevo
	$numeromagico = 2;
	//$inicial = $_GET["inicial"];
	
	$res = "";
	$obj = "";
	
	if ($es_alta)
		echo "<p align=\"center\"><span class=\"titulo titulo_rojo\">Alta de Nuevo T&eacute;rmino del Glosario</span><br>";
	else
	{
		echo "<p align=\"center\"><span class=\"titulo titulo_rojo\">Modificaci&oacute;n de T&eacute;rminos del Glosario</span><br>";
		$consulta = "SELECT termino FROM glosario where id_glosario='$termino'";
		$res = mysql_query($consulta) or die (mysql_error());
		$obj = mysql_fetch_object($res);
		$termino = $obj->termino;
	}
	echo "<img border=\"0\" src=\"../imagenes/linea_horiz.gif\" ></p>";

	$termino = convertirMinusculas($termino);

	$error = false;
	$error_text = "";
	$res = null; // Asignamos a $res un valor para poder ser usado en toda la pagina
	$obj = null; // Asignamos a $obj un valor para poder ser usado en toda la pagina
	
	$termino_plural = crearPlural($termino, $id);

	
	if ($es_alta) { // se esta dando de alta un nuevo termino en el glosario
		$consulta = "SELECT id_glosario FROM glosario WHERE termino = '$termino'";
		$res = mysql_query($consulta) or $error=true;
		
		// Si el termino no existia con anterioridad, se da de alta y se recupera el codigo del termino creado.
		if (mysql_num_rows($res) == 0) {
			$consulta = "INSERT INTO glosario(termino,termino_plural,inicial,idioma,ocurrencias,usuario_alta,fecha_alta) VALUES ";
			$consulta .="('".$termino."','".$termino_plural."','".eliminar_tilde(substr($termino, 0, 1))."','".$id."','0','".$_SESSION['username']."',now())";
			mysql_query($consulta) or die("No se pudo dar de alta el t&eacute;rmino");
			
		   $datos = "Identificador:".$termino."<br>Plural: ".crearPlural($termino, $id)."<br>Inicial: ".eliminar_tilde(substr($termino, 0, 1))."<br>Idioma: ".$id;
		   alta_historico ("alta", $_SESSION['username'], "termino", $datos);
			
			// Recuperamos los datos del nuevo termino
			$consulta = "SELECT id_glosario,termino,idioma,fecha_alta,usuario_alta,fecha_modificacion,usuario_modificacion FROM glosario WHERE termino = '$termino'";
			$res = mysql_query($consulta);
			$obj = mysql_fetch_object($res);
			
			$error = (mysql_num_rows($res) == 0);
			if (!$error)
				$id_termino = $obj->id_glosario;
			else
				$error_text = "No se pudo recuperar el t&eacute;rmino del glsoario"; 
		} else {
			$error = true;
			$error_text = "El t&eacute;rmino $termino ya existe en el glosario";
		}
	} else {
		$error = ($termino == ""); // Se supone que es una modificacion. Si viene vacio es que hay error.
		$error_text = "El t&eacute;rmino no se encuentra en el glosario";
	}
	
	if ($error) {
		echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>$error_text</p>";
		echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      Aceptar \" onclick=\"";
		if ($desde_lista == "")
			echo "document.location='resultado.php?inicial=admin_termino';";
		else
			echo "window.close();";
		echo "\" /></p>";
	} else {
		/* Conexion con la base de datos */
		include ("../comun/conexion.php");

		/* Obtenemos los datos de la BD */
		if (!$es_alta) {
			$consulta = "SELECT termino,idioma,fecha_alta,usuario_alta,fecha_modificacion,usuario_modificacion,term_compuesto FROM glosario WHERE id_glosario = $id_termino";
			$res = mysql_query($consulta) or die("No se pudo acceder al glosario");
			$obj = mysql_fetch_object($res); 
		}

		echo "<form enctype=\"multipart/form-data\" action=\"operacion_glosario2.php\" target=\"resultado\" method=\"post\" name=\"formulario\">";
		echo "<script>var termino = '".$obj->termino."'; var termino_idioma='$obj->idioma';</script>";
		echo "<p align=\"center\">";
		echo "<table width='80%' border='0'>";
		echo "<tr><td width='20%'><b>T&eacute;rmino</b></td><td width='10%' align='left'>".$obj->termino."</td>";
		echo "<td width='20%'><b>Idioma</b></td><td width='10%' align='left'>".$obj->idioma."</td></tr>";
		$termino_mostrar = $obj->termino;
		$id = $obj->idioma;
		if (!$es_alta)
		{
			// se comprueba si pertenece a algun termino compuesto
			echo "<td width='20%'><b>".$termino_compuesto."</b>";
			
			if ($obj->term_compuesto != 1) // El termino no es compuesto y se busca si pertenece a alguno
			{
				$consulta = "SELECT termino,id_glosario FROM glosario WHERE term_compuesto = 1";
				$res = mysql_query($consulta);
				while($obj2 = mysql_fetch_object($res))
				{
					if (strpos($obj2->termino, $obj->termino) === false) // No se muestra nada
					{
					}
					else
					{
						echo "<td width='10%' align='left'><a href='operacion_glosario.php?arg_op=mostrar&termino=$obj2->id_glosario' target='_blank'>".$obj2->termino."</a></td></tr>";
					}
				}
			}
		}
		if (tienePermisos("buscadorespecial")) {
			$numeromagico = 4;
			$fecha_alta = "";
			$fecha_modificacion = "";
			if ($obj->fecha_alta != "0000-00-00")
				$fecha_alta = implode('/',array_reverse(explode('-',$obj->fecha_alta)));
			if ($obj->fecha_modificacion != "0000-00-00")
				$fecha_modificacion  = implode('/',array_reverse(explode('-',$obj->fecha_modificacion)));
			echo "<tr><td width='20%'><b>Usuario alta</b></td><td>".$obj->usuario_alta."</td><td width='10%'><b>Fecha alta</b></td><td>".$fecha_alta."</td></tr>";
			echo "<tr><td width='20%'><b>Usuario modificaci&oacute;n</b></td><td>".$obj->usuario_modificacion."</td><td width='10%'><b>Fecha modificaci&oacute;n</b></td><td>".$fecha_modificacion."</td></tr>";
		}
		
		echo "<tr><td colspan='4'>&nbsp;</td></tr>";
		echo "<tr><td colspan='4'><input type='button' class='boton' id='botonacepcion' value=' Introducir acepci&oacute;n ' onclick='preparar_alta();'/>&nbsp;&nbsp;<input type='button' class='boton' ";
		echo "value=' Mostrar en RAE ' onclick='window.open(\"http://buscon.rae.es/draeI/SrvltConsulta?TIPO_BUS=3&LEMA=".$obj->termino."\",\"_blank\")'/>&nbsp;&nbsp;<input type='button' class='boton' ";
		echo "value=' Mostrar en Webopedia ' onclick='window.open(\"http://www.webopedia.com/search/".$obj->termino."\",\"_blank\")'/>&nbsp;&nbsp;<input type='button' class='boton' ";
		if ($obj->idioma == "esp")
			echo "value=' Mostrar en Wikipedia ' onclick='window.open(\"http://es.wikipedia.org/wiki/".$obj->termino."\",\"_blank\")'/>";
		else
			echo "value=' Mostrar en Wikipedia ' onclick='window.open(\"http://en.wikipedia.org/wiki/".$obj->termino."\",\"_blank\")'/>";
				
		// Comprobamos si el termino esta en eurowordnet y mostramos su informacion
		if ($id == "esp")
		{
			$consulta = "SELECT * FROM eswn_variant WHERE word='$termino_mostrar'";
			$res = mysql_query($consulta);
		}
		else
		{
			$consulta = "SELECT offset FROM synsetword WHERE word='$termino_mostrar'";
			$res = mysql_query($consulta) or die (mysql_error());
		}
		if (mysql_num_rows($res) != 0) // el termino esta contenido en eurowordnet por lo que se muestra el enlace al mismo
		{
			echo "&nbsp;&nbsp;<input type='button' class='boton' ";
			echo "value=' Mostrar en EuroWordNet ' onclick='window.open(\"operacion_glosario.php?arg_op=mostrar_eurowordnet&termino=$termino_mostrar&idioma=$id\")'/>";
		}
				
		if ($desde_lista == "si")
			echo "&nbsp;&nbsp;<input type='button' class='boton' value=' Cerrar ventana ' onclick='window.close();'/>";
		echo "</td></tr>";
		if($es_alta) 
		{
			echo "<input type=\"hidden\" name=\"arg_op\" value=\"alta\"/>";
			// Se comprueba si el termino que se quiere anadir es compuesto o simple
			// Se mira si el termino tiene un caracter en blanco o no.
			$longitud = strlen($obj->termino);
			$pos_term = strpos($obj->termino, ' ');
			$pal_term_comp = explode(" ", $obj->termino);
			if ($pos_term != $longitud - 1 && $pos_term != false) // Se trata de un termino compuesto
			{
				echo "<input type=\"hidden\" name=\"compuesto\" value=\"1\">";
				echo "<input type=\"hidden\" name=\"pal_term_comp1\" value='".$pal_term_comp[0]."'>";
				echo "<input type=\"hidden\" name=\"pal_term_comp2\" value='".$pal_term_comp[1]."'>";
				echo "<tr><td><b>T&eacute;rmino Compuesto</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				echo "<td><select align=\"left\" name=\"term_comp1\" title=' ".$termino_compuesto." '>";
				// Listamos todos los terminos en espanol y en ingles
				$id = array("esp","ing");
				for ($i=0; $i < count($id); $i++) 
				{
					if ($id[$i] == "esp")
					{
						echo "<optgroup label=\"$term_esp\">";
					}
					else 
					{
						echo "<optgroup label=\"$term_ing\">";
					}
					$consulta = "SELECT termino, term_compuesto FROM glosario WHERE idioma = '$id[$i]'";
					$res = mysql_query($consulta);
					while($obj = mysql_fetch_object($res))
					{
						if ($obj->term_compuesto != true)
						{
							echo "<option value=".$obj->termino.">".$obj->termino."</option>";
						}
					}
					echo "</optgroup>";
				}
				echo "<optgroup label=' ".$otros." '></optgroup>";
				echo "<option value=\"Otro\">".$otro."</option>";
				echo "</select></td>";
				echo "<td><select align=\"left\" name=\"term_comp2\" title=' ".$termino_compuesto." '>";
				/* Consulta a la base de datos */
				include ("../comun/conexion.php");
				
				$id = array("esp","ing");
				for ($i=0; $i < count($id); $i++) 
				{
					if ($id[$i] == "esp")
					{
						echo "<optgroup label=\"$term_esp\">";
						echo "</optgroup>";
					}
					else 
					{
						echo "<optgroup label=\"$term_ing\">";
						echo "</optgroup>";
					}
					$consulta = "SELECT termino, term_compuesto FROM glosario WHERE idioma = '$id[$i]'";
					$res = mysql_query($consulta);
					while($obj = mysql_fetch_object($res))
					{
						if ($obj->term_compuesto != true)
						{
							echo "<option value=".$obj->termino.">".$obj->termino."</option>";
						}
					}
				}
				echo "<optgroup label=' ".$otros." '></optgroup>";
				echo "<option value=\"Otro\">".$otro."</option>";
				echo "</select></td></tr>";
			}
		}
		else // se esta realizando la modificacion de un termino
		{
			echo "<input type=\"hidden\" name=\"arg_op\" value=\"modificar\"/>";
		}
		echo "<tr><td colspan='4'>&nbsp;</td></tr>";
		//echo "</table>";
		echo "<tr><td id='filaacepcion' class='acepcion_out' colspan='4'>";
		echo "<table width='80%' class='acepcion_in'>";
		echo "<tr><td width='5%'>&nbsp;</td><td width='15%'>Categor&iacute;a gramatical</td><td>";
		echo "<select id='cat_gramatical' title='Categor&iacute;a gramatical' class='obligatorio'><option value='sustantivo'>Sustantivo</option>";
		echo "<option value='verbo'>Verbo</option><option value='determinante'>Determinante</option><option value='adjetivo'>Adjetivo</option>";
		echo "<option value='pronombre'>Pronombre</option><option value='preposicion'>Preposici&oacute;n</option><option value='conjuncion'>Conjunci&oacute;n</option><option value='adverbio'>Adverbio</option>";
		echo "<option value='interjeccion'>Interjecci&oacute;n</option></select></td><td width='15%'>Traducci&oacute;n</td><td><input type='text' id='traduccion' value='' size='30' maxlength='30' /></td>";
		echo "<td rowspan='6'><input type='button' class='boton boton_aceptar' id='botonacepcion' value='      Aceptar ' onclick='incluirEntrada($id_termino);'/><br><br><input type='button' class='boton boton_cancelar' ";
		echo "id='botonacepcion' value='      Cancelar ' onclick='cancelarEntrada();'/></td></tr>";
		echo "<input type=\"hidden\" name=\"lim_tamano\" value=\"65000\"/>";
		echo "<input type=\"hidden\" name=\"termino\" value=\" ".$termino." \">";
		echo "<input type=\"hidden\" name=\"id_termino\" value=\" ".$id_termino." \">";
		$consulta4 = "SELECT idioma FROM glosario WHERE id_glosario='$id_termino'";
		$res4 = mysql_query($consulta4) or die (mysql_error());
		$obj4 = mysql_fetch_object($res4);
		
		echo "<input type=\"hidden\" name=\"idioma\" value='".$obj4->idioma."'>";
		
		//echo "<input type='hidden' name='orden_acepcion' value="
		//echo "<tr><td colspan='4'>Imagen&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#000000\"><b>Introduzca una imagen</b></font><input type='file' id='archivo' name='archivo'></td></tr>";
			
			
		echo "<tr><td width='5%'>&nbsp;</td><td width='15%'>Definici&oacute;n</td><td colspan='3'><textarea class='obligatorio' id='definicion' cols='80' rows='5'></textarea></td></tr>";
		echo "</table></td></tr><tr><td colspan='4' id='mensajes'></td></tr>";
		
		echo "<tr><td colspan='4'>&nbsp;</td></tr>";
		echo "<tr><td colspan='4' align='center'><span class=\"titulo titulo_rojo\">Acepciones</span><br><img border=\"0\" src=\"../imagenes/linea_horiz.gif\" ></td></tr>";
		echo "<tr><td colspan='4'><div id='acepciones' align='center'>";
		
		// Mostrar acepciones
		$consulta = "SELECT orden,definicion,cat_gramatical,traduccion,imagen,tamano,formato,nombre FROM acepcion WHERE id_glosario = '$id_termino' order by cat_gramatical,orden";
		$res2 = mysql_query($consulta) or die ("No se pudieron leer las acepciones");
		
		if (mysql_num_rows($res2) != 0) { // tiene acepciones
			$i = 1;
			while($obj2 = mysql_fetch_object($res2)) {
				echo "<span class='Info2' id='".$id_termino."_".$obj2->orden."_acepcion'>";
				echo "<table width='95%' border='0' onclick='trigger(\"".$id_termino."_".$obj2->orden."\",\"contextos_out\",\"contextos_in\")'>\n";
				echo "<tr><td colspan='4' align=\"center\"><b><u>ACEPCI&Oacute;N ".$i."</u></b></td></tr>";
				echo "<tr><td colspan='4'><b><u>Informaci&oacute;n General</u></b></td></tr>";
				echo "<tr></tr>";
				echo "<tr>\n<td width='15%'><b>Categor&iacute;a gramatical</b></td>\n<td width='15%' id='".$id_termino."_".$obj2->orden."_categoria'>";
				switch($obj2->cat_gramatical) {
					case('sustantivo'): echo 'Sustantivo'; break;
					case('verbo'): echo 'Verbo'; break;
					case('determinante'): echo 'Determinante'; break;
					case('adjetivo'): echo 'Adjetivo'; break;
					case('pronombre'): echo 'Pronombre'; break;
					case('preposicion'): echo 'Preposici&oacute;n'; break;
					case('conjuncion'): echo 'Conjunci&oacute;n'; break;
					case('adverbio'): echo 'Adverbio'; break;
					case('interjeccion'): echo 'Interjecci&oacute;n'; break;
				}
				echo "</td>\n<td width='15%'><b>Traducci&oacute;n</b></td>";
				if (!$es_alta)
				{
					include ("../comun/conexion.php");
					$consulta = "SELECT id_glosario FROM glosario WHERE termino='$obj2->traduccion'";
					$res = mysql_query($consulta) or die (mysql_error());
					if (mysql_num_rows($res) != 0)
					{
						$obj3 = mysql_fetch_object($res);
						echo "<td id='".$id_termino."_".$obj2->orden."_traduccion'><a href='operacion_glosario.php?arg_op=mostrar&termino=$obj3->id_glosario' target='_blank'>".$obj2->traduccion."</a></td>";
					}
					else
					{
						echo "<td id='".$id_termino."_".$obj2->orden."_traduccion'>".$obj2->traduccion."</td>";
					}
				}
				else
				{
					echo "<td id='".$id_termino."_".$obj2->orden."_traduccion'>".$obj2->traduccion."</td>";
				}

				echo "<td rowspan='2' align='right' width='5%'>";
				echo "<a href='#' onclick='event.cancelBubble=true;preparar_modificacion(\"".$id_termino."_".$obj2->orden."\")'><img border='0' src='../imagenes/modificar_ico.gif' title='Modificar acepci&oacute;n'><br><br>";
				echo "<a href='#' onclick='event.cancelBubble=true;preparar_eliminacion(\"".$id_termino."_".$obj2->orden."\")'><img border='0' src='../imagenes/papelera_ico.png' title='Eliminar acepci&oacute;n'></a><br><br>";
				echo "<a href='#' onclick='event.cancelBubble=true;buscar_contextos(\"".$id_termino."_".$obj2->orden."\",\"".$obj->termino."\",\"".$id."\")'><img border='0' src='../imagenes/menu_lupa.gif' title='Buscar contextos'><br><br>";
				echo "<a href='#' onclick='event.cancelBubble=true;refrescar_contextos(\"".$id_termino."\",\"".$obj2->orden."\",\"".$obj->termino."\",\"".$obj->idioma."\")'><img border='0' src='../imagenes/refresh.gif' title='Refrescar contextos'><br><br>";
				echo "</td></tr>\n<tr>\n<td width='15%'><b>Definici&oacute;n</b></td>\n<td colspan='3' id='".$id_termino."_".$obj2->orden."_definicion'>".$obj2->definicion."</td>\n</tr>\n";
				
				if (!$es_alta)
				{
					if ($obj2->tamano!='') // hay imagen
					{
						echo "&nbsp;&nbsp;<tr><td><input type='button' class='boton' ";
						echo "value=' Mostrar Imagen ' onclick='window.open(\"mostrar_imagen.php?orden=$obj2->orden&termino=$termino\")'/></td></tr>";
					}
					else
					{
						//echo "&nbsp;&nbsp;<tr><td><input type='button' class='boton' ";
						//echo "value=' Mostrar Imagen ' onclick='window.open(\"http://images.google.com\",\"_blank\")'/></td></tr>";
						echo "&nbsp;&nbsp;<tr><td colspan=\"4\"><b>Este t&eacute;rmino no dispone de una imagen, si desea a&ntilde;adir una, pulse el bot&oacute;n Modificar T&acute;rmino.</b></td></tr><tr></tr>";
					}
				}
				
				echo "<tr class='contextos_out' id='".$id_termino."_".$obj2->orden."'>\n<td colspan='4'>";
				echo "<span id='".$id_termino."_".$obj2->orden."_contextos'>";
				
				mostrar_terminos_administrar($id_termino, $obj2->orden, $obj->termino, $obj->idioma);
				
				echo "</span></td></tr></table></span><br>";
				$i++;
			}
		} else {
			echo "<span class='Info2' id='noacepciones' align='center'>No se encontraron acepciones.</span>";
		}
		echo "<br>";
		if ($es_alta)
		{
			echo "<tr><td colspan='4' align='center'><input type=\"submit\" class=\"boton\" value=\" Dar de alta el t&eacute;rmino\"/></td></tr>";
		}
		else
		{
			echo "<tr><td colspan='4' align='center'><input type=\"submit\" class=\"boton\" value=\" Modificar t&eacute;rmino\"/></td></tr>";
		}
		echo "</div></td></tr><tr><td colspan='4'>&nbsp;</td></tr></table>";
		echo "</form>";
	}
	//mysql_close($enlace);		
}

//-----------------------------------------------------------------------------------------------------------------------------------

function alta_termino ($termino, $id, $id_termino, $compuesto, $pal_term_comp1, $pal_term_comp2, $term_comp_select1, $term_comp_select2, $lim_tamano)
{
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
	
	include ("../comun/conexion.php");

	if ($compuesto != "") // es un termino compuesto asique actualizamos la bbdd
	{
		$consulta = "UPDATE glosario SET term_compuesto='$compuesto', compuesto1='$pal_term_comp1', compuesto2='$pal_term_comp2' WHERE id_glosario = '$id_termino'";
		$res = mysql_query($consulta) or die (mysql_error());
	}

	echo "<form enctype=\"multipart/form-data\" action=\"operacion_glosario.php\" method=\"post\" name=\"formulario\">";
	echo "<input type=\"hidden\" name=\"arg_op\" value=\"imagen\">";
	if ($compuesto == 1) // se esta tratando un termino compuesto
    {
			// Si hay alguna acepcion para ese termino y no dispone de imagen, se pregunta al usuario si se quiere anadir una.
		   $consulta = "SELECT orden, definicion, cat_gramatical, traduccion, imagen, nombre, tamano, formato FROM acepcion WHERE id_glosario='$id_termino' order by orden";
		   $res = mysql_query($consulta) or die (mysql_error());
			
			echo "<input type=\"hidden\" name=\"lim_tamano\" value=\"65000\"/>";
			echo "<input type=\"hidden\" name=\"termino\" value=\" ".$termino." \">";
			echo "<input type=\"hidden\" name=\"id_termino\" value=\" ".$id_termino." \">";
			echo "<input type=\"hidden\" name=\"idioma\" value='".$id."'>";
			
			if (mysql_num_rows($res) != 0) // Tiene al menos una acepcion
		    {
				echo "<p align=\"center\">Las siguientes acepciones del t&eacute;rmino no disponen de una imagen, &iquest;desea a&ntilde;adir una?</p>";
				echo "<table border=\"0\" width=\"100%\" cellpadding=\"5\" cellspacing=\"5\">";
				echo "<tr>";
				echo "<td align=\"center\"><b><u><font size=\"3\">".$termino."</font></u></b></td>";
				echo "</tr><tr></tr>";
				$i = 0;
				while ($obj = mysql_fetch_object($res))
				{
					if ($obj->tamano == 0) // la acepcion no tiene imagen y se pregunta si se quiere anadir una
					{
						echo "<tr><td align=\"center\">";
						echo "<input type=\"checkbox\" name=\"acepcion1".$i."\" value=\"".$obj->orden."\"/><b><i>Acepeci&oacute;n ".$i."</i></b>";
						echo "<br></td></tr>";
						echo "<tr><td align=\"center\">";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Categor&iacute;a Gramatical:   </b> ".$obj->cat_gramatical."<br></td></tr>";
						echo "<tr><td align=\"center\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Traducci&oacute;n:   </b>".$obj->traduccion."<br></td></tr>";
						echo "<tr><td align=\"center\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Definici&oacute;n:   </b>".$obj->definicion."<br></td></tr>";
						echo "<tr><td colspan='4' align=\"center\"><b>Imagen</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#000000\"><b>Introduzca una imagen</b></font><input type=\"file\" name=\"archivo\"></td></tr>";			
						$i++;
					}
				}
				if ((($term_comp_select1 == "Otro" || $term_comp_select1 == "Another") && ($term_comp_select2 != "Otro" || $term_comp_select2 != "Another")) ||
					(($term_comp_select2 == "Otro" || $term_comp_select2 == "Another") && ($term_comp_select1 != "Otro" || $term_comp_select1 != "Another")) ||
					(($term_comp_select1 == "Otro" || $term_comp_select1 == "Another") && ($term_comp_select2 == "Otro" || $term_comp_select2 == "Another")))
				{
					if (($term_comp_select1 == "Otro" || $term_comp_select1 == "Another") || 
						(($term_comp_select1 == "Otro" || $term_comp_select1 == "Another") && ($term_comp_select2 == "Otro" || $term_comp_select2 == "Another")))
					{
						echo "<input type=\"hidden\" name=\"termino_comp1\" value=\"$pal_term_comp1\">";
						//echo "<input type=\"hidden\" name=\"desdelista\" value=\"$desdelista\">";
						echo "<input type=\"hidden\" name=\"idioma\" value=\"$id\">";
						echo "<input type=\"hidden\" name=\"continuar\" value=\"si\">";
					}
					else
					{
						echo "<input type=\"hidden\" name=\"termino_comp2\" value=\"$pal_term_comp2\">";
						//echo "<input type=\"hidden\" name=\"desdelista\" value=\"$desdelista\">";
						echo "<input type=\"hidden\" name=\"idioma\" value=\"$id\">";
						echo "<input type=\"hidden\" name=\"continuar\" value=\"si\">";
					}
				}

				
				//echo "<table border=\"0\" align=\"center\"> <td align=\"center\"><br>";
			   echo "<tr><td align=\"center\">";
			   echo "<input type=\"submit\" class=\"boton long_93 boton_aceptar\" value=\"      $boton_aceptar\" />&nbsp;&nbsp;&nbsp;";
			   echo "<input type=\"button\" class=\"boton long_93 boton_cancelar\" value=\"	Finalizar\" onclick=\"document.location='resultado.php?inicial=admin_termino';\" />";
			   echo "</td></tr></table>";
			   echo "</form>";
		    }
		    else // no tiene acepciones que mostrar, se comprueba si se ha seleccionado 'Otro'
		    {
				echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>".$el_term."<b>$termino</b>".$mensaje80."</p>";
				if ((($term_comp_select1 == "Otro" || $term_comp_select1 == "Another") && ($term_comp_select2 != "Otro" || $term_comp_select2 != "Another")) ||
					(($term_comp_select2 == "Otro" || $term_comp_select2 == "Another") && ($term_comp_select1 != "Otro" || $term_comp_select1 != "Another")) ||
					(($term_comp_select1 == "Otro" || $term_comp_select1 == "Another") && ($term_comp_select2 == "Otro" || $term_comp_select2 == "Another")))
				{
					if (($term_comp_select1 == "Otro" || $term_comp_select1 == "Another") || 
						(($term_comp_select1 == "Otro" || $term_comp_select1 == "Another") && ($term_comp_select2 == "Otro" || $term_comp_select2 == "Another")))
					{
						//echo "<input type=\"hidden\" name=\"termino_comp1\" value=\"$pal_term_comp1\">";
						//echo "<input type=\"hidden\" name=\"continuar\" value=\"si\">";
						$consulta = "SELECT id_glosario FROM glosario WHERE termino='$pal_term_comp1'";
						$res = mysql_query($consulta) or die (mysql_error());
						if (mysql_num_rows($res) == 0) // el termino de verdad no existe en el glosario
						{
							echo "<p align=\"center\"><font size=\"4\"><b>".$el_term." <i>$pal_term_comp1</i> no esta incluido en el glosario</b></font></p>";
							echo "<p align=\"center\">Si desea incluirlo, pulse aceptar, en caso contrario pulse finalizar</p>";
							echo "<table border=\"0\" align=\"center\"> <td align=\"center\" colspan=\"2\"><br>";
								echo "<input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      $boton_aceptar\" onclick=\"document.location='operacion_glosario.php?arg_op=nuevo&termino=$pal_term_comp1&idioma=$id';\" />&nbsp;&nbsp;";
								echo "<input type=\"button\" class=\"boton long_93 boton_cancelar\" value=\"      Finalizar\" onclick=\"document.location='resultado.php?inicial=admin_termino';\" />";				
							echo "</td></table>";
						}
						else
						{
							echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_termino'\" /></p>";
						}
					}
					else
					{
						//echo "<input type=\"hidden\" name=\"termino_comp2\" value=\"$pal_term_comp2\">";
						//echo "<input type=\"hidden\" name=\"idioma\" value=\"$id\">";
						//echo "<input type=\"hidden\" name=\"continuar\" value=\"si\">";
						$consulta = "SELECT id_glosario FROM glosario WHERE termino='$pal_term_comp2'";
						$res = mysql_query($consulta) or die (mysql_error());
						if (mysql_num_rows($res) == 0) // el temrino de verdad no existe en el glosario
						{
							echo "<p align=\"center\"><font size=\"4\"><b>".$el_term." <i>$pal_term_comp2</i> no esta incluido en el glosario</b></font></p>";
							echo "<p align=\"center\">Si desea incluirlo, pulse aceptar, en caso contrario pulse finalizar</p>";
							echo "<table border=\"0\" align=\"center\"> <td align=\"center\" colspan=\"2\"><br>";
								echo "<input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      $boton_aceptar\" onclick=\"document.location='operacion_glosario.php?arg_op=nuevo&termino=$pal_term_comp2&idioma=$id';\" />&nbsp;&nbsp;";
								echo "<input type=\"button\" class=\"boton long_93 boton_cancelar\" value=\"      Finalizar\" onclick=\"document.location='resultado.php?inicial=admin_termino';\" />";				
							echo "</td></table>";
						}
						else
						{
							echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_termino'\" /></p>";
						}
					}
				}
				else
				{
					echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_termino'\" /></p>";
				}
		    }
		//}
    }
   else // se esta tratando un termino simple
   {		
		// Si hay alguna acepcion para ese termino y no dispone de imagen, se pregunta al usuario si se quiere anadir una.
	    $consulta = "SELECT orden, definicion, cat_gramatical, traduccion, imagen, nombre, tamano, formato FROM acepcion WHERE id_glosario='$id_termino' order by orden";
	    $res = mysql_query($consulta) or die (mysql_error());
	   
	    echo "<input type=\"hidden\" name=\"lim_tamano\" value=\"65000\"/>";
		echo "<input type=\"hidden\" name=\"termino\" value=\" ".$termino." \">";
		echo "<input type=\"hidden\" name=\"id_termino\" value=\" ".$id_termino." \">";
		echo "<input type=\"hidden\" name=\"idioma\" value='".$id."'>";
		
		if (mysql_num_rows($res) != 0) // Tiene al menos una acepcion
		    {
				echo "<p align=\"center\">Las siguientes acepciones del t&eacute;rmino no disponen de una imagen, &iquest;desea a&ntilde;adir una?</p>";
				echo "<table border=\"0\" width=\"100%\" cellpadding=\"5\" cellspacing=\"5\">";
				echo "<tr>";
				echo "<td align=\"center\"><b><u><font size=\"3\">".$termino."</font></u></b></td>";
				echo "</tr><tr></tr>";
				$i = 0;
				while ($obj = mysql_fetch_object($res))
				{
					if ($obj->tamano == 0) // la acepcion no tiene imagen y se pregunta si se quiere anadir una
					{
						echo "<tr><td align=\"center\">";
						echo "<input type=\"checkbox\" name=\"acepcion1".$i."\" value=\"".$obj->orden."\"/><b><i>Acepeci&oacute;n ".$i."</i></b>";
						echo "<br></td></tr>";
						echo "<tr><td align=\"center\">";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Categor&iacute;a Gramatical:   </b> ".$obj->cat_gramatical."<br></td></tr>";
						echo "<tr><td align=\"center\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Traducci&oacute;n:   </b>".$obj->traduccion."<br></td></tr>";
						echo "<tr><td align=\"center\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Definici&oacute;n:   </b>".$obj->definicion."<br></td></tr>";
						echo "<tr><td colspan='4' align=\"center\"><b>Imagen</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#000000\"><b>Introduzca una imagen</b></font><input type=\"file\" name=\"archivo\"></td></tr>";			
						$i++;
					}
				}
				echo "<tr><td align=\"center\">";
			    echo "<input type=\"submit\" class=\"boton long_93 boton_aceptar\" value=\"      $boton_aceptar\" />&nbsp;&nbsp;&nbsp;";
			    echo "<input type=\"button\" class=\"boton long_93 boton_cancelar\" value=\"	Finalizar\" onclick=\"document.location='resultado.php?inicial=admin_termino';\" />";
			    echo "</td></tr></table>";
			    echo "</form>";
	    }
		else // no tiene acepciones
		{
			echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>".$el_term."<b>$termino</b>".$mensaje80."</p>";
			echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_termino'\" /></p>";
		}
	}
	mysql_close($enlace);
   
}
//-----------------------------------------------------------------------------------------------------------------------------------

function modificar_termino($termino, $id, $id_termino, $lim_tamano, $desdelista)
{
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
	
	include ("../comun/conexion.php");
	
	$consulta = "SELECT termino FROM glosario where id_glosario='$id_termino'";
	$res2 = mysql_query($consulta) or die (mysql_error());
	$obj2 = mysql_fetch_object($res2);
	$termino = $obj2->termino;
	
	// se muestran todas las acepciones del termino, tanto si tienen imagen como si no.
	$consulta = "SELECT orden, definicion, cat_gramatical, traduccion, imagen, nombre, tamano, formato FROM acepcion WHERE id_glosario='$id_termino' order by orden";
    $res = mysql_query($consulta) or die (mysql_error());
	
	if (mysql_num_rows($res) != 0) // Tiene al menos una acepcion
	{
		echo "<form enctype=\"multipart/form-data\" action=\"operacion_glosario.php\" method=\"post\" name=\"formulario\">";
		echo "<input type=\"hidden\" name=\"arg_op\" value=\"imagen\">";
		
		echo "<input type=\"hidden\" name=\"lim_tamano\" value=\"65000\"/>";
		echo "<input type=\"hidden\" name=\"termino\" value=\" ".$termino." \">";
		echo "<input type=\"hidden\" name=\"id_termino\" value=\" ".$id_termino." \">";
		echo "<input type=\"hidden\" name=\"idioma\" value='".$id."'>";
		echo "<input type=\"hidden\" name=\"modificar\" value='si'>";
		
		echo "<p align=\"center\">".$el_term." <b>$termino</b> tiene las siguientes acepciones.</p>";
		echo "<p align=\"center\">Si desea a&ntilde;adir una nueva imagen, seleccione la acepci&oacute;n a la que desea a&ntilde;adir la imagen.</p>";
		echo "<table border=\"0\" width=\"100%\" cellpadding=\"5\" cellspacing=\"5\">";
		echo "<tr>";
		echo "<td align=\"center\"><b><u><font size=\"3\">".$termino."</font></u></b></td>";
		echo "</tr><tr></tr>";
		$i = 0;
		while ($obj = mysql_fetch_object($res))
		{
			echo "<tr><td align=\"center\">";
			echo "<input type=\"checkbox\" name=\"acepcion1".$i."\" value=\"".$obj->orden."\"/><b><i>Acepeci&oacute;n ".$i."</i></b>";
			echo "<br></td></tr>";
			echo "<tr><td align=\"center\">";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Categor&iacute;a Gramatical:   </b> ".$obj->cat_gramatical."<br></td></tr>";
			echo "<tr><td align=\"center\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Traducci&oacute;n:   </b>".$obj->traduccion."<br></td></tr>";
			echo "<tr><td align=\"center\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Definici&oacute;n:   </b>".$obj->definicion."<br></td></tr>";
			if ($obj->tamano == 0) // la acepcion no tiene imagen
			{
				echo "<tr><td colspan='4' align=\"center\"><b>Imagen</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#000000\"><b>Introduzca una imagen</b></font><input type=\"file\" name=\"archivo\"></td></tr>";			
				$i++;
			}
			else // la acepcion ya tiene una imagen
			{
				echo "<tr><td align=\"center\"><input type='button' class='boton' ";
				echo "value=' Mostrar Imagen ' onclick='window.open(\"mostrar_imagen.php?orden=$obj->orden&termino=$termino\")'/></td></tr>";
				echo "<tr><td colspan='3' align=\"center\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#000000\"><b>Introduzca una nueva imagen</b></font><input type=\"file\" name=\"archivo\"></td></tr>";			
				$i++;
			}
		}
		echo "<tr><td align=\"center\">";
	    echo "<input type=\"submit\" class=\"boton long_93 boton_aceptar\" value=\"      $boton_aceptar\" />&nbsp;&nbsp;&nbsp;";
	    echo "<input type=\"button\" class=\"boton long_93 boton_cancelar\" value=\"	Finalizar\" onclick=\"document.location='resultado.php?inicial=admin_termino';\" />";
	    echo "</td></tr></table>";
	    echo "</form>";

	}
	else // el termino no tiene acepciones
	{
		echo "<p align=\"center\"><font size=\"4\"><b>".$el_term." <i>$termino</i> ".$mensaje3."</b></font></p>";
		echo "<table border=\"0\" align=\"center\"> <td align=\"center\"><br>";
		echo "<input type=\"button\" class=\"boton long_93 boton_cancelar\" value=\"      $boton_aceptar\" onclick=\"document.location='resultado.php?inicial=admin_termino';\" />";
		echo "</td></table>";
	}
}

//-----------------------------------------------------------------------------------------------------------------------------------

// Funcion encargada de mostrar una serie de contextos. Usada para adminsitracion.
function mostrar_terminos_administrar ($id_termino, $orden, $termino, $idioma) {
	// Preparamos la conexion
	include ("../comun/conexion.php");
	
	echo "<tr><td><b><u>Contextos</u></b></td></tr>";
	
	// Contextos
	$consulta = "SELECT a.id_contexto,a.contexto,a.fecha_alta,a.usuario_alta,b.h_title FROM contexto a left join texto b on a.id_texto=b.id_texto WHERE id_glosario = '$id_termino' and orden=".$orden;
	$res3 = mysql_query($consulta) or die ("No se pudieron leer los contextos.");
	
	$html_contextos = "";
	$num_contextos = 0;
	
	if (mysql_num_rows($res3) != 0){
		$html_contextos.= "<tr><td align='center' width='20%'><b>Fuente</b></td><td align='center' width='50%'><b>Contexto</b></td>";
		if (tienePermisos("buscadorespecial"))
			$html_contextos.= "<td align='center' width='15%'><b>Usuario alta</b></td><td align='center' width='15%'><b>Fecha alta</b></td><td>&nbsp;</td>";
		$html_contextos.= "</tr>";
		while($obj3 = mysql_fetch_object($res3)) {
			$num_contextos++;
			$html_contextos.= "<tr id='".$obj3->id_contexto."_contexto'><td align='center'>".$obj3->h_title."</td><td align='center'>".marcarTermino($obj3->contexto, $termino, $idioma)."</td>";
			
			if (tienePermisos("buscadorespecial")) {
				$fecha_alta = "";
				if ($obj3->fecha_alta != "0000-00-00")
					$fecha_alta = implode('/',array_reverse(explode('-',$obj3->fecha_alta)));
				$html_contextos.= "<td align='center'>".$obj3->usuario_alta."</td>";
				$html_contextos.= "<td align='center'>".$fecha_alta."</td>";
				
			}
			$html_contextos.=  "<td><a href='#' onclick='event.cancelBubble=true;eliminarContexto(\"".$id_termino."_".$orden."\",".$obj3->id_contexto.")'><img src='../imagenes/papelera_ico.png' border='0' title='Eliminar contexto'></a></td>";
			$html_contextos.=  "</tr>";
		}
	} else {
		$html_contextos.=  "<tr><td align='center'>No se encontraron contextos.</td></tr>";
	}
	
	echo "<table width='100%' class='contextos'>".$html_contextos;
	echo "</table><script>contextos_contador['".$id_termino."_".$orden."']=".$num_contextos.";</script>";

	// Cerramos la conexion.
	mysql_close($enlace);		
}

// --------------------------------------------------------------------------------------------------------------------------

// Funcion encargada de buscar contextos para un termino ($termino) en una lista de doucmentos ($documento). 
function buscar_contextos($termino, $documento, $idioma) // antigua funcion nuevo_termino($termino, $idioma)
{
	$encontrados = 0; // Numero de contextos encontrados.
	
	/* Consulta a la base de datos */
	include ("../comun/conexion.php");
   
	// Construccion de la consulta.
	$sql_where = "";
	while($elemento = each($documento)) {
		if ($sql_where != "")
			$sql_where .= ",";
		$sql_where .= $elemento[1];
	}

	/* Buscar ocurrencias y crear contextos */
	$consulta2 = "SELECT id_texto,h_title,body FROM texto WHERE id_texto in (".$sql_where.")";
	$res2 = mysql_query($consulta2) or die("No se pudo leer los textos");
	
	while($obj = mysql_fetch_object($res2))  // Busqueda en los textos de la BD
	{
		$aux_encontrado = 0;
		$s_html_inicio = "<tr style='cursor:hand' onclick='show(".$obj->id_texto.")' title=Pulse&nbsp;aqu&iacute;&nbsp;para&nbsp;mostrar/ocultar><td><p class='Info2'><b>TEXTO:</b>&nbsp;".preg_replace("/\s/","&nbsp;",$obj->h_title).".&nbsp;C&Oacute;DIGO:&nbsp;".$obj->id_texto;
		$s_html_fin = "</table></td></tr>"; 
		$s_html = ""; 
	
		$vector = extraerElementos($obj->body);
		$contextos = buscar_contextos_como_lista ($termino, $vector, $idioma);
		foreach($contextos as $contexto)
		{
			$aux_encontrado++;
			$contexto_normalizado1 = preg_replace("/[\n\r|\n|\r]+/i","<br>",preg_replace("/\'/i","\\'",$contexto));
			$contexto_normalizado2 = preg_replace("/[\n\r|\n|\r]+/i","<br>",htmlentities($contexto));
			$s_html .= "<tr><td style='vertical-align:top'><input type='checkbox' name='contextos[]' value='".$obj->id_texto."#".$contexto_normalizado1."'></td><td>".$contexto_normalizado2."</td></tr>";
		}
	      
		$encontrados += $aux_encontrado;
		      
		if ($aux_encontrado > 0) {
			$s_html_inicio .= "&nbsp;<b>CONTEXTOS&nbsp;ENCONTRADOS:</b>&nbsp;".$aux_encontrado."&nbsp;&nbsp;</p></td></tr>";
		    $s_html_inicio .= "<tr><td style='display:none;border: dashed;border-width: 1px;border-color: #808080;margin-left: 1%;margin-right: 1%;' id='".$obj->id_texto."'><table width='100%' border='0' cellpadding='0' cellspacing='4' bgcolor='#D8D7A3'>";
			echo $s_html_inicio.$s_html.$s_html_fin;
			flush();
		}
	}
	
   return $encontrados;
}

function buscar_contextos_como_lista ($termino, $vector, $idioma) {
	$contextos = array();
	
	$term_mayusc = convertirMayusculas(eliminarTildes($termino));
	$term_mayusc_pl = convertirMayusculas(eliminarTildes(crearPlural($termino, $idioma)));
	$i = 0;
	foreach($vector as $x)
	{
		$x_mayusc = convertirMayusculas(eliminarTildes($x));

		if($x_mayusc ==  $term_mayusc || $x_mayusc == $term_mayusc_pl)  // Ocurrencia encontrada, crear contexto
		{
			$contexto = crearContexto($vector, $i);
			$contextos[] = $contexto;
		}
		$i++;
	}
	
	return $contextos;
}

//------------------------------------------------------------------------------

// Devuelve una lista de terminos cuya inicial coincide con el parametro.
function buscar_inicial($inicial)
{
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

	/* Consulta a la base de datos */
	include ("../comun/conexion.php");
   
	$id = array("esp","ing");
	$texto_idioma = array("<p id=\"titulo\">".$term_esp."</p>",
		"<p id=\"titulo\">".$term_ing."</p>");
	
	echo "<table border=\"0\" width=\"90%\" cellspacing=\"4\">";
	echo "<tr>";
	/* Listado de terminos */
	for ($i=0; $i < count($id); $i++) {
		if($inicial == 'todas') {
			$consulta = "SELECT a.id_glosario,a.termino,b.cat_gramatical FROM glosario a left join acepcion b on a.id_glosario=b.id_glosario WHERE a.idioma = '$id[$i]' order by a.termino, b.cat_gramatical asc";
		} else {
			$consulta = "SELECT a.id_glosario,a.termino,b.cat_gramatical FROM glosario a left join acepcion b on a.id_glosario=b.id_glosario WHERE (inicial = '$inicial' AND idioma = '$id[$i]') order by a.termino, b.cat_gramatical asc";
		}
		$res = mysql_query($consulta) or die (mysql_error().$consulta);
	
		echo "<td align=\"left\" valign=\"top\" ";
		if ($i < count($idioma)-1)
			echo "style=\"border-right: 4 dotted #CC0000\"";
		echo ">$texto_idioma[$i]<br><br>\n";
	
		$num = 0;
		$anterior = "";
		$textohtml_inicio = "";
		$textohtml_categoria = "";
		$textohtml_fin = "";
		
		while($obj = mysql_fetch_object($res)) {
			
			if ($anterior != $obj->termino || $anterior == "") {
				$num++;
				echo $textohtml_inicio.$textohtml_categoria.$textohtml_fin;
				
				$anterior = "$obj->termino";
				$textohtml_inicio = "<p id=\"Line\"><a href=\"operacion_glosario.php?arg_op=mostrar&termino=$obj->id_glosario\">".$obj->termino."</a> (<i>";
				$textohtml_fin = "</i>)</p>"; 
				
				switch($obj->cat_gramatical)
				{
					case('sustantivo'): $textohtml_categoria = 'n.'; break;
					case('verbo'): $textohtml_categoria = 'vb.'; break;
					case('determinante'): $textohtml_categoria = 'det.'; break;
					case('adjetivo'): $textohtml_categoria = 'adj.'; break;
					case('pronombre'): $textohtml_categoria = 'pron.'; break;
					case('preposicion'): $textohtml_categoria = 'prep.'; break;
					case('conjuncion'): $textohtml_categoria = 'conj.'; break;
					case('adverbio'): $textohtml_categoria = 'adv.'; break;
					case('interjeccion'): $textohtml_categoria = 'interj.'; break;
				}
			} else {
				$textohtml_categoriaaux = "";
				switch($obj->cat_gramatical)
				{
					case('sustantivo'): $textohtml_categoriaaux = 'n.'; break;
					case('verbo'): $textohtml_categoriaaux = 'vb.'; break;
					case('determinante'): $textohtml_categoriaaux = 'det.'; break;
					case('adjetivo'): $textohtml_categoriaaux = 'adj.'; break;
					case('pronombre'): $textohtml_categoriaaux = 'pron.'; break;
					case('preposicion'): $textohtml_categoriaaux = 'prep.'; break;
					case('conjuncion'): $textohtml_categoriaaux = 'conj.'; break;
					case('adverbio'): $textohtml_categoriaaux = 'adv.'; break;
					case('interjeccion'): $textohtml_categoriaaux = 'interj.'; break;
				}
				
				if (!preg_match("/".$textohtml_categoriaaux."/i",$textohtml_categoria))
					$textohtml_categoria .= "&nbsp;/&nbsp;".$textohtml_categoriaaux;
			}
	   }
	   
	   if ($textohtml_inicio != "")
		echo "<p id=\"Line\">".$textohtml_inicio.$textohtml_categoria."</i>)</p>";
	   
		echo "<p id=\"Info\"><b>".$num."</b> ".$term."(s) ".$mensaje86." <b>'".$inicial."'</b></p>";
		
		echo "</td>";
   }
   
   echo "</tr></table>";
   
   mysql_close($enlace);
}

//------------------------------------------------------------------------------
// realiza la busqueda de un termino por su nombre
function buscar_termino($termino)
{
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
   
   /* Consulta a la base de datos */

   include ("../comun/conexion.php");
   
   $consulta = "SELECT termino,id_glosario FROM glosario WHERE termino = '$termino'";
   $res = mysql_query($consulta);

   mysql_close($enlace);

   if (mysql_num_rows($res) != 0)
   {
		$obj = mysql_fetch_object($res);
		mostrar_termino($obj->id_glosario);
		//mostrar_termino($termino);
   }
   else
   {
        echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$mensaje87." <b>".$termino."</b> ".$mensaje88."</p>";
		echo "<table align=\"center\" border=\"0\" width=\"80%\"><tr><td>
		".$mensaje89."
		<ul>
			<li>".$mensaje90." <b>plural</b>, ".$mensaje91." </li>
			<li>".$mensaje92." <b>-ing</b>, ".$mensaje93."</li>
			<li>".$mensaje94." <b>".$abreviatura."</b>, ".$mensaje95."</li>
		</ul>
		</td></tr></table>";
		echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php';\"/></p>";
   }

}

//------------------------------------------------------------------------------
// realiza la busqueda de termino por categoria gramatical
function buscar_cat_gramatical($cat_gram)
{
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
	
	/* Consulta a la base de datos */

	include ("../comun/conexion.php");

	$id = array("esp","ing");
	$texto_idioma = array("<p id=\"titulo\">".$term_esp."</p>",
		"<p id=\"titulo\">".$term_ing."</p>");
	
	echo "<table border=\"0\" width=\"90%\" cellspacing=\"5\">";
	echo "<tr>";
	/* Listado de terminos */
	for ($i=0; $i < count($id); $i++) {
		$consulta = "SELECT a.id_glosario,a.termino,b.cat_gramatical FROM glosario a left join acepcion b on a.id_glosario=b.id_glosario WHERE a.idioma = '".$id[$i].
			"' and b.cat_gramatical='".$cat_gram."' order by a.termino, b.cat_gramatical asc";
		$res = mysql_query($consulta) or die (mysql_error().$consulta);
	
		echo "<td align=\"left\" valign=\"top\" ";
		if ($i < count($idioma)-1)
			echo "style=\"border-right: 4 dotted #CC0000\"";
		echo ">$texto_idioma[$i]<br><br>\n";
	
		$num = 0;
		$anterior = "";
		$textohtml_inicio = "";
		$textohtml_categoria = "";
		$textohtml_fin = "";
		
		while($obj = mysql_fetch_object($res)) {
			
			if ($anterior != $obj->termino || $anterior == "") {
				$num++;
				echo $textohtml_inicio.$textohtml_categoria.$textohtml_fin;
				
				$anterior = "$obj->termino";
				$textohtml_inicio = "<p id=\"Line\"><a href=\"operacion_glosario.php?arg_op=mostrar&termino=$obj->id_glosario\">".$obj->termino."</a> (<i>";
				$textohtml_fin = "</i>)</p>"; 
				
				switch($obj->cat_gramatical)
				{
					case('sustantivo'): $textohtml_categoria = 'n.'; break;
					case('verbo'): $textohtml_categoria = 'vb.'; break;
					case('determinante'): $textohtml_categoria = 'det.'; break;
					case('adjetivo'): $textohtml_categoria = 'adj.'; break;
					case('pronombre'): $textohtml_categoria = 'pron.'; break;
					case('preposicion'): $textohtml_categoria = 'prep.'; break;
					case('conjuncion'): $textohtml_categoria = 'conj.'; break;
					case('adverbio'): $textohtml_categoria = 'adv.'; break;
					case('interjeccion'): $textohtml_categoria = 'interj.'; break;
				}
			} else {
				$textohtml_categoriaaux = "";
				switch($obj->cat_gramatical)
				{
					case('sustantivo'): $textohtml_categoriaaux = 'n.'; break;
					case('verbo'): $textohtml_categoriaaux = 'vb.'; break;
					case('determinante'): $textohtml_categoriaaux = 'det.'; break;
					case('adjetivo'): $textohtml_categoriaaux = 'adj.'; break;
					case('pronombre'): $textohtml_categoriaaux = 'pron.'; break;
					case('preposicion'): $textohtml_categoriaaux = 'prep.'; break;
					case('conjuncion'): $textohtml_categoriaaux = 'conj.'; break;
					case('adverbio'): $textohtml_categoriaaux = 'adv.'; break;
					case('interjeccion'): $textohtml_categoriaaux = 'interj.'; break;
				}
				
				if (!preg_match("/".$textohtml_categoriaaux."/i",$textohtml_categoria))
					$textohtml_categoria .= "&nbsp;/&nbsp;".$textohtml_categoriaaux;
			}
	   }
	   
	   if ($textohtml_inicio != "")
		echo $textohtml_inicio.$textohtml_categoria."</i>)<br>";
	   
		echo "<p id=\"Info\">".$num."</b> ".$term."(s) ".$mensaje86." <b>".$inicial."</b></p>";
		
		echo "</td>";
   }
   
   echo "</tr></table";
   
   mysql_close($enlace);
   
}

// ----------------------------------------------------------------------------------------------------

function listar_terminos($inicial)
{
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

	/* Consulta a la base de datos */
	include ("../comun/conexion.php");

	$id = array("esp","ing");
	$texto_idioma = array("<img border\"0\" src=\"../imagenes/bandera_esp_gran.gif\"><span class=\"titulo titulo_gris\">&nbsp;&nbsp;T&eacute;rminos en <b>ESPA&Ntilde;OL<b></span>",
		"<img border\"0\" src=\"../imagenes/bandera_ing_grande.gif\"><span class=\"titulo titulo_gris\">&nbsp;&nbsp;T&eacute;rminos en <b>INGL&Eacute;S<b></span>");
   
	echo "<p align=\"center\"><span class=\"titulo titulo_rojo\">Administraci&oacute;n de T&eacute;rminos del Glosario</span><br>";
	echo "<img border=\"0\" src=\"../imagenes/linea_horiz.gif\" ></p>";
	echo "<table border=\"0\" width=\"90%\" cellspacing=\"5\"><tr>";
	
	/* Listado de terminos */
	for ($i=0; $i < count($id); $i++) {
		if($inicial == 'todas') {
			$consulta = "SELECT a.id_glosario,a.termino,a.idioma,a.usuario_alta,a.fecha_alta,a.usuario_modificacion,a.fecha_modificacion,b.cat_gramatical FROM glosario a left join acepcion b on a.id_glosario=b.id_glosario WHERE a.idioma = '$id[$i]' order by a.termino, b.cat_gramatical asc";
		} else {
			$consulta = "SELECT a.id_glosario,a.termino,a.idioma,a.usuario_alta,a.fecha_alta,a.usuario_modificacion,a.fecha_modificacion,b.cat_gramatical FROM glosario a left join acepcion b on a.id_glosario=b.id_glosario WHERE (inicial = '$inicial' AND idioma = '$id[$i]') order by a.termino, b.cat_gramatical asc";
		}
		$res = mysql_query($consulta) or die ("No se pudo leer el glosario:".$consulta);
	
		echo "<td align=\"center\" valign=\"top\"";
		if ($i < count($id)-1)
			echo "style=\"border-right: 4 dotted #CC0000\"";
			
		echo ">$texto_idioma[$i]<br><br>";
	
		echo "<table border=\"0\" cellpadding=\"4\" cellspacing=\"1\" bgcolor=\"#CC0000\">";
		echo "   <tr bgcolor=\"#D8D9A4\">";       
		echo "      <td align=\"center\"><b>T&eacute;rmino</b></td><td align=\"center\"><b>Categor&iacute;a gramatical</b></td>";
		if (tienePermisos("buscadorespecial")) {
			echo "      <td align=\"center\"><b>Usuario (alta)</b></td>";
			echo "      <td align=\"center\"><b>Fecha (modificaci&oacute;n)</b></td>";
			echo "      <td align=\"center\"><b>Usuario (alta)</b></td>";
			echo "      <td align=\"center\"><b>Fecha (modificaci&oacute;n)</b></td>";
		}
		echo "      <td></td>";
		echo "   </tr>";
	
		$num = 0;
		$anterior = "";
		$textohtml_inicio = "";
		$textohtml_categoria = "";
		$textohtml_fin = "";
		$texto_categoria = array();
		$esprimero = true;
		
		while($obj = mysql_fetch_object($res)) {
			
			if ($anterior != $obj->termino || $anterior == "") {
				$anterior = "$obj->termino";
				
				if ($texto_categoria["sustantivo"]!="") {$esprimero=false;$textohtml_categoria.= $sustantivo;}
				if ($texto_categoria["verbo"]!="") {if (!$esprimero) {$textohtml_categoria.= '<br>';}$textohtml_categoria.= $verbo;$esprimero=false;}
				if ($texto_categoria["determinante"]!="") {if (!$esprimero) {$textohtml_categoria.= '<br>';}$textohtml_categoria.= $determinante;$esprimero=false;}
				if ($texto_categoria["adjetivo"]!="") {if (!$esprimero) {$textohtml_categoria.= '<br>';}$textohtml_categoria.= $adjetivo;$esprimero=false;}
				if ($texto_categoria["pronombre"]!="") {if (!$esprimero) {$textohtml_categoria.= '<br>';}$textohtml_categoria.= $pronombre;$esprimero=false;}
				if ($texto_categoria["preposicion"]!="") {if (!$esprimero) {$textohtml_categoria.= '<br>';}$textohtml_categoria.= $preposicion;$esprimero=false;}
				if ($texto_categoria["conjuncion"]!="") {if (!$esprimero) {$textohtml_categoria.= '<br>';}$textohtml_categoria.= $conjuncion;$esprimero=false;}
				if ($texto_categoria["adverbio"]!="") {if (!$esprimero) {$textohtml_categoria.= '<br>';}$textohtml_categoria.= $adverbio;$esprimero=false;}
				if ($texto_categoria["interjeccion"]!="") {if (!$esprimero) {$textohtml_categoria.= '<br>';}$textohtml_categoria.= $interjeccion;$esprimero=false;}
				
				echo $textohtml_inicio.$textohtml_categoria.$textohtml_fin;
				$textohtml_inicio = "";
				$textohtml_categoria = "<td><b>";
				$textohtml_fin = "</b></td>";
				$texto_categoria = array();
				$esprimero = true;
				
				if(($num % 2) == 0) {
					$textohtml_inicio = "<tr bgcolor=#FFFFFF>";
				} else {
					$textohtml_inicio = "<tr bgcolor=#FFFF99>";
				}
				
				$texto_categoria[$obj->cat_gramatical] = '1';
				
				$textohtml_inicio .= "<td><b>".$obj->termino."</b></td>";
			
				if (tienePermisos("buscadorespecial")) {
					$fecha_creation = "";
					$fecha_modificacion = "";
					$textohtml_fin .= "<td>".$obj->usuario_alta."</td>";
					if ($obj->fecha_alta != "0000-00-00")
						$fecha_creation = implode('/',array_reverse(explode('-',$obj->fecha_alta)));
					$textohtml_fin .= "<td>".$fecha_creation."</td>";
					$textohtml_fin .= "<td>".$obj->usuario_modificacion."</td>";
					if ($obj->fecha_modificacion != "0000-00-00")
						$fecha_modificacion  = implode('/',array_reverse(explode('-',$obj->fecha_modificacion)));
					$textohtml_fin .= "<td>".$fecha_modificacion."</td>";
				}
	      
				$textohtml_fin .= "<td><a href=\"operacion_glosario.php?arg_op=modificar&termino=$obj->id_glosario\"><img border=\"0\" src=\"../imagenes/modificar_ico.gif\" ></a>";
				$textohtml_fin .= "&nbsp;&nbsp;&nbsp;&nbsp;";
				$textohtml_fin .= "<a href=\"operacion_glosario.php?arg_op=eliminar&termino=$obj->id_glosario\"><img border=\"0\" src=\"../imagenes/papelera_ico.png\" ></a></td>";
				$textohtml_fin .= "</tr>";
			} else {
				$texto_categoria[$obj->cat_gramatical] = '1';
			}
	   }
	   
	   if ($textohtml_inicio != "") {
		if ($texto_categoria["sustantivo"]!="") {if (!$esprimero) {$textohtml_categoria.= '<br>';}$esprimero=false;$textohtml_categoria.= $sustantivo;}
		if ($texto_categoria["verbo"]!="") {if (!$esprimero) {$textohtml_categoria.= '<br>';}$textohtml_categoria.= $verbo;$esprimero=false;}
		if ($texto_categoria["determinante"]!="") {if (!$esprimero) {$textohtml_categoria.= '<br>';}$textohtml_categoria.= $determinante;$esprimero=false;}
		if ($texto_categoria["adjetivo"]!="") {if (!$esprimero) {$textohtml_categoria.= '<br>';}$textohtml_categoria.= $adjetivo;$esprimero=false;}
		if ($texto_categoria["pronombre"]!="") {if (!$esprimero) {$textohtml_categoria.= '<br>';}$textohtml_categoria.= $pronombre;$esprimero=false;}
		if ($texto_categoria["preposicion"]!="") {if (!$esprimero) {$textohtml_categoria.= '<br>';}$textohtml_categoria.= $preposicion;$esprimero=false;}
		if ($texto_categoria["conjuncion"]!="") {if (!$esprimero) {$textohtml_categoria.= '<br>';}$textohtml_categoria.= $conjuncion;$esprimero=false;}
		if ($texto_categoria["adverbio"]!="") {if (!$esprimero) {$textohtml_categoria.= '<br>';}$textohtml_categoria.= $adverbio;$esprimero=false;}
		if ($texto_categoria["interjeccion"]!="") {if (!$esprimero) {$textohtml_categoria.= '<br>';}$textohtml_categoria.= $interjeccion;$esprimero=false;}

		echo $textohtml_inicio.$textohtml_categoria.$textohtml_fin;
	   }
	   
	   echo "</table>";
	   echo "</td>";
   }
   echo "</tr></table>";

   mysql_close($enlace);
}

//------------------------------------------------------------------------------

function mostrar_termino($termino)
{
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

	/* Consulta a la base de datos */
	include ("../comun/conexion.php");

	$consulta = "SELECT termino,idioma,fecha_alta,usuario_alta,fecha_modificacion,usuario_modificacion,term_compuesto,compuesto1,compuesto2 FROM glosario WHERE id_glosario = '$termino'";
	$res = mysql_query($consulta) or die ($no_leer_term_glosario.$consulta);
	$obj = mysql_fetch_object($res);
	$numeromagico = 2;

	echo "<p align=\"center\">";
	echo "<table width='100%' border='0'>";
	echo "<tr><td colspan='4' align='center'><p id=\"titulo\">".$datos_termino."</p><br></td></tr>";
	echo "<tr><td colspan='4' align='center'><table border='0' width='60%'>";
	echo "<tr><td width='20%'><b>".$term."</b></td><td width='10%' align='left'>".$obj->termino."</td>";
	echo "<td width='20%'><b>".$idiom."</b></td><td width='10%' align='left'>".$_SESSION["idioma"][$obj->idioma]."</td></tr>";
	echo "<td width='20%'><b>".$termino_compuesto."</b>";
	$termino_mostrar = $obj->termino;
	if ($obj->term_compuesto != 1) // El termino no es compuesto y se busca si pertenece a alguno
	{
		$consulta = "SELECT termino, id_glosario FROM glosario WHERE term_compuesto = 1";
		$res = mysql_query($consulta);
		while($obj2 = mysql_fetch_object($res))
		{
			if (strpos($obj2->termino, $termino_mostrar) === false) // No se muestra nada
			{
			}
			else
			{
				echo "<td width='10%' align='left'><a href='operacion_glosario.php?arg_op=mostrar&termino=$obj2->id_glosario' target='_blank'>".$obj2->termino."</a></td></tr>";
			}
		}
	}
	
	if (tienePermisos("buscadorespecial")) {
		$numeromagico = 4;
		$fecha_creation = "";
		$fecha_modificacion = "";
		if ($obj->fecha_alta != "0000-00-00")
			$fecha_creation = implode('/',array_reverse(explode('-',$obj->fecha_alta)));
		if ($obj->fecha_modificacion != "0000-00-00")
			$fecha_modificacion  = implode('/',array_reverse(explode('-',$obj->fecha_modificacion)));
		echo "<tr><td width='20%'><b>".$usuario_alta."</b></td><td>".$obj->usuario_alta."</td><td width='10%'><b>".$fecha_alta."</b></td><td>".$fecha_creation."</td></tr>";
		echo "<tr><td width='20%'><b>".$usuario_modificacion."</b></td><td>".$obj->usuario_modificacion."</td><td width='10%'><b>".$fecha_modificacin."</b></td><td>".$fecha_modificacion."</td></tr>";
	}

	echo "<tr><td colspan='4'>&nbsp;</td></tr>";
	echo "<tr><td align=\"center\" colspan='4'>&nbsp;&nbsp;<input id=\"rae\" type='button'";
	echo "value=' $mostrar_rae ' onclick='window.open(\"http://buscon.rae.es/draeI/SrvltConsulta?TIPO_BUS=3&LEMA=".$obj->termino."\",\"_blank\")'/>&nbsp;&nbsp;<input id=\"webopedia\" type='button'";
	echo "value=' $mostrar_webopedia ' onclick='window.open(\"http://www.webopedia.com/search/".$obj->termino."\",\"_blank\")'/>";
	//echo "<tr><td colspan='4'>&nbsp;</td></tr>";
	// Comprobamos si el termino esta en eurowordnet y mostramos su informacion
	$consulta = "SELECT * FROM eswn_variant WHERE word='$termino_mostrar'";
	$res = mysql_query($consulta);
	if (mysql_num_rows($res) != 0) // el termino esta contenido en eurowordnet por lo que se muestra el enlace al mismo
	{
		echo "&nbsp;&nbsp;<input type='button' id=\"EuroWord\" ";
		echo "value=' $mostrar_eurowordnet ' onclick='window.open(\"operacion_glosario.php?arg_op=mostrar_eurowordnet&termino=$termino_mostrar&idioma=esp\")'/>";
	}
	else // comprobamos si pertenece a eurowordnet ingles
	{
		$consulta = "SELECT offset FROM synsetword WHERE word='$termino_mostrar'";
		$res = mysql_query($consulta) or die (mysql_error());
		if (mysql_num_rows($res) != 0) // el termino pertenece a eurowordnet ingles
		{
			echo "&nbsp;&nbsp;<input type='button' id=\"EuroWord\"";
			echo "value=' $mostrar_eurowordnet ' onclick='window.open(\"operacion_glosario.php?arg_op=mostrar_eurowordnet&termino=$termino_mostrar&idioma=ing\")'/>";
		}
	}
	echo "<br>";
	echo "<tr><td colspan='4' align='center'><p id=\"titulo\">".$acepciones."</p></td></tr>";
	
	// Seccion de acepciones.
	$consulta = "SELECT orden,definicion,cat_gramatical,traduccion,imagen,tamano,nombre,formato FROM acepcion WHERE id_glosario = '$termino' order by cat_gramatical,orden";
	$res2 = mysql_query($consulta) or die ($no_leer_acepciones.$consulta);
	
	if (mysql_num_rows($res2) != 0) {
		$i = 1;
		while($obj2 = mysql_fetch_object($res2)) {
			echo "<tr><td colspan='4' onclick=''>";
			echo "<span class='Info2' onclick='trigger(\"".$termino."_".$obj2->orden."\",\"contextos_out\",\"contextos_in\")'>";
			echo "<table width='100%' border='0'>";
			echo "<tr><td colspan='4' align=\"center\"><b><u>".$sense." ".$i."</u></b></td></tr>";
			echo "<tr><td colspan='4'><b><u>".$informacion_general."</u></b></td></tr>";
			echo "<tr></tr>";
			echo "<tr><td width='15%'><b>".$categoria_gramatical.":</b></td><td width='15%'>";
			switch($obj2->cat_gramatical) {
				case('sustantivo'): echo ''.$sustantivo.''; break;
				case('verbo'): echo ''.$verbo.''; break;
				case('determinante'): echo '<br>'.$determinante.''; break;
				case('adjetivo'): echo ''.$adjetivo.''; break;
				case('pronombre'): echo ''.$pronombre.''; break;
				case('preposicion'): echo ''.$preposicion.''; break;
				case('conjuncion'): echo ''.$conjuncion.''; break;
				case('adverbio'): echo ''.$adverbio.''; break;
				case('interjeccion'): echo ''.$interjeccion.''; break;
			}
			
			//echo "</td><td width='15%'><b>Traducci&oacute;n:</b></td><td width='15'>".$obj2->traduccion."</td></tr>";
			echo "</td><td width='15%'><b>".$traduccion_select.":</b></td>";
			$consulta = "SELECT id_glosario FROM glosario WHERE termino='$obj2->traduccion'";
			$res3 = mysql_query($consulta) or die (mysql_error());
			
			if (mysql_num_rows($res3) != 0)
			{
				$obj3 = mysql_fetch_object($res3);
				echo "<td width='15'><a href='operacion_glosario.php?arg_op=mostrar&termino=$obj3->id_glosario' target='_blank'>".$obj2->traduccion."</a></td></tr>";
			}
			else
			{
				echo "<td width='15'>".$obj2->traduccion."</td></tr>";
			}
			echo "<tr><td width='15%'><b>".$definicion_select."</b></td><td colspan='3'>".$obj2->definicion."</td></tr>";
			if ($obj2->tamano!='') // hay imagen
			{
				//echo "<img src=\"$obj->imagen2\" />";
				//echo "<img src=\"mostrar_imagen.php?termino=".$termino."\">";
				echo "&nbsp;&nbsp;<tr><td align=\"center\"><input type='button' ";
				echo "value=' ".$mostrar_imagen." ' onclick='window.open(\"mostrar_imagen.php?orden=$obj2->orden&termino=$termino\")'/></td></tr>";
			}
			else
			{
				//echo "<a href=http://images.google.com target=_blank> <font size=3>Ver imagen</font></a><br>";
				echo "&nbsp;&nbsp;<tr><td align=\"center\"><input align=\"center\" type='button' ";
				echo "value=' ".$mostrar_imagen." ' onclick='window.open(\"http://images.google.com\",\"_blank\")'/></td></tr>";
			}
			echo "<tr class='contextos_out' id='".$termino."_".$obj2->orden."'><td colspan='4'>";
			echo "<table width='100%'>";
			echo "<tr><td><b><u>".$conexts."</u></b></td></tr>";
			
			// Contextos
			$consulta = "SELECT a.contexto,a.fecha_alta,a.usuario_alta,b.h_title FROM contexto a left join texto b on a.id_texto=b.id_texto WHERE id_glosario = '$termino' and orden=".$obj2->orden;
			$res3 = mysql_query($consulta) or die ($no_leer_contextos.$consulta);
			
			if (mysql_num_rows($res3) != 0){
				echo "<tr><td align='center' width='20%'><b>".$fuente."</b></td>&nbsp;<td align='center' width='50%'><b>".$context."</b></td>&nbsp;";
				if (tienePermisos("buscadorespecial"))
					echo "<td align='center' width='15%'><b>".$usuario_alta."</b></td>&nbsp;<td align='center' width='15%'><b>".$fecha_alta."</b></td>";
				echo "</tr>";
				while($obj3 = mysql_fetch_object($res3)) {
					echo "<tr><td align='center'>".$obj3->h_title."</td><td style='text-align:justify'>".marcarTermino($obj3->contexto, $obj->termino, $obj->idioma)."</td>";
					if (tienePermisos("buscadorespecial")) {
						$fecha_creation = "";
						if ($obj3->fecha_alta != "0000-00-00")
							$fecha_creation = implode('/',array_reverse(explode('-',$obj3->fecha_alta)));
						echo "<td align='center'>".$obj3->usuario_alta."</td>";
						echo "<td align='center'>".$fecha_creation."</td>";
					}
					echo "</tr>";
				}
			} else {
				echo "<tr><td colspan='$numeromagico' align='center'>".$no_encontrar_contextos.".</td></tr>";
			}
			echo "<tr></tr><tr></tr><tr><td colspan='4'><b><u>".$rels_otros_terms."</u></b></td></tr>";
//			echo "</table>";
//			echo "<table width='100%' class='cabecera'>";
			
			// Relaciones con otros terminos (tienen que ir asociadas a la acepcion del termino correspondiente)			
     		$consulta = "select b.termino as termino_1, c.termino as termino_2, d.nombre_tipo as tipo_relacion, a.fecha_alta, a.usuario_alta, a.fecha_modificacion, a.usuario_modificacion, a.particula, a.nota, a.orden_1, a.orden_2 from ";
		    $consulta .= "(select id_termino_1,id_termino_2,id_tipo_relacion,fecha_alta,usuario_alta,fecha_modificacion,usuario_modificacion,particula,nota,orden_1,orden_2 from relacion where id_termino_1=$termino or id_termino_2=$termino";
		    $consulta .= ") a left join glosario b on a.id_termino_1 = b.id_glosario left join glosario c on a.id_termino_2=c.id_glosario left join tipo_relacion d on a.id_tipo_relacion=d.id_tipo_relacion where a.id_tipo_relacion=a.id_tipo_relacion";
		    $res4 = mysql_query($consulta) or die ($no_leer_relaciones.$consulta);
			
			$fila = "listado";
			if (mysql_num_rows($res4) != 0) {
				echo "<tr class='cabecera'><td align='center'  width='20%'><b>".$tipo_texto."</b></td><td align='center'  width='20%'><b>".$rel."</b></td><td align='center'  width='20%'><b>".$nota."</b></td>&nbsp;";
				if (tienePermisos("buscadorespecial"))
					echo "<td align='center' width='10%'><b>".$usuario_alta."</b></td><td align='center' width='10%'><b>".$fecha_alta."</b></td><td align='center' width='10%'><b>".$usuario_modificacion."</b></td>".
					"<td align='center' width='15%'><b>".$fecha_modificacin."</b></td>";
				echo "</tr>";
				
				while($obj4 = mysql_fetch_object($res4)) {
										
					if (($obj4->termino_1 == $termino_mostrar && $obj2->orden == $obj4->orden_1) || ($obj4->termino_2 == $termino_mostrar && $obj2->orden == $obj4->orden_2))
					{
						echo "<tr";
						if ($fila == "listado") {
							echo "class='".$fila."'";
							$fila = "";
						} else {
							$fila = "listado";
						}
						$n = substr($obj4->nota, 0, 10); // cogemos solo los 10 primeros caracteres de la nota para mostrarlos
						
						if ($obj4->termino_1 == $termino_mostrar)
						{
							$consulta = "SELECT id_glosario FROM glosario WHERE termino='$obj4->termino_2'";
							$res5 = mysql_query($consulta) or die (mysql_error());
							$obj5 = mysql_fetch_object($res5);
							
							echo "><td align='center'>".$obj4->tipo_relacion."</td><td align='center'>".$obj4->termino_1."&nbsp;-&nbsp;<i>".$obj4->particula."</i>&nbsp;-&nbsp;<a href='operacion_glosario.php?arg_op=mostrar&termino=$obj5->id_glosario' target='_blank'>".$obj4->termino_2."</a></td><td align='center' title='$obj4->nota'>".$n."</td>"; 
						}
						else
						{
							$consulta = "SELECT id_glosario FROM glosario WHERE termino='$obj4->termino_1'";
							$res5 = mysql_query($consulta) or die (mysql_error());
							$obj5 = mysql_fetch_object($res5);
							
							echo "><td align='center'>".$obj4->tipo_relacion."</td><td align='center'><a href='operacion_glosario.php?arg_op=mostrar&termino=$obj5->id_glosario' target='_blank'>".$obj4->termino_1."</a>&nbsp;-&nbsp;<i>".$obj4->particula."</i>&nbsp;-&nbsp;".$obj4->termino_2."</td><td align='center' title='$obj4->nota'>".$n."</td>"; 
						}
						
						if (tienePermisos("buscadorespecial")) {
							$fecha_creation = "";
							$fecha_modificacion = "";
							if ($obj4->fecha_alta != "0000-00-00")
								$fecha_creation = implode('/',array_reverse(explode('-',$obj4->fecha_alta)));
							if ($obj4->fecha_modificacion != "0000-00-00")
								$fecha_modificacion = implode('/',array_reverse(explode('-',$obj4->fecha_modificacion)));
							echo "<td align='center'>".$obj4->usuario_alta."</td>";
							echo "<td align='center'>".$fecha_creation."</td>";
							echo "<td align='center'>".$obj4->usuario_modificacion."</td>";
							echo "<td align='center'>".$fecha_modificacion."</td>";
						}
						echo "</tr>";
					}
				}
			} else {
				echo "<tr class='cabecera'><td>".$no_relaciones_terminos."</td></tr>";
			}
			echo "</table></td></tr></table></span></td></tr>";
			$i++;
		}
	} else {
		echo "<tr><td colspan='4'><p align=\"center\">".$no_encontrar_acepciones.".</p></td></tr>";
	}
		
	//echo "<tr><td colspan='4'>&nbsp;</td></tr>";
	//echo "<tr><td colspan='4' align='center'><span class=\"titulo titulo_rojo\">Relaciones con otros T&eacute;rminos</span></td></tr>";
       
	//echo "<tr><td colspan='4' align='center'>";
	//echo "<table width='90%' class='relaciones'>";
	
	echo "</table></td></tr></table>";

   mysql_close($enlace);
}

//------------------------------------------------------------------------------

function eliminar_termino($termino)
{
   /* Consulta a la base de datos */
   include ("../comun/conexion.php");
   $consulta= "SELECT termino FROM glosario WHERE id_glosario = $termino";
   $res = mysql_query($consulta) or die("No se pudo eliminar el t&eacute;rmino: ".mysql_error());
   $obj = mysql_fetch_object($res);
   
   $consulta= "DELETE FROM glosario WHERE id_glosario = $termino";
   mysql_query($consulta) or die("No se pudo eliminar el t&eacute;rmino: ".mysql_error());
   
   alta_historico ("eliminar", $_SESSION['username'], "termino", "Identificador: ".$obj->termino);
   
   echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>El t&eacute;rmino <b>$obj->termino</b> ha sido eliminado del glosario correctamente.</p>";
   echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      Aceptar \" onclick=\"document.location='resultado.php?inicial=admin_termino';\"/></p>";

   mysql_close($enlace);
}

//------------------------------------------------------------------------------

function alta_relacion($tipo_relacion, $termino1, $termino2, $part, $nt, $acc, $id_relacion)
{
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
	
   /* Consulta a la base de datos */
   include ("../comun/conexion.php");
      
   $consulta = "SELECT id_tipo_relacion FROM tipo_relacion WHERE (nombre_tipo = '$tipo_relacion')";
   $res = mysql_query($consulta) or die ($modificacion_fallo . mysql_error());
   $obj = mysql_fetch_object($res);
   $id_tipo_relacion = $obj->id_tipo_relacion;
   
	if ($acc == "")
	{
	   /* Comprobar que la relacion no existe */
	   // Se comprueba que la particula tambien sea igual, si es diferente se considera una relacion diferente.
	   $consulta = "SELECT b.termino termino1,c.termino termino2 FROM relacion a left join glosario b on a.id_termino_1=b.id_glosario left join glosario c on a.id_termino_2=c.id_glosario where a.id_tipo_relacion='$id_tipo_relacion' and a.particula='$particula' and ";
	   $consulta .= "((b.termino='$termino1' and c.termino='$termino2') or (b.termino='$termino2' and c.termino='$termino1'))"; 
	   
	   $res = mysql_query($consulta) or die("No se pudieron leer las relaciones: " . mysql_error());  

	   if(mysql_num_rows($res) != 0) //-- La relacion ya existe
	   {
		  $obj = mysql_fetch_object($res);
		  echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$mensaje99." <b>".$obj->termino1."</b> ".$mensaje100." <b>".$obj->termino2."</b> ".$mensaje15."</p>";
		  echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_relacion'\" /></p>";
	   }
	   else  //-- La relacion NO existe
	   {
		  $consulta = "SELECT id_glosario FROM glosario WHERE termino = '$termino1'";
		  $res = mysql_query($consulta) or die("No se pudo acceder al glosario: " . mysql_error());  
		  $obj = mysql_fetch_object($res);
		  $id_glosario1 = $obj->id_glosario;
		  
		  if(mysql_num_rows($res) == 0) //-- El termino 1 NO existe
		  {
			 echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$el_term." <b>$termino1</b> ".$mensaje101."<br>".$mensaje102."</p>";
			 echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_relacion'\" />&nbsp;&nbsp;&nbsp;&nbsp;";
			 echo "<input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$anadir." \" onclick=\"window.open('resultado.php?inicial=admin_termino&termino=$termino1');\" /></p>";
		  }
		  else 
		  {
			 $consulta = "SELECT id_glosario FROM glosario WHERE termino = '$termino2'";
			 $res2 = mysql_query($consulta) or die("No se pudo acceder al glosario: " . mysql_error());
			 
			 if(mysql_num_rows($res2) == 0 && $termino2 != '') //-- El termino 2 NO existe
			 {
				echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$el_term." <b>$termino2</b> ".$mensaje103."<br>".$mensaje102."</p>";
				echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_relacion'\" />&nbsp;&nbsp;&nbsp;&nbsp;";
				echo "<input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$anadir." \" onclick=\"window.open('resultado.php?inicial=admin_termino&termino=$termino2');\" /></p>";
			 }
			 else  //-- Los terminos 1 y 2 SI existen
			 {
				$obj2 = mysql_fetch_object($res2);
				$id_glosario2 = $obj2->id_glosario;
				if($termino2 == '') //-- El termino 2 es vacio
				{
				   $consulta = "INSERT INTO relacion (id_termino_1, id_tipo_relacion, fecha_alta, usuario_alta, particula, nota) VALUES ('".$obj->id_glosario."', '$id_tipo_relacion',now(), '".$_SESSION['username']."', '$part', '$nt')";
				   mysql_query($consulta) or die (mysql_error());
				}
				else  //-- El termino 2 no  es vacio
				{
				   $consulta = "INSERT INTO relacion (id_termino_1, id_termino_2, id_tipo_relacion, fecha_alta, usuario_alta, particula, nota) VALUES ('".$obj->id_glosario."', '".$obj2->id_glosario."', '$id_tipo_relacion',now(), '".$_SESSION['username']."', '$part', '$nt')";
				   mysql_query($consulta) or die (mysql_error());
				}

				$consulta = "SELECT MAX(id_relacion) AS max_relacion FROM relacion";
				$res = mysql_query($consulta);  
				$obj = mysql_fetch_object($res);
				
				alta_historico ("alta", $_SESSION['username'], "relacion", "Identificador:".$obj->max_relacion."<br>T&eacute;rmino 1: ".$termino1."<br>T&eacute;rmino 2: ".$termino2."<br>Tipo: ".$tipo.
				"<br>Particula: ".$particula."<br>Nota: ".$nt);
				
				
				/*echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>".$mensaje104."</p>";
				echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_relacion'\" /></p>";
				*/
				// Se muestran las acepciones de los dos terminos
				echo "<p align=\"center\">Seleccione una acepci&oacute;n de cada t&eacute;rmino si &eacute;stos disponen de ella.</p>";
				
				echo "<form name=\"formulario\" method=\"post\" action=\"operacion_glosario2.php\">";
				echo "<input type=\"hidden\" name=\"arg_op\" value=\"alta_relacion\">";
				echo "<input type=\"hidden\" name=\"termino1\" value=\"$id_glosario1\">";
				echo "<input type=\"hidden\" name=\"termino2\" value=\"$id_glosario2\">";
				echo "<input type=\"hidden\" name=\"id_relacion\" value=\"$obj->max_relacion\">";
				echo "<input type=\"hidden\" name=\"id_tipo_relacion\" value=\"$tipo_relacion\">";
				echo "<input type=\"hidden\" name=\"term1\" value=\"$termino1\">";
				echo "<input type=\"hidden\" name=\"term2\" value=\"$termino2\">";
				echo "<input type=\"hidden\" name=\"particula\" value=\"$particula\">";
				echo "<input type=\"hidden\" name=\"nt\" value=\"$nt\">";
				
				echo "<table border=\"0\" width=\"100%\" cellpadding=\"5\" cellspacing=\"5\">";
				// termino 1
				echo "<tr>";
				echo "<td width=\"50%\" valign=\"top\">";
				echo "<table border=\"0\">";
				echo "<tr>";
				echo "<td align=\"left\"><b><u><font size=\"4\">".$termino1."</font></u></b></td>";
				echo "</tr><tr></tr>";
				
				$consulta = "SELECT orden,definicion,cat_gramatical,traduccion FROM acepcion WHERE id_glosario='$id_glosario1'";
				$res = mysql_query($consulta) or die (mysql_error());
				
				$consulta2 = "SELECT orden,definicion,cat_gramatical,traduccion FROM acepcion WHERE id_glosario='$id_glosario2'";
				$res2 = mysql_query($consulta2);
				
				if (mysql_num_rows($res) == 0) // no hay acepciones para el termino 1
				{
					echo "<tr><td>";
					echo "&nbsp;&nbsp;&nbsp;&nbsp;";
					echo "No hay acepciones para este t&eacute;rmino</td></tr>";
					echo "<input type=\"hidden\" name=\"ac1\" value=\"no acepcion\">";
				}
				else // hay acepciones y por tanto se muestran
				{
					echo "<input type=\"hidden\" name=\"ac1\" value=\"si acepcion\">";
					$i = 1;
					while ($obj3 = mysql_fetch_object($res))
					{	
						echo "<tr><td>";
						echo "<input type=\"checkbox\" name=\"acepcion1".$i."\" value=\"".$obj3->orden."\"/><b><i>Acepeci&oacute;n ".$i."</i></b>";
						echo "<br></td></tr>";
						echo "<tr><td>";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Categor&iacute;a Gramatical:   </b> ".$obj3->cat_gramatical."<br></td></tr>";
						echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Traducci&oacute;n:   </b>".$obj3->traduccion."<br></td></tr>";
						echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Definici&oacute;n:   </b>".$obj3->definicion."<br></td></tr>";
						$i++;
					}
				}
				echo "</table>";
				echo "</td>";
				// termino 2
				echo "<td width=\"50%\" valign=\"top\">";
				echo "<table border=\"0\">";
				echo "<tr>";
				echo "<td align=\"left\"><b><u><font size=\"4\">".$termino2."</font></u></b></td>";
				echo "</tr><tr></tr>";
				if (mysql_num_rows($res2) == 0) // no hay acepciones para el termino 2
				{
					echo "<tr><td>";
					echo "&nbsp;&nbsp;&nbsp;&nbsp;";
					echo "No hay acepciones para este t&eacute;rmino</td></tr>";
					echo "<input type=\"hidden\" name=\"ac2\" value=\"no acepcion\">";
				}
				else // hay acepciones y por tanto se muestran
				{
					echo "<input type=\"hidden\" name=\"ac2\" value=\"si acepcion\">";
					$i = 1;
					while ($obj3 = mysql_fetch_object($res2))
					{	
						echo "<tr><td>";
						echo "<input type=\"checkbox\" acepcion".$i." name=\"acepcion2".$i."\" value=\"".$obj3->orden."\"/><b><i>Acepeci&oacute;n ".$i."</i></b>";
						echo "<br></td></tr>";
						echo "<tr><td>";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Categor&iacute;a Gramatical:   </b> ".$obj3->cat_gramatical."<br></td></tr>";
						echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Traducci&oacute;n:   </b>".$obj3->traduccion."<br></td></tr>";
						echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Definici&oacute;n:   </b>".$obj3->definicion."<br></td></tr>";
						$i++;
					}
				}
				echo "</table>";
				echo "</td>";
				echo "</tr>";
				
				echo "<tr><td align=\"center\" colspan=\"2\">";
				echo "<input type=\"submit\" class=\"boton long_93 boton_aceptar\" value=\"      $boton_aceptar\" />&nbsp;&nbsp;&nbsp;";
				echo "<input type=\"button\" class=\"boton long_93 boton_cancelar\" value=\"      $boton_cancelar\" onclick=\"document.location='resultado.php?inicial=admin_relacion'\" />";
				echo "</td></tr>";
				
				echo "</table>";
				echo "</form>";
			 }
		  }
	  }
   }
    else
	{
		$consulta = "SELECT id_glosario FROM glosario WHERE termino = '$termino1'";
		$res = mysql_query($consulta) or die("No se pudo acceder al glosario: " . mysql_error());  
		$obj = mysql_fetch_object($res);
		$id_glosario1 = $obj->id_glosario;
		
		$consulta = "SELECT id_glosario FROM glosario WHERE termino = '$termino2'";
		$res2 = mysql_query($consulta) or die("No se pudo acceder al glosario: " . mysql_error());
		$obj2 = mysql_fetch_object($res2);
		$id_glosario2 = $obj2->id_glosario;
		  
		echo "<p align=\"center\">Seleccione una acepci&oacute;n de cada t&eacute;rmino si &eacute;stos disponen de ella.</p>";
			
			echo "<form name=\"formulario\" method=\"post\" action=\"operacion_glosario2.php\">";
			echo "<input type=\"hidden\" name=\"arg_op\" value=\"alta_relacion\">";
			echo "<input type=\"hidden\" name=\"termino1\" value=\"$id_glosario1\">";
			echo "<input type=\"hidden\" name=\"termino2\" value=\"$id_glosario2\">";
			echo "<input type=\"hidden\" name=\"id_relacion\" value=\"$id_relacion\">";
			echo "<input type=\"hidden\" name=\"id_tipo_relacion\" value=\"$tipo_relacion\">";
			echo "<input type=\"hidden\" name=\"term1\" value=\"$termino1\">";
			echo "<input type=\"hidden\" name=\"term2\" value=\"$termino2\">";
			echo "<input type=\"hidden\" name=\"particula\" value=\"$particula\">";
			echo "<input type=\"hidden\" name=\"nt\" value=\"$nt\">";
			
			echo "<table border=\"0\" width=\"100%\" cellpadding=\"5\" cellspacing=\"5\">";
			// termino 1
			echo "<tr>";
			echo "<td width=\"50%\" valign=\"top\">";
			echo "<table border=\"0\">";
			echo "<tr>";
			echo "<td align=\"left\"><b><u><font size=\"4\">".$termino1."</font></u></b></td>";
			echo "</tr><tr></tr>";
			
			$consulta = "SELECT orden,definicion,cat_gramatical,traduccion FROM acepcion WHERE id_glosario='$id_glosario1'";
			$res = mysql_query($consulta) or die (mysql_error());
			
			$consulta2 = "SELECT orden,definicion,cat_gramatical,traduccion FROM acepcion WHERE id_glosario='$id_glosario2'";
			$res2 = mysql_query($consulta2);
			
			if (mysql_num_rows($res) == 0) // no hay acepciones para el termino 1
			{
				echo "<tr><td>";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;";
				echo "No hay acepciones para este t&eacute;rmino</td></tr>";
				echo "<input type=\"hidden\" name=\"ac1\" value=\"no acepcion\">";
			}
			else // hay acepciones y por tanto se muestran
			{
				echo "<input type=\"hidden\" name=\"ac1\" value=\"si acepcion\">";
				$i = 1;
				while ($obj3 = mysql_fetch_object($res))
				{	
					echo "<tr><td>";
					echo "<input type=\"checkbox\" name=\"acepcion1".$i."\" value=\"".$obj3->orden."\"/><b><i>Acepeci&oacute;n ".$i."</i></b>";
					echo "<br></td></tr>";
					echo "<tr><td>";
					echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Categor&iacute;a Gramatical:   </b> ".$obj3->cat_gramatical."<br></td></tr>";
					echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Traducci&oacute;n:   </b>".$obj3->traduccion."<br></td></tr>";
					echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Definici&oacute;n:   </b>".$obj3->definicion."<br></td></tr>";
					$i++;
				}
			}
			echo "</table>";
			echo "</td>";
			// termino 2
			echo "<td width=\"50%\" valign=\"top\">";
			echo "<table border=\"0\">";
			echo "<tr>";
			echo "<td align=\"left\"><b><u><font size=\"4\">".$termino2."</font></u></b></td>";
			echo "</tr><tr></tr>";
			if (mysql_num_rows($res2) == 0) // no hay acepciones para el termino 2
			{
				echo "<tr><td>";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;";
				echo "No hay acepciones para este t&eacute;rmino</td></tr>";
				echo "<input type=\"hidden\" name=\"ac2\" value=\"no acepcion\">";
			}
			else // hay acepciones y por tanto se muestran
			{
				echo "<input type=\"hidden\" name=\"ac2\" value=\"si acepcion\">";
				$i = 1;
				while ($obj3 = mysql_fetch_object($res2))
				{	
					echo "<tr><td>";
					echo "<input type=\"checkbox\" acepcion".$i." name=\"acepcion2".$i."\" value=\"".$obj3->orden."\"/><b><i>Acepeci&oacute;n ".$i."</i></b>";
					echo "<br></td></tr>";
					echo "<tr><td>";
					echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Categor&iacute;a Gramatical:   </b> ".$obj3->cat_gramatical."<br></td></tr>";
					echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Traducci&oacute;n:   </b>".$obj3->traduccion."<br></td></tr>";
					echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Definici&oacute;n:   </b>".$obj3->definicion."<br></td></tr>";
					$i++;
				}
			}
			echo "</table>";
			echo "</td>";
			echo "</tr>";
			
			echo "<tr><td align=\"center\" colspan=\"2\">";
			echo "<input type=\"submit\" class=\"boton long_93 boton_aceptar\" value=\"      $boton_aceptar\" />&nbsp;&nbsp;&nbsp;";
			echo "<input type=\"button\" class=\"boton long_93 boton_cancelar\" value=\"      $boton_cancelar\" onclick=\"document.location='resultado.php?inicial=admin_relacion'\" />";
			echo "</td></tr>";
			
			echo "</table>";
			echo "</form>";
	}

   mysql_close($enlace);   
}

//------------------------------------------------------------------------------

function listar_relaciones($tipo_relacion, $termino1, $termino2, $part, $nt)
{
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

	/* Consulta a la base de datos */
   include ("../comun/conexion.php");
   
   if ($termino1 != "")
   {
	   $consulta = "SELECT id_glosario FROM glosario WHERE termino='$termino1'";
	   $res = mysql_query($consulta);
	   $obj =  mysql_fetch_object($res);
	   $term1 = $obj->id_glosario;
   }
	
   if ($termino2 != "")
   {
	   $consulta = "SELECT id_glosario FROM glosario WHERE termino='$termino2'";
	   $res = mysql_query($consulta);
	   $obj =  mysql_fetch_object($res);
	   $term2 = $obj->id_glosario;
   }
   
      // Mostramos las relaciones dependiendo de los campos que se hayan rellenado
   if ($tipo_relacion != '' && $termino1 == '' && $termino2 == '' && $part == '') // Mostramos solo por el tipo de relacion
   {
		$consulta = "SELECT id_tipo_relacion FROM tipo_relacion WHERE nombre_tipo ='$tipo_relacion'";
	    $res = mysql_query($consulta);
	    $obj = mysql_fetch_object($res) or die (mysql_error());
	    $id_rel = $obj->id_tipo_relacion;
		
		$consulta = "SELECT a.id_tipo_relacion,a.id_termino_1,a.id_termino_2,a.usuario_alta,a.fecha_alta,a.usuario_modificacion,a.fecha_modificacion,a.id_relacion,a.particula,a.nota,b.nombre_tipo FROM relacion a, tipo_relacion b WHERE a.id_tipo_relacion=b.id_tipo_relacion AND (a.id_tipo_relacion = '$id_rel')";
		$res = mysql_query($consulta);
   }
   else if ($tipo_relacion != '' && $termino1 != '' && $termino2 == '' && $part == '') // Mostramos por el tipo de relacion y el termino1
   {
		$consulta = "SELECT id_tipo_relacion FROM tipo_relacion WHERE nombre_tipo ='$tipo_relacion'";
		$res = mysql_query($consulta);
		$obj = mysql_fetch_object($res) or die (mysql_error());
		$id_rel = $obj->id_tipo_relacion;
   
		$consulta = "SELECT a.id_tipo_relacion,a.id_termino_1,a.id_termino_2,a.usuario_alta,a.fecha_alta,a.usuario_modificacion,a.fecha_modificacion,a.id_relacion,a.particula,a.nota,b.nombre_tipo FROM relacion a, tipo_relacion b WHERE a.id_tipo_relacion=b.id_tipo_relacion AND (a.id_tipo_relacion = '$id_rel')  AND (a.id_termino_1 = '$term1')";
		$res = mysql_query($consulta);
   }
   else if ($tipo_relacion != '' && $termino1 != '' && $termino2 != '' && $part == '') // Mostramos por el tipo de relacion, el termino1 y el termino2
   {
		$consulta = "SELECT id_tipo_relacion FROM tipo_relacion WHERE nombre_tipo ='$tipo_relacion'";
		$res = mysql_query($consulta);
		$obj = mysql_fetch_object($res) or die (mysql_error());
		$id_rel = $obj->id_tipo_relacion;
		
		$consulta = "SELECT a.id_tipo_relacion,a.id_termino_1,a.id_termino_2,a.usuario_alta,a.fecha_alta,a.usuario_modificacion,a.fecha_modificacion,a.id_relacion,a.particula,a.nota,b.nombre_tipo FROM relacion a, tipo_relacion b WHERE a.id_tipo_relacion=b.id_tipo_relacion AND (a.id_tipo_relacion = '$id_rel') AND (a.id_termino_1 = '$term1') AND (a.id_termino_2 = '$term2')";
		$res = mysql_query($consulta);
   }
   else if ($tipo_relacion != '' && $termino1 != '' && $termino2 != '' && $part != '') // Mostramos por la relacion completa
   {
	   $consulta = "SELECT id_tipo_relacion FROM tipo_relacion WHERE nombre_tipo ='$tipo_relacion'";
	   $res = mysql_query($consulta);
	   $obj = mysql_fetch_object($res) or die (mysql_error());
	   $id_rel = $obj->id_tipo_relacion;
   
		$consulta = "SELECT a.id_tipo_relacion,a.id_termino_1,a.id_termino_2,a.usuario_alta,a.fecha_alta,a.usuario_modificacion,a.fecha_modificacion,a.id_relacion,a.particula,a.nota,b.nombre_tipo FROM relacion a, tipo_relacion b WHERE a.id_tipo_relacion=b.id_tipo_relacion AND (a.id_tipo_relacion = '$id_rel') AND (a.id_termino_1 = '$term1') AND (a.id_termino_2 = '$term2') AND (a.particula = '$part')";
		$res = mysql_query($consulta);
   }
   else if ($tipo_relacion != '' && $termino1 == '' && $termino2 != '' && $part == '') // Mostramos por la relacion completa
   {
		$consulta = "SELECT id_tipo_relacion FROM tipo_relacion WHERE nombre_tipo ='$tipo_relacion'";
	   $res = mysql_query($consulta);
	   $obj = mysql_fetch_object($res) or die (mysql_error());
	   $id_rel = $obj->id_tipo_relacion;
   
		$consulta = "SELECT a.id_tipo_relacion,a.id_termino_1,a.id_termino_2,a.usuario_alta,a.fecha_alta,a.usuario_modificacion,a.fecha_modificacion,a.id_relacion,a.particula,a.nota,b.nombre_tipo FROM relacion a, tipo_relacion b WHERE a.id_tipo_relacion=b.id_tipo_relacion AND (a.id_tipo_relacion = '$id_rel') AND (a.id_termino_2 = '$term2') AND (a.particula = '$part')";
		$res = mysql_query($consulta);
   }
   else if ($tipo_relacion != '' && $termino1 == '' && $termino2 == '' && $part != '') // Mostramos por la relacion completa
   {
	   $consulta = "SELECT id_tipo_relacion FROM tipo_relacion WHERE nombre_tipo ='$tipo_relacion'";
	   $res = mysql_query($consulta);
	   $obj = mysql_fetch_object($res) or die (mysql_error());
	   $id_rel = $obj->id_tipo_relacion;
	   
		$consulta = "SELECT a.id_tipo_relacion,a.id_termino_1,a.id_termino_2,a.usuario_alta,a.fecha_alta,a.usuario_modificacion,a.fecha_modificacion,a.id_relacion,a.particula,a.nota,b.nombre_tipo FROM relacion a, tipo_relacion b WHERE a.id_tipo_relacion=b.id_tipo_relacion AND (a.id_tipo_relacion = '$id_rel') AND (a.particula = '$part')";
		$res = mysql_query($consulta);
   }
   else if ($tipo_relacion != '' && $termino1 == '' && $termino2 != '' && $part != '') // Mostramos por la relacion completa
   {
		$consulta = "SELECT id_tipo_relacion FROM tipo_relacion WHERE nombre_tipo ='$tipo_relacion'";
	   $res = mysql_query($consulta);
	   $obj = mysql_fetch_object($res) or die (mysql_error());
	   $id_rel = $obj->id_tipo_relacion;
   
		$consulta = "SELECT a.id_tipo_relacion,a.id_termino_1,a.id_termino_2,a.usuario_alta,a.fecha_alta,a.usuario_modificacion,a.fecha_modificacion,a.id_relacion,a.particula,a.nota,b.nombre_tipo FROM relacion a, tipo_relacion b WHERE a.id_tipo_relacion=b.id_tipo_relacion AND (a.id_tipo_relacion = '$id_rel') AND (a.id_termino_2 = '$term2')";
		$res = mysql_query($consulta);
   }    
   else if ($tipo_relacion == '' && $termino1 != '' && $termino2 == '' && $part == '') // Mostramos por la relacion completa
   {
		$consulta = "SELECT a.id_tipo_relacion,a.id_termino_1,a.id_termino_2,a.usuario_alta,a.fecha_alta,a.usuario_modificacion,a.fecha_modificacion,a.id_relacion,a.particula,a.nota,b.nombre_tipo FROM relacion a, tipo_relacion b WHERE a.id_tipo_relacion=b.id_tipo_relacion AND (a.id_termino_1 = '$term1')";
		$res = mysql_query($consulta);
   }
   else if ($tipo_relacion == '' && $termino1 != '' && $termino2 != '' && $part == '') // Mostramos por el termino1 y termino2
   {
		$consulta = "SELECT a.id_tipo_relacion,a.id_termino_1,a.id_termino_2,a.usuario_alta,a.fecha_alta,a.usuario_modificacion,a.fecha_modificacion,a.id_relacion,a.particula,a.nota,b.nombre_tipo FROM relacion a, tipo_relacion b WHERE a.id_tipo_relacion=b.id_tipo_relacion AND (a.id_termino_1 = '$term1') AND (a.id_termino_2 = '$term2')";
		$res = mysql_query($consulta);
   } 
   else if ($tipo_relacion == '' && $termino1 != '' && $termino2 != '' && $part != '') // Mostramos por el termino1, termino2 y la particula
   {
		$consulta = "SELECT a.id_tipo_relacion,a.id_termino_1,a.id_termino_2,a.usuario_alta,a.fecha_alta,a.usuario_modificacion,a.fecha_modificacion,a.id_relacion,a.particula,a.nota,b.nombre_tipo FROM relacion a, tipo_relacion b WHERE a.id_tipo_relacion=b.id_tipo_relacion AND (a.id_termino_1 = '$term1') AND (a.id_termino_2 = '$term2') AND (a.particula = '$part')";
		$res = mysql_query($consulta);
   }
   else if ($tipo_relacion == '' && $termino1 != '' && $termino2 == '' && $part != '') // Mostramos por el termino1, termino2 y la particula
   {
		$consulta = "SELECT a.id_tipo_relacion,a.id_termino_1,a.id_termino_2,a.usuario_alta,a.fecha_alta,a.usuario_modificacion,a.fecha_modificacion,a.id_relacion,a.particula,a.nota,b.nombre_tipo FROM relacion a, tipo_relacion b WHERE a.id_tipo_relacion=b.id_tipo_relacion AND (a.id_termino_1 = '$term1') AND (a.particula = '$part')";
		$res = mysql_query($consulta);
   }
   else if ($tipo_relacion == '' && $termino1 == '' && $termino2 != '' && $part == '') // Mostramos por el termino2
   {
		$consulta = "SELECT a.id_tipo_relacion,a.id_termino_1,a.id_termino_2,a.usuario_alta,a.fecha_alta,a.usuario_modificacion,a.fecha_modificacion,a.id_relacion,a.particula,a.nota,b.nombre_tipo FROM relacion a, tipo_relacion b WHERE a.id_tipo_relacion=b.id_tipo_relacion AND (a.id_termino_2 = '$term2')";
		$res = mysql_query($consulta);
   }
   else if ($tipo_relacion == '' && $termino1 == '' && $termino2 != '' && $part != '') // Mostramos por el termino2 y la particula
   {
		$consulta = "SELECT a.id_tipo_relacion,a.id_termino_1,a.id_termino_2,a.usuario_alta,a.fecha_alta,a.usuario_modificacion,a.fecha_modificacion,a.id_relacion,a.particula,a.nota,b.nombre_tipo FROM relacion a, tipo_relacion b WHERE a.id_tipo_relacion=b.id_tipo_relacion AND (a.id_termino_2 = '$term2') AND (a.particula = '$part')";
		$res = mysql_query($consulta);
   }   
   else if ($tipo_relacion == '' && $termino1 == '' && $termino2 == '' && $part != '') // Mostramos por la particula
   {
		$consulta = "SELECT a.id_tipo_relacion,a.id_termino_1,a.id_termino_2,a.usuario_alta,a.fecha_alta,a.usuario_modificacion,a.fecha_modificacion,a.id_relacion,a.particula,a.nota,b.nombre_tipo FROM relacion a, tipo_relacion b WHERE a.id_tipo_relacion=b.id_tipo_relacion AND (a.particula = '$part')";
		$res = mysql_query($consulta);
   }

   //$consulta = "SELECT a.id_tipo_relacion,a.id_termino_1,a.prep_esp,a.prep_ing,a.id_termino_2,a.usuario_alta,a.fecha_alta,a.usuario_modificacion,a.fecha_modificacion,a.id_relacion,b.nombre_tipo FROM relacion a, tipo_relacion b WHERE a.id_tipo_relacion=b.id_tipo_relacion AND (a.id_termino_1 = '$termino' OR a.id_termino_2 = '$termino')";
   //$res = mysql_query($consulta);

   echo "<table align=\"center\" border=\"0\" cellpadding=\"4\" cellspacing=\"1\" bgcolor=\"#CC0000\">";
   echo "   <tr bgcolor=#D8D9A4>";       
   echo "      <td align=\"center\"><b>".$tipo."</b></td><td align=\"center\"><b>".$term." 1</b></td>";
   echo "      <td align=\"center\"><b>".$particula."</b></td><td align=\"center\"><b>".$term." 2</b></td>";
   echo "      <td align=\"center\"><b>".$nota."</b>";
   

   if (tienePermisos("buscadorespecial")) {
      echo "<td align=\"center\"><b>".$usuario_alta."</b></td>";
      echo "<td align=\"center\"><b>".$fecha_alta."</b></td>";
      echo "<td align=\"center\"><b>".$usuario_modificacion."</b></td>";
      echo "<td align=\"center\"><b>".$fecha_modificacin."</b></td>";
   }
   echo "   <td>&nbsp;</td></tr>";

   $num = 0;

   while($obj = mysql_fetch_object($res))
   {
      if(($num % 2) == 0)
	  {
         echo "<tr bgcolor=#FFFFFF>";
	  }
	  else
	  {
	     echo "<tr bgcolor=#FFFF99>";
	  }
	  
	  $consulta = "SELECT termino FROM glosario where id_glosario='$obj->id_termino_1'";
	  $res2 = mysql_query($consulta) or die (mysql_error());
	  $obj2 = mysql_fetch_object($res2);
      
      echo "<td>".$obj->nombre_tipo."</td><td>".$obj2->termino."</td>";
	  echo "<td>".$obj->particula."</td>";
	  
	  $consulta = "SELECT termino FROM glosario where id_glosario='$obj->id_termino_2'";
	  $res2 = mysql_query($consulta) or die (mysql_error());
	  $obj2 = mysql_fetch_object($res2);
	  
	  echo "<td>".$obj2->termino."</td>";
	  echo "<td>".$obj->nota."</td>";
	  
	  $fecha_alta = "";
	  $fecha_modificacion = "";
	  if ($obj->fecha_alta != "0000-00-00")
	     $fecha_alta = implode('/',array_reverse(explode('-',$obj->fecha_alta)));
	  if ($obj->fecha_modificacion != "0000-00-00") 
	     $fecha_modificacion = implode('/',array_reverse(explode('-',$obj->fecha_modificacion)));
	 
	  if (tienePermisos("buscadorespecial")) {
	    echo "<td align=\"center\">".$obj->usuario_alta."</b></td>";
	    echo "<td align=\"center\">".$fecha_alta."</b></td>";
	    echo "<td align=\"center\">".$obj->usuario_modificacion."</td>";
	    echo "<td align=\"center\">".$fecha_modificacion."</td>";
	 }

	  echo "<td><a href=\"operacion_glosario.php?arg_op=modificar_relacion&relacion=$obj->id_relacion\"><img border=\"0\" src=\"../imagenes/modificar_ico.gif\" ></a>";
	  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
	  echo "<a href=\"operacion_glosario.php?arg_op=eliminar_relacion&relacion=$obj->id_relacion\"><img border=\"0\" src=\"../imagenes/papelera_ico.png\" ></a></td>";
	  echo "</tr>";	  
   }
   echo "</table>";
   echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_relacion';\"/></p>";
   

   mysql_close($enlace);
}

//------------------------------------------------------------------------------


function mostrar_relacion($id_relacion)
{
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

   /* Consulta a la base de datos */
   include ("../comun/conexion.php");

   $consulta = "SELECT a.id_tipo_relacion, a.particula,a.nota,b.termino id_termino_1,c.termino id_termino_2, a.usuario_alta,a.fecha_alta,a.usuario_modificacion,a.fecha_modificacion, d.nombre_tipo FROM relacion a left join tipo_relacion d on a.id_tipo_relacion=d.id_tipo_relacion ";
   $consulta .= "left join glosario b on a.id_termino_1=b.id_glosario left join glosario c on a.id_termino_2=c.id_glosario where a.id_relacion='$id_relacion'";
   $res = mysql_query($consulta);

   $obj = mysql_fetch_object($res);

   /*if($obj->prep_esp != '')
   {
      $prep = $obj->prep_esp;
   }
   else
   {
      if($obj->prep_ing != '')
	  {
	     $prep = $obj->prep_ing;
	  }
	  else
	  {
	     $prep = '-';
	  }
   }*/
   
   $consulta = "SELECT nombre_tipo FROM tipo_relacion WHERE id_tipo_relacion='$obj->id_tipo_relacion'";
   $res = mysql_query($consulta);
   $obj2 = mysql_fetch_object($res);
   
   echo "<table align=\"center\" style=\"border: 1 dashed #CC0000\" bgcolor=\"#FFFF99\" cellspacing=\"5\">";
   echo "<tr><td><b>".$tipo."</b></td><td>$obj2->nombre_tipo</td></tr>";
   echo "<tr><td><b>".$term." 1:</b></td><td>$obj->id_termino_1</td></tr>";
   echo "<tr><td><b>".$particula.":</b></td><td>$obj->particula</td></tr>";
   echo "<tr><td><b>".$term." 2:</b></td><td>$obj->id_termino_2</td></tr>";
   echo "<tr><td><b>".$nota." :</b></td><td>$obj->nota</td></tr>";
   echo "</table>";

   mysql_close($enlace);
}

//------------------------------------------------------------------------------

function eliminar_relacion($id_relacion)
{
   /* Consulta a la base de datos */
   include ("../comun/conexion.php");
   
   $consulta= "DELETE FROM relacion WHERE id_relacion = '$id_relacion'";
   $res = mysql_query($consulta);

   alta_historico ("eliminar", $_SESSION['username'], "relacion", "Identificador: ".$id_relacion);
      
   echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>La relaci&oacute;n ha sido eliminada del glosario correctamente.</p>";
   echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      Aceptar \" onclick=\"document.location='resultado.php?inicial=admin_relacion'\" /></p>";

   mysql_close($enlace);
}

//------------------------------------------------------------------------------

function modificar_relacion($id_relacion, $ant_tipo, $tipo, $ant_termino1, $termino1, $ant_termino2, $termino2, $part, $nt)
{
   $tipo_relacion = $tipo;
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
	
	/* Consulta a la base de datos */
   include ("../comun/conexion.php");   

   if( ($ant_tipo == $tipo_relacion) && ($termino1 == $ant_termino1) && ($termino2 == $ant_termino2) )
   {  
	  //-- Solo ha cambiado la particula o la relacion no ha sufrido cambios
      $consulta = "UPDATE relacion SET particula='$part', nota='$nt', fecha_modificacion=now(),usuario_modificacion='".$_SESSION['username']."' WHERE id_relacion='$id_relacion'";   
      $res = mysql_query($consulta);

   	  alta_historico ("modificar", $_SESSION['username'], "relacion", "Identificador: ".$id_relacion."<br>T&eacute;rmino 1: ".$termino1."<br>T&eacute;rmino 2: ".$termino2."<br>Tipo: ".$tipo_relacion."<br>Part&iacute;cula: ".$part.
   	  "<br>Nota: ".$nt);
   	  
   	  echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>".$mensaje106."</p>";
      echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_relacion'\" /></p>";
   }
   else //-- Ha cambiado el tipo o alguno de los 2 terminos
   {
      $consulta_term1 = "SELECT id_glosario FROM glosario WHERE termino = '$termino1'";
      $res_term1 = mysql_query($consulta_term1);
      $consulta_term2 = "SELECT id_glosario FROM glosario WHERE termino = '$termino2'";
      $res_term2 = mysql_query($consulta_term2);
      $obj_term1 = mysql_fetch_object($res_term1);
      $obj_term2 = mysql_fetch_object($res_term2);
      
 	  if( (mysql_num_rows($res_term1) == 0) || 
		  (mysql_num_rows($res_term2) == 0 && $termino2 != '') )  //-- Alguno de los terminos no existe
	  {
         echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$mensaje107."</p>";
		 echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_relacion'\" /></p>";         
      }
	  else
	  {
		 if($termino2 == '')
		 {
		    $consulta = "SELECT id_tipo_relacion FROM relacion WHERE (id_tipo_relacion='$tipo_relacion' AND id_termino_1=".$obj_term1->id_glosario." AND id_termino_2=NULL)";
		 }
		 else
		 {
            $consulta = "SELECT id_tipo_relacion FROM relacion WHERE (id_tipo_relacion='$tipo_relacion' AND id_termino_1=".$obj_term1->id_glosario." AND id_termino_2=".$obj_term2->id_glosario.")";
		 }
         $res = mysql_query($consulta) or die ($consulta);
		 
	     if(mysql_num_rows($res) != 0) //-- La relacion ya existe
	     {
	        echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$mensaje108." <b>$tipo_relacion</b> ".$mensaje109." <b>$termino1</b> y <b>$termino2</b>".$mensjae71."<br>".$mensaje110."</p>";
		    echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_relacion'\" /></p>";
         }
	     else  //-- La relacion no existe, se actualizan los cambios
	     {
	        if($termino2 == '')
			{
			   $consulta = "UPDATE relacion SET id_tipo_relacion='$tipo_relacion', id_termino_1=".$obj_term1->id_glosario.", id_termino_2=NULL, particula='$particula', nota='$nt', fecha_modificacion=now(),usuario_modificacion='".$_SESSION['username']."' WHERE id_relacion='$id_relacion'";   
			}
			else
			{
			   $consulta = "UPDATE relacion SET id_tipo_relacion='$tipo_relacion', id_termino_1=".$obj_term1->id_glosario.", id_termino_2=".$obj_term2->id_glosario.", particula='$part', nota='$nt', fecha_modificacion=now(),usuario_modificacion='".$_SESSION['username']."' WHERE id_relacion='$id_relacion'";   
		    }
            $res = mysql_query($consulta) or die ("No se pudo actualizar la relaci&oacute;n");

   	  		alta_historico ("modificar", $_SESSION['username'], "relacion", "Identificador: ".$id_relacion."<br>T&eacute;rmino 1: ".$termino1."<br>T&eacute;rmino 2: ".$termino2."<br>Tipo: ".$tipo_relacion."<br>Part&iacute;cula: ".$particula.
   	  		"<br>Nota: ".$nt);
            
   	  		echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>".$mensaje106."</p>";
            echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_relacion'\" /></p>";
         }
	  }
   }

   mysql_close($enlace);
}

//------------------------------------------------------------------------------

function listar_tipos_relacion()
{
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

   /* Consulta a la base de datos */
   include ("../comun/conexion.php");

   $consulta = "SELECT id_tipo_relacion,nombre_tipo,descripcion FROM tipo_relacion WHERE tipo_rel='colocacion'";
   $res = mysql_query($consulta);

   echo "<table align=\"center\" border=\"0\" cellpadding=\"4\" cellspacing=\"1\" bgcolor=\"#CC0000\">
           <tr bgcolor=#D8D9A4>       
              <td align=\"center\"><b>".$tipo_rel."</b></td><td align=\"center\"><b>".$descripcion."</b></td>
			  <td></td>
           </tr>";

   $num = 0;

   while($obj = mysql_fetch_object($res))
   {
      if(($num % 2) == 0)
	  {
         echo "<tr bgcolor=#FFFFFF>";
	  }
	  else
	  {
	     echo "<tr bgcolor=#FFFF99>";
	  }

      echo "<td><b>".$obj->nombre_tipo."</b></td><td>".$obj->descripcion."</td>";
	  echo "<td><a href=\"operacion_glosario.php?arg_op=modificar_tipo_relac&tipo_relacion=$obj->id_tipo_relacion\"><img border=\"0\" src=\"../imagenes/modificar_ico.gif\" ></a>";
	  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
	  echo "<a href=\"operacion_glosario.php?arg_op=eliminar_tipo_relac&tipo_relacion=$obj->id_tipo_relacion\"><img border=\"0\" src=\"../imagenes/papelera_ico.png\" ></a></td>";
	  echo "</tr>";
   }
   echo "</table>";

   mysql_close($enlace);
}

//------------------------------------------------------------------------------

function alta_tipo_relacion($id_tipo_relacion, $nombre_tipo, $descripcion_relacion, $id_tipo_relacion_max)
{
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
	
   /* Consulta a la base de datos */
   include ("../comun/conexion.php");
    
   //$consulta= "SELECT id_tipo_relacion FROM tipo_relacion WHERE id_tipo_relacion = '$id_tipo_relacion' ";
   $consulta = "SELECT nombre_tipo FROM tipo_relacion WHERE nombre_tipo='$nombre_tipo'";
   $res = mysql_query($consulta) or die (mysql_error());

   if (mysql_num_rows($res) != 0)  //-- El tipo de relacion ya existe
   {
      echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$mensaje111."  <b>$nombre_tipo</b> ".$mensjae71."<br>".$mensaje112."</p>";
	  echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_tipo_relacion'\" /></p>";
   }
   else
   {
      $consulta = "INSERT INTO tipo_relacion (id_tipo_relacion, nombre_tipo, descripcion, indice, tipo_rel) VALUES ('$id_tipo_relacion', '$nombre_tipo', '$descripcion_relacion', '$id_tipo_relacion_max', 'colocacion')";
	  $res = mysql_query($consulta); 

   	  alta_historico ("alta", $_SESSION['username'], "tipo relacion", "Identificador: ".$id_tipo_relacion."<br>Nombre: ".$nombre_tipo."<br>Descripcion: ".$descripcion_relacion);
	  
	  echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>".$mensaje111." <b>$nombre_tipo</b> ".$mensaje113."</p>";
      echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_tipo_relacion'\" /></p>";
   } 

   mysql_close($enlace); 
}

//------------------------------------------------------------------------------

function eliminar_tipo_relacion($id_tipo_relacion)
{
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
	
   /* Consulta a la base de datos */
   include ("../comun/conexion.php");
      
   $consulta= "DELETE FROM tipo_relacion WHERE id_tipo_relacion = '$id_tipo_relacion'";
   $res = mysql_query($consulta); 

   alta_historico ("eliminar", $_SESSION['username'], "tipo relacion", "Identificador: ".$id_tipo_relacion);
   
   echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>".$mensaje111." <b>$id_tipo_relacion</b> ".$mensaje96."</p>";
    echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_tipo_relacion'\" /></p>";

   mysql_close($enlace);
}

//------------------------------------------------------------------------------

function modificar_tipo_relacion($ant_id_tipo_relacion, $id_tipo_relacion, $nombre_tipo)
{
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

   /* Consulta a la base de datos */
   include ("../comun/conexion.php");

   if($id_tipo_relacion == $ant_id_tipo_relacion)
   {
      $consulta = "UPDATE tipo_relacion SET nombre_tipo='$nombre_tipo', descripcion='$descripcion_relacion' WHERE id_tipo_relacion='$id_tipo_relacion'"; 
      $res = mysql_query($consulta);

   	  alta_historico ("modificar", $_SESSION['username'], "tipo relacion", "Identificador: ".$id_tipo_relacion."<br>Nombre: ".$nombre_tipo);
      
      echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>".$mensaje111." <b>$id_tipo_relacion</b> ".$mensaje97."</p>";
      echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_tipo_relacion'\" /></p>";
   }
   else
   {
      $consulta= "SELECT id_tipo_relacion FROM tipo_relacion WHERE id_tipo_relacion = '$id_tipo_relacion' ";
      $res = mysql_query($consulta);  
	  
      if (mysql_num_rows($res) != 0) //-- El tipo de relacion ya existe
      {
	     echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$mensaje111."  <b>$id_tipo_relacion</b> ".$mensjae71."<br>No se pudo modificar el tipo de relaci&oacute;n.</p>";
	     echo "<p align=\"center\"<input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_tipo_relacion'\" /></p>"; 
	  }
	  else
	  {
	     $consulta = "UPDATE tipo_relacion SET id_tipo_relacion='$id_tipo_relacion', nombre_tipo='$nombre_tipo' WHERE id_tipo_relacion='$ant_id_tipo_relacion'"; 
         $res = mysql_query($consulta);

   	  	 alta_historico ("alta", $_SESSION['username'], "tipo relacion", "Identificador antiguo: ".$ant_id_tipo_relacion."Identificador nuevo: ".$id_tipo_relacion."<br>Nombre: ".$nombre_tipo);
         
         echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>".$mensaje111." <b>$id_tipo_relacion</b> ".$mensaje97."</p>";
         echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_tipo_relacion'\" /></p>";
	  }
   }

   mysql_close($enlace);    
}



//------------------------------------------------------------------------------

function eliminar_tilde($c)
{
   switch($c)
   {
      case("á"): $res = 'a'; break;
      case("é"): $res = 'e'; break;
      case("í"): $res = 'i'; break;
      case("ó"): $res = 'o'; break;
      case("ú"): $res = 'u'; break;
	  default: $res = $c;  break;
   }
   return $res;
}

//------------------------------------------------------------------------------

function esCaracter($c)
{
   if((ord($c) > 47 && ord($c) < 58 ) ||	 
       (ord($c) > 64 && ord($c) < 91 ) ||	
	   (ord($c) > 96 && ord($c) < 123 ) ||	
	   (ord($c) > 191 && ord($c) < 215 ) ||	
	   (ord($c) > 216 && ord($c) < 222 ) ||	
	   (ord($c) > 223 && ord($c) < 247 ) ||	
	   (ord($c) > 248 && ord($c) < 254 ) 
		)
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

//------------------------------------------------------------------------------

function marcarTermino($contexto, $termino, $idioma)
{
   $vector_contexto = extraerElementosContexto($contexto);

   $resultado = '';

   $term_mayusc = convertirMayusculas($termino);
   $term_mayusc_pl = convertirMayusculas(eliminarTildes(crearPlural($termino, $idioma)));

   foreach($vector_contexto as $x)
   {
	  $x_mayusc = convertirMayusculas($x);

      if($x_mayusc == $term_mayusc || $x_mayusc == $term_mayusc_pl)
	  {
         $resultado .= "<b><u>";
		 $resultado .= $x;
		 $resultado .= "</u></b>";
	  }
	  else
	  {
         $resultado .= $x;
	  }
   }

   return $resultado;
}

//------------------------------------------------------------------------------

function extraerElementosContexto($contexto)
{
   $i = 0;
   $es_caracter = 0;
   $encontrada = 0;
   $palabra = "";

   for($j = 0; $j < strlen($contexto); $j++)
   {
      if( esCaracter($contexto[$j]) ) // Se ha leido un caracter
	  {
	     $palabra .= $contexto[$j];
		 $es_caracter = 1;
      }
	  else                    // Se la leido un "no caracter"
	  {
         if($es_caracter) // Tenemos una palabra
		 {
            $resultado[$i] = $palabra;
			$i++;	   
			$palabra = "";
			$es_caracter = 0;
		 }
		 $resultado[$i] = $contexto[$j];
		 $i++;
      }
   }
   $i++;

   $resultado[$i] = $palabra;

   return $resultado;
}

//------------------------------------------------------------------------------

function convertirMayusculas($palabra)
{
	$longitud = strlen($palabra);
	$p = "";

	for($i = 0; $i < $longitud; $i++)
	{
		$c = $palabra[$i];

		if( ord($c) > 96 && ord($c) < 123 )
		{
	       $p .= strtoupper($c);
		}
		else
		{
		   switch($c)
		   {
		      case("á"): $p .= 'Á'; break;
		      case("é"): $p .= 'É'; break;
		      case("í"): $p .= 'Í'; break;
		      case("ó"): $p .= 'Ó'; break;
		      case("ú"): $p .= 'Ú'; break;
		      case("ü"): $p .= 'Ü'; break;
		      case("ñ"): $p .= 'Ñ'; break;
		      default: $p .= $c; break;
		   }
		}
	}

	return $p;
}


//------------------------------------------------------------------------------

function convertirMinusculas($palabra)
{
	$longitud = strlen($palabra);
	$p = "";

	for($i = 0; $i < $longitud; $i++)
	{
		$c = $palabra[$i];

		if( ord($c) > 64 && ord($c) < 91 )
		{
	       $p .= strtolower($c);
		}
		else
		{
		   switch($c)
		   {
		      case("Á"): $p .= 'á'; break;
		      case("É"): $p .= 'é'; break;
		      case("Í"): $p .= 'í'; break;
		      case("Ó"): $p .= 'ó'; break;
		      case("Ú"): $p .= 'ú'; break;
		      case("Ü"): $p .= 'ü'; break;
		      case("Ñ"): $p .= 'ñ'; break;
		      default: $p .= $c; break;
		   }
		}
	}

	return $p;
	/*for($i=0; $i<512; $i++)
    {
		echo $i.": ".chr($i)." ++ ";
	}
	return "";*/
}

//------------------------------------------------------------------------------

function crearPlural($term, $idioma)

/*-------------------------------------------------------------------------------------------------
  Funcion que devuelve el plural de un termino en espanyol o ingles.

  ENT: $term    - termino del cual se quiere crear el plural (en minusculas)
       $idioma  - idioma de $termino
  SAL: Plural de $termino
-------------------------------------------------------------------------------------------------*/

{
   $longitud = strlen($term);
   $final = $term[$longitud-1];      // -> ultimo caracter del termino
   if ($longitud == 1) // !!!!!!!!!!!!!!!!!!!!!!!!!! ANADIDO EL 4 DE JUNIO, NO SE SI CON ESTE CAMBIO FALLARA ALGO MAS, COMPROBAR !!!!!!!!!!!!!!!!!!!!!!!!
   // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
   // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		$ant_final = $term;
	else
		$ant_final = $term[$longitud-2];  // -> antepenultimo caracter del termino
   $plural = $term;

   //---------- TERMINO ESPANYOL ----------
   
   if($idioma == "esp")  
   {
      if($final == 'a' || $final == 'á' || $final == 'A' || $final == 'Á' ||
		 $final == 'e' || $final == 'é' || $final == 'E' || $final == 'É' ||
		 $final == 'o' || $final == 'ó' || $final == 'O' || $final == 'Ó' )
   	  {
         $plural .= 's';
	  }
	  else
	  {
	     $plural .= 'es';
	  }
   }

   //---------- TERMINO INGLES ----------

   if($idioma == "ing")  
   {
	   if($final == 'y' || $final == 'Y')
	   {
	      if($ant_final == 'a' || $ant_final == 'A' || $ant_final == 'e' || $ant_final == 'E' || 
			 $ant_final == 'i' || $ant_final == 'I' || $ant_final == 'o' || $ant_final == 'O' ||
			 $ant_final == 'u' || $ant_final == 'U')
		  {
		     $plural .= 's';
		  }
		  else
		  {
			 $plural[$longitud-1] = 'i';  // La 'y' se cambia por 'i'
		     $plural .= 'es';
		  }
	   }
	   else
	   {
	      if($final == 'a' || $final == 'A' || $final == 'i' || $final == 'I' || 
			 $final == 'o' || $final == 'O' || $final == 'u' || $final == 'U' ||
			 $final == 's' || $final == 'S')
		  {
		     $plural .= 'es';
		  }
		  else  // La palabra acaba en consonante distinta de 'y' y de 's'
		  {
		     $plural .= 's';
		  }
	   }
   }

   return $plural;
}


function eliminarTildes($palabra)
{
	$longitud = strlen($palabra);
	$p = "";

	for($i = 0; $i < $longitud; $i++)
	{
		$c = $palabra[$i];

		switch($c)
		{
		   case("á"): $p .= 'a'; break;
		   case("Á"): $p .= 'A'; break;
		   case("é"): $p .= 'e'; break;
		   case("É"): $p .= 'E'; break;
		   case("í"): $p .= 'i'; break;
		   case("Í"): $p .= 'I'; break;
		   case("ó"): $p .= 'o'; break;
		   case("Ó"): $p .= 'O'; break;
		   case("ú"): $p .= 'u'; break;
		   case("Ú"): $p .= 'U'; break;
		   case("ü"): $p .= 'u'; break;
		   case("Ü"): $p .= 'U'; break;
		   default: $p .= $c; break;
		   }
	}

	return $p;
}


function extraerElementos($texto)  // Anteriormente, el argumento era '$fd'
{

   $i = 0;  // -> puntero a una posicion del vector resultado
   $es_caracter = 0;
   $encontrada = 0;
   $palabra = "";

   for($j = 0; $j < strlen($texto); $j++)
   {
      $c = $texto[$j];

      if(esCaracter($c)) // Se ha leido un caracter
	  {
	     $palabra .= $c;
	     $es_caracter = 1;
	  }
	  else                    // Se la leido un "no caracter"
	  {
	     if($es_caracter) // Tenemos una palabra
	     {
            $resultado[$i] = $palabra;
			$i++;	   
			$palabra = "";
			$es_caracter = 0;
		 }
		 $resultado[$i] = $c;
		 $i++;
      }
   }

   $resultado[$i] = $palabra;  // Ultima palabra del texto

   return $resultado;
}

//------------------------------------------------------------------------------

function crearContexto($vector, $i)
{
   $ancho_contexto = 50;
   
   /*$longitud = 0;
   foreach($vector as $x)
   {
      $longitud++;
   }*/
   $longitud = count($vector);

   if($i >= $ancho_contexto) //-- Fijar el inicio del contexto
   {
      $elem_ini = $i - $ancho_contexto;
   }
   else
   {
      $elem_ini = 0;	 
   }

   if($i + $ancho_contexto <= $longitud)  //-- Fijar el fin del contexto
   {
      $elem_fin = $i + $ancho_contexto;
   }
   else
   {
      $elem_fin = $longitud;	 
		//$elem_fin = $elem_ini + $ancho_contexto;
   }   

   $contexto = '';
   
   for($j = $elem_ini; $j < $elem_fin; $j++)  //-- Crear contexto
   {
      if($vector[$j] != "\n")
	  {
	     $contexto .= $vector[$j];
	  }
   }

   return $contexto;
}

?>