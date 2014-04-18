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

     Pagina que muestra los enlaces de búsqueda de coocurrencias por término o categoria gramatical

----------------------------------------------------------------------------------------------- -->


<html>

<head>
	<title>Calíope</title>
	<link rel="stylesheet" type="text/css" href="../../CSS/menu_colocaciones.css">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta content="Microsoft FrontPage 4.0" name=GENERATOR>
</head>

<body>

<?php  
   if(tienePermisos("corpusmenu"))
   {
?>
	<header>
		<h1>Corpus</h1>
	</header>

	<section>
		<p><?php echo $elija_opciones ?></p>
		<p><a href="menu_concord_antiguo.php?concord=Buscar Colocaciones"><?php echo $busqueda_termino ?></a></p>
		<p><a href="busqueda_gramatical.php?concord=Buscar Colocaciones"><?php echo $busqueda_categoria ?></a></p>
		<input type="button" value="<?php echo $salir_principal ?> " onclick="document.location='../../encabezado.php'" />
	</section>
<?php 
   }
   else  // El usuario NO tiene privilegios para acceder a la pagina
   {
      echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   }
?>

</body>

</html>
         