<!-- func_usuario.php ---------------------------------------------------------------------------

     Funciones para la administracion de usuarios.
---------------------------------------------------------------------------------------------------
     Copyright (c) 2006 Raul BARAHONA CRESPO
     Verbatim copying and distribution of this entire document is permitted in 
     any medium, provided this notice is preserved. 
---------------------------------------------------------------------------------------------------
     FUNCIONES:
	    tratar_error($tipo)
		listar_usuarios()
		alta($login, $dni, $password, $privilegios, $nombre, $apellidos, $email)
		modificar($login, $dni, $privilegios, $nombre, $apellidos, $email)
		modificar_password($login, $password)
		eliminar($login)
		alta_usuario_fichero($fichero)
		esCaracter($c)
----------------------------------------------------------------------------------------------- -->

<?php

//=================================================================================================

function tratar_error($tipo)

/*-------------------------------------------------------------------------------------------------
  Funcion de visualizacion de mensajes de error por pantalla.

  ENT: $tipo - tipo de error
  SAL: visualizacion por pantalla del mensaje de error
-------------------------------------------------------------------------------------------------*/

{
	if(isset($_GET['lg']))
	{
		$lg = $_GET['lg'];
		$_SESSION['lg'] = $lg;
		include ("../idioma/".$lg.".php");
	}
	else if(isset($_SESSION['lg']))
	{
		$lg = $_SESSION['lg'];
		include ("../idioma/".$lg.".php");
	}

   if($tipo == '1')  //-- El usuario no existe
   {
      echo "<p class=\"Alerta\">";
	  echo "<img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$error1;
	  echo "</p>";
   }
   if($tipo == '2')  //-- Contrasenya incorrecta
   {
      echo "<p class=\"Alerta\">";
	  echo "<img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$error2;
      echo "</p>";
   }
   if($tipo == '3')  //-- Acceso invalido
   {
      echo "<p class=\"Alerta\">";
      echo "<img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$error3;
      echo "</p>";
   }	
   if($tipo == '4')  //-- Las contrasenyas no coinciden
   {
      echo "<p class=\"Alerta\">";
      echo "<img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$error4;
      echo "</p>";
      echo "<p align=\"center\"><input type=\"button\" class=\"boton long_130 boton_nuevo_usuario\" value=\"      ".$nuevo_usuario." \" onclick=\"document.location='operacion_usuario.php?arg_op=nuevo';\" />&nbsp;&nbsp;";
      echo "<input type=\"button\" class=\"boton long_93 boton_cancelar\" value=\"      ".$boton_cancelar." \" onclick=\"document.location='menu_admin_usuario.php';\" /></p>";
   }
}



//=================================================================================================

function listar_usuarios()

/*-------------------------------------------------------------------------------------------------
  Funcion que lista por pantalla todos los usuarios del sistema (administradores y usuarios
  normales). Se presentan las operaciones sobre los usuarios - alta, baja, modificacion.

  ENT: -
  SAL: listado de usuarios, presentacion de operaciones sobre los usuarios
-------------------------------------------------------------------------------------------------*/

