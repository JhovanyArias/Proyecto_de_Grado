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

// Verificar si el ID ya está en la sesión
if (isset($_GET['id'])) {
    // Si no hay un ID en la sesión, asignar el ID de la URL
    if (!isset($_SESSION['actividad_id'])) {
        $_SESSION['actividad_id'] = intval($_GET['id']); // Guardar el ID en la sesión
    }
}
$actividad_id = $_SESSION['actividad_id'];

// Inicializar contadores
if (!isset($_SESSION['intentosConteo'])) {
    $_SESSION['intentosConteo'] = 0; // Iniciar en 0 si no está establecido
    $_SESSION['erroresConteo'] = 0; // Iniciar en 0 si no está establecido
}
if (!isset($_SESSION['intentosAdivina'])) {
    $_SESSION['intentosAdivina'] = 0; // Iniciar en 0 si no está establecido
    $_SESSION['erroresAdivina'] = 0; // Iniciar en 0 si no está establecido
}
if (!isset($_SESSION['intentosFrases'])) {
    $_SESSION['intentosFrases'] = 0;
    $_SESSION['erroresFrases'] = 0;
}
if (!isset($_SESSION['intentosSeries'])) {
    $_SESSION['intentosSeries'] = 0;
    $_SESSION['erroresSeries'] = 0;
}


