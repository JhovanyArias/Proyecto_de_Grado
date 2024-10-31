<?php
$host = 'localhost';        
$dbname = "proyecto_de_grado";
$user = 'root';             
$pass = '';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
$nombre_usuario = $_SESSION['nombre_usuario'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM actividades WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM actividades");
    $stmt->execute();
    $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error en la conexión o consulta: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Interfaz de Estudiante</title>
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
    <div class="sidebar">
      <div class="profile-info">
        <div class="profile_image">
          <img src="images/boy_10539288.png" alt="">
        </div>
          <p class="info-text"><?php echo htmlspecialchars($nombre_usuario); ?></p>
      </div>
      <div class="div-logros">
          <p class="info-text">LOGROS</p>
          <div class="logros">
            <li>Logro 1</li>
            <li>Logro 2</li>
            <li>Logro 3</li>
            <li>Logro 4</li>
          </div>
      </div>
    </div>

    <div class="activity_container_student">
      <h2>Estudiante</h2>
    <div class="activity_container">
      <h2>Listado de Actividades</h2>
        <div class="containertable">
            <table class="tabla_mostrar">
                <thead>
                    <tr>
                        <th id=column_id>ID</th>
                        <th id=column_nombres>Fecha de Creacion</th>
                        <th id=column_actions>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($actividades as $actividad): ?>
                        <tr>
                            <td id="column-id"><?php echo $actividad['id']; ?></td>
                            <td id="column-fecha"><?php echo $actividad['fecha_actividad']; ?></td>
                            <td id="column-accion">
                                <form method="GET" action="./actividades/juegos.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $actividad['id']; ?>">
                                    <button type="submit" class="btn-action btn-edit">Jugar!</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
  </body>
</html>
