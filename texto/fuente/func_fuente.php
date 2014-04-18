<!-- func_fuente.php ------------------------------------------------------------------------------

     Funciones para la administracion de fuentes de texto.

----------------------------------------------------------------------------------------------- -->

<?php 


//=================================================================================================

function listar_fuentes()

/*-------------------------------------------------------------------------------------------------
  Funcion de listado de todas las fuentes de texto.

  ENT: -
  SAL: listado por pantalla de las fuentes de texto
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

   $consulta= "SELECT id_fuente,edition,pub_date,h_title,h_author,pub_place,publisher FROM fuente";
   $resultado = mysql_query($consulta) or die($lectura_fuente_incorrecta . mysql_error()); 

   echo"<p align=\"center\">
        <table border=\"0\" cellpadding=\"4\" cellspacing=\"1\" bgcolor=\"#CC0000\">
           <tr bgcolor=#D8D9A4>       
              <td><b>ISBN/ISSN</b></td><td><b>".$tipo_texto."</b></td><td><b>".$titulo."</b></td><td><b>".$autor."</b></td><td><b>".$lugar."</b></td><td><b>".$editorial."</b></td><td><b>".$fecha."</b></td><td></td>
           </tr>";

   $num = 0;

   while($obj = mysql_fetch_object($resultado))
   {
      if(($num % 2) == 0)
	  {
         echo "<tr bgcolor=#FFFFFF>";
	  }
	  else
	  {
	     echo "<tr bgcolor=#FFFF99>";
	  }
	  $href_modificar = 'operacion_fuente.php?arg_op=modificar&id_fuente='.$obj->id_fuente; 
	  $href_eliminar = 'operacion_fuente.php?arg_op=eliminar&id_fuente='.$obj->id_fuente. '&edition='.$obj->edition.'&h_title='.$obj->h_title.'&h_author='.$obj->h_author;
	  $dia = obtenerDia($obj->pub_date);
	  $mes = obtenerMes($obj->pub_date);
	  $anyo = obtenerAnyo($obj->pub_date);
      echo "<td><b>$obj->id_fuente</b></td><td>$obj->edition</td>";
	  echo "<td><i>$obj->h_title</i></td><td>$obj->h_author</td>";
	  echo "<td>$obj->pub_place</td><td>$obj->publisher</td><td>$dia-$mes-$anyo</td>";
	  echo "<td><a href=\"$href_modificar\"><img border=\"0\" src=\"../../imagenes/modificar_ico.gif\" title=\"".$modificar."\"></a>"; // Modificar
	  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
	  echo "<a href=\"$href_eliminar\"><img border=\"0\" src=\"../../imagenes/papelera_ico.png\" title=\"".$eliminar."\"></a></td>";  // Eliminar
      echo "</tr>";
	  $num++;
   }
   echo "</table></p>";

   mysql_free_result($resultado);

   mysql_close($enlace);   
}



//=================================================================================================

function alta_fuente($id_fuente, $edition, $h_title, $h_author, $pub_place, $publisher, $pub_date)

/*-------------------------------------------------------------------------------------------------
  Funcion de alta de fuente de texto.

  ENT: $id_fuente - identificador de la fuente de texto
       $edition   - tipo de la fuente
	   $h_title   - titulo de la fuente
	   $h_author  - autor de la fuente
	   $pub_place - lugar de publicacion
	   $publisher - editorial
	   $pub_date  - fecha de publicacion
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
   $consulta = "SELECT id_fuente FROM fuente WHERE id_fuente = '$id_fuente'";
   $res = mysql_query($consulta) or die($lectura_fuente_incorrecta);

   if(mysql_num_rows($res) != 0)
   {
      echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$fuente_ya_existe."<br><br>";
      echo $no_creacion_fuente."<br><b>$id_fuente</b></p>";
   }
   else
   {
      $consulta= "INSERT INTO fuente (id_fuente, edition, h_title, h_author, pub_place, publisher, pub_date) VALUES ('$id_fuente', '$edition', '$h_title', '$h_author', '$pub_place', '$publisher', '$pub_date')";
	  $resultado = mysql_query($consulta) or die($no_creacion_fuente . mysql_error());

      alta_historico ("alta", $_SESSION['username'], "campo", "ISBN/ISSN: ".$id_fuente."<br>Tipo: ".$edition."<br>T&iacute;tulo: ".$h_title."<br>Autor: ".$h_author."<br>Lugar/URL: ".$pub_place."<br>Editorial/N&ordm; Revista: ".
      $publisher."<br>Fecha: ".$pub_date);

      echo "<p class=\"Resultado\"><img border=\"0\" src=\"../../imagenes/info.gif\"><br>".$mensaje36."<br><b>$h_title</b><br>";
      echo $mensaje37."</p>";
   }

   mysql_close($enlace); 

   echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='menu_admin_fuente.php';\" /></p>";
}



//=================================================================================================

function modificar_fuente($id_fuente, $nuevo_id_fuente, $edition, $h_title, $h_author, $pub_place, $publisher, $pub_date)

/*-------------------------------------------------------------------------------------------------
  Funcion de modificacion de una fuente de texto.

  ENT: $id_fuente       - identificador de la fuente de texto
       $nuevo_id_fuente - nuevo identificador de la fuente de texto
       $edition         - tipo de la fuente
	   $h_title         - titulo de la fuente
	   $h_author        - autor de la fuente
	   $pub_place       - lugar de publicacion
	   $publisher       - editorial
	   $pub_date        - fecha de publicacion
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

   $consulta= "SELECT id_fuente FROM fuente WHERE id_fuente='$nuevo_id_fuente'";
   $resultado = mysql_query($consulta) or die($lectura_fuente_incorrecta . mysql_error()); 

   if (mysql_num_rows($resultado) != 0 && ($id_fuente != $nuevo_id_fuente))
   {
      echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$mensaje36."<b>$nuevo_id_fuente</b>".$mensaje38."<br>";
      echo $no_modificacion_fuente."<br><b>$id_fuente</b></p>";
   }
   else
   {
      $consulta= "UPDATE fuente SET id_fuente='$nuevo_id_fuente', edition='$edition', h_title='$h_title', h_author='$h_author', pub_place='$pub_place', publisher='$publisher', pub_date='$pub_date'  WHERE id_fuente='$id_fuente'";
      $resultado = mysql_query($consulta) or die($no_modificacion_fuente . mysql_error()); 

      alta_historico ("modificar", $_SESSION['username'], "campo", "ISBN/ISSN antiguo: ".$id_fuente."<br>ISBN/ISSN nuevo: ".$nuevo_id_fuente."<br>Tipo: ".$edition."<br>T&iacute;tulo: ".$h_title."<br>Autor: ".$h_author."<br>Lugar/URL: ".$pub_place."<br>Editorial/N&ordm; Revista: ".$publisher."<br>Fecha: ".$pub_date);
      
	  // Mostrar resultados
      echo "<p class=\"Resultado\"><img border=\"0\" src=\"../../imagenes/info.gif\"><br>".$mensaje36."<br><b>$id_fuente</b><br>";
      echo $mensaje39."</p>";
   }

   mysql_close($enlace);

   echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='menu_admin_fuente.php';\" /></p>"; 
}



//=================================================================================================

function eliminar_fuente($id_fuente)

/*-------------------------------------------------------------------------------------------------
  Funcion de eliminacion de fuente.

  ENT: $id_fuente
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

    $consulta= "DELETE FROM fuente WHERE id_fuente = '$id_fuente' ";
    $resultado = mysql_query($consulta) or die($no_eliminacion_fuente . mysql_error()); 

    alta_historico ("eliminar", $_SESSION['username'], "campo", "ISBN/ISSN: ".$id_fuente);
    mysql_close($enlace);


    // Mostrar resultados 
    echo "<p class=\"Resultado\"><img border=\"0\" src=\"../../imagenes/info.gif\"><br>".$mensaje36."<br><b>$id_fuente</b><br>";
    echo $mensaje40."</p>";

	echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='menu_admin_fuente.php';\" /></p>";
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

?>