{
   // Consulta a la base de datos
   include ("../comun/conexion.php");
   
   if(isset($_GET['lg']))
	{
		$lg = $_GET['lg'];
		$_SESSION['lg'] = $lg;
		include ("../idioma/".$lg.".php");
	}
	else if(isset($_SESSION['lg']))
	{
		$lg = $_SESSION['lg'];
		include ("../idioma/".$lg.".php");
	}



   //---------- MOSTRAR ADMINISTRADORES ----------

   if (tienePermisos("usuariolistar"))
   {
      $consulta= "SELECT login,nombre,apellidos,email,dni FROM usuario WHERE privilegios = 'admin'";
      $resultado = mysql_query($consulta) or die($lectura_incorrecta_admins . mysql_error()); 

	   echo "<h4>".$titulo_db_admins."</h4>";

	   echo"<p align=\"center\">
			<table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ffffff\">
			   <tr id=\"db_admin\" bgcolor=\"#2980b9\">       
				  <td align=\"center\"><b>Login</b></td><td align=\"center\"><b>".$dni."</b></td>
				  <td align=\"center\"><b>".$nomb."</b></td><td align=\"center\"><b>".$apell."</b></td>
				  <td align=\"center\"><b>Email</b></td><td></td>
			   </tr>";

	   //$num = 0;

	   while($obj = mysql_fetch_object($resultado))
	   {
		  //if(($num % 2) == 0)
		  //{
			 //echo "<tr bgcolor=#FFFFFF>";
		  //}
		  //else
		  //{
			 //echo "<tr bgcolor=#FFFF99>";
		  //}
		  $href_modificar = 'operacion_usuario.php?arg_op=modificar&arg_login='.$obj->login. '&arg_tipo=admin&arg_nombre='.$obj->nombre. '&arg_apellidos='.$obj->apellidos; 
		  $href_eliminar = 'operacion_usuario.php?arg_op=eliminar&arg_login='.$obj->login. '&arg_dni='.$obj->dni. '&arg_tipo=admin&arg_nombre='.$obj->nombre. '&arg_apellidos='.$obj->apellidos;
		  $href_modificar_pass = 'operacion_usuario.php?arg_op=cambiar_contrasena&arg_login='.$obj->login; 
		  echo "<td><b>$obj->login</b></td><td>$obj->dni</td>";
		  echo "<td>$obj->nombre</td><td>$obj->apellidos</td><td>$obj->email</td>";
		  echo "<td><a href=\"$href_modificar\"><img border=\"0\" src=\"../imagenes/modificar_ico.gif\" title=".$modificar_info_user."></a>"; // Modificar
		  echo "&nbsp;&nbsp;";
		  echo "<a href=\"$href_modificar_pass\"><img border=\"0\" src=\"../imagenes/menu_texto.gif\" title=".$cambiar_contrasena."></a>"; // Cambiar contrasenya
		  echo "&nbsp;&nbsp;";
		  		  echo "<a href=\"$href_eliminar\"><img border=\"0\" src=\"../imagenes/papelera_ico.png\" title=".$eliminar_usuario."></a></td>";  // Eliminar
		  echo "</tr>";
		  //$num++;
	   }

	   echo "</table></p>";

	   mysql_free_result($resultado);


	   // Consulta a la base de datos

	   $consulta= "SELECT login,nombre,apellidos,email,dni FROM usuario WHERE privilegios != 'admin'";
	   $resultado = mysql_query($consulta) or die($lectura_incorrecta_users . mysql_error()); 

	   echo "<h4>".$titulo_db_users."</h4>";

	   echo "<P align=center>
			<table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ffffff\">
			 <tr id=\"db_users\" bgcolor=\"#2980b9\">       
				<td align=\"center\"><b>Login</b></td><td align=\"center\"><b>".$dni."</b></td>
				  <td align=\"center\"><b>".$nomb."</b></td><td align=\"center\"><b>".$apell."</b></td>
				  <td align=\"center\"><b>Email</b></td><td></td>
			 </tr>";


	   //---------- MOSTRAR USUARIOS ----------

	   //$num = 0;

	   while($obj = mysql_fetch_object($resultado))
	   {
		  //if(($num % 2) == 0)
		  //{
			 //echo "<tr bgcolor=#FFFFFF>";
		  //}
		  //else
		  //{
			 //echo "<tr bgcolor=#FFFF99>";
		  //}
		  
		  $href_modificar = 'operacion_usuario.php?arg_op=modificar&arg_login='.$obj->login. '&arg_tipo=usuario&arg_nombre='.$obj->nombre. '&arg_apellidos='.$obj->apellidos; 
		  $href_eliminar = 'operacion_usuario.php?arg_op=eliminar&arg_login='.$obj->login. '&arg_dni='.$obj->dni. '&arg_tipo=usuario&arg_nombre='.$obj->nombre. '&arg_apellidos='.$obj->apellidos; 
		  $href_modificar_pass = 'operacion_usuario.php?arg_op=cambiar_contrasena&arg_login='.$obj->login; 
		  echo "<td><b>$obj->login</b></td><td>$obj->dni</td>";
		  echo "<td>$obj->nombre</td><td>$obj->apellidos</td><td>$obj->email</td>";
		  echo "<td><a href=\"$href_modificar\"><img border=\"0\" src=\"../imagenes/modificar_ico.gif\" title=".$modificar_info_user."></a>"; // Modificar
		  echo "&nbsp;&nbsp;";
		  echo "<a href=\"$href_modificar_pass\"><img border=\"0\" src=\"../imagenes/menu_texto.gif\" title=".$cambiar_contrasena."></a>"; // Cambiar contrasenya
		  echo "&nbsp;&nbsp;";
		  echo "<a href=\"$href_eliminar\"><img border=\"0\" src=\"../imagenes/papelera_ico.png\" title=".$eliminar_usuario."></a></td>";  // Eliminar
		  echo "</tr>";
		  //$num++;
	   }

	   echo "</table></p>";

	   mysql_free_result($resultado);
   }
   else
   {
		// Buscamos el usuario actual para mostrar la posibilidad de modificar sus datos.
	   // Consulta a la base de datos

	   $consulta= "SELECT login,nombre,apellidos,email,dni FROM usuario WHERE login = '" . $_SESSION["username"] ."'";
	   $resultado = mysql_query($consulta) or die($lectura_incorrecta_users . mysql_error()); 

	   echo "<h4>".$titulo_db_users."</h4>";
	   
	   echo "<P align=center>
			<table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ffffff\">
			 <tr id=\"db_users\" bgcolor=\"#2980b9\">       
				<td align=\"center\"><b>Login</b></td><td align=\"center\"><b>".$dni."</b></td>
				  <td align=\"center\"><b>".$nomb."</b></td><td align=\"center\"><b>".$apell."</b></td>
				  <td align=\"center\"><b>Email</b></td><td></td>
			 </tr>";


	   //---------- MOSTRAR USUARIOS ----------

	   //$num = 0;
		
	   while($obj = mysql_fetch_object($resultado))
	   {
		  echo "<tr bgcolor=#FFFFFF>";
		  $href_modificar = 'operacion_usuario.php?arg_op=cambiar_contrasena&arg_login='.$obj->login; 
		  echo "<td><b>$obj->login</b></td><td>$obj->dni</td>";
		  echo "<td>$obj->nombre</td><td>$obj->apellidos</td><td>$obj->email</td>";
		  echo "<td><a href=\"$href_modificar\"><img border=\"0\" src=\"../imagenes/menu_texto.gif\" ></a>"; // Modificar
		  echo "</tr>";
	   }

	   echo "</table></p>";

	   mysql_free_result($resultado);
   }

   mysql_close($enlace);
}



