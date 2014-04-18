<?php 
	session_start();header('Content-Type: text/html; charset=utf-8');ini_set("session.cookie_httponly", 1);
   
	include ("../../comun/permisos.php");
    $indice = $_GET["index"];
	$texto = $_GET["texto"];
	if(tienePermisos("corpusconcordanciaslista"))
	{
		//$tipo = $_SESSION["tipo"];
		$palabra = $_SESSION["palabra"];
		$entorno = $_SESSION["entorno"];
		$distancia = $_SESSION["distancia"];
		$donde_buscar = $_SESSION["donde_buscar"];
		$cat_gram = $_SESSION["categoria"];
		//$texto = $_GET["texto"];
		//$indice = $_GET["index"];
		
		//echo $indice."#".buscarPalabra2($texto, $palabra, $entorno, $distancia,$elementos[1], $donde_buscar, $cat_gram);
		$salida = "$indice#".buscarPalabra2($texto, $palabra, $entorno, $distancia,  $donde_buscar, $cat_gram);
		echo $salida;
	}

//=================================================================================================

function buscarPalabra2($texto_codigo, $palabra, $entorno, $distancia, $donde_buscar, $cat_gram)

/*-------------------------------------------------------------------------------------------------
  Funcion de busqueda de concordancias de un termino en un texto.

  ENT: $texto_codigo        - identificador del texto en el que se realiza la busqueda
       $palabra      - termino que se busca en el texto con identificador $texto_codigo
       $entorno      - numero de terminos que anteceden y preceden a $palabra en la concordancia
       $distancia    - distancia entre terminos
  SAL: numero de ocurrencias encontradas
-------------------------------------------------------------------------------------------------*/