// Informe
  if (isset($_POST['submit'])) {
      $conn = new mysqli('localhost', 'root', '', 'proyecto_de_grado');
      if ($conn->connect_error) {
          die("Conexión fallida: " . $conn->connect_error);
      }
    $nombre_usuario = $_SESSION['nombre_usuario'];
    $aciertos_conteo = $_SESSION['intentosConteo'];
    $fallos_conteo = $_SESSION['erroresConteo'];
    $aciertos_adivina = $_SESSION['intentosAdivina'];
    $fallos_adivina = $_SESSION['erroresAdivina'];
    $aciertos_frases = $_SESSION['intentosFrases'];
    $fallos_frases = $_SESSION['erroresFrases'];
    $aciertos_series = $_SESSION['intentosSeries'];
    $fallos_series = $_SESSION['erroresSeries'];


      // Insertar en la base de datos
      $stmt = $conn->prepare("INSERT INTO informes (fecha_actividad, nombre_estudiant, aciertos_conteo, fallos_conteo, aciertos_adivina, fallos_adivina, aciertos_frases, fallos_frases, aciertos_series, fallos_series) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      
      if (!$stmt) {
          echo "Error en la preparación del statement: " . $conn->error;
          exit();
      }

      $stmt->bind_param('sssssssss', $nombre_usuario, $aciertos_conteo, $fallos_conteo, $aciertos_adivina, $fallos_adivina, $aciertos_frases, $fallos_frases, $aciertos_series, $fallos_series);

      if ($stmt->execute()) {
          echo "<p>Imágenes y respuestas guardadas correctamente.</p>";
      } else {
          echo "<p>Error al guardar los datos: " . $stmt->error . "</p>";
      }
      $stmt->close();
  }


// Conectar a la base de datos
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['selected_answersConteo'])) {
        $_SESSION['intentosConteo']++;
        $selected_answersConteo = json_decode($_POST['selected_answersConteo'], true);
        $id = intval($_GET['id']);
        $conn = new mysqli('localhost', 'root', '', 'proyecto_de_grado');
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT * FROM actividades WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $correct_answers = unserialize($row['correcto_conteo']);
            $all_correct = true;

            foreach ($selected_answersConteo as $key => $answer) {
                if ($answer != $correct_answers[$key]) {
                    $all_correct = false;
                    break;
                }
            }

            if ($all_correct) {
                $_SESSION['respuestaCorrectaConteo'] = true; // Asignar true si la respuesta es correcta
            } else {
                $_SESSION['erroresConteo']++;
                $_SESSION['respuestaCorrectaConteo'] = false; // Asignar false si la respuesta es incorrecta
            }
        }

        // $conn->close();
    }

    if (isset($_POST['selected_answersAdivina'])) {
        $_SESSION['intentosAdivina']++;
        $selected_answersAdivina = json_decode($_POST['selected_answersAdivina'], true);
        $id = intval($_GET['id']);
        $conn = new mysqli('localhost', 'root', '', 'proyecto_de_grado');
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT correcto_adivinar FROM actividades WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $correct_answers = unserialize($row['correcto_adivinar']);
            $all_correct = true;
            foreach ($selected_answersAdivina as $key => $answer) {
                if ($answer != $correct_answers[$key]) {
                    $all_correct = false;
                    break;
                }
            }
            if ($all_correct) {
                $_SESSION['respuestaCorrectaAdivina'] = true; // Asignar true si la respuesta es correcta
            } else {
                $_SESSION['erroresAdivina']++;
                $_SESSION['respuestaCorrectaAdivina'] = false; // Asignar false si la respuesta es incorrecta
            }
        }

        // $conn->close();
    }

    if (isset($_POST['selected_answersFrases'])) {
        $_SESSION['intentosFrases']++;
        $selectedAnswersFrases = json_decode($_POST['selected_answersFrases'], true);
        $id = intval($_GET['id']);
        
        // Convertir las respuestas seleccionadas en una cadena
        if (is_array($selectedAnswersFrases)) {
            $respuestaUsuario = implode(" ", $selectedAnswersFrases);
        } else {
            $respuestaUsuario = "";
            echo "Error: las respuestas seleccionadas no están en el formato esperado.";
        }

        // Conectar a la base de datos
        $conn = new mysqli($host, $user, $pass, $dbname);
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Obtener la respuesta correcta desde la base de datos
        $stmt = $conn->prepare("SELECT correcto_ordenar FROM actividades WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                $correcto_array = unserialize($row['correcto_ordenar']);
            }
        } else {
            echo "No se encontró la actividad con ID: " . $id;
        }

        if (isset($correcto_array) && is_array($correcto_array)) {
            $respuestaCorrectaFrases = implode(" ", $correcto_array);
            if ($respuestaUsuario === $respuestaCorrectaFrases) {
                $_SESSION['respuestaCorrectaFrases'] = true; // Indicar que la respuesta es correcta
                $mensaje = "Respuesta correcta";
            } else {
                $_SESSION['respuestaCorrectaFrases'] = false;
                $_SESSION['erroresFrases']++;
            }
        } else {
            echo "Error al deserializar la respuesta correcta.";
        }

        // Cerrar la conexión a la base de datos
        $stmt->close();
        // $conn->close();
    }

    if (isset($_POST['selected_image'])) {
        $selectedImage = $_POST['selected_image'];
        $id = intval($_POST['id']);

        $stmt = $conn->prepare("SELECT correcto_series FROM actividades WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $_SESSION['intentosSeries']++;

        $response = ''; // Inicializa la variable de respuesta
        $respuestaCorrecta = false; // Inicializa la variable para el estado de la respuesta

        if ($row = $result->fetch_assoc()) {
            $correct_answers = unserialize($row['correcto_series']);

            // Comparar la imagen seleccionada con la respuesta correcta
            if (in_array($selectedImage, $correct_answers)) {
                $response = "¡Respuesta correcta!";
                $respuestaCorrecta = true; // La respuesta es correcta
            } else {
                $response = "Respuesta incorrecta. Intenta de nuevo.";
                $_SESSION['erroresSeries']++;
            }
        }
        $stmt->close();

        // Retornar los mensajes y los contadores en formato JSON
        echo json_encode([
            'mensaje' => $response,
            'intentosSeries' => $_SESSION['intentosSeries'],
            'erroresSeries' => $_SESSION['erroresSeries'],
            'correctaSeries' => $respuestaCorrecta // Añade el campo correcta
        ]);
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap" rel="stylesheet">
    <title>Ver Actividad</title>
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

<div id="div-conteo" class="mostrar_actividad">
    <h2>Conteo</h2>
    
    <!-- Mostrar contadores -->
    <div class="contador" style="display: none;">
        <p>Intentos: <?php echo $_SESSION['intentosConteo']; ?></p>
        <p>Errores: <?php echo $_SESSION['erroresConteo']; ?></p>
    </div>
    
    <form method="POST" action="">
        <table class="tabla_juegos">
            <tr>
                <th>Imagen 1</th>
                <th>Imagen 2</th>
                <th>Imagen 3</th>
                <th>Imagen 4</th>
            </tr>
            <?php
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                
                $conn = new mysqli('localhost', 'root', '', 'proyecto_de_grado');
                if ($conn->connect_error) {
                    die("Conexión fallida: " . $conn->connect_error);
                }

                $stmt = $conn->prepare("SELECT * FROM actividades WHERE id = ?");
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    echo "<tr>";

                    $images = unserialize($row['images_conteo']);
                    $respuestas_desempaquetadas = unserialize($row['answers_conteo']);

                    if ($images !== false && is_array($images) && $respuestas_desempaquetadas !== false && is_array($respuestas_desempaquetadas)) {
                        foreach ($images as $key => $image) {
                            echo "<td><img src='data:image/jpeg;base64," . base64_encode($image) . "' alt='Imagen guardada' style='width: 200px; height: auto;'><br>";

                            foreach ($respuestas_desempaquetadas[$key] as $respuesta) {
                                echo "<div class='clickable-answer clickable-answer1' data-group='$key' style='border: 1px solid #000; padding: 5px;'>" . htmlspecialchars($respuesta) . "</div>";
                            }

                            echo "</td>";
                        }
                    } else {
                        echo "<td colspan='4'>Error al cargar las imágenes o respuestas.</td>";
                    }

                    echo "</tr>";
                } else {
                    echo "<tr><td colspan='4'>No se encontró la actividad.</td></tr>";
                }

                // $conn->close();
            }
            ?>
        </table>

        <input type="hidden" id="selected_answersConteo" name="selected_answersConteo" />
        <div class="div-btn_validar">
            <button type="submit" class="btn_validar" id="submitBtnConteo" disabled>Adivinar</button>
        </div>
    </form>
