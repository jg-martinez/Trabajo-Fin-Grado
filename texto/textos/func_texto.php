<?php 
/*
-- func_texto.php -------------------------------------------------------------------------------

     Funciones para la administracion de textos.
 
-----------------------------------------------------------------------------------------------
*/

//=================================================================================================

function alta_texto($parametros)

/*-------------------------------------------------------------------------------------------------
  Funcion de alta de texto.

  ENT: $parametros: Lista de los parametros. Por orden, son: 
	    $h_title      - titulo del texto
	    $edition_stmt - formato del texto
	    $f_bytes      - numero de bytes que ocupa el fichero con el texto
	    $lang_usage   - idioma
	    $id_tipo      - tipo del texto
	    $id_campo     - campo del texto
	    $f_temp       - descriptor del fichero con el texto
	    $texto_relacionado - texto existente y relacionado con el nuevo texto
	    $fuente_relacionada - fuente existente que se relaciona con el texto
     	$fuente_id_fuente - isbn de la nueva fuente 
     	$fuente_edition - tipo de fuente
     	$fuente_h_title -  titulo de la nueva fuente
     	$fuente_h_author - autor de la nueva fuente
     	$fuente_pub_place - luegar de publicacion de la nueva fuente
     	$fuente_publisher - editor de la nueva fuente
     	$pub_date - fecha de edicion de la nueva fuente
  SAL: -
-------------------------------------------------------------------------------------------------*/