{

   $entorno2= $entorno;

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

   $consulta = "SELECT id_texto,h_title,edition_stmt,lang_usage,body, anotado FROM texto WHERE texto.id_texto='$texto_codigo'";
   $res = mysql_query($consulta) or die($lectura_texto_incorrecta);
   $obj = mysql_fetch_object($res);
   
   $formato = $obj->edition_stmt;
   $idioma = $obj->lang_usage;
   $texto_nombre=$obj->h_title;
   $anotado=$obj->anotado;
   if ($anotado){
		$distancia = $distancia * 4;
	}
   $vector = extraerPalabrasBBDD($obj->body,$formato);
   $parrafos = extraerParrafos($obj->body);

   
   mysql_close($enlace);

   $i = 0;          // -> puntero al comienzo de la busqueda de termino.
   $q = 0;			// -> contador de parrafos
   $t = 0;
   $contador_parrafo = 0;
   $elementos = 0;  // -> numero de ocurrencias encontradas
   $colspan = $entorno*2 + count($palabra)*2 - 1;
   $s_html = "";
   $s_html_inicio = "<div><table id='table2'><tr style='cursor:hand' onclick=show(\"".$texto_codigo."\") title=".$pulse_aqui."><td id='table2_td' colspan=".$colspan."><p><b>".$text.":</b>&nbsp;".preg_replace("/\s/","&nbsp;",$texto_nombre).".&nbsp;C&Oacute;DIGO:&nbsp;".$texto_codigo;
   $s_html_fin = "</table></td></tr><tr><td>&nbsp;</td></tr></table></div>";

   while ($i < count($vector)) {
      // Iniciamos iteracion de busqueda de concordancia
      $j = $i; // $j sera el contador para la concordancia actual.
      $contador_termino = 0;
      $concordancia = false;
      $error = false;
      $distancia_temporal = 0;
      $texto_html = "";
      $texto_html_aux = "";
      $texto_html_palabra = "";
      $es_exacto = true;
      $codificacion = "";
      
      // Mientras no se encuentre una concordancia o haya un error en las comparaciones.
      while (!$concordancia && !$error) {
         $palabras = preg_split("/ /", $palabra[$contador_termino]);

		 // Calculamos la distancia limite: 0 si es el primer termino
         if ($contador_termino == 0)
            $limite_contador = 1;
         else
         {
         	$distancias = preg_split("/ /", $distancia[$contador_termino]);
            $limite_contador = intval($distancias[0])+1;
            $es_exacto = ($distancias[1] == "="); 
			
//            echo "'".$distancia[$contador_termino]."' '".$distancias[0]."' '".intval($distancias[0])."'<br>";
         }
         
         $encontrada = false;
         $encontrado_contador = 0;
         
         if (!$es_exacto)
         {
	         // Comenzamos a iterar desde 0 hasta la distancia final para este termino.
	         for ($l=0; !$encontrada && $l<$limite_contador; $l++) {
	         	$distancia_temporal = $l;
	            $error = false; // Al omenzar una nueva iteracion de busqueda de la palabra, no hay error.
	         	
	         	// Se intenta buscar una concordancia con la primera palabra de la distancia.
	            for ($k=0; !$error && $k< count($palabras); $k++) {
		           $palabra_pl = crearPlural($palabras[$k], $idioma); // plural
					
//		           if (strcasecmp($vector[$j+$l+$k], $palabras[$k]) != 0 && strcasecmp($vector[$j+$l+$k], $palabra_pl) != 0) {
		           if ($vector[$j+$l+$k] == "" || (!preg_match ("/^".$palabras[$k]."$/i", $vector[$j+$l+$k]) && !preg_match("/^".$palabra_pl."$/i", $vector[$j+$l+$k]) != 0)) {
						$distancia_temporal = $l + $k; // Indica la distancia desde el ultimo termino hasta donde se encontro el error.
	               	  $error = true; // Concordancia no encontraday fallo en la comparacion.
	               }
	            }
	            
	            $encontrada = !$error;
	         }
         }
         else
         {
	         $l = $limite_contador-1;
	         $error = false; // Al omenzar una nueva iteracion de busqueda de la palabra, no hay error.
	         
	         // Se intenta buscar una concordancia con la primera palabra de la distancia.
	         for ($k=0; !$error && $k< count($palabras); $k++) {
		        $palabra_pl = crearPlural($palabras[$k], $idioma); // plural
	               
				if ($vector[$j+$l+$k] == "" || (!preg_match ("/^".$palabras[$k]."$/i", $vector[$j+$l+$k]) && !preg_match("/^".$palabra_pl."$/i", $vector[$j+$l+$k]) != 0))
	               $error = true; // Concordancia no encontraday fallo en la comparacion.
             }
	            
	         $encontrada = !$error;
	         
	         if (!$encontrada)
             	$distancia_temporal = $l + $k -1; // Indica la distancia desde el ultimo termino hasta donde se encontro el error.
             else
             	$distancia_temporal = $l; // Indica la distancia donde empieza el termino encontrado.
         }
         
         if ($encontrada) {
         	$codificacion2 = "";
         	
         	$texto_html .= "<td>";
            
         	for ($n=0; $n < $distancia_temporal; $n++) {
         		$texto_html.= $vector[$j+$n]." ";
         		$codificacion2.=$vector[$j+$n]." ";
         	}
         	$texto_html .= "</td>";
         	$texto_html .= "<td id='res_pal' align='center' bgcolor='#2980b9'>";
         	
         	$codificacion2.="#";
         	
         	$texto_html_palabra = $vector[$j + $distancia_temporal];
         	$codificacion2.= $vector[$j + $distancia_temporal];

         	for ($n=1; $n <count($palabras); $n++) {
         		$texto_html_palabra .= " ".$vector[$j + $distancia_temporal + $n];
         		$codificacion2.= " ".$vector[$j + $distancia_temporal + $n];
         	}
			
         	//$num_parrafos = count($parrafos);
			
			//for ($m=0; $m < count($parrafos); $m++)
			$continuar = true;
			while (($q < count($parrafos)) && $continuar)
			{
				//$num_continuacion = perteneceParrafo($parrafos[$q], $palabra, $contador_parrafo);
				$vec = extraerPalabrasBBDD($parrafos[$q], "texto");
				$found = false;
				//$t = $contador_parrafo;
				while (($t < count($vec)) && !$found)
				{
					if (strcasecmp($vec[$t], $texto_html_palabra) == 0)
					{
						$t++;
						$result = $t; // Devuelve la posicion en donde tiene que seguir buscando la siguiente vez
						$found = true;
					}
					else
					{
						$result = -1;
						$t++;
					}
				}
				if ($result == -1) // no se ha encontrado el termino en ese parrafo y se continua buscando en el siguiente
				{
					$q++;
					$t = 0;
					$contador_parrafo = 0;
					$continuar = true;
				}
				else // se ha encontrado y se para la busqueda
				{
					//$num_parrafo = $q;
					$contador_parrafo = $result; // se deja apuntando a continuacion de donde se tiene que seguir buscando dentro del mismo parrafo
					$continuar =  false;
				}
			}			
			$num_parrafo = $q;
         	$texto_html .= "<a href='".$_SESSION['application_url']."/corpus/concord/visualizar_texto.php?texto=$texto_codigo&num_parrafo=$num_parrafo&palabra=$texto_html_palabra#$elementos' target='_blank'>$texto_html_palabra</a>";
         	$texto_html .= "</td>";
			$codificacion.=$codificacion2."#";
         	
            $texto_html_palabra = "";

            $j = $j + $distancia_temporal + count($palabras); // Calculo del nuevo inicio de busqueda
            $contador_termino++;
         } else {
         	$j = $j + $distancia_temporal + 1; // Calculo del nuevo inicio de busqueda
         	$contador_termino = 0; // Ponemos el contador de terminos a 0.
         }
         
         // Acabamos de encontrar una concordancia
         if ($contador_termino == count($palabra)) {
			
			include ("../../comun/conexion.php");
		 
         	$s_html .= "<tr>";
         	$codificacion2 = "$texto_codigo#";
			
			// MUESTRA TODOS LOS RESULTADOS TANTO SI COINCIDE CON LA CATEGORIA GRAMATICAL COMO SI NO			
			// Crear entorno ANTERIOR al termino
			if ($donde_buscar == "IzqDer" || $donde_buscar == "Izquierda")
			{
				//Duplicado para textos anotados
				
				if ($anotado){
					for ($m = entorno; $m>0; m-4){
						$pal_gram = substr($i-$m+2,0,1);
						echo $pal_gram;
						if ($pal_gram == cat_gram){
								$texto_html_aux = $texto_html_aux."<td align=center bgcolor=#CCCOO>" . $vector[$i-$m] . "</td>";
								$codificacion2 .= " ".$vector[$i-$m];
						}
						else{
							$texto_html_aux = $texto_html_aux."<td align=center>" . $vector[$i-$m] . "</td>";
							$codificacion2 .= " ".$vector[$i-$m];
						}
					}
				}
			
			
				for ($m = $entorno; $m > 0; $m--)
				{
					$pal_gram = $vector[$i-$m];
					$consulta = "SELECT pos FROM eswn_variant WHERE eswn_variant.word='$pal_gram'";
					$res = mysql_query($consulta) or die($lectura_texto_incorrecta);
					//$obj = mysql_fetch_object($res);
					
					//if ($obj->pos == $cat_gram)
					if (mysql_num_rows($res) != 0)
					{
						$stop = false;
						while (($obj = mysql_fetch_object($res)) && !$stop)
						{
							if ($obj->pos == $cat_gram)
							{
								$texto_html_aux = $texto_html_aux."<td align=center bgcolor=#CCCOO>" . $vector[$i-$m] . "</td>";
								$codificacion2 .= " ".$vector[$i-$m];
								$stop = true;
							}
						}
						if (!$stop)
						{
							$texto_html_aux = $texto_html_aux."<td align=center>" . $vector[$i-$m] . "</td>";
							$codificacion2 .= " ".$vector[$i-$m];
						}
					}
					else
					{
						$texto_html_aux = $texto_html_aux."<td align=center>" . $vector[$i-$m] . "</td>";
						$codificacion2 .= " ".$vector[$i-$m];
					}				   
				}
			
				$texto_html = $texto_html_aux.$texto_html;
				$codificacion=$codificacion2.$codificacion;
			}
			if ($donde_buscar == "IzqDer" || $donde_buscar == "Derecha")
			{
			
			//Duplicado para textos anotados
				
				if ($anotado){
					for ($m = 0; $m>entorno; m-4){
						$pal_gram = substr($j-$m+2,0,1);
						if ($pal_gram == cat_gram){
								$texto_html_aux = $texto_html_aux."<td align=center bgcolor=#CCCOO>" . $vector[$j-$m] . "</td>";
								$codificacion2 .= " ".$vector[$j-$m];
						}
						else{
							$texto_html_aux = $texto_html_aux."<td align=center>" . $vector[$j-$m] . "</td>";
							$codificacion2 .= " ".$vector[$j-$m];
						}
					}
				}
				// Crear entorno POSTERIOR al termino
				for ($m = 0; $m < $entorno; $m++)
				{
					$pal_gram = $vector[$j+$m];
					$consulta = "SELECT pos FROM eswn_variant WHERE eswn_variant.word='$pal_gram'";
					$res = mysql_query($consulta) or die($lectura_texto_incorrecta);
					//$obj = mysql_fetch_object($res);
					
					//if ($obj->pos == $cat_gram)
					if (mysql_num_rows($res) != 0)
					{
						$stop = false;
						while (($obj = mysql_fetch_object($res)) && !$stop)
						{
							if ($obj->pos == $cat_gram)
							{
								$texto_html .= "<td align=center bgcolor=#CCCOO>" . $vector[$j+$m] . "</td>";
								$codificacion .= " ".$vector[$j+$m];
								$stop = true;
							}
						}
						
						if (!$stop)
						{
							$texto_html .= "<td align='center'>" . $vector[$j+$m] . "</td>";
							$codificacion .= " ".$vector[$j+$m];
						}
					}
					else
					{
						$texto_html .= "<td align='center'>" . $vector[$j+$m] . "</td>";
						$codificacion.=$vector[$j+$m]." ";
					}				   
				}
				$codificacion.= "#".$elementos;
			}

			
			mysql_close($enlace);
			
	        $s_html .= "<td align='center'><input type='checkbox' name='resultado".$texto_codigo.$elementos."' value=\"$codificacion\">&nbsp;</td>$texto_html</tr>";
		    
            $texto_html_aux = "";
            $elementos ++;
            $concordancia = true;
         }
      }
   	 
   	 // Colocamos el puntero para la nueva concordancia. 
   	 $i = $j;
   }

   
   if ($elementos > 0) {
	 $s_html_inicio .= ".&nbsp;<b>".$total_ocurrencias.":</b>&nbsp;".$elementos."&nbsp;&nbsp;</p><input type=hidden name=texto".$texto_codigo." value='<tr><td><p class=Info2><b>".$text.":</b>&nbsp;";
	 $s_html_inicio .= preg_replace("/\s/","&nbsp;",$texto_nombre).".&nbsp;C&Oacute;DIGO:&nbsp;".$texto_codigo.".&nbsp;<b>".$total_ocurrencias.":</b>&nbsp;".$elementos."&nbsp;&nbsp;</p>'>";
	 $s_html_inicio .= "</td></tr><tr><td colspan='".$colspan."' style='display:none;border: dashed;border-width: 1px;border-color: #515151;margin-left: 1%;margin-right: 1%;' id='".$texto_codigo."'><table width='100%' border='0' cellpadding='0' cellspacing='4' bgcolor='#ffffff'>";
	 $s_html_fin = "<tr><td colspan='".$colspan."'>&nbsp;</td></tr>".$s_html_fin;
	 return $elementos."#".$s_html_inicio.$s_html.$s_html_fin; // Introducimos una linea de separacion.
   } else
      return $elementos."#";
}

