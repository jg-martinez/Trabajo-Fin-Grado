<?php 
	session_start();header('Content-Type: text/html; charset=utf-8');ini_set("session.cookie_httponly", 1);

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
<html>
<!-- 
   vista_listado.php
   ------------------------------------------------------------------------------------------------
   Vista del listado de historico.
-->
<head>
   <title>Calíope</title>
   <link rel="stylesheet" type="text/css" href="../CSS/vista_listado.css">
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta content="Microsoft FrontPage 4.0" name=GENERATOR>
   <link href='http://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
   <script languaje="javascript" src="../ajax/ajax.js"></script>
   <script>
    var enviarPeticion = enviarPeticionHistorico;

    function mostrar_texto ()
    {
        var tabla = document.getElementById("tabla_texto");
        var mensaje_tabla = document.getElementById("mensaje_tabla");
        var imagen_tabla = document.getElementById("imagen_tabla");

        if (tabla.style.display == "")
        {
            tabla.style.display = "none";
            mensaje_tabla.innerHTML = "<?php echo $pulse_historico ?>";
            imagen_tabla.src = "<?php  echo $_SESSION['application_url']; ?>/imagenes/orden_asc.png";
        }
        else
        {
            tabla.style.display = "";
            mensaje_tabla.innerHTML = "<?php echo $pulse_historico_ocultar ?>";
            imagen_tabla.src = "<?php  echo $_SESSION['application_url']; ?>/imagenes/orden_desc.png";
        }
    }

    // Funcion encargada de eliminar los checks seleccionados.

    function eliminarSeleccionados() {
        var encontrado = false;
        var miform = document.formulario;
        var contador = 0;

        while (contador < miform["historico[]"].length && ! encontrado) {
            encontrado = encontrado || miform["historico[]"][contador].checked;
            contador++;
		}

        if (encontrado)
            miform.submit();
        else
            alert ("<?php echo $necesario_seleccionar ?>");
    }

    // Funcion encargada de recoger la respuesta de la invocacion via Ajax y de dibujar la tabla.

    function postbackComplete( contenedor, respuesta) {

		document.getElementById("loadingimg").style.display = "none";
	
        eval(respuesta);

        var iniciotabla = "<table border='0' cellpadding='4' cellspacing='1' bgcolor='#CC0000'>";
        var fintabla = "</table>";
        var textocabecera = "";
        var textoseparador = "";
        var img_html = "&nbsp;<img src='../imagenes/orden_" + document.formulario.orden_sentido.value + ".png' border='0' />";
        var textotitulo = "<tr bgcolor='#D8D9A4'><td>&nbsp;</td>";
            textotitulo += "<td><b><a href='#' onclick='ordenarpor(\"accion\")'><?php echo $accion ?></a></b>";         if (document.formulario.orden_campo.value == 'accion') textotitulo += img_html;         textotitulo += "</td>";         textotitulo += "<td><b><a href='#' onclick='ordenarpor(\"entidad\")'><?php echo $entidad ?></a></b>";          if (document.formulario.orden_campo.value == 'entidad') textotitulo += img_html;            textotitulo += "</td>";         textotitulo += "<td><b><a href='#' onclick='ordenarpor(\"usuario\")'><?php echo $user ?></a></b>";          if (document.formulario.orden_campo.value == 'usuario') textotitulo += img_html;            textotitulo += "</td>";         textotitulo += "<td><b><a href='#' ><?php echo $datos ?></a></b>";         if (document.formulario.orden_campo.value == 'datos') textotitulo += img_html;          textotitulo += "</td>";         textotitulo += "<td><b><a href='#' onclick='ordenarpor(\"fecha\")'><?php echo $date ?></a></b>";          if (document.formulario.orden_campo.value == 'fecha') textotitulo += img_html;          textotitulo += "</td>";     textotitulo += "</tr>";
        var textotabla = "";

        if (historicoarray.length == 0) {
            textotabla = "<tr bgcolor='#FFFF99'><td colspan='6' align='center'><?php echo $no_registros ?></td></tr>";
        } else {
            textocabecera = "<tr bgcolor='#FFFFFF'><td colspan='6'>";
            textocabecera += "<table border='0' width='100%'><tr><td align='left'><?php echo $pagina ?> " + paginaactual + " <?php echo $de ?> " + maxpaginas + " (" + registrosencontrados + " <?php echo $registros_encontrados ?>)</td>";
            textocabecera += "<td align='right'>";

            if (paginaactual > 1) {
                textocabecera += "<a href='javascript:enviarPeticionHistorico(1)'><img border='0' src='../imagenes/separador1.gif' alt='Primera p&aacute;gina' /></a>";
                textocabecera += "<a href='javascript:enviarPeticionHistorico(" + (paginaactual - 1) + ")'><img border='0' src='../imagenes/bullet2.gif' alt='P&aacute;gina anterior' /></a>&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            if (paginaactual < maxpaginas) {
                textocabecera += "<a href='javascript:enviarPeticionHistorico(" + (paginaactual + 1) + ")'><img border='0' src='../imagenes/bullet21.gif' alt='Pr&oacute;xima p&aacute;gina' /></a>";
                textocabecera += "<a href='javascript:enviarPeticionHistorico(" + maxpaginas + ")'><img border='0' src='../imagenes/separador.gif' alt='&Uacute;ltima p&aacute;gina' /></a>";
            }

            textocabecera += "</td></tr></table></td></tr>";

            var textotitulo2 = "<tr bgcolor='#FFFFFF'><td width='5%'><input type='checkbox' name='auxallcheck' onclick='for (var i=0;i<document.forms[0][\"historico[]\"].length;i++) document.forms[0][\"historico[]\"][i].checked=true;'></td>"
            textotitulo2 += "<td colspan='4' color='#000000'> <?php echo $todos ?></td><td><input type='button' class='boton' value='<?php echo $borrar_seleccionados ?>' onclick='eliminarSeleccionados();'></td></tr>";
            textotitulo = textotitulo2 + textotitulo;

            for (var contador = 0 ; contador < historicoarray.length; contador ++) {
                if (contador%2==0)
                    textotabla += "<tr  bgcolor='#FFFFFF'>";
                else
                    textotabla += "<tr bgcolor='#FFFF99'>";
 
				textotabla += "<td width='5%'><input type='checkbox' name='historico[]' value='" + historicoarray[contador][0] + "'></td>";
                textotabla += "<td>" + historicoarray[contador][1] + " </td>";
                textotabla += "<td>" + historicoarray[contador][2] + " </td>";
                textotabla += "<td>" + historicoarray[contador][3] + " </td>";
                textotabla += "<td>" + historicoarray[contador][5] + " </td>";
                textotabla += "<td>" + historicoarray[contador][4] + " </td>";
                textotabla += "</tr>";
            }
        }
        contenedor.innerHTML = iniciotabla + textocabecera + textoseparador + textotitulo + textotabla + textoseparador + textocabecera + fintabla;
    }
    </script>
