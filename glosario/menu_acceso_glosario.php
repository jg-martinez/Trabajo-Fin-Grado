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

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<!-- 
   menu_acceso_glosario.php
   ------------------------------------------------------------------------------------------------
   Menu de administracion del glosario del sitio web. 

-->

<html>

<head><title></title>
   <link rel="stylesheet" type="text/css" href="../comun/estilo.css">
   <meta content="Microsoft FrontPage 4.0" name=GENERATOR>
</head>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">


<?php  
   if(tienePermisos("glosariomenuacceso"))
   {
?>

<frameset framespacing="0" border="0" cols="300,*" frameborder="0">
  <frame name="menu" src="menu.php"  target="resultado"  marginwidth="0" marginheight="0">
  <frame name="resultado" src="resultado.php">
  <noframes>
  <body>

  <p><?php echo $mensaje_error ?></p>

  </body>
  </noframes>
</frameset>



<?php 
   }
   else  //-- Usuario tipo USUARIO, sin privilegios para administrar
   {
      echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>"; 
   }
?>


</html>
         