//=================================================================================================

function visualizarPalabra($texto, $formato, $palabra, $idioma)

/*-------------------------------------------------------------------------------------------------
  Visualizacion (en formato HTML) de todas las ocurrencias de un termino en un texto.

  ENT: $texto   - cuerpo del texto en el que se realiza la busqueda
       $formato - formato del texto con identificador $texto_codigo
       $palabra - termino que se busca en el texto con identificador $texto_codigo
	   $idioma  - idioma del texto
  SAL: numero de ocurrencias de $palabra en el texto $texto_codigo y visualizacion del texto con 
       las ocurrencias del termino marcadas y resaltadas     
-------------------------------------------------------------------------------------------------*/

{
   $vector = extraerElementosBBDD($texto);
   
   // Busqueda del termino en el vector
   $elemento = 0;
   
   $palabras = preg_split("/ /", $palabra);
   $palabras_pl = array();
   $j = 0;
   
   foreach ($palabras as $x)
   	 $palabras_pl[] = crearPlural($x, $idioma);
   
   $contador=0;
   while ( $contador<count($vector))
   {
     $igual = true;
     if (strcasecmp($vector[$contador], $palabras[0]) == 0 || strcasecmp($vector[$contador], $palabras_pl[0]) == 0)
     {
     	$j = 1;
      	for ($contador_2=1; $igual && $contador_2<count($palabras); $contador_2++)
       	{
      		while (!esCaracter($vector[$contador+$j][0]))
      		   $j++;
       		
      		if (strcasecmp($vector[$contador+$j], $palabras[$contador_2]) != 0 && strcasecmp($vector[$contador+$j], $palabras_pl[$contador_2]) != 0)
     		   $igual = false;
     		
     		$j++; // Al incrementar termino incrementamos a la siguiente posicion.
       	}
     }
     else
     {
      	$igual = false;
     }
      	 
     if ($igual)
     {
       // Resaltar el termino dentro del texto	
	   echo "<a name='$elemento'><span style=\"background-color: #FFFF99\"><font size=\"4\" color=\"#CC0000\"><b>";
       echo $vector[$contador];
       for ($contador_3=$contador+1; $contador_3< $contador+$j; $contador_3++)
       {
          echo $vector[$contador_3];
       }
	   echo "</b></font></span></a>";

	   $contador += $j-1; // -1 porque despue se incrementa en 1

	   $elemento ++;
	   $j = 1;
     }
     else
     {
	   if($vector[$contador] == '\n')  // Salto de linea
	   {
	     if($formato == 'texto')
	     {
	        echo "<br>";
	     }
	   }
	   else
	   {
	     echo $vector[$contador];
	   }
     }
     
     $contador++;
   }


   return $elemento;
}
//=================================================================================================
function extraerParrafos($texto)
{
	$parrafos = explode(".\r", $texto);
	return $parrafos;
}

