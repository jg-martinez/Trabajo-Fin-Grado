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
	
	$conc = $_GET['concord'];
?>

<!-- menu_concord.php ---------------------------------------------------------------------------

     Pagina que presenta el formulario para la busqueda de coocurrencias por termino.

----------------------------------------------------------------------------------------------- -->

<html>
<head>
	<title>Calíope</title>
	<link rel="stylesheet" type="text/css" href="../../CSS/menu_concord_antiguo.css">
	<link rel="stylesheet" type="text/css" href="../../comun/estilo.css">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="MSHTML 6.00.2800.1498" name="GENERATOR" />
	<script languaje="javascript" src="../../ajax/ajax.js"></script>
	<script>

	// Funcion encargada de recoger la respuesta de la invocacion via Ajax y de dibujar la tabla.
	function postbackComplete( contenedor, respuesta) {
		document.getElementById("loadingimg").style.display = "none";
			
		eval (respuesta);
		var iniciotabla = "<table width=70% border='1' cellpadding='4' cellspacing='1' bgcolor='#ffffff'>";
		var fintabla = "</table>";
		var textocabecera = "";
		var textoseparador = "";
		var img_html = "&nbsp;<img src='../../imagenes/orden_" + document.formulario.orden_sentido.value + ".png' border='0' />";

		var textotitulo = "<tr id='texto_titulo' bgcolor='#2980b9'><td>&nbsp;</td>";
<?php 
   $field_names = array("a.h_title","a.usuario_alta","a.fecha_alta","a.usuario_modificacion","a.fecha_modificacion");
   $field_description = array($titulo,$usuario_alta,$fecha_alta,$usuario_modificacion,$fecha_modificacin);
   
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
			textotabla = "<tr bgcolor='#FFFFff'><td colspan='<?php  echo $numeromagico; ?>' align='center'>No se encontraron registros</td></tr>";
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
				
			var textotitulo2 = "<tr bgcolor='#FFFFFF'><td width='5%'><input type='checkbox' name='auxallcheck' onclick='for (var i=0;i<document.forms[0][\"documento[]\"].length;i++) document.forms[0][\"documento[]\"][i].checked=this.checked;'></td>"
			textotitulo2 += "<td colspan='<?php  echo $numeromagico-1; ?>' color='#000000'> <?php echo $todos ?></td></tr>";
			textotitulo = textotitulo2 + textotitulo;
								
			for (var contador = 0 ; contador < textosarray.length; contador ++) {
				if (contador%2==0)
					textotabla += "<tr  bgcolor='#FFFFFF'>";
				else
					textotabla += "<tr bgcolor='#FFFFff'>";
				
				textotabla += "<td width='5%'><input type='checkbox' name='documento[]' value='" + textosarray[contador][0] + "#" + textosarray[contador][1] + "'></td>";
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
	
	// Sube un termino una fila
	function subir () {
		var form = document.formulario;
		var distancia = form["distancia[]"];
		var palabra = form["palabra[]"];
		var indice = palabra.selectedIndex;

		if (indice > -1) {
			var tempTermino = new Option (palabra.options[indice].text, palabra.options[indice].value);
			var tempDistancia = new Option (distancia.options[indice].text, distancia.options[indice].value);

			if (indice > 1) {
				palabra.options[indice] = new Option(palabra.options[indice-1].text,palabra.options[indice-1].value); 
				distancia.options[indice] = new Option(distancia.options[indice-1].text,distancia.options[indice-1].value);
				palabra.options[indice-1] = tempTermino;
				distancia.options[indice-1] = tempDistancia;
			} else if (indice == 1) {
				// La primera distancia siempre es vacio.
				palabra.options[indice] = new Option(palabra.options[indice-1].text,palabra.options[indice-1].value);
				palabra.options[indice-1] = tempTermino;
			}
		} else {
			alert ("Debe seleccionar un t\u00e9rmino para cambiar de orden.");
		}
	}
	
	// Baja un termino una fila
	function bajar() {
		var form = document.formulario;
		var distancia = form["distancia[]"];
		var palabra = form["palabra[]"];
		var indice = palabra.selectedIndex;
		
		if (indice > -1) {
			var tempTermino = new Option (palabra.options[indice].text, palabra.options[indice].value);
			var tempDistancia = new Option (distancia.options[indice].text, distancia.options[indice].value);

			if (indice > 0 && indice < palabra.options.length-1) {
				palabra.options[indice] = new Option(palabra.options[indice+1].text,palabra.options[indice+1].value); 
				distancia.options[indice] = new Option(distancia.options[indice+1].text,distancia.options[indice+1].value);
				palabra.options[indice+1] = tempTermino;
				distancia.options[indice+1] = tempDistancia;
			} else if (indice == 0) {
				// La primera distancia siempre es vacio.
				tempTermino.value = palabra.options[1].value; 
				palabra.options[0] = new Option(palabra.options[1].text,palabra.options[0].value);
				palabra.options[1] = tempTermino;
			}
		} else {
			alert ("Debe seleccionar un t\u00e9rmino para cambiar de orden.");
		}
	}
	
	// Incluye un termino en la lista, junto con la distancia al termino anterior
	function anadirTermino () {
		var form = document.formulario;
		var distancia = form["distancia[]"];
		var palabra = form["palabra[]"];
		var nuevaPalabra = form.nuevoTermino.value;
		var nuevaDistancia = form.nuevaDistancia.value;
		var chequeoNumero = /\d+/;
		
		if (nuevaPalabra != "") {
			// chequeo de que la palabra es valida.
			if (nuevaDistancia == "" || chequeoNumero.test(nuevaDistancia)) {
				if (nuevaDistancia == "")
					nuevaDistancia = 0;
				if (form.distanciaexacta.checked)
					nuevaDistancia += " =";
				else
					nuevaDistancia += " <=";

				if (palabra.options.length == 0) {
					distancia.options[distancia.options.length] = new Option(""," ");
					palabra.options[palabra.options.length] = new Option (nuevaPalabra,nuevaPalabra);
				} else {
					if (nuevaDistancia != "") {
						distancia.options[distancia.options.length] = new Option(nuevaDistancia,nuevaDistancia);
						palabra.options[palabra.options.length] = new Option (nuevaPalabra,nuevaPalabra);
					} else {
						alert ("Solamente la primera distancia puede ser vac\u00eda.");
					}
				}
			} else {
				alert ("La distancia debe no tener valor o ser un n\u00famero entero no negativo.");
			}
		} else {
			alert ("El nuevo t\u00e9rmino no puede ser vac\u00edo.");
		}
	}

	// Elimina un termino y su distancia de la lista
	function eliminar () {
		var form = document.formulario;
		var distancia = form["distancia[]"];
		var palabra = form["palabra[]"];
		var index = palabra.selectedIndex;

		if (index > 0) {
			palabra.options[index] = null;
			distancia.options[index] = null;
		} else {
			if (palabra.options.length > 1) {
				palabra.options[0].value = palabra.options[1].value;
				palabra.options[0].text = palabra.options[1].text;
				palabra.options[1] = null;
				distancia.options[1] = null;
			} else {
				palabra.options[0] = null;
				distancia.options[0] = null; 
			}
		}
	}

	// Envia la peticion de buscar concordancias.
	function buscarConcordancias() {
		var form = document.formulario;

		// Verificamos si hay terminos incluidos
		if (form["palabra[]"].options.length == 0) {
			alert ("Es necesario introducir al menos un t\u00e9rmino");
		} else {

			var hayDocsSeleccionados = false;
			if ("" + form["documento[]"].length != "undefined") {
				//Chequeo de que hay textos seleccionados
				for (var j=0; !hayDocsSeleccionados &&  "" + form["documento[]"] != "undefined" && j< form["documento[]"].length; j++) {
					hayDocsSeleccionados = hayDocsSeleccionados || form["documento[]"][j].checked; 
				}
			} else {
				hayDocsSeleccionados = form["documento[]"].checked;
			}
	
			// Si no hay documentos, alertamos y nos salimos.
			if (!hayDocsSeleccionados) {
				alert ("Debe seleccionar al menos un documento");
			} else {
				// Transformamos los campos select en multiples para enviar todos los datos
				form["palabra[]"].setAttribute("multiple","multiple");
				form["distancia[]"].setAttribute("multiple","multiple");
				form["distancia[]"].disabled= false;
				
				for (var i=0; i< form["palabra[]"].options.length; i++) {
					form["palabra[]"].options[i].selected = true;
					form["distancia[]"].options[i].selected = true;
				}
				
				form.submit();
			}
		}
	}

	function mostrar_opciones ()
	{
		var tabla = document.getElementById("tabla_opciones");
		var mensaje_tabla = document.getElementById("mensaje_opciones");
		var imagen_tabla = document.getElementById("imagen_opciones");

		if (tabla.style.display == "")
		{
			tabla.style.display = "none";
			mensaje_tabla.innerHTML = "Pulse <b>aqu&iacute</b> para mostrar las opciones de b&uacute;squeda de concordancias";
			imagen_tabla.src = "<?php  echo $_SESSION['application_url']; ?>/imagenes/orden_asc.png";
		}
		else
		{
			tabla.style.display = "";
			mensaje_tabla.innerHTML = "Pulse <b>aqu&iacute</b> para ocultar las opciones de b&uacute;squeda de concordancias";
			imagen_tabla.src = "<?php  echo $_SESSION['application_url']; ?>/imagenes/orden_desc.png";
		}
	}
	</script>
</head>

<body>

<?php 
   if(tienePermisos("corpusconcordanciasmenu"))
   {
?>

	<header>
		<h1><?php echo $colocacion?></h1>
	</header>

<form action="concordancia.php" name="formulario" method="post">
<input type="hidden" name="orden_campo" value="a.h_title" />
<input type="hidden" name="orden_sentido" value="asc" />

    <p id="opciones"><?php echo $opciones ?></p>
<p align="center">
<table  width="70%" border="0" bgcolor="#FFFFFF" cellpadding="5" cellspacing="5">
   <tr>
      <td colspan="4">
      	<table width="100%" border="0" bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" >
		  <tr>
		    <td>
				<table  width="100%" class="mensaje">
					<tr>
						<td colspan="3" align="center" onclick="mostrar_opciones();"><span  id="mensaje_opciones"><?php echo $mensaje60 ?> <b><i><?php echo $mensaje61 ?></i></b> <?php echo $mensaje62 ?></span></td>
						<!--<td align="right"><img id="imagen_opciones" border="0" src="<?php  echo $_SESSION['application_url']; ?>/imagenes/orden_desc.png" /></td>-->
					</tr>
				</table><br>
		      	<table width="100%" border="0" cellpadding="0" cellspacing="0" id="tabla_opciones">
		      	   <tr>
		      	      <td align="center"><?php echo $term ?></td>
		      	      <td align="center"><?php echo $dist ?></td>
		      	      <td>&nbsp;</td>
		      	      <td>&nbsp;</td>
		      	   </tr>
		      	   <tr>
		      	   	<td align="center">
		      	   	   <select name="palabra[]" size="8" title="<?php echo $term_buscar ?>">
		      	   	      <option value="">--------------------</option>
		      	   	      <option value="">--------------------</option>
		      	   	      <option value="">--------------------</option>
		      	   	      <option value="">--------------------</option>
		      	   	      <option value="">--------------------</option>
		      	   	      <option value="">--------------------</option>
		      	   	      <option value="">--------------------</option>
		      	   	      <option value="">--------------------</option>
		      	   	   </select>
		      	   	</td>
		      	   	<td align="center">
		      	   	   <select name="distancia[]" size="8" disabled="true" title="<?php echo $dist_term_anterior ?>">
		      	   	      <option value="">--------------------</option>
		      	   	      <option value="">--------------------</option>
		      	   	      <option value="">--------------------</option>
		      	   	      <option value="">--------------------</option>
		      	   	      <option value="">--------------------</option>
		      	   	      <option value="">--------------------</option>
		      	   	      <option value="">--------------------</option>
		      	   	      <option value="">--------------------</option>
		      	   	   </select>
		      	   	</td>
		            <td>&nbsp;</td>
		      	   	<td>
		      	   	   <a href="#" onclick="subir()"><img src="../../imagenes/orden_asc.png" border="0" title="<?php echo $up ?>" /></a><br><br>
		      	   	   <a href="#" onclick="bajar()"><img src="../../imagenes/orden_desc.png" border="0" title="<?php echo $down ?>" /></a><br><br><br>
		      	   	   <a href="#" onclick="eliminar()"><img src="../../imagenes/papelera_ico.png" border="0" title="<?php echo $eliminar ?>" /></a>
		      	   	</td>
		      	   </tr>
		      	   <tr>
		      	      <td colspan="4">&nbsp;</td>
		      	   </tr>
		      	   <tr>
		      	      <td align="center"><?php echo $nuevo_term ?></td>
		      	      <td align="center"><?php echo $dist ?> <sub><i>(<?php echo $term_ant ?>)</i></sub></td>
		      	      <td><?php echo $dist_exacta ?></td>
		      	      <td>&nbsp;</td>
		      	   </tr>
		      	   <tr>
		      	      <td align="center"><input type="text" name="nuevoTermino" size="30" maxlength="100" title="<?php echo $mensaje63 ?>"/></td>
		      	      <td align="center"><input type="text" name="nuevaDistancia" size="10" maxlength="4"  title="<?php echo $dist_term_anterior ?>"/></td>
		      	      <td><input type="checkbox" name="distanciaexacta"  title="<?php echo $mensaje64 ?>"/></td>
		      	      <td><a href="#" onclick="anadirTermino()"><img src="../../imagenes/ok_tr.png" border="0" title="<?php echo $anadir ?>" /></a></td>
		      	   </tr>
		      	   <tr>
		      	      <td colspan="4">&nbsp;</td>
		      	   </tr>
		      	   <tr>
		      	      <td align="center">&nbsp;<?php echo $ent ?>:&nbsp;&nbsp;
		      	         <select name="entorno" title="<?php echo $ent ?>">
		      	            <option value="1">1 <?php echo $pal ?></option>
		      	            <option value="2">2 <?php echo $palabras ?></option>
		      	            <option value="3">3 <?php echo $palabras ?></option>
		         	        <option value="4">4 <?php echo $palabras ?></option>
		      	            <option value="5">5 <?php echo $palabras ?></option>
		      	         </select>
		      	      </td>
					  <td align="center">&nbsp;<?php echo $busq_donde?>:&nbsp;&nbsp;
		      	         <select name="donde_buscar" title="<?php echo $tit_busqueda ?>">
		      	            <option value="IzqDer"><?php echo $dchaizda ?></option>
		      	            <option value="Izquierda"><?php echo $izda ?></option>
		      	            <option value="Derecha"><?php echo $dcha ?></option>>
		      	         </select>
		      	      </td>
		           </tr>
		      	   <tr>
		      	      <td colspan="4">&nbsp;</td>
		      	   </tr>
		      	</table>
		    </td>
		  </tr>
		</table>
      </td>
   </tr>
   <tr>
      <td align="center" bgcolor="#FFFFFF" colspan="4"><p id="opciones"><?php echo $titulo_texto ?></p>
   </tr>
   <tr>
      <td  colspan="4">
<?php 
	$texto_buscador = $mensaje45." <br>".$mensaje65;
	include ("../../comun/buscador_textos.php");
?>
      </td>
   </tr>
   <tr>
   	  <td colspan="4">&nbsp;</td>
   </tr>
   <tr>
     <td>
   	   <input type="button" id="buscar_coloc" value="<?php echo $buscar_colocaciones ?>" onclick="buscarConcordancias();"/>&nbsp;&nbsp;
   	 </td>
     <td>
   	   <input type="button" value="<?php echo $limpiar_formulario ?>" onclick="document.formulario.reset();"/>&nbsp;&nbsp;
   	 </td>
   	 <td>
   	   <input type="button" value="<?php echo $boton_volver ?>" onclick="document.location='menu_colocaciones.php';"/>&nbsp;&nbsp;
   	 </td>
	 <td>
   	   <input type="button" value="<?php echo $ayuda ?>" onclick="document.location='../../ayuda/ayuda_concord.htm'" />&nbsp;&nbsp;
   	 </td>
   </tr>
</table>
<table border="0" width="100%" cellpadding="5" cellspacing="5">
   <tr>
      <td id="tempDiv" align="center">
      </td>
   </tr>
</table>
</form>
</p>
<script>
   document.formulario["palabra[]"].options.length=0;
   document.formulario["distancia[]"].options.length=0;
</script>
<br>

<!--<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_acceso_corpus.php">CORPUS</a> > <u><?php echo $concordancias ?></u></td>
   </tr>-->
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
