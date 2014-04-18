<?php 
session_start();header('Content-Type: text/html; charset=utf-8');ini_set("session.cookie_httponly", 1);

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

include ("../comun/permisos.php");
// recogida de los parametros que llegan via URL
if (isset($_GET['arg_op']))
{
	$arg_op = $_GET["arg_op"];
}
else
{
	$arg_op = $_POST['arg_op'];
}


if ($arg_op == "mostrar" || $arg_op == "eliminar")
{
	$termino = $_GET["termino"];
}
else if ($arg_op == "modif_elim")
{
	$inicial = $_GET["inicial"];
}
else if ($arg_op == "modificar")
{
	$termino = $_GET["termino"];
	if (isset($_GET["desdelista"]))
	{
		$desdelista = $_GET["desdelista"];
	}
	else
	{
		$desdelista = '';
	}
}
else if ($arg_op == "nuevo")
{
	if (isset($_POST['termino'])) $termino = $_POST['termino'];
	else $termino = $_GET['termino'];
	if (isset($_POST['idioma'])) $idioma = $_POST['idioma'];
	else $idioma = $_GET['idioma'];
	$desdelista = '';
}
else if ($arg_op == 'mostrar_eurowordnet')
{
	$termino = $_GET['termino'];
	$idiom = $_GET['idioma'];
}


// Si la operacion viene vacia. cogemos el valor de post 
if ($arg_op == "")
{
	$arg_op = $_POST['arg_op'];
	$termino = $_POST['termino'];
	$idioma = $_POST['idioma'];
}
?><!-- 
    Pagina que realiza las operaciones sobre el glosario de terminos
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Calíope</title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="../CSS/resultado.css">
	<!--<link rel="stylesheet" type="text/css" href="../comun/estilo.css">-->
	<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
	<meta content="MSHTML 6.00.2800.1498" name=GENERATOR>
<script type="text/javascript" src="../ajax/ajax.js" ></script>

<script type="text/JavaScript">

var ajaxenejecucion = false; // Indica si se esta ejecutando una peticion ajax, e impide que se ejecuten dos a la vez.
var esmodificacion = false;
var acepcion_base = "";
var contexto_base = "";
var categorias = new Array();
var contextos_contador = new Array();

categorias ["sustantivo"] = 0;
categorias ["verbo"] = 1;
categorias ["determinante"] = 2;
categorias ["adjetivo"] = 3;
categorias ["pronombre"] = 4;
categorias ["preposici\xF3n"] = 5;
categorias ["conjunci\xF3n"] = 6;
categorias ["adverbio"] = 7;
categorias ["interjeccion"] = 8;

postError = desbloquear_acepcion;

function trigger(id,outclass, inclass) {
	var el = document.getElementById(id);
	el.className = (el.className == outclass)?inclass:outclass;
}

// Sobreescribimos el tratamiento de errores por defecto para ajax.
function tratarError (contenedor, error) {
	alert (error);
	ajaxenejecucion = false;
	
	desbloquear_acepcion(); // Colocamos la funcion desbloquear_acepcion para ejecutar tras el procesamiento del error.
}

//Bloquea la zona de entrada de datos.
function bloquear_acepcion() {
	document.getElementById("definicion").disabled = true;
	document.getElementById("traduccion").disabled = true;
	document.getElementById("cat_gramatical").disabled = true;
}

// Desbloquea la zona de entrada de datos.
function desbloquear_acepcion() {
	document.getElementById("definicion").disabled = false;
	document.getElementById("traduccion").disabled = false;
	document.getElementById("cat_gramatical").disabled = false;
}