//=================================================================================================

function alta($login, $dni, $pass, $privilegios, $nombre, $apellidos, $email)

/*-------------------------------------------------------------------------------------------------
  Funcion de alta de usuario.

  ENT: $login - login del usuario a dar de alta
       $dni
       $password
       $privilegios
       $nombre
       $apellidos
       $email
  SAL: -
-------------------------------------------------------------------------------------------------*/

{
   // Conexion con la base de datos
   include ("../comun/conexion.php");
   
   if(isset($_GET['lg']))
	{
		$lg = $_GET['lg'];
		$_SESSION['lg'] = $lg;
		include ("../idioma/".$lg.".php");
	}
	else if(isset($_SESSION['lg']))
	{
		$lg = $_SESSION['lg'];
		include ("../idioma/".$lg.".php");
	}


   // Comprobamos si el usuario existe

   $consulta = "SELECT login FROM usuario WHERE login = '$login'";
   $res = mysql_query($consulta) or die($lectura_incorrecta_users . mysql_error());

   if (mysql_num_rows($res) != 0)
   {
      echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$user_ya_existe."<br><br>";
      echo $imposible_crear_user."<br><b>$login</b></p>";
   }
   else
   {	
	// Cifrado de la contraseña
	  $password = sha1($pass);
      
	  $consulta= "INSERT INTO usuario (login, dni, password, privilegios, nombre, apellidos, email) VALUES ('$login', '$dni',  '$password', '$privilegios', '$nombre', '$apellidos', '$email')";
	  $resultado = mysql_query($consulta) or die("Alta incorrecta: " . mysql_error());

      alta_historico ("alta", $_SESSION['username'], "usuario", "login: ".$login."<br>dni:".$dni."<br>perfil:".$privilegios."<br>Nombre:".$nombre."<br>Apellidos:".$apellidos."<br>Email:".$email);
	  
      echo "<p align=center>".$mensaje1. "<b>$login</b> ";
      echo $mensaje2."<br></p>";
   }

   mysql_close($enlace); 

   echo "<p align=\"center\"><input type=\"button\" value=\"".$boton_aceptar."\" onclick=\"document.location='menu_admin_usuario.php';\" /></p>";
}

