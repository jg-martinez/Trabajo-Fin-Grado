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
   menu_admin_usuario.php
   ------------------------------------------------------------------------------------------------
   Menu de administracion de usuarios del sitio web. Se listan todos los usuarios y todas
   las funciones que pueden realizarse.

-->

<html>

<head>
	<title>Calíope</title>
	<link rel="stylesheet" type="text/css" href="../CSS/admin_usuario.css">
	<link rel="stylesheet" type="text/css" href="../comun/estilo.css">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href='http://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
	<meta content="Microsoft FrontPage 4.0" name=GENERATOR>
</head>

<body>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">



<?php  
   include("func_usuario.php");
   if(tienePermisos("usuariomenuadmin"))
   {
?>
	<header>
		<h1><?php echo $titulo_menu_admin_users ?></h1>
	</header>
	<nav>
	<input id="operacion" type="button" value="Operaciones"/> 
<!--<table border="0" width="100%">
   <tr>
      <td align="center" width="150" valign="top" bgcolor="#FFFF99" class="conborde">
         <p align="center"><br>
         <span class="titulo titulo_gris"><?php echo $operaciones ?></span><br><br>-->
<?php 
      if(tienePermisos("nuevousuario"))
      {
?>			<div id="new_user">
			<input type="button" value="<?php echo $nuevo_user?>"onclick="document.location='operacion_usuario.php?arg_op=nuevo';"/>
			</div>
<?php 
      }

      if(tienePermisos("nuevousuario"))
      {
?>
			<div id="new_users">
			<input type="button" value="<?php echo $nuevos_users?>" onclick="document.location='operacion_usuario.php?arg_op=nuevo_fichero';"/>
			</div>
<?php 
      }

?>
			<div id="back">
            <input type="button" value="<?php echo $salir_principal ?>" onclick="document.location='../encabezado.php'"/>
			</div>
			<div id="help"> 
		    <input type="button" value="<?php echo $ayuda ?>" onclick="document.location='../ayuda/ayuda_admin_usuario.htm'"/>
			</div>
	  </nav>
      <section>

<?php  
	     listar_usuarios(); 
?>

      <section>
 <!--
<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a>   >   <u><?php echo $admin_users ?></u></td>
   </tr>
</table>
-->
<?php 
   }
   else
   {
      echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   }  
?>

</body>

</html>