</div>

<div id="div-adivina" class="mostrar_actividad">
    <h2>Adivina la Imagen</h2>
    <div class="contador" style="display: none;">
        <p>Intentos: <?php echo $_SESSION['intentosAdivina']; ?></p>
        <p>Errores: <?php echo $_SESSION['erroresAdivina']; ?></p>
    </div>
    <form method="POST" action="">
    <table>
        <tr>
            <th>Imagen 1</th>
            <th>Imagen 2</th>
            <th>Imagen 3</th>
            <th>Imagen 4</th>
        </tr>
        <?php
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']); // Asegúrate de que el ID es un número entero
            
            // Conexión a la base de datos
            $conn = new mysqli('localhost', 'root', '', 'proyecto_de_grado');
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }
            // Recuperar los datos de la actividad específica, incluyendo el campo respuesta_serializada
            $stmt = $conn->prepare("SELECT * FROM actividades WHERE id = ?");
            $stmt->bind_param('i', $id); // Usar el ID de la actividad
            $stmt->execute();
            $result = $stmt->get_result();
            // Procesar la fila de la consulta
            if ($row = $result->fetch_assoc()) {
                echo "<tr>";

                // Deserializar las imágenes y las respuestas
                $images = unserialize($row['images_adivinar']);
                $respuestas_desempaquetadas = unserialize($row['answers_adivinar']);

                if ($images !== false && is_array($images) && $respuestas_desempaquetadas !== false && is_array($respuestas_desempaquetadas)) {
                    foreach ($images as $key => $image) {
                        echo "<td><img src='data:image/jpeg;base64," . base64_encode($image) . "' alt='Imagen guardada' style='width: 200px; height: auto;'><br>";

                        // Mostrar las respuestas correspondientes
                        foreach ($respuestas_desempaquetadas[$key] as $respuesta) {
                            echo "<div class='clickable-answer clickable-answer2' data-group='$key' style='border: 1px solid #000; padding: 5px;'>" . htmlspecialchars($respuesta) . "</div>"; // Mostrar cada respuesta
                        }
                    }
                } else {
                    echo "<td colspan='4'>Error al cargar las imágenes o respuestas.</td>";
                }
                echo "</tr>";
            } else {
                echo "<tr><td colspan='4'>No se encontró la actividad.</td></tr>";
            }

            // $conn->close();
        }
        ?>
    </table>
    
        <input type="hidden" id="selected_answersAdivina" name="selected_answersAdivina" />
        <div class="div-btn_validar">
            <button type="submit" class="btn_validar" id="submitBtnAdivina" disabled>Adivinar</button>
        </div>
    </form>
