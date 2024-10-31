<?php
session_start();

$host = "localhost";
$user = "root"; 
$pass = "";
$db = "proyecto_de_grado";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM usuarios WHERE nickname = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Guardar información del usuario en la sesión
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['nickname'];
        $_SESSION['nombre_usuario'] = $row['nombre_usuario'];
        $_SESSION['tipo_usuario'] = $row['tipo_usuario'];

        if ($row['tipo_usuario'] == 'profesor') {
            header("Location: acceder_profesor.php");
        } elseif ($row['tipo_usuario'] == 'estudiante') {
            header("Location: acceder_estudiante.php");
        } elseif ($row['tipo_usuario'] == 'padre_acud') {
            header("Location: acceder_padre.php");
        } elseif ($row['tipo_usuario'] == 'admin') {
            header("Location: acceder_admin.php");
        }
        exit();
    } else {
        $error_message = "Usuario o contraseña incorrectos.";
    }
    
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SaberKids</title>
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
          <li class="list-navigation-index">
            <a class="titulo">Aprende jugando.</a>
          </li>
        </ul>
      </div>
    </header>
    <div class="section-container">
    <div class="login-container">

      <h2>Iniciar Sesion</h2>
      <ul class="form-group">
        <form action="index.php" method="POST">
          <li class="form-element">
              <img class="img_login" src="images/login.jpg" alt="login">
          </li>
          <li class="form-element">
            <label for="nombre">Nombre:</label>
            <input class="formulario_input" type="text" id="username" name="username" size="3vh" required />
          </li>
          <li class="form-element">
            <label for="password">Contraseña:</label>
            <input class="formulario_input" type="password" id="password" name="password" size="3vh" required />
          </li>
          <li class="button-element">
                  <button type="submit">Iniciar sesión</button>
          </li>
        </form>
        </ul>
      </form>
    </div>
    </div>
    <div>
    </div>
  </body>
</html>