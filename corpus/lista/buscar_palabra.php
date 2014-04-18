<!-- buscar_palabra.php ---------------------------------------------------------------------------

     Funciones para la visualizacion de un termino del listado de palabras de un texto.

----------------------------------------------------------------------------------------------- -->


<?php 

//=================================================================================================

function visualizarPalabra($texto, $formato, $palabra, $idioma)

/*-------------------------------------------------------------------------------------------------
  Visualizacion (en formato HTML) de todas las ocurrencias de un termino en un texto.

  ENT: $texto   - identificador del texto en el que se realiza la busqueda
       $formato - formato del texto con identificador $texto
       $palabra - termino que se busca en el texto con identificador $texto
	   $idioma  - idioma del texto
  SAL: numero de ocurrencias de $palabra en el texto $texto y visualizacion del texto con 
       las ocurrencias del termino marcadas y resaltadas
-------------------------------------------------------------------------------------------------*/

{
   $vector = extraerElementosBBDD($texto);  // -> $vector contiene todas las palabras y elementos
                                          //    graficos del texto

   // Busqueda del termino en el vector

   $elemento = 0;
   foreach($vector as $x)
   {
      $palabra_pl = crearPlural($palabra, $idioma);      
      
	  if(strcasecmp($palabra, $x) == 0 || strcasecmp($palabra_pl, $x) == 0)  // Termino encontrado
	  {
		 // Resaltar el termino dentro del texto
		 $elemento ++;
		 echo "<a name='$elemento'><span style='background-color: #FFFF99'><font size='4' color='#CC0000'><b>";
	     echo $x;
		 echo "</b></font></span></a> ";
	  }
	  else
	  {
		 if($x == "\n")  // Salto de linea
		 {
			if($formato == "texto")
			{
		       echo "<br>";
			}
		 }
		 else
		 {
		    echo $x;
		 }
	  }
   }

   return $elemento;
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
         $palabra .= $texto[$j];  // Anadir el caracter a la palabra temporal leida
         $es_caracter = 1;
      }
      else // Se ha leido un "no caracter"
      {
         if($es_caracter) // Tenemos una palabra
         {
            $resultado[$i] = $palabra;  // Anadir la nueva palabra al vector resultado
            $i++;
            $palabra = "";
            $es_caracter = 0;
         }
         
         $resultado[$i] = $texto[$j];  // Anadir el "no caracter" al vector resultado
         $i++;
      }
   }
   $resultado[$i] = $palabra;  // Se anade el ultimo elemento leido

   return $resultado;
}

//=================================================================================================
function extraerParrafos($texto)
{
	$parrafos = explode(".\r", $texto);
	return $parrafos;
}
//=================================================================================================
function perteneceParrafo ($parrafo, $palabra)
{
	$vector = extraerElementosBBDD($parrafo, "texto");
	$pertenece = false;
	$i = 0;
	while (($i < count($vector)) && !$pertenece)
	{
		if (strcasecmp($vector[$i], $palabra) == 0)
		{
			$pertenece = true;
		}
		else
		{
			$i++;
		}
	}
	return $pertenece;	
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
	  (ord($c) > 248 && ord($c) < 254 )     // vocal 'u' acentuada
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
   if ($longitud > 1)
   {
		$ant_final = $term[$longitud-2];  // -> antepenultimo caracter del termino
   }
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
		if($ant_final != '')
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