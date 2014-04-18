<?php
   session_start();header('Content-Type: text/html; charset=utf-8');ini_set("session.cookie_httponly", 1);

   include ("../comun/permisos.php");
   
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
   operacion_usuario.php
   ------------------------------------------------------------------------------------------------
   Realiza la operacion indicada sobre el usuario: ALTA, BAJA y MODIFICACION.

-->

<html>

<head>
	<title>Calíope</title>
	<link rel="stylesheet" type="text/css" href="../CSS/op_usuario.css">
	<link href='http://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
	<!--<link rel="stylesheet" type="text/css" href="../comun/estilo.css">-->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta content="Microsoft FrontPage 4.0" name=GENERATOR>

<script type="text/JavaScript">
// codigo para verificar si los campos de los formularios son correctos o no
function check_datos(data)
{    

   // Comprobar NOMBRE (no vacio)

   if(data.login.value == "" ) 
   {
      alert("<?php echo $login_vacio ?>");
	  return false;
   }

   // Comprobar PASSWORD

   if(data.password.value == "" ) 
   {
      alert("<?php echo $pwd_vacio ?>");
	  return false;
   }


   if(data.password.value != data.password2.value)
   {
      alert("<?php echo $pwd_no_iguales ?>");
	  return false;
   }

   // Si se han pasado todas las comprobaciones el formulario es valido

   return true;
}

</script>

</head>