</div>

<div id="div-frases" class="mostrar_actividad">
    <h2>Ordena la Frase</h2>

    <div class="contador" style="display: none;">
        <p>Intentos: <?php echo $_SESSION['intentosFrases']; ?></p>
        <p>Errores: <?php echo $_SESSION['erroresFrases']; ?></p>
    </div>
    <form method="POST" action="">
        <table class="tabla_mostrar">
            <?php
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $conn = new mysqli($host, $user, $pass, $dbname);
                if ($conn->connect_error) {
                    die("Conexión fallida: " . $conn->connect_error);
                }

                $stmt = $conn->prepare("SELECT * FROM actividades WHERE id = ?");
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    $frasesDesordenadas = unserialize($row['frase_ordenar']);

                    if ($frasesDesordenadas !== false && is_array($frasesDesordenadas)) {
                        foreach ($frasesDesordenadas as $group => $fraseDesordenada) {
                            $palabras = explode(' ', htmlspecialchars($fraseDesordenada));
                            echo "<tr>";
                            foreach ($palabras as $palabra) {
                                echo "<td class='clickable-answer clickable-answer3' data-group='$group' style='border: 1px solid #000; padding: 5px;'>$palabra</td>";
                            }
                            echo "</tr><tr>";
                            foreach ($palabras as $palabra) {
                                echo "<td><div class='empty-slot' style='width: 40px; height: 20px;'></div></td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Error al cargar las frases.</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No se encontró la actividad.</td></tr>";
                }
            }
            ?>
        </table>
        <input type="hidden" id="selected_answersFrases" name="selected_answersFrases" />
        <div class="div-btn_validar">
            <button type="submit" class="btn_validar" id="submitBtnFrases" disabled>Adivinar</button>
        </div>
    </form>
</div>

<div id="div-series" class="mostrar_actividad">
    <h2>Series Lógicas</h2>
        <div class="contadorSeries" style="display: none;">
            <p>Intentos: <?php echo $_SESSION['intentosSeries']; ?></p>
            <p>Errores: <?php echo $_SESSION['erroresSeries']; ?></p>
        </div>
    <form id="actividad-form">
        <input type="hidden" id="actividad-id" value="<?php echo isset($_SESSION['actividad_id']) ? $_SESSION['actividad_id'] : ''; ?>">

        <table>
            <tr>
                <th>Imagen 1</th>
                <th>Imagen 2</th>
                <th>Imagen 3</th>
                <th>Imagen 4</th>
            </tr>
            <tr>
                <?php
                if (isset($_GET['id'])) {
                    $id = intval($_GET['id']);
                    
                    $stmt = $conn->prepare("SELECT * FROM actividades WHERE id = ?");
                    $stmt->bind_param('i', $id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Opciones_series
                            $opcionesSeries = unserialize($row['opciones_series']);
                            if ($opcionesSeries !== false && is_array($opcionesSeries)) {
                                echo "</tr><tr>"; // Cambia a la nueva fila
                                foreach ($opcionesSeries as $opcion) {
                                    echo "<td><img src='$opcion' alt='Opción' style='width: 100px; height: auto;'></td>";
                                }
                            } else {
                                echo "<td colspan='4'>No hay opciones disponibles</td>";
                            }
                        }
                    } else {
                        echo "<tr><td colspan='4'>No se encontraron imágenes.</td></tr>";
                    }

                    $stmt->close();
                }
                ?>
            </tr>
            <tr>
                <th>Opción 1</th>
                <th>Opción 2</th>
                <th>Opción 3</th>
                <th>Respuesta</th>
            </tr>
            <tr>
                <?php
                if (isset($_GET['id'])) {
                    $id = intval($_GET['id']);
                    
                    $stmt = $conn->prepare("SELECT * FROM actividades WHERE id = ?");
                    $stmt->bind_param('i', $id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Imágenes de respuestas
                            $imagenesRespuestas = unserialize($row['respuestas_series']);
                            if ($imagenesRespuestas !== false && is_array($imagenesRespuestas)) {
                                foreach ($imagenesRespuestas as $image) {
                                    echo "<td class='clickable-answer' onclick='seleccionarRespuestaSeries(\"$image\")'>";
                                    echo "<img src='$image' alt='Imagen guardada' style='width: 100px; height: auto;'><br>";
                                    echo "</td>";
                                }
                            } else {
                                echo "<td colspan='4'>No hay imágenes disponibles o respuesta correcta</td>";
                            }
                        }
                    } else {
                        echo "<tr><td colspan='4'>No se encontraron imágenes.</td></tr>";
                    }

                    $stmt->close();
                }
                ?>
                <td id='respuestaSeleccionada'></td> <!-- Espacio para mostrar la respuesta seleccionada -->
            </tr>
        </table>
        <div class="div-btn_validar">
            <button id="btn_validarSeries" class="btn_validarSeries">Validar</button>
        </div>
    </form>    
