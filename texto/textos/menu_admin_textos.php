<?php 
	session_start();header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);

	// Permisos.
	include ("../../comun/permisos.php");
   
   	// Conexion a base de datos.
	include ("../../comun/conexion.php");
	
	// Fijamos orden y sentido por defecto.
	//if ($_SESSION['orden_campo'] == "")
	if (!isset($_SESSION['orden_campo']))
	{
		$_SESSION['orden_campo'] = "a.h_title";
	}
	//if ($_SESSION['orden_sentido'] == "")
	if (!isset($_SESSION['orden_sentido']))
	{
		$_SESSION['orden_sentido'] = "asc";
	}	
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
?>

<!-- menu_admin_textos.php ------------------------------------------------------------------------

     Menu de administracion de textos. Se listan todos los textos y todas las funciones que 
	 pueden realizarse.

----------------------------------------------------------------------------------------------- -->

<html>

<head><title></title>
   <link rel="stylesheet" type="text/css" href="../../comun/estilo.css">
   <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
   <meta content="Microsoft FrontPage 4.0" name=GENERATOR>
	<script languaje="javascript" src="../../ajax/ajax.js"></script>
	<script>
		var tipoTexto = new Array();
		var campoTexto = new Array();
		var fuenteTexto = new Array();
		
<?php 

   // Obtenemos los campos.
   $consulta= "SELECT id_campo,description_esp, description_ing FROM campo";
   $resultado = mysql_query($consulta) or die($lectura_campos_incorrecta . mysql_error()); 

   while($obj = mysql_fetch_object($resultado))
   {
		if ($lg == "esp") // Se muestran solo la info en espanol y si esta no existe en ingles.
		{
		  if ($obj->description_esp == '') // Se muestra el nombre en ingles
		  {
			  echo "campoTexto['$obj->id_campo'] = '$obj->description_ing';";
		  }
		  else // Se muestra en espanol
		  {
			  echo "campoTexto['$obj->id_campo'] = '$obj->description_esp';";
		  }
		}
		else // se muestra la info en ingles y si esta no existe en espanol
		{
		  if ($obj->description_ing == '') // Se muestra el nombre en ingles
		  {
			  echo "campoTexto['$obj->id_campo'] = '$obj->description_esp';";
		  }
		  else // Se muestra en espanol
		  {
			  echo "campoTexto['$obj->id_campo'] = '$obj->description_ing';";
		  }
		}
   	  //echo "campoTexto['$obj->id_campo'] = '$obj->description';";
   }

   mysql_free_result($resultado);

   // Obtenemos los tipos.
   $consulta= "SELECT id_tipo,scheme_esp, scheme_ing FROM tipo";
   $resultado = mysql_query($consulta) or die($lectura_tipos_incorrecta . mysql_error()); 

   while($obj = mysql_fetch_object($resultado))
   {
		if ($lg == "esp") // Se muestran solo la info en espanol y si esta no existe en ingles.
		{
			if ($obj->scheme_esp == '') // Se muestra el nombre en ingles
			{
			   echo "tipoTexto['$obj->id_tipo'] = '$obj->scheme_ing';";
			}
			else // Se muestra en espanol
			{
			   echo "tipoTexto['$obj->id_tipo'] = '$obj->scheme_esp';";
			}
		}
		else // se muestra la info en ingles y si esta no existe en espanol
		{
			if ($obj->scheme_ing == '') // Se muestra el nombre en ingles
			{
			   echo "tipoTexto['$obj->id_tipo'] = '$obj->scheme_esp';";
			}
			else // Se muestra en espanol
			{
			  echo "tipoTexto['$obj->id_tipo'] = '$obj->scheme_ing';";
			}
		}
   	  //echo "tipoTexto['$obj->id_tipo'] = '$obj->scheme';";
   }
   mysql_free_result($resultado);

   // Obtenemos las fuentes.
   $consulta= "SELECT id_fuente,h_title FROM fuente";
   $resultado = mysql_query($consulta) or die($lectura_fuente_incorrecta . mysql_error()); 


   while($obj = mysql_fetch_object($resultado))
   {
   	  echo "fuenteTexto['$obj->id_fuente'] = '".preg_replace('/\'/i','\\\'',$obj->h_title)."';";
   }

   mysql_free_result($resultado);


   mysql_close($enlace);
   

   $field_names = array("a.h_title","a.word_count","a.lang_usage","a.id_tipo","a.id_campo","a.usuario_alta","a.fecha_alta","a.usuario_modificacion","a.fecha_modificacion");
   if ($lg == "esp")
		$field_description = array("T&iacute;tulo","Palabras","Idioma","Tipo","Campo","Usuario (alta)","Fecha (alta)","Usuario (modificaci&oacute;n)","Fecha (modificaci&oacute;n)");
   else
		$field_description = array("Title","Words","Idiom","Type","Field","User (creation)","Date (creation)","User (modification)","Date (modification)");
   
   if (tienePermisos("buscadorespecial")) { 
      $numeromagico = 11;
   } else {
      $numeromagico = 7;
   }