</head>

<body>
<form action="eliminar_historico.php" name="formulario" method="post">
<input type="hidden" name="orden_campo" value="id" />
<input type="hidden" name="orden_sentido" value="asc" />

<p align="center">
<header>
	<h1><?php echo $administracion_historico ?></h1>
</header>
<table border="0" width="100%">
   <tr>
      <!--td align="center" width="150" valign="top" bgcolor="#FFFF99" class="conborde">
         <p align="center"><br>
         <span class="titulo titulo_gris"><?php echo $operaciones ?></span><br><br>
            <input type="button" class="boton boton_volver long_160" value="      <?php echo $salir_principal ?> " onclick="document.location='../principal.php'" /><br><br>
         </p>
      </td>
      <td>-->
        <table width="100%" border="0">
        <tr>
            <td width="20%">&nbsp;</td>
            <td align="center" width="60%" class="buscador">

			<table width="100%" border="0" class="mensaje">
                <tr>
                    <td colspan="2" align="center" onclick="mostrar_texto();"><span class="subtitulo" id="mensaje_tabla"><?php echo $pulse ?> <b><i><?php echo $aqui ?></i></b> <?php echo $opciones_historico ?></span></td>
                    <!--<td align="right"><img id="imagen_tabla" border="0" src="<?php  echo $_SESSION['application_url']; ?>/imagenes/orden_desc.png" /></td>-->
                </tr>
             </table><br>

             <table width="100%" border="0" id="tabla_texto" align="center">
                <tr>
                    <td colspan="3" align="center">
                        <?php echo $seleccione_pars_historico ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $accion ?></td>
                    <td>
                        <select name="accion" size="1" title="<?php echo $accion ?>">
                            <option selected value=""><?php echo $todas ?></option>
                            <option value="alta"><?php echo $alta ?></option>
                            <option value="modificar"><?php echo $modificar ?></option>
                            <option value="eliminar"><?php echo $elim ?></option>
                        </select>
                    </td>
                    <!--<td rowspan="3">
                        <input type="button" class="boton" value=" <?php echo $buscar ?> " onclick="enviarPeticionHistorico(1);"/>
                        &nbsp;&nbsp;<img id="loadingimg" src="../imagenes/loading.gif" style="display:none" border="0" alt="Cargando"/>
                    </td>-->
                </tr>
                <tr>
                    <td><?php echo $user ?></td>
                    <td>
                        <input type="text" name="usuario" size="15" maxlength="15" value="" title="<?php echo $nombre_usuario ?>">&nbsp;&nbsp;
                        <img src="../imagenes/nota.gif" width="20px" height="20px" border="0" title="<?php echo $comodines ?>" >
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entidad ?></td>
                    <td>
                        <select name="entidad" size="1" title="<?php echo $entidad ?>">
                            <option selected value=""><?php echo $todos ?></option>
                            <option value="usuario"><?php echo $user ?></option>
                            <option value="tipo"><?php echo $tipo_texto ?></option>
                            <option value="campo"><?php echo $campo ?></option>
                            <option value="fuente"><?php echo $fuente ?></option>
                            <option value="texto"><?php echo $titulo_texto ?></option>
                            <option value="termino"><?php echo $term ?></option>
                            <option value="tipo relacion"><?php echo $tipo_de_relacion ?></option>
                            <option value="relacion"><?php echo $rel ?></option>
                            <option value="contexto"><?php echo $conexts ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php echo $fecha ?>
                    </td>
                    <td>
                        <input type="date" name="fecha" size="10" maxlength="10" value="" title="<?php echo $ano_publicacion ?>"/>
						</td>
						</tr>
						<tr>
						<td>
                        <?php echo $hora ?>
						</td>
						<td>
						<input type="time" name="hora" size="5" maxlength="5" value="" title="<?php echo $hora ?>"/> 
                    </td>
                </tr>
                <tr>
                    <td><?php echo $regs_por_pag ?></td>
                    <td>
                        <select name="pagesize" size="1" title="<?php echo $numero_registros ?>">
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="-1"><?php echo $todos ?></option>
                        </select>
                    </td>
                </tr>
             </table>
            </td>
            <td width="20%">&nbsp;</td>
           </tr>
           <tr>
              <td align="center" colspan="3">
                <table width="100%" border="0">
                    <tr><td id="tempDiv" align="center"></td></tr>
                </table>
              </td>
           </tr>
       </table>
      </td>
	  <table align="center">
	  <tr>
	  <td rowspan="3" align="center">
        <input type="button" class="boton" value=" <?php echo $buscar ?> " onclick="enviarPeticionHistorico(1);"/>
        &nbsp;&nbsp;<img id="loadingimg" src="../imagenes/loading.gif" style="display:none" border="0" alt="Cargando"/>
      </td>
	  </tr>
	  </td>
   </tr>
</table>

<br>

<!--<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
      <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a>   >   <u><?php echo $administrar_historico ?></u></td>
   </tr>
</table>-->
</form>
</body>
</html>