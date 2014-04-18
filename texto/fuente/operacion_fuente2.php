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

<!-- operacion_fuente2.php ------------------------------------------------------------------------

     Realiza la operacion indicada sobre la fuente de texto: ALTA, BAJA y MODIFICACION.

----------------------------------------------------------------------------------------------- -->

<html>

<head><title></title>
   <link rel="stylesheet" type="text/css" href="../../comun/estilo.css">
   <meta http-equiv=Content-Type content="text/html; charset=windows-1252">
   <meta content="Microsoft FrontPage 4.0" name=GENERATOR>
</head>

<body>

<?php  
   include("func_fuente.php");
   include ("../../historico/operaciones_historico.php");
   if(tienePermisos("textofuenteoperacion2"))
   {
?>
<p align="center"><span class="titulo titulo_rojo"><?php echo $titulo_menu_admin_fuente ?></span><br>
<img border="0" src="../../imagenes/linea_horiz.gif" ></p>
<?php 
	    if (isset($_POST['arg_op']))
		{
			$arg_op = $_POST['arg_op'];
			$_SESSION['arg_op'] = $arg_op;
		}
	    else
		{
			$arg_op = $_SESSION['arg_op'];
		}	  
	  

      //-- ALTA DE FUENTE -------------------------------------------------------------------------

	  if($arg_op == 'alta')  
	  {
              // recogida de los datos que se han pasado por el formulario
			if (isset($_POST['id_fuente']) && isset($_POST['edition']) && isset($_POST['h_title']) && isset($_POST['h_author']) && isset($_POST['pub_place']) &&
				isset($_POST['publisher']) && isset($_POST['dia']) && isset($_POST['mes']) && isset($_POST['anyo']))
			{
				$id_fuente = $_POST['id_fuente'];		
				$edition = $_POST['edition'];		
				$h_title = $_POST['h_title'];       
				$h_author = $_POST['h_author'];       
				$pub_place = $_POST['pub_place'];       
				$publisher = $_POST['publisher'];       
				$dia = $_POST['dia']; 
				$mes = $_POST['mes'];       
				$anyo = $_POST['anyo'];
				
				$_SESSION['id_fuente'] = $id_fuente;
				$_SESSION['edition'] = $edition;
				$_SESSION['h_title'] = $h_title;
				$_SESSION['h_author'] = $h_author;
				$_SESSION['pub_place'] = $pub_place;
				$_SESSION['publisher'] = $publisher;
				$_SESSION['dia'] = $dia;
				$_SESSION['mes'] = $mes;
				$_SESSION['anyo'] = $anyo;
			}
			else
			{
				$id_fuente = $_SESSION['id_fuente'];		
				$edition = $_SESSION['edition'];		
				$h_title = $_SESSION['h_title'];       
				$h_author = $_SESSION['h_author'];       
				$pub_place = $_SESSION['pub_place'];       
				$publisher = $_SESSION['publisher'];       
				$dia = $_SESSION['dia']; 
				$mes = $_SESSION['mes'];       
				$anyo = $_SESSION['anyo'];
			}       

			$pub_date = $anyo."-".$mes."-".$dia;

			alta_fuente($id_fuente, $edition, $h_title, $h_author, $pub_place, $publisher, $pub_date);
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_texto ?></a> > <a href="menu_admin_fuente.php"><?php echo $titulo_menu_admin_fuente ?></a> > <u><?php echo $crear_fuente_texto ?></u></td>
   </tr>
</table>

<?php 
	  }


      //-- MODIFICACION DE FUENTE -----------------------------------------------------------------

	  if($arg_op == 'modificar') 
	  {
              // recogida de los datos que se han pasado por el formulario
			if (isset($_POST['id_fuente']) && isset($_POST['nuevo_id_fuente']) && isset($_POST['edition']) && isset($_POST['h_title']) && isset($_POST['h_author']) && 
				isset($_POST['pub_place']) && isset($_POST['publisher']) && isset($_POST['dia']) && isset($_POST['mes']) && isset($_POST['anyo']))
			{
				$id_fuente = $_POST['id_fuente'];		
				$nuevo_id_fuente = $_POST['nuevo_id_fuente'];		
				$edition = $_POST['edition'];		
				$h_title = $_POST['h_title'];       
				$h_author = $_POST['h_author'];       
				$pub_place = $_POST['pub_place'];       
				$publisher = $_POST['publisher'];       
				$dia = $_POST['dia']; 
				$mes = $_POST['mes'];       
				$anyo = $_POST['anyo'];

				$_SESSION['id_fuente'] = $id_fuente;
				$_SESSION['nuevo_id_fuente'] = $nuevo_id_fuente;
				$_SESSION['edition'] = $edition;
				$_SESSION['h_title'] = $h_title;
				$_SESSION['h_author'] = $h_author;
				$_SESSION['pub_place'] = $pub_place;
				$_SESSION['publisher'] = $publisher;
				$_SESSION['dia'] = $dia;
				$_SESSION['mes'] = $mes;
				$_SESSION['anyo'] = $anyo;
			}
			else
			{
				$id_fuente = $_SESSION['id_fuente'];
				$nuevo_id_fuente = $_SESSION['nuevo_id_fuente'];			
				$edition = $_SESSION['edition'];		
				$h_title = $_SESSION['h_title'];       
				$h_author = $_SESSION['h_author'];       
				$pub_place = $_SESSION['pub_place'];       
				$publisher = $_SESSION['publisher'];       
				$dia = $_SESSION['dia']; 
				$mes = $_SESSION['mes'];       
				$anyo = $_SESSION['anyo'];
			}  

		    $pub_date = $anyo."-".$mes."-".$dia;
     
		    modificar_fuente($id_fuente, $nuevo_id_fuente, $edition, $h_title, $h_author, $pub_place, $publisher, $pub_date);
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_texto ?></a> > <a href="menu_admin_fuente.php"><?php echo $titulo_menu_admin_fuente ?></a> > <u><?php echo $modificar_fuente_texto ?></u></td>
   </tr>
</table>

<?php 
	  }


      //-- BAJA DE FUENTE -------------------------------------------------------------------------

	  if($arg_op == 'eliminar')  
	  {
              // recogida de los datos que se han pasado por el formulario
			if (isset($_POST['id_fuente']))
			{
				$id_fuente = $_POST['id_fuente'];
				
				$_SESSION['id_fuente'] = $id_fuente;
			}
			else
			{
				$id_fuente = $_SESSION['id_fuente'];
			}

		  eliminar_fuente($id_fuente);
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_texto ?></a> > <a href="menu_admin_fuente.php"><?php echo $titulo_menu_admin_fuente ?></a> > <u><?php echo $eliminar_fuente_texto ?></u></td>
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