?>
		// Funcion encargada de recoger la respuesta de la invocacion via Ajax y de dibujar la tabla.
		function postbackComplete( contenedor, respuesta) {
			document.getElementById("loadingimg").style.display = "none";

			eval (respuesta);
			var iniciotabla = "<table border='0' cellpadding='4' cellspacing='1' bgcolor='#CC0000'>";
			var fintabla = "</table>";
			var textocabecera = "";
			var textoseparador = "";
			var img_html = "&nbsp;<img src='../../imagenes/orden_" + document.formulario.orden_sentido.value + ".png' border='0' />";

			var textotitulo = "<tr bgcolor='#D8D9A4'>";
<?php 
	for ($i=0; $i< $numeromagico-2; $i++) {
		echo "			textotitulo += \"<td><b><a href='#' onclick='ordenarpor(\\\"".$field_names[$i]."\\\")'>".$field_description[$i]."</a></b>\";";
		echo "			if (document.formulario.orden_campo.value == '".$field_names[$i]."') textotitulo += img_html;";
		echo "			textotitulo += \"</td>\";";
	}
?>
			textotitulo += "<td>&nbsp;</td></tr>";

			
			var textotabla = "";
			
			if (textosarray.length == 0) {
				textotabla = "<tr bgcolor='#FFFF99'><td colspan='<?php  echo $numeromagico; ?>' align='center'>No se encontraron registros</td></tr>";
			} else {
				textocabecera = "<tr bgcolor='#FFFFFF'><td colspan='<?php  echo $numeromagico; ?>'>";
				textocabecera += "<table border='0' width='100%'><tr><td align='left'><?php echo $pagina ?> " + paginaactual + " <?php echo $de ?> " + maxpaginas + " (" + registrosencontrados + " <?php echo $registros_encontrados ?>)</td>";
				textocabecera += "<td align='right'>";
				
				if (paginaactual > 1) {
					textocabecera += "<a href='javascript:enviarPeticionTexto(1)'><img border='0' src='../../imagenes/separador1.gif' alt='Primera p&aacute;gina' /></a>";
					textocabecera += "<a href='javascript:enviarPeticionTexto(" + (paginaactual - 1) + ")'><img border='0' src='../../imagenes/bullet2.gif' alt='P&aacute;gina anterior' /></a>&nbsp;&nbsp;&nbsp;&nbsp;";
				}
				if (paginaactual < maxpaginas) {
					textocabecera += "<a href='javascript:enviarPeticionTexto(" + (paginaactual + 1) + ")'><img border='0' src='../../imagenes/bullet21.gif' alt='Pr&oacute;xima p&aacute;gina' /></a>";
					textocabecera += "<a href='javascript:enviarPeticionTexto(" + maxpaginas + ")'><img border='0' src='../../imagenes/separador.gif' alt='&Uacute;ltima p&aacute;gina' /></a>";
				}

				textocabecera += "</td></tr></table></td></tr>";

				for (var contador = 0 ; contador < textosarray.length; contador ++) {
					
					if (contador%2==0)
						textotabla += "<tr bgcolor='#FFFFFF'>";
					else
						textotabla += "<tr bgcolor='#FFFF99'>";
					
					textotabla += "<td><a href='operacion_texto.php?arg_op=vista&id_texto=" +
						textosarray[contador][0] + "&edition_stmt=" + textosarray[contador][2] + "&idioma=" + textosarray[contador][5] + "'>" + textosarray[contador][1] + "</td>";
					textotabla += "<td>" + textosarray[contador][3] + "</td>";
					textotabla += "<td>" + textosarray[contador][5] + "</td>";
					textotabla += "<td>" + tipoTexto[textosarray[contador][6]] + "</td>";
					textotabla += "<td>" + campoTexto[textosarray[contador][7]] + "</td>";
//					textotabla += "<td>" + fuenteTexto[textosarray[contador][8]] + "</td>";
<?php 
   if (tienePermisos("buscadorespecial")) {
?>					textotabla += "<td>" + textosarray[contador][9] + " </td>";
					textotabla += "<td>" + textosarray[contador][10] + " </td>";
					textotabla += "<td>" + textosarray[contador][11] + " </td>";
					textotabla += "<td>" + textosarray[contador][12] + " </td>";
<?php 
   }
?>
					textotabla += "<td><a href='operacion_texto.php?arg_op=modificar&id_texto=" + textosarray[contador][0] + 
						"'><img border=\"0\" src=\"../../imagenes/modificar_ico.gif\" ></a>&nbsp;&nbsp;&nbsp;<a href='" +
						"operacion_texto.php?arg_op=eliminar&id_texto=" + textosarray[contador][0] + "&h_title=" + textosarray[contador][1] +
						"&edition_stmt=" + textosarray[contador][2] + "''><img border=\"0\" src=\"../../imagenes/papelera_ico.png\" ></a></td>";
					textotabla += "</tr>";
					
				}
			}
			
			contenedor.innerHTML = iniciotabla + textocabecera + textoseparador + textotitulo + textotabla + textoseparador + textocabecera + fintabla;
		}
	</script>		