<?php
	// Se genera el codigo javascript si es necesario.
	if ($arg_op == 'nuevo' || $arg_op == 'modificar') {
?>

// Lanza la busqueda de contexto.
function buscar_contextos(base, termino, idioma) {
	var datos_base = base.split("_");
	window.open ("menu_buscar_nuevos_contextos.php?id_glosario=" + datos_base[0] + "&orden=" + datos_base[1] + "&termino=" + escape(termino) + "&idioma=" + idioma,"buscar_contextos","scrollbars=yes,resizable=yes,location=no");
}

// Prepara la zona para introducir los datos para la nueva acepcion
function preparar_alta () {
	if (ajaxenejecucion) {
		alert ("Existe un solicitud en curso.");
	} else {
		esmodificacion = false;
		trigger ("filaacepcion","acepcion_out","acepcion_in");
		var boton = document.getElementById("botonacepcion");
	
		if (document.getElementById("filaacepcion").className == "acepcion_out")
			boton.value = "Introducir acepci\u00f3n";
		else 
			boton.value = "Ocultar acepci\u00f3n";
	}
}

// Limpia la zona de entrada de los datos 
function cancelarEntrada() {
	acepcion_base = "";
	trigger ("filaacepcion","acepcion_out","acepcion_in");
	var boton = document.getElementById("botonacepcion");

	boton.value = "Introducir acepci\u00f3n";
	document.getElementById("cat_gramatical").selectedIndex = -1;
	document.getElementById("traduccion").value = "";
	document.getElementById("definicion").value = "";
}

// Funcion que, una vez realizada la peticion, envia la llamada al servidor para
// crear o modificar la acepcion.
function incluirEntrada(termino) {
	var cat_gram = document.getElementById("cat_gramatical");

	if (ajaxenejecucion) {
		alert ("Existe un solicitud en curso.");
	} else {
		ajaxenejecucion = true;

		// Comprobacion de que los datos existen.
		var ok = true;
		if (cat_gram.selectedIndex == -1) {
			ok = false;
			alert ("Debe seleccionar una categor\u00eda gramatical");
		}

		// Hacemos trim de la definicion para comprobar que haya datos. 
		if (ok && document.getElementById("definicion").value.replace(/^\s+/g,'').replace(/\s+$/g,'') == "") {
			ok = false;
			alert ("Debe introducir una definici\u00f3n");
		}

		if (ok) {
			
			bloquear_acepcion();
			
			if (acepcion_base == "")
				sendAjaxPostback("crear_acepcion.php?session_id=<?php  echo session_id(); ?>&id_glosario=" + termino + "&cat_gramatical=" + cat_gram.options[cat_gram.selectedIndex].value + "&traduccion=" + escape(document.getElementById("traduccion").value) + "&definicion=" + escape(document.getElementById("definicion").value),
					document.getElementById("mensajes"),tratarAltaAcepcion);
			else {
				var array_acepcion_base = acepcion_base.split("_");
				sendAjaxPostback("modificar_acepcion.php?session_id=<?php  echo session_id(); ?>&id_glosario=" + array_acepcion_base[0] + "&orden=" + array_acepcion_base[1] + "&cat_gramatical=" + cat_gram.options[cat_gram.selectedIndex].value + "&traduccion=" + escape(document.getElementById("traduccion").value) + "&definicion=" + escape(document.getElementById("definicion").value),
						document.getElementById("mensajes"),tratarModificarAcepcion);
			}
		} else {
			ajaxenejecucion = false;
		}
	}
}

// Acciones a realizar una vez dada de alta la acepcion 
function tratarAltaAcepcion (contenedor, respuesta) {
	var error = false;
	ajaxenejecucion = false;
	
	eval (respuesta);

	var cat_gram = document.getElementById("cat_gramatical");

	if (error)
		contenedor.innerText = errorMensaje;
	else {
		var definicion = document.getElementById("definicion").value;
		var traduccion = document.getElementById("traduccion").value;
		var cat_gramatical = cat_gram.options[cat_gram.selectedIndex].text;

		// El valor termino (linea 188 y 189) se genera en func_glosario.php, linea 81, ya que en esta pagina tenemos un codigo y no el termino en si, que se usa para buscar contextos.
		// Lo mismo ocurre para el idioma (linea 189) 
		var texto = "<span class='Info2' id='" + orden.id_glosario + "_" + orden.orden + "_acepcion'><table width='95%' border='0' onclick='trigger(\"" + orden.id_glosario + "_" + orden.orden + "\",\"contextos_out\",\"contextos_in\")'>";
		texto += "<tr><td width='15%'><b>Categor&iacute;a gramatical:</b></td><td width='15%' id='" + orden.id_glosario + "_" + orden.orden + "_categoria'>" + cat_gramatical;
		texto += "</td><td width='15%'><b>Traducci&oacute;n:</b></td><td width='15%' id='" + orden.id_glosario + "_" + orden.orden + "_traduccion'>" + traduccion + "</td>";
		
		texto += "<td rowspan='5' align='right' width='5%'>";
		
		texto += "<a href='#' onclick='event.cancelBubble=true;preparar_modificacion(\"" + orden.id_glosario + "_" + orden.orden + "\")'><img border='0' src='../imagenes/modificar_ico.gif' title='Modificar'><br><br>";
		texto += "<a href='#' onclick='event.cancelBubble=true;preparar_eliminacion(\"" + orden.id_glosario + "_" + orden.orden + "\")'><img border='0' src='../imagenes/papelera_ico.png' title='Eliminar'></a><br><br>";
		texto += "<a href='#' onclick='event.cancelBubble=true;buscar_contextos(\"" + orden.id_glosario + "_" + orden.orden + "\",\"" + termino + "\")'><img border='0' src='../imagenes/menu_lupa.gif' title='Buscar contextos'><br><br>";
		texto += "<a href='#' onclick='event.cancelBubble=true;refrescar_contextos(\"" + orden.id_glosario + "\",\"" + orden.orden + "\",\"" + termino + "\",\"" + termino_idioma + "\")'><img border='0' src='../imagenes/refresh.gif' title='Refrescar contextos'>";

		texto += "</td></tr><tr><td width='15%'><b>Definici&oacute;n</b></td>";
		texto += "<td colspan='3' id='" + orden.id_glosario + "_" + orden.orden + "_definicion'>" + definicion + "</td></tr><tr ";
		texto += "class='contextos_out' id='" + orden.id_glosario + "_" + orden.orden + "'><td colspan='4'><span id='" + orden.id_glosario + "_" + orden.orden + "_contextos'><table width='100%' class='contextos'><tr><td align='center'>No se ";
		texto += "encontraron contextos.</td></tr></table></span></td></tr></table></span><br>";

		if (document.getElementById("noacepciones") == null) {
			document.getElementById("acepciones").innerHTML += texto;
		} else {
			if (document.getElementById("noacepciones").className == "acepcion_out")
				document.getElementById("acepciones").innerHTML += texto;
			else {
				document.getElementById("noacepciones").className = "acepcion_out";
				document.getElementById("acepciones").innerHTML = texto;
			}
		}
		desbloquear_acepcion();
	}
}

// Carga los datos para la modificacion de la acepcion 
function preparar_modificacion(base) {
	if (ajaxenejecucion) {
		alert ("Existe un solicitud en curso.");
	} else {
		acepcion_base = base;
		
		trigger ("filaacepcion","acepcion_in","acepcion_in");
		document.getElementById("definicion").value = document.getElementById(base + "_definicion").innerText;
		document.getElementById("traduccion").value = document.getElementById(base + "_traduccion").innerText;
		document.getElementById("cat_gramatical").selectedIndex = categorias[document.getElementById(base + "_categoria").innerText.toLowerCase()];
	}
}

//Acciones a realizar una vez modificada la acepcion 
function tratarModificarAcepcion (contenedor, respuesta) {
	var error = false;
	ajaxenejecucion = false;
	eval (respuesta);

	var cat_gram = document.getElementById("cat_gramatical");

	if (error)
		contenedor.innerText = errorMensaje;
	else {
		trigger ("filaacepcion","acepcion_in","acepcion_in");
		document.getElementById(acepcion_base + "_definicion").innerText = document.getElementById("definicion").value;
		document.getElementById(acepcion_base + "_traduccion").innerText = document.getElementById("traduccion").value;
		document.getElementById(acepcion_base + "_categoria").innerText = cat_gram.options[cat_gram.selectedIndex].text;
		acepcion_base = "";
		cancelarEntrada();
		desbloquear_acepcion();
	}
}

// Confirma si se desea eliminar y realiza la peticion de eliminacion al servidor.
function preparar_eliminacion(base) {
	if (ajaxenejecucion) {
		alert ("Existe un solicitud en curso.");
	} else {
		if (confirm("Al eliminar la acepci\u00f3n se eliminaran los contextos asociados.\u00bfDesea continuar?")) {
			ajaxenejecucion = true;
			acepcion_base = base;
			var array_base = base.split("_");
			sendAjaxPostback("borrar_acepcion.php?session_id=<?php  echo session_id(); ?>&id_glosario=" + array_base[0] + "&orden=" + array_base[1], document.getElementById("mensajes"),
				tratarEliminarAcepcion);
		}
	}
}

//Acciones a realizar una vez modificada la acepcion 
function tratarEliminarAcepcion (contenedor, respuesta) {
	var error = false;
	ajaxenejecucion = false;
	eval (respuesta);

	if (error)
		contenedor.innerText = errorMensaje;
	else {
		trigger (acepcion_base + "_acepcion","acepcion_out","acepcion_out");
		acepcion_base = "";

		var borradas = false;
		for (var i=0; !borradas && i< document.getElementById("acepciones").getElementsByTagName('span').length; i++) {
			borradas = borradas || (document.getElementById("acepciones").getElementsByTagName('span')[i].className != "acepcion_out");
		}
		
		if (!borradas)
			document.getElementById("acepciones").innerHTML = "<span class='Info2' id='noacepciones'>No se encontraron acepciones</span>";
	}
}

// Funciones ajax para el borrado de contextos.
function eliminarContexto(base, idcontexto)
{
	if (!ajaxenejecucion) {
		ajaxenejecucion = true;
		contexto_base = base;
		if (confirm ("Se va a eliminar el contexto.¿Desea continuar?")) {
			var url = "borrar_contexto.php?session_id=<?php  echo session_id(); ?>&idcontexto=" + idcontexto;
			sendAjaxPostback(url, null, tratarEliminarContexto);
		} else {
			ajaxenejecucion = false;
		}
	} else {
		alert ("Ya hay una petici\u00f3n en curso.");
	}
}

// Acciones a realizar una vez eliminado un contexto.
function tratarEliminarContexto (contenedor, respuesta) {
	var error = false;
	ajaxenejecucion = false;
	eval (respuesta);

	// No hay else, pues ya viene codificado en la respuesta.
	if (error)
		contenedor.innerText = errorMensaje;
	else {
		if (contexto_base != "") {
			
			var num_contextos = contextos_contador[contexto_base]-1;

			// Si se han eliminado todos los contextos se pone el mensaje correspondiente.
			if (num_contextos == 0) {
				document.getElementById(contexto_base + "_contextos").innerHTML = "<table width='100%' class='contextos'><tr><td align='center'>No se encontraron contextos.</td></tr></table>";
			} else {
				contextos_contador[contexto_base] = num_contextos;
			} 
		}

		contexto_base = "";
	}
}

//Funciones ajax para refrescar contextos.
function refrescar_contextos(id_termino,orden,termino,idioma)
{
	if (!ajaxenejecucion) {
		ajaxenejecucion = true;
		contexto_base = id_termino + "_" + orden;
		var url = "refrescar_contexto.php?session_id=<?php  echo session_id(); ?>&id_termino=" + id_termino + "&orden=" + orden + "&termino=" + termino + "&idioma=" + idioma;
		sendAjaxPostback(url, null, tratarRefrescarContexto);
	} else {
		alert ("Ya hay una petici\u00f3n en curso.");
	}
}

// Acciones a realizar una vez eliminado un contexto.
function tratarRefrescarContexto (contenedor, respuesta) {
	ajaxenejecucion = false;

	String.prototype.startsWith = function(str) {return (this.match("^"+str)==str)};

	// No hay else, pues ya viene codificado en la respuesta.
	if (respuesta.startsWith("Error"))
		contenedor.innerText = errorMensaje;
	else if (respuesta == "")
		document.getElementById(contexto_base + "_contextos").innerHTML = "<table width='100%' class='contextos'><tr><td align='center'>No se encontraron contextos.</td></tr></table>";
	else
		document.getElementById(contexto_base + "_contextos").innerHTML = respuesta;

	contexto_base = "";
}

// Funcion encargada de recoger la respuesta de la invocaci0n via Ajax.
function postbackComplete( contenedor, respuesta) {
	var error = false;
	ajaxenejecucion = false;
	eval (respuesta);

	// No hay else, pues ya viene codificado en la respuesta.
	if (error)
		contenedor.innerText = errorMensaje;
}

<?php 
}
?>
function check_datos(data)
{    
   // Comprobar TERMINO (no vacio)
    if(data.termino1.value == "") 
   {
      alert("El T\u00c9RMINO 1 est\u00e1 vac\u00edo. Rellene el campo T\u00c9RMINO 1 del formulario.");
	  return false;
   }

   // Comprobar preposiciones
   if(data.prep_esp.value != "-" && data.prep_ing.value != "-")
   {
      alert("Ha introducido 2 preposiciones (una espa\u00f1ola y otra inglesa). Por favor, deseleccione una de ellas.");
	  return false;
   }

   // Si se han pasado todas las comprobaciones el formulario es valido
   return true;
}

