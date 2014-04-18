<?php 
// construye el formulario de las busquedas de fuentes
	if (isset($_SESSION["page"])) {
		echo "<script>window.onload = function () {enviarPeticionFuente(".$_SESSION["page"].");}</script>";
	}
?>
<script>
	var enviarPeticion = enviarPeticionFuente;

	function mostrar_fuente ()
	{
		var tabla = document.getElementById("tabla_fuente");
		var mensaje_tabla = document.getElementById("mensaje_tabla_fuente");
		var imagen_tabla = document.getElementById("imagen_tabla_fuente");

		if (tabla.style.display == "")
		{
			tabla.style.display = "none";
			mensaje_tabla.innerHTML = "<?php echo $pulse_fuente ?>";
			imagen_tabla.src = "<?php  echo $_SESSION['application_url']; ?>/imagenes/orden_asc.png";
		}
		else
		{
			tabla.style.display = "";
			mensaje_tabla.innerHTML = "<?php echo $pulse_fuente_ocultar ?>";
			imagen_tabla.src = "<?php  echo $_SESSION['application_url']; ?>/imagenes/orden_desc.png";
		}
	}
</script>
				<table width="100%" border="0" class="mensaje">
					<tr>
						<td colspan="2" align="center" onclick="mostrar_fuente();"><span class="subtitulo" id="mensaje_tabla_fuente"><?php echo $pulse ?> <b><i><?php echo $aqui ?></i></b> <?php echo $opciones_fuente ?></span></td>
						<td align="right"><img id="imagen_tabla_fuente" border="0" src="<?php  echo $_SESSION['application_url']; ?>/imagenes/orden_desc.png" /></td>
					</tr>
				</table><br>
				<table width="100%" border="0" id="tabla_fuente">
					<tr>
						<td><?php echo $tipo_texto ?></td>
						<td>
					      	<select name="edition" title="<?php echo $tipo_texto ?>">
								<option value=""><?php echo $todos ?></option>
						    	<option value="web">Web</option>
						        <option value="libro"><?php echo $libro ?></option>
								<option value="revista"><?php echo $revista ?></option>
						        <option value="otro"><?php echo $other ?></option>
							</select>
						</td>
						<td rowspan="3">
							<input type="button" class="boton" value=" <?php echo $buscar_fuente ?> " onclick="enviarPeticionFuente(1);"/>
							&nbsp;&nbsp;<img id="loadingimg" src="../../imagenes/loading.gif" style="display:none" border="0" alt="Cargando"/>
						</td>
					</tr>
				   <tr>
				      <td>ISBN/ISSN:</td><td><input name="id_fuente" size="50" title="C&oacute;digo ISBN/ISSSN"></td>
				   </tr>
				   <tr>
				      <td><?php echo $titulo ?>:</td><td><input name="h_title" size="50" title="<?php echo $nombre_titulo ?>"></td>
				   </tr>
				   <tr>
				      <td><?php echo $autor ?>:</td><td><input name="h_author" size="50" title="<?php echo $autor_creador ?>"></td>
				   </tr>
				   <tr>
				      <td><?php echo $lugar_url ?>:</td><td><input name="pub_place" size="50" title="<?php echo $lugar_publicacion ?>"></td>
				   </tr> 
				   <tr>
				      <td><?php echo $editorial ?>:</td><td><input name="publisher" size="50" title="<?php echo $editorial_creador ?>"></td>
				   </tr>
					<tr>
						<td><?php echo $regs_por_pag ?></td>
						<td>
							<select name="pagesize" size="1" title="<?php echo $numero_registros ?>">
								<option value="10" selected>10</option>
								<option value="15"<?php  if ($_SESSION["pagesize"] == 15) echo " selected"; ?>>15</option>
								<option value="25"<?php  if ($_SESSION["pagesize"] == 25) echo " selected"; ?>>25</option>
								<option value="50"<?php  if ($_SESSION["pagesize"] == 50) echo " selected"; ?>>50</option>
								<option value="-1"<?php  if ($_SESSION["pagesize"] == -1) echo " selected"; ?>><?php echo $todos ?></option>
							</selected>
						</td>
					</tr>
				</table>