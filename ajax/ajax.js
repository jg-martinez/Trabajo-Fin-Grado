function GetAjaxObj(){
    var xmlhttp=false;
    try{
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    }catch(e){
        try{
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }catch(E){
            xmlhttp = false;
        }
    }

    if(!xmlhttp && typeof XMLHttpRequest!='undefined'){
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}

// Función por defecto para el tratamiento de errores.
function tratarError (contenedor, error) {
	// Por defecto se escribe el error en el objeto contenedor.
	contenedor.innerHTML = error;
}

function sendAjaxPostback (url, contenedor, postback) {
	ajax=GetAjaxObj(); 
    ajax.open("GET", url,true); 
    ajax.onreadystatechange=function() {
		if(ajax.readyState==4){
            //Sucede cuando la pagina se cargó
            if(ajax.status==200){
            	//Todo OK
                postback(contenedor, ajax.responseText);
            }else if(ajax.status==404){
                //La pagina no existe
                tratarError(contenedor, "La p\u00e1gina no existe");
            }else{
                //Mostramos el posible error
                tratarError(contenedor, "Error: " + ajax.status);
            }
        }
    }
    ajax.send(null);
}

function enviarPeticionTexto(page) {
	var form = document.formulario;
	/*var idioma = document.getElementById("idioma");
	var nombre = document.getElementById("nombre");
	var year_desde = document.getElementById("year_desde");
	var year_hasta = document.getElementById("year_hasta");*/
	var idioma = formulario.idioma.options[formulario.idioma.selectedIndex].value;
	var nombre = formulario.nombre.value;
	var year_desde = formulario.year_desde.value;
	var year_hasta = formulario.year_hasta.value;
	
	if (year_desde != "" && ("" + parseInt(year_desde) == "NaN" || parseInt(year_desde) <=0)) {
		alert ("El campo 'Desde' debe ser un n\u00famero entero positivo");
		return;
	}

	if (year_hasta != "" & ("" + parseInt(year_hasta) == "NaN" || parseInt(year_hasta) <=0)) {
		alert ("El campo 'Hasta' debe ser un n\u00famero entero positivo");
		return;
	}
	
	var orden_campo = formulario.orden_campo.value;
	var orden_sentido = formulario.orden_sentido.value;
	var usuario = formulario.usuario.value;
	var autor = formulario.autor.value;
	var pagesize = form.pagesize.options[form.pagesize.selectedIndex].value;
	var campo = form.campo.options[form.campo.selectedIndex].value;
	var tipo = form.tipo.options[form.tipo.selectedIndex].value;
	
	/*var orden_campo = document.getElementById("orden_campo");
	var orden_sentido = document.getElementById("orden_sentido");
	var usuario = document.getElementById("usuario");
	var autor = document.getElementById("autor");
	var pagesize = document.getElementById("pagesize");
	var campo = document.getElementById("campo");
	var tipo = document.getElementById("tipo");*/

	var url = "/Caliope/comun/query_texto.php?language=" + idioma + "&name=" + nombre + "&pagesize=" + pagesize + "&page=" + page;
	url += "&field=" + campo + "&tipo=" + tipo + "&inputname=aux_texto&year_desde=" + year_desde + "&year_hasta=" + year_hasta + "&autor=" + autor + "&usuario=" + usuario;
	url += "&orden_campo=" + orden_campo + "&orden_sentido=" + orden_sentido;
	sendAjaxPostback(url,document.getElementById("tempDiv"),postbackComplete);
	document.getElementById("loadingimg").style.display = "";
}

function enviarPeticionFuente(page) {
	var form = document.formulario;
	var orden_campo = formulario.orden_campo.value;
	var orden_sentido = formulario.orden_sentido.value;
	var id_fuente = formulario.id_fuente.value;
	var edition = form.edition.options[form.edition.selectedIndex].value;
	var h_title = formulario.h_title.value;
	var h_author = formulario.h_author.value;
	var pub_place = formulario.pub_place.value;
	var publisher = formulario.h_author.value;
	var pagesize = form.pagesize.options[form.pagesize.selectedIndex].value;

	var url = "/Caliope/comun/query_fuente.php?id_fuente=" + id_fuente + "&edition=" + edition + "&pagesize=" + pagesize + "&page=" + page;
	url += "&h_title=" + h_title + "&h_author=" + h_author + "&pub_place=" + pub_place + "&publisher=" + publisher;
	url += "&orden_campo=" + orden_campo + "&orden_sentido=" + orden_sentido;
	
	sendAjaxPostback(url,document.getElementById("tempDiv"),postbackComplete);
	document.getElementById("loadingimg").style.display = "";
}

function ordenarpor (campo) {
	var form = document.formulario;
	
	if (form.orden_campo.value == campo) {
		form.orden_sentido.value = (form.orden_sentido.value == "asc")?"desc":"asc";
	} else {
		form.orden_campo.value = campo;
		form.orden_sentido.value = "asc";
	}
	
	enviarPeticion(1);
}

function enviarPeticionHistorico(page) {
	var form = document.formulario;
	var accion = formulario.accion.options[formulario.accion.selectedIndex].value;
	var entidad = formulario.entidad.options[formulario.entidad.selectedIndex].value;
	var usuario = formulario.usuario.value;
	var fecha = formulario.fecha.value;
	var hora = formulario.hora.value;
	var orden_campo = formulario.orden_campo.value;
	var orden_sentido = formulario.orden_sentido.value;
	var pagesize = form.pagesize.options[form.pagesize.selectedIndex].value;
	
	if (hora != "" && hora.match(/^0[1-9]|1\d|2[0-4]:[0-5][0-9]$/) == null) {
		alert ("El campo 'Hora' no es correcto");
		return;
	}

	if (fecha != "" && fecha.match(/^(0[1-9]|1\d|2\d|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}$/) == null) {
		alert ("El campo 'Fecha' no es correcto");
		return;
	}

	if (fecha == "" && hora != "") {
		alert ("Si se rellena la fecha, se debe rellenar la hora");
		return;
	}
	
	var fecha_url = fecha;
	if (hora != "")
		fecha_url += " " + hora;
	
	var url = "/Caliope/comun/query_historico.php?accion=" + accion + "&entidad=" + entidad + "&pagesize=" + pagesize + "&page=" + page;
	url += "&usuario=" + usuario + "&fecha=" + escape(fecha_url) + "&orden_campo=" + orden_campo + "&orden_sentido=" + orden_sentido;
	
	sendAjaxPostback(url,document.getElementById("tempDiv"),postbackComplete);
	document.getElementById("loadingimg").style.display = "";
}