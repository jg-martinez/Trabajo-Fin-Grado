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

<!-- operacion_tipo2.php --------------------------------------------------------------------------

     Realiza la operacion indicada sobre el tipo de texto: ALTA, BAJA y MODIFICACION.

----------------------------------------------------------------------------------------------- -->


<html>

<head><title></title>
   <link rel="stylesheet" type="text/css" href="../../comun/estilo.css">
   <meta http-equiv=Content-Type content="text/html; charset=windows-1252">
   <meta content="Microsoft FrontPage 4.0" name=GENERATOR>
</head>

<body>


<?php  
   include("func_tipo.php");
   include ("../../historico/operaciones_historico.php");
   if(tienePermisos("textotipooperacion2"))
   {
	    if (isset($_POST['arg_op']))
		{
			$arg_op = $_POST['arg_op'];
			$_SESSION['arg_op'] = $arg_op;
		}
	    else
		{
			$arg_op = $_SESSION['arg_op'];
		}	  
?>

<p align="center"><span class="titulo titulo_rojo"><?php echo $titulo_menu_admin_tipo ?></span><br>
<img border="0" src="../../imagenes/linea_horiz.gif" ></p>

<?php 	  
      //-- ALTA DE TIPO ---------------------------------------------------------------------------

	    if($arg_op == 'alta')  
	    {
                // recogida de los parametros introducidos en el formulario correspondiente
			if (isset($_POST['scheme_esp']) && isset($_POST['h_keyword_esp']) && isset($_POST['scheme_ing']) && isset($_POST['h_keyword_ing']))
			{		
				$scheme_esp = $_POST['scheme_esp'];       
				$h_keyword_esp = $_POST['h_keyword_esp'];
				$scheme_ing = $_POST['scheme_ing'];       
				$h_keyword_ing = $_POST['h_keyword_ing'];
				
				$_SESSION['scheme_esp'] = $scheme_esp;
				$_SESSION['h_keyword_esp'] = $h_keyword_esp;
				$_SESSION['scheme_ing'] = $scheme_ing;
				$_SESSION['h_keyword_ing'] = $h_keyword_ing;
			}
			else
			{
				$scheme_esp = $_SESSION['scheme_esp'];
				$h_keyword_esp = $_SESSION['h_keyword_esp'];
				$scheme_ing = $_SESSION['scheme_ing'];
				$h_keyword_ing = $_SESSION['h_keyword_ing'];
			}

                    // llamada a la funcion correspondiente que realiza el alta 
		   alta_tipo($scheme_esp, $h_keyword_esp, $scheme_ing, $h_keyword_ing);
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_texto ?></a> > <a href="menu_admin_tipo.php"><?php echo $titulo_menu_admin_tipo ?></a> > <u><?php echo $crear_tipo_texto ?></u></td>
   </tr>
</table>

<?php 
	  }


      //-- MODIFICACION DE TIPO -------------------------------------------------------------------

	    if($arg_op == 'modificar') 
	    {
                // recogida de los parametros introducidos en el formulario correspondiente
	        if (isset($_POST['id_tipo']) && isset($_POST['scheme_esp']) && isset($_POST['h_keyword_esp']) && isset($_POST['scheme_ing']) && isset($_POST['h_keyword_ing']))
			{
				$id_tipo = $_POST['id_tipo'];
				$scheme_esp = $_POST['scheme_esp'];
				$h_keyword_esp = $_POST['h_keyword_esp'];
				$scheme_ing = $_POST['scheme_ing'];
				$h_keyword_ing = $_POST['h_keyword_ing'];
				
				$_SESSION['id_tipo'] = $id_tipo;
				$_SESSION['scheme_esp'] = $scheme_esp;
				$_SESSION['h_keyword_esp'] = $h_keyword_esp;
				$_SESSION['scheme_ing'] = $scheme_ing;
				$_SESSION['h_keyword_ing'] = $h_keyword_ing;
			}
			else
			{
				$id_tipo = $_SESSION['id_tipo'];
				$scheme_esp = $_SESSION['scheme_esp'];
				$h_keyword_esp = $_SESSION['h_keyword_esp'];
				$scheme_ing = $_SESSION['scheme_ing'];
				$h_keyword_ing = $_SESSION['h_keyword_ing'];
			}
                      // llamada a la funcion correspondiente que realiza la modificacion 
		    modificar_tipo($id_tipo, $scheme_esp, $h_keyword_esp, $scheme_ing, $h_keyword_ing);
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_texto ?></a> > <a href="menu_admin_tipo.php"><?php echo $titulo_menu_admin_tipo ?></a> > <u><?php echo $cambiar_tipo_texto ?></u></td>
   </tr>
</table>

<?php 
	  }


      //-- BAJA DE TIPO ---------------------------------------------------------------------------

	    if($arg_op == 'eliminar')  
	    {
                // recogida de los parametros introducidos en el formulario correspondiente
		    if (isset($_POST['id_tipo']))
			{
				$id_tipo = $_POST['id_tipo'];
				$_SESSION['id_tipo'] = $id_tipo;
			}
			else
			{
				$id_tipo = $_SESSION['id_tipo'];
			}
                        // llamada a la funcion correspondiente que realiza el borrado 
		    eliminar_tipo($id_tipo);
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_texto ?></a> > <a href="menu_admin_tipo.php"><?php echo $titulo_menu_admin_tipo ?></a> > <u><?php echo $eliminar_tipo_texto ?></u></td>
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