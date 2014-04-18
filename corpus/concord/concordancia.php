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
?>

<!-- concordancia.php ---------------------------------------------------------------------------

     Psgina donde se realiza la busqueda de las concordancias y se muestran los resultados.

----------------------------------------------------------------------------------------------- -->
<html>
<head>
   <title>Calíope</title>
   <link rel="stylesheet" type="text/css" href="../../CSS/concordancia.css">
   <link rel="stylesheet" type="text/css" href="../../comun/estilo.css">
   <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
   <meta http-equiv=Content-Type content="text/html; charset=utf-8">
   <meta content="MSHTML 6.00.2800.1498" name=GENERATOR>
   <script type="text/javascript" src="../../ajax/ajax.js"></script>
   <script>

      function show (id) {
          if (document.getElementById(id).style.display == "") {
        	  document.getElementById(id).style.display = "none";
          } else {
        	  document.getElementById(id).style.display = "";
          }
      }

      function salvarTodos () {
    	  var ok=false;
          for (var i=0; i < document.main.elements.length; i++) {
        	  if (document.main.elements[i].tagName == "INPUT" && document.main.elements[i].type == "checkbox") {
        		  document.main.elements[i].checked = true;
        		  ok = true;
        	  }
          }

		  if (ok) {
			document.main.action = "resultados_concordancia.php";
	  		document.main.target = "_blank";
			document.main.submit();
		  } else {
			  alert ("No hay concordancias para guardar.");
		  }
      }   

	  function salvarSeleccionados () {
  		var ok=false;
          for (var i=0; i < document.main.elements.length; i++) {
          	if (document.main.elements[i].tagName == "INPUT" && document.main.elements[i].type == "checkbox") 
              	ok = ok || document.main.elements[i].checked;
          }

          if (!ok) {
  			alert ("Debe seleccionar al menos una concordancia para guardar");
          } else {
  			document.main.action = "resultados_concordancia.php";
  			document.main.target = "_blank";
  			document.main.submit();
		  }
	  }

	  function salvarSeleccionadosCSV () {
  		var ok=false;
          for (var i=0; i < document.main.elements.length; i++) {
          	if (document.main.elements[i].tagName == "INPUT" && document.main.elements[i].type == "checkbox") 
              	ok = ok || document.main.elements[i].checked;
          }

          if (!ok) {
  			alert ("Debe seleccionar al menos una concordancia para guardar");
          } else {
  			document.main.action = "resultados_concordancia_csv.php";
  			document.main.target = "_blank";
  			document.main.submit();
		  }
	  }

	  function postbackComplete(contenedor, respuesta) {
		  var indice = respuesta.indexOf("#");
		  var panel = document.getElementById("panel");

		  if (indice > 0) {

			  var index = respuesta.substring(0,indice);
			  index = parseInt(index);

			  var subrespuesta = respuesta.substring(indice+1);

			  indice = subrespuesta.indexOf("#");

			  var subocurrencias = "0";

			  if (indice > -1)
				  subocurrencias = subrespuesta.substring(0,indice)

			  if (subocurrencias != "0") {
				ocurrencias += parseInt(subocurrencias);
			  	subrespuesta = subrespuesta.substring(indice+1);
			  	
				// Internet Explorer da problemas al introducir el innerHTML
			  	var newtr = document.createElement("div");
			  	newtr.innerHTML = subrespuesta;
			  	contenedor.appendChild(newtr);
			  } 
		  }

		  if (parseados == array_textos.length) {
			if (ocurrencias == 0) { // NO se ha encontrado la palabra en ninguno de los textos
				panel.className = "Alerta";
				panel.innerHTML = "<img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br><?php echo $no_encontrar_concordancia ?>";
			} else {
				panel.className = "Info";
				panel.innerHTML = "<b><?php echo $total_ocurrencias ?>:</b>" + ocurrencias;
			}
	
			panel.style.display = "";
		  } else {
			  sendMyAjaxPostback(index,document.getElementById("contenedor"),postbackComplete);
		  }
	  }

      function cargarConcordancias() {
          var limite = (array_textos.length > 20)?20:array_textos.length;
          for (var i =0 ; i< limite; i++) {
        	  sendMyAjaxPostback(i,document.getElementById("contenedor"),postbackComplete);
          }
      }

      function sendMyAjaxPostback (indice, contenedor, postback) {
    		var ajax=objetos_ajax[indice];
  		    //var panel = document.getElementById("panel");
		    //panel.innerHTML += indice + "#";

    	    ajax.open("GET", "buscar_concordancia.php?index=" + indice + "&PHPSESSID=<?php echo session_id();?>&texto=" + array_textos[parseados++],true); 
    	    ajax.onreadystatechange=function() {
    			if(ajax.readyState==4){
    	            //Sucede cuando la pagina se cargo
    	            if(ajax.status==200){
    	            	//Todo OK
    	                postback(contenedor, ajax.responseText);

    	            }else if(ajax.status==404){
    	                //La pagina no existe
    	                tratarError(contenedor, "La p\u00e1gina no existe");
    	            }else{
    	                //Mostramos el posible error
    	                tratarError(contenedor, "Error: " + ajax.status);
    	            }
    	        }
    	    }
    	    ajax.send(null);
    	}
    	      
      
   </script>