<body>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<?php  
   include("func_usuario.php");

          // recogida de los parametros
	  $arg_op = $_GET['arg_op'];
	  if ($arg_op == "eliminar")
	  {
		$arg_login = $_GET['arg_login'];
		$arg_tipo = $_GET['arg_tipo'];
	    $arg_dni = $_GET['arg_dni'];
		$arg_nombre = $_GET['arg_nombre'];
		$arg_apellidos = $_GET['arg_apellidos'];
	  }
	  else if ($arg_op == "cambiar_contrasena")
	  {
		$arg_login = $_GET['arg_login'];
	  }
	  else if ($arg_op == "modificar")
	  {
		$arg_login = $_GET['arg_login'];
		$arg_tipo = $_GET['arg_tipo'];
		$arg_nombre = $_GET['arg_nombre'];
		$arg_apellidos = $_GET['arg_apellidos'];
	  }

    //-- ALTA DE USUARIO ------------------------------------------------------------------------

	  if(tienePermisos("usuariooperacionnuevo") && $arg_op == 'nuevo') 
	  {
?>
<header>
	<h1><?php echo $titulo_nuevo_usuario ?></h1>
</header>

<!-- FORMULARIO DE ALTA DE USUARIO -->

<form action="operacion_usuario2.php" method=post name="formulario" onSubmit='return check_datos(formulario);'>
   <input type="hidden" name="arg_op" value="alta">
<p align="center">
<table border="0">
   <tr>
      <td>Login*:</td><td><input name="login" size="50" class="obligatorio" title="<?php echo $titulo_user ?>"></td></tr>
   <tr>
      <td><?php echo $dni_matricula ?></td><td><input name="dni" size="50" title="<?php echo $numero_dni ?>"></td></tr>
<tr>
   <td>Password*:</td><td><input type=password name="password" size="49" class="obligatorio" title="<?php echo $titulo_pwd ?>"></td></tr>
<tr>
   <td><?php echo $repetir_pwd."*" ?></td><td><input type=password name="password2" size="49" class="obligatorio" title="<?php echo $repetir_pwd2 ?>"></td></tr>
<tr>
   <td><?php echo $priv ?></td>
   <td id=\"selection\"><SELECT name=privilegios title="<?php echo $priv ?>">
<?php 
	$privilegiosArray = preg_split ("/#/",$permisos["encabezado"]);

	for ($i=1; $i< count($privilegiosArray)-1;$i++) {
		if ($lg == "esp")
			echo "<OPTION VALUE=\"$privilegiosArray[$i]\">".$permisosDescripcionEsp[$i-1]."</OPTION>";
		else
			echo "<OPTION VALUE=\"$privilegiosArray[$i]\">".$permisosDescripcionIng[$i-1]."</OPTION>";
	}
?>
       </SELECT></td>
</TR>
<TR>
   <td><?php echo $nomb ?></td><td><INPUT name=nombre size="50" title="<?php echo $nomb ?>"></td>
</TR>
<TR>
   <td><?php echo $apell ?></td><td><INPUT name=apellidos size="50" title="<?php echo $apell ?>"></td>
</TR>
<TR>
   <td>Email:</td><td><INPUT name=email size="50" title="Email"></td>
</TR>
<tr>
<td></td><td id="message"><p>* <?php echo $mandatory ?></p></td>
</tr>
<TR>
   <td align="center" colspan="2"><br>
	 <input type="submit" value="<?php echo $boton_aceptar ?>" />&nbsp;&nbsp;
	 <input type="button" value="<?php echo $boton_cancelar ?>" onclick="document.location='menu_admin_usuario.php';" />&nbsp;&nbsp;
	 <input type="button" value="<?php echo $limpiar_formulario ?>" onclick="document.formulario.reset();"/>&nbsp;&nbsp;
   </td>
</table>
</p>
</form>

<!--<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="menu_admin_usuario.php"><?php echo $titulo_menu_admin_users ?></a> > <u><?php echo $titulo_nuevo_usuario ?></u></td>
   </tr>
</table>-->

<?php 
    }
    else
      //-- BAJA DE USUARIO ------------------------------------------------------------------------

	  if(tienePermisos("usuariooperacionnuevo") && $arg_op == 'eliminar')  
	  {
?>

<header>
	<h1><?php echo $titulo_menu_admin_users ?></h1>
</header>


<?php  echo "<p align=center>".$mensaje7; ?>

<!-- TABLA que recoge los datos del usuario que se quiere borrar -->
<p align="center">
  <table border="0" width="330" style="border: 1 solid #515151" bgcolor="#FFFFFF" cellspacing="5">
    <tr>
      <td width="80"><b><?php echo $tipo ?></b></td>
      <td width="250"><?php echo $arg_tipo;?></td>
    </tr>
	<tr></tr>
    <tr>
      <td width="20%"><b>Login:</b></td>
      <td width="80%"><?php echo $arg_login;?></td>
    </tr>
	<tr>
      <td width="20%"><b><?php echo $dni_matricula ?></b></td>
      <td width="80%"><?php echo $arg_dni;?></td>
    </tr>
    <tr>
      <td width="20%"><b><?php echo $nomb ?></b></td>
      <td width="80%"><?php echo $arg_nombre;?></td>
    </tr>
    <tr>
      <td width="20%"><b><?php echo $apell ?></b></td>
      <td width="80%"><?php echo $arg_apellidos;?></td>
    </tr>
    <tr>
    	<td colspan="2">
    	</td>
    </tr>
  </table>
</p>

<!-- FORMULARIO de confimacion de borrado o no de usuario -->
<form action="operacion_usuario2.php" method=post>
	<input type="hidden" name="arg_op" value="eliminar">
	<input type="hidden" name="arg_login" value="<?php echo $arg_login?>">
	<table border="0" align="center">
		<tr>
			<td id="form_delete" align="center">
				<input type="submit" value="<?php echo $boton_aceptar?>" />
				<input type="button" value="<?php echo $boton_cancelar?>" onclick="document.location='menu_admin_usuario.php';"/>
			</td>
		</tr>
	</table>
</form>
<br>

<!--<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php $menu_principal ?></a> > <a href="menu_admin_usuario.php"><?php echo $titulo_menu_admin_users ?></a> > <u><?php echo $eliminar_usuario ?></u></td>
   </tr>
</table>-->
<?php 
	  }
    else
      //-- MODIFICACION DE USUARIO ----------------------------------------------------------------

    if(tienePermisos("usuariooperacionmodificar") && $arg_op == 'modificar') 
	  {
         /* Conexion con la base de datos */
         include ("../comun/conexion.php");

         /* Obtenemos los datos de la BD */

         $consulta = "SELECT login,dni,privilegios,nombre,apellidos,email FROM usuario WHERE usuario.login = '$arg_login'";
         $res = mysql_query($consulta);

         mysql_close($enlace);

         /* AQUI SE HACE LA MODIFICACIÓN DE LOS DATOS */

         $fila = mysql_fetch_assoc($res);
         
         $login = $fila["login"]; 
         $dni = $fila["dni"];   
         $privilegios = $fila["privilegios"]; 
         $nombre = $fila["nombre"]; 
         $apellidos = $fila["apellidos"]; 
         $email = $fila["email"];
?>
<p align="center">
<header>
  <h1><?php echo $titulo_modificar_user ?></h1>
</header>
<!-- FORMUALRIO de modificacion de usuario -->
<form action="operacion_usuario2.php" method="post" name="formulario">
	<input type="hidden" name="arg_op" value="modificar">
	<p align="center">
	<table border="0">
		<tr>
          <td>Login:</td>
          <td>
            <b>
              <?php  echo $login;?>
            </b>
            <input type="hidden" name="login" value="<?php echo $login;?>">
          </td>
        </tr>
        <tr>
          <td><?php echo $dni_matricula ?></td>
          <td>
            <input type="text" name="dni" value="<?php echo $dni;?>" size="10" maxlength="10" title="<?php echo $numero_dni ?>">
          </td>
        </tr>
        <tr>
          <td>Password:</td>
          <td>**********</td>
        </tr>
        <tr>
          <td><?php echo $priv ?></td>

   <td><select name=privilegios title="<?php echo $priv ?>">
<?php 
	$privilegiosArray = preg_split ("/#/",$permisos["encabezado"]);

	for ($i=1; $i< count($privilegiosArray)-1;$i++) {
		$selected = "";
		if ($privilegios == $privilegiosArray[$i]) {
			$selected = " SELECTED";
		}
		if ($lg == "esp")
			echo "<option value=\"$privilegiosArray[$i]\"".$selected.">".$permisosDescripcionEsp[$i-1]."</option>";
		else
			echo "<option value=\"$privilegiosArray[$i]\"".$selected.">".$permisosDescripcionIng[$i-1]."</option>";
	}
?>
       </select></td>

		<tr>
			<td><?php echo $nomb ?></td>
			<td><input type="text" name="nombre" value="<?php echo $nombre;?>" size="50" title="<?php echo $nomb ?>"></td>
		</tr>
		<tr>
			<td><?php echo $apell ?></td>
			<td><input type="text" name="apellidos" value="<?php echo $apellidos;?>" size="50" title="<?php echo $apell ?>"></td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><input type="text" name="email" value="<?php echo $email;?>" size="50" title="Email"></td>
		</tr>
		<tr>
			<td align="center" colspan="2">
				<br>
				<input type="submit" value="<?php echo $boton_aceptar?>" />
				<input type="button" value="<?php echo $boton_cancelar?>" onclick="document.location='menu_admin_usuario.php';"/>
				<input type="button" value="<?php echo $limpiar_formulario?>" onclick="document.formulario.reset();"/>
			</td>
		</tr>
	</table>
</p>
</form>

<br>

<!--  <table border="0" width="100%" style="border-top: 1 solid #FF0000">
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
      //-- CAMBIO DE CONTRASENA ----------------------------------------------------------------

    if(tienePermisos('usuariooperacioncambiarcontrasena') && $arg_op == 'cambiar_contrasena') 
	  {
         /* Conexion con la base de datos */
         include ("../comun/conexion.php");

         /* Obtenemos los datos de la BD */

         $consulta = "SELECT login,password FROM usuario WHERE usuario.login = '$arg_login'";
         $res = mysql_query($consulta);


         /* AQUI SE HACE LA MODIFICACION DE LOS DATOS */
         $fila = mysql_fetch_assoc($res);
         
         $login = $fila["login"]; 
         $contrasenia = $fila["password"];

         mysql_close($enlace);
?>

<!-- FORMULARIO de cambio de contrasena -->
      <p align="center">
	  <header>
        <h1><?php echo $titulo_modificar_pwd ?></h1>
	  </header>
      </p>
      <p align="center">
        <table border="0">
          <form action="operacion_usuario2.php" method="post" name="formulario" onSubmit='return check_datos(formulario);'>
            <input type="hidden" name="arg_op" value="cambiar_contrasena">
              <tr>
                <td>Login:</td>
                <td>
                  <b>
                    <?php echo $login; ?>
                  </b>
                  <input type="hidden" name="login" value="<?php echo $login; ?>">
                </td>
              </tr>
              <tr>
                  <td>Password: *</td>
                  <td>
                    <input type="password" name="password" size="49" class="obligatorio" title="Password">
                  </td>
              </tr>
              <tr>
                  <td><?php echo "$repetir_pwd * "?></td>
                  <td><input type=password name="password2" size="49" class="obligatorio" title="<?php echo $repetir_pwd2 ?>"></td>
              </tr>
			  <tr>
				<td></td><td id="message"><p>* <?php echo $mandatory ?></p></td>
			  </tr>
              <tr>
                <td id="form_pass" align="center" colspan="3">
                    <input type="submit" value="<?php echo $boton_aceptar?>"/>
                    <input type="button" value="<?php echo $boton_cancelar?>" onclick="document.location='menu_admin_usuario.php';"/>
					<input type="button" class="boton" value="<?php echo $limpiar_formulario?>" onclick="document.formulario.reset();"/>
                </td>
              </tr>
          </form>
        </table>
      </p>


 <!--       <table border="0" width="100%" style="border-top: 1 solid #FF0000">
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
      //-- ALTA DE USUARIOS (DESDE FICHERO)  ------------------------------------------------------

	  if(tienePermisos("usuariooperacionnuevo") && $arg_op == 'nuevo_fichero')  
	  {
?>
<!-- FORMULARIO para el alta de usuarios mediante un fichero de texto -->
<header>
	<h1><?php echo $titulo_menu_admin_users ?></h1>
</header>
<form action="operacion_usuario2.php" method="post" enctype="multipart/form-data" name="formulario">
  <input type="hidden" name="arg_op" value="nuevo_fichero">
<p align="center">
<?php echo $mensaje8 ?><br><br>
  <?php echo $fichero ?><input name="fichero" type="file" title="<?php echo $fichero_user ?>"><br><br>
  <input type="submit" value="<?php echo $boton_aceptar ?>" />&nbsp;&nbsp;
  <input type="button" value="<?php echo $boton_cancelar ?>" onclick="document.location='menu_admin_usuario.php';" />&nbsp;&nbsp;
  <input type="button" value="<?php echo $limpiar_formulario ?>" onclick="document.formulario.reset();"/>&nbsp;&nbsp;
</p>
</form>

<br>

<!--<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="menu_admin_usuario.php"><?php echo $titulo_menu_admin_users ?></a> > <u><?php echo $titulo_nuevo_users ?>s</u></td>
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