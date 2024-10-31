<?php
$host = "localhost";
$user = "root"; 
$pass = "";
$db = "proyecto_de_grado";

// Crear conexión usando PDO
try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

$nombre_usuario = $_SESSION['nombre_usuario'];
$message = '';
$success = false;

try {
    // consulta para obtener los nombres de usuarios con el rol 'estudiante'
    $stmt = $conn->prepare("SELECT nombre_usuario FROM usuarios WHERE tipo_usuario = 'estudiante'");
    $stmt->execute();
    $usuarios_estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // consulta para obtener los nombres de usuarios con el rol 'padre_acud'
    $stmt = $conn->prepare("SELECT nombre_usuario FROM usuarios WHERE tipo_usuario = 'padre_acud'");
    $stmt->execute();
    $usuarios_papa = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $username = $_POST['username'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $password = $_POST['password'];
    $tipo_usuario = $_POST['tipo_usuario'];
    
    // Asignar un valor de N/A a $relacion si el rol es "profesor"
    $relacion = ($tipo_usuario === "profesor") ? "N/A" : $_POST['relacion'];

    // Validar que los campos no estén vacíos
    if (empty($username) || empty($nombre_usuario) || empty($password) || empty($tipo_usuario) || ($tipo_usuario !== "profesor" && empty($relacion))) {
        $message = "Todos los campos son obligatorios.";
    } else {
        // Insertar el nuevo usuario en la base de datos
        $sql = "INSERT INTO usuarios (nombre_usuario, nombre_usuario, password, tipo_usuario, relacion_user) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        // Contraseña encriptada para futuras actualizaciones de seguridad
        // $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        if ($stmt->execute([$username, $nombre_usuario, $password, $tipo_usuario, $relacion])) {
            $success = true;
            $message = "Usuario creado exitosamente.";
        } else {
            $message = "Error al crear el usuario.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Creacion de Usuarios</title>
    <link rel="stylesheet" href="./style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap"
      rel="stylesheet"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Permanent+Marker&family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap"
      rel="stylesheet"
    />
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
            <a class="sub-titulo" href="acceder_admin.php">Regresar</a>
          </li>
          <li class="list-navigation-page">
            <a class="sub-titulo" onclick="confirmarCierreSesion()" href="logout.php">Salir</a>
          </li>
        </ul>
      </div>
    </header>
    <div class="section-container">
      <div>
        <img src="images/teacher1.png" height="700vh" alt="teacher1" />
      </div>
      <div class="form-container">
        <h2>Creación de Usuario</h2>
        <form action="new_user.php" method="POST">
          <ul class="form-group">
            <li class="form-element">
              <label for="nombre">Nombre:</label>
              <input class="formulario_input" type="text" id="username" name="username" required />
            </li>
            <li class="form-element">
              <label for="nombre">Nombre de Usuario:</label>
              <input class="formulario_input" type="text" id="nombre_usuario" name="nombre_usuario" required />
            </li>
            <li class="form-element">
              <label for="password">Contraseña:</label>
              <input class="formulario_input" type="password" id="password" name="password" required />
            </li>
            <li class="form-element">
              <label for="rol">Selecciona el Rol:</label>
              <select class="tipo_usuario" name="tipo_usuario" id="tipo_usuario" onchange="toggleInput()">
                <option value="profesor">Profesor</option>
                <option value="estudiante">Estudiante</option>
                <option value="padre_acud">
                  Padre de familia/Acudiente
                </option>
              </select>
            </li>
            <li class="form-element">
              <label for="rol">Relacion:</label>
              <select class="tipo_usuario" name="relacion" id="relacion" disabled>
                <option value="">Seleccione un estudiante o padre</option>
              </select>
            </li>
            <li class="button-element">
              <button class="action-button" type="submit">
                Crear
              </button>
            </li>
          </ul>
        </form>
      </div>
      <div>
        <img src="images/teacher2.png" height="700vh" alt="teacher2" />
      </div>
    </div>
  </body>
</html>

<!-- Modal de éxito -->
<div class="modal" id="successModal" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Usuario Creado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Usuario creado exitosamente.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de error -->
<div class="modal" id="errorModal" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Error al crear el usuario: <?php echo isset($errorMessage) ? $errorMessage : ''; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-close" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Función para mostrar un modal
    function showModal(modalId) {
        var modal = document.getElementById(modalId);
        modal.style.display = "block";
    }

    // Función para cerrar un modal
    function closeModal(modal) {
        modal.style.display = "none";
    }

    // Cerrar el modal al hacer clic en el botón con clase btn-close o btn btn-primary
    var modals = document.getElementsByClassName("modal");
    for (var i = 0; i < modals.length; i++) {
        var modal = modals[i];
        var closeButton = modal.querySelector(".btn-close");
        var acceptButton = modal.querySelector(".btn.btn-primary");

        closeButton.onclick = function() {
            closeModal(this.closest(".modal"));
        }

        acceptButton.onclick = function() {
            closeModal(this.closest(".modal"));
        }
    }

    // Cerrar el modal al hacer clic fuera de él
    window.onclick = function(event) {
        if (event.target.className === "modal") {
            closeModal(event.target);
        }
    }

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($success) {
            echo "showModal('successModal');";
        } elseif (!empty($errorMessage)) {
            echo "document.getElementById('errorMessage').textContent = '" . addslashes($errorMessage) . "';";
            echo "showModal('errorModal');";
        }
    }
    ?>
</script>

<script>
    const usuariosEstudiantes = <?php echo json_encode($usuarios_estudiantes); ?>;
    const usuariosPapa = <?php echo json_encode($usuarios_papa); ?>;

    function toggleInput() {
        const tipo_usuario = document.getElementById("tipo_usuario").value;
        const relacion = document.getElementById("relacion");

        // Limpiar opciones previas
        relacion.innerHTML = '<option value="">Seleccione un estudiante o padre</option>';

        if (tipo_usuario === "estudiante") {
            usuariosPapa.forEach(usuario => {
                const option = document.createElement("option");
                option.value = usuario.nombre_usuario;
                option.textContent = usuario.nombre_usuario;
                relacion.appendChild(option);
            });
            relacion.disabled = false;
        } else if (tipo_usuario === "padre_acud") {
            usuariosEstudiantes.forEach(usuario => {
                const option = document.createElement("option");
                option.value = usuario.nombre_usuario;
                option.textContent = usuario.nombre_usuario;
                relacion.appendChild(option);
            });
            relacion.disabled = false;
        } else {
            relacion.disabled = true;
        }
    }
</script>

</body>
</html>


