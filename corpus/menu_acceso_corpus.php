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

<!-- menu_acceso_corpus.php ---------------------------------------------------------------------

     Pagina de visualizacion de un texto. 

----------------------------------------------------------------------------------------------- -->


<html>

<head>
	<title>Calíope</title>
	<link rel="stylesheet" type="text/css" href="../CSS/menu_acceso_corpus.css">
	<link rel="stylesheet" type="text/css" href="../comun/estilo.css">
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
	
	<p align="center">
<table align="center" border="0">
<?php 
if(tienePermisos("corpuslistaoperacion")) {
?>
   <tr >
      <td><a href="lista/menu_lista.php"><?php echo $lista_palabras ?></a></td>
   </tr>
<?php 
} else {
?>
   <tr >
   </tr>
<?php 
}
?>
   <tr >
      <td ><a href="concord/menu_concord.php"><?php echo $concordancias ?></a></td>
   </tr>
   <tr>
      <td><a href="visualizar/menu_visualizar.php?idioma=todos"><?php echo $ver_textos ?></a></td>
   </tr>
   <tr>
      <td align="center" colspan="2">
		<br><input type="button" value="<?php echo $salir_principal ?>" onclick="document.location='../encabezado.php'" />&nbsp;&nbsp;
      </td>
   </tr>
</table>
</p>

<br>

<!--<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <u>CORPUS</u></td>
   </tr>
</table>-->

<?php 
   }
   else  // El usuario NO tiene privilegios para acceder a la pagina
   {
      echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   }
?>

</body>

</html>
         