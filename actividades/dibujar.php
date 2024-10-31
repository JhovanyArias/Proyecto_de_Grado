<?php
$host = 'localhost';
$dbname = 'proyecto_de_grado';
$user = 'root';
$pass = '';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
$nombre_usuario = $_SESSION['nombre_usuario'];


// Conectar a la base de datos
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SaberKids</title>
    <link rel="stylesheet" href="../style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap"
      rel="stylesheet"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  </head>
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
        <a class="sub-titulo" onclick="confirmarCierreSesion()" href="../logout.php">Salir</a>
        </li>
    </ul>
    </div>
</header>

<div class="div-dibujo">
    <div class="container">
        <canvas id="canvas" width="800" height="600"></canvas>
        <div class="color-palette">
            <div class="color-option" style="background-color: black;" data-color="black"></div>
            <div class="color-option" style="background-color: red;" data-color="red"></div>
            <div class="color-option" style="background-color: blue;" data-color="blue"></div>
            <div class="color-option" style="background-color: green;" data-color="green"></div>
            <div class="color-option" style="background-color: yellow;" data-color="yellow"></div>
            <div class="color-option" style="background-color: orange;" data-color="orange"></div>
            <div class="color-option" style="background-color: purple;" data-color="purple"></div>
            <div class="color-option" style="background-color: brown;" data-color="brown"></div>
            <div class="color-option" style="background-color: pink;" data-color="pink"></div>
            <div class="color-option" style="background-color: teal;" data-color="teal"></div>
            <div class="color-option" style="background-color: olive;" data-color="olive"></div>
        </div>
        <div class="color-palette">
            <div class="color-option" style="background-color: navy;" data-color="navy"></div>
            <div class="color-option" style="background-color: maroon;" data-color="maroon"></div>
            <div class="color-option" style="background-color: lime;" data-color="lime"></div>
            <div class="color-option" style="background-color: aqua;" data-color="aqua"></div>
            <div class="color-option" style="background-color: coral;" data-color="coral"></div>
            <div class="color-option" style="background-color: silver;" data-color="silver"></div>
            <div class="color-option" style="background-color: gold;" data-color="gold"></div>
            <div class="color-option" style="background-color: indigo;" data-color="indigo"></div>
            <div class="color-option" style="background-color: violet;" data-color="violet"></div>
            <div class="color-option" style="background-color: beige;" data-color="beige"></div>
            <div class="color-option" style="background-color: turquoise;" data-color="turquoise"></div>
        </div>
    </div>

    <div class="image-display">
        <img id="selectedImage" src="" alt="Imagen ampliada" />
    </div>

    <div class="thumbnail-list">
        <img src="../images/animals/dog.png" alt="Perro" class="thumbnail" />
        <img src="../images/animals/elephant.png" alt="Perro" class="thumbnail" />
        <img src="../images/animals/giraffe.png" alt="Perro" class="thumbnail" />
        <img src="../images/animals/kangaroo.png" alt="Perro" class="thumbnail" />
        <img src="../images/animals/koala.png" alt="Perro" class="thumbnail" />
        <img src="../images/animals/lion.png" alt="Perro" class="thumbnail" />
        <img src="../images/animals/monkey.png" alt="Perro" class="thumbnail" />
        <img src="../images/animals/sloth.png" alt="Perro" class="thumbnail" />
    </div>

    <script>
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        let isDrawing = false;
        let lastX = 0;
        let lastY = 0;

        function startDrawing(e) {
            isDrawing = true;
            [lastX, lastY] = [e.offsetX, e.offsetY];
        }

        function draw(e) {
            if (!isDrawing) return;
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.stroke();
            [lastX, lastY] = [e.offsetX, e.offsetY];
        }

        function stopDrawing() {
            isDrawing = false;
        }

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);

        ctx.strokeStyle = 'black';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';

        // Cambio de color
        const colorOptions = document.querySelectorAll('.color-option');
        colorOptions.forEach(option => {
            option.addEventListener('click', function() {
                ctx.strokeStyle = this.dataset.color;
            });
        });

        // Visualización de imagen ampliada
        const selectedImage = document.getElementById('selectedImage');
        const thumbnails = document.querySelectorAll('.thumbnail');
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                selectedImage.src = this.src;
            });
        });
    </script>

</div>


</body>
</html>