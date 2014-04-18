<?php
// construye el formulario de las busquedas de textos
	if (isset($_SESSION['page'])) 
	{
		echo "<script>window.onload = function () {enviarPeticionTexto(".$_SESSION['page'].");}</script>";
	}	
?>
<script>
	var enviarPeticion = enviarPeticionTexto;

	function mostrar_texto ()
	{
		var tabla = document.getElementById("tabla_texto");
		var mensaje_tabla = document.getElementById("mensaje_tabla");
		var imagen_tabla = document.getElementById("imagen_tabla");

		if (tabla.style.display == "")
		{
			tabla.style.display = "none";
			mensaje_tabla.innerHTML = "Pulse <b>aqu&iacute</b> para mostrar las opciones de b&uacute;squeda de textos";
			imagen_tabla.src = "../imagenes/orden_asc.png";
		}
		else
		{
			tabla.style.display = "";
			mensaje_tabla.innerHTML = "Pulse <b>aqu&iacute</b> para ocultar las opciones de b&uacute;squeda de textos";
			imagen_tabla.src = "../imagenes/orden_desc.png";
		}
	}
</script>
				<table width="100%" border="0" class="mensaje">
					<tr>
						<td colspan="2" align="center" onclick="mostrar_texto();"><?php echo $mensaje60 ?> <b><i><?php echo $mensaje61 ?></i></b> <?php echo $mensaje62 ?></td>
						<!--<td align="right"><img id="imagen_tabla" border="0" src="../imagenes/orden_desc.png" /></td>-->
					</tr>
				</table><br>
				<table width="100%" border="0" id="tabla_texto">
					<tr>
						<td colspan="3" align="center"><?php  echo $texto_buscador; ?></td>
					</tr>
					<tr>
						<td><?php echo $idiom ?></td>
						<td>
							<select name="idioma" size="1" title="<?php echo $idioma ?>">
								<option value=""><?php echo $todos ?></option>
								<option value="esp"<?php  if (isset($_SESSION["language"]) == "esp") echo " selected"; ?>><?php echo $espanol ?></option>
								<option value="ing"<?php  if (isset($_SESSION["language"]) == "ing") echo " selected"; ?>><?php echo $ingles ?></option>
							</select>
						</td>
						<td rowspan="3">
							<input type="button" value="<?php echo $buscar_texto ?>" onclick="enviarPeticionTexto(1);"/>
							&nbsp;&nbsp;<img id="loadingimg" src="<?php  echo $_SESSION['application_url'];?>../imagenes/loading.gif" style="display:none" border="0" alt="Cargando"/>
						</td>
					</tr>
					<tr>
						<td><?php echo $titulo ?></td>
						<td><input type="text" name="nombre" size="40" value="<?php  if (isset($_SESSION['name'])) echo $_SESSION['name']; ?>" title="<?php echo $nomb ?>">&nbsp;&nbsp;<img src="../../imagenes/nota.gif" width="20px" height="20px" border="0" title="<?php echo $comodines ?>" ></td>
					</tr>
					<tr>
						<td><?php echo $campo ?></td>
						<td>
							<select name="campo" size="1" title="<?php echo $campo ?>">
								<option selected value=""><?php echo $todos ?></option>
<?php 
   // Dado que existen paginas que incluyen esta pagina desde una ruta que no esta dos niveles por debajo
   // la inclusion de la conexion se realizara en esas pagina y aqui se elimina.
   if (!isset($incluirconexion))
      include ("../../comun/conexion.php");

   $consulta = "SELECT id_campo,description_esp, description_ing FROM campo";
   $res = mysql_query($consulta) or die($lectura_campos_incorrecta);

   while($obj = mysql_fetch_object($res))
   {
   	  $selected = "";
   	  if (isset($_SESSION["field"]) && ($_SESSION["field"] == $obj->id_campo))
	  {
   	  	$selected = " selected";
	  }
	  if ($lg == "esp") // Se muestran solo la info en espanol y si esta no existe en ingles.
	  {
		if ($obj->description_esp == '') // Se muestra el nombre en ingles
		{
			echo "								<option value=\"$obj->id_campo\"$selected>$obj->description_ing</option>";
		}
		else // Se muestra en espanol
		{
			echo "								<option value=\"$obj->id_campo\"$selected>$obj->description_esp</option>";
		}
	  }
	  else
	  {
		if ($obj->description_ing == '') // Se muestra el nombre en ingles
		{
			echo "								<option value=\"$obj->id_campo\"$selected>$obj->description_esp</option>";
		}
		else // Se muestra en espanol
		{
			echo "								<option value=\"$obj->id_campo\"$selected>$obj->description_ing</option>";
		}
	  }
   }