{
   // Conexion con la base de datos
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

   // Miramos si existe la fuente, en cuyo caso se crea.
   if ($parametros[8] == "") {
		// Consulta a la base de datos
		$consulta = "SELECT id_fuente FROM fuente WHERE id_fuente = '$parametros[9]'";
		$res = mysql_query($consulta) or die($lectura_fuente_incorrecta);

		if(mysql_num_rows($res) != 0)
		{
			echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$fuente_ya_existe."<br><br>";
			echo $no_creacion_fuente."<br><b>$parametros[9]</b></p>";
		}
		else
		{
			$consulta = "INSERT INTO fuente (id_fuente, edition, h_title, h_author, pub_place, publisher, pub_date) VALUES (";
			$consulta .= "'$parametros[10]', '$parametros[9]', '$parametros[11]', '$parametros[12]', '$parametros[13]', '$parametros[14]'";
			$consulta .= ", '$parametros[15]')";
			
			$resultado = mysql_query($consulta) or die($no_creacion_fuente . mysql_error());
			alta_historico ("alta", $_SESSION['username'], "fuente", "ISBN/ISSN: ".$parametros[10]."<br>Tipo: ".$parametros[9]."<br>T&iacute;tulo: ".$parametros[11]."<br>Autor: ".$parametros[12].
			"<br>Lugar/URL: ".$parametros[13]."<br>Editorial/N&ordm; Revista: ".$parametros[14]."<br>Fecha: ".$parametros[15]);
			
			$id_fuente = $parametros[10]; 
		}
   } else {
   		$id_fuente = $parametros[8];
   }
   
   
   $consulta = "INSERT INTO texto (h_title, edition_stmt, word_count, byte_count, lang_usage, id_tipo, id_campo, id_fuente, fecha_alta,";
   $consulta .= "usuario_alta, texto_relacionado) VALUES ('$parametros[0]', '$parametros[1]', 0, '$parametros[2]', '$parametros[3]', '";
   $consulta .= "$parametros[4]', '$parametros[5]', '$id_fuente',now(),'".$_SESSION['username']."','$parametros[7]')";
   
   $res = mysql_query($consulta) or die($no_creacion_tipo_texto . mysql_error());

   /* Se obtiene el identificador del texto */
   $consulta = "SELECT MAX(id_texto) FROM texto";
   $codigo = mysql_query($consulta) or die ($maximo_fallo);
   $nombre_fichero = mysql_result($codigo,0);

   alta_historico ("alta", $_SESSION['username'], "texto", "Identificador:".$nombre_fichero."<br>T&iacute;tulo: ".$parametros[0]."<br>Formato: ".$parametros[1]."<br>Num Palabras: 0<br>Idioma: ".$parametros[2]."<br>Tipo: ".$parametros[3].
   "<br>Campo: ".$parametros[4]."<br>Fuente: ".$id_fuente."<br>Texto relacionado:".$parametros[7]);

echo "<p class=\"Resultado\"><img border=\"0\" src=\"../../imagenes/info.gif\"><br>".$el_texto."<br><b>$parametros[0] </b><br>".$mensaje47."<br><b>" . $_SESSION['username'] . "</b></p>";
   $body = "";  // -> cuerpo del texto que se almacena en una tabla de la BD
      
   if($parametros[1] == 'html') // Contar palabras, formato HTML
   {
      $fd = fopen($parametros[6],"r");
      $num_palabras = 0;
      $ini_body = 0; // Se activa cuando se encuentra la etiqueta <body>
      $fin_body = 0; // Se activa cuando se ha leido completamente la etiqueta <body>
      $etiq_abierta = 0;
      while($linea = fgets($fd, 1024))
      {
         $longitud = strlen($linea);   
         for($i = 0; $i < $longitud; $i++)
         {
            $c = $linea[$i];
            if(!$fin_body) //-- Localizacion de la etiqueta <body>
            {
               if($ini_body)
               {
                  if($c == '>'){ $fin_body = 1; }
               }
               else
               {
                  if( ($c == '<') && ($linea[$i+1] == 'b' || $linea[$i+1] == 'B') ){ $ini_body = 1; }
               }
            }
            else  //-- Contar palabras del texto
            {
               if($etiq_abierta) // Se esta leyendo una etiqueta
               {
                  if($c == '>')
                  {
                     $etiq_abierta = 0;
                  }
               }
               else              // Se esta leyendo texto
               {
                  if($c == '<')
                  {
                     $etiq_abierta = 1;
                  }
                  else
                  {
                     if(esCaracterSeparador($linea[$i]))
                     {
                        if($hay_palabra)
                        {
                           $num_palabras ++;
                           $hay_palabra = 0;
                        }
                     }
                     else
                     {
                        $hay_palabra = 1;
                     }
                     $body .= $c;//$linea[$i];
                  }
               }
            }
         }
      }
      fclose($fd);
   } 
   else
   //if($edition_stmt == 'texto') // Contar palabras, formato Texto
   {
      $fd = fopen($parametros[6],"r");
      $num_palabras = 0;
      $hay_palabra = 0;
      while($linea = fgets($fd, 1024))
      {
         $longitud = strlen($linea);
         for($i = 0; $i < $longitud; $i++)
         {
            if(esCaracterSeparador($linea[$i]))
            {
               if($hay_palabra)
               {
                  $num_palabras ++;
                  $hay_palabra = 0;
               }
            }
            else
            {
               $hay_palabra = 1;
            }
            $body .= $linea[$i];
         }
      }
      $num_palabras++; // La ultima palabra no se cuenta porque el fin de fichero no se cuenta como
		                  // caracter separador => incrementamos $num_palabras
      fclose($fd);
   }

   // Actualizar numero de palabras
   $body = str_replace("'","''",$body);
   $consulta = "UPDATE texto SET word_count='$num_palabras', body='$body', fecha_modificacion=now(), usuario_modificacion='".$_SESSION['username']."' WHERE id_texto = '$nombre_fichero'";
   $resultado = mysql_query($consulta) or die($modificacion_fallo . mysql_error() ); 
   alta_historico ("modificar", $_SESSION['username'], "texto", "Identificador:".$nombre_fichero."<br>Num Palabras: ".$num_palabras);
   mysql_close($enlace);

   unlink($parametros[6]);

   /* Creacion de la lista de palabras */
   listaPalabras($nombre_fichero, $body, $parametros[1], $parametros[3]);

   /* Creacion de nuevos contextos para terminos del glosario */
   $vector = extraerElementos($body);
          
   // Conexion con la base de datos 
   include ("../../comun/conexion.php");

   $i = 0;
   foreach($vector as $x)
   {
      $mx = convertirMinusculas($x);

      $consulta= "SELECT id_glosario FROM glosario WHERE (id_glosario='$mx' OR termino_plural='$mx')";
      $resultado = mysql_query($consulta);

      if (mysql_num_rows($resultado) != 0)  
      {
         $obj = mysql_fetch_object($resultado);

         // Se reemplazan las comillas simples ya que producen fallos en el sql.
         $contexto = str_replace("'","''",crearContexto($vector, $i));

         $consulta = "INSERT INTO contexto (contexto, id_texto, id_glosario,fecha_alta,usuario_alta) VALUES ('$contexto', '$nombre_fichero', '$obj->id_glosario',now(),'".$_SESSION['username']."')";
         $res = mysql_query($consulta) or die($no_creacion_contexto. $consulta);

         alta_historico ("alta", $_SESSION['username'], "contexto", "Identificador texto: ".$nombre_fichero."<br>T&eacute;rmino: ".$obj->id_glosario."<br>Contexto: ".$contexto);
         // Incrementar el numero de ocurrencias del termino 
         $consulta = "SELECT termino,ocurrencias FROM glosario WHERE (termino='$mx' OR termino_plural='$mx')";
         $res = mysql_query($consulta);

         $obj = mysql_fetch_object($res);
         $ocurrencias = $obj->ocurrencias;
         $ocurrencias++;

         $consulta = "UPDATE glosario SET ocurrencias='$ocurrencias' WHERE id_termino='$obj->termino'";   
         $res = mysql_query($consulta);
      }
      $i++;
   }

   mysql_close($enlace);

   echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='menu_admin_textos.php';\" /></p>";
}



