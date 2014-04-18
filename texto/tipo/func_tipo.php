<!-- func_tipo.php ---------------------------------------------------------------------------

     Funciones para la administracion de tipos de texto.
---------------------------------------------------------------------------------------------------

     FUNCIONES:
	    listar_tipos()
		alta_tipo($id_tipo, $scheme, $h_keyword)
		modificar_tipo($id_tipo, $nuevo_id_tipo, $scheme, $h_keyword)
		eliminar_tipo($id_tipo)
----------------------------------------------------------------------------------------------- -->

<?php 

//=================================================================================================

function listar_tipos()

/*-------------------------------------------------------------------------------------------------
  Funcion de listado de tipos de texto por pantalla.

  ENT: -
  SAL: listado de tipos por pantalla
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
	

   $consulta= "SELECT id_tipo,scheme_esp,h_keyword_esp,scheme_ing,h_keyword_ing FROM tipo";
   $resultado = mysql_query($consulta) or die($lectura_tipos_incorrecta . mysql_error()); 

   echo"<p align=\"center\">
        <table border=\"0\" cellpadding=\"4\" cellspacing=\"1\" bgcolor=\"#CC0000\">
           <tr bgcolor=#D8D9A4>       
              <td><b>".$signatura."</b></td><td><b>".$tipo_texto."</b></td><td><b>".$descripcion."</b></td><td></td>
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
	  if ($lg == "esp") // Se muestran solo la info en espanol y si esta no existe en ingles.
	  {
	    $href_modificar = 'operacion_tipo.php?arg_op=modificar&id_tipo='.$obj->id_tipo; 
	    $href_eliminar = 'operacion_tipo.php?arg_op=eliminar&id_tipo='.$obj->id_tipo.'&scheme_esp='.$obj->scheme_esp.'&h_keyword_esp='.$obj->h_keyword_esp.'&scheme_ing='.$obj->scheme_ing.'&h_keyword_ing='.$obj->h_keyword_ing;
	    if ($obj->scheme_esp == '' && $obj->h_keyword_esp == '') // Se muestra en ingles o vacio
		{
			echo "<td><b>$obj->id_tipo</b></td><td>$obj->scheme_ing</td><td>$obj->h_keyword_ing</td>";
		}
		else if ($obj->scheme_esp == '' && $obj->h_keyword_esp != '')
		{
			echo "<td><b>$obj->id_tipo</b></td><td>$obj->scheme_ing</td><td>$obj->h_keyword_esp</td>";
		}
		else if ($obj->scheme_esp != '' && $obj->h_keyword_esp == '')
		{
			echo "<td><b>$obj->id_tipo</b></td><td>$obj->scheme_esp</td><td>$obj->h_keyword_ing</td>";
		}
		else
		{
			echo "<td><b>$obj->id_tipo</b></td><td>$obj->scheme_esp</td><td>$obj->h_keyword_esp</td>";
		}
		// echo "<td><b>$obj->id_tipo</b></td><td>$obj->scheme_esp</td><td>$obj->h_keyword_esp</td><td>$obj->scheme_ing</td><td>$obj->h_keyword_ing</td>";
	    echo "<td><a href=\"$href_modificar\"><img border=\"0\" src=\"../../imagenes/modificar_ico.gif\" title=".$modificar."></a>"; // Modificar
	    echo "&nbsp;&nbsp;&nbsp;&nbsp;";
	    echo "<a href=\"$href_eliminar\"><img border=\"0\" src=\"../../imagenes/papelera_ico.png\" title=".$eliminar."></a></td>";  // Eliminar
	    echo "</tr>";
	    $num++;
	  }
	  else // se muestra la info en ingles y si esta no existe en espanol
	  {
	    $href_modificar = 'operacion_tipo.php?arg_op=modificar&id_tipo='.$obj->id_tipo; 
	    $href_eliminar = 'operacion_tipo.php?arg_op=eliminar&id_tipo='.$obj->id_tipo.'&scheme_esp='.$obj->scheme_esp.'&h_keyword_esp='.$obj->h_keyword_esp.'&scheme_ing='.$obj->scheme_ing.'&h_keyword_ing='.$obj->h_keyword_ing;
	    if ($obj->scheme_ing == '' && $obj->h_keyword_ing == '') // Se muestra en ingles o vacio
		{
			echo "<td><b>$obj->id_tipo</b></td><td>$obj->scheme_esp</td><td>$obj->h_keyword_esp</td>";
		}
		else if ($obj->scheme_ing == '' && $obj->h_keyword_ing != '')
		{
			echo "<td><b>$obj->id_tipo</b></td><td>$obj->scheme_esp</td><td>$obj->h_keyword_ing</td>";
		}
		else if ($obj->scheme_ing != '' && $obj->h_keyword_ing == '')
		{
			echo "<td><b>$obj->id_tipo</b></td><td>$obj->scheme_ing</td><td>$obj->h_keyword_esp</td>";
		}
		else
		{
			echo "<td><b>$obj->id_tipo</b></td><td>$obj->scheme_ing</td><td>$obj->h_keyword_ing</td>";
		}
		// echo "<td><b>$obj->id_tipo</b></td><td>$obj->scheme_esp</td><td>$obj->h_keyword_esp</td><td>$obj->scheme_ing</td><td>$obj->h_keyword_ing</td>";
	    echo "<td><a href=\"$href_modificar\"><img border=\"0\" src=\"../../imagenes/modificar_ico.gif\" title=".$modificar."></a>"; // Modificar
	    echo "&nbsp;&nbsp;&nbsp;&nbsp;";
	    echo "<a href=\"$href_eliminar\"><img border=\"0\" src=\"../../imagenes/papelera_ico.png\" title=".$eliminar."></a></td>";  // Eliminar
	    echo "</tr>";
	    $num++;
	  }
	  
   }
   echo "</table></p>";

   mysql_free_result($resultado);

   mysql_close($enlace);   
}



//=================================================================================================

function alta_tipo($scheme_esp, $h_keyword_esp, $scheme_ing, $h_keyword_ing)

/*-------------------------------------------------------------------------------------------------
  Funcion de alta de tipo de texto.

  ENT: $id_tipo - identificador del tipo
       $scheme_esp - nombre del tipo en espanol
	   $h_keyword_esp - descripcion del tipo en espanol
	   $scheme_ing - nombre del tipo en ingles
	   $h_keyword_ing - descripcion del tipo en ingles
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
	
	/* Comprobamos si el tipo existe */
	
	$consulta = "SELECT scheme_esp FROM tipo WHERE scheme_esp = '$scheme_esp'";
        $res = mysql_query($consulta) or die($lectura_tipos_incorrecta);
	
	if (mysql_num_rows($res) != 0 && $scheme_esp != '')
    {
		echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$mensaje10."<br><br>";
		echo $mensaje11."<br><b>$scheme_esp</b></p>";
    }
	else // si no existe en espanol, se comprueba si existe el nombre del tipo en ingles
	{
		$consulta = "SELECT scheme_ing FROM tipo WHERE scheme_ing = '$scheme_ing'";
		$res = mysql_query($consulta) or die($lectura_tipos_incorrecta);
	
		if (mysql_num_rows($res) != 0 && $scheme_ing != '')
		{
			echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$mensaje10."<br><br>";
			echo $mensaje11."<br><b>$scheme_ing</b></p>";
		}
		else // No existe el nombre del tipo ni en ingles ni en espanol asique se introduce en la bbdd
		{
			$consulta = "SELECT MAX(id_tipo) FROM tipo";
			$codigo = mysql_query($consulta) or die ($maximo_fallo);
			$id_tipo_max = mysql_result($codigo,0);
			
			$id_tipo_max = $id_tipo_max + 1; // Incrementamos el indice
			$consulta= "INSERT INTO tipo (id_tipo, scheme_esp, h_keyword_esp, scheme_ing, h_keyword_ing) VALUES ('$id_tipo_max', '$scheme_esp', '$h_keyword_esp', '$scheme_ing', '$h_keyword_ing')";
			$resultado = mysql_query($consulta) or die($no_creacion_tipo_texto . mysql_error());

	        alta_historico ("alta", $_SESSION['username'], "tipo", "Signatura: ".$id_tipo_max."<br>Tipo (espa&ntilde;ol):".$scheme_esp."<br>Descripci&oacute;n:".$h_keyword_esp."<br>Tipo (ingl&eacute;s):".$scheme_ing."<br>Descripci&oacute;n:".$h_keyword_ing);
			
			echo "<p class=\"Resultado\"><img border=\"0\" src=\"../../imagenes/info.gif\"><br>".$mensaje12."<br><b>$id_tipo_max</b><br>";
			echo $mensaje13."</p>";
		}
	}	


   mysql_close($enlace); 

   echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='menu_admin_tipo.php';\" /></p>";
}