//=================================================================================================

function modificar($login, $dni, $privilegios, $nombre, $apellidos, $email)

/*-------------------------------------------------------------------------------------------------
  Funcion de modificacion de usuario.

  ENT: $login - login del usuario a modificar
       $dni
       $privilegios
       $nombre
       $apellidos
       $email
  SAL: -
-------------------------------------------------------------------------------------------------*/

{
   // Conexion con la base de datos
   include ("../comun/conexion.php");
   
   if(isset($_GET['lg']))
	{
		$lg = $_GET['lg'];
		$_SESSION['lg'] = $lg;
		include ("../idioma/".$lg.".php");
	}
	else if(isset($_SESSION['lg']))
	{
		$lg = $_SESSION['lg'];
		include ("../idioma/".$lg.".php");
	}

   // Consulta a la base de datos 

   $consulta= "UPDATE usuario SET dni='$dni', privilegios='$privilegios', nombre='$nombre', apellidos='$apellidos', email='$email' WHERE login='$login'";
   $resultado = mysql_query($consulta) or die($modificacion_incorrecta . mysql_error()); 

   alta_historico ("modificar", $_SESSION['username'], "usuario", "login: ".$login."<br>dni:".$dni."<br>perfil:".$privilegios."<br>Nombre:".$nombre."<br>Apellidos:".$apellidos."<br>Email:".$email);
	  
   mysql_close($enlace);


   // Mostrar resultados 

   echo "<p align=center>".$mensaje1. "<b>$login</b> ";
   echo $mensaje3."<br></p>";

   echo "<p align=\"center\"><input type=\"button\" value=\"".$boton_aceptar."\" onclick=\"document.location='menu_admin_usuario.php';\" /></p>";
}

//=================================================================================================

function modificar_password($login, $pass)

/*-------------------------------------------------------------------------------------------------
  Funcion de modificacion de usuario.

  ENT: $login - login del usuario a modificar
       $pass
  SAL: -
-------------------------------------------------------------------------------------------------*/

{
   // Conexion con la base de datos
   include ("../comun/conexion.php");
   
   if(isset($_GET['lg']))
	{
		$lg = $_GET['lg'];
		$_SESSION['lg'] = $lg;
		include ("../idioma/".$lg.".php");
	}
	else if(isset($_SESSION['lg']))
	{
		$lg = $_SESSION['lg'];
		include ("../idioma/".$lg.".php");
	}

	// Cifrado de la contraseña
	$password = sha1($pass);
	  
   // Consulta a la base de datos 
   $consulta= "UPDATE usuario SET password='$password' WHERE login='$login'";
   $resultado = mysql_query($consulta) or die($modificacion_incorrecta . mysql_error()); 

   alta_historico ("modificar", $_SESSION['username'], "usuario", "Password: ".$password);
   mysql_close($enlace);


   // Mostrar resultados 

      echo "<p align=center>".$mensaje1. "<b>$login</b> ";
	  echo $mensaje3."<br></p>";

   echo "<p align=\"center\"><input type=\"button\" value=\"".$boton_aceptar."\" onclick=\"document.location='menu_admin_usuario.php';\" /></p>";
}



//=================================================================================================

function eliminar($login)

/*-------------------------------------------------------------------------------------------------
  Funcion de eliminacion de un usuario.

  ENT: $login - login del usuario a eliminar
  SAL: -
-------------------------------------------------------------------------------------------------*/