//=================================================================================================

function modificar_texto($id_texto, $h_title, $lang_usage, $id_tipo, $id_campo, $id_fuente, $texto_relacionado)

/*-------------------------------------------------------------------------------------------------
  Funcion de modificacion de un texto.

  ENT: $id_texto   - identificador del texto
       $h_title    - titulo del texto
       $lang_usage - idioma
       $id_tipo    - tipo del texto
       $id_campo   - campo del texto
       $id_fuente  - fuente del texto
       $texto_relacionado - texto relacionado
  SAL: -
-------------------------------------------------------------------------------------------------*/

{
   // Conexion con la base de datos 
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

   // Consulta a la base de datos 
   
   $consulta= "UPDATE texto SET h_title='$h_title', lang_usage='$lang_usage', id_tipo='$id_tipo', id_campo='$id_campo', id_fuente='$id_fuente', fecha_modificacion=now(), usuario_modificacion='".$_SESSION['username']."', texto_relacionado='$texto_relacionado' WHERE id_texto = '$id_texto'";
   $resultado = mysql_query($consulta) or die($modificacion_fallo . mysql_error()); 

   alta_historico ("modificar", $_SESSION['username'], "texto", "Identificador:".$id_texto."<br>T&iacute;tulo: ".$h_title."<br>Tipo: ".$id_tipo."<br>Idioma: ".$lang_usage."<br>Campo: ".$id_campo.
   "<br>Fuente: ".$id_fuente."<br>Texto relacionado:".$texto_relacionado);
   /* Mostrar resultados */
   echo "<p class=\"Resultado\">".$el_texto."<br><b>[$id_texto] $h_title </b><br>".$mensaje48."</p>";

   mysql_close($enlace);

   echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='menu_admin_textos.php';\" /></p>";
}



//=================================================================================================

function eliminar_texto($id_texto, $h_title, $edition_stmt)

/*-------------------------------------------------------------------------------------------------
  Funcion de eliminacion de texto.

  ENT: $id_texto     - identificador del texto
       $h_title      - titulo del texto
       $edition_stmt - formato del texto
  SAL: -
-------------------------------------------------------------------------------------------------*/

{
   // Conexion con la base de datos 
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

   /* Decrementar la ocurrencias de los terminos del glosario que aparecen en el texto */
   
   $consulta= "SELECT b.id_glosario,b.ocurrencias FROM contexto a, glosario b WHERE a.id_texto = '$id_texto' AND b.id_glosario=a.id_glosario";
   $resultado = mysql_query($consulta) or die($lectura_contextos_incorrecta . mysql_error()); 

   while($obj = mysql_fetch_object($resultado))
   {
	   $ocurrencias = $obj->ocurrencias - 1;

       $consulta3 = "UPDATE glosario SET ocurrencias='$ocurrencias' WHERE id_glosario = '$obj->id_glosario'";
       $resultado3 = mysql_query($consulta3) or die($modificacion_fallo . mysql_error()); 
   }

   
   /* Consulta a la base de datos: ELIMINACION de la lista de palabras */
   $consulta= "DELETE FROM lista_terminos WHERE id_texto = '$id_texto' ";
   $resultado = mysql_query($consulta) or die("Eliminaci&oacute;n incorrecta: " . mysql_error()); 
   
   /* Consulta a la base de datos: ELIMINACION del texto */
   $consulta= "DELETE FROM texto WHERE id_texto = '$id_texto' ";
   $resultado = mysql_query($consulta) or die($eliminacion_incorrecta . mysql_error()); 

   alta_historico ("eliminar", $_SESSION['username'], "texto", "Identificador:".$id_texto."<br>T&iacute;tulo: ".$h_title."<br>Formato: ".$edition_stmt);
   mysql_close($enlace);

   /* Mostrar resultados */
   echo "<p class=\"Resultado\"><img border=\"0\" src=\"../../imagenes/info.gif\"><br>".$el_texto."<br><b> $h_title </b><br>".$mensaje49."</P>";
   echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='menu_admin_textos.php';\" /></p>";
}


