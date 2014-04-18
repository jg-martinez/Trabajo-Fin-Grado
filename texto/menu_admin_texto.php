<?php 
   session_start();header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);

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


<!-- menu_admin_texto.php ---------------------------------------------------------------------------

     Menu de administracion de textos del sitio web.

----------------------------------------------------------------------------------------------- -->


<html>

<head><title></title>
   <link rel="stylesheet" type="text/css" href="../comun/estilo.css">
   <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
   <meta content="Microsoft FrontPage 4.0" name=GENERATOR>
</head>

<body>

<?php  
   if(tienePermisos("textomenuadmin"))
   {
?>

<p align="center"><span class="titulo titulo_rojo"><?php echo $titulo_menu_admin_texto ?></span><br>
<img border="0" src="../imagenes/linea_horiz.gif" ></p>

<p align="center">
<table border="0">
   <tr class="Menu">
      <td rowspan="4"><img border="0" src="../imagenes/pergamino2.jpg" width="150px" height="225px" >&nbsp;&nbsp;&nbsp;&nbsp;</td>
      <td height="56px"><a href="tipo/menu_admin_tipo.php"><?php echo $tipo_de_texto ?></a></td>
   </tr>
   <tr class="Menu"><td height="56px"><a href="campo/menu_admin_campo.php"><?php echo $campo_texto ?></a></td></tr>
   <tr class="Menu"><td height="56px"><a href="fuente/menu_admin_fuente.php"><?php echo $fuente_texto ?></a></td></tr>
   <tr class="Menu"><td height="56px"><a href="textos/menu_admin_textos.php"><?php echo $textos ?></a></td></tr>
   <tr>
      <td align="center" colspan="2">
		<br><input type="button" class="boton boton_volver long_160" value="      <?php echo $salir_principal ?> " onclick="document.location='../principal.php'" />&nbsp;&nbsp;
      </td>
   </tr>
</table>
</p>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <u><?php echo $titulo_menu_admin_texto ?></u></td>
   </tr>
</table>

<?php 
   }
   else  //-- Usuario tipo USUARIO, sin privilegios para administrar 
   {
      echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   } 
?>

</body>

</html>
         