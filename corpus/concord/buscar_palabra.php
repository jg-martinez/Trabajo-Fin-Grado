<?php

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
   $parrafos = extraerParrafos($texto);
   
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
function extraerParrafos($texto)
{
	$parrafos = explode(".\r", $texto);
	return $parrafos;
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