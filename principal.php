<?php
	session_start();
   	if(isset($_GET['lg']))
	{	
		$lg = $_GET['lg'];
		$_SESSION['lg'] = $lg;
		include ("idioma/".$lg.".php");
	}
	else if(isset($_SESSION['lg']))
	{
		$lg = $_SESSION['lg'];
		include ("idioma/".$lg.".php");
	}

?>
<!-- 
   principal.php
   ------------------------------------------------------------------------------------------------
   P&aacute;gina principal inicial.

   Copyright (c) 10-03-2006 Ra&uacute;l BARAHONA CRESPO (raulbcneo@terra.es)
     Verbatim copying and distribution of this entire document is permitted in 
     any medium, provided this notice is preserved. 
-->

<html>

<head>
<!-- <link rel="stylesheet" type="text/css" href="estilo.css"> -->
<meta http-equiv="Content-Language" content="es">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<link rel="stylesheet" type="text/css" href="comun/estilo.css">

<!--  Reloj-->
<script type="text/javascript">

function getDigits()
{
   num=new Array("./imagenes/d0.gif","./imagenes/d1.gif","./imagenes/d2.gif","./imagenes/d3.gif","./imagenes/d4.gif","./imagenes/d5.gif","./imagenes/d6.gif","./imagenes/d7.gif","./imagenes/d8.gif","./imagenes/d9.gif")
   time=new Date()
   hour=time.getHours()

   if (hour<10)
   {
	document.getElementById('hour1').src=num[0]
	h2="'" + hour + "'"
	h2=h2.charAt(1)
	document.getElementById('hour2').src=num[h2]
	}
else
	{
	h1="'" + hour + "'"
	h1=h1.charAt(1)
	document.getElementById('hour1').src=num[h1]
	h2="'" + hour + "'"
	h2=h2.charAt(2)
	document.getElementById('hour2').src=num[h2]
	}
minute=time.getMinutes()
if (minute<10)
	{
	document.getElementById('minute1').src=num[0]
	m2="'" + minute + "'"
	m2=m2.charAt(1)
	document.getElementById('minute2').src=num[m2]
	}
else
	{
	m1="'" + minute + "'"
	m1=m1.charAt(1)
	document.getElementById('minute1').src=num[m1]
	m2="'" + minute + "'"
	m2=m2.charAt(2)
	document.getElementById('minute2').src=num[m2]
	}
}
function showTime()
{
timer=setTimeout("getDigits()",10)
interval=setInterval("getDigits()",1000)
}
function stopInterval()
{
clearTimeout(timer)
clearInterval(interval)
}
</script>

</head>

<body onload="showTime()" onunload="stopInterval()">

<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td width="20%">
      <p align="center"><img border="0" src="imagenes/php_80x40.gif" width="80" height="43"></p>
      <p align="center"><img border="0" src="imagenes/powered-by-mysql-80x40.gif" width="80" height="41"></p>
      <p align="left">&nbsp;
    </td>
    <td width="60%" align="center">
       	<table style="border:0;color:#000000;width:300;height:250;background-image:url('imagenes/bienvenida.png');background-position:center; background-repeat:no-repeat;text-align:center;">
      		<tr>
      			<td>
      				<span style="font-family:Book Antigua; font-stretch:wider;font-size:16pt;font-weight: normal"><?php echo $bienvenido ?></span><br><br>	
      				<span style="font-family:Book Antigua; font-style:italic;font-stretch:wider;font-size:37pt;font-weight: bold">Cal&iacute;ope</span><br>
      				<span style="font-family:Book Antigua; font-stretch:wider;font-size:20pt;font-weight: normal"><?php echo $titulo_medio1 ?><br><?php echo $titulo_medio2 ?></span>
      			</td>
      		</tr>
      	</table>
		
	</td>
    <td width="20%" align="center">
	  <img border="0" src="imagenes/reloj2.gif">
	  <img id="hour1" src="" alt=""/>
      <img id="hour2" src="" alt=""/>
      <img src="imagenes/dpuntos.gif"  alt=""/>
      <img id="minute1" src="" alt=""/> 
      <img id="minute2" src="" alt=""/> 
      <br>
<?php
	  if ($lg == "ing")
	  {
?>
      <script type="text/javascript">
         <!--
         dows = new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
         months = new Array("January","February","March","April","May","June","July","August","September","October","November","December");
         now = new Date();
         dow = now.getDay();
         d = now.getDate();
         m = now.getMonth();
         h = now.getTime();
         //y = now.getYear();
		 y = now.getFullYear();
         if (d == 1 || d == 31 || d == 21)
		 {
			d = d+"st";
		 }
		 else if (d == 2 || d == 22)
		 {
			d = d+"nd";
		 }
		 else
		 {
			d = d+"th";
		 }
         //document.write(dows[dow]+" "+d+" de "+months[m]+" de "+y);
		document.write(months[m]+" "+d+", "+y);
         //-->
      </script>
<?php
	  }
	  else // la pagina esta en espa�ol
	  {
?>
	    <script type="text/javascript">
         <!--
         dows = new Array("Domingo","Lunes","Martes","Mi&eacute;rcoles","Jueves","Viernes","S&aacute;bado");
         months = new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
         now = new Date();
         dow = now.getDay();
         d = now.getDate();
         m = now.getMonth();
         h = now.getTime();
         //y = now.getYear();
		 y = now.getFullYear();
         document.write(dows[dow]+" "+d+" de "+months[m]+" de "+y);
         //-->
		 </script>
<?php
	  }
?>
      <p><span class="subtitulo titulo_rojo"><a href="ayuda/ayuda_principal.php" target="_blank"><img border="0" src="imagenes/ayuda.png" width="43" height="24" /><br><?php echo $ayuda ?></a></span></p>
	</td>
  </tr>
</table>

</body>

</html>
