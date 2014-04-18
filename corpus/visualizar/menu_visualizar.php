<?php 
   session_start();header('Content-Type: text/html; charset=utf-8');ini_set("session.cookie_httponly", 1);
   
   	// Permisos.
	include ("../../comun/permisos.php");
   
   	// Listado de los textos.
	include ("../../comun/conexion.php");
	
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

<!-- menu_visualizar.php --------------------------------------------------------------------------

     Menu de visualizacion de textos del sitio web. Se listan todos los textos y todas las 
	 funciones que pueden realizarse.

----------------------------------------------------------------------------------------------- -->
<html>
<head>
	<title>Calíope</title>
	<link rel="stylesheet" type="text/css" href="../../CSS/menuvisualizar.css">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
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
		else
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
		else
		{
		  if ($obj->scheme_ing == '') // Se muestra el nombre en ingles
		  {
			  echo "tipoTexto['$obj->id_tipo'] = '$obj->scheme_ing';";
		  }
		  else // Se muestra en espanol
		  {
			  echo "tipoTexto['$obj->id_tipo'] = '$obj->scheme_esp';";
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
   	  echo "fuenteTexto['$obj->id_fuente'] = '$obj->h_title';";
   }

   mysql_free_result($resultado);


   mysql_close($enlace);   

   $field_names = array("a.h_title","a.word_count","a.lang_usage","a.id_tipo","a.id_campo","a.id_fuente","a.usuario_alta","a.fecha_alta","a.usuario_modificacion","a.fecha_modificacion");
   if ($lg == "esp")
   {
		   $field_description = array("T&iacute;tulo","Palabras","Idioma","Tipo","Campo","Fuente","Usuario (alta)","Fecha (alta)","Usuario (modificaci&oacute;n)","Fecha (modificaci&oacute;n)");
   }
   else
   {
		   $field_description = array("Title","Words","Idiom","Type","Field","Source","User (new)","Date (new)","Users (modification)","date (modification)");

   }
   
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
			var iniciotabla = "<table id='aux_table' width=70% border='1' cellpadding='4' cellspacing='1' bgcolor='#FFFFFF'>";
			var fintabla = "</table>";
			var textocabecera = "";
			var textoseparador = "";
			var textotabla = "";
			var img_html = "&nbsp;<img src='../../imagenes/orden_" + document.formulario.orden_sentido.value + ".png' border='0' />";

			var textotitulo = "<tr bgcolor='#2980b9'>";
<?php 
	for ($i=0; $i< $numeromagico-1; $i++) {
		echo "			textotitulo += \"<td id='texto_titulo'><b><a href='#' onclick='ordenarpor(\\\"".$field_names[$i]."\\\")'>".$field_description[$i]."</a></b>\";";
		echo "			if (document.formulario.orden_campo.value == '".$field_names[$i]."') textotitulo += img_html;";
		echo "			textotitulo += \"</td>\";";
	}
?>
			textotitulo += "</tr>";
			if (textosarray.length == 0) {
				textotabla = "<tr bgcolor='#FFFFFF'><td colspan='<?php  echo $numeromagico; ?>' align='center'>No se encontraron registros</td></tr>";
			} else {
				textocabecera = "<tr bgcolor='#FFFFFF'><td colspan='<?php  echo $numeromagico; ?>'>";
				textocabecera += "<table border='0' width='100%'><tr><td align='left'>P&aacute;gina " + paginaactual + " de " + maxpaginas + " (" + registrosencontrados + " registros encontrados)</td>";
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
						textotabla += "<tr bgcolor='#FFFFFF'>";
					
					textotabla += "<td><a href='operacion_visualizar.php?arg_op=vista&id_texto=" +
					textosarray[contador][0] + "&edition_stmt=" + textosarray[contador][2] + "&idioma=" + textosarray[contador][5] + "'>" + textosarray[contador][1] + "</td>";
					textotabla += "<td>" + textosarray[contador][3] + "</td>";
					textotabla += "<td>" + textosarray[contador][5] + "</td>";
					textotabla += "<td>" + tipoTexto[textosarray[contador][6]] + "</td>";
					textotabla += "<td>" + campoTexto[textosarray[contador][7]] + "</td>";
					textotabla += "<td>" + fuenteTexto[textosarray[contador][8]] + "</td>";
<?php 
   if (tienePermisos("buscadorespecial")) {
?>					textotabla += "<td>" + textosarray[contador][9] + " </td>";
					textotabla += "<td>" + textosarray[contador][10] + " </td>";
					textotabla += "<td>" + textosarray[contador][11] + " </td>";
					textotabla += "<td>" + textosarray[contador][12] + " </td>";
<?php 
   }
?>
					textotabla += "</tr>";
					
				}
			}
			
			contenedor.innerHTML = iniciotabla + textocabecera + textoseparador + textotitulo + textotabla + textoseparador + textocabecera + fintabla;
		}
	</script>		

</head>

<body>

<?php      
   if(tienePermisos("corpusvisualizarmenu"))
   {
      include("func_texto.php");

      $idioma = $_GET['idioma'];
?>

	<header>
		<h1><?php echo $titulo_visualizar_texto ?></h1>
	</header>	
	<nav>
		<input id="operacion" type="button" value="<?php echo $operaciones ?>"/> 
		<input type="button" value="<?php echo $salir_principal ?>" onclick="document.location='../../encabezado.php'"/>
		<input id="ayuda" type="button" value="<?php echo $ayuda ?>" onclick="document.location='../../ayuda/ayuda_visualizar.htm';"/>
	</nav>
	<section>
		<form name="formulario" action="" method="post">
		<input type="hidden" name="orden_campo" value="a.h_title" />
		<input type="hidden" name="orden_sentido" value="asc" />
		<?php 
			$texto_buscador = $mensaje45." <br>".$mensaje70;
			include ("../../comun/buscador_textos.php");
		
		?>
		</form>
		<br>
		<div id="limpiarFormulario">
			<input type="button" value="<?php echo $limpiar_formulario ?>" onclick="document.formulario.reset();"/>
		</div>
	</section>

			<table id="aux_table" width="100%" border="0">
				<tr><td id="tempDiv" align="center"></td></tr>
			</table>
<?php 
   }
   else  // El usuario NO tiene privilegios para acceder a la pagina
   {
      echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   }
?>

</body>

</html>