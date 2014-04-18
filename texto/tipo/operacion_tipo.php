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

<!-- operacion_tipo.php ---------------------------------------------------------------------------

     Realiza la operacion indicada sobre el tipo de texto: ALTA, BAJA y MODIFICACION.

----------------------------------------------------------------------------------------------- -->

<html>

<head><title></title>
   <link rel="stylesheet" type="text/css" href="../../comun/estilo.css">
   <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
   <meta content="Microsoft FrontPage 4.0" name=GENERATOR>

<script type="text/JavaScript">
// comprobaciones de los formualarios
function check_datos(data)
{    

   // Comprobar NOMBRE (no vacio)

   if(data.scheme_esp.value == "" ) // Se chequea en el ALTA y en la MODIFICACION
   {
	  if (data.scheme_ing.value == "")
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
   include("func_tipo.php");

   if(tienePermisos("textotipooperacion"))
   {
	  $arg_op = $_GET['arg_op'];


      //-- ALTA DE TIPO ---------------------------------------------------------------------------

	  if($arg_op == 'nuevo') 
	  {
?>

<p align="center"><span class="titulo titulo_rojo"><?php echo $crear_tipo_texto ?></span><br>
<img border="0" src="../../imagenes/linea_horiz.gif" ></p>

<p align="center">
<table border="0">
<!-- FORMULARIO para realizar el alta de un nuevo tipo -->
<form action="operacion_tipo2.php" method=post name="formulario" onSubmit='return check_datos(formulario);'>
	<input type="hidden" name="arg_op" value="alta">
	<tr>
		<th colspan="4"><?php echo $rellena_info ?></th></tr>
	<tr></tr><tr></tr><tr></tr><tr></tr>
	<tr>
		<td colspan="2" align="center"><?php echo $version_esp ?></td>
		<td colspan="2" align="center"><?php echo $version_ing ?></td>
	</tr>
	<tr>
		<td><?php echo $nomb_tipo ?>:</td>
		<td><input name="scheme_esp" size="50" title="<?php echo $nomb ?>"></td>&nbsp;&nbsp;&nbsp;&nbsp;
		<td width="150" align="right"><?php echo $nomb_tipo ?>:</td>
		<td><input name="scheme_ing" size="50" title="<?php echo $nomb ?>"></td>
	</tr>
	<tr>
		<td><?php echo $descripcion ?>:</td>
		<td><input name="h_keyword_esp" size="50" title="<?php echo $desc_tipo ?>"></td>&nbsp;&nbsp;&nbsp;&nbsp;
		<td width="150" align="right"><?php echo $descripcion ?>:</td>
		<td><input name="h_keyword_ing" size="50" title="<?php echo $desc_tipo ?>"></td>
	</tr>
	<tr>
		<td align="center" colspan="4">
			<br><input type="submit" class="boton long_93 boton_aceptar" value="      <?php echo $boton_aceptar ?> " />&nbsp;&nbsp;
			<input type="button" class="boton long_93 boton_cancelar" value="      <?php echo $boton_cancelar ?> " onclick="document.location='menu_admin_tipo.php';"/>&nbsp;&nbsp;
			<input type="button" class="boton" value=" <?php echo $limpiar_formulario ?> " onclick="document.formulario.reset();"/>&nbsp;&nbsp;
	    </td>
	<tr>
</form>
</table>
</p>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_texto ?></a> > <a href="menu_admin_tipo.php"><?php echo $titulo_menu_admin_tipo ?></a> > <u><?php echo $crear_tipo_texto ?></u></td>
   </tr>
</table>

<?php 
      }


      //-- BAJA DE TIPO ---------------------------------------------------------------------------

	  if($arg_op == 'eliminar')  
	  {
              // se recogen los parametros pasados via URL
	     $id_tipo = $_GET['id_tipo'];
	     $scheme_esp = $_GET['scheme_esp'];
	     $h_keyword_esp = $_GET['h_keyword_esp'];
             $scheme_ing = $_GET['scheme_ing'];
	     $h_keyword_ing = $_GET['h_keyword_ing'];
?>

<p align=center><span class="titulo titulo_rojo"><?php echo $eliminar_tipo_texto ?></span><br>
<img border="0" src="../../imagenes/linea_horiz.gif" ></p>

<?php 
         // Conexion con la base de datos 
      	 include ("../../comun/conexion.php");

         /* Consulta a la base de datos */

         $consulta= "SELECT id_tipo FROM texto WHERE id_tipo='$id_tipo'";
         $resultado = mysql_query($consulta) or die($lectura_texto_incorrecta . mysql_error()); 

         if(mysql_num_rows($resultado) == 0) // No hay textos de este tipo
		 {
		 
?>

<p align="center"><?php echo $mensaje18 ?></p>

<p align="center">
  <!-- TABLA que muestra los datos del tipo que se quiere borrar -->
  <table border="0" width="330" style="border: 1 dashed #CC0000" bgcolor="#FFFF99" cellspacing="5">
    <tr>
      <td width="20%" align="center" colspan="4"><b><?php echo $signatura ?>:</b> &nbsp;&nbsp;<?php echo $id_tipo;?></td>
    </tr>
	<tr>
      <td width="20%" valign="top"><b><?php echo $tipo_texto ?> (<?php echo $espanol ?>):</b></td>
      <td width="80%"><?php echo $scheme_esp;?></td>
	  <td><b><?php echo $descripcion ?> (<?php echo $espanol ?>):</b></td>
	  <td width="80%"><?php echo $h_keyword_esp ?></td>
    </tr>
	<tr>
      <td width="20%" valign="top"><b><?php echo $tipo_texto ?> (<?php echo $ingles ?>):</b></td>
      <td width="80%"><?php echo $scheme_ing;?></td>
	  <td><b><?php echo $descripcion ?> (<?php echo $ingles ?>):</b></td>
	  <td width="80%"><?php echo $h_keyword_ing ?></td>
    </tr>
  </table>
</p>

<!-- FORMULARIO de confirmacion de borrado -->
<form action="operacion_tipo2.php" method=post>
  <input type="hidden" name="arg_op" value="eliminar">
  <input type="hidden" name="id_tipo" value="<?php echo $id_tipo?>">
	<table width="100%" border="0" align="center">
		<tr>
			<td align="center">
				<input type="submit" class="boton long_93 boton_aceptar" value="      <?php echo $boton_aceptar ?> " />&nbsp;&nbsp;
				<input type="button" class="boton long_93 boton_cancelar" value="      <?php echo $boton_cancelar ?> " onclick="document.location='menu_admin_tipo.php';"/>  
			</td>
		</tr>
	</table>
</form>

<?php 
         }
	     else // Existen textos de este tipo => no se puede eliminar el tipo de texto
		 {
		 
		 $consulta= "SELECT scheme_esp, scheme_ing, h_keyword_esp, h_keyword_ing FROM tipo WHERE id_tipo='$id_tipo'";
         $res = mysql_query($consulta) or die($lectura_texto_incorrecta . mysql_error());
		 $obj = mysql_fetch_object($res);

         mysql_close($enlace);
?>

<p align="center"><?php echo $mensaje19 ?></p>

<p align="center">
  <!-- TABLA que muestra los datos del tipo que se quiere borrar -->
  <table border="0" width="330" style="border: 1 dashed #CC0000" bgcolor="#FFFF99" cellspacing="5">
    <tr>
      <td width="20%"><b><?php echo $signatura ?>:</b></td>
      <td width="80%"><?php echo $id_tipo;?></td>
    </tr>
	<tr>
<?php
	if ($obj->scheme_esp == '')
	{
?>
		<td width="20%" valign="top"><b><?php echo $tipo_texto ?>:</b></td>
		<td width="80%"><?php echo $obj->scheme_esp ?></td>
<?php
	}
	else
	{
?>
		<td width="20%" valign="top"><b><?php echo $tipo_texto ?>:</b></td>
		<td width="80%"><?php echo $obj->scheme_ing;?></td>

<?php
	}
?>
    </tr>
	<tr>
<?php
	if ($obj->h_keyword_esp == '')
	{
?>
		<td width="20%" valign="top"><b><?php echo $descripcion ?>:</b></td>
		<td width="80%"><?php echo $obj->h_keyword_esp?></td>
<?php
	}
	else
	{
?>
		<td width="20%" valign="top"><b><?php echo $descripcion ?>:</b></td>
		<td width="80%"><?php echo $obj->h_keyword_ing?></td>
<?php
	}
?>
    </tr>
  </table>
</p>

<?php 
			echo "<p align=\"center\">";
            echo "<table border=\"0\" width=\"500\" style=\"border: 1 dashed #CC0000\" bgcolor=\"#FFFF55\" cellspacing=\"5\">";
	        echo "<tr><td align=\"center\">";
			if ($obj->scheme_esp == '')
				echo "<b>".$atencion."</b>".$mensaje121."<b>" . $obj->scheme_esp . "</b>.<br>";
			else
				echo "<b>".$atencion."</b>".$mensaje121."<b>" . $obj->scheme_ing . "</b>.<br>";
			echo $mensaje21;
			echo "</td></tr>";
			
			// Conexion con la base de datos 
			include ("../../comun/conexion.php");

			/* Consulta a la base de datos */

			$consulta= "SELECT h_title FROM texto WHERE id_tipo='$id_tipo'";
			$resultado = mysql_query($consulta) or die($lectura_texto_incorrecta . mysql_error()); 

			while($obj = mysql_fetch_object($resultado))
            {
		       echo "<tr><td align=\"center\"> $obj->h_title</td></tr>";
            }

		    echo "<tr><td align=\"center\">";
			echo "<b>".$mensaje22."</b><br> ".$mensaje23."<u>".$tipo_texto."</u>".$mensaje24;
			echo "</td></tr>";
			echo "</table></p>";
			
			echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='menu_admin_tipo.php';\" /></p>";
		 }
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_texto?></a> > <a href="menu_admin_tipo.php"><?php echo $titulo_menu_admin_tipo ?></a> > <u><?php echo $eliminar_tipo_texto ?></u></td>
   </tr>
</table>

<?php 
		mysql_close($enlace);
	  }


      //-- MODIFICACION DE TIPO -------------------------------------------------------------------

      if($arg_op == 'modificar') 
	  {
	     $id_tipo = $_GET['id_tipo'];

         // Conexion con la base de datos
      	 include ("../../comun/conexion.php");

         /* Obtenemos los datos de la BD */

         $consulta = "SELECT scheme_esp, h_keyword_esp, scheme_ing, h_keyword_ing, id_tipo FROM tipo WHERE tipo.id_tipo = '$id_tipo'";
         $res = mysql_query($consulta) or die($lectura_tipos_incorrecta);

         mysql_close($enlace);

         /* AQUI SE HACE LA MODIFICACION DE LOS DATOS */

         $fila = mysql_fetch_assoc($res);
         
         $scheme_esp = $fila["scheme_esp"]; 
         $h_keyword_esp = $fila["h_keyword_esp"]; 
		 $scheme_ing = $fila["scheme_ing"]; 
         $h_keyword_ing = $fila["h_keyword_ing"];		 
?>

<p align="center">
	<span class="titulo titulo_rojo"><?php echo $cambiar_tipo_texto ?></span><br>
	<img border="0" src="../../imagenes/linea_horiz.gif" >
</p>

<p align="center">
<table border="0">
 <!-- FORMULARIO para la modificacion del tipo de texto -->
<form action="operacion_tipo2.php" method="post" name="formulario" onSubmit='return check_datos(formulario);'>
   <input type="hidden" name="arg_op" value="modificar">
   <input type="hidden" name="id_tipo" value="<?php echo $id_tipo?>">
   <tr>
      <td><?php echo $signatura ?>:</td><td><input type="text" name=nuevo_id_tipo readonly="readonly" value="<?php echo $id_tipo?>" size="50" class="obligatorio" title="<?php echo $clave ?>"></td>
   </tr>
   <tr>
      <td><?php echo $tipo_texto ?> (<?php echo $espanol ?>):</td><td><input type="text" name=scheme_esp value="<?php echo $scheme_esp;?>" size="50" title="<?php echo $nomb?>"></td>
   </tr>
   <tr>
      <td><?php echo $desc_esp ?>:</td><td><input type="text" name=h_keyword_esp value="<?php echo $h_keyword_esp?>" size="50" title="Especificador"></td>
   </tr>
   <tr>
      <td><?php echo $tipo_texto ?> (<?php echo $ingles ?>):</td><td><input type="text" name=scheme_ing value="<?php echo $scheme_ing;?>" size="50" title="<?php echo $nomb?>"></td>
   </tr>
   <tr>
      <td><?php echo $desc_ing ?>:</td><td><input type="text" name=h_keyword_ing value="<?php echo $h_keyword_ing?>" size="50" title="Especificador"></td>
   </tr>
   <tr>
      <td align="center" colspan="2"><br>
        <input type="submit" class="boton long_93 boton_aceptar" value="      <?php echo $boton_aceptar ?> " />&nbsp;&nbsp;
      	<input type="button" class="boton long_93 boton_cancelar" value="      <?php echo $boton_cancelar ?> " onclick="document.location='menu_admin_tipo.php';"/>&nbsp;&nbsp;
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
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_texto ?></a> > <a href="menu_admin_tipo.php"><?php echo $titulo_menu_admin_tipo ?></a> > <u><?php echo $cambiar_tipo_texto ?></u></td>
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