</head>
<body onload="cargarConcordancias();">

<form name="main" action="resultados_concordancia.php" target="_blank" method="post">
<?php 
   if(tienePermisos("corpusconcordanciaslista"))
   {
		//include("buscar_palabra.php");
?>

<header>
	<h1><?php echo $concord ?></h1>
</header>
<?php 
	
	  // Obtener los datos del formulario
	  //$palabra = $_POST['palabra'];
	  $nuevoTermino = $_POST['nuevoTermino'];
	  if (!isset($_POST['palabra']))
	  {
		$palabra[0] = $nuevoTermino;
		//$palabra = $_POST['palabra'];
	  }
	  else
	  {
		$palabra = $_POST['palabra'];
	  }
	  $entorno = $_POST['entorno'];
	  if (!isset($_POST['distancia']))
	  {
		$distancia[0] = $entorno;
	  }
	  else
	  {
		$distancia = $_POST['distancia'];
	  }
	  $lista_textos = $_POST['documento']; // Recogemos los textos en los que se va a buscar. Vienen codigos y nombres
	  if (isset($_POST['donde_buscar']))
	  {
		$donde_buscar = $_POST['donde_buscar'];
		$_SESSION["donde_buscar"] = $donde_buscar;
	  }
	  else
	  {
		$donde_buscar = "no_seleccionado";
		$_SESSION["donde_buscar"] = $donde_buscar;
	  }
	  if (isset($_POST['categoria']))
	  {
		$cat_gram = $_POST['categoria'];
		$_SESSION["categoria"] = $cat_gram;
	  }
	  else
	  {
		$cat_gram = $other;
		$_SESSION["categoria"] = $cat_gram;
	  }
	  
	  //$distancia = $_POST['distancia'];

	  // Los datos comunes a la busqueda de concordancias se dejan en sesion.
	  $_SESSION["palabra"] = $palabra;
	  $_SESSION['nuevoTermino'] = $nuevoTermino;
	  $_SESSION["distancia"] = $distancia; 
	  $_SESSION["entorno"] = $entorno;
	  
      $num_textos = count($lista_textos);

	 //---------- INICIO DE LA PAGINA DE RESULTADOS DE LA CONCORDANCIA ----------
?>
<nav>
    <input id="operacion" type="button" value="<?php echo $operaciones ?>"/>
    <input type="button" value="<?php echo $concordancias ?>" onclick="document.location='menu_concord.php';"/><br>
</nav>
<section>
<table border="0" width="100%">
   <tr>
      <td>
<?php 
	  // Cabecera de la pagina: tipo de operacion, termino a buscar, entorno
	  echo "<b>".$operacion."</b> ".$concord." <img border=\"0\" src=\"../../imagenes/separador2.gif\"> ";
	  echo "<b>".$pal.":</b> $palabra[0] <img border=\"0\" src=\"../../imagenes/separador2.gif\"> ";
	  for ($i = 1; $i < count($palabra); $i++) {
	     echo "<b>".$pal." ".($i+1).":</b> ".$palabra[$i]." <img border=\"0\" src=\"../../imagenes/separador2.gif\"> ";
	     echo "<b>".$dist." ".($i+1).":</b> $distancia[$i] <img border=\"0\" src=\"../../imagenes/separador2.gif\"> ";
	  }
	  echo "<b>".$ent.":</b> $entorno <img border=\"0\" src=\"../../imagenes/separador2.gif\"> ";
	  $s2 = "<p style='display: block;border: dashed;border-width: 1px;border-color: #808080;margin-left: 1%;margin-right: 1%;background-color: #DBDBDB;font-size:12pt;color:#808080;'>".
	  		"<b>Operaci&oacute;n:</b> Concordancia<b><br>Palabra:</b> $palabra[0] <br>";
	  for ($i = 1; $i < count($palabra); $i++) {
	     $s2 .= "<b>".$pal." ".($i+1).":</b> ".$palabra[$i]."&nbsp;&nbsp;";
	     $s2 .= "<b>".$dist." ".$distancia[$i]." <br>";
	  }
	  $s2 .= "<b>".$ent.":</b> $entorno <br>";

	  if ($donde_buscar == "IzqDer")
			echo "<b>".$busq_donde.":</b> ".$dchaizda." <br>";
	  else if ($donde_buscar == "Izquierda")
			echo "<b>".$busq_donde.":</b> ".$izda." <br>";
	  else
			echo "<b>".$busq_donde.":</b> ".$dcha." <br>";
			
	  echo "<b>".$categoria_gramatical.":</b> ".$cat_gram." <br>";
	  echo "<b>".$textos_seleccionados.":</b> ";
	  $s2 .= "<b>".$textos_seleccionados.":</b> &nbsp;&nbsp;";
	  $primero = 1;
	  
	  for ($i = 0; $i < $num_textos; $i++)
	  {
	     $elementos = preg_split('/#/',$lista_textos[$i]);
	     $elemento_nombre = $elementos[1];
                 
	     if($primero)
	     {
	        echo "[$elemento_nombre]";
	        $s2 .= "[$elemento_nombre]";
	        $primero = 0;
	     } else {
	        echo ", [$elemento_nombre]";
	        $s2 .= ",&nbsp;[$elemento_nombre]";
	     }
	  }
	  
	  echo " <img border=\"0\" src=\"../../imagenes/separador2.gif\"> ";
	  //$s2 .= " <img border=0 src=".$_SESSION['application_url']."/imagenes/separador2.gif> ";

	  // Cabecera de la pagina: fecha en la que se realizo la concordancia

	  $fecha = getdate();
	  if ($lg == "esp")
	  {
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
	  $s2 .= "<b>".$date.":</b> $dia, $fecha[mday]-$mes-$fecha[year],  ";

	  if($fecha['hours'] < 10){ echo "0"; }
	  echo "$fecha[hours]:";
	  $s2 .= "$fecha[hours]:";

	  if($fecha['minutes'] < 10){ echo "0"; }
	  echo "$fecha[minutes]:";
	  $s2 .= "$fecha[minutes]:";

	  if($fecha['seconds'] < 10){ echo "0"; }
	  echo "$fecha[seconds]";
	  
	  $s2 .= "$fecha[seconds] <img border=0 src=".$_SESSION['application_url']."/imagenes/separador2.gif> <img border=0 src=".$_SESSION['application_url']."/imagenes/tit_principal_info.png align=bottom ></p>";
		
	  echo "<input type=\"hidden\" name=\"cabecera\" value=\"".$s2."\" />";

	  $palabra_comparar = "";
	  
	  // Convertimos las palabras de forma que se puedan usar como expresiones regulares.
	  for ($i = 0; $i < count($palabra); $i++) 
	  {
	  	$palabra_comparar = preg_replace("/\*/","[A-Z]*",$palabra[$i]);
   		$palabra_comparar = preg_replace("/\?/","[A-Z]?",$palabra_comparar);
   		
		$palabra[$i] = $palabra_comparar;
	  }
?><script language="javascript" type="text/javascript">
	var ocurrencias = 0;
	var parseados = 0;
	var array_textos = new Array();
<?php

	  for ($i = 0; $i < $num_textos; $i++)
	  {
	     $elementos = preg_split('/#/',$lista_textos[$i]);
		echo "array_textos[array_textos.length]= $elementos[0];\n";
	  }
?>
	  var objetos_ajax = new Array();
	  for (var i=0; i<20;i++) {
		  objetos_ajax[objetos_ajax.length] = GetAjaxObj(); 
	  }
</script>
		<div align='center' border='0' id='contenedor'></div>
		
		<p id="panel"></p>
      </td>
   </tr>
</table>
<p align="center">
    <input type="button" value="<?php echo $boton_aceptar ?>" onclick="document.location='menu_concord.php';"/>&nbsp;&nbsp;
    <input type="button" value="<?php echo $guardar_resultado ?>" onclick="salvarTodos();"/>&nbsp;&nbsp;
    <input type="button" id="seleccionado" value="<?php echo $guardar_seleccionados ?>" onclick="salvarSeleccionados();"/>&nbsp;&nbsp;
	<input type="button" value="CSV" onclick="salvarSeleccionadosCSV();"/>
<?php 
      // Si es un unico termino, introducimos el contexto.
      if (count($palabra) == 1 && tienePermisos("administrarglosario")) { 
?>
<!--    <input type="button" class="boton boton_descarga" value="      <?php echo $anadir_como_context ?> " onclick="anadirTerminos();"/>&nbsp;&nbsp; -->
<?php 
      } 
?>
  <input type="button" value="<?php echo $limpiar_formulario ?>" onclick="document.main.reset();"/>
</p>
<br>
</section>
<!--<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_acceso_corpus.php">CORPUS</a> > <u><?php echo $concordancias ?></u></td>
   </tr>
</table>-->

<?php 
   }
   else  // El usuario NO tiene privilegios para acceder a la pagina
   {
	   echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   }
?>
</form>
</body>
</html>
