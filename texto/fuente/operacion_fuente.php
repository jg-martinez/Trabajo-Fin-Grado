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

<!-- operacion_fuente.php ---------------------------------------------------------------------------

     Realiza la operacion indicada sobre la fuente de texto: ALTA, BAJA y MODIFICACION.

----------------------------------------------------------------------------------------------- -->

<html>

<head><title></title>
   <link rel="stylesheet" type="text/css" href="../../comun/estilo.css">
   <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
   <meta content="Microsoft FrontPage 4.0" name=GENERATOR>

<script type="text/JavaScript">

function check_datos(data)
{   
	// Comprobar ISBN/ISSN (no vacio)

   if(data.id_fuente.value == "" ||data.nuevo_id_fuente.value == "") // Se chequea en el ALTA y en la MODIFICACION
   {
      alert("El campo ISBN/ISSN est\u00e1 vac\u00edo.");
	  return false;
   } 
   // Comprobar FECHA

   if(data.dia.value == 31 && 
	  (data.mes.value == 2 || data.mes.value == 4 || data.mes.value == 6 ||
	   data.mes.value == 9 || data.mes.value == 11))
   {
      alert("El mes seleccionado s\u00f3lo tiene 30 d\u00edas. Introduzca una fecha correcta");
	  return false;
   }

   if(data.mes.value == 2 && data.dia.value > 29)
   {
      alert("Febrero s\u00f3lo tiene 29 d\u00edas. Introduzca una fecha correcta");
	  return false;
   }

   // Si se han pasado todas las comprobaciones el formulario es valido

   return true;
}
</script>
</head>