?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo $tipo ?></td>
						<td>
							<select name="tipo" size="1" title="<?php echo $tipo_texto ?>">
								<option selected value=""><?php echo $todos ?></option>
<?php 

   $consulta = "SELECT id_tipo, scheme_esp, scheme_ing FROM tipo";
   $res = mysql_query($consulta) or die($lectura_tipos_incorrecta);

   while($obj = mysql_fetch_object($res))
   {
   	  $selected = "";
   	  if (isset($_SESSION["tipo"]) && ($_SESSION["tipo"] == $obj->id_tipo))
	  {
   	  	$selected = " selected";
	  }
	  if ($lg == "esp") // Se muestran solo la info en espanol y si esta no existe en ingles.
	  {
		if ($obj->scheme_esp == '') // Se muestra el nombre en ingles
		{
			echo "								<option value=\"$obj->id_tipo\"$selected>$obj->scheme_ing</option>";
		}
		else // Se muestra en espanol
		{
			echo "								<option value=\"$obj->id_tipo\"$selected>$obj->scheme_esp</option>";
		}
	  }
	  else
	  {
		if ($obj->scheme_ing == '') // Se muestra el nombre en ingles
		{
			echo "								<option value=\"$obj->id_tipo\"$selected>$obj->scheme_esp</option>";
		}
		else // Se muestra en espanol
		{
			echo "								<option value=\"$obj->id_tipo\"$selected>$obj->scheme_ing</option>";
		}
	  }
   }

   mysql_close($enlace);
?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $ano_publicacion ?>
						</td>
						<td>
							<?php echo $desde ?>&nbsp;&nbsp;<input id="desde" type="text" name="year_desde" size="4" maxlength="4" value="<?php  if (isset($_SESSION['year_desde'])) echo $_SESSION['year_desde'] ?>" title="<?php echo $ano_publicacion ?>"/>&nbsp;&nbsp;
							<?php echo $hasta ?>&nbsp;&nbsp;<input id="hasta" type="text" name="year_hasta" size="4" maxlength="4" value="<?php  if (isset($_SESSION['year_desde'])) echo $_SESSION['year_hasta'] ?>" title="<?php echo $ano_publicacion ?>"/> 
						</td>
					</tr>
					<tr>
						<td><?php echo $autor ?></td>
						<td><input type="text" name="autor" size="30" maxlength="30" value="<?php  if (isset($_SESSION['autor'])) echo $_SESSION['autor'] ?>" title="<?php echo $autor ?>"/>&nbsp;&nbsp;<img src="../../imagenes/nota.gif" width="20px" height="20px" border="0" title="<?php echo $comodines ?>"></td>
					</tr>
					<tr>
						<td><?php echo $regs_por_pag ?></td>
						<td>
							<select name="pagesize" size="1" title="<?php echo $numero_registros ?>">
								<option value="10" selected>10</option>
								<option value="15"<?php  if (isset($_SESSION['pagesize']) && $_SESSION['pagesize'] == 15) echo " selected"; ?>>15</option>
								<option value="25"<?php  if (isset($_SESSION['pagesize']) && $_SESSION['pagesize'] == 25) echo " selected"; ?>>25</option>
								<option value="50"<?php  if (isset($_SESSION['pagesize']) && $_SESSION['pagesize'] == 50) echo " selected"; ?>>50</option>
								<option value="-1"<?php  if (isset($_SESSION['pagesize']) && $_SESSION['pagesize'] == -1) echo " selected"; ?>><?php echo $todos ?></option>
							</select>
						</td>
					</tr>
<?php 
   if (tienePermisos("buscadorespecial"))
   {
?>
					<tr>
						<td><?php echo $user_nuevo_modificacion ?></td>
						<td><input type="text" name="usuario" size="15" maxlength="15" value="<?php  if (isset($_SESSION['usuario'])) echo $_SESSION['usuario'] ?>" title="<?php echo $user ?>" />&nbsp;&nbsp;<img src="../../imagenes/nota.gif" width="20px" height="20px" border="0" title="<?php echo $comodines ?>"></td>
					</tr>
<?php 
   } else {
?>
					<input type="hidden" name="usuario" value="" />
<?php 
   } 
   
?>
				</table>