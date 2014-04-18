<?php 
   session_start();header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);

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

<!-- menu_admin_tipo.php --------------------------------------------------------------------------

     Menu de administracion de usuarios del sitio DLACT:Corpus Textual. Se listan todos los tipos 
	 de texto y todas las funciones que pueden realizarse.

----------------------------------------------------------------------------------------------- -->

<html>

<head><title></title>
   <link rel="stylesheet" type="text/css" href="../../comun/estilo.css">
   <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
   <meta content="Microsoft FrontPage 4.0" name=GENERATOR>
</head>

<body>
<?php  
   include("func_tipo.php");
  
   if(tienePermisos("textotipomenuadmin"))
   {
?>

<p align="center"><span class="titulo titulo_rojo"><?php echo $titulo_menu_admin_tipo ?></span><br>
<img border="0" src="../../imagenes/linea_horiz.gif" ></p>

<table border="0" width="100%">
   <tr>
      <td align="center" width="150" valign="top" bgcolor="#FFFF99" class="conborde">
         <p align="center"><br><span class="titulo titulo_gris"><?php echo $operaciones ?></span><br><br>
			<input type="button" class="boton long_130 boton_nuevo" value="      <?php echo $nuevo_tipo ?> " onclick="document.location='operacion_tipo.php?arg_op=nuevo';"/><br><br>
	        <input type="button" class="boton boton_volver long_160" value="      <?php echo $salir_menu_textos ?> " onclick="document.location='../menu_admin_texto.php';"/><br><br>
	     </p>
		 <a href="../../ayuda/ayuda_admin_tipo.htm" target="_blank"><span class="subtitulo titulo_rojo"><img border="0" src="../../imagenes/ayuda.png" width="43" height="24" /><br><?php echo $ayuda ?></span></a><br>&nbsp;
	  </td>
      <td>
<?php  
            listar_tipos();    
?> 
      </td>
   </tr>
</table>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_texto ?></a> > <u><?php echo $titulo_menu_admin_tipo ?></u<</td>
   </tr>
</table>

<?php 
   }
   else
   {
      echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   }
?>

</body>

</html>