{
   // Conexion con la base de datos
   include ("../comun/conexion.php");
   
   if(isset($_GET['lg']))
	{
		$lg = $_GET['lg'];
		$_SESSION['lg'] = $lg;
		include ("../idioma/".$lg.".php");
	}
	else if(isset($_SESSION['lg']))
	{
		$lg = $_SESSION['lg'];
		include ("../idioma/".$lg.".php");
	}

   // Consulta a la base de datos
   $consulta= "DELETE FROM usuario WHERE login = '$login' ";
   $resultado = mysql_query($consulta) or die($imposible_eliminar_user . mysql_error()); 

   alta_historico ("eliminar", $_SESSION['username'], "usuario", "login: ".$login);
   mysql_close($enlace);


   // Mostrar resultados 

      echo "<p align=center>".$mensaje1. "<b>$login</b> ";
   echo $mensaje4."<br></p>";

   echo "<p align=\"center\"><input type=\"button\" value=\"".$boton_aceptar."\" onclick=\"document.location='menu_admin_usuario.php';\" /></p>";
}

//=================================================================================================

function alta_usuario_fichero($fich)

/*-------------------------------------------------------------------------------------------------
  Funcion de alta de usuarios desde fichero.

  ENT: $fichero - fichero que contiene los datos de los usuarios
  SAL: numero de ocurrencias encontradas
-------------------------------------------------------------------------------------------------*/

{
	if(isset($_GET['lg']))
	{
		$lg = $_GET['lg'];
		$_SESSION['lg'] = $lg;
		include ("../idioma/".$lg.".php");
	}
	else if(isset($_SESSION['lg']))
	{
		$lg = $_SESSION['lg'];
		include ("../idioma/".$lg.".php");
	}
   // Lectura de datos desde el fichero

   $fd = fopen($fich,"r");
   $num_usuarios = 0;

   while($linea = fgets($fd, 20))
   {
	  $usuario = "";
      for($i = 0; $i < strlen($linea); $i++)
	  {
		  if( esCaracter($linea[$i]) && $linea[$i] != '')
		  {
		     $usuario .= $linea[$i];
		  }
	  }
	  if(strlen($usuario) > 0)
	  {
         $lista_usuarios[$num_usuarios] = $usuario;
	     $num_usuarios++;
	  }
   }

   fclose($fd);

   echo "<p align=\"center\">".$users_procesados."<br><br>";


   // Conexion con la base de datos
   include ("../comun/conexion.php");

   for($j = 0; $j < $num_usuarios; $j++)
   {
      $usuario = $lista_usuarios[$j];

      // Consulta a la base de datos: comprobamos si el usuario existe

      $consulta = "SELECT login FROM usuario WHERE login = '$usuario'";
      $res = mysql_query($consulta) or die($lectura_incorrecta_users . mysql_error());

      if (mysql_num_rows($res) != 0)
      {
         echo "<b>".$usuario."</b> <img border=\"0\" src=\"../imagenes/ko_tr.png\"><br>";
      }
      else
      {
         $consulta= "INSERT INTO usuario (login, dni, password, privilegios, nombre, apellidos, email) VALUES ('$usuario', '$usuario',  '$usuario', 'usuario', '$usuario', '', '')";
		 $resultado = mysql_query($consulta) or die($creacion_incorrecta . mysql_error());

       	 alta_historico ("alta", $_SESSION['username'], "usuario", "login: ".$usuario."<br>dni:".$usuario."<br>perfil:usuario<br>Nombre:".$usuario."<br>Apellidos:<br>Email:");
        
		 echo "<b>".$usuario."</b> <img border=\"0\" src=\"../imagenes/ok_tr.png\"><br>";
      }
   }

   mysql_close($enlace); 


   // Mostrar informacion de ayuda

   echo "<br>(<img border=\"0\" src=\"../imagenes/ok_tr.png\"> = ".$mensaje5."<br>";
   echo "<img border=\"0\" src=\"../imagenes/ko_tr.png\"> = ".$mensaje6."<br><br>";
   echo "</p>";

   echo "<p align=\"center\"><input type=\"button\" value=\"".$boton_aceptar."\" onclick=\"document.location='menu_admin_usuario.php';\" /></p>";
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
?>