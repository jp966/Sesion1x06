<?php 
include 'db.php';
session_start();

if(!isset($_SESSION['usuario'])) {
	header('Location: ./');
	die;
}

$error = '';
if(isset($_SESSION['error'])) {
  $error = $_SESSION['error'];
  unset($_SESSION['error']);
}

$usuario = $_SESSION['usuario'];

if(isset($_POST["titulo"]) && isset($_POST["texto"])){
   if($usuario["rol"] != 1) {
    header('Location: welcome.php');
    die;
  }
  $id =$_GET['id'];
  $titulo = $db->escape_string($_POST['titulo']);
  $texto = $db->escape_string($_POST['texto']);
  $result = $db->query("UPDATE noticias set titulo='$titulo', texto='$texto' where id_noticia=$id");
  if(!$result) {
    $_SESSION['error'] = $db->error;
    header('Location: crear-noticia.php');
  } else {
    header('Location: welcome.php');
  }

  die;
	
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Agregar noticia</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
	<div class="container">
    <div class="row justify-content-md-center mt-5">
      <div class="col-sm-8">
        <div class="card">
          <h4 class="card-header">Editar noticia</h4>
          <div class="card-body">
            <form method="post">
              <div class="form-group">
              <?php 
                $id= $_GET['id'];
                $result = $db->query("SELECT * from noticias where id_noticia=$id");
                $line=$result->fetch_assoc();

               ?>
                <label for="titulo">Título</label>
                <input type="text" name="titulo" class="form-control" id="titulo" value="<?php echo $line["titulo"]; ?>">
              </div>
              <div class="form-group">
                <label for="texto">Contenido</label>
                <textarea id="texto" name="texto" class="form-control" rows="5"><?php echo $line["texto"]?></textarea>
              </div>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
          </div>
        </div>
        <?php if($error) { ?>
        <div class="alert alert-danger mt-3" role="alert">
          <b>Error</b>: <?php echo $error; ?>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</body>
</html>