//=================================================================================================

function visualizar_texto($id_texto, $arg_op)

/*-------------------------------------------------------------------------------------------------
  Visualizacion por pantalla de un texto del corpus.

  ENT: $id_texto     - identificador del texto que se quiere visualizar
       $edition_stmt - formato del fichero que contiene el texto
       $arg_op - indica si es para visualizar o para descargar.
  SAL: visualizacion del texto por pantalla    
-------------------------------------------------------------------------------------------------*/

{
   // Conexion con la base de datos 
   include ("../../comun/conexion.php");
	  	 
   // Cargar datos del formulario
   $id_texto = $_GET['id_texto'];

   $consulta = "SELECT edition_stmt,h_title,body FROM texto WHERE id_texto = '$id_texto'";
   $res = mysql_query($consulta);
   $obj = mysql_fetch_object($res);
		 
   if ($obj->edition_stmt == "texto")
      $extension = ".txt";
   else
      $extension = ".html";

   if ($arg_op == "descargar")
   {
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="'.$obj->h_title.$extension.'"');
   } 
   
   
   if ($arg_op == "descargar" || $obj->edition_stmt != "texto")
   {
      echo $obj->body;
   }
   else
   {
      echo str_replace(array("\r\n", "\n", "\r"),'<br />',$obj->body) ;
   }

   mysql_close($enlace);
}

//=================================================================================================

function listaPalabras($texto, $body, $edition_stmt, $idioma)

/*-------------------------------------------------------------------------------------------------
  Funcion de creacion del listado de palabras en un fichero XML.

  ENT: $texto        - identificador del texto.
       $body         - texto 
       $edition_stmt - formato del texto
	   $idioma       - idioma
  SAL: -
-------------------------------------------------------------------------------------------------*/

