<?php 
	session_start();header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);
	
	include ("../comun/permisos.php");
	
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
	
	$id_glosario = $_REQUEST['id_glosario']; // Identificador de termino
	$orden = $_REQUEST['orden']; // Orden
	$termino = $_REQUEST['termino']; // Termino
	$idioma = $_REQUEST['idioma']; // Idioma
	?>
<!-- buscar_nuevos_contextos.php --------------------------------------------------------------------------

     Realiza la busqueda de contextos.
---------------------------------------------------------------------------------------------------
     Copyright (c) 2010 Roberto Martin-Corral Mayoral
----------------------------------------------------------------------------------------------- -->
<html>
<head>
   <title>Buscador de nuevos contextos</title>
   <link rel="stylesheet" type="text/css" href="../comun/estilo.css">
   <meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
   <meta content="MSHTML 6.00.2800.1498" name="GENERATOR" />
   <script languaje="javascript" src="../ajax/ajax.js"></script>
   <script>
	// Funcion encargada de comprobar si se puede realizar la peticion.
	function doSubmit() {
		var form = document.formulario;

		var hayDocsSeleccionados = false;

		if ("" + form["documento[]"] != "undefined" && "" + form["documento[]"].length != "undefined") {
			//Chequeo de que hay textos seleccionados
			for (var j=0; !hayDocsSeleccionados &&  "" + form["documento[]"] != "undefined" && j< form["documento[]"].length; j++)
				hayDocsSeleccionados = hayDocsSeleccionados || form["documento[]"][j].checked; 
		} else {
			if ("" + form["documento[]"] != "undefined")
				hayDocsSeleccionados = form["documento[]"].checked;
			else
				hayDocsSeleccionados = false;
		}

		// Si no hay documentos, alertamos y nos salimos.
		if (!hayDocsSeleccionados) {
			alert ("Debe seleccionar al menos un documento");
		} else {
			document.formulario.submit();
		}
	}
   
	// Funcion encargada de recoger la respuesta de la invocacion via Ajax y de dibujar la tabla.
	function postbackComplete( contenedor, respuesta) {
		document.getElementById("loadingimg").style.display = "none";
		
		eval (respuesta);
		var iniciotabla = "<table border='0' cellpadding='4' cellspacing='1' bgcolor='#CC0000'>";
		var fintabla = "</table>";
		var textocabecera = "";
		var textoseparador = "";
		var img_html = "&nbsp;<img src='../imagenes/orden_" + document.formulario.orden_sentido.value + ".png' border='0' />";

		var textotitulo = "<tr bgcolor='#D8D9A4'><td>&nbsp;</td>";
<?php 
   $field_names = array("a.h_title","a.usuario_alta","a.fecha_alta","a.usuario_modificacion","a.fecha_modificacion");
   $field_description = array("T&iacute;tulo","Usuario (alta)","Fecha (alta)","Usuario (modificaci&oacute;n)","Fecha (modificaci&oacute;n)");
   
   if (tienePermisos("buscadorespecial")) { 
      $numeromagico = 7;
   } else {
      $numeromagico = 3;
   }

   for ($i=0; $i< $numeromagico-2; $i++) {
		echo "			textotitulo += \"<td><b><a href='#' onclick='ordenarpor(\\\"".$field_names[$i]."\\\")'>".$field_description[$i]."</a></b>\";";
		echo "			if (document.formulario.orden_campo.value == '".$field_names[$i]."') textotitulo += img_html;";
		echo "			textotitulo += \"</td>\";";
	}
?>
		textotitulo += "</tr>";
		var textotabla = "";
			
		if (textosarray.length == 0) {
			textotabla = "<tr bgcolor='#FFFF99'><td colspan='<?php  echo $numeromagico; ?>' align='center'>No se encontraron registros</td></tr>";
		} else {
			textocabecera = "<tr bgcolor='#FFFFFF'><td colspan='<?php  echo $numeromagico; ?>'>";
			textocabecera += "<table border='0' width='100%'><tr><td align='left'>P&aacute;gina " + paginaactual + " de " + maxpaginas + " (" + registrosencontrados + " registros encontrados)</td>";
			textocabecera += "<td align='right'>";
			
			if (paginaactual > 1) {
				textocabecera += "<a href='javascript:enviarPeticionTexto(1)'><img border='0' src='../imagenes/separador1.gif' alt='Primera p&aacute;gina' /></a>";
				textocabecera += "<a href='javascript:enviarPeticionTexto(" + (paginaactual - 1) + ")'><img border='0' src='../imagenes/bullet2.gif' alt='P&aacute;gina anterior' /></a>&nbsp;&nbsp;&nbsp;&nbsp;";
			}

			if (paginaactual < maxpaginas) {
				textocabecera += "<a href='javascript:enviarPeticionTexto(" + (paginaactual + 1) + ")'><img border='0' src='../imagenes/bullet21.gif' alt='Pr&oacute;xima p&aacute;gina' /></a>";
				textocabecera += "<a href='javascript:enviarPeticionTexto(" + maxpaginas + ")'><img border='0' src='../imagenes/separador.gif' alt='&Uacute;ltima p&aacute;gina' /></a>";
			}

			textocabecera += "</td></tr></table></td></tr>";
				
			var textotitulo2 = "<tr bgcolor='#FFFFFF'><td width='5%'><input type='checkbox' name='auxallcheck' onclick='for (var i=0;i<document.forms[0][\"documento[]\"].length;i++) document.forms[0][\"documento[]\"][i].checked=this.checked;'></td>"
			textotitulo2 += "<td colspan='<?php  echo $numeromagico-1; ?>' color='#000000'> Todos</td></tr>";
			textotitulo = textotitulo2 + textotitulo;
								
			for (var contador = 0 ; contador < textosarray.length; contador ++) {
				if (contador%2==0)
					textotabla += "<tr  bgcolor='#FFFFFF'>";
				else
					textotabla += "<tr bgcolor='#FFFF99'>";
				
				textotabla += "<td width='5%'><input type='checkbox' name='documento[]' value='" + textosarray[contador][0] + "'></td>";
				textotabla += "<td <?php  if (!tienePermisos("buscadorespecial")) { ?>colspan='2'<?php  } ?>>" + textosarray[contador][1] + " (" + textosarray[contador][2] + ") </td>";
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
	if (tienePermisos("administrarglosario")) {
?>
 <!-- FORMULARIO para la busqueda de contextos -->
<form name="formulario" method="post" action="operacion_nuevos_contextos.php">
	<input type="hidden" name="arg_op" value="buscar_contextos" />
	<input type="hidden" name="orden_campo" value="a.h_title" />
	<input type="hidden" name="orden_sentido" value="asc" />
	<input type="hidden" name="id_glosario" value="<?php echo $id_glosario;?>" />
	<input type="hidden" name="orden" value="<?php echo $orden;?>" />
	<input type="hidden" name="termino" value="<?php echo $termino;?>" />
	<input type="hidden" name="termino_idioma" value="<?php echo $idioma;?>" />

	<p align="center">
		<table border="0" width="700" bgcolor="#FFFF99" cellpadding="5" cellspacing="5">
		   <tr>
		      <td align="center" colspan="2">
         		<span class="titulo titulo_gris">Opciones</span>
		      </td>
		   </tr>
		   <tr>
		      <td colspan="2" class="buscador">
<?php
	$texto_buscador = "Seleccione los par&aacute;metros y pulse \"Aceptar\" para mostrar los textos. <br>Seleccione a continuaci&oacute;n los textos en los que quiera buscar contextos y pulse \"Buscar contextos\".";
	$incluirconexion = "no";
	include ("../comun/conexion.php");
	include ("../comun/buscador_textos.php");
?>
		      </td>
		   </tr>
		   <tr>
		      <td colspan="2" align="center"><input type="button" class="boton" value=" Buscar contextos " onclick="doSubmit()"/></td>
		   </tr>
		</table>
		<table border="0" width="100%" cellpadding="5" cellspacing="5">
		   <tr>
		      <td colspan="2" id="tempDiv" align="center">
		      </td>
		   </tr>
		</table>
	</p>
</form>
<?php 
	} else {
		echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>ACCESO INV&Aacute;LIDO a la p&aacute;gina.</p>";
		echo "<p align=\"center\"><input type='button' class='boton' value=' Cerrar ' onclick='window.close();' /></p>";
	}
?>
</body>
</html>