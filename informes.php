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

    // Consulta
    $stmt = $pdo->prepare("SELECT * FROM informes");
    $stmt->execute();
    $informes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error en la conexión o consulta: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Informes</title>
    <link rel="stylesheet" href="./style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap" rel="stylesheet" />
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
            <a class="sub-titulo" href="./acceder_profesor.php">Regresar</a>
          </li>
          <li class="list-navigation-page">
            <a class="sub-titulo" onclick="confirmarCierreSesion()" href="./logout.php">Salir</a>
          </li>
        </ul>
      </div>
    </header>
    <div class="container-table">
        <h1>Lista de Informes</h1>
        <table>
          <thead>
              <tr>
                  <th id="column_nombres">Fecha de Actividad</th>
                  <th id="column_nombres">Nombre de Estudiante</th>
                  <th id="column_nombres">Conteo</th>
                  <th id="column_nombres">Adivina la Imagen</th>
                  <th id="column_nombres">Ordena la Frase</th>
                  <th id="column_nombres">Series Lógicas</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach ($informes as $informe): ?>
                  <tr>
                      <td><?php echo htmlspecialchars($informe['fecha_actividad']); ?></td>
                      <td><?php echo htmlspecialchars($informe['nombre_estudiant']); ?></td>

                      <!-- Conteo -->
                      <td>
                          Intentos: <?php echo htmlspecialchars($informe['aciertos_conteo']); ?><br>
                          Fallos: <?php echo htmlspecialchars($informe['fallos_conteo']); ?>
                      </td>

                      <!-- Adivina la Imagen -->
                      <td>
                          Intentos: <?php echo htmlspecialchars($informe['aciertos_adivina']); ?><br>
                          Fallos: <?php echo htmlspecialchars($informe['fallos_adivina']); ?>
                      </td>

                      <!-- Ordena la Frase -->
                      <td>
                          Intentos: <?php echo htmlspecialchars($informe['aciertos_frases']); ?><br>
                          Fallos: <?php echo htmlspecialchars($informe['fallos_frases']); ?>
                      </td>

                      <!-- Series Lógicas -->
                      <td>
                          Intentos: <?php echo htmlspecialchars($informe['aciertos_series']); ?><br>
                          Fallos: <?php echo htmlspecialchars($informe['fallos_series']); ?>
                      </td>
                  </tr>
              <?php endforeach; ?>
          </tbody>
        </table>
    </div>
</body>
</html>
