<?php 
   session_start();header('Content-Type: text/html; charset=utf-8');ini_set("session.cookie_httponly", 1);
   include ("../comun/permisos.php");
 
   include("func_usuario.php");
   include ("../historico/operaciones_historico.php");   

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
?>

<!-- 
   operacion_usuario2.php
   ------------------------------------------------------------------------------------------------
   Realiza la operacion indicada sobre el usuario: ALTA, BAJA y MODIFICACION.

-->

<html>

<head>
   <title>Calíope</title>
   <link rel="stylesheet" type="text/css" href="../CSS/opusuario2.css">
   <!--<link rel="stylesheet" type="text/css" href="../comun/estilo.css">-->
   	<link href='http://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
   <meta http-equiv=Content-Type content="text/html; charset=utf-8">
   <meta content="Microsoft FrontPage 4.0" name=GENERATOR>
</head>

<body>

<header>
	<h1><?php echo $titulo_menu_admin_users ?></h1>
</header>

<?php
        // recogida de los datos vía POST (los datos que llegan del formulario)
	if (isset($_POST['arg_op']))
	{
		$arg_op = $_POST['arg_op'];
		$_SESSION['arg_op'] = $arg_op;
	}
	else
	{
		$arg_op = $_SESSION['arg_op'];
	}	

    //-- ALTA DE USUARIO ------------------------------------------------------------------------
	  
	  if(tienePermisos("usuariooperacionnuevo") && $arg_op == 'alta')
	  {
              // recogida de los datos vía POST (los datos que llegan del formulario)
		if (isset($_POST['login']) && isset($_POST['dni']) && isset($_POST['password']) && isset($_POST['password2']) && 
			isset($_POST['privilegios']) && isset($_POST['nombre']) && isset($_POST['apellidos']) && isset($_POST['email']))
		{
			$login = $_POST['login'];
			$dni = $_POST['dni'];
			$password = $_POST['password'];
			$password2 = $_POST['password2'];
			$privilegios = $_POST['privilegios'];
			$nombre = $_POST['nombre'];
			$apellidos = $_POST['apellidos'];
			$email = $_POST['email'];
		
			$_SESSION['login'] = $login;
			$_SESSION['dni'] = $dni;
			$_SESSION['password'] = $password;
			$_SESSION['password2'] = $password2;
			$_SESSION['privilegios'] = $privilegios;
			$_SESSION['nombre'] = $nombre;
			$_SESSION['apellidos'] = $apellidos;
			$_SESSION['email'] = $email;
		}
		else
		{
			$login = $_SESSION['login'];
			$dni = $_SESSION['dni'];
			$password = $_SESSION['password'];
			$password2 = $_SESSION['password2'];
			$privilegios = $_SESSION['privilegios'];
			$nombre = $_SESSION['nombre'];
			$apellidos = $_SESSION['apellidos'];
			$email = $_SESSION['email'];
		}

      if($password == $password2)
		  {
         /* $password = md5($password); Eliminamos la conversion en la alternativa*/
         $password = $password;
                // llamada a la funcion que realiza el alta
	       alta($login, $dni, $password, $privilegios, $nombre, $apellidos, $email);
?>
<!--<br>
<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="menu_admin_usuario.php"><?php echo $titulo_menu_admin_users ?></a> > <u><?php echo $titulo_nuevo_usuario ?></u></td>
   </tr>
</table>-->
<?php 
		  }
		  else
		  {
		     tratar_error(4);
		  }
	  }
    else
      //-- MODIFICACION DE USUARIO ----------------------------------------------------------------
        
	  if(tienePermisos("usuariooperacionmodificar") && $arg_op == 'modificar')
	  {
              // recogida de los datos vía POST (los datos que llegan del formulario)
	    if (isset($_POST['login']) && isset($_POST['dni']) &&	isset($_POST['privilegios']) && isset($_POST['nombre']) && 
			isset($_POST['apellidos']) && isset($_POST['email']))
		{
		
			$login = $_POST['login'];
			$dni = $_POST['dni'];
			$privilegios = $_POST['privilegios'];
			$nombre = $_POST['nombre'];
			$apellidos = $_POST['apellidos'];
			$email = $_POST['email'];
			
			$_SESSION['login'] = $login;
			$_SESSION['dni'] = $dni;
			$_SESSION['privilegios'] = $privilegios;
			$_SESSION['nombre'] = $nombre;
			$_SESSION['apellidos'] = $apellidos;
			$_SESSION['email'] = $email;
		}
		else
		{
			$login = $_SESSION['login'];
			$dni = $_SESSION['dni'];
			$privilegios = $_SESSION['privilegios'];
			$nombre = $_SESSION['nombre'];
			$apellidos = $_SESSION['apellidos'];
			$email = $_SESSION['email'];	
		}
		// llamada a la funcion que realiza la modificacion
		modificar($login, $dni, $privilegios, $nombre, $apellidos, $email);
?>
<!--<br>
      <table border="0" width="100%" style="border-top: 1 solid #FF0000">
        <tr>
          <td width="190">
            <img border="0" src="../imagenes/tit_principal_pie.gif">
            </td>
          <td class="Pie">
            <a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="menu_admin_usuario.php"><?php echo $titulo_menu_admin_users ?></a> > <u><?php echo $titulo_modificar_user ?></u>
          </td>
        </tr>
      </table>-->

<?php 
	  }
    else
      //-- MODIFICACION DE PASSWORD ----------------------------------------------------------------

	  if(tienePermisos("usuariooperacioncambiarcontrasena") && $arg_op == 'cambiar_contrasena')
	  {
              // recogida de los datos vía POST (los datos que llegan del formulario)
	    if (isset($_POST['login']) && isset($_POST['password']))
		{
			$login = $_POST['login'];
			$contrasenia = $_POST['password'];
			
			$_SESSION['login'] = $login;
			$_SESSION['password'] = $contrasenia;
		}
		else
		{
			$login = $_SESSION['login'];
			$contrasenia = $_SESSION['password'];
		}
		// llamada a la funcion que realiza la modificacion de la contrasena    
		modificar_password($login, $contrasenia);
?>

<!--      <br>

        <table border="0" width="100%" style="border-top: 1 solid #FF0000">
          <tr>
            <td width="190">
              <img border="0" src="../imagenes/tit_principal_pie.gif">
            </td>
            <td class="Pie">
              <a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="menu_admin_usuario.php"><?php echo $titulo_menu_admin_users ?></a> > <u><?php echo $cambiar_contrasena ?></u>
            </td>
          </tr>
        </table>-->

<?php 
	  }
    else
      //-- BAJA DE USUARIO ------------------------------------------------------------------------

	  if(tienePermisos("usuariooperacionnuevo") && $arg_op == 'eliminar')
	  {
              // recogida de los datos vía POST (los datos que llegan del formulario)
		if (isset($_POST['arg_login']))
		{
			$arg_login = $_POST['arg_login'];
			
			$_SESSION['arg_login'] = $arg_login;
		}
		else
		{
			$arg_login = $_SESSION['arg_login'];
		}
                // llamada a la funcion que realiza la eliminacion del usuario
		eliminar($arg_login);
?>

<!--<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="menu_admin_usuario.php"><?php echo $titulo_menu_admin_users ?></a> > <u><?php echo $eliminar_usuario ?></u></td>
   </tr>
</table>-->

<?php 
	  }
    else
      //-- ALTA DE USUARIOS (DESDE FICHERO) -------------------------------------------------------

	  if(tienePermisos("usuariooperacionnuevo") && $arg_op == 'nuevo_fichero')
	  {
              // recogida de los datos vía POST (los datos que llegan del formulario)
		$fichero = $_FILES['fichero'];
		$f_temp = $fichero['tmp_name'];

		if($f_temp == '')
		{
		    echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>No se seleccion&oacute; ning&uacute;n fichero o el fichero est&aacute; corrupto.</p>";	
			echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='menu_admin_usuario.php';\" /></p>";
		}
		else
		{
                    // llamada a la funcion que realiza el alta de usuarios por fichero
			alta_usuario_fichero($f_temp);
		}
?>
<!--<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="menu_admin_usuario.php"><?php echo $titulo_menu_admin_users ?></a> > <u><?php echo $titulo_nuevo_users ?></u></td>
   </tr>
</table>-->

<?php 
   }
   else
   {
	   echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   }
?>

</body>

</html>