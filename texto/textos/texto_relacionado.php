<?php 
   session_start();header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);

   include ("../../comun/permisos.php");
   include("func_texto.php");
   
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

   if (isset($_GET['arg_op']))
   {
		$arg_op = $_GET['arg_op'];
	}	
		
?><!-- texto_relacionado.php --------------------------------------------------------------------------

     Realiza la busqueda de un texto.
---------------------------------------------------------------------------------------------------
     Copyright (c) 2010 Roberto Martin-Corral Mayoral
----------------------------------------------------------------------------------------------- -->
<html>
<head>
   <title></title>
   <link rel="stylesheet" type="text/css" href="../../comun/estilo.css">
   <meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
   <meta content="MSHTML 6.00.2800.1498" name="GENERATOR" />
   <script languaje="javascript" src="../../ajax/ajax.js"></script>
   <script>
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
				textocabecera += "<a href='javascript:enviarPeticionTexto(1)'><img border='0' src='../../imagenes/separador1.gif' alt='Primera p&aacute;gina' /></a>";
				textocabecera += "<a href='javascript:enviarPeticionTexto(" + (paginaactual - 1) + ")'><img border='0' src='../../imagenes/bullet2.gif' alt='P&aacute;gina anterior' /></a>&nbsp;&nbsp;&nbsp;&nbsp;";
			}

			if (paginaactual < maxpaginas) {
				textocabecera += "<a href='javascript:enviarPeticionTexto(" + (paginaactual + 1) + ")'><img border='0' src='../../imagenes/bullet21.gif' alt='Pr&oacute;xima p&aacute;gina' /></a>";
				textocabecera += "<a href='javascript:enviarPeticionTexto(" + maxpaginas + ")'><img border='0' src='../../imagenes/separador.gif' alt='&Uacute;ltima p&aacute;gina' /></a>";
			}

			textocabecera += "</td></tr></table></td></tr>";
				
			var textotitulo2 = "<tr bgcolor='#FFFFFF'>";
			textotitulo = textotitulo2 + textotitulo;
			
			for (var contador = 0 ; contador < textosarray.length; contador ++) {
				if (contador%2==0)
					textotabla += "<tr  bgcolor='#FFFFFF'>";
				else
					textotabla += "<tr bgcolor='#FFFF99'>";
				
				textotabla += "<td <?php  if (!tienePermisos("buscadorespecial")) { ?>colspan='2'<?php  } ?>><a href=\"#\" onclick=\"cargar('" + textosarray[contador][0] + "','" + textosarray[contador][1] + "')\">" + textosarray[contador][1] + "</a></td>";
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

	function cargar(codigo, texto) {
		var form = window.opener.document.formulario;

		form.texto_relacionado.value = codigo;
		form.txt_texto_relacionado.value = texto;
		this.close();
	} 
   </script>
</head>
<body>
<form name="formulario" method="post">
	<input type="hidden" name="orden_campo" value="a.h_title" />
	<input type="hidden" name="orden_sentido" value="asc" />

	<p align="center">
		<table border="0" width="700" bgcolor="#FFFF99" cellpadding="5" cellspacing="5">
		   <tr>
		      <td align="center" colspan="2">
         		<span class="titulo titulo_gris"><?php echo $opciones ?></span>
		      </td>
		   </tr>
		   <tr>
		      <td colspan="2" class="buscador">
<?php 
	$texto_buscador = $mensaje45."<br>".$mensaje50;
	include ("../../comun/buscador_textos.php");
?>
		      </td>
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
</body>
</html>