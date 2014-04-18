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

   if(tienePermisos("corpusvisualizaroperacion"))
   {
      include("func_texto.php");
	  $arg_op = $_GET['arg_op'];

	  //---------- VISUALIZACION DEL TEXTO ----------

	  if($arg_op == 'vista')  
	  {
         // Cargar datos del formulario

	     $id_texto = $_GET['id_texto'];
		 $edition_stmt = $_GET['edition_stmt'];
		 $idioma = $_GET['idioma'];
?>
<!-- operacion_visualizar.php ---------------------------------------------------------------------

     Pagina de visualizacion de un texto. 

----------------------------------------------------------------------------------------------- -->

<html>

<head>
	<title>Calíope</title>
	<link rel="stylesheet" type="text/css" href="../../CSS/op_visualizar.css">
	<!--<link rel="stylesheet" type="text/css" href="../../comun/estilo.css">-->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta content="Microsoft FrontPage 4.0" name=GENERATOR>
</head>

<body>

	<header>
		<h1><?php echo $titulo_visualizar_texto ?></h1>
	</header>
	<nav>
		<input id="operacion" type="button" value="<?php echo $operaciones ?>" />
        <input type="button" value="<?php echo $download_text ?>" onclick="document.location='operacion_visualizar.php?arg_op=descargar&id_texto=<?php echo $id_texto?>&idioma=<?php echo $idioma;?>';"/></br>
	    <input type="button" value="<?php echo $volver_visualizar ?> " onclick="document.location='menu_visualizar.php?idioma=<?php echo $idioma?>';"/>
	</nav>
	<section>
	<?php  
	     visualizar_texto($id_texto, $arg_op);   
	?> 
   </section>

<br>

<!--<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_acceso_corpus.php">CORPUS</a> > <u><?php echo $titulo_visualizar_texto ?></u></td>
   </tr>
</table>-->


</body>

</html><?php 
      }

      else 
      //---------- DESCARGA DEL TEXTO ----------

      if($arg_op == 'descargar')  
	  {
	  	 visualizar_texto($_GET['id_texto'],$arg_op);
      }
   }
   else  // El usuario NO tiene privilegios para acceder a la pagina
   {
       echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   }
?>