//=================================================================================================
function perteneceParrafo($parrafo, $palabra, $contador)
{
	//$palabras = explode(" ", $parrafo);
	
	$vector = extraerPalabrasBBDD($parrafo, "texto");
	$encontrada = false;
	$res = 56789;
	// $i tiene que ser la posicion desde donde empieza a comparar las palabras
	//for ($i=$contador; $i<count($vector); $i++)
	$i = $contador;
	while (($i < count($vector)) && !$encontrada)
	{
		if (strcasecmp($vector[$i],$palabra) == 0)
		{
			$i++;
			$res = $i; // Devuelve la posicion en donde tiene que seguir buscando la siguiente vez
			$encontrada = true;
		}
		else
		{
			$i++;
		}
	}
	return $res; // si devuelve -1 es que no ha encontrado nada en ese parrafo
}


//=================================================================================================
function extraerPalabrasBBDD($texto, $formato)

/*-------------------------------------------------------------------------------------------------
  Funcion que extrae de un string todos sus elementos (palabras y otros elementos graficos).

  ENT: $texto - string
  SAL: vector con todos los elementos del texto
-------------------------------------------------------------------------------------------------*/
{
   $tamano_texto = strlen($texto);
   //---------- FICHERO FORMATO TEXTO ----------

   if($formato == 'texto') 
   {
      $i = 0;  // -> $i es un puntero a un caracter dentro del texto

      $es_caracter = 0;  // -> indica si se ha leido un caracter del alfabeto o numero
      $palabra = "";
      
      for ($j = 0; $j < $tamano_texto; $j++)
      {
         if( esCaracter($texto[$j]) ) // Se ha leido un caracter, anyadir a la palabra temporal leida
         {
            $palabra .= $texto[$j];
            $es_caracter = 1;
         }
         else                    // Se la leido un no caracter
         {
            if($es_caracter) // Tenemos una palabra completa, anyadir al vector de palabras
            {
               $resultado[$i] = $palabra;
               $i++;
               $palabra = "";
               $es_caracter = 0;
            }
         }
      }

      $resultado[$i] = $palabra;  // Anyadir la ultima palabra del texto al vector de palabras
   }
   
   else

   //---------- FICHERO FORMATO HTML ----------

   if($formato == 'html') 
   {
      $i = 0;
	  $etiq_abierta = 0;  // -> indica si se esta leyendo una etiqueta
	  $body = 0;          // -> indica si ya se ha leido la etiqueta <body> completa
	  $ini_body = 0;      // -> indica si se ha iniciado la lectura de la etiqueta <body>

      // Identificacion de palabras en el texto

	  $es_caracter = 0;
	  $encontrada = 0;
	  $palabra = "";

	  for ($j = 0; $j < tamano_texto; $j++)
      {
		if($body)  // Ya se ha leido la etiqueta <body>
		{
		   if($etiq_abierta)  // Estamos dentro de una etiqueta
		   {
		      if($texto[$j] == '>'){ $etiq_abierta = 0;}
		   }
		   else  // No estamos dentro de una etiqueta
		   {		     		      
                  if( esCaracter($texto[$j]) ) // Se ha leido un caracter
	          {
                 $palabra .= $texto[$j];  // Anyadir el caracter a la palabra temporal leida
		         $es_caracter = 1;
	          }
	          else                    // Se la leido un "no caracter"
	          {
			     if($texto[$j] == '<')
		         {
		            $etiq_abierta = 1;
		         }
	             if($es_caracter) // Tenemos una palabra completa
		         {
                    $resultado[$i] = $palabra;  // Anyadir la nueva palabra al vector resultado
		            $i++;
		            $palabra = "";
		            $es_caracter = 0;
		         }
	          }    
		   }
		}
		else  // No se ha leido la etiqueta <body> todavia
		{
		   if($ini_body)
		   {
			  if($texto[$j] == '>'){ $body = 1;}
		   }
		   else
		   {
		      if(($texto[$j] == '<') && ($texto[$j+1] == 'b' || $texto[$j+1] == 'B')){ $ini_body = 1;}
		   }
		}
      }

      $resultado[$i] = $palabra;  // Se anyade la ultima palabra leida
   }

   return $resultado;
}