//=================================================================================================

function modificar_tipo($id_tipo, $scheme_esp, $h_keyword_esp, $scheme_ing, $h_keyword_ing)

/*-------------------------------------------------------------------------------------------------
  Funcion de modificacion de tipo de texto.

  ENT: $id_tipo       - identificador de tipo de texto
       $scheme_esp    - nombre del tipo en espanol
       $scheme_ing    - nombre del tipo en ingles
       $h_keyword_esp - despcripcion del tipo en espanol
       $h_keyword_ing - descripcion del tipo en ingles
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

      $consulta= "UPDATE tipo SET id_tipo='$id_tipo', scheme_esp='$scheme_esp', h_keyword_esp='$h_keyword_esp', scheme_ing='$scheme_ing', h_keyword_ing='$h_keyword_ing'  WHERE id_tipo='$id_tipo'";
      $resultado = mysql_query($consulta) or die($no_modificacion_tipo_texto . mysql_error()); 

	  alta_historico ("modificar", $_SESSION['username'], "tipo", "Signatura antigua:".$id_tipo."<br>Signatura nueva: ".$id_tipo."<br>Tipo (espa&ntilde;ol):".$scheme_esp."<br>Descripci&oacute;n (espa&ntilde;ol):".$h_keyword_esp."<br>Tipo (ingl&eacute;s):".$scheme_esp."<br>Descripci&oacute;n (ingl&eacute;s):".$h_keyword_ing);

      /* Mostrar resultados */

      echo "<p class=\"Resultado\"><img border=\"0\" src=\"../../imagenes/info.gif\"><br>".$mensaje12."<br><b> $id_tipo</b><br>";
      echo $mensaje16."</p>";
   //}

   mysql_close($enlace);

   echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='menu_admin_tipo.php';\" /></p>"; 
}



//=================================================================================================

function eliminar_tipo($id_tipo)

/*-------------------------------------------------------------------------------------------------
  Funcion de eliminacion de tipo de texto.

  ENT: $id_tipo
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

   $consulta= "DELETE FROM tipo WHERE id_tipo = '$id_tipo' ";
   $resultado = mysql_query($consulta) or die($no_eliminacion_tipo_texto . mysql_error()); 

   alta_historico ("eliminar", $_SESSION['username'], "tipo", "Signatura:".$id_tipo);
   mysql_close($enlace);

   /* Mostrar resultados */

   echo "<p class=\"Resultado\"><img border=\"0\" src=\"../../imagenes/info.gif\"><br>".$mensaje12."<br><b> $id_tipo</b><br>";
   echo $mensaje17."</p>";

   echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='menu_admin_tipo.php';\" /></p>";
}
?>