function check_datos_tipo_relac(data)
{    
   // Comprobar TERMINO (no vacio)
    if(data.nombre_tipo.value == "" ) 
   {
      alert("El nombre de la relaci\xF3n est\u00e1 vac\u00edo. Introduzca un nombre en dicho campo.");
	  return false;
   }
   // Si se han pasado todas las comprobaciones el formulario es valido
   return true;
}

</script>
</head>
<body>
<?php 
if(tienePermisos("glosariooperacion"))
{
	include("func_glosario.php");
	include ("../historico/operaciones_historico.php");
	
	if( ($arg_op == 'modif_elim') && tienePermisos("glosariooperacionmodificar") )  //-- MODIFICAR/ELIMINAR TERMINO ----------------------
	{
            // muestra el termino seleccionado
		listar_terminos($inicial);
?>
<p align="center"><input type="button" class="boton long_93 boton_aceptar" value="      Aceptar " onclick="document.location='resultado.php?inicial=admin_termino';"/></p>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
	<tr>
		<td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
		<td class="Pie"><a href="../principal.htm">Principal</a> > <a href="resultado.php">Glosario</A> > <u>MODIFICAR/ELIMINAR T&Eacute;RMINO</u></td>
	</tr>
</table>
<?php 
	}
    else if( $arg_op == 'nuevo' && tienePermisos("glosariooperacionnuevo") )  //-- NUEVO TERMINO DEL GLOSARIO ------------------------
	{		
            // muestra el formulario para el alta de un nuevo termino
		administrar_termino ($termino, true, $desdelista, $idioma);

		if ($desdelista == "")
		{
?>
<br>
<table border="0" width="100%" style="border-top: 1 solid #FF0000">
	<tr>
		<td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
		<td class="Pie"><a href="../principal.htm">Principal</a> > <a href="resultado.php">Glosario</A> > <u>NUEVO T&Eacute;RMINO</u></td>
	</tr>
</table>
<?php 
		}
	}
	else if ($arg_op == 'imagen') // -- GUARDAR IMAGEN
	{
            // recogida de los parametros de la imagen via POST (formualario)
		$lim_tamano = $_POST['lim_tamano'];
		$termino = $_POST['termino'];
		$id_termino = $_POST['id_termino'];
		$id = $_POST['idioma'];
				
		if (isset($_FILES['archivo']['name'])) $binario_nombre = $_FILES['archivo']['name'];
		else $binario_nombre = '';
		if (isset($_FILES['archivo']['size'])) $binario_tamano = $_FILES['archivo']['size'];
		else $binario_tamano = '';
		if (isset($_FILES['archivo']['type'])) $binario_tipo = $_FILES['archivo']['type'];
		else $binario_tipo = '';
		if (isset($_FILES['archivo']['tmp_name'])) $binario_temporal = $_FILES['archivo']['tmp_name'];
		else $binario_temporal = '';
		
		if ($binario_tamano != 0) // se ha cargado una imagen
		{
			$i = 0;
			foreach ($_POST as $key => $valor) 
			{ 
				if (substr($key, 0, 9) == 'acepcion1') 
				{	
					$val = $valor;
					$i++;
				}
			}
			if ($i == 0 || $i > 1) // No se ha seleccionado ninguna acepcion o se ha seleccionado mas de una
			{
				//echo "no ha seleccionado ninguna acepcion o ha seleccionado mas de una para el t&eacute;rmino 1";
				echo "<p align=\"center\"><span class=\"titulo titulo_rojo\">$administracion_terms_glosario</span><br></br>";
				echo "<img border=\"0\" src=\"../imagenes/linea_horiz.gif\"></p>";
				echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>No ha seleccionado ninguna acepcion o ha seleccionado mas de una<br>Por favor, seleccione una &uacute;nica acepci&oacute;n</p>";
				
				echo "<table border=\"0\" align=\"center\"> <td align=\"center\"><br>";
				if (isset($_POST['continuar'])) // se ha seleccionado 'otro' para alguna de las palabras que forman el termino compuesto
				{
					if (isset($_POST['termino_comp1']))
					{
						$pal_comp = $_POST['termino_comp1'];
					}
					else
					{
						$pal_comp = $_POST['termino_comp2'];
					}
					echo "<input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      $boton_aceptar\" onclick=\"document.location='operacion_glosario2.php?arg_op=imagen_acepcion&termino=$termino&idioma=$id&id_termino=$id_termino&lim_tamano=$lim_tamano&pal_comp=$pal_comp&continuar=si'\"/>";
				}
				else
				{
					if (isset($_POST['modificar']))
					{
						echo "<input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      $boton_aceptar\" onclick=\"document.location='operacion_glosario2.php?arg_op=imagen_acepcion&termino=$termino&idioma=$id&id_termino=$id_termino&lim_tamano=$lim_tamano&modificar=si'\"/>";
					}
					else
					{
						echo "<input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      $boton_aceptar\" onclick=\"document.location='operacion_glosario2.php?arg_op=imagen_acepcion&termino=$termino&idioma=$id&id_termino=$id_termino&lim_tamano=$lim_tamano'\"/>";
					}
				}
				
				echo "&nbsp;&nbsp;&nbsp;<input type=\"button\" class=\"boton long_93 boton_cancelar\" value=\"	Finalizar\" onclick=\"document.location='resultado.php?inicial=admin_termino';\" />";
			    echo "</td></table>";
			}
			else // se ha seleccionado solo una acepcion
			{
				/* los tipos de formatos que se admiten son: jpg, gif y png. Estos pueden ser diferentes segun el navegador que se este utilizando: 
				- png  que segun del navegador que ulicemos puede ser:
				  en IE image/x-png  en Firefox y Mozilla image/png
				- jpg que puede tener como tipo
				  en IE image/pjpeg  en Firefox y Mozilla image/jpeg
				- gif que tiene como tipo image/gif en todos los navegadores */
				if ($binario_tipo=="image/x-png" OR $binario_tipo=="image/png")
				{
					$extension="image/png";
				}
				if ($binario_tipo=="image/pjpeg" OR $binario_tipo=="image/jpeg")
				{
					$extension="image/jpg";
				}
				if ($binario_tipo=="image/gif" OR $binario_tipo=="image/gif")
				{
					$extension="image/gif";
				}
				/* condicionamos la insercion a que la foto tenga nombre,
				un tamano distinto de cero y menor de limite establecido
				en el formulario y que la variable extension sea no nula	*/
				if ($binario_nombre != "" && $binario_tamano != 0 && $binario_tamano<=$lim_tamano && $extension !='')
				{
					/*reconversion de la imagen para meter en la tabla, se abre el fichero temporal en modo lectura "r" binaria"b" */
					$f1= fopen($binario_temporal,"rb");
					// se lee el fichero completo limitando la lectura al tamano de fichero		
					$foto_reconvertida = fread($f1, $binario_tamano);
					// se anteponen \ a las comillas que pudiera contener el fichero para evitar que sean interpretadas como final de cadena	
					$foto_reconvertida=addslashes($foto_reconvertida);
					fclose($f1);
					// Borra archivos temporales si es que existen
					@unlink($tmp_name);
					
					include ("../comun/conexion.php");
					
					$consulta = "UPDATE acepcion SET nombre='$binario_nombre', tamano='$binario_tamano', formato='$extension', imagen='$foto_reconvertida' WHERE id_glosario = '$id_termino' AND orden='$val'";
					$res = mysql_query($consulta) or die (mysql_error());
				}
				echo "<p align=\"center\"><span class=\"titulo titulo_rojo\">$administracion_terms_glosario</span><br></br>";
				echo "<img border=\"0\" src=\"../imagenes/linea_horiz.gif\"></p>";
				
				if (isset($_POST['modificar']))
				{
					echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>".$el_term."<b>$termino</b>".$mensaje3."</p>";
				}
				else
				{
					echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>".$el_term."<b>$termino</b>".$mensaje80."</p>";
				}
				
				if (isset($_POST['continuar'])) // se ha seleccionado 'otro' para alguna de las palabras que forman el termino compuesto
				{
					if (isset($_POST['termino_comp1']))
					{
						$pal_comp = $_POST['termino_comp1'];
					}
					else if (isset($_POST['termino_comp2']))
					{
						$pal_comp = $_POST['termino_comp2'];
					}
					else
					{
						$pal_comp = $_POST['pal_comp'];
					}
					$consulta = "SELECT id_glosario FROM glosario WHERE termino='$pal_comp'";
					$res = mysql_query($consulta) or die (mysql_error());
					if (mysql_num_rows($res) == 0) // el termino de verdad no existe en el glosario
					{
						echo "<p align=\"center\"><font size=\"4\"><b>".$el_term." <i>$pal_comp</i> no esta incluido en el glosario</b></font></p>";
							echo "<p align=\"center\">Si desea incluirlo, pulse aceptar, en caso contrario pulse finalizar</p>";
							echo "<table border=\"0\" align=\"center\"> <td align=\"center\" colspan=\"2\"><br>";
								echo "<input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      $boton_aceptar\" onclick=\"document.location='operacion_glosario.php?arg_op=nuevo&termino=$pal_comp&idioma=$id';\" />&nbsp;&nbsp;";
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
						echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_termino'\" /></p>";
				}
			}
		}
		else
		{
			echo "<p align=\"center\"><span class=\"titulo titulo_rojo\">$administracion_terms_glosario</span><br></br>";
			echo "<img border=\"0\" src=\"../imagenes/linea_horiz.gif\"></p>";
			if (isset($_POST['modificar']))
			{
				echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>".$el_term."<b>$termino</b>".$mensaje3."</p>";
			}
			else
			{
				echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>".$el_term."<b>$termino</b>".$mensaje80."</p>";
			}
			echo "<form action=\"operacion_glosario.php\" method=\"post\" name=\"formulario\">";
			echo "<input type=\"hidden\" name=\"arg_op\" value=\"nuevo\">";
			if (isset($_POST['continuar']))
			{
				if (isset($_POST['termino_comp1'])) // se ha seleccionado Otro para el termino 1 
				{
					$pal_term_comp = $_POST['termino_comp1'];	
				}
				else if (isset($_POST['termino_comp2']))
				{
					$pal_term_comp = $_POST['termino_comp2'];
				}
				echo "<input type=\"hidden\" name=\"termino\" value=\"$pal_term_comp\">";
				echo "<input type=\"hidden\" name=\"idioma\" value=\"$id\">";
				echo "<p align=\"center\"><font size=\"4\"><b>".$el_term." <i>$pal_term_comp</i> no esta incluido en el glosario</b></font></p>";
				echo "<p align=\"center\">Si desea incluirlo, pulse aceptar, en caso contrario pulse finalizar</p>";
				echo "<table border=\"0\" align=\"center\"> <td align=\"center\" colspan=\"2\"><br>";
					echo "<input type=\"submit\" class=\"boton long_93 boton_aceptar\" value=\"      $boton_aceptar\" />&nbsp;&nbsp;";
					echo "<input type=\"button\" class=\"boton long_93 boton_cancelar\" value=\"      Finalizar\" onclick=\"document.location='resultado.php?inicial=admin_termino';\" />";				
				echo "</td></table>";
			}
			else
			{
				echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_termino'\" /></p>";
			}
		}
		
	}
    else if($arg_op == 'mostrar')  //-- MOSTRAR TERMINO ----------------------------------------------
	{
        // muestra la info asociada al terminno
		mostrar_termino($termino);
?>
<br>

<!--<table border="0" width="100%" style="border-top: 1 solid #FF0000">
	<tr>
		<td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
		<td class="Pie"><a href="../principal.htm"><?php echo $menu_principal ?></a> > <a href="resultado.php"><?php echo $glosario ?></A> > <u><?php echo $mostrar_termino ?></u></td>
	</tr>
</table>-->
<?php 
	}
    else
	if( ($arg_op == 'eliminar') && tienePermisos("glosariooperacioneliminar") )  //-- ELIMINAR TERMINO --------------------------------
	{
		echo "<p align=\"center\"><span class=\"titulo titulo_rojo\">Eliminaci&oacute;n de T&eacute;rminos del Glosario</span><br>";
		echo "<img border=\"0\" src=\"../imagenes/linea_horiz.gif\" ></p>";
?>
<form action="operacion_glosario2.php" method="post">
	<input name="arg_op" type="hidden" value="eliminar">
	<input name="termino" type="hidden" value="<?php echo $termino;?>">
	<p align="center">
		Se dispone a ELIMINAR el t&eacute;rmino que se muestra en la parte inferior de esta p&aacute;gina. Pulse ACEPTAR si desea continuar con la eliminaci&oacute;n.<br><br>
		<table border="0">
			<tr>
				<td>
					<input type="submit" class="boton long_93 boton_aceptar" value="      Aceptar " /> &nbsp;&nbsp;
					<input type="button" class="boton long_93 boton_cancelar" value="      Cancelar " onclick="document.location='resultado.php?inicial=admin_termino';" />
				</td>
			</tr>
		</table><br></br>
	</p>
</form>
<?php 
	mostrar_termino($termino);
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
	<tr>
		<td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
		<td class="Pie"><a href="../principal.htm">Principal</a> > <a href="resultado.php">Glosario</A> > <u>ELIMINAR T&Eacute;RMINO</u></td>
	</tr>
</table>
<?php 
	}
    else
	if( ($arg_op == 'modificar') && tienePermisos("glosariooperacionmodificar") )  //-- MODIFICAR TERMINO --------------------------------
	{
            // muestra el formulario para modificar el termino
		administrar_termino ($termino, false, $desdelista, '');

	}
    else
	if($arg_op == 'mostrar_eurowordnet')  //-- MOSTRAR INFO DE UN TERMINO EN EUROWORDNET -------------
	{
		include ("../comun/conexion.php");
		
		$p = strtolower($termino);
		
		if ($idiom == "esp")
		{
			$consulta = "SELECT pos FROM eswn_variant WHERE word='$p'";
			$res = mysql_query($consulta);
			$obj = mysql_fetch_object($res);
		}
		else
		{
			$consulta = "SELECT offset FROM synsetword WHERE word='$p'";
			$res = mysql_query($consulta);
			$obj2 = mysql_fetch_object($res);
			
			$consulta = "SELECT pos FROM synset WHERE offset='$obj2->offset'";
			$res = mysql_query($consulta) or die (mysql_error());
			$obj = mysql_fetch_object($res);
		}
?>
		<table border="3" align="center" width="50%" bgcolor="#EFFBFB">
			<th colspan="2" bgcolor="#6D8FFF"><font size="3"><?php echo $termino_eurowordnet?></font></th>
			<tr>
				<td align="center"><b><?php echo $pal ?>:</b></td>
				<td align="center"><i><?php echo $termino ?></i></td>
			</tr>
			<tr>
				<td align="center"><b><?php echo $categoria_gramatical ?>:</b></td>
<?php
				if ($obj->pos == "n") echo "<td align=\"center\"><i>".$sustantivo."</i></td>";
				else if ($obj->pos == "v") echo "<td align=\"center\"><i>".$verbo."</i></td>";
				else if ($obj->pos == "a") echo "<td align=\"center\"><i>".$adjetivo."/".$adverbio."</i></td>";
				else echo "<td>&nbsp;</td>";
?>
			</tr>
			<tr>
				<td align="center"><b><?php echo $definicion_select ?>:</b></td>
				<td align="center">
<?php
				if ($idiom == "esp")
				{
					$consulta = "SELECT offset FROM eswn_variant WHERE word='$p'";
					$res = mysql_query($consulta);
				}
				else
				{
					$consulta = "SELECT offset FROM synsetword WHERE word='$p'";
					$res = mysql_query($consulta) or die (mysql_error());
				}
				
				while ($obj = mysql_fetch_object($res))
				{
					if ($idiom == "esp")
					{
						$consulta2 = "SELECT gloss FROM eswn_synset WHERE offset='$obj->offset'";
						$res2 = mysql_query($consulta2);
					}
					else
					{
						$consulta2 = "SELECT gloss FROM synset WHERE offset='$obj->offset'";
						$res2 = mysql_query($consulta2) or die (mysql_error());
					}
					while ($obj2 = mysql_fetch_array($res2))
					{
						if ($obj2["gloss"] != "")
						{
							echo "<i><font size=\"4\"><b>.</b></font>".$obj2["gloss"]."</i><br>";
						}
					}
				}
?>
				</td>
			</tr>
				<td align="center"><b><?php echo $rels_otros_terms ?>:</b></td>
<?php
		if ($idiom == "esp")
		{
			$consulta = "SELECT pos,offset FROM eswn_variant WHERE word='$p'";
			$res = mysql_query($consulta);
			$obj = mysql_fetch_object($res);
			
			$consulta = "SELECT relation,targetSynset FROM eswn_relation WHERE sourceSynset='$obj->offset'";
			$res = mysql_query($consulta);
			
			echo "<td align=\"center\">";
			while ($obj3 = mysql_fetch_object($res))
			{
				$consulta2 = "SELECT word FROM eswn_variant WHERE offset='$obj3->targetSynset'";
				$res2 = mysql_query($consulta2) or die (mysql_error());
				if (mysql_num_rows($res2) != 0)
				{
					$obj4 = mysql_fetch_object($res2);
					echo "<i><font size=\"4\"><b>.</b></font>".$obj3->relation.": ".$obj4->word."</i><br>";
				}
			}
		}
		else
		{
			$consulta = "SELECT offset FROM synsetword WHERE word='$p'";
			$res = mysql_query($consulta) or die (mysql_error());
			$obj2 = mysql_fetch_object($res);
			
			$consulta = "SELECT ptr, targetoffset FROM synsetptr WHERE sourceoffset='$obj2->offset'";
			$res = mysql_query($consulta) or die (mysql_error());
			
			echo "<td align=\"center\">";
			while ($obj3 = mysql_fetch_object($res))
			{
				$consulta = "SELECT word FROM synsetword WHERE offset='$obj3->targetoffset'";
				$res2 = mysql_query($consulta) or die (mysql_error());
				if (mysql_num_rows($res2) != 0)
				{
					$obj4 = mysql_fetch_object($res2);
					
					$consulta = "SELECT txt FROM pointer WHERE ptr='$obj3->ptr'";
					$res5 = mysql_query($consulta) or die (mysql_error());
					$obj5 = mysql_fetch_object($res5);
					
					echo "<i><font size=\"4\"><b>.</b></font>".$obj5->txt.": ".$obj4->word."</i><br>";
				}
			}
		}
		
		echo "</td>";
?>
		</table>
		<p class="Alerta"><img border="0" src="../imagenes/alerta2.gif"><br><b><font size="2"><?php echo $recordatorio ?></font></b></p>
<?php
		mysql_close($enlace);
	}
	else
	if ($arg_op == 'mostrar_acepciones')
	{
		$id_tipo_relacion = $_GET['id_tipo_relacion'];
		$term1 = $_GET['term1'];
		$term2 = $_GET['term2'];
		$particula = $_GET['particula'];
		$nt = $_GET['nt'];
		$acc = $_GET['acc'];
		$id_relacion = $_GET['id_relacion'];
		
                // procede al alta de una nueva relacion
		alta_relacion($id_tipo_relacion, $term1, $term2, $particula, $nt, $acc, $id_relacion);
	}
	else
	if($arg_op == 'relacion')  //-- ADMINISTRAR RELACION ---------------------------------------------
	{
		echo "<p align=\"center\"><span class=\"titulo titulo_rojo\">".$administracion_rels_terms."</span><br>";
		echo "<img border=\"0\" src=\"../imagenes/linea_horiz.gif\" ></p>";

                // recoge del formulario los datos introducidos
		if (isset($_POST['tipo']))
		$tipo = $_POST['tipo']; // nombre de la relacion
		else $tipo = '';
		if (isset($_POST['termino1'])) $termino1 = $_POST['termino1'];
		else $termino1 = '';
		if (isset($_POST['termino2'])) $termino2 = $_POST['termino2'];
		else $termino2 = '';
		if (isset($_POST['particula'])) $part = $_POST['particula'];
		else $part = '';
		if (isset($_POST['nota'])) $nota = $_POST['nota'];
		else $nota = '';
		if (isset($_GET['accion'])) $accion = $_GET['accion'];
		else $accion = "";
		//$prep_esp = $_POST['prep_esp'];
		//$prep_ing = $_POST['prep_ing'];
		//$termino3 = $_POST['termino3'];

		$termino1 = convertirMinusculas($termino1);
		$termino2 = convertirMinusculas($termino2);
		//$termino3 = convertirMinusculas($termino3);
		

		if($accion == '')  //-- ALTA DE RELACION -----------------------------------------------
		{
			$acc = "";
			$id_relacion = "";
			alta_relacion($tipo, $termino1, $termino2, $part, $nota, $acc, $id_relacion);
		}
		else if ($accion == "modificar")  //-- MODIFICAR RELACION ---------------------------------------------------
		{
			if ($tipo == '' && $termino1 == '' && $termino2 == '' && $part == '')
			{
				echo "<p class=\"Info\" align=\"center\">".$atencion.": Debe rellenar al menos <b> uno de los 4 </b> campos.</p>";
				echo "<form action=\"resultado.php?inicial=admin_relacion\" method=\"post\" name=\"form_rel\">";
				echo "<table align=\"center\" border=\"0\" width=\"80%\">";
				echo "<p align=\"center\"><input type=\"submit\" class=\"boton long_93 boton_aceptar\" value=\"      $boton_volver\" />&nbsp;&nbsp;";
				echo "<input type=\"button\" class=\"boton long_93 boton_cancelar\" value=\"      $boton_cancelar\" onclick=\"document.location='resultado.php';\" /></p>";
				echo "</table>";
				echo "</form>";
			}
			else
			{
				listar_relaciones($tipo, $termino1, $termino2, $part, $nota);
			}
		}
		else //-- ELIMINAR RELACION ---------------------------------------------------
		{
			
		}
?>
<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
	<tr>
		<td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
		<td class="Pie"><a href="../principal.htm">Principal</a> > <a href="resultado.php">Glosario</A> > <u>ADMINISTRAR RELACI&Oacute;N</u></td>
	</tr>
</table>
<?php 
	}
	else
	
	if($arg_op == 'eliminar_relacion')  //-- ELIMINAR RELACION ---------------------------------------------
	{
		$id_relacion = $_GET['relacion'];

		echo "<p align=\"center\"><span class=\"titulo titulo_rojo\">".$eliminacion_rels_terms."</span><br>";
		echo "<img border=\"0\" src=\"../imagenes/linea_horiz.gif\" ></p>";
?>
<p align="center"><?php echo $mensaje73 ?> <b><?php echo $boton_aceptar ?></b><?php echo $mensaje74 ?>:</p>
<?php 
		mostrar_relacion($id_relacion);
?>
<br>
	<form action="operacion_glosario2.php" method="post">
		<input name="arg_op" type="hidden" value="eliminar_relacion">
		<input name="id_relacion" type="hidden" value="<?php echo $id_relacion;?>">
		<table align="center" border="0">
			<tr>
				<td align="center">
					<input type="submit" class="boton long_93 boton_aceptar" value="      <?php echo $boton_aceptar ?> " />&nbsp;&nbsp;
					<input type="button" class="boton long_93 boton_cancelar" value="      <?php echo $boton_cancelar ?> " onclick="document.location='resultado.php?inicial=admin_relacion';" />&nbsp;&nbsp;
				</td>
			</tr>
		</table>
	</form>
<br>
<table border="0" width="100%" style="border-top: 1 solid #FF0000">
	<tr>
		<td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
		<td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="resultado.php"><?php echo $glosario ?></A> > <u><?php echo $eliminacion_rels_terms ?></u></td>
	</tr>
</table>

<?php 
	}
    else
	if($arg_op == 'modificar_relacion')  //-- MODIFICAR RELACION ---------------------------------------------
	{
		$id_relacion = $_GET['relacion'];

		echo "<p align=\"center\"><span class=\"titulo titulo_rojo\">".$modificacion_rels_terms."</span><br>";
		echo "<img border=\"0\" src=\"../imagenes/linea_horiz.gif\" ></p>";

		/* Conexion con la base de datos */
		include ("../comun/conexion.php");

		/* Obtenemos los datos de la BD */
	    $consulta = "SELECT a.id_relacion, a.id_tipo_relacion, a.particula, a.nota,b.termino id_termino_1,c.termino id_termino_2, a.usuario_alta,a.fecha_alta,a.usuario_modificacion,a.fecha_modificacion, d.nombre_tipo FROM relacion a left join tipo_relacion d on a.id_tipo_relacion=d.id_tipo_relacion ";
	    $consulta .= "left join glosario b on a.id_termino_1=b.id_glosario left join glosario c on a.id_termino_2=c.id_glosario where a.id_relacion='$id_relacion'";
		
		$res = mysql_query($consulta);

		mysql_close($enlace);

		/* AQUI SE HACE LA MODIFICACION DE LOS DATOS */

		$fila = mysql_fetch_assoc($res);
			
		$id_tipo_relacion = $fila["id_tipo_relacion"];
		$termino1 = $fila["id_termino_1"];
		$termino2 = $fila["id_termino_2"];
		//$prep_esp = $fila["prep_esp"];
		//$prep_ing = $fila["prep_ing"];
		$part = $fila['particula'];
		$nt = $fila['nota'];
?>
<form action="operacion_glosario2.php" method="post" name="formulario" onSubmit='return check_datos(formulario);'>
	<input type="hidden" name="arg_op" value="modificar_relacion">
	<input type="hidden" name="id_relacion" value="<?php echo $id_relacion;?>">
	<input type="hidden" name="ant_termino1" value="<?php echo $termino1;?>">
	<input type="hidden" name="ant_termino2" value="<?php echo $termino2;?>">
	<input type="hidden" name="ant_tipo" value="<?php echo $id_tipo_relacion;?>">
<p align="center">
<table border="0">
	<tr>
		<td><?php echo $tipo ?></td>
		<td>
			<select name="tipo" size="1" title="<?php echo $tipo ?>">
			<optgroup label="Relaciones Jer&aacute;rquicas"></optgroup>
			<optgroup label="-> Hiponimia">

<?php 
		/* Conexion con la base de datos */
		include ("../comun/conexion.php");
		
		$consulta = "SELECT id_tipo_relacion FROM relacion WHERE id_relacion='$id_relacion'";
		$res = mysql_query($consulta);
		$obj2 = mysql_fetch_object($res);

		/* Obtenemos los datos de la BD */
		$consulta = "SELECT id_tipo_relacion,nombre_tipo FROM tipo_relacion WHERE tipo_rel='hiponimia'";
		$res = mysql_query($consulta);

		while($obj = mysql_fetch_object($res))
		{
			if ($obj->id_tipo_relacion == $obj2->id_tipo_relacion) 
			{
?> 				<option value="<?php echo $obj->id_tipo_relacion ?>" selected="selected"><?php echo $obj->nombre_tipo ?></option>				
<?php 
			}
			else
			{
?> 			    <option value="<?php echo $obj->id_tipo_relacion ?>"><?php echo $obj->nombre_tipo ?></option>
			
<?php
			}
		}
?>
		</optgroup>
		<optgroup label="-> Meronimia">
<?php
		
		/* Obtenemos los datos de la BD */
		$consulta = "SELECT id_tipo_relacion,nombre_tipo FROM tipo_relacion WHERE tipo_rel='meronimia'";
		$res = mysql_query($consulta);

		while($obj = mysql_fetch_object($res))
		{
			if ($obj->id_tipo_relacion == $obj2->id_tipo_relacion) 
			{
?> 				<option value="<?php echo $obj->id_tipo_relacion ?>" selected="selected"><?php echo $obj->nombre_tipo ?></option>				
<?php
			}
			else 
			{
?> 				<option value="<?php echo $obj->id_tipo_relacion ?>"><?php echo $obj->nombre_tipo ?></option>
<?php
			}
		}
?>
		</optgroup>
		<optgroup label="Relaciones No Jer&aacute;rquicas">
		</optgroup>
		<optgroup label="-> De Colocaci&oacute;n (Ad Hoc)">
<?php
		/* Obtenemos los datos de la BD */
		$consulta = "SELECT id_tipo_relacion,nombre_tipo FROM tipo_relacion WHERE tipo_rel='colocacion'";
		$res = mysql_query($consulta);

		while($obj = mysql_fetch_object($res))
		{
			if ($obj->id_tipo_relacion == $obj2->id_tipo_relacion) 
			{
?> 				<option value="<?php echo $obj->id_tipo_relacion ?>" selected="selected"><?php echo $obj->nombre_tipo ?></option>				
<?php 
			}
			else 
			{
?> 				<option value="<?php echo $obj->id_tipo_relacion ?>"><?php echo $obj->nombre_tipo ?></option>
<?php
			}
		}
?>
		</optgroup>
<?php

		mysql_close($enlace);
?>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php echo $term ?> 1:</td>
		<td><input name="termino1" size="50" title="<?php echo $term ?> 1" value="<?php echo $termino1;?>"></td>
	</tr>
	<tr>
		<td><?php echo $particula ?>:</td>
		<td><input name="particula" size="50" title="<?php echo $particula ?>" value="<?php echo $part ?>"></td>
	</tr>
	<tr>
		<td><?php echo $term ?> 2:</td>
		<td><input name="termino2" size="50" title="<?php echo $term ?> 2" value="<?php echo $termino2;?>"></td>
	</tr>
	<tr>
		<td><?php echo $nota ?>:</td>
		<td><input name="nota" size="50" title="<?php echo $nota ?> 2" value="<?php echo $nt;?>"></td>
	</tr>
	<tr>
		<td align="center" colspan="2"><br>
			<input type="submit" class="boton long_93 boton_aceptar" value="      <?php echo $boton_aceptar ?> " />&nbsp;&nbsp;
			<input type="button" class="boton long_93 boton_cancelar" value="      <?php echo $boton_cancelar ?> " onclick="document.location='resultado.php?inicial=admin_relacion';" />&nbsp;&nbsp;
			<input type="button" class="boton" value=" <?php echo $limpiar_formulario ?> " onclick="document.formulario.reset();"/>&nbsp;&nbsp;
		</td>
	</tr>
</table>
</p>
</form>


<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
	<tr>
		<td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
		<td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="resultado.php"><?php echo $glosario ?></A> > <u><?php echo $modificacion_rels_terms ?></u></td>
	</tr>
</table>

<?php 
	}
    else
	if($arg_op == 'nuevo_tipo_relac')  //-- NUEVO TIPO RELACION ---------------------------------------------
	{
?>
<p align="center">
	<span class="titulo titulo_rojo"><?php echo $nuevo_tipo_rels ?></span><br>
	<img border="0" src="../imagenes/linea_horiz.gif">
</p>

<form action="operacion_glosario2.php" method="post" name="form_tipo_relac" onSubmit='return check_datos_tipo_relac(form_tipo_relac);'>
	<input type="hidden" name="arg_op" value="nuevo_tipo_relacion">
<p align="center">
<table border="0">
	<tr>
		<td><?php echo $tipo_rel ?>:</td>
		<td><input name="nombre_tipo" size="50" title="<?php echo $tipo_rel ?>"></td>
	</tr>
	<tr>
		<td><?php echo $descripcion ?>:</td>
		<td><input name="descripcion_relacion" size="50" title="<?php echo $descripcion ?>"></td>
	</tr>
	<tr>
		<td align="center" colspan="2">
			<br><input type="submit" class="boton long_93 boton_aceptar" value="      <?php echo $boton_aceptar ?> " />&nbsp;&nbsp;
			<input type="button" class="boton long_93 boton_cancelar" value="      <?php echo $boton_cancelar ?> " onclick="document.location='resultado.php?inicial=admin_tipo_relacion';" />&nbsp;&nbsp;
			<input type="button" class="boton" value=" <?php echo $limpiar_formulario ?> " onclick="document.form_tipo_relac.reset();"/>&nbsp;&nbsp;
		</td>
	</tr>
</table>
</p>
</form>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
	<tr>
		<td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
		<td class="Pie"><a href="../principal.htm">Principal</a> > <a href="resultado.php">Glosario</A> > <u>NUEVO TIPO DE RELACI&Oacute;N</u></td>
	</tr>
</table>
<?php 
	}
    else
	if($arg_op == 'eliminar_tipo_relac')  //-- ELIMINAR TIPO RELACION --------------------------------------------
	{
		$id_tipo_relacion = $_GET['tipo_relacion'];
?>

<p align="center"><span class="titulo titulo_rojo"><?php echo $eliminacion_tipos_rels ?></span><br>
<img border="0" src="../imagenes/linea_horiz.gif"></p>

<?php
	// Se comprueba si existe alguna relacion que sea del tipo que se desea eliminar
	// Conexion con la base de datos 
    include ("../comun/conexion.php");
		 
	$consulta = "SELECT id_tipo_relacion FROM relacion WHERE id_tipo_relacion = '$id_tipo_relacion'";
	$res = mysql_query($consulta) or die (mysql_error());
	
	if (mysql_num_rows($res) == 0) // No hay relaciones de este tipo
	{
?>
	<p align="center"><?php echo $mensaje75 ?>:</p>
	<p align="center">
		<table align="center" border="0" width="330" style="border: 1 dashed #CC0000" bgcolor="#FFFF99" cellspacing="5">
		<?php 
			/* Conexion con la base de datos */
			include ("../comun/conexion.php");

			/* Obtenemos los datos de la BD */

			$consulta = "SELECT id_tipo_relacion,nombre_tipo,descripcion FROM tipo_relacion WHERE id_tipo_relacion = '$id_tipo_relacion'";
			$res = mysql_query($consulta);

			$fila = mysql_fetch_assoc($res);

			echo "<tr><td width=\"40%\"><b>".$ident.":</b></td><td>".$fila["id_tipo_relacion"]."</td></tr>";
			echo "<tr><td width=\"40%\"><b>".$tipo_rel.":</b></td><td>".$fila["nombre_tipo"]."</td></tr>";
			echo "<tr><td width=\"40%\"><b>".$descripcion.":</b></td><td>".$fila["descripcion"]."</td></tr>";
			
			mysql_close($enlace);
		?>
		</table>
	</p>

	<form action="operacion_glosario2.php" method=post>
		<input name="arg_op" type="hidden" value="eliminar_tipo_relacion">
		<input name="id_tipo_relacion" type="hidden" value="<?php echo $id_tipo_relacion;?>">
		<table align="center" border="0">
			<tr>
				<td align="center">
					<input type="submit" class="boton long_93 boton_aceptar" value="      <?php echo $boton_aceptar ?> " />&nbsp;&nbsp;
					<input type="button" class="boton long_93 boton_cancelar" value="      <?php echo $boton_cancelar ?> " onclick="document.location='resultado.php?inicial=admin_tipo_relacion';" />
			</tr>
		</table>
	</form>

<?php
	}
	else // existe alguna relacion de este tipo
	{
		// Conexion con la base de datos 
      	 include ("../comun/conexion.php");
	
		$consulta = "SELECT nombre_tipo, descripcion FROM tipo_relacion WHERE id_tipo_relacion='$id_tipo_relacion'";
		$res = mysql_query($consulta) or die (mysql_error());
		$obj = mysql_fetch_object($res);
?>
		<p align="center"><?php echo $mensaje120 ?></p>
		<p align="center">
	    <table border="0" width="330" style="border: 1 dashed #CC0000" bgcolor="#FFFF99" cellspacing="5">
			<tr>
				<td width="20%"><b><?php echo $tipo_texto ?>:</b></td>
				<td width="80%"><?php echo $obj->nombre_tipo;?></td>
			</tr>
			<tr>
				<td width="20%"><b><?php echo $descripcion ?>:</b></td>
				<td width="80%"><?php echo $obj->descripcion;?></td>
			</tr>
		</table>
		</p>
<?php		
		echo "<p align=\"center\">";
		echo "<table border=\"0\" width=\"500\" style=\"border: 1 dashed #CC0000\" bgcolor=\"#FFFF55\" cellspacing=\"5\">";
		echo "<tr><td align=\"center\">";
		echo "<b>".$atencion."</b>".$mensaje121."<b>" . $obj->nombre_tipo . "</b>.<br>";
		echo $mensaje122;
		echo "</td></tr>";
		
		// Conexion con la base de datos 
      	 include ("../comun/conexion.php");
	
		$consulta = "SELECT id_termino_1, id_termino_2, particula, nota FROM relacion WHERE id_tipo_relacion='$id_tipo_relacion'";
		$res = mysql_query($consulta) or die (mysql_error());
		
		while ($obj = mysql_fetch_object($res))
		{
			$consulta = "SELECT termino FROM glosario WHERE id_glosario='$obj->id_termino_1'";
			$res2 = mysql_query($consulta) or die (mysql_error());
			$obj2 = mysql_fetch_object($res2);
			
			$consulta = "SELECT termino FROM glosario WHERE id_glosario='$obj->id_termino_2'";
			$res3 = mysql_query($consulta) or die (mysql_error());
			$obj3 = mysql_fetch_object($res3);
			
			echo "<tr><td align=\"center\">".$obj2->termino." ".$obj->particula." ".$obj3->termino."</td></tr>";
		}
		
		echo "<tr><td align=\"center\">";
		echo "<b>".$mensaje123."</b><br> ".$mensaje23.$mensaje124;
		echo "</td></tr>";
		echo "</table></p>";
			
			echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_tipo_relacion';\" /></p>";
	}
?>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
	<tr>
		<td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
		<td class="Pie"><a href="../principal.htm">Principal</a> > <a href="resultado.php">Glosario</A> > <u>ELIMINAR TIPO DE RELACI&Oacute;N</u></td>
	</tr>
</table>

<?php 
	}
    else
	if($arg_op == 'modificar_tipo_relac')  //-- MODIFICAR TIPO RELACION ---------------------------
	{
		$id_tipo_relacion = $_GET['tipo_relacion'];

		echo "<p align=\"center\"><span class=\"titulo titulo_rojo\">".$modificacion_tipos_rels."</span><br>";
		echo "<img border=\"0\" src=\"../imagenes/linea_horiz.gif\" ></p>";
			
		/* Conexion con la base de datos */
		include ("../comun/conexion.php");
		
		/* Obtenemos los datos de la BD */
		$consulta = "SELECT nombre_tipo, descripcion FROM tipo_relacion WHERE id_tipo_relacion = '$id_tipo_relacion'";
		$res = mysql_query($consulta) or die (mysql_fallo());
		
		/* AQUI SE HACE LA MODIFICACION DE LOS DATOS */
		$fila = mysql_fetch_assoc($res);
		
		$nombre_tipo = $fila["nombre_tipo"];
		$descripcion_relacion = $fila["descripcion"];
		
		mysql_close($enlace);
?>
<form action="operacion_glosario2.php" method="post" name="form_tipo_relac2" onSubmit='return check_datos_tipo_relac(form_tipo_relac2);'>
	<input type="hidden" name="arg_op" value="modificar_tipo_relacion">
	<input type="hidden" name="ant_id_tipo_relacion" value="<?php echo $id_tipo_relacion;?>">
	<input type="hidden" name="id_tipo_relacion" value="<?php echo $id_tipo_relacion;?>">
<p align="center">
	<table border="0">
		<tr>
			<td><?php echo $tipo_rel ?>:</td>
			<td><input name="nombre_tipo" size="50" title="<?php echo $tipo_rel ?>" value="<?php echo $nombre_tipo;?>"></td>
		</tr>
		<tr>
			<td><?php echo $descripcion ?>:</td>
			<td><input name="descripcion_relacion" size="50" title="<?php echo $descripcion ?>" value="<?php echo $descripcion_relacion;?>"></td>
		</tr>
		<tr>
			<td align="center" colspan="2"><br>
				<input type="submit" class="boton long_93 boton_aceptar" value="      <?php echo $boton_aceptar ?> " />&nbsp;&nbsp;
				<input type="button" class="boton long_93 boton_cancelar" value="      <?php echo $boton_cancelar ?> " onclick="document.location='resultado.php?inicial=admin_tipo_relacion';" />&nbsp;&nbsp;
				<input type="button" class="boton" value=" <?php echo $limpiar_formulario ?> " onclick="document.form_tipo_relac2.reset();"/>&nbsp;&nbsp;
			</td>
	</table>
</p>
</form>
<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
	<tr>
		<td width="190">
			<img border="0" src="../imagenes/tit_principal_pie.gif">
		</td>
		<td class="Pie">
			<a href="../principal.htm">Principal</a> > <a href="resultado.php">Glosario</A> > <u>MODIFICAR TIPO DE RELACI&Oacute;N</u>
		</td>
	</tr>
</table>
<?php 
	}
}
else
{
	echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>ACCESO INV&Aacute;LIDO a la p&aacute;gina.</p>";
}
?>
</body>
</html>