{
   /* Variables necesarias para ficheros HTML */
   $ini_body = 0; // Se activa cuando se encuentra la etiqueta <body>
   $fin_body = 0; // Se activa cuando se ha leido completamente la etiqueta <body>
   $etiq_abierta = 0;

   $palabra = "";
   $letra = 1; // indica si se ha encontrado una letra
   $longitud = strlen($body);
   
   if($edition_stmt == 'html') //-- TEXTO FORMATO HTML
   {
      for($i = 0; $i < $longitud; $i++)
      {
         $c = $body[$i];

         if(!$fin_body) //-- Localizacion de la etiqueta <body>
         {
            if($ini_body)
            {
               if($c == '>'){ $fin_body = 1; }
            }
            else
            {
               if( ($c == '<') && ($body[$i+1] == 'b' || $body[$i+1] == 'B') ){ $ini_body = 1; }
            }
         }   
         else  //-- Contar palabras del texto
         {
            if($etiq_abierta) // Se esta leyendo una etiqueta
            {
               if($c == '>')
               {
                  $etiq_abierta = 0;
               }
            }  
            else              // Se esta leyendo texto
            {
	           if($c == '<')
	           {
	              $etiq_abierta = 1;
	           }
	           else
	           {
	              if(!esCaracterSeparador($body[$i])) // Se lee un caracter
	              {
	        	     $palabra .= $body[$i];
	        	     $letra = 1;
	              }
	        	  else // Se lee un signo ortografico o un espacio
	        	  { 
	        	     if($letra == 1 && $palabra != "") 
	        	     {
	        	        $lista[convertirMayusculas($palabra)]++; 
	        	     }
	        	     $palabra = "";
	        	     $letra = 0;
                  }
               }
            }
         } 
      } 
   }
   
   if($edition_stmt == 'texto')  //-- TEXTO FORMATO TEXTO
   {
	  $palabra_anterior = "";
	  //$lista[] = '';
	  
	  // Conexion con la base de datos
	  include ("../../comun/conexion.php");
	  
	  $lista = array();
	  
      for($i = 0; $i < $longitud; $i++)
      {   
         if(!esCaracterSeparador($body[$i])) // Se lee un caracter
         {
            $palabra .= $body[$i];
            $letra = 1;
         }
         else // Se lee un signo ortografico o un espacio
         {			
            if($letra == 1 && $palabra != "")
            {	
			    //$lista[convertirMayusculas($palabra)]++; // Incrementamos la frecuencia de la palabra (en mayusculas)
				if (isset($lista[convertirMayusculas($palabra)]))
					$lista[convertirMayusculas($palabra)]++;
				else
					$lista[convertirMayusculas($palabra)] = 1;
					
				$palabra_compuesta = $palabra_anterior. " " .$palabra;
				//echo $palabra_compuesta;
				//echo "--";
				$salir = false;
				$consulta = "SELECT termino FROM glosario WHERE term_compuesto=1";
				$res2 = mysql_query($consulta) or die (mysql_error());
				while($obj = mysql_fetch_object($res2))
				{
					if (($obj->termino == $palabra_compuesta) && !$salir)
					{
						if (isset($lista[convertirMayusculas($palabra_compuesta)]))
							$lista[convertirMayusculas($palabra_compuesta)]++;
						else
							$lista[convertirMayusculas($palabra_compuesta)] = 1;
						$salir = true;
					}
				}
				$palabra_anterior = $palabra;
            }
            $palabra = "";
            $letra = 0;
         }
      }
   }

   if($palabra != "")
   {
      $lista[convertirMayusculas($palabra)]++; //Incrementamos la frecuencia de la ultima palabra de la linea

	  $palabra_compuesta = $palabra_anterior." ".$palabra;
	  $salir = false;
	  $consulta = "SELECT termino FROM glosario WHERE term_compuesto=1";
	  $res2 = mysql_query($consulta) or die (mysql_error());
	  while($obj = mysql_fetch_object($res2))
	  {
 		  if (($obj->termino == $palabra_compuesta) && !$salir)
		  {
			  $lista[convertirMayusculas($palabra_compuesta)]++; // Incrementamos la frecuencia de la palabra (en mayusculas)
			  $salir = true;
		  }
	  }
   }

    // Unir en un solo elemento un termino y su plural (se suman sus frecuencias de aparicion)
   agruparPlurales($lista, $idioma);

   // Grabamos en labase de datos
   include ("../../comun/conexion.php");
   $patron = "/^[[:digit:]]+$/"; // Patron para digitos
   
   while($elemento = each($lista))
   {
      if($elemento[0] != " " && $elemento[1] > 0 && !preg_match($patron, $elemento[0]))
      {
         $lista_consulta = "INSERT INTO lista_terminos (id_texto, frecuencia, id_termino) VALUES (".$texto.",".$elemento[1].",'".$elemento[0]."')";
	     $res = mysql_query($lista_consulta) or die ($no_creacion_contexto);
      }
   }
   
   mysql_close($enlace);
   
}


//=================================================================================================

function agruparPlurales($lista, $idioma)

/*-------------------------------------------------------------------------------------------------
  Funcion de agrupacion de un termino con su singular en una lista de palabras.

  ENT: $lista  - lista de palabras
       $idioma - idioma del texto
  SAL: $lista
-------------------------------------------------------------------------------------------------*/

