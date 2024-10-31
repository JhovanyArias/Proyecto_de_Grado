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
    $stmt = $pdo->prepare("SELECT * FROM usuarios");
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error en la conexión o consulta: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap" rel="stylesheet">
    <title>Lista de Usuarios</title>

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
            <a class="sub-titulo" href="acceder_profesor.php">Regresar</a>
          </li>
          <li class="list-navigation-page">
            <a class="sub-titulo" onclick="confirmarCierreSesion()" href="logout.php">Salir</a>
          </li>
        </ul>
      </div>
    </header>
    <div class="container-table">
        <h1>Lista de Usuarios asignados a <?php echo htmlspecialchars($nombre_usuario); ?></h1>
        <table>
            <thead>
                <tr>
                    <th id=column_id>ID</th>
                    <th id=column_nombres>Nombre</th>
                    <th id=column_nombres>Nombre de Usuario</th>
                    <th id=column_type>Tipo de Usuario</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo $usuario['id']; ?></td>
                        <td><?php echo ucwords(strtolower($usuario['nombre_usuario'])); ?></td>
                        <td><?php echo $usuario['nickname']; ?></td>
                        <td><?php echo ucwords(strtolower($usuario['tipo_usuario'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de edición -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Editar Usuario</h2>
            <form id="editForm" method="POST">
                <input class="formulario_input" type="hidden" id="editId" name="id">
                <label for="editNombre">Nombre Completo:</label>
                <input class="formulario_input" type="text" id="editNombre" name="nombre" required><br><br>
                <label for="editNickname">Nombre de Usuario:</label>
                <input class="formulario_input" type="text" id="editNickname" name="nickname" required><br><br>
                <label for="editPassword">Contraseña:</label>
                <input class="formulario_input" type="text" id="editPassword" name="password" required><br><br>
                <label for="editTipoUsuario">Tipo de Usuario:</label>
                <select class="formulario_input" id="editTipoUsuario" name="tipo_usuario">
                    <option value="profesor">Profesor</option>
                    <option value="estudiante">Estudiante</option>
                    <option value="padre_acudiente">Padre de familia/Acudiente</option>
                </select><br><br>
                <button type="submit" name="update">Actualizar</button>
            </form>
        </div>
    </div>

    <script>
        var modal = document.getElementById("editModal");
        var span = document.getElementsByClassName("close")[0];

        function openEditModal(usuario) {
            document.getElementById("editId").value = usuario.id;
            document.getElementById("editNombre").value = usuario.nombre_usuario;
            document.getElementById("editNickname").value = usuario.nickname;
            document.getElementById("editPassword").value = usuario.password;
            document.getElementById("editTipoUsuario").value = usuario.tipo_usuario;
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>