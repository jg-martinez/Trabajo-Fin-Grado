<?php 
   session_start();header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);
   
   include ("comun/permisos.php");
   
   if(isset($_GET['lg']))
	{
		$lg = $_GET['lg'];
		$_SESSION['lg'] = $lg;
		include ("idioma/".$lg.".php");
	}
	else if(isset($_SESSION['lg']))
	{
		$lg = $_SESSION['lg'];
		include ("idioma/".$lg.".php");
	}
?>
<!-- 
   encabezado.htm
   ------------------------------------------------------------------------------------------------
   Encabezado de la pagina principal para un usuario tipo ADMINISTRADOR. Incluye los menus de 
   acceso a los distintos apartados.
-->

<html>

<head>
<link rel="stylesheet" type="text/css" href="comun/estilo.css">
<meta http-equiv="Content-Language" content="es">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title></title>
<script src="comun/scripts.js">
</script>
<script type="text/javascript" src="comun/jquery.js"></script>
<script type="text/javascript"> 
	$(document).ready(function() { 
		$('.menuOpciones > li').bind('mouseover', openSubMenu);
		$('.menuOpciones > li').bind('mouseout', closeSubMenu);
		function openSubMenu() { $(this).find('ul').css('visibility', 'visible'); };	
		function closeSubMenu() { $(this).find('ul').css('visibility', 'hidden'); };		
}); 
</script>
<!-- <base target="principal"> -->
</head>

<body bgcolor="#FFFFFF">
<?php 
    // se comprueba si tiene permisos para mostrar el menu principal
   if(tienePermisos("encabezado"))
   {
   
    $noactivo = 0;    
?>

<!--<table border="0" width="100%" cellspacing="0" cellpadding="0" style="table-layout:fixed">
  <tr>
    <td width="50%" colspan="2" height="70" valign="top">&nbsp;<a href="principal.php" target="principal"><img border="0" src="imagenes/tit_principal.png"><span class="titulo titulo_cabecera"><?php echo $titulo_secund ?></span></a><br>

	<a href="cerrar_sesion.php" target="_top"><img border="0" src="imagenes/bullet2.gif"><?php echo $salir ?></a>
	
    </td>
    <td width="25%" height="70" align="center">
	   <img border="0" src="imagenes/logocaliope3_peq.gif">
    </td>
    <td width="30%" height="70">
      <p align="right">
		 <a href="JavaScript:refrescarFrames('esp', '<?php  echo $_SESSION['application_url']; ?>')"><img src="imagenes/bandera_esp.gif" border="0" title=<?php echo $espanol ?>></a>
		 <a href="JavaScript:refrescarFrames('ing', '<?php  echo $_SESSION['application_url']; ?>')"><img src="imagenes/bandera_ing.gif" border="0" title=<?php echo $ingles ?> ></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <a href="http://www.fi.upm.es" target="_blank"><img border="0" src="imagenes/logo_facultad.gif" alt="Facultad de Inform&aacute;tica (Universidad Polit&eacute;cnica de Madrid)"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <a href="http://www.fi.upm.es/dlacyt/" target="_blank"><img border="0" src="imagenes/estanteria.gif"></a>
      </p>
    </td>
  </tr>
</table>-->
<table border="0" width="100%" cellspacing="0" cellpadding="0" style="table-layout:fixed">
  <tr class="Menu">
<?php 
      // se comprueba si tiene permisos de usuario o administrador para mostrar un menú u otro
      if(tienePermisos("menuadminusuarios"))
      {
?>
    <td width="15%" height="29" align="center" bgcolor="#000000" background="imagenes/fondo_menu1.gif"><img border="0" src="imagenes/menu_persona.gif" width="20" height="20">
	<a href="./usuario/menu_admin_usuario.php" target="principal"><?php echo $administrar_usuarios ?></a>
    </td>
<?php 
      }
      else
      {
             $noactivo++;
      }

	  if(tienePermisos("historico"))
      {
?>
    <td width="15%" height="29" align="center" bgcolor="#000000" background="imagenes/fondo_menu1.gif"><img border="0" src="imagenes/menu_persona.gif" width="20" height="20">
      <a href="historico/vista_listado.php" target="principal"><?php echo $administrar_historico ?></a>
    </td>
<?
      }
      else
      {
             $noactivo++;
      }  
    
      if(tienePermisos("menuadmintextos"))
      {
?>
    <td width="15%" height="29" align="center" bgcolor="#000000" background="imagenes/fondo_menu1.gif"><img border="0" src="imagenes/menu_texto.gif" width="20" height="20">
      <a href="./texto/menu_admin_texto.php" target="principal"><?php echo $administrar_textos ?></a>
    </td>
<?php 
      }
      else
      {
             $noactivo++;
      }
      
      if(tienePermisos("menuconsultacorpus"))
      {
?>
    <td width="15%" height="29" align="center" bgcolor="#000000" background="imagenes/fondo_menu1.gif"><img border="0" src="imagenes/menu_lupa.gif" width="20" height="20">
      <a href="./corpus/menu_acceso_corpus.php" target="principal"><?php echo $consultar_corpus ?></a>
    </td>
<?php 
      }
      else
      {
             $noactivo++;
      }
      
      if(tienePermisos("menuconsultaglosario"))
      {
?>
  <td width="15%" height="29" align="center" bgcolor="#000000" background="imagenes/fondo_menu1.gif"><img border="0" src="imagenes/menu_libro.gif" width="20" height="20">
      <a href="./glosario/menu_acceso_glosario.php" target="principal">&nbsp;<?php echo $consultar_glosario ?></a>
  </td>
  
  <td width="15%" height="29" align="center" bgcolor="#000000" background="imagenes/fondo_menu1.gif"><img border="0" src="imagenes/menu_persona.gif" width="20" height="20">
<!--	<a href="cerrar_sesion.php" target="_top"><img border="0" src="imagenes/bullet2.gif"><?php echo $salir ?></a> -->
	<ul class ="menuOpciones">
		<li> Opciones
			<ul>
				<li><a href="cerrar_sesion.php" target="_top"><img border="0" src="imagenes/bullet2.gif"><?php echo $salir ?></a></li>
				<li><a href="./usuario/menu_admin_usuario.php" target="principal"><?php echo $administrar_usuarios ?></a></li>
				<!-- Ambas banderas en la misma línea-->
				<li><a href="JavaScript:refrescarFrames('esp', '<?php  echo $_SESSION['application_url']; ?>')"><img src="imagenes/bandera_esp.gif" border="0" title=<?php echo $espanol ?>></a></li>
				<li><a href="JavaScript:refrescarFrames('ing', '<?php  echo $_SESSION['application_url']; ?>')"><img src="imagenes/bandera_ing.gif" border="0" title=<?php echo $ingles ?> ></a></li>
			</ul>
		</li>
	</ul>
    </td>
<?php 
      }
      else
      {
             $noactivo++;
      }

      if ($noactivo > 0)
      {
         $tamano = 20*$noactivo;
?>
<td width="<?php echo $tamano; ?>%" height="29" align="center" bgcolor="#000000" background="imagenes/fondo_menu1.gif"></td>
<?php 
      }
?>
  </tr>
</table>

<?php 
   }
   else
   {
      echo "<p class=\"Alerta\"><img border=\"0\" src=\"imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   }  
?>
<?php 
	if ($_GET['refrescar_frame'] == "S") {
?>
	<script>
		parent.principal.document.location.reload();
	</script>
<?php
	}
?>
</body>
</html>
