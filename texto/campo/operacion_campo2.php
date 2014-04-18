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

<!-- operacion_campo2.php -------------------------------------------------------------------------

     Realiza la operacion indicada sobre el campo de texto: ALTA, BAJA y MODIFICACION.

----------------------------------------------------------------------------------------------- -->


<html>

<head><title></title>
   <link rel="stylesheet" type="text/css" href="../../comun/estilo.css">
   <meta http-equiv=Content-Type content="text/html; charset=windows-1252">
   <meta content="Microsoft FrontPage 4.0" name=GENERATOR>
</head>

<body>


<?php  
   include("func_campo.php");
   include ("../../historico/operaciones_historico.php");
   if(tienePermisos("textocampooperacion2"))
   {
?>

<p align="center"><span class="titulo titulo_rojo"><?php echo $titulo_menu_admin_campo ?></span><br>
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
	  

      //-- ALTA DE CAMPO --------------------------------------------------------------------------

      if($arg_op == 'alta')  
	  {
          // recogida de los datos introducidos en el formulario
	        if (isset($_POST['description_esp']) && isset($_POST['description_ing']))
			{	
				$description_esp = $_POST['description_esp'];
				$description_ing = $_POST['description_ing'];
				
				$_SESSION['description_esp'] = $description_esp;
				$_SESSION['description_ing'] = $description_ing;
			}
			else
			{
				$description_esp = $_SESSION['description_esp'];
				$description_ing = $_SESSION['description_ing'];
			}
		    alta_campo($description_esp, $description_ing);
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_texto ?></a> > <a href="menu_admin_campo.php"><?php echo $titulo_menu_admin_campo ?></a> > <u><?php echo $crear_nuevo_campo ?></u></td>
   </tr>
</table>

<?php 
	  }


      //-- MODIFICACION DE CAMPO ------------------------------------------------------------------

	  if($arg_op == 'modificar') 
	  {
              // recogida de los datos introducidos en el formulario
			if (isset($_POST['id_campo']) && isset($_POST['description_esp']) && isset($_POST['description_ing']))
			{
				$id_campo = $_POST['id_campo'];
				$description_esp = $_POST['description_esp'];
				$description_ing = $_POST['description_ing'];
				
				$_SESSION['id_campo'] = $id_campo;
				$_SESSION['description_esp'] = $description_esp;
				$_SESSION['description_ing'] = $description_ing;
			}
			else
			{
				$id_campo = $_SESSION['id_campo'];
				$description_esp = $_SESSION['description_esp'];
				$description_ing = $_SESSION['description_ing'];
			}

			modificar_campo($id_campo, $description_esp, $description_ing);
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_texto ?></a> > <a href="menu_admin_campo.php"><?php echo $titulo_menu_admin_texto ?></a> > <u><?php echo $cambiar_campo_texto ?></u></td>
   </tr>
</table>

<?php 
	  }


      //-- BAJA DE CAMPO --------------------------------------------------------------------------

	  if($arg_op == 'eliminar')  
	  {
              // recogida de los datos introducidos en el formulario
			if (isset($_POST['id_campo']))
			{
				$id_campo = $_POST['id_campo'];
				
				$_SESSION['id_campo'] = $id_campo;
			}
			else
			{
				$id_campo = $_SESSION['id_campo'];
			}

			eliminar_campo($id_campo);
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_texto ?></a> > <a href="menu_admin_campo.php"><?php echo $titulo_menu_admin_texto ?></a> > <u><?php echo $eliminar_campo_texto ?></u></td>
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