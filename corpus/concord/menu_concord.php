<?php 
   session_start();header('Content-Type: text/html; charset=utf-8');ini_set("session.cookie_httponly", 1);

   include ("../../comun/permisos.php");
   
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
?>

<!-- menu_concord.php ---------------------------------------------------------------------

     Pagina que presenta los enlaces para la búsqueda de concordancias o coocurrencias. 
---------------------------------------------------------------------------------------------------
     Copyright (c) 2006 Raul BARAHONA CRESPO
     Verbatim copying and distribution of this entire document is permitted in 
     any medium, provided this notice is preserved. 
----------------------------------------------------------------------------------------------- -->


<html>

<head>
	<title>Calíope</title>
	<link rel="stylesheet" type="text/css" href="../../CSS/menu_concord.css">
	<link rel="stylesheet" type="text/css" href="../../comun/estilo.css">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="MSHTML 6.00.2800.1498" name="GENERATOR" />
</head>

<body>

<?php  
   //if(tienePermisos("corpusmenu"))
	if(tienePermisos("corpusconcordanciasmenu"))
   {
?>

	<header>
		<h1>Corpus</h1>
	</header>

<p align="center">
<table border="0">
   <tr class="Menu">
      <td rowspan="3"><img border="0" src="../../imagenes/librolupa.jpg" height="100px" width="171px" ></td>
      <td>&nbsp;</td>
   </tr>
   <tr class="Menu">
      <td height="33px"><a href="menu_concordancias.php?concord=Buscar Concordancias"><?php echo $concord ?></a></td>
   </tr>
   <tr class="Menu">
      <td height="33px"><a href="menu_colocaciones.php?concord=Buscar Colocaciones"><?php echo $colocacion ?></a></td>
   </tr>
   <tr>
      <td align="center" colspan="2">
		<br><input type="button" class="boton boton_volver long_160" value="      <?php echo $salir_principal ?> " onclick="document.location='../../principal.php'" />&nbsp;&nbsp;
      </td>
   </tr>
</table>
</p>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <u>CORPUS</u></td>
   </tr>
</table>

<?php 
   }
   else  // El usuario NO tiene privilegios para acceder a la pagina
   {
      echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   }
?>

</body>

</html>
         