// realiza el cambio de un idioma a otro
	function refrescarFrames(idioma,address)
	{
        parent.encabezado.location = "encabezado.php?lg=" + idioma + "&refrescar_frame=S";
	}