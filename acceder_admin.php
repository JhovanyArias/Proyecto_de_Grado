<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
$nombre_usuario = $_SESSION['nombre_usuario'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="./style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap"
      rel="stylesheet"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  </head>
  <body>
    <header>
      <div class="header-container">
        <ul class="list-header">
          <li class="list-navigation-page">
            <a class="titulo">Aprende jugando.</a>
          </li>
          <li class="list-navigation-page">
            <a class="sub-titulo">Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?></a>
          </li>
          <li class="list-navigation-page">
            <a class="sub-titulo" onclick="confirmarCierreSesion()" href="logout.php">Salir</a>
          </li>
        </ul>
      </div>
    </header>
    <ul class="images-section-admin list">
      <li class="list-image-item">
        <div class="portfolio-card">
          <a href="new_user.php">
            <div class="card-img">
              <img
                class="img-portfolio"
                src="images/users.png"
                alt="basquetball_players"
              />
            </div>
            <div class="portfolio-description">
              <h1 class="portfolio-image-tittle">Creacion de Usuarios</h1>
            </div>
          </a>
        </div>
      </li>
      <li class="list-image-item">
        <div class="portfolio-card">
          <a href="listado_de_usuarios.php">
            <div class="card-img">
              <img
                class="img-portfolio"
                src="images/list_users.jpg"
                alt="basquetball_players"
              />
            </div>
            <div class="portfolio-description">
              <h1 class="portfolio-image-tittle">Listado de Usuarios</h1>
            </div>
          </a>
        </div>
      </li>
    </ul>
  </body>
</html>

<script>
    document.getElementById('username').textContent = '<?php echo htmlspecialchars($nombre_usuario); ?>';
    
    function confirmarCierreSesion() {
        if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
            window.location.href = 'logout.php';
        }
    }
</script>
