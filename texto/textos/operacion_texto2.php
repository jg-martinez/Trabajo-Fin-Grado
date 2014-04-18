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

<!-- operacion_texto2.php ---------------------------------------------------------------------------

     Realiza la operacion indicada el texto: ALTA, BAJA y MODIFICACION.

----------------------------------------------------------------------------------------------- -->

<html>

<head><title></title>
   <link rel="stylesheet" type="text/css" href="../../comun/estilo.css">
   <meta http-equiv=Content-Type content="text/html; charset=windows-1252">
   <meta content="Microsoft FrontPage 4.0" name=GENERATOR>
</head>

<body>

<?php  
   include("func_texto.php");
   include ("../../historico/operaciones_historico.php");
   if(tienePermisos("textotextosoperacion2"))
   {
	  $arg_op = $_POST['arg_op'];	  
?>

<p align="center"><span class="titulo titulo_rojo"><?php echo $titulo_menu_admin_texto ?></span><br>
<img border="0" src="../../imagenes/linea_horiz.gif" ></p>

<?php 
	  //-- ALTA DE TEXTO --------------------------------------------------------------------------

	  if($arg_op == 'alta')  
	  {
		 // Carga de los datos del formulario en variables locales 
         $fichero = $_FILES['fichero'];

         $f_bytes = $fichero['size'];
         $f_temp = $fichero['tmp_name'];

         $h_title = $_POST['h_title'];
         $edition_stmt = $_POST['edition_stmt'];
         $lang_usage = $_POST['lang_usage'];
         $id_tipo = $_POST['id_tipo'];
         $id_campo = $_POST['id_campo'];
         $texto_relacionado = $_POST['texto_relacionado'];
         
         $fuente_relacionada = $_POST['fuente_relacionada'];
     	 $fuente_id_fuente = $_POST['fuente_id_fuente'];
     	 if (isset($_POST['fuente_edition'])) $fuente_edition = $_POST['fuente_edition'];
		 else $fuente_edition = '';
         if (isset($_POST['fuente_dia'])) $fuente_dia = $_POST['fuente_dia'];
		 else $fuente_dia = '';
     	 if (isset($_POST['fuente_mes'])) $fuente_mes = $_POST['fuente_mes'];
		 else $fuente_mes = '';
         $fuente_h_title = $_POST['fuente_h_title'];
     	 $fuente_h_author = $_POST['fuente_h_author'];
     	 $fuente_pub_place = $_POST['fuente_pub_place'];
     	 $fuente_publisher = $_POST['fuente_publisher'];
     	 $fuente_anyo = $_POST['fuente_anyo'];
     	 
     	 $parametros = array();
     	 $parametros [] = $h_title;
     	 $parametros [] = $edition_stmt;
     	 $parametros [] = $f_bytes;
     	 $parametros [] = $lang_usage;
     	 $parametros [] = $id_tipo;
     	 $parametros [] = $id_campo;
     	 $parametros [] = $f_temp;
     	 $parametros [] = $texto_relacionado;
     	 $parametros [] = $fuente_relacionada;
     	 $parametros [] = $fuente_edition;
     	 $parametros [] = $fuente_id_fuente;
     	 $parametros [] = $fuente_h_title;
     	 $parametros [] = $fuente_h_author;
     	 $parametros [] = $fuente_pub_place;
     	 $parametros [] = $fuente_publisher;
     	 $parametros [] = $fuente_anyo."-".$fuente_mes."-".$fuente_dia;
     	 
         if($f_bytes > 0)
         {
             // llamada a la funcion que da de alta el texto
            alta_texto($parametros);
	     }
		 else
         {
	        echo "<b>ERROR: ".$mensaje56."</b><br><br>";
         }
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $administrar_textos ?></a> > <a href="menu_admin_textos.php"><?php echo $textos ?></a> > <u><?php echo $creacion_nuevo_texto ?></u></td>
   </tr>
</table>

<?php 
	  }


      //-- MODIFICACION DE TEXTO ------------------------------------------------------------------

	  if($arg_op == 'modificar') 
	  {
              // Carga de los datos del formulario en variables locales 
	     $id_texto = $_POST['id_texto'];
         $h_title = $_POST['h_title'];
		 $lang_usage = $_POST['lang_usage'];
         $id_tipo = $_POST['id_tipo'];
         $id_campo = $_POST['id_campo'];
         $id_fuente = $_POST['fuente_relacionada'];
         $texto_relacionado = $_POST['texto_relacionado'];
            
            // llamada a la funcion que modifica los campos del texto
		 modificar_texto($id_texto, $h_title, $lang_usage, $id_tipo, $id_campo, $id_fuente, $texto_relacionado);
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $administrar_textos ?></a> > <a href="menu_admin_textos.php"><?php echo $textos ?></a> > <u><?php echo $creacion_nuevo_texto ?>/u></td>
   </tr>
</table>

<?php 
	  }


      //-- BAJA DE TEXTO --------------------------------------------------------------------------

	  if($arg_op == 'eliminar')  
	  {
              // Carga de los datos del formulario en variables locales 
		  $id_texto = $_POST['id_texto'];
		  $h_title = $_POST['h_title'];
		  $edition_stmt = $_POST['edition_stmt'];

                  // llamada a la funcion que elimina un texto
		  eliminar_texto($id_texto, $h_title, $edition_stmt);
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.htm"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $administrar_textos ?></a> > <a href="menu_admin_textos.php"><?php echo $textos ?></a> > <u><?php echo $eliminacion_texto ?></u></td>
   </tr>
</table>

<?php 
	  }
   }
   else
   {
	   echo "<p class=\"Alerta\"><img border=\"0\" src=\"../../imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   }
?>

</body>

</html>