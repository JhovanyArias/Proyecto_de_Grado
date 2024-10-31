<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
$nombre_usuario = $_SESSION['nombre_usuario'];

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "proyecto_de_grado";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener los usuarios
    $stmt = $conn->prepare("SELECT id, nombre_usuario FROM usuarios WHERE tipo_usuario = 'padre_acud' ORDER BY nombre_usuario");
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Procesar el formulario al enviar
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario_id = $_POST['usuario_id'];
        $mensaje = $_POST['mensaje'];

        // Validar que los campos no estén vacíos
        if (!empty($usuario_id) && !empty($mensaje)) {
            // Obtener el nombre del destinatario
            $stmt = $conn->prepare("SELECT nombre_usuario FROM usuarios WHERE id = :usuario_id");
            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->execute();
            $destinatario = $stmt->fetchColumn();

            // Inserción en la base de datos
            if ($destinatario) {
                $stmt = $conn->prepare("INSERT INTO mensajes (remitente, destinatario, mensaje) VALUES (:remitente, :destinatario, :mensaje)");
                $stmt->bindParam(':remitente', $nombre_usuario);
                $stmt->bindParam(':destinatario', $destinatario);
                $stmt->bindParam(':mensaje', $mensaje);
                if ($stmt->execute()) {
                    // echo "<p>Mensaje enviado con éxito.</p>";
                } else {
                    // echo "<p>Error al enviar el mensaje.</p>";
                }
            } else {
                echo "<p>Error: destinatario no encontrado.</p>";
            }
        } else {
            echo "<p>Por favor, completa todos los campos.</p>";
        }
    }

} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
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
    <link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet" />
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
            <a class="sub-titulo" onclick="confirmarCierreSesion()" href="../logout.php">Salir</a>
          </li>
        </ul>
      </div>
    </header>
    <div class="form-container-mensaje">
      <form action="mensajes.php" method="POST">
        <h2>Centro de Mensajes</h2>
        <ul class="form-group">
          <li class="form-element">
            <label for="nombre">Para:</label>
            <select class="tipo_usuario" name="usuario_id" id="tipo_usuario">
              <option value="">Selecciona un usuario</option>
              <?php
              foreach($usuarios as $usuario) {
                  echo "<option value='" . htmlspecialchars($usuario['id']) . "'>" . htmlspecialchars($usuario['nombre_usuario']) . "</option>";
              }
              ?>
            </select>
          </li>
          <li class="form-element">
            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" name="mensaje" rows="8" cols="45"></textarea>
          </li>
          <li class="button-element">
            <button class="action-button" type="submit">Enviar</button>
          </li>
          <li class="button-element">
            <a href="acceder_profesor.php">Regresar</a>
          </li>
        </ul>
      </form>
    </div>
  </body>
</html>