{
	$temp = array();
	while($elemento = each($lista))
	//foreach ($lista as $elemento)
	{
	   if($elemento[1] > 0)
	   {
          $termino = $elemento[0];     // -> $elemento[0] es la palabra
	      $frecuencia = $elemento[1];  // -> $elemento[1] es la frecuencia de la palabra

	      $termino_pl = eliminarTildes(convertirMayusculas(crearPlural($termino, $idioma)));

	      if(isset($lista[$termino_pl]) > 0 || (isset($temp[$termino_pl])) > 0)
	      {
			  if($lista[$termino_pl] > 0)
			  {
			     $temp[$termino] = $lista[$termino] + $lista[$termino_pl];
			  }
			  else
			  {
			     $temp[$termino] = $lista[$termino] + $temp[$termino_pl];
			  }
			  $lista[$termino_pl] = 0;
			  $temp[$termino_pl] = 0;
	      }
		  else
		  {
		     $temp[$termino] = $frecuencia;
		  }
	   }
	}

	$lista = $temp;
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
   if ($longitud == 1)
		$ant_final = $term;
   else
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



//=================================================================================================

function esCaracterSeparador($c)

/*-------------------------------------------------------------------------------------------------
  Funcion que comprueba si un caracter no pertenece al rango {a..Z}.

  ENT: $c - caracter
  SAL: 1 si es caracter separador
       0 e.o.c.
-------------------------------------------------------------------------------------------------*/

{
	if((ord($c) > 47 && ord($c) < 58 ) ||	 
       (ord($c) > 64 && ord($c) < 91 ) ||	
	   (ord($c) > 96 && ord($c) < 123 ) ||	
	   (ord($c) > 191 && ord($c) < 215 ) ||	
	   (ord($c) > 216 && ord($c) < 222 ) ||	
	   (ord($c) > 223 && ord($c) < 247 ) ||	
	   (ord($c) > 248 && ord($c) < 254 ) 
	   
		)
	{
		return 0;
	}
	else
	{
		return 1;
	}
}



//=================================================================================================

function esCaracter($c)

/*-------------------------------------------------------------------------------------------------
  Funcion que comprueba si un caracter no pertenece al rango {a..Z}.

  ENT: $c - caracter
  SAL: 1 si es caracter separador
       0 e.o.c.
-------------------------------------------------------------------------------------------------*/


{
   if((ord($c) > 47 && ord($c) < 58 ) ||	 
       (ord($c) > 64 && ord($c) < 91 ) ||	
	   (ord($c) > 96 && ord($c) < 123 ) ||	
	   (ord($c) > 191 && ord($c) < 215 ) ||	
	   (ord($c) > 216 && ord($c) < 222 ) ||	
	   (ord($c) > 223 && ord($c) < 247 ) ||	
	   (ord($c) > 248 && ord($c) < 254 ) 
		)
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
  Funcion de conversion a mayusculas de una palabra.

  ENT: $palabra
  SAL: mayusculas de $palabra
-------------------------------------------------------------------------------------------------*/

{
	$longitud = strlen($palabra);
	$p = "";

	for($i = 0; $i < $longitud; $i++)
	{
		$c = $palabra[$i];

		if( ord($c) > 96 && ord($c) < 123 )
		{
	       $p .= strtoupper($c);
		}
		else
		{
		   switch($c)
		   {
		      case("á"): $p .= 'Á'; break;
		      case("é"): $p .= 'É'; break;
		      case("í"): $p .= 'Í'; break;
		      case("ó"): $p .= 'Ó'; break;
		      case("ú"): $p .= 'Ú'; break;
		      case("à"): $p .= 'Á'; break;
		      case("è"): $p .= 'È'; break;
		      case("è"): $p .= 'Ì'; break;
		      case("ò"): $p .= 'Ò'; break;
		      case("ù"): $p .= 'Ù'; break;
		      case("ü"): $p .= 'Ü'; break;
		      case("ñ"): $p .= 'Ñ'; break;
		      default: $p .= $c; break;
		   }
		}
	}

	return $p;
}



//=================================================================================================

function eliminarTildes($palabra)

/*-------------------------------------------------------------------------------------------------
  Funcion de eliminacion de tildes de una palabra.

  ENT: $palabra
  SAL: $palabra sin tildes
-------------------------------------------------------------------------------------------------*/

{
	$longitud = strlen($palabra);
	$p = "";

	for($i = 0; $i < $longitud; $i++)
	{
		$c = $palabra[$i];

		switch($c)
		{
		   case("á"): $p .= 'a'; break;
		   case("Á"): $p .= 'A'; break;
		   case("é"): $p .= 'e'; break;
		   case("É"): $p .= 'E'; break;
		   case("í"): $p .= 'i'; break;
		   case("Í"): $p .= 'I'; break;
		   case("ó"): $p .= 'o'; break;
		   case("Ó"): $p .= 'O'; break;
		   case("ú"): $p .= 'u'; break;
		   case("Ú"): $p .= 'U'; break;
		   case("ü"): $p .= 'u'; break;
		   case("Ü"): $p .= 'U'; break;
		   default: $p .= $c; break;
		   }
	}

	return $p;
}



//=================================================================================================

function convertirMinusculas($palabra)

/*-------------------------------------------------------------------------------------------------
  Funcion de conversion a minusculas de una palabra.

  ENT: $palabra
  SAL: minusculas de $palabra
-------------------------------------------------------------------------------------------------*/

{
	$longitud = strlen($palabra);
	$p = "";

	for($i = 0; $i < $longitud; $i++)
	{
		$c = $palabra[$i];

		if( ord($c) > 64 && ord($c) < 91 )
		{
	       $p .= strtolower($c);
		}
		else
		{
		   switch($c)
		   {
		      case("Á"): $p .= 'á'; break;
		      case("É"): $p .= 'é'; break;
		      case("Í"): $p .= 'í'; break;
		      case("Ó"): $p .= 'ó'; break;
		      case("Ú"): $p .= 'ú'; break;
		      case("À"): $p .= 'à'; break;
		      case("È"): $p .= 'è'; break;
		      case("Ì"): $p .= 'ì'; break;
		      case("Ò"): $p .= 'ò'; break;
		      case("Ù"): $p .= 'ù'; break;
		      case("Ü"): $p .= 'ü'; break;
		      case("Ñ"): $p .= 'ñ'; break;
		      default: $p .= $c; break;
		   }
		}
	}

	return $p;
}



//=================================================================================================

function obtenerAnyo($fecha)

/*-------------------------------------------------------------------------------------------------
  Funcion de obtencion del anyo de una fecha determinada.

  ENT: $fecha
  SAL: anyo de la fecha
-------------------------------------------------------------------------------------------------*/

{
   return substr($fecha, 0, 4);
}



//=================================================================================================

function obtenerMes($fecha)

/*-------------------------------------------------------------------------------------------------
  Funcion de obtencion del mes de una fecha determinada.

  ENT: $fecha
  SAL: mes de la fecha
-------------------------------------------------------------------------------------------------*/


{
   return substr($fecha, 5, 2);
}



//=================================================================================================

function obtenerDia($fecha)

/*-------------------------------------------------------------------------------------------------
  Funcion de obtencion del dia de una fecha determinada.

  ENT: $fecha
  SAL: dia de la fecha
-------------------------------------------------------------------------------------------------*/

{
   return substr($fecha, 8, 2);
}



//=================================================================================================

function crearContexto($vector, $i)

/*-------------------------------------------------------------------------------------------------
  Funcion de creacion de un contexto.

  ENT: $vector - lista de palabras de un texto
       $i      - elemento alrededor del cual se crea el contexto
  SAL: contexto
-------------------------------------------------------------------------------------------------*/

{
   $ancho_contexto = 50;
   
   $longitud = 0;
   foreach($vector as $x)
   {
      $longitud++;
   }

   if($i >= $ancho_contexto) //-- Fijar el inicio del contexto
   {
      $elem_ini = $i - $ancho_contexto;
   }
   else
   {
      $elem_ini = 0;	 
   }

   if($i + $ancho_contexto <= $longitud)  //-- Fijar el fin del contexto
   {
      $elem_fin = $i + $ancho_contexto;
   }
   else
   {
      $elem_fin = $longitud;	 
   }   

   $contexto = '';
   for($i = $elem_ini; $i <= $elem_fin; $i++)  //-- Crear contexto
   {
      if($vector[$i] != '\n')
	  {
	     $contexto .= $vector[$i];
	  }
   }

   return $contexto;
}



//=================================================================================================

function extraerElementos($texto)  

/*-------------------------------------------------------------------------------------------------
  Funcion de extraccion de palabras y elementos ortograficos de un texto.

  ENT: $texto
  SAL: listado de elementos de un texto
-------------------------------------------------------------------------------------------------*/

{

   $i = 0;  // -> puntero a una posicion del vector resultado
   $es_caracter = 0;
   $encontrada = 0;
   $palabra = "";

   for($j = 0; $j < strlen($texto); $j++)
   {
      $c = $texto[$j];

      if(esCaracter($texto,$j)) // Se ha leido un caracter
	  {
	     $palabra .= $c;
	     $es_caracter = 1;
	  }
	  else                    // Se la leido un "no caracter"
	  {
	     if($es_caracter) // Tenemos una palabra
	     {
            $resultado[$i] = $palabra;
			$i++;	   
			$palabra = "";
			$es_caracter = 0;
		 }
		 $resultado[$i] = $c;
		 $i++;
      }
   }

   $resultado[$i] = $palabra;  // Ultima palabra del texto

   return $resultado;
}

?>