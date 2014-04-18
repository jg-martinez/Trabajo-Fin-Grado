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

<!-- operacion_campo.php --------------------------------------------------------------------------

     Realiza la operacion indicada sobre el campo de texto: ALTA, BAJA y MODIFICACION.

----------------------------------------------------------------------------------------------- -->


<html>

<head><title></title>
   <link rel="stylesheet" type="text/css" href="../../comun/estilo.css">
   <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
   <meta content="Microsoft FrontPage 4.0" name=GENERATOR>

<script type="text/JavaScript">

function check_datos(data)
{    

   // Comprobar NOMBRE (no vacio)

   if (data.description_esp.value == "") // Se chequea en el ALTA y en la MODIFICACION
   {
	  if (data.description_ing.value == "")
	  {
		alert("<?php echo $aviso_campo_vacio ?>");
		return false;
	  }
   }

   // Si se han pasado todas las comprobaciones el formulario es valido

   return true;
}

</script>

</head>

<body>

<?php  
   include("func_campo.php");

   if(tienePermisos("textocampooperacion"))
   {
	  $arg_op = $_GET['arg_op'];


      //-- ALTA DE CAMPO --------------------------------------------------------------------------

	  if($arg_op == 'nuevo') 
	  {
?>

<p align="center"><span class="titulo titulo_rojo"><?php echo $crear_nuevo_campo ?></span><br>
<img border="0" src="../../imagenes/linea_horiz.gif" ></p>

<p align="center">
<table border="0">
    <!-- FORMULARIO para el alta de una nueva fuente de texto -->
<form action="operacion_campo2.php" method="post" name="formulario" onSubmit='return check_datos(formulario);'>
   <input type="hidden" name="arg_op" value="alta">
   <tr>
		<th colspan="4"><?php echo $rellena_info ?></th></tr>
	<tr></tr><tr></tr><tr></tr><tr></tr>
	<tr>
		<td colspan="2" align="center"><?php echo $version_esp ?></td>
		<td colspan="2" align="center"><?php echo $version_ing ?></td>
	</tr>
	<tr>
		<td><?php echo $nomb_campo ?>:</td>
		<td><input name="description_esp" size="50" title="<?php echo $nomb ?>"></td>&nbsp;&nbsp;&nbsp;&nbsp;
		<td width="150" align="right"><?php echo $nomb_campo ?>:</td>
		<td><input name="description_ing" size="50" title="<?php echo $nomb ?>"></td>
	</tr>
    <tr>
		<td align="center" colspan="4">
			<br><input type="submit" class="boton long_93 boton_aceptar" value="      <?php echo $boton_aceptar ?> " />&nbsp;&nbsp;
			<input type="button" class="boton long_93 boton_cancelar" value="      <?php echo $boton_volver ?> " onclick="document.location='menu_admin_campo.php';"/>&nbsp;&nbsp;
			<input type="button" class="boton" value=" <?php echo $limpiar_formulario ?> " onclick="document.formulario.reset();"/>&nbsp;&nbsp;
		</td>
    </tr>
</form>
</table>
</p>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_texto ?></a> > <a href="menu_admin_campo.php"><?php echo $titulo_menu_admin_campo ?></a> > <u><?php echo $crear_nuevo_campo ?></u></td>
   </tr>
</table>

<?php 
      }
	  else
      //-- BAJA DE CAMPO --------------------------------------------------------------------------

	  if($arg_op == 'eliminar')  
	  {
	     $id_campo = $_GET['id_campo'];
	     $description_esp = $_GET['description_esp'];
		 $description_ing = $_GET['description_ing'];
?>
<p align=center><span class="titulo titulo_rojo"><?php echo $eliminar_campo_texto ?></span><br>
<img border="0" src="../../imagenes/linea_horiz.gif" ></p>
<?php 
         // Conexion con la base de datos
     	 include ("../../comun/conexion.php");

         // Consulta a la base de datos

         $consulta= "SELECT id_campo FROM texto WHERE id_campo='$id_campo'";
         $resultado = mysql_query($consulta) or die($lectura_campos_incorrecta . mysql_error()); 

         mysql_close($enlace);

         if(mysql_num_rows($resultado) == 0)  //-- No hay textos de este campo
         {
?>

<p align="center"><?php echo $mensaje31 ?></p>

<p align="center">
    <!-- TABLA que muestra los datos de la fuente que se quiere eliminar -->
  <table border="0" width="330" style="border: 1 dashed #CC0000" bgcolor="#FFFF99" cellspacing="5">
    <tr>
      <td width="20%" align="center" colspan="4"><b><?php echo $signatura ?>:</b> &nbsp;&nbsp;<?php echo $id_campo;?></td>
    </tr>
	<tr>
      <td width="20%" valign="top"><b><?php echo $campo ?> (<?php echo $espanol ?>):</b></td>
      <td width="80%"><?php echo $description_esp;?></td>
	  <td width="20%" valign="top"><b><?php echo $campo ?> (<?php echo $ingles ?>):</b></td>
      <td width="80%"><?php echo $description_ing;?></td>
    </tr>
  </table>
</p>

<form action="operacion_campo2.php" method=post>
	<input type="hidden" name="arg_op" value="eliminar">
	<input type="hidden" name="id_campo" value="<?php echo $id_campo?>">
	<table width="100%" align="center" border="0">
		<tr>
			<td align="center">
				<input type="submit" class="boton long_93 boton_aceptar" value="      <?php echo $boton_aceptar ?> " />&nbsp;&nbsp;
				<input type="button" class="boton long_93 boton_cancelar" value="      <?php echo $boton_cancelar ?> " onclick="document.location='menu_admin_campo.php';"/>
			</td>
		</tr>
	</table>
</form>

<?php 
         }
         else  //-- Existen textos de este campo => no se puede eliminar el campo de texto
         {
?>

<p align="center"><?php echo $mensaje32 ?></p>

<p align="center">
  <table border="0" width="330" style="border: 1 dashed #CC0000" bgcolor="#FFFF99" cellspacing="5">
    <tr>
      <td width="20%"><b><?php echo $signatura ?>:</b></td>
      <td width="80%"><?php echo $id_campo;?></td>
    </tr>
	<tr>
<?php
	include ("../../comun/conexion.php");

	 // Consulta a la base de datos

	 $consulta= "SELECT description_esp, description_ing FROM campo WHERE id_campo='$id_campo'";
	 $resultado = mysql_query($consulta) or die($lectura_campos_incorrecta . mysql_error()); 
	 $obj = mysql_fetch_object($resultado);

	 mysql_close($enlace);
	 
	 if ($obj->description_esp == '')
	 {
?>
		  <td width="20%" valign="top"><b><?php echo $campo ?>:</b></td>
		  <td width="80%"><?php echo $obj->description_ing?></td>
<?php
	}
	else
	{
?>
		  <td width="20%" valign="top"><b><?php echo $campo ?>:</b></td>
		  <td width="80%"><?php echo $obj->description_esp?></td>
<?php
	}
?>
    </tr>
  </table>
</p>

<?php 
            echo "<p align=\"center\">";
            echo "<table border=\"0\" width=\"500\" style=\"border: 1 dashed #CC0000\" bgcolor=\"#FFFF55\" cellspacing=\"5\">";
	        echo "<tr><td  align=\"center\">";
	        echo "<b>".$atencion.":</b>".$mensaje33."<b>" . $id_campo . "</b>.<br>";
	        echo $mensaje21;
	        echo "</td></tr>";
			
			// Conexion con la base de datos
			 include ("../../comun/conexion.php");

			 // Consulta a la base de datos

			 $consulta= "SELECT h_title FROM texto WHERE id_campo='$id_campo'";
			 $resultado = mysql_query($consulta) or die($lectura_campos_incorrecta . mysql_error()); 

			 mysql_close($enlace);

	        while($obj = mysql_fetch_object($resultado))
            {
	           echo "<tr><td align=\"center\" >$obj->h_title</td></tr>";
            }

	        echo "<tr><td align=\"center\">";
	        echo "<b>".$mensaje34."</b><br>".$mensaje35;
	        echo "</td></tr>";
	        echo "</table></p>";

	        echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='menu_admin_campo.php';\" /></p>";
         }
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_texto ?></a> > <a href="menu_admin_campo.php"><?php echo $titulo_menu_admin_campo ?></a> > <u><?php echo $eliminar_campo_texto ?></u></td>
   </tr>
</table>

<?php 
	  }
	  else
      //-- MODIFICACION DE CAMPO ------------------------------------------------------------------

      if($arg_op == 'modificar') 
	  {
	     $id_campo = $_GET['id_campo'];


         // Conexion con la base de datos
      	 include ("../../comun/conexion.php");

         // Consulta a la base de datos
         $consulta = "SELECT id_campo,description_esp,description_ing FROM campo WHERE id_campo = '$id_campo'";
         $res = mysql_query($consulta) or die($lectura_campos_incorrecta);

         // Modificacion de los datos
         $fila = mysql_fetch_assoc($res);
         
         $description_esp = $fila["description_esp"];
		 $description_ing = $fila["description_ing"];		 
         mysql_close($enlace);

?>

<p align="center"><span class="titulo titulo_rojo"><?php echo $cambiar_campo_texto ?></span><br>
<img border="0" src="../../imagenes/linea_horiz.gif" ></p>

<!-- FORMULARIO para la modificacion de una fuente de texto -->
<form action="operacion_campo2.php" method=post name="formulario" onSubmit='return check_datos(formulario);'>
   <input type="hidden" name="arg_op" value="modificar">
   <input type="hidden" name="id_campo" value="<?php echo $id_campo?>">
	<p align="center">
	<table border="0">
	   <tr>
	      <td><?php echo $signatura ?>:</td>
	      <td><input type="text" name=nuevo_id_campo readonly="readonly" value="<?php echo $id_campo?>" size="50" class="obligatorio" title="Clave"></td>
	   </tr>
	   <tr>
	      <td><?php echo $campo ?> (<?php echo $espanol ?>):</td>
	      <td><input type="text" name=description_esp value="<?php echo $description_esp?>" size="50" title="<?php echo $descripcion ?>"></td>
	   </tr>
	   <tr>
	      <td><?php echo $campo ?> (<?php echo $ingles ?>):</td>
	      <td><input type="text" name=description_ing value="<?php echo $description_ing?>" size="50" title="<?php echo $descripcion ?>"></td>
	   </tr>
	   <tr>
	      <td align="center" colspan="2">
	      	<br><input type="submit" class="boton long_93 boton_aceptar" value="      <?php echo $boton_aceptar ?> " />&nbsp;&nbsp;
	      	<input type="button" class="boton long_93 boton_cancelar" value="      <?php echo $boton_cancelar ?> " onclick="document.location='menu_admin_campo.php';"/>&nbsp;&nbsp;
			<input type="button" class="boton" value=" <?php echo $limpiar_formulario ?> " onclick="document.formulario.reset();"/>&nbsp;&nbsp;
	      </td>
	   </tr>
	</table>
	</p>
</form>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_texto ?></a> > <a href="menu_admin_campo.php"><?php echo $titulo_menu_admin_campo ?></a> > <u><?php echo $cambiar_campo_texto ?></u></td>
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