</div>

<div id="div-insertar" class="mostrar_actividad">
    <form action="juegos.php" method="post" enctype="multipart/form-data">
        <button id="btnAccion" type="submit" name="submit">Finalizar</button>
    </form>
</div>

<div class="mostrar_actividad dibujo">
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

<script>

    document.addEventListener('DOMContentLoaded', function () {
        const selectedAnswersConteo = {};
        const clickableAnswers1 = document.querySelectorAll('.clickable-answer1');
        const submitBtnConteo = document.getElementById('submitBtnConteo');

        clickableAnswers1.forEach(answer => {
            answer.addEventListener('click', function () {
                const group = this.getAttribute('data-group');
                
                // Desmarcar todas las respuestas del mismo grupo
                document.querySelectorAll(`.clickable-answer1[data-group="${group}"]`).forEach(a => {
                    a.classList.remove('clicked');
                });
                
                // Marcar la respuesta clickeada
                this.classList.add('clicked');

                // Guardar la respuesta seleccionada en el objeto
                selectedAnswersConteo[group] = this.textContent.trim();
                
                // Actualizar el valor del input oculto con las respuestas seleccionadas
                document.getElementById('selected_answersConteo').value = JSON.stringify(selectedAnswersConteo);

                // Verificar si todas las respuestas han sido seleccionadas
                const allSelected = [...new Set(Object.keys(selectedAnswersConteo))].length === clickableAnswers1.length / 4; // 4 respuestas por fila
                submitBtnConteo.disabled = !allSelected; // Habilitar o deshabilitar el botón
            });
        });

        // Ocultar el div si la respuesta es correcta
        <?php if (isset($_SESSION['respuestaCorrectaConteo']) && $_SESSION['respuestaCorrectaConteo'] === true): ?>
            document.getElementById('div-conteo').style.display = 'none';
        <?php endif; ?>

    });

