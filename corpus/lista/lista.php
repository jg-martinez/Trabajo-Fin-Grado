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

	// Carga de los datos del formulario en variables locales
	if (isset($_POST['texto'])) {$texto = $_POST['texto'];}
	else {$texto = "";}
	if (isset($_POST['columnas'])) {$columnas = $_POST['columnas'];}
	else {$columnas = "";}
	if (isset($_POST['orden'])) {$orden = $_POST['orden'];}
	else {$orden = "";}
	if (isset($_POST['sentido'])) {$sentido = $_POST['sentido'];}
	else {$sentido = "";}
	if (isset($_POST['frecuencia_hasta'])) {$frecuencia_hasta = $_POST['frecuencia_hasta'];}
	if (isset($_POST['frecuencia_desde'])) {$frecuencia_desde = $_POST['frecuencia_desde'];}
	
   //if ($_POST['esExcel'] != "")
   if (isset($_POST['esExcel']))
   {
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment; filename="ListaPalabras.xls"'); 
   	   
      // Conexion con la base de datos
      include ("../../comun/conexion.php");
      include("listar_palabras.php");
      
      $texto_sql="id_texto= '".$texto[0]."'";
      for ($i=1; $i<count($texto); $i++)
         $texto_sql .= " or id_texto='".$texto[$i]."'";

      $consulta= "SELECT id_texto, h_title, lang_usage, word_count FROM texto WHERE $texto_sql";
      $resultado = mysql_query($consulta) or die($eliminacion_incorrecta . mysql_error()); 

      $cabecera_auxiliar = "";
      while ($obj = mysql_fetch_object($resultado))
      {
         $cabecera_auxiliar .= "<td colspan='2'><b> $obj->h_title </b></td>\n";
      }
      
      if ($orden == "")
         $orden = "a.id_termino";
      if ($sentido == "")
         $sentido = "asc";
		 
	  // ---------- INICIO de la carga del listado de palabras ----------
      echo "\n<table border='1'><tr bgcolor='#D8D9A4'>$cabecera_auxiliar</tr><tr bgcolor='#D8D9A4'>";
         
      $html_cabecera_termino = "<td><b>".$term."</b></td>";
      $html_cabecera_frecuencia = "<td><b>".$frec."</b></a></td>";
      
      for ($i=0; $i<count($texto); $i++)
      {
         echo $html_cabecera_termino.$html_cabecera_frecuencia;
      }
      echo "</tr>\n";

		cargarListado($texto, $columnas, $orden, $sentido, $frecuencia_desde, $frecuencia_hasta);
      echo "</table>";
   }
   else 
   {
?>
<!-- lista.php ---------------------------------------------------------------------------

     Pagina que presenta el listado de palabras de un texto.

----------------------------------------------------------------------------------------------- -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Calíope</title>
	<script type="text/javascript" src="../../ajax/ajax.js" ></script>
	<!--<link rel="stylesheet" type="text/css" href="../../comun/estilo.css">-->
	<link rel="stylesheet" type="text/css" href="../../CSS/lista.css">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
	<meta http-equiv=Content-Type content="text/html; charset=utf-8">
	<meta content="MSHTML 6.00.2800.1498" name=GENERATOR>
	<script language="javascript">
   		function ordenarpor(campo, sentido)
		/*-------------------------------------------------------------------------------------------------
		Funcion que llama a cambiar la ordenacion de campos.

		ENT: campo   - campo de la tabla por el que se ordena
			 sentido - indica si se ordena de forma ascendente o descendente 
		SAL:
		-------------------------------------------------------------------------------------------------*/
   		{
   	   		document.formulario.esExcel.value = "";
   	   		document.formulario.sentido.value = sentido;
   	   		document.formulario.orden.value = campo;
   	   		document.formulario.target = "_self";
   	   		document.formulario.submit();
   		}

   		function exportarExcel ()
		/*-------------------------------------------------------------------------------------------------
		Funcion que llama a la exportacion del listado a una tabla de excel

		ENT: 
		SAL:
		-------------------------------------------------------------------------------------------------*/
   		{
   	   		document.formulario.esExcel.value = "true";
   	   		document.formulario.target = "_blank";
   	   		document.formulario.submit();
   		}

   		function modificarGlosario (texto, palabra, frecuencia)
		/*-------------------------------------------------------------------------------------------------
		Funcion que lanza la modifiacion de un termino en el glosario o su visualizacion

		ENT: texto      - identificador del texto
			 palabra    - palabra o termino a visualizar o modificar 
			 frecuencia - numero de veces encontrado en el texto
		SAL: 
		-------------------------------------------------------------------------------------------------*/
   		{
			if (confirm("Pulse 'Aceptar' si desea modificar el glosario. Si desea visualizar las palabras dentro del texto pulse 'Cancelar'"))
			{
				window.open ("../../glosario/operacion_glosario.php?arg_op=modificar&desdelista=si&termino=" + palabra,"modificarGlosario","scrollbars=yes,resizable=yes,location=no");
			}
			else
			{
				window.open("visualizar_texto.php?texto=" + texto + "&palabra=" + palabra + "&ocurr=" + frecuencia,"visualizarPalabra","scrollbars=yes,resizable=yes,location=no");
			}
   		}
		
		var palabras = new Array();

   		function botonDerecho(base, ev) {
   			var contextdiv = document.getElementById("contextdiv");
   			contextdiv.className = "divvisible";

   			contextdiv.style.top = (ev.clientY + document.body.scrollTop) + "px";
   			contextdiv.style.left = (ev.clientX + document.body.scrollLeft) + "px";

   			var datos_texto = base.split("_");
   			var html = "<table width='100%' border='1px' bordercolor='#800000' cellpadding='2px' cellspacing='0' id='contexttable'>";
			html += "<tr><td border='0'><a href=\"#\" onclick=\"window.open('http://buscon.rae.es/draeI/SrvltConsulta?TIPO_BUS=3&LEMA=" + datos_texto[1] + "','_blank')\"><?php echo $mostrar_rae ?></a></td></tr>";
			html += "<tr><td border='0'><a href=\"#\" onclick=\"window.open('http://www.webopedia.com/search/" + datos_texto[1] + "','_blank')\"><?php echo $mostrar_webopedia ?></a></td></tr>";

			if (palabras[base].lang == "esp")
				html += "<tr><td border='0'><a href=\"#\" onclick=\"window.open('http://es.wikipedia.org/wiki/" + datos_texto[1] + "','_blank')\"><?php echo $mostrar_wikipedia ?></a></td></tr>";
			else
				html += "<tr><td border='0'><a href=\"#\" onclick=\"window.open('http://en.wikipedia.org/wiki/" + datos_texto[1] + "','_blank')\"><?php echo $mostrar_wikipedia ?></a></td></tr>";
			
			if (palabras[base].existeEurowordnet)
				html += "<tr><td border='0'><a href=\"#\" onclick=\"window.open('../../glosario/operacion_glosario.php?arg_op=mostrar_eurowordnet&termino=" + datos_texto[1] + "&idioma=" + palabras[base].lang + "','_blank')\"><?php echo $mostrar_eurowordnet ?></a></td></tr>";
/*			else
				html += "<tr><td border='0'><a href=\"#\" onclick=\"window.open('../../glosario/operacion_glosario.php?arg_op=mostrar_eurowordnet&termino=" + datos_texto[1] + "&idioma=" + palabras[base].lang + "','_blank')\"><?php echo $mostrar_eurowordnet ?></a></td></tr>";
*/			
			html += "<tr><td border='0'><a href=\"#\" onclick=\"window.open('visualizar_texto.php?texto=" + datos_texto[0] + '&palabra=' + datos_texto[1] + '&ocurr=' + palabras[base].frecuencia +  "','visualizarPalabra','scrollbars=yes,resizable=yes,location=no')\"><?php echo $visualizar_en_texto ?></a></td></tr>";

   			if (eval(palabras[base].existeGlosario))
   	   			html += "<tr border='0'><td><a href=\"#\" onclick=\"window.open('../../glosario/operacion_glosario.php?arg_op=nuevo&desdelista=si&termino=" + datos_texto[1] + "&idioma=" + palabras[base].lang + "','modificarGlosario','scrollbars=yes,resizable=yes,location=no')\"><?php echo $anadir_al_glosario ?></a></td></tr>";
			else
  	   			html += "<tr border='0'><td><a href=\"#\" onclick=\"window.open ('../../glosario/operacion_glosario.php?arg_op=modificar&desdelista=si&termino=" + palabras[base].id_glosario + "','modificarGlosario','scrollbars=yes,resizable=yes,location=no')\"><?php echo $modificar_termino ?></a></td></tr>";

     		if (!eval(palabras[base].existeGlosario)) {
           		html += "<tr border='0'><td align='center' style='background-color:#800000;color:yellow;font-weight:bolder'><?php echo $acepciones ?></td></tr>";
        	   	html += "<tr border='0'><td align='center' id='acepcionesimage'><img border='0' src='../../imagenes/loading.gif' title='Cargando...' /></td></tr>";
     		}

			html += "<tr border='0'><td align='center' style='background-color:#800000;color:yellow;font-weight:bolder'><?php echo $conexts ?></td></tr>";
			html += "<tr border='0'><td align='center' id='contextimage'><img border='0' src='../../imagenes/loading.gif' title='Cargando...' /></td></tr>";
   			html += "</table>";

   			contextdiv.innerHTML = html;

   			buscar_contextos(datos_texto[1], datos_texto[0], palabras[base].lang);
			//if (eval(palabras[base].existeGlosario) != true) 
				//setTimeout ("buscar_acepciones('" + datos_texto[1] + "')",1000);

   			return false;
   		}
   		
   		//var palabras = new Array();

	   	// Lanza la busqueda de contexto.
   		function buscar_contextos(termino, texto, idioma) {
   			sendAjaxPostback ("<?php echo $_SESSION['application_url'];?>/glosario/ajax_buscar_contextos.php?session_id=<?php  echo session_id(); ?>&texto=" + texto + "&termino=" + termino + "&idioma=" + idioma, document.getElementById("contextimage"),tratarBuscarContextos);
   		}

	   	// Lanza la busqueda de acepciones.
   		function buscar_acepciones(termino) {
   			sendAjaxPostback ("<?php echo $_SESSION['application_url'];?>/glosario/ajax_buscar_acepciones.php?session_id=<?php  echo session_id(); ?>&termino=" + termino, document.getElementById("acepcionesimage"),tratarBuscarAcepciones);
   		}

	   	// Trata la respuesta de la busqueda de contextos
   		function tratarBuscarContextos(contenedor, respuesta) {
			var error = false;
   	   		eval(respuesta);
   	   		if (error) {
   	   	   		contenedor.style.textAlign = "center";
   	   	   		contenedor.innerHTML = errorMensaje;
   	   		} else {
   	   	   		var html = "<ul>";
   	   	   		for (var i=0; i<lineas.length; i++) {
   	   	   	   		html += lineas[i];

   	   	   	   		if (i < lineas.length -1)
						html += "<br>";
   	   	   		}
   	   	   		contenedor.style.textAlign = "left";

   	   	  		contenedor.innerHTML = html + "</ul>";
   	   		}

   	   		if (eval(palabras[base].existeGlosario) != true)
   	   			buscar_acepciones(termino); // Enlazamos con el siguiente paso, que es obtener las definiciones.
   		}

	   	// Trata la respuesta de la busqueda de contextos
   		function tratarBuscarAcepciones(contenedor, respuesta) {
			var error = false;
   	   		eval (respuesta);
   	   		if (error) {
   	   	   		contenedor.style.textAlign = "center";
   	   	   		contenedor.innerHTML = errorMensaje;
   	   		} else {
   	   	   		var html = "<ul>";
   	   	   		for (var i=0; i<lineas.length; i++) {
   	   	   	   		html += lineas[i];

   	   	   	   		if (i < lineas.length -1)
						html += "<br>";
   	   	   		}
   	   	   		contenedor.style.textAlign = "left";
   	   	  		contenedor.innerHTML = html + "</ul>";
   	   		}

   		}

   	</script>
</head>
<body onContextMenu='return false;'  onclick='document.getElementById("contextdiv").className="divoculto";'>
<div id="contextdiv" class="divoculto"></div>
<?php 
   if(tienePermisos("corpuslistaoperacion"))
   {
      include("listar_palabras.php");
?>
	<header>
		<h1><?php echo $titulo_listado_palabras ?></h1>
	</header>
<?php 
	  if($texto == '')
	  {
	     echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$no_texto_seleccionado."</p>";
	  }
	  else
	  {
	  	sort($texto);
?>

<form name="formulario" action="lista.php" method="post">
	<input type="hidden" name="esExcel" value="" />
	<input type="hidden" name="orden" value="<?php  echo $orden; ?>" />
	<input type="hidden" name="sentido" value="<?php  echo $sentido; ?>" />
	<input type="hidden" name="frecuencia_desde" value="<?php  echo $frecuencia_desde; ?>" />
	<input type="hidden" name="frecuencia_hasta" value="<?php  echo $frecuencia_hasta; ?>" />
<?php 
         for ($i=0; $i<count($texto); $i++)
         {
?>	<input type="hidden" name="texto[]" value="<?php  echo $texto[$i]; ?>" />
<?php 
         } 
?>
</form>
<nav>
         <input id="operacion" type="button" value="<?php echo $operaciones ?>"/>
         <input type="button" value="<?php echo $exportar_excel ?> " onclick="exportarExcel();"/><br>
         <input type="button" value="<?php echo $lista_palabras ?> " onclick="document.location='menu_lista.php';"/>
      </td>
	  </nav>
      <section>

<?php 
         // Conexion con la base de datos
         include ("../../comun/conexion.php");
         
         $texto_sql="id_texto= '".$texto[0]."'";
         for ($i=1; $i<count($texto); $i++)
         	$texto_sql .= " or id_texto='".$texto[$i]."'";

         $consulta= "SELECT id_texto, h_title, lang_usage, word_count FROM texto WHERE $texto_sql";
         $resultado = mysql_query($consulta) or die($eliminacion_incorrecta . mysql_error()); 

	     //"<p class=\"Info\"><img border=\"0\" src=\"../../imagenes/info.gif\"> ";
         //echo "<b>".$operacion."</b> ".$lista_palabras." <img border=\"0\" src=\"../../imagenes/separador2.gif\"> ";
         
         $cabecera_auxiliar = "";
         while ($obj = mysql_fetch_object($resultado))
         {
		 	// Cabecera de la pagina: tipo de operacion, texto
         	//echo "<b>".$titulo_texto.":</b> $obj->h_title <img border=\"0\" src=\"../../imagenes/separador2.gif\"> ";

         	// Cabecera de la pagina: idioma, numero de palabras
		 	//echo "<b>".$idiom.":</b> ";
	     	//if($obj->lang_usage == 'esp')
		        //echo $espanol." <img border=\"0\" src=\"../../imagenes/separador2.gif\"> ";
			 //else 
			    //echo $ingles." <img border=\"0\" src=\"../../imagenes/separador2.gif\"> ";
         	//echo "<b>".$frec.":</b> $frecuencia <img border=\"0\" src=\"../../imagenes/separador2.gif\"> ";
			//echo "<b>".$numero_palabras.":</b> $obj->word_count <img border=\"0\" src=\"../../imagenes/separador2.gif\"> ";

		 	$cabecera_auxiliar .= "<td colspan='2'><b> $obj->h_title </b></td>\n";
         }
			
         mysql_close($enlace); 

         // Cabecera de la pagina: fecha
		 if ($lg == "esp")
		 {
			 $fecha = getdate();
			 switch($fecha['wday'])
			 {
				case(1): $dia = "Lunes"; break;       case(2): $dia = "Martes"; break;
				case(3): $dia = "Mi&eacute;rcoles"; break;   case(4): $dia = "Jueves"; break;
				case(5): $dia = "Viernes"; break;     case(6): $dia = "S&aacute;bado"; break;
				case(0): $dia = "Domingo"; break;
			 }
			 switch($fecha['mon'])
			 {
				case(1): $mes = "Enero"; break;        case(2): $mes = "Febrero"; break;
				case(3): $mes = "Marzo"; break;        case(4): $mes = "Abril"; break;
				case(5): $mes = "Mayo"; break;         case(6): $mes = "Junio"; break;
				case(7): $mes = "Julio"; break;        case(8): $mes = "Agosto"; break;
				case(9): $mes = "Septiembre"; break;   case(10): $mes = "Octubre"; break;
				case(11): $mes = "Noviembre"; break;   case(12): $mes = "Diciembre"; break;
			 }
		 }
		 else
		 {
			$fecha = getdate();
			 switch($fecha['wday'])
			 {
				case(1): $dia = "Monday"; break;       case(2): $dia = "Tuesday"; break;
				case(3): $dia = "Wednesday"; break;   case(4): $dia = "Thursday"; break;
				case(5): $dia = "Friday"; break;     case(6): $dia = "Saturday"; break;
				case(0): $dia = "Sunday"; break;
			 }
			 switch($fecha['mon'])
			 {
				case(1): $mes = "January"; break;        case(2): $mes = "February"; break;
				case(3): $mes = "March"; break;        case(4): $mes = "April"; break;
				case(5): $mes = "May"; break;         case(6): $mes = "June"; break;
				case(7): $mes = "July"; break;        case(8): $mes = "August"; break;
				case(9): $mes = "September"; break;   case(10): $mes = "October"; break;
				case(11): $mes = "November"; break;   case(12): $mes = "December"; break;
			 }
		 }
		 //echo "<b>".$date.":</b> $dia, $fecha[mday]-$mes-$fecha[year],  ";
		 //if($fecha['hours'] < 10){ echo "0"; }
		 //echo "$fecha[hours]:";
		 //if($fecha['minutes'] < 10){ echo "0"; }
		 //echo "$fecha[minutes]:";
		 //if($fecha['seconds'] < 10){ echo "0"; }
		 //echo "$fecha[seconds] <img border=\"0\" src=\"../../imagenes/separador2.gif\"> ";
		 //echo "<img border=\"0\" src=\"../../imagenes/tit_principal_info.png\" align=\"bottom\" ></p>";

		 if ($orden == "")
		   $orden = "a.id_termino";
		 if ($sentido == "")
		   $sentido = "asc";
		 
		 //---------- INICIO de la carga del listado de palabras ----------
         echo "<p>$cabecera_auxiliar</p>\n<table width=100% border='1' cellpadding='4' cellspacing='0'><tr bgcolor='#2980b9'>";
         
         $imagen_filtrado = "<img src='../../imagenes/orden_$sentido.png' border='0' />";
         $sentido_auxiliar = ( $sentido == "asc")?"desc":"asc";

         $html_cabecera_termino = "<td id='termino'><a href='#' onclick='ordenarpor(\"a.id_termino\",";
         $html_cabecera_frecuencia = "<td id='frecuencia'><a href='#' onclick='ordenarpor(\"a.frecuencia\",";
         
         if ($orden == "a.id_termino")
         {
         	$html_cabecera_termino.="\"$sentido_auxiliar\")'><b>".$term."</b></a>&nbsp;$imagen_filtrado</td>";
         	$html_cabecera_frecuencia.="\"asc\")'><b>".$frec."</b></a>";
         }
         else
         {
         	$html_cabecera_termino.="\"asc\")'>".$term."</b></a>";
         	$html_cabecera_frecuencia.="\"$sentido_auxiliar\")'>".$frec."</b></a>&nbsp;$imagen_filtrado</td>";
         }

         for ($i=0; $i<count($texto); $i++)
         {
         	echo $html_cabecera_termino.$html_cabecera_frecuencia;
         }
         echo "</tr>\n";
		
			cargarListado($texto, $columnas, $orden, $sentido, $frecuencia_desde, $frecuencia_hasta);
		 
		 
         echo "</table>";
         //---------- INICIO de la carga del listado de palabras ----------			
?> 
	     
<?php 
      }
?>

<br>
<?php 
   }
   else  // El usuario NO tiene privilegios para acceder a la pagina
   {
	   echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   }
?>
</body>
</html>
<?php 
   } 
?>
