<?php 
   session_start();header('Content-Type: text/html; charset=utf-8');ini_set("session.cookie_httponly", 1);
   
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

   // Fijamos orden y sentido por defecto.
	if (isset($_SESSION["orden_campo"]) == "")
		$_SESSION["orden_campo"] = "a.h_title";
	if (isset($_SESSION["orden_sentido"]) == "")
		$_SESSION["orden_sentido"] = "asc";
?><!-- menu_lista.php ---------------------------------------------------------------------------
     Pagina que presenta el formulario para el listado de palabras de un texto.
----------------------------------------------------------------------------------------------- -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Calíope</title>
    <link rel="stylesheet" type="text/css" href="../../CSS/menu_lista.css">	
	<link rel="stylesheet" type="text/css" href="../../CSS/menuvisualizar.css">
    <meta http-equiv=Content-Type content="text/html; charset=utf-8">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
    <meta content="MSHTML 6.00.2800.1498" name=GENERATOR>
   <script languaje="javascript" src="../../ajax/ajax.js"></script>
   <script>
		var checkspulsados = new Array();

		function comprobarChecksPulsados(indice) {
//			var maximo = 6;
			if (checkspulsados.length == 0) {
				checkspulsados[0] = indice;
			} else {
				var salir = false;
				for (var i=0; !salir && i<checkspulsados.length; i++) {
					if (checkspulsados[i] == indice) {
						salir = true;
						// Pasamos todos al final.
						for (var j=i; j<checkspulsados.length-1; j++) {
							checkspulsados[j] = checkspulsados[j+1];
						}

						checkspulsados.length--;  
					}
				}

/*
				if (checkspulsados.length == maximo && !salir) {
					document.formulario["texto[]"][indice].checked = false;
					alert ("Solamente se pueden seleccionar " + maximo + " textos como m\u00e1ximo.");
				} else 
*/
				if (!salir) {
					checkspulsados[checkspulsados.length] = indice;
				}
			}
		}

		function postbackComplete( contenedor, respuesta) {
			checkspulsados.length = 0;

			document.getElementById("loadingimg").style.display = "none";
			
			eval(respuesta);
			var iniciotabla = "<table id='aux_table' width=95% align='center' border='1' cellpadding='4' cellspacing='1' bgcolor='#FFFFFF'>";
			var fintabla = "</table>";
			var textocabecera = "";
			var textoseparador = "";
			var textotitulo = "";
			var textotabla = "";
			var img_html = "&nbsp;<img src='../../imagenes/orden_" + document.formulario.orden_sentido.value + ".png' border='0' />";

			var textotitulo = "<tr bgcolor='#2980b9'><td>&nbsp;</td>";
<?php 
   $field_names = array("a.h_title","a.usuario_alta","a.fecha_alta","a.usuario_modificacion","a.fecha_modificacion");
   if ($lg == "esp")
   {
		$field_description = array("T&iacute;tulo","Usuario (alta)","Fecha (alta)","Usuario (modificaci&oacute;n)","Fecha (modificaci&oacute;n)");
   }
   else
   {
		$field_description = array("Title","User (creation)","Date (creation)","User (modification)","Date (modification)");
   }
   
   if (tienePermisos("buscadorespecial")) { 
      $numeromagico = 7;
   } else {
      $numeromagico = 3;
   }

   for ($i=0; $i< $numeromagico-2; $i++) {
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
						textotabla += "<tr bgcolor='#FFFFFF'>";
						
					textotabla += "<td width='5%'><input type='checkbox' onclick='comprobarChecksPulsados(" + contador + ")' name='texto[]' value='" + textosarray[contador][0] + "'></td>";
					textotabla += "<td>" + textosarray[contador][1] + "</td>";
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

		function enviar() {
			if (checkspulsados.length == 0) {
				alert ("Debe seleccionar un texto para ver el listado de palabras y dos para comparar el listado de palabras de los dos documentos");
			} else {
				var frecuencia_desde = document.formulario.frecuencia_desde.value;
				var frecuencia_hasta = document.formulario.frecuencia_hasta.value;
				if (frecuencia_desde != "" & ("" + parseInt(frecuencia_desde) == "NaN" || parseInt(frecuencia_desde) <=0)) {
					alert ("El campo 'Frecuencia Desde' debe ser un n\u00famero entero positivo");
				} else if (frecuencia_hasta != "" & ("" + parseInt(frecuencia_hasta) == "NaN" || parseInt(frecuencia_hasta) <=0)) {
					alert ("El campo 'Frecuencia Hasta' debe ser un n\u00famero entero positivo");
				} else {
					document.formulario.submit();
				}
			}
		}
   </script>
</head>
<body>
<?php 
   if(tienePermisos("corpuslistamenu")) {
?>
	<header>
		<h1><?php echo $titulo_listado_palabras ?></h1>
	</header>

	<nav>
		<input id="operacion" type="button" value="<?php echo $operaciones ?>"/> 
		<input type="button" value="<?php echo $salir_principal ?>" onclick="document.location='../../encabezado.php'"/>
		<input id="ayuda" type="button" value="<?php echo $ayuda ?>" onclick="document.location='../../ayuda/ayuda_visualizar.htm';"/>
	</nav>
<form action="lista.php" method="post" name="formulario">
	<input type="hidden" name="orden_campo" value="a.h_title" />
	<input type="hidden" name="orden_sentido" value="asc" />
<!--	<p align="center">
	<table border="0" width="100%">
		<tr>
			<td align="center" width="170" valign="top" bgcolor="#FFFF99" rowspan="6" class="conborde">
				<p align="center"><br><span class="titulo titulo_gris"><?php echo $operaciones ?></span><br><br>
	      	      	<input type="button" class="boton boton_volver long_160" value="      <?php echo $volver_corpus ?> " onclick="document.location='../menu_acceso_corpus.php';"/><br><br><br>
		 			<a href="../../ayuda/ayuda_lista.htm" target="_blank"><span class="subtitulo titulo_rojo"><img border="0" src="../../imagenes/ayuda.png" width="43" height="24" /><br><?php echo $ayuda ?></span></a><br>&nbsp;
				</p>
			</td>
			<td>&nbsp;</td>
			<td align="center" class="buscador">-->
	<section>
<?php 
	$texto_buscador = $mensaje45." <br>".$mensaje57;
	include ("../../comun/buscador_textos.php");
?>
	<br>
	<div id="desde-hasta">
		<table border="0" width="100%" align="center">
		<td colspan="4"><?php echo $frec ?></td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="left"><?php echo $desde ?>&nbsp;&nbsp;<input id="desde2" type="text" name="frecuencia_desde" size="4" maxlength="4" value="" title="<?php echo $frec_aparicion_minima ?>">&nbsp;&nbsp;
		<?php echo $hasta ?>&nbsp;&nbsp;<input id="hasta2" type="text" name="frecuencia_hasta" size="4" maxlength="4" value="" title="<?php echo $frec_aparicion_maxima ?>"></td>
		</table>
	</div>
	<br>
	<div id="listar-limpiar">
		<input type="button" value="<?php echo $mostrar_listado ?>" onclick="enviar();"/>&nbsp;&nbsp;
		<input id="clean_form" type="button" value="<?php echo $limpiar_formulario ?>" onclick="document.formulario.reset();"/>&nbsp;&nbsp;
	</div>
	<!--		</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td width="700">
				<table border="0" width="100%">
					<tr>			
						<td></td>
						<td>
							
						</td>
						<td>&nbsp;</td>
					</tr>
				</table>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" align="center">
			    
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td align="center" width="800">-->
				<table id='aux_table' width=100% border="0">
					<tr><td id="tempDiv" align="center"></td></tr>
				</table>
			<!--</td>
			<td>&nbsp;</td>
		</tr>
	</table>
	</p>
</form>-->
	</section>

<!--<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_acceso_corpus.php">CORPUS</a> > <u><?php echo $lista_palabras ?></u></td>
   </tr>
</table>-->

<?php 
   }
   else  // El usuario NO tiene privilegios para acceder a la pagina
   {
      echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   }
?>

</body>
</html>