</head>

<body>

<?php  
   include("func_texto.php");
  
   if(tienePermisos("textotextosmenuadmin"))
   {
?>
<p align="center"><span class="titulo titulo_rojo"><?php echo $titulo_menu_admin_texto ?></span><br>
<img border="0" src="../../imagenes/linea_horiz.gif" ></p>

<form name="formulario" action="" method="post">
	<input type="hidden" name="orden_campo" value="a.h_title" />
	<input type="hidden" name="orden_sentido" value="asc" />
	<p align="center">
		<table border="0" width="100%">
			<tr>
				<td align="center" width="150" valign="top" bgcolor="#FFFF99" rowspan="2" class="conborde">
					<p align="center"><br><span class="titulo titulo_gris"><?php echo $operaciones ?></span>
						<br><br><input type="button" class="boton long_130 boton_nuevo" value="      <?php echo $nuevo_texto ?> " onclick="document.location='operacion_texto.php?arg_op=nuevo';"/><br><br>
	                    <input type="button" class="boton boton_volver long_160" value="      <?php echo $salir_menu_textos ?> " onclick="document.location='../menu_admin_texto.php';"/><br><br>
					</p>
					<a href="../../ayuda/ayuda_admin_textos.htm" target="_blank"><span class="subtitulo titulo_rojo"><img border="0" src="../../imagenes/ayuda.png" width="43" height="24" /><br><?php echo $ayuda ?></span></a>
				</td>
				<td>
					<table width="100%" border="0">
						<tr>
							<td width="20%">&nbsp;</td>
							<td align="center" width="60%" class="buscador">
<?php 
	$texto_buscador = $mensaje45." <br>".$mensaje46;
	include ("../../comun/buscador_textos.php");
?>
							</td>
							<td width="20%">&nbsp;</td>
						</tr>
						<tr>
							<td align="center" colspan="3">
								<table width="100%" border="0">
									<tr><td id="tempDiv" align="center"></td></tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</p>
</form>
<br>
<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_texto ?></a> > <u><?php echo $textos ?></u<</td>
   </tr>
</table>
<?php 
   }
   else
   {
      echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   }
?>

</body>

</html>