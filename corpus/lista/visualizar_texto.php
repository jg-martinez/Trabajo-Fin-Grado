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
?>

<!-- visualizar_texto.php ---------------------------------------------------------------------------

     Pagina que muestra parrafos con las ocurrencias de una determinada palabra.

----------------------------------------------------------------------------------------------- -->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>

<head><title></title>
   <link rel="stylesheet" type="text/css" href="../../comun/estilo.css">
   <meta http-equiv=Content-Type content="text/html; charset=windows-1252">
   <meta content="MSHTML 6.00.2800.1498" name=GENERATOR>
</head>

<body>

<?php 
   if(tienePermisos("corpuslistavisualizartexto"))
   {
      include("buscar_palabra.php");

?>

<p align="center"><span class="titulo titulo_rojo"><?php echo $titulo_listado_palabras ?></span><br>
<img border="0" src="../../imagenes/linea_horiz.gif" ></p>

<?php 
	// Obtenemos los datos del formulario

	$palabra = $_GET['palabra'];
	$texto = $_GET['texto'];

	include ("../../comun/conexion.php");

	$consulta= "SELECT id_texto, h_title, lang_usage, edition_stmt, body FROM texto WHERE texto.id_texto='$texto'";
	$resultado = mysql_query($consulta) or die($no_consulta_db . mysql_error()); 

	$obj = mysql_fetch_object($resultado);
	mysql_close($enlace); 

	// Cabecera de la pagina: tipo de operacion, termino a buscar, texto

	echo "<p class=\"Info\"><img border=\"0\" src=\"../../imagenes/info.gif\"> "; 
	echo "<b>".$operacion."</b>".$listado_pals_ocurrencias_terms."<img border=\"0\" src=\"../../imagenes/separador2.gif\"> ";
	echo "<b>".$pal.":</b> $palabra <img border=\"0\" src=\"../../imagenes/separador2.gif\"> ";            
	echo "<b>".$titulo_texto.":</b> [$obj->id_texto] $obj->h_title <img border=\"0\" src=\"../../imagenes/separador2.gif\"> ";
	echo "<b>".$idiom.":</b> ";

	// Cabecera de la pagina: idioma del texto

	if($obj->lang_usage == 'esp')
	{
	 echo "Espa&ntilde;ol";
	}
	else
	{ 
	 echo "Ingl&eacute;s";
	}
	
	echo " <img border=\"0\" src=\"../../imagenes/separador2.gif\"> ";

	// Cabecera de la pagina: fecha en la que se realizo la concordancia
	
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
	else // el idioma esta en ingles
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
	echo "<b>".$date.":</b> $dia, $fecha[mday]-$mes-$fecha[year],  ";
	if($fecha['hours'] < 10){ echo "0"; }
	echo "$fecha[hours]:";
	if($fecha['minutes'] < 10){ echo "0"; }
	echo "$fecha[minutes]:";
	if($fecha['seconds'] < 10){ echo "0"; }
	echo "$fecha[seconds] <img border=\"0\" src=\"../../imagenes/separador2.gif\"> ";
	echo "<a href=\"../../index.php\"><img border=\"0\" src=\"../../imagenes/tit_principal_info.png\" align=\"bottom\"></a></p>";
?>

<table border="0" width="90%">
   <tr>  
      <td class="Leer">

<?php 
      // INICIO de la visualizacion del texto y las concordancias

	  $formato = $obj->edition_stmt;
	  $idioma = $obj->lang_usage;
	  
	  $vec = extraerParrafos($obj->body);
	  
	  
	  if (isset($_GET['entero'])) // solo se visualizan los parrafos
	  {
		  $total = visualizarPalabra($obj->body, $formato, $palabra, $idioma);
	  }
	  else // se visualiza el texto entero
	  {
		  $i = 0;
		  $total = 0;
		  while ($i < count($vec))
		  {
			if (perteneceParrafo($vec[$i],$palabra))
			{
				$tot = visualizarPalabra($vec[$i], $formato, $palabra, $idioma); 
				$total = $total + $tot;
			}
			$i++;
		  }
	  }
      // FIN de la visualizacion del texto y las concordancias
?> 

      </td>
   </tr>
</table>

<?php 
	 if (tienePermisos("corpusvertextoentero"))
	 {
		echo "<p><a href='".$_SESSION['application_url']."/corpus/lista/visualizar_texto.php?texto=$texto&palabra=$palabra&entero=si' target='_blank'>".$mostrar_texto_entero."</a></p>";
	 }

      echo "<p class=\"Info\"><b>".$total_ocurrencias."</b> $total</p>";
   }
   else  // El usuario NO tiene privilegios para acceder a la pagina
   {
      echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   }
?>

</body>
</html>
