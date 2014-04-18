<?php 
   session_start();header('Content-Type: text/html; charset=utf-8');ini_set("session.cookie_httponly", 1);
   include ("comun/funciones.php");
   $_SESSION['estado'] = '';
   
   $_SESSION['username'] = '';
   $_SESSION['application_url'] = "/Caliope";
   $_SESSION['idioma'] = array ("ing" => "Ingl&eacute;s","esp" => "Espa&ntilde;ol");
   
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

<!-- index2.php -----------------------------------------------------------------------------------

     Pagina de marcos de la aplicacion. Visualizacion del menu de la aplicacion.

-->

<html>

    <head>
        <title><?php echo $titulo_ppal ?></title>
        <!--<link rel="stylesheet" type="text/css" href="comun/estilo.css">-->
        <link rel="stylesheet" type="text/css" href="CSS/principal.css">
        <link href='http://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
    </head>

<?php 

   //$nombre = $_POST['nombre'];
   $nombre = evita_inyeccion($_POST['nombre']);
   $passwd = $_POST['passwd'];

   $_SESSION['username'] = $nombre;
   
   /* Consulta a la base de datos */

   include ("comun\conexion.php");

   /* Comprobamos si el usuario existe */

   $consulta = "SELECT nombre,password,privilegios FROM usuario WHERE login = '$nombre'";
   $res = mysql_query($consulta);
   if (mysql_num_rows($res) == 0)
   {
      $codigo = 'no_existe';
   }
   else
   {
      /* Comprobamos el password */

     $password = substr(sha1($passwd),0,32); //En la BD es un string de 32
     /* $password = $passwd; */

      $fila = mysql_fetch_assoc($res);
      $p = $fila["password"];
      $nombre = $fila["nombre"];

      if($password == $p)
      {
         $_SESSION['estado'] = $fila["privilegios"];;
         $codigo = $fila["privilegios"];;
      }
      else
      {
         $codigo = 'password_ko';
      }
   }


   if($codigo == 'password_ko')
   {
?>
<body>
   <div id="Error_Password">
       <div id="Pass_Message"><p><?php echo $pwd_incorrecto ?></p></div>
       <form>
       <input type="button" value="<?php echo $boton_volver ?>" onclick="document.location='index.php'";>
       </form>
       </div>
</body>

<?php 
   } 
   else if($codigo == 'no_existe')
   {
?>

<body>
	<div id="Error_User">
       <div id="User_Message"><p><?php echo $palabra_usuario ?> "<?php echo $nombre;?>" <?php echo $usuario_no_existe ?></p></div>
       <form>
       <input type="button" value="<?php echo $boton_volver ?>" onclick="document.location='index.php'";>
       </form>
    </div>
</body>
<?php 
   }
   else
   {
?>
<!--<frameset framespacing="0" border="0" rows="100,*" frameborder="0">
<frameset framespacing="0" border="0" rows="75%,*" frameborder="0">
  <frame name="encabezado" scrolling="no" noresize target="principal" src="encabezado.php" marginwidth="0" marginheight="0">
  <!--<frame name="principal" src="principal.php">
  <noframes>
  <body>

  <p><?php echo $mensaje_error ?></p>

  </body>
  </noframes>
</frameset>-->
	<iframe src="encabezado.php" style="border: 0; width: 100%; height: 100%"></iframe>
<?php 
   }
?>
</html>