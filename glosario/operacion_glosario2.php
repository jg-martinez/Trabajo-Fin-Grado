<?php 
   session_start();header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);

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
<!-- 
     Pagina que realiza las operaciones sobre el glosario de terminos
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head><title></title>
   <link rel="stylesheet" type="text/css" href="../comun/estilo.css"> 
   <meta http-equiv=Content-Type content="text/html; charset=windows-1252">
   <meta content="MSHTML 6.00.2800.1498" name=GENERATOR>
   
   <script type="text/JavaScript">
   
	function contar()
	{
		var checkboxes = document.getElementById("formulario").acep;
		var cont = 0;
		
		for (var x=0; x < checkboxes.length; x++)
		{
			if (checkboxes[x].checked)
			{
				cont++;
			}
		}
		if (cont == 1)
		{
			return true;
		}
		else
		{
			alarm ("Ha seleccionado de nuevo ninguna o mas de una acepci\xF3n. Por favor, seleccione s\xF3lo UNA acepci\xF3n");
			return false;
		}
	}
   
   </script>
   
</head>
<body>
<?php 
   if(tienePermisos("glosariooperacion2"))
   {
	  echo "<p align=\"center\"><span class=\"titulo titulo_rojo\">".$administracion_terms_glosario."</span><br><img border=\"0\" src=\"../imagenes/linea_horiz.gif\" ></p>";

      include("func_glosario.php");
	  include ("../historico/operaciones_historico.php");
	  
	  if (isset($_POST['arg_op']))
	  {
		$arg_op = $_POST['arg_op'];
	  }
	  else 
	  {
		$arg_op = $_GET['arg_op'];
	  }
	  
	  
	  if ($arg_op == 'alta_comp')
	  {
			$termino = $_GET['termino'];
?>
			<p align=\"center\"><font size=\"4\"><b><?php echo $el_term ?> <i><?php echo $termino ?></i><?php echo $mensaje80 ?></b></font></p>;
			<p align=\"center\"><?php echo $mensaje81 ?></p>;
			<p class=\"Info\"><?php echo $atencion ?>: <?php echo $mensaje82 ?> <b> <?php echo $mensaje83 ?></b><?php echo $mensaje84 ?></p>;
			<script>document.location='operacion_glosario.php?arg_op=buscar_contexto&termino=$termino&idioma=$id&desdelista=$desdelista'</script>;
<?php		
	  }

      if($arg_op == 'alta')  //-- NUEVO TERMINO DEL GLOSARIO --------------------------------------
	  {
            // recoge los datos del formulario de alta de un nuevo termino
		 $termino = $_POST['termino'];
		 $id_termino = $_POST['id_termino'];
         $id = $_POST['idioma'];
         if (isset($_POST['desdelista']))
		 {
			$desde_lista = $_POST['desdelista'];
         }
		 else
		 {
			$desde_lista = '';
		 }
		 
		 $lim_tamano = $_POST['lim_tamano'];
		 //$binario_nombre=$_FILES['archivo']['name'];
		 //$binario_tamano=$_FILES['archivo']['size'];
		 //$binario_tipo=$_FILES['archivo']['type'];
		 //$binario_temporal= $_FILES['archivo']['tmp_name'];
		 
		 if (isset($_POST['compuesto']))
		 {
			$compuesto = $_POST['compuesto'];
			$pal_term_comp1 = $_POST['pal_term_comp1'];
			$pal_term_comp2 = $_POST['pal_term_comp2'];
			$term_comp_select1 = $_POST['term_comp1'];
			$term_comp_select2 = $_POST['term_comp2'];
		 }
		 else
		 {
			$compuesto = "";
			$pal_term_comp1 = "";
			$pal_term_comp2 = "";
			$term_comp_select1 = "";
			$term_comp_select2 = "";
			
		 }
		 
		alta_termino($termino, $id, $id_termino, $compuesto, $pal_term_comp1, $pal_term_comp2, $term_comp_select1, $term_comp_select2, $lim_tamano);
		 
		 if ($desde_lista == "")
		 {
?>
<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="resultado.php?inicial=admin"><?php echo $glosario ?></A> > <u><?php echo $titulo_nuevo_termino ?></u></td>
   </tr>
</table>
<?php 
		 }
      }

      if($arg_op == 'eliminar')  //-- ELIMINAR TERMINO DEL GLOSARIO -------------------------------------
	  {
		 $termino = $_POST['termino'];

		 eliminar_termino($termino);
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="resultado.php"><?php echo $glosario ?></A> > <u><?php echo $eliminacion_terms_glosario ?></u></td>
   </tr>
</table>

<?php 
      }

      if($arg_op == 'modificar')  //-- MODIFICAR TERMINO DEL GLOSARIO -------------------------------------
	  {  		 
            // recoge los datos del formulario de modificacion de un termino del glosario
		$termino = $_POST['termino'];
		$id_termino = $_POST['id_termino'];
        $idioma = $_POST['idioma'];
        if (isset($_POST['desdelista']))
		{
			$desdelista = $_POST['desdelista'];
        }
		else
		{
			$desdelista = '';
		}
		
		$lim_tamano = $_POST['lim_tamano'];
	
		//modificar_termino($termino, $idioma, $cat_gramatical, $def0, $def1, $def2, $def3, $def4, $def5, $def6, $def7, $def8, $def9, $traduccion, $desdelista, $lim_tamano, $binario_nombre, $binario_tamano, $binario_tipo, $binario_temporal);	
		modificar_termino($termino, $idioma, $id_termino, $lim_tamano, $desdelista);
		 
		 
		 // Si viene desde la lista, el camino no debe dejar salirse salvo para cerrar la ventana.
		 if ($desdelista == "")
		 {
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="resultado.php"><?php echo $glosario ?></A> > <u><?php echo $modificacion_terms_glosario ?></u></td>
   </tr>
</table>

<?php 
		 }
      }
	  
	  if ($arg_op == 'imagen_acepcion')
	  {
	  
		$lim_tamano = $_GET['lim_tamano'];
		$termino = $_GET['termino'];
		$id_termino = $_GET['id_termino'];
		$id = $_GET['idioma'];
		
		include ("../comun/conexion.php");
				
		$consulta = "SELECT orden,cat_gramatical,definicion,traduccion,tamano FROM acepcion WHERE id_glosario='$id_termino' order by orden";
		$res = mysql_query($consulta) or die (mysql_error());
		
		echo "<form enctype=\"multipart/form-data\" action=\"operacion_glosario.php\" method=\"post\" name=\formulario\">";
		
		echo "<input type=\"hidden\" name=\"arg_op\" value=\"imagen\">";
		echo "<input type=\"hidden\" name=\"termino\" value=\"$termino\">";
		echo "<input type=\"hidden\" name=\"idioma\" value=\"$id\">";
		echo "<input type=\"hidden\" name=\"id_termino\" value=\"$id_termino\">";
		echo "<input type=\"hidden\" name=\"lim_tamano\" value=\"$lim_tamano\">";
		if (isset($_GET['continuar']))
		{
			echo "<input type=\"hidden\" name=\"continuar\" value=\"si\">";
		}
		if (isset($_GET['pal_comp']))
		{
			$pal_comp = $_GET['pal_comp'];
			echo "<input type=\"hidden\" name=\"pal_comp\" value=\"$pal_comp\">";
		}
		
		if (isset($_GET['modificar']))
		{
			echo "<input type=\"hidden\" name=\"modificar\" value=\"si\">";
			echo "<p align=\"center\">".$el_term." <b>$termino</b> tiene las siguientes acepciones.</p>";
			echo "<p align=\"center\">Si desea a&ntilde;adir una nueva imagen, seleccione la acepci&oacute;n a la que desea a&ntilde;adir la imagen.</p>";
		}
		else
		{
			echo "<p align=\"center\">Las siguientes acepciones del t&eacute;rmino no disponen de una imagen, &iquest;desea a&ntilde;adir una?</p>";
		}
		echo "<table border=\"0\" width=\"100%\" cellpadding=\"5\" cellspacing=\"5\">";
		
		while ($obj = mysql_fetch_object($res))
		{
			echo "<tr>";
			echo "<td align=\"center\"><b><u><font size=\"3\">".$termino."</font></u></b></td>";
			echo "</tr><tr></tr>";
			$i = 0;
			if (isset($_GET['modificar'])) // si se esta modificando un termino se muestran todas sus acepciones tenga o no imagen
			{
				echo "<tr><td align=\"center\">";
					echo "<input type=\"checkbox\" name=\"acepcion1".$i."\" value=\"".$obj->orden."\"/><b><i>Acepeci&oacute;n ".$i."</i></b>";
					echo "<br></td></tr>";
					echo "<tr><td align=\"center\">";
					echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Categor&iacute;a Gramatical:   </b> ".$obj->cat_gramatical."<br></td></tr>";
					echo "<tr><td align=\"center\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Traducci&oacute;n:   </b>".$obj->traduccion."<br></td></tr>";
					echo "<tr><td align=\"center\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Definici&oacute;n:   </b>".$obj->definicion."<br></td></tr>";
					echo "<tr><td align=\"center\"><input type='button' class='boton' ";
					echo "value=' Mostrar Imagen ' onclick='window.open(\"mostrar_imagen.php?orden=$obj->orden&termino=$termino\")'/></td></tr>";
					echo "<tr><td colspan='3' align=\"center\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#000000\"><b>Introduzca una nueva imagen</b></font><input type=\"file\" name=\"archivo\"></td></tr>";			
					$i++;
			}
			else
			{
				if ($obj->tamano == 0) // la acepcion no tiene imagen y se pregunta si se quiere anadir una
				{
					echo "<tr><td align=\"center\">";
					echo "<input type=\"checkbox\" name=\"acepcion1".$i."\" value=\"".$obj->orden."\"/><b><i>Acepeci&oacute;n ".$i."</i></b>";
					echo "<br></td></tr>";
					echo "<tr><td align=\"center\">";
					echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Categor&iacute;a Gramatical:   </b> ".$obj->cat_gramatical."<br></td></tr>";
					echo "<tr><td align=\"center\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Traducci&oacute;n:   </b>".$obj->traduccion."<br></td></tr>";
					echo "<tr><td align=\"center\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Definici&oacute;n:   </b>".$obj->definicion."<br></td></tr>";
					echo "<tr><td colspan='4' align=\"center\"><b>Imagen</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#000000\"><b>Introduzca una imagen</b></font><input type=\"file\" name=\"archivo\"></td></tr>";			
					$i++;
				}
			}
		}
	
		echo "<tr><td align=\"center\">";
	   echo "<input type=\"submit\" class=\"boton long_93 boton_aceptar\" value=\"      $boton_aceptar\"/>&nbsp;&nbsp;&nbsp;";
	   echo "<input type=\"button\" class=\"boton long_93 boton_cancelar\" value=\"	Finalizar\" onclick=\"document.location='resultado.php?inicial=admin_termino';\" />";
	   echo "</td></tr></table>";
	   echo "</form>";
	}

      if($arg_op == 'eliminar_relacion')  //-- ELIMINAR RELACION -------------------------------------
	  {
		 $id_relacion = $_POST['id_relacion'];

		 eliminar_relacion($id_relacion);
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.htm"><?php echo $menu_principal ?></a> > <a href="resultado.php?inicial=admin"><?php echo $glosario ?></A> > <u><?php echo $eliminacion_rels_terms ?></u></td>
   </tr>
</table>

<?php 
      }

      if($arg_op == 'modificar_relacion')  //-- MODIFICAR RELACION -------------------------------------
	  {
		 $id_relacion = $_POST['id_relacion'];
		 $tipo = $_POST['tipo'];
		 $termino1 = $_POST['termino1'];
		 $termino2 = $_POST['termino2'];
		 $part = $_POST['particula'];
		 $ant_tipo = $_POST['ant_tipo'];
		 $ant_termino1 = $_POST['ant_termino1'];
		 $ant_termino2 = $_POST['ant_termino2'];
		 $nt = $_POST['nota'];

         $termino1 = convertirMinusculas($termino1);
		 $termino2 = convertirMinusculas($termino2);

		 modificar_relacion($id_relacion, $ant_tipo, $tipo, $ant_termino1, $termino1, $ant_termino2, $termino2, $part, $nt);
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="resultado.php?inicial=admin"><?php echo $glosario ?></A> > <u><?php echo $modificacion_rels_terms ?></u></td>
   </tr>
</table>

<?php 
      }
	  
	  if($arg_op == 'alta_relacion') //-- ALTA DE UNA NUEVA RELACION -------------------------------------
	  {
		$termino1 = $_POST['termino1'];
		$termino2 = $_POST['termino2'];
		$ac1 = $_POST['ac1'];
		$ac2 = $_POST['ac2'];
		$id_relacion = $_POST['id_relacion'];
		$id_tipo_relacion = $_POST['id_tipo_relacion'];
		$term1 = $_POST['term1'];
		$term2 = $_POST['term2'];
		$particula = $_POST['particula'];
		$nt = $_POST['nt'];
		
		/*echo "<pre>";
		print_r($_POST);
		echo "</pre>";*/
		
		include ("../comun/conexion.php");
		
		if ($ac1 == "si acepcion" && $ac2 == "no acepcion")
		{			
			$i = 0;
			foreach ($_POST as $key => $valor) 
			{ 
				if (substr($key, 0, 9) == 'acepcion1') 
				{	
					$val = $valor;
					$i++;
				}
			}
			if ($i == 0 || $i > 1) // No se ha seleccionado ninguna acepcion o se ha seleccionado mas de una
			{
				//echo "no ha seleccionado ninguna acepcion o ha seleccionado mas de una para el t&eacute;rmino 1";
				echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>no ha seleccionado ninguna acepcion o ha seleccionado mas de una para el t&eacute;rmino 1</p>";
				echo "<p align=\"center\"><input type=\"button\" class=\"boton\" value=\"      $boton_volver\" onclick=\"document.location='operacion_glosario.php?arg_op=mostrar_acepciones&id_tipo_relacion=$id_tipo_relacion&term1=$term1&term2=$term2&particula=$particula&nt=$nt&acc=volver&id_relacion=$id_relacion'\" />&nbsp;&nbsp;";
			}
			else // se ha seleccionado solo una acepcion para el termino1
			{
				$consulta = "UPDATE relacion SET orden_1='$val' WHERE id_relacion='$id_relacion'";
				$res = mysql_query($consulta) or die (mysql_error());
				
				echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>".$mensaje104."</p>";
				echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_relacion'\" /></p>";
		
			}
		}
		else if ($ac2 == "si acepcion" && $ac1 == "no acepcion")
		{
			$i = 0;
			foreach ($_POST as $key => $valor) 
			{ 
				if (substr($key, 0, 9) == 'acepcion2') 
				{	
					$val = $valor;
					$i++;
				}
			}
			if ($i == 0 || $i > 1) // No se ha seleccionado ninguna acepcion o se ha seleccionado mas de una
			{
				//echo "no ha seleccionado ninguna acepcion o ha seleccionado mas de una para el t&eacute;rmino 2";
				echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>No ha seleccionado ninguna acepcion o ha seleccionado mas de una para el t&eacute;rmino 2</p>";
				echo "<p align=\"center\"><input type=\"button\" class=\"boton\" value=\"      $boton_volver\" onclick=\"document.location='operacion_glosario.php?arg_op=mostrar_acepciones&id_tipo_relacion=$id_tipo_relacion&term1=$term1&term2=$term2&particula=$particula&nt=$nt&acc=volver&id_relacion=$id_relacion'\" />&nbsp;&nbsp;";
			}
			else // se ha seleccionado solo una acepcion para el termino1
			{
				$consulta = "UPDATE relacion SET orden_2='$val' WHERE id_relacion='$id_relacion'";
				$res = mysql_query($consulta) or die (mysql_error());
				
				echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>".$mensaje104."</p>";
				echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_relacion'\" /></p>";
			}
		}
		else if ($ac1 == "no acepcion" && $ac2 == "no acepcion")
		{
			echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>".$mensaje104."</p>";
			echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_relacion'\" /></p>";
		}
		else
		{
			$i = 0;
			foreach ($_POST as $key => $valor) 
			{ 
				if (substr($key, 0, 9) == 'acepcion1') 
				{	
					$val1 = $valor;
					$i++;
				}
			}
			if ($i == 0 || $i > 1) // No se ha seleccionado ninguna acepcion o se ha seleccionado mas de una
			{
				//echo "no ha seleccionado ninguna acepcion o ha seleccionado mas de una para alguno de los dos t&eacute;rminos";
				echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>No ha seleccionado ninguna acepcion o ha seleccionado mas de una para alguno de los dos t&eacute;rminos</p>";
				echo "<p align=\"center\"><input type=\"button\" class=\"boton\" value=\"      $boton_volver\" onclick=\"document.location='operacion_glosario.php?arg_op=mostrar_acepciones&id_tipo_relacion=$id_tipo_relacion&term1=$term1&term2=$term2&particula=$particula&nt=$nt&acc=volver&id_relacion=$id_relacion'\" />&nbsp;&nbsp;";
			}
			else
			{
				$i = 0;
				foreach ($_POST as $key => $valor) 
				{ 
					if (substr($key, 0, 9) == 'acepcion2') 
					{	
						$val2 = $valor;
						$i++;
					}
				}
				if ($i == 0 || $i > 1) // No se ha seleccionado ninguna acepcion o se ha seleccionado mas de una
				{
					//echo "no ha seleccionado ninguna acepcion o ha seleccionado mas de una para alguno de los dos t&eacute;rminos";
					echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>No ha seleccionado ninguna acepcion o ha seleccionado mas de una para el t&eacute;rmino 2</p>";
					echo "<p align=\"center\"><input type=\"button\" class=\"boton\" value=\"      $boton_volver\" onclick=\"document.location='operacion_glosario.php?arg_op=mostrar_acepciones&id_tipo_relacion=$id_tipo_relacion&term1=$term1&term2=$term2&particula=$particula&nt=$nt&acc=volver&id_relacion=$id_relacion'\" />&nbsp;&nbsp;";
				}
				else // se ha seleccionado una acepcion de cada termino
				{
					$consulta = "UPDATE relacion SET orden_1='$val1' WHERE id_relacion='$id_relacion'";
					$res = mysql_query($consulta) or die (mysql_error());
					
					$consulta = "UPDATE relacion SET orden_2='$val2' WHERE id_relacion='$id_relacion'";
					$res = mysql_query($consulta) or die (mysql_error());
					
					echo "<p class=\"Resultado\"><img border=\"0\" src=\"../imagenes/info.gif\"><br>".$mensaje104."</p>";
					echo "<p align=\"center\"><input type=\"button\" class=\"boton long_93 boton_aceptar\" value=\"      ".$boton_aceptar." \" onclick=\"document.location='resultado.php?inicial=admin_relacion'\" /></p>";
				}
			}
		}

      }
      if($arg_op == 'nuevo_tipo_relacion')  //-- NUEVO TIPO RELACION -------------------------------------
	  {
		 //$id_tipo_relacion = $_POST['id_tipo_relacion'];
		 $nombre_tipo = $_POST['nombre_tipo'];
		 $descripcion_relacion = $_POST['descripcion_relacion'];
		 
		 /* Consulta a la base de datos */
		 include ("../comun/conexion.php");
		 
		 $consulta= "SELECT MAX(indice) FROM tipo_relacion";
		 $codigo = mysql_query($consulta);		 
		 $id_tipo_relacion_max = mysql_result($codigo,0);
		 $id_tipo_relacion_max++;
			
		 $id_tipo_relacion = "r".$id_tipo_relacion_max;
		 
		 alta_tipo_relacion($id_tipo_relacion, $nombre_tipo, $descripcion_relacion, $id_tipo_relacion_max);
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="resultado.php?inicial=admin"><?php echo $glosario ?></A> > <u><?php echo $nuevo_tipo_rels ?></u></td>
   </tr>
</table>

<?php 
      }

      if($arg_op == 'eliminar_tipo_relacion')  //-- ELIMINAR TIPO RELACION -------------------------------------
	  {
		 $id_tipo_relacion = $_POST['id_tipo_relacion'];
		 
		 eliminar_tipo_relacion($id_tipo_relacion);
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="resultado.php?inicial=admin"><?php echo $glosario ?></A> > <u><?php echo $eliminacion_tipos_rels ?></u></td>
   </tr>
</table>

<?php 
      }

      if($arg_op == 'modificar_tipo_relacion')  //-- MODIFICAR TIPO RELACION ---------------------------
	  {
		 $ant_id_tipo_relacion = $_POST['ant_id_tipo_relacion'];
		 $id_tipo_relacion = $_POST['id_tipo_relacion'];
		 $nombre_tipo = $_POST['nombre_tipo'];
		 $descripcion_relacion = $_POST['descripcion_relacion'];

		 modificar_tipo_relacion($ant_id_tipo_relacion, $id_tipo_relacion, $nombre_tipo, $descripcion_relacion);
?>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="resultado.php?inicial=admin"><?php echo $glosario ?></A> > <u><?php echo $modificacion_tipos_rels ?></u></td>
   </tr>
</table>

<?php 
      }


   }
   else
   {
	   echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   }
?>

</body>
</html>