<body>
<?php  
   include("func_fuente.php");

   if(tienePermisos("textofuenteoperacion"))
   {
	  $arg_op = $_GET['arg_op'];


      //-- ALTA DE FUENTE -------------------------------------------------------------------------

	  if($arg_op == 'nuevo') 
	  {
?>
<p align="center">
	<span class="titulo titulo_rojo"><?php echo $crear_fuente_texto ?></span><br>
	<img border="0" src="../../imagenes/linea_horiz.gif" >
</p>

<p align="center">
<table border="0">
 <!-- FORMULARIO para el alta de un nuevo campo de texto -->
<form action="operacion_fuente2.php" method="post" name="formulario" onSubmit='return check_datos(formulario);'>
   <input type="hidden" name="arg_op" value="alta">
   <tr>
      <td><?php echo $tipo_texto ?>:</td>
      <td>
      	<select name="edition" title="Tipo">
	    	<option value="web">Web</option>
	        <option value="libro"><?php echo $libro ?></option>
			<option value="revista"><?php echo $revista ?></option>
	        <option value="otro"><?php echo $other ?></option>
		</select>
	  </td>
   </tr>
   <tr>
      <td>ISBN/ISSN:</td><td><input name="id_fuente" size="50" class="obligatorio" title="<?php echo $codigo ?>"></td></tr>
   <tr>
      <td><?php echo $titulo ?>:</td><td><input name="h_title" size="50" title="<?php echo $nombre_titulo ?>"></td></tr>
   <tr>
      <td><?php echo $autor ?>:</td><td><input name="h_author" size="50" title="<?php echo $autor_creador ?>"></td></tr>
   <tr>
      <td><?php echo $lugar_url ?>:</td><td><input name="pub_place" size="50" title="<?php echo $lugar_publicacion ?>"></td></tr> 
   <tr>
      <td><?php echo $editorial ?>:</td><td><input name="publisher" size="50" title="<?php echo $editorial_creador ?>"></td></tr>
   <tr>
      <td><?php echo $fecha ?>:</td>
      <td>
	     <?php echo $day ?>: <select name="dia" title="<?php echo $fecha_publicacion ?>">
<?php 
         for($i=1; $i < 32; $i++)
		 {
		    echo "<option value=\"$i\">".$i."</option>";
		 }
?>
         </select>
         &nbsp;<?php echo $month ?>: <select name="mes" title="<?php echo $fecha_publicacion ?>">
<?php 
		 for($i=1; $i < 13; $i++)
	     {
	        echo "<option value=\"$i\">".$i."</option>";
		 }
		 $anyo = getdate();
	     $anyo = $anyo['year'];
?> 
         </select>
		 &nbsp;<?php echo $year ?>: <input name=anyo size="5" value="<?php echo $anyo;?>" title="<?php echo $fecha_publicacion ?>">
      </td>
   </tr>
   <tr>
      <td align="center" colspan="2">
      	<br><input type="submit" class="boton long_93 boton_aceptar" value="      <?php echo $boton_aceptar ?> " />&nbsp;&nbsp;
      	<input type="button" class="boton long_93 boton_cancelar" value="      <?php echo $boton_cancelar ?> " onclick="document.location='menu_admin_fuente.php';"/>&nbsp;&nbsp;
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
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_tipo ?></a> > <a href="menu_admin_fuente.php"><?php echo $titulo_menu_admin_fuente ?></a> > <u><?php echo $crear_fuente_texto ?></u></td>
   </tr>
</table>

<?php 
      }
      else
      //-- BAJA DE FUENTE -------------------------------------------------------------------------

	  if($arg_op == 'eliminar')  
	  {
	     $id_fuente = $_GET['id_fuente'];
	     $edition = $_GET['edition'];
	     $h_title = $_GET['h_title'];
	     $h_author = $_GET['h_author'];
?>

<p align=center><span class="titulo titulo_rojo"><?php echo $eliminar_fuente_texto ?></span><br>
<img border="0" src="../../imagenes/linea_horiz.gif" ></p>

<?php 
         // Conexion con la base de datos
         include ("../../comun/conexion.php");


         // Consulta a la base de datos 

         $consulta= "SELECT id_fuente FROM texto WHERE id_fuente='$id_fuente'";
         $resultado = mysql_query($consulta) or die($lectura_fuente_incorrecta . mysql_error()); 

         mysql_close($enlace);

         if(mysql_num_rows($resultado) == 0) //-- No hay textos de esta fuente
		 {
?>


<p align="center"><?php echo $mensaje41 ?></p>

<p align="center">
   <!-- TABLA para la eliminacion de un campo de texto -->
  <table border="0" width="330" style="border: 1 dashed #CC0000" bgcolor="#FFFF99" cellspacing="5">
    <tr>
      <td width="20%"><b>ISBN/ISSN:</b></td>
      <td width="80%"><?php echo $id_fuente;?></td>
    </tr>
	<tr>
      <td width="20%"><b><?php echo $tipo_texto ?>:</b></td>
      <td width="80%"><?php echo $edition;?></td>
    </tr>
	<tr>
      <td width="20%"><b><?php echo $titulo ?>:</b></td>
      <td width="80%"><i><?php echo $h_title;?></i></td>
    </tr>
	<tr>
      <td width="20%"><b><?php echo $autor ?>:</b></td>
      <td width="80%"><?php echo $h_author;?></td>
    </tr>
  </table>
</p>

<form action="operacion_fuente2.php" method=post>
  <input type="hidden" name="arg_op" value="eliminar">
  <input type="hidden" name="id_fuente" value="<?php echo $id_fuente?>">
	<p align="center">
	  <input type="submit" class="boton long_93 boton_aceptar" value="      <?php echo $boton_aceptar ?> " />&nbsp;&nbsp;
	  <input type="button" class="boton long_93 boton_cancelar" value="      <?php echo $boton_cancelar ?> " onclick="document.location='menu_admin_fuente.php';"/>  
	</p>
</form>

<?php 
         }
	     else // Existen textos de esta fuente => no se puede eliminar la fuente de texto
		 {
?>


<p align="center"><?php echo $mensaje42 ?></p>

<p align="center">
  <table border="0" width="330" style="border: 1 dashed #CC0000" bgcolor="#FFFF99" cellspacing="5">
<tr>
      <td width="20%"><b>ISBN/ISSN:</b></td>
      <td width="80%"><?php echo $id_fuente;?></td>
    </tr>
	<tr>
      <td width="20%"><b><?php echo $tipo_texto ?>:</b></td>
      <td width="80%"><?php echo $edition;?></td>
    </tr>
	<tr>
      <td width="20%"><b><?php echo $titulo ?>:</b></td>
      <td width="80%"><i><?php echo $h_title;?></i></td>
    </tr>
	<tr>
      <td width="20%"><b><?php echo $autor ?>:</b></td>
      <td width="80%"><?php echo $h_author;?></td>
    </tr>
  </table>
</p>

<?php 
	        echo "<p align=\"center\">";
            echo "<table border=\"0\" width=\"500\" style=\"border: 1 dashed #CC0000\" bgcolor=\"#FFFF55\" cellspacing=\"5\">";
	        echo "<tr><td align=\"center\">";
		    echo "<b>".$atencion.":</b> ".$mensaje43 . $id_fuente . "</b>.<br>";
			echo $mensaje21;
			echo "</td></tr>";

			// Conexion con la base de datos
			 include ("../../comun/conexion.php");


			 // Consulta a la base de datos 

			 $consulta= "SELECT h_title FROM texto WHERE id_fuente='$id_fuente'";
			 $resultado = mysql_query($consulta) or die($lectura_fuente_incorrecta . mysql_error()); 

         mysql_close($enlace);
			
			while($obj = mysql_fetch_object($resultado))
            {
		       echo "<tr><td align=\"center\" >$obj->h_title</td></tr>";
            }

		    echo "<tr><td align=\"center\">";
			echo "<b>".$no_creacion_fuente."</b><br>".$mensaje44;
			echo "</td></tr>";
			echo "</table></p>";
			
			echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='menu_admin_fuente.php';\" /></p>";
		 }
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
	  else
      //-- MODIFICACION DE FUENTE -----------------------------------------------------------------

      if($arg_op == 'modificar') 
	  {
	     $id_fuente = $_GET['id_fuente'];

         // Conexion con la base de datos
         include ("../../comun/conexion.php");

         // Consulta a la base de datos

         $consulta = "SELECT edition,h_title,h_author,pub_place,publisher,pub_date FROM fuente WHERE fuente.id_fuente = '$id_fuente'";
         $res = mysql_query($consulta) or die($lectura_fuente_incorrecta);

         // AQUI SE HACE LA MODIFICACION DE LOS DATOS

         $fila = mysql_fetch_assoc($res);
         
		 $edition = $fila["edition"];
         $h_title = $fila["h_title"]; 
         $h_author = $fila["h_author"];
         $pub_place = $fila["pub_place"];
         $publisher = $fila["publisher"];
         $pub_date = $fila["pub_date"];

         mysql_close($enlace);
?>
<p align="center"><span class="titulo titulo_rojo"><?php echo $modificar_fuente_texto ?></span><br>
<img border="0" src="../../imagenes/linea_horiz.gif" ></p>
<!-- FORMULARIO para confimacion de modificacion de un campo de texto -->
<form action="operacion_fuente2.php" method=post name="formulario" onSubmit='return check_datos(formulario);'>
   <input type="hidden" name="arg_op" value="modificar">
   <input type="hidden" name="id_fuente" value="<?php echo $id_fuente?>">   
<p align="center">
<table border="0">
   <tr>
      <td><?php echo $tipo_texto ?>:</td>
      <td>
         <select name="edition" title="Tipo">
	        <option value="web"<?php  if($edition == 'web') {?>selected<?php }?>>Web</option>		 
	        <option value="libro"<?php  if($edition == 'libro') {?>selected<?php }?>><?php echo $libro ?></option>		 
	        <option value="revista"<?php  if($edition == 'revista') {?>selected<?php }?>><?php echo $revista ?></option>		 
	        <option value="otro"<?php  if($edition == 'otro') {?>selected<?php }?>><?php echo $other ?></option>		 
		 </select>
      </td>
   </tr>
   <tr>
      <td>ISBN/ISSN:</td><td><input type="text" name=nuevo_id_fuente value="<?php echo $id_fuente?>" size="50" class="obligatorio" title="<?php echo $codigo ?>"></td></tr>
   <tr>
      <td><?php echo $titulo ?>:</td><td><input type="text" name=h_title value="<?php echo $h_title?>" size="50" title="<?php echo $nombre_titulo ?>"></td></tr>
   <tr>
      <td><?php echo $autor ?>:</td><td><input type="text" name=h_author value="<?php echo $h_author?>" size="50" title="<?php echo $autor_creador ?>"></td></tr>
   <tr>
      <td><?php echo $lugar_url ?>:</td><td><input type="text" name=pub_place value="<?php echo $pub_place?>" size="50" title="<?php echo $lugar_publicacion ?>"></td></tr>
   <tr>
      <td><?php echo $editorial ?>:</td><td><input type="text" name=publisher value="<?php echo $publisher?>" size="50" title="<?php echo $editorial_creador ?>"></td></tr>
   <tr>
<?php 
	     $dia = obtenerDia($pub_date);
		 $mes = obtenerMes($pub_date);
		 $anyo = obtenerAnyo($pub_date)
?>
      <td><?php echo $fecha  ?>:</td><td>
	     <?php echo $day ?>: <select name="dia" title="<?php echo $fecha_publicacion ?>">
<?php 
		 echo "<option value=\"$dia\">$dia";
		 for($i=1; $i < 32; $i++)
		 {
	        if($i != $dia)
			   echo "<option value=\"$i\">".$i."</option>";
		 }
?>
      </select>
         &nbsp;<?php echo $month ?>: <select name="mes" title="<?php echo $fecha_publicacion ?>">
<?php 
		 echo "<option value=\"$mes\">$mes";
		 for($i=1; $i < 13; $i++)
		 {
		    if($i != $mes) 
			   echo "<option value=\"$i\">".$i."</option>";
		 }
?> 
      </select>
		 &nbsp;<?php echo $year ?>: <input name=anyo size="5" value="<?php echo $anyo;?>" title="<?php echo $fecha_publicacion ?>">
		</td>
	</tr>
	<tr>
		<td align="center" colspan="2">
			<br><input type="submit" class="boton long_93 boton_aceptar" value="      <?php echo $boton_aceptar ?> " />&nbsp;&nbsp;
			<input type="button" class="boton long_93 boton_cancelar" value="      <?php echo $boton_cancelar ?> " onclick="document.location='menu_admin_fuente.php';"/>&nbsp;&nbsp;
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
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_texto ?></a> > <a href="menu_admin_fuente.php"><?php echo $titulo_menu_admin_fuente ?></a> > <u><?php echo $modificar_fuente_texto ?></u></td>
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