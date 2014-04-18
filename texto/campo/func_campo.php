<!-- func_campo.php ---------------------------------------------------------------------------

     Funciones para la administracion de campos de texto.
---------------------------------------------------------------------------------------------------
     Copyright (c) 2006 Raul BARAHONA CRESPO
     Verbatim copying and distribution of this entire document is permitted in 
     any medium, provided this notice is preserved. 

----------------------------------------------------------------------------------------------- -->

<?php 

//=================================================================================================

function listar_campos()

/*-------------------------------------------------------------------------------------------------
  Funcion de listado de campos por pantalla y las operaciones sobre campos de texto.

  ENT: -
  SAL: listado de campos por pantalla, operaciones sobre campos
-------------------------------------------------------------------------------------------------*/

{
   // Conexion a la base de datos
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

   $consulta= "SELECT id_campo,description_esp,description_ing FROM campo";
   $resultado = mysql_query($consulta) or die($lectura_campos_incorrecta . mysql_error()); 

   echo"<p align=\"center\">
        <table border=\"0\" cellpadding=\"4\" cellspacing=\"1\" bgcolor=\"#CC0000\">
           <tr bgcolor=#D8D9A4>       
              <td><b>".$signatura."</b></td><td><b>".$campo."</b></td><td></td>
           </tr>";


   // Mostrar campos por pantalla

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
	  
	  $href_modificar = 'operacion_campo.php?arg_op=modificar&id_campo='.$obj->id_campo; 
	  $href_eliminar = 'operacion_campo.php?arg_op=eliminar&id_campo='.$obj->id_campo. '&description_esp='.$obj->description_esp. '&description_ing='.$obj->description_ing;
	  
	  if ($lg == "esp") // Se muestran solo la info en espanol y si esta no existe en ingles.
	  {
		if ($obj->description_esp == '') // Se muestra el nombre en ingles
		{
			echo "<td><b>$obj->id_campo</b></td><td>$obj->description_ing</td>";
		}
		else // Se muestra en espanol
		{
			echo "<td><b>$obj->id_campo</b></td><td>$obj->description_esp</td>";
		}
	  }
	  else
	  {
		if ($obj->description_ing == '') // Se muestra el nombre en espanol
		{
			echo "<td><b>$obj->id_campo</b></td><td>$obj->description_esp</td>";
		}
		else // Se muestra en ingles
		{
			echo "<td><b>$obj->id_campo</b></td><td>$obj->description_ing</td>";
		}
	  }
	  //echo "<td><b>$obj->id_campo</b></td><td>$obj->description</td>";
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

function alta_campo($description_esp, $description_ing)

/*-------------------------------------------------------------------------------------------------
  Funcion de alta de campo de texto.

  ENT: $description_esp    - descripcion del campo de texto en espanol
       $description_ing    - descripcion del campo de texto en ingles
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

   // Comprobamos si el usuario existe

      $consulta = "SELECT description_esp FROM campo WHERE description_esp = '$description_esp'";
      $res = mysql_query($consulta) or die($lectura_campos_incorrecta);

      if (mysql_num_rows($res) != 0 && $description_esp != '')
      {
         echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$campo_ya_existe."<br>";
         echo $mensaje25."<br><b>$description_esp</b></p>";
      }
      else // si no existe el nombre del campo en espanol, se comprueba si existe el nombre del campo en ingles
      {
	    $consulta = "SELECT description_ing FROM campo WHERE description_ing = '$description_ing'";
        $res = mysql_query($consulta) or die($lectura_campos_incorrecta);

        if (mysql_num_rows($res) != 0 && $description_ing != '')
        {
			echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$campo_ya_existe."<br>";
			echo $mensaje25."<br><b>$description_ing</b></p>";
        }
		else // No existe el nombre del campo ni en ingles ni en espanol asique se introduce en la bbdd
		{
			$consulta = "SELECT MAX(id_campo) FROM campo";
			$codigo = mysql_query($consulta) or die ($maximo_fallo);
			$id_campo_max = mysql_result($codigo,0);
			
			$id_campo_max = $id_campo_max + 1; // Incrementamos el indice
			$consulta= "INSERT INTO campo (id_campo, description_esp, description_ing) VALUES ('$id_campo_max', '$description_esp', '$description_ing')";
			$resultado = mysql_query($consulta) or die($mensaje25 . mysql_error());

			alta_historico ("alta", $_SESSION['username'], "campo", "Signatura: ".$id_campo_max."<br>Campo (espa&ntilde;ol):".$description_esp."<br>Campo (ingl&eacute;s):".$description_ing);

			echo "<p class=\"Resultado\"><img border=\"0\" src=\"../../imagenes/info.gif\"><br>".$mensaje26."<br><b> $id_campo_max </b><br>";
			echo $mensaje27."</p>";
			echo "<br>";
		}
      }

      mysql_close($enlace); 

	  echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='menu_admin_campo.php';\" /></p>";
}



//=================================================================================================

function modificar_campo($id_campo, $description_esp, $description_ing)

/*-------------------------------------------------------------------------------------------------
  Funcion de modificacion de campo de texto.

  ENT: $id_campo       - identificador del campo de texto
       $description_esp - descripcion del campo de texto en espanol (modificada o no)
	   $description_ing    - descripcion del campo de texto en ingles (modificada o no)
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

      $consulta= "UPDATE campo SET id_campo='$id_campo', description_esp='$description_esp', description_ing='$description_ing'  WHERE id_campo='$id_campo'";
      $resultado = mysql_query($consulta) or die($no_modificacion_campo . mysql_error()); 

      alta_historico ("modificar", $_SESSION['username'], "campo", "Signatura antigua: ".$id_campo."<br>Signatura nueva: ".$id_campo."<br>Campo(espa&ntilde;ol):".$description_esp."<br>Campo(ingl&eacute;s):".$description_ing);
      // Mostrar resultados 

      echo "<p class=\"Resultado\"><img border=\"0\" src=\"../../imagenes/info.gif\"><br>".$mensaje26."<br><b>$id_campo</b><br>";
      echo $mensaje29."</p>";
   //}

   mysql_close($enlace);

   echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='menu_admin_campo.php';\" /></p>"; 
}



//=================================================================================================

function eliminar_campo($id_campo)

/*-------------------------------------------------------------------------------------------------
  Funcion de eliminacion de campo de texto.

  ENT: $id_campo - identificador de campo de texto
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

   $consulta= "DELETE FROM campo WHERE id_campo = '$id_campo' ";
   $resultado = mysql_query($consulta) or die($no_eliminacion_campo . mysql_error()); 

   alta_historico ("eliminar", $_SESSION['username'], "campo", "Signatura: ".$id_campo);
   mysql_close($enlace);


   // Mostrar resultados

   echo "<p class=\"Resultado\"><img border=\"0\" src=\"../../imagenes/info.gif\"><br>".$mensaje26."<br><b>$id_campo</b><br>";
   echo $mensaje30."</p>";

   echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='menu_admin_campo.php';\" /></p>";
}
?>