</script>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        const selectedAnswersAdivinar = {};
        const clickableAnswers2 = document.querySelectorAll('.clickable-answer2');
        const submitBtnAdivina = document.getElementById('submitBtnAdivina');

        clickableAnswers2.forEach(answer => {
            answer.addEventListener('click', function () {
                const group2 = this.getAttribute('data-group');
                
                // Desmarcar todas las respuestas del mismo grupo
                document.querySelectorAll(`.clickable-answer2[data-group="${group2}"]`).forEach(a => {
                    a.classList.remove('clicked');
                });
                
                // Marcar la respuesta clickeada
                this.classList.add('clicked');

                // Guardar la respuesta seleccionada en el objeto
                selectedAnswersAdivinar[group2] = this.textContent.trim();

                // Actualizar el valor del input oculto con las respuestas seleccionadas
                document.getElementById('selected_answersAdivina').value = JSON.stringify(selectedAnswersAdivinar);

                // Verificar si todas las respuestas han sido seleccionadas
                // Aquí aseguramos que el botón se habilite cuando todas las filas tienen una respuesta seleccionada
                const allSelected = Object.keys(selectedAnswersAdivinar).length === (clickableAnswers2.length / 4); // 4 respuestas por fila
                submitBtnAdivina.disabled = !allSelected; // Habilitar o deshabilitar el botón
            });
        });

        // Ocultar el div si la respuesta es correcta
        <?php if (isset($_SESSION['respuestaCorrectaAdivina']) && $_SESSION['respuestaCorrectaAdivina'] === true): ?>
            document.getElementById('div-adivina').style.display = 'none';
        <?php endif; ?>

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectedAnswersFrases = [];
        const clickableAnswers3 = document.querySelectorAll('.clickable-answer3');
        const emptySlots = document.querySelectorAll('.empty-slot');
        const submitBtnFrases = document.getElementById('submitBtnFrases');

        let currentIndex = 0;

        clickableAnswers3.forEach(answer => {
            answer.addEventListener('click', function () {
                if (currentIndex < emptySlots.length) {
                    emptySlots[currentIndex].textContent = this.textContent.trim();
                    selectedAnswersFrases.push(this.textContent.trim());
                    this.style.visibility = 'hidden';
                    currentIndex++;

                    if (currentIndex === emptySlots.length) {
                        submitBtnFrases.disabled = false;
                    }
                }
            });
        });

        submitBtnFrases.addEventListener('click', function () {
            document.getElementById('selected_answersFrases').value = JSON.stringify(selectedAnswersFrases);
        });

        // Ocultar el div si la respuesta es correcta
        <?php if (isset($_SESSION['respuestaCorrectaFrases']) && $_SESSION['respuestaCorrectaFrases'] === true): ?>
            document.getElementById('div-frases').style.display = 'none';
        <?php endif; ?>

    });
</script>

<script>
    let imagenSeleccionadaSeries = ''; // Variable para almacenar la imagen seleccionada

    // Función para seleccionar la imagen y mostrarla en la casilla de respuesta
    function seleccionarRespuestaSeries(imagenSeries) {
        imagenSeleccionadaSeries = imagenSeries;
        document.getElementById('respuestaSeleccionada').innerHTML = `<img src="${imagenSeries}" alt="Respuesta seleccionada" style="width: 100px; height: auto;">`;
    }

    // Evento para el botón de validación
    document.getElementById('btn_validarSeries').addEventListener('click', function(event) {
        event.preventDefault();

        if (imagenSeleccionadaSeries) {
            const formData = new FormData();
            formData.append('selected_image', imagenSeleccionadaSeries);
            formData.append('id', document.getElementById('actividad-id').value);

            fetch("juegos.php", {  
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Verifica que la respuesta es JSON
            .then(data => {
                if (data.intentosSeries !== undefined && data.erroresSeries !== undefined && data.correctaSeries !== undefined) {
                    // Actualiza los contadores en el DOM
                    document.querySelector('.contadorSeries p:nth-child(1)').textContent = `Intentos: ${data.intentosSeries}`;
                    document.querySelector('.contadorSeries p:nth-child(2)').textContent = `Errores: ${data.erroresSeries}`;

                    // Oculta el div si la respuesta es correcta
                    if (data.correctaSeries) {
                        document.getElementById('div-series').style.display = 'none';
                    }
                } else {
                    console.error("Respuesta inesperada del servidor:", data);
                }
            })
            .catch(error => {
                console.error("Error al validar respuesta:", error);
            });
        } else {
            alert("Por favor, selecciona una imagen.");
        }
    });
</script>

</body>
</html>