//=================================================================================================

function extraerElementosBBDD($texto)

/*-------------------------------------------------------------------------------------------------
  Funcion que extrae de un texto todos sus elementos (palabras y otros elementos graficos).

  ENT: $texto - texto del cual se extraen los elementos
  SAL: vector con todos los elementos del texto
-------------------------------------------------------------------------------------------------*/

{
   $i = 0;
   $tamano_texto = strlen($texto);
   
   // Identificacion de elementos en el texto
   $es_caracter = 0;
   $encontrada = 0;
   $palabra = "";
   for ($j = 0; $j < $tamano_texto; $j++)
   {
      if(esCaracter($texto[$j]) )  // Se ha leido un caracter
      {
         $palabra .= $texto[$j];  // Anyadir el caracter a la palabra temporal leida
         $es_caracter = 1;
      }
      else // Se ha leido un "no caracter"
      {
         if($es_caracter) // Tenemos una palabra
         {
            $resultado[$i] = $palabra;  // Anyadir la nueva palabra al vector resultado
            $i++;
            $palabra = "";
            $es_caracter = 0;
         }
         
         $resultado[$i] = $texto[$j];  // Anyadir el "no caracter" al vector resultado
         $i++;
      }
   }
   $resultado[$i] = $palabra;  // Se anyade el ultimo elemento leido

   return $resultado;
}

//=================================================================================================

function esCaracter($c)

/*-------------------------------------------------------------------------------------------------
  Funcion que analiza si un elemento de tipo 'char' es un caracter del alfabeto o un numero.

  ENT: $c - elemento del tipo 'char' que se analiza
  SAL: '0' si no es un caracter del alfabeto ni un numero
       '1' si es un caracter del alfabeto o un numero
-------------------------------------------------------------------------------------------------*/

{
   if((ord($c) > 47 && ord($c) < 58 ) ||    // rango {0..9}
      (ord($c) > 64 && ord($c) < 91 ) ||    // rango {A..Z}
	  (ord($c) > 96 && ord($c) < 123 ) ||	// rango {a..z}
	  (ord($c) > 191 && ord($c) < 215 ) ||	// vocales 'A','E','I','O' acentuadas
	  (ord($c) > 216 && ord($c) < 222 ) ||  // vocal 'U' acentuada
	  (ord($c) > 223 && ord($c) < 247 ) ||	// vocales 'a','e','i','o' acentuadas
	  (ord($c) > 248 && ord($c) < 254 ))     // vocal 'u' acentuada
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

//=================================================================================================

function convertirMayusculas($palabra)

/*-------------------------------------------------------------------------------------------------
  Funcion que convierte todos los caracteres de una palabra a mayusculas.

  ENT: $palabra - termino que se quiere convertir a mayusculas.
  SAL: $palabra convertida a mayusculas (sin tildes).
-------------------------------------------------------------------------------------------------*/

{
	$longitud = strlen($palabra);  // -> numero de caracteres de la palabra
	$p = "";

    // Tratamiento de cada uno de los caracteres de la palabra

	for($i = 0; $i < $longitud; $i++)
	{
		$c = $palabra[$i];

		if( ord($c) > 96 && ord($c) < 123 )  // El caracter pertenece al rango {a..z,A..Z}
		{
	       $p .= strtoupper($c);
		}
		else
		{
		   switch($c)  // Se eliminan las tildes para comparar los terminos sin tildes
		   {
		      case("á"): $p .= 'A'; break;
		      case("é"): $p .= 'E'; break;
		      case("í"): $p .= 'I'; break;
		      case("ó"): $p .= 'O'; break;
		      case("ú"): $p .= 'U'; break;
		      case("ü"): $p .= 'Ü'; break;
		      case("ñ"): $p .= 'Ñ'; break;
			  default: $p .= $c; break;  // El caracter es un numero
		   }
		}
	}

	return $p;
}

//=================================================================================================

function crearPlural($term, $idioma)

/*-------------------------------------------------------------------------------------------------
  Funcion que devuelve el plural de un termino en espanyol o ingles.

  ENT: $term    - termino del cual se quiere crear el plural (en minusculas)
       $idioma  - idioma de $termino
  SAL: Plural de $termino
-------------------------------------------------------------------------------------------------*/

{
   $longitud = strlen($term);
   $final = $term[$longitud-1];      // -> ultimo caracter del termino
   $ant_final = $term[$longitud-2];  // -> antepenultimo caracter del termino
   $plural = $term;

   //---------- TERMINO ESPANYOL ----------

   if($idioma == 'esp')  
   {
      if($final == 'a' || $final == 'á' || $final == 'A' || $final == 'Á' ||
		 $final == 'e' || $final == 'é' || $final == 'E' || $final == 'É' ||
		 $final == 'o' || $final == 'ó' || $final == 'O' || $final == 'Ó' )
      {
   	         $plural .= 's';
	  }
	  else
	  {
	     $plural .= 'es';
	  }
   }

   //---------- TERMINO INGLES ----------

   if($idioma == 'ing')  
   {
	   if($final == 'y' || $final == 'Y')
	   {
	      if($ant_final == 'a' || $ant_final == 'A' || $ant_final == 'e' || $ant_final == 'E' || 
			 $ant_final == 'i' || $ant_final == 'I' || $ant_final == 'o' || $ant_final == 'O' ||
			 $ant_final == 'u' || $ant_final == 'U')
		  {
		     $plural .= 's';
		  }
		  else
		  {
			 $plural[$longitud-1] = 'i';  // La 'y' se cambia por 'i'
		     $plural .= 'es';
		  }
	   }
	   else
	   {
	      if($final == 'a' || $final == 'A' || $final == 'i' || $final == 'I' || 
			 $final == 'o' || $final == 'O' || $final == 'u' || $final == 'U' ||
			 $final == 's' || $final == 'S')
		  {
		     $plural .= 'es';
		  }
		  else  // La palabra acaba en consonante distinta de 'y' y de 's'
		  {
		     $plural .= 's';
		  }
	   }
   }

   return $plural;
}

?>