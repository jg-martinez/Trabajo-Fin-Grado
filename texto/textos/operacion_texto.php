<?php 
   session_start();header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);

   include ("../../comun/permisos.php");
   include("func_texto.php");
   
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

   if(tienePermisos("textotextosoperacion"))
   {
	  $arg_op = $_GET['arg_op'];

	 
      //---------- DESCARGA DEL TEXTO ----------
      if($arg_op == 'descargar')  
	  {
	  	visualizar_texto($_GET['id_texto'],$arg_op);
      }
      else
      {
?>
<!-- operacion_texto.php --------------------------------------------------------------------------

     Realiza la operacion indicada sobre el texto: ALTA, BAJA y MODIFICACION.

----------------------------------------------------------------------------------------------- -->

<html>

<head>
   <title></title>
   <link rel="stylesheet" type="text/css" href="../../comun/estilo.css">
   <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
   <meta content="Microsoft FrontPage 4.0" name=GENERATOR>
   
<script type="text/JavaScript">
// comprobacion de los datos introducidos en los formularios
function check_datos(data)
{    
   // Comprobar TITULO (no vacio)
   if(data.h_title.value == "") // Se chequea en el ALTA y en la MODIFICACION
   {
      alert("El campo T\u00cdTULO est\u00e1 vac\u00edo.");
	  return false;
   }

   if("$arg_op" == "nuevo" && data.fichero.value == "") // Se chequea en el ALTA y en la MODIFICACION
   {
      alert("El campo FICHERO est\u00e1 vac\u00edo. No hay ning\u00fan texto seleccionado");
	  return false;
   }

   // Si se han pasado todas las comprobaciones el formulario es valido
   return true;
}

function buscarTexto()
{
	window.open("texto_relacionado.php","buscadortexto","scrollbars=yes");
}

function buscarFuente()
{
	window.open("fuente_relacionada.php","buscadortexto","scrollbars=yes");
}

function limpiarFuente()
{
	document.formulario.fuente_relacionada.value = "";
	document.formulario.txt_fuente_relacionada.value = "";
	document.formulario.txt_fuente_relacionada.title = "";

	document.formulario.fuente_edition.disabled = false;
	document.formulario.fuente_id_fuente.readOnly = false;
	document.formulario.fuente_h_title.readOnly = false;
	document.formulario.fuente_h_author.readOnly = false;
	document.formulario.fuente_pub_place.readOnly = false;
	document.formulario.fuente_publisher.readOnly = false;
	document.formulario.fuente_dia.disabled = false;
	document.formulario.fuente_mes.disabled = false;
	document.formulario.fuente_anyo.readOnly = false;
}

function cargarFuente (codigo, texto, descripcion) {
	document.formulario.fuente_relacionada.value = codigo;
	document.formulario.txt_fuente_relacionada.title = descripcion.replace(/#/g,"\n");
	document.formulario.txt_fuente_relacionada.value = texto;

	document.formulario.fuente_edition.selectedIndex = -1;
	document.formulario.fuente_dia.selectedIndex = -1;
	document.formulario.fuente_mes.selectedIndex = -1;
	document.formulario.fuente_id_fuente.value = "";
	document.formulario.fuente_h_title.value = "";
	document.formulario.fuente_h_author.value = "";
	document.formulario.fuente_pub_place.value = "";
	document.formulario.fuente_publisher.value = "";
	document.formulario.fuente_anyo.value = "";

	
	document.formulario.fuente_edition.disabled = true;
	document.formulario.fuente_dia.disabled = true;
	document.formulario.fuente_mes.disabled = true;
	document.formulario.fuente_id_fuente.readOnly = true;
	document.formulario.fuente_h_title.readOnly = true;
	document.formulario.fuente_h_author.readOnly = true;
	document.formulario.fuente_pub_place.readOnly = true;
	document.formulario.fuente_publisher.readOnly = true;
	document.formulario.fuente_anyo.readOnly = true;
}
	
function checkDatosModificar () {
	var form = document.formulario;
	var ok = true;

	if (form.h_title.value == "") {
		alert ("Es necesario rellenar el campo 'T\u00edtulo'");
		form.h_title.focus();
		
		ok = false;
	}

	if (ok && form.txt_fuente_relacionada.value == "") {
		alert ("Es necesario rellenar el campo 'Fuente'");
		form.txt_fuente_relacionada.focus();
		ok = false;
	} 

	return ok;	
}

function checkDatosAlta () {
	var form = document.formulario;
	var ok = true;

	if (form.h_title.value == "") {
		alert ("Es necesario rellenar el campo 'T\u00edtulo'");
		form.h_title.focus();
		ok = false;
	}

	if (ok && form.fichero.value == "") {
		alert ("Es necesario rellenar el campo 'Fichero'");
		form.fichero.focus();
		ok = false;
	} 

	if (ok) {
		if (form.txt_fuente_relacionada.value == "" && form.fuente_id_fuente.value == "") { 
			alert ("Es necesario rellenar el campo 'Fuente'.\nSeleccione una fuente existente o rellena el campo ISBN/ISSN.");
			form.fuente_id_fuente.focus();
			ok = false;
		}
	} 

	return ok;	
}

</script>

</head>
<body>
<?php 
          //-- ALTA DE TEXTO --------------------------------------------------------------------------
		  if($arg_op == 'nuevo') 
		  {
		     // Conexion con la base de datos 
	      	 include ("../../comun/conexion.php");
?>

<p align="center">
	<span class="titulo titulo_rojo"><?php echo $creacion_nuevo_texto ?></span><br>
	<img border="0" src="../../imagenes/linea_horiz.gif" >
</p>
<p align="center">
<table border="0">
    <!-- FORMULARIO para el alta de un nuevo texto -->
<form action="operacion_texto2.php" method="post" enctype="multipart/form-data" name="formulario" onSubmit='return check_datos(formulario);'>
   <input type="hidden" name="arg_op" value="alta">
   <tr>
      <td><?php echo $titulo ?>:</td>
      <td><input name="h_title" size="50" class="obligatorio" title="<?php echo $titulo ?>"></td>
   </tr>
   <tr>
      <td><?php echo $formato ?>:</td>
      <td>
      	<select name="edition_stmt" title="<?php echo $formato ?>">
			<option value="texto"><?php echo $titulo_texto ?> (*.txt)</option>              
            <option value="html">HTML (*.htm, *.html)</option>
		</select>
      </td>
   </tr>
   <tr>
      <td><?php echo $idioma ?>:</td>
      <td>
      	<select name="lang_usage" title="<?php echo $idioma ?>">
			<option value="esp"><?php echo $espanol ?></option>
            <option value="ing"><?php echo $ingles ?></option>
        </select>
      </td>
   </tr>
   <tr>
      <td><?php echo $tipo_texto ?>:</td>
      <td>
         <select name="id_tipo" title="<?php echo $tipo_texto ?>">
<?php 	  
	         // Consulta a la base de datos
	
	         $consulta = "SELECT id_tipo,scheme_esp, scheme_ing FROM tipo";
	         $res = mysql_query($consulta) or die($lectura_tipos_incorrecta);
	   
			 while($obj = mysql_fetch_object($res))
	         {
				if ($lg == "esp") // Se muestran solo la info en espanol y si esta no existe en ingles.
			    {
				  if ($obj->scheme_esp == '') // Se muestra el nombre en ingles
				  {
					  echo "<option value=\"$obj->id_tipo\">$obj->scheme_ing</option>";
				  }
				  else // Se muestra en espanol
				  {
					  echo "<option value=\"$obj->id_tipo\">$obj->scheme_esp</option>";
				  }
			    }
			    else
			    {
				  if ($obj->scheme_ing == '') // Se muestra el nombre en ingles
				  {
					  echo "<option value=\"$obj->id_tipo\">$obj->scheme_esp</option>";
				  }
				  else // Se muestra en espanol
				  {
					  echo "<option value=\"$obj->id_tipo\">$obj->scheme_ing</option>";
				  }
			    }
	            //echo "<option value=\"$obj->id_tipo\">$obj->scheme</option>";
	         }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td><?php echo $campo ?>:</td>
      <td>
         <select name="id_campo" title="<?php echo $campo ?>">
<?php 
	         // Seleccion del campo del texto 
	
	         $consulta = "SELECT id_campo,description_esp, description_ing FROM campo";
	         $res = mysql_query($consulta) or die($lectura_campos_incorrecta);
	   
			 while($obj = mysql_fetch_object($res))
	         {
				if ($lg == "esp") // Se muestran solo la info en espanol y si esta no existe en ingles.
			    {
				  if ($obj->description_esp == '') // Se muestra el nombre en ingles
				  {
					  echo "<option value=\"$obj->id_campo\">$obj->description_ing</option>";
				  }
				  else // Se muestra en espanol
				  {
					  echo "<option value=\"$obj->id_campo\">$obj->description_esp</option>";
				  }
			    }
			    else
			    {
				  if ($obj->description_ing == '') // Se muestra el nombre en ingles
				  {
					  echo "<option value=\"$obj->id_campo\">$obj->description_esp</option>";
				  }
				  else // Se muestra en espanol
				  {
					  echo "<option value=\"$obj->id_campo\">$obj->description_ing</option>";
				  }
			    }
	            //echo "<option value=\"$obj->id_campo\">$obj->description</option>";
	         }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td><?php echo $fuente ?>:</td>
      <td style="border:1 #800000 solid;cellpadding:2">
      	<table>
      		<tr>
      			<td colspan="2"><i><?php echo $mensaje51 ?></i></td>
      		</tr>
		   <tr>
		      <td colspan="2">
		         <input type="hidden" name="fuente_relacionada" value="" />
		         <input name="txt_fuente_relacionada" type="text" readonly="true" size="100" title="<?php echo $fuente_relaciona ?>">
		         <input type="button" class="boton" value=" <?php echo $relacionar_fuente ?> " onclick="buscarFuente()" />
		         <input type="button" class="boton" value=" <?php echo $limpiar ?> " onclick="limpiarFuente();" />
		      </td>
		   </tr>
		   <tr>
		      <td colspan="2"><i><?php echo $mensaje52 ?></i></td>
		   </tr>
		   <tr>
		      <td><?php echo $tipo_texto ?>:</td>
		      <td>
		      	<select name="fuente_edition" title="Tipo">
			    	<option value="web" selected="true">Web</option>
			        <option value="libro"><?php echo $libro ?></option>
					<option value="revista"><?php echo $revista ?></option>
			        <option value="otro"><?php echo $other ?></option>
				</select>
			  </td>
		   </tr>
		   <tr>
		      <td>ISBN/ISSN:</td><td><input name="fuente_id_fuente" size="50" title="<?php echo $codigo ?>"></td>
		   </tr>
		   <tr>
		      <td><?php echo $titulo ?>:</td><td><input name="fuente_h_title" size="50" title="<?php echo $nombre_titulo ?>"></td>
		   </tr>
		   <tr>
		      <td><?php echo $autor ?>:</td><td><input name="fuente_h_author" size="50" title="<?php echo $autor_creador ?>"></td>
		   </tr>
		   <tr>
		      <td><?php echo $lugar ?>/URL:</td><td><input name="fuente_pub_place" size="50" title="<?php echo $lugar_publicacion ?>"></td>
		   </tr> 
		   <tr>
		      <td><?php echo $editorial ?>:</td><td><input name="fuente_publisher" size="50" title="<?php echo $editorial_creador ?>"></td>
		   </tr>
		   <tr>
		      <td><?php echo $fecha ?>:</td>
		      <td>
			     <?php echo $day ?>: <select name="fuente_dia" title="<?php echo $fecha_publicacion ?>">
		<?php 
		         for($i=1; $i < 32; $i++)
				 {
				    echo "<option value=\"$i\">".$i."</option>";
				 }
		?>
		         </select>
		         &nbsp;<?php echo $month ?>: <select name="fuente_mes" title="<?php echo $fecha_publicacion ?>">
		<?php 
				 for($i=1; $i < 13; $i++)
			     {
			        echo "<option value=\"$i\">".$i."</option>";
				 }
				 $anyo = getdate();
			     $anyo = $anyo['year'];
		?> 
		         </select>
				 &nbsp;<?php echo $year ?>: <input name="fuente_anyo" size="5" value="<?php echo $anyo;?>" title="<?php echo $fecha_publicacion ?>">
		      </td>
		   </tr>
      	</table>
      </td>
   </tr>
   <tr>
      <td><?php echo $fichero ?></td>
      <td><input name="fichero" type="file" size="50" class="obligatorio" title="<?php echo $archivo_texto ?>"></td>
   </tr>
   <tr>
      <td><?php echo $texto_relacionado ?>:</td>
      <td>
         <input type="hidden" name="texto_relacionado" value="" />
         <input name="txt_texto_relacionado" type="text" readonly="true" size="100" title="<?php echo $texto_relaciona ?>">
         <input type="button" class="boton" value=" <?php echo $relacionar_texto ?> " onclick="buscarTexto()" />
         <input type="button" class="boton" value=" <?php echo $limpiar ?> " onclick="document.formulario.texto_relacionado.value='';document.formulario.txt_texto_relacionado.value='';" />
      </td>
   </tr>
   <tr>
      <td align="center" colspan="2">
      	<br><input type="button" class="boton long_93 boton_aceptar" value="      <?php echo $boton_aceptar ?> "  onclick="if (checkDatosAlta()) submit();"/>&nbsp;&nbsp;
      	<input type="button" class="boton long_93 boton_cancelar" value="      <?php echo $boton_cancelar ?> " onclick="document.location='menu_admin_textos.php';"/>&nbsp;&nbsp;
		<input type="button" class="boton" value=" <?php echo $limpiar_formulario ?> " onclick="document.formulario.reset();"/>&nbsp;&nbsp;
      </td>
   </tr>
</table>
</form>
</p>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $administrar_textos ?></a> > <a href="menu_admin_textos.php"><?php echo $titulo_menu_admin_texto ?></a> > <u><?php echo $creacion_nuevo_texto ?></u></td>
   </tr>
</table>

<?php 
	      }
	
	      else
	      //-- BAJA DE TEXTO -/-------------------------------------------------------------------------
                  // recocida de los parametros pasados via URL
		  if($arg_op == 'eliminar')  
		  {
		     $id_texto = $_GET['id_texto'];
		     $h_title = $_GET['h_title'];
		     $edition_stmt = $_GET['edition_stmt'];
?>

<p align=center><span class="titulo titulo_rojo"><?php echo $eliminacion_texto ?></span><br>
<img border="0" src="../../imagenes/linea_horiz.gif" ></p>


<?php  echo "<p align=\"center\">".$mensaje53; ?>

<p align="center">
    <!-- TABLA que muestra los datos del texto que se quiere eliminar -->
  <table border="0" width="330" bgcolor="#FFFF99" cellspacing="0" cellpadding="0">
    <tr>
      <td width="20%"><b><?php echo $titulo ?>:</b></td>
      <td width="80%"><?php echo '['.$id_texto.'] '.$h_title;?></td>
    </tr>
	<tr>
      <td width="20%"><b><?php echo $formato ?>:</b></td>
      <td width="80%"><?php echo $edition_stmt;?></td>
    </tr>
<?php 
	         // Comprobar si existen contextos (de terminos del glosario) asociados a este texto
	      	 include ("../../comun/conexion.php");
	
	         // Consulta a la base de datos
	
	         $consulta= "SELECT id_texto FROM contexto WHERE id_texto='$id_texto'";
	         $resultado = mysql_query($consulta) or die($lectura_texto_incorrecta . mysql_error()); 
	
	         mysql_close($enlace);
	
	         if(mysql_num_rows($resultado) != 0) //-- Hay textos de este campo
			 {
			    echo "<tr><td align=\"center\" colspan=\"2\">";
				echo "<b>".$atencion.":</b><br>".$mensaje54."<br><br>".$mensaje55;
				echo "</td></tr>";
	         }
?>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr><td colspan="2" bgcolor="white">&nbsp;</td></tr>
	<tr>
		<td colspan="2" align="center" bgcolor="white">
			<form action="operacion_texto2.php" method="post" name="formulario">
			  <input type="hidden" name="arg_op" value="eliminar">
			  <input type="hidden" name="id_texto" value="<?php echo $id_texto?>">
			  <input type="hidden" name="h_title" value="<?php echo $h_title?>">
			  <input type="hidden" name="edition_stmt" value="<?php echo $edition_stmt?>">
			  <input type="submit" class="boton long_93 boton_aceptar" value="      <?php echo $boton_aceptar ?> " />&nbsp;&nbsp;
			  <input type="button" class="boton long_93 boton_cancelar" value="      <?php echo $boton_cancelar ?> " onclick="document.location='menu_admin_textos.php';"/>  
			</form>
		</td>
	</tr>
  </table>
</p>

<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $titulo_menu_admin_texto ?></a> > <a href="menu_admin_textos.php"><?php echo $textos ?></a> > <u><?php echo $eliminacion_texto ?></u></td>
   </tr>
</table>
<?php 
		  }
		  else
	      //-- MODIFICACION DE TEXTO ------------------------------------------------------------------
	      if($arg_op == 'modificar') 
		  {
		     $id_texto = $_GET['id_texto'];
	
	         // Conexion con la base de datos 
	      	 include ("../../comun/conexion.php");
	
	         // Obtenemos los datos de la BD 
	         $consulta = "SELECT h_title,edition_stmt,word_count,byte_count,lang_usage,id_tipo,id_campo,id_fuente,texto_relacionado FROM texto WHERE id_texto = '$id_texto'";
	         $res = mysql_query($consulta) or die($lectura_texto_incorrecta);
	
	         // AQUI SE HACE LA MODIFICACION DE LOS DATOS
	         $fila = mysql_fetch_assoc($res);
	         
			 $h_title = $fila["h_title"]; 
	         $edition_stmt = $fila["edition_stmt"]; 
	         $word_count = $fila["word_count"]; 
	         $byte_count = $fila["byte_count"]; 
	         $lang_usage = $fila["lang_usage"]; 
	         $id_tipo = $fila["id_tipo"]; 
	         $id_campo = $fila["id_campo"]; 
	         $id_fuente = $fila["id_fuente"];
	         $texto_rel = $fila["texto_relacionado"]; 
?>

<p align="center"><span class="titulo titulo_rojo"><?php echo $modificacion_texto ?></span><br>
<img border="0" src="../../imagenes/linea_horiz.gif" ></p>

<p align="center">
<table border="0">
    <!-- FORMULARIO para la modificacion de un texto -->
<form action="operacion_texto2.php" method="post" name="formulario" onSubmit="return check_datos(formulario);">
   <input type="hidden" name="arg_op" value="modificar">
   <input type="hidden" name="id_texto" value="<?php echo $id_texto?>">
   <tr> 
      <td><?php echo $titulo ?>:</td>
      <td><input type="text" name="h_title" value="<?php echo $h_title?>" size="50" class="obligatorio" title="<?php echo $titulo ?>"></td>
   </tr> 
   <tr>
      <td><?php echo $formato ?>:</td><td><?php echo $edition_stmt;?></td>
   </tr>
   <tr>
      <td><?php echo $palabras ?>:</td><td><?php echo $word_count;?></td>
   </tr> 
   <tr>
      <td><?php echo $tamano ?>:</td><td><?php echo $byte_count;?> bytes</td>
   </tr> 
   <tr>
      <td><?php echo $idioma ?>:</td>
      <td>
         <select name="lang_usage" title="Idioma">
            <option value="esp"<?php  if($lang_usage == "esp") {?> selected<?php  }?>><?php echo $espanol ?></option>
            <option value="ing"<?php  if($lang_usage == "ing") {?> selected<?php  }?>><?php echo $ingles ?></option>
         </select>
      </td>
   </tr>
   <tr> 
      <td><?php echo $tipo_texto ?>:</td>
      <td><select name="id_tipo" title="Tipo">
<?php       
	         $consulta2 = "SELECT id_tipo,scheme_esp, scheme_ing FROM tipo";
	         $res2 = mysql_query($consulta2) or die($lectura_tipos_incorrecta);
	
	         while($obj = mysql_fetch_object($res2))
	         {
		         echo "<option value=\"$obj->id_tipo\"";
		         if($id_tipo == $obj->id_tipo)
				 {
		         	echo " selected";
				 }
				 if ($lg == "esp") // Se muestran solo la info en espanol y si esta no existe en ingles.
			     {
				   if ($obj->scheme_esp == '') // Se muestra el nombre en ingles
				   {
					   echo ">$obj->scheme_ing";
				   }
				   else // Se muestra en espanol
				   {
					   echo ">$obj->scheme_esp";
				   }
			     }
			     else
			     {
				   if ($obj->scheme_ing == '') // Se muestra el nombre en ingles
				   {
					   echo ">$obj->scheme_esp";
				   }
				   else // Se muestra en espanol
				   {
					   echo ">$obj->scheme_ing";
				   }
			     }
				 //echo ">$obj->scheme";
	         }
?>
            </select>
         </td>
      </tr>
      <tr>
         <td><?php echo $campo ?>:</td>
         <td>
            <select name="id_campo" title="Campo">
<?php    
	         $consulta2 = "SELECT id_campo,description_esp, description_ing FROM campo";
	         $res2 = mysql_query($consulta2) or die($lectura_campos_incorrecta);
	
	         while($obj = mysql_fetch_object($res2))
	         {
		        echo "<option value=\"$obj->id_campo\"";
		        if($id_campo == $obj->id_campo)
				{
		         	echo " selected";
		        }
				if ($lg == "esp") // Se muestran solo la info en espanol y si esta no existe en ingles.
			    {
				  if ($obj->description_esp == '') // Se muestra el nombre en ingles
				  {
					  echo ">$obj->description_ing";
				  }
				  else // Se muestra en espanol
				  {
					  echo ">$obj->description_esp";
				  }
			    }
			    else
			    {
				  if ($obj->description_ing == '') // Se muestra el nombre en ingles
				  {
					  echo ">$obj->description_esp";
				  }
				  else // Se muestra en espanol
				  {
					  echo ">$obj->description_ing";
				  }
			    }
				//echo ">$obj->description";
	         }
?>
         </select>
      </td>
   </tr>
   <tr>
      <td><?php echo $fuente ?>:</td>
      <td>
      	<table>
		   <tr>
		      <td colspan="2">
		         <input type="hidden" name="fuente_relacionada" value="" />
		         <input name="txt_fuente_relacionada" class="obligatorio" type="text" readonly="true" size="100" title="<?php echo $fuente_relaciona ?>">
		         <input type="button" class="boton" value=" <?php echo $relacionar_fuente ?> " onclick="buscarFuente()" />
		         <input type="button" class="boton" value=" <?php echo $limpiar ?> " onclick="limpiarFuente();" />
		         
		         <input type="hidden" name="fuente_edition" value="" />
		         <input type="hidden" name="fuente_id_fuente" value="" />
		         <input type="hidden" name="fuente_h_title" value=""/>
		         <input type="hidden" name="fuente_h_author" value=""/>
		         <input type="hidden" name="fuente_pub_place" value=""/>
		         <input type="hidden" name="fuente_publisher" value=""/>
		         <input type="hidden" name="fuente_dia" value=""/>
		         <input type="hidden" name="fuente_mes" value=""/>
		         <input type="hidden" name="fuente_anyo" value=""/>
		      </td>
		   </tr>
      	</table>
<?php 
			$consulta2 = "SELECT id_fuente, h_title, edition, h_author, pub_place, publisher, pub_date FROM fuente where id_fuente='$id_fuente'";
			$res2 = mysql_query($consulta2) or die($lectura_tipos_incorrecta);

			$obj = mysql_fetch_object($res2);
			$fuente_descripcion = "ISBN: $obj->id_fuente\\nT\u00edtulo: $obj->h_title\\nTipo: $obj->edition\\nAutor: $obj->h_author\\n";
			$fuente_descripcion .= "Lugar de publicaci\u00f3n: $obj->pub_place\\nEditorial: $obj->publisher\\nFecha de publicaci\u00f3n: $obj->pub_date";
?>
		<script>
		cargarFuente ("<?php  echo $obj->id_fuente; ?>", "<?php  echo $obj->id_fuente." - ".$obj->h_title; ?>", "<?php  echo $fuente_descripcion; ?>");
		</script>
      </td>
   </tr>
   <tr>
      <td><?php echo $texto_relacionado ?>:</td>
      <td>
         <input type="hidden" name="texto_relacionado" value="<?php  echo $texto_rel; ?>" /> 
		 
<?php 		if ($texto_rel != 0)
			{
				 $consulta = "SELECT h_title FROM texto WHERE id_texto = '$texto_rel'";
				 $res = mysql_query($consulta) or die($lectura_texto_incorrecta);
				 $obj = mysql_fetch_object($res);
?>
         <input name="txt_texto_relacionado" type="text" readonly="true" size="100" title="<?php echo $texto_relaciona ?>" value="<?php  echo $obj->h_title ?>">
<?php 
			}
			else
			{
?>
		 <input name="txt_texto_relacionado" type="text" readonly="true" size="100" title="<?php echo $texto_relaciona ?>" value="">

<?php
			}
	         mysql_close($enlace);
?>
         <input type="button" class="boton" value=" <?php echo $relacionar_texto ?>" onclick="buscarTexto()" />
         <input type="button" class="boton" value=" <?php echo $limpiar ?> " onclick="document.formulario.texto_relacionado.value='';document.formulario.txt_texto_relacionado.value='';" />
      </td>
   </tr>
   <tr>
      <td align="center" colspan="2">
      	<br><input type="button" class="boton long_93 boton_aceptar" value="      <?php echo $boton_aceptar ?> " onclick="if (checkDatosModificar()) submit();"/>&nbsp;&nbsp;
      	<input type="button" class="boton long_93 boton_cancelar" value="      <?php echo $boton_cancelar ?> " onclick="document.location='menu_admin_textos.php';"/>&nbsp;&nbsp;
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
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $administrar_textos ?></a> > <a href="menu_admin_textos.php"><?php echo $textos ?></a> > <u><?php echo $modificacion_texto ?></u></td>
   </tr>
</table>
<?php 
	      } 
	
	      else
	      //-- VISUALIZACION DE TEXTO -----------------------------------------------------------------
		  if($arg_op == 'vista')  
		  {
		     $id_texto = $_GET['id_texto'];
			 $edition_stmt = $_GET['edition_stmt'];
			 $idioma = $_GET['idioma'];
?>

<p align=center><span class="titulo titulo_rojo"><?php echo $visualizar_texto ?></span><br>
<img border="0" src="../../imagenes/linea_horiz.gif" ></p>

<table border="0" width="100%">
   <tr>
      <td align="center" width="180" valign="top" bgcolor="#FFFF99">
         <p align="center"><br><span class="titulo titulo_gris"><?php echo $operaciones ?></span><br><br>
         <input type="button" class="boton boton_descarga long_150" value="      <?php echo $descargar_texto ?> " onclick="document.location='operacion_texto.php?arg_op=descargar&id_texto=<?php echo $id_texto?>';"/><br><br>
	     <input type="button" class="boton boton_volver long_150" value="     <?php echo $salir_menu_textos ?> " onclick="document.location='menu_admin_textos.php';"/><br><br>
      <td class="Leer">
<?php  
	        visualizar_texto($id_texto, $arg_op);   
?> 
      </td>
   </tr>
</table>
<br>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../../principal.php"><?php echo $menu_principal ?></a> > <a href="../menu_admin_texto.php"><?php echo $administrar_textos ?></a> > <a href="menu_admin_textos.php"><?php echo $textos ?></a> > <u><?php echo $visualizar_texto ?></u></td>
   </tr>
</table>

<?php 
	      }
?>
</body>
</html><?php 
      }
   }
   else
   {
?>

<html>

<head>
   <title></title>
   <link rel="stylesheet" type="text/css" href="../../comun/estilo.css">
   <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
   <meta content="Microsoft FrontPage 4.0" name=GENERATOR>
   
<script type="text/JavaScript">

function check_datos(data)
{    
   // Comprobar TITULO (no vacio)
   if(data.h_title.value == "") // Se chequea en el ALTA y en la MODIFICACION
   {
      alert("El campo T\u00cdTULO est\u00e1 vac\u00edo.");
	  return false;
   }

   if("$arg_op" == "nuevo" && data.fichero.value == "") // Se chequea en el ALTA y en la MODIFICACION
   {
      alert("El campo FICHERO est\u00e1 vac\u00edo. No hay ning\u00fan texto seleccionado");
	  return false;
   }

   // Si se han pasado todas las comprobaciones el formulario es valido
   return true;
}
</script>

</head>
<body>
<p class="Alerta"><img border="0" src="../../imagenes/alerta2.gif"><br><?php echo $acceso_invalido_pagina ?></p>
</body>
</html><?php 
   }
?>