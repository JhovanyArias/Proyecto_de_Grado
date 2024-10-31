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
?>

<!DOCTYPE html>
<html lang="en">
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
            <a class="sub-titulo" href="../revision_actividades.php">Regresar</a>
            </li>
            <li class="list-navigation-page">
            <a class="sub-titulo" onclick="confirmarCierreSesion()" href="../logout.php">Salir</a>
            </li>
        </ul>
        </div>
    </header>

    <div>
        <div class="mostrar_actividad">
            <h2>Conteo</h2>
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
                        $images = unserialize($row['images_conteo']);
                        $respuestas_desempaquetadas = unserialize($row['answers_conteo']);
                        $respuestas_correctas = unserialize($row['correcto_conteo']); // Deserializar respuestas correctas

                        if ($images !== false && is_array($images) && $respuestas_desempaquetadas !== false && is_array($respuestas_desempaquetadas)) {
                            foreach ($images as $key => $image) {
                                echo "<td><img src='data:image/jpeg;base64," . base64_encode($image) . "' alt='Imagen guardada' style='width: 200px; height: auto;'><br>";

                                // Mostrar las respuestas correspondientes
                                foreach ($respuestas_desempaquetadas[$key] as $respuesta) {
                                    echo "<div style='border: 1px solid #000; padding: 5px;'>" . htmlspecialchars($respuesta) . "</div>"; // Mostrar cada respuesta
                                }

                                // Mostrar la respuesta correcta deserializada
                                if (isset($respuestas_correctas[$key])) {
                                    echo "<div style='color: green; font-weight: bold;'>Respuesta Correcta: " . htmlspecialchars($respuestas_correctas[$key]) . "</div>"; // Mostrar la respuesta correcta
                                }
                            }
                        } else {
                            echo "<td colspan='4'>Error al cargar las imágenes o respuestas.</td>";
                        }

                        echo "</tr>";
                    } else {
                        echo "<tr><td colspan='4'>No se encontró la actividad.</td></tr>";
                    }

                    // Cerrar la conexión
                    $conn->close();
                }
                ?>
            </table>
        </div>
        <div class="mostrar_actividad">
            <h2>Adivina la Imagen</h2>
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
                        $respuestas_correctas = unserialize($row['correcto_adivinar']); // Deserializar respuestas correctas

                        if ($images !== false && is_array($images) && $respuestas_desempaquetadas !== false && is_array($respuestas_desempaquetadas)) {
                            foreach ($images as $key => $image) {
                                echo "<td><img src='data:image/jpeg;base64," . base64_encode($image) . "' alt='Imagen guardada' style='width: 200px; height: auto;'><br>";

                                // Mostrar las respuestas correspondientes
                                foreach ($respuestas_desempaquetadas[$key] as $respuesta) {
                                    echo "<div style='border: 1px solid #000; padding: 5px;'>" . htmlspecialchars($respuesta) . "</div>"; // Mostrar cada respuesta
                                }

                                // Mostrar la respuesta correcta deserializada
                                if (isset($respuestas_correctas[$key])) {
                                    echo "<div style='color: green; font-weight: bold;'>Respuesta Correcta: " . htmlspecialchars($respuestas_correctas[$key]) . "</div>"; // Mostrar la respuesta correcta
                                }
                            }
                        } else {
                            echo "<td colspan='4'>Error al cargar las imágenes o respuestas.</td>";
                        }

                        echo "</tr>";
                    } else {
                        echo "<tr><td colspan='4'>No se encontró la actividad.</td></tr>";
                    }

                    // Cerrar la conexión
                    $conn->close();
                }
                ?>
            </table>
        </div>
<div class="mostrar_actividad">
    <h2>Ordena la Frase</h2>
    <table class="tabla_mostrar">
        <tr>
            <th>Frase Desordenada</th>
            <th>Frase Ordenada</th>
        </tr>
        <?php
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']); // Asegurarse de que el ID es un número entero
            
            // Conexión a la base de datos
            $conn = new mysqli('localhost', 'root', '', 'proyecto_de_grado');
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            // Recuperar los datos de la actividad específica
            $stmt = $conn->prepare("SELECT * FROM actividades WHERE id = ?");
            $stmt->bind_param('i', $id); // Usar el ID de la actividad
            $stmt->execute();
            $result = $stmt->get_result();

            // Procesar la fila de la consulta
            if ($row = $result->fetch_assoc()) {
                // Deserializar las frases ordenadas
                $frasesOrdenadas = unserialize($row['correcto_ordenar']);
                // Deserializar las frases desordenadas
                $frasesDesordenadas = unserialize($row['frase_ordenar']);

                if ($frasesOrdenadas !== false && is_array($frasesOrdenadas) && $frasesDesordenadas !== false && is_array($frasesDesordenadas)) {
                    // Asegúrate de que ambas arrays tengan el mismo número de frases
                    $maxLength = max(count($frasesOrdenadas), count($frasesDesordenadas));
                    for ($i = 1; $i < $maxLength+1; $i++) {
                        $fraseDesordenada = isset($frasesDesordenadas[$i]) ? htmlspecialchars($frasesDesordenadas[$i]) : "N/A";
                        $fraseOrdenada = isset($frasesOrdenadas[$i]) ? htmlspecialchars($frasesOrdenadas[$i]) : "N/A";
                        echo "<tr><td>$fraseDesordenada</td><td>$fraseOrdenada</td></tr>"; // Mostrar frases desordenadas primero y luego las ordenadas
                    }
                } else {
                    echo "<tr><td colspan='2'>Error al cargar las frases.</td></tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No se encontró la actividad.</td></tr>";
            }

            // Cerrar la conexión
            $conn->close();
        }
        ?>
    </table>
</div>

        <div class="mostrar_actividad">
            <h2>Series Logicas</h2>
            <table>
                <tr>
                    <th>Imagen 1</th>
                    <th>Imagen 2</th>
                    <th>Imagen 3</th>
                    <th>Imagen 4</th>
                </tr>
                <?php
                if (isset($_GET['id'])) {
                    $id = intval($_GET['id']); // Asegurarse de que el ID es un número entero
                    
                    // Conexión a la base de datos
                    $conn = new mysqli('localhost', 'root', '', 'proyecto_de_grado');
                    if ($conn->connect_error) {
                        die("Conexión fallida: " . $conn->connect_error);
                    }

                    // Recuperar los datos de la actividad específica
                    $stmt = $conn->prepare("SELECT * FROM actividades WHERE id = ?");
                    $stmt->bind_param('i', $id); // Usar el ID de la actividad
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        // Mostrar cada fila de resultados
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            
                            // Deserializar las imágenes
                            $imagenes = unserialize($row['opciones_series']);
                            if ($imagenes !== false && is_array($imagenes)) {
                                foreach ($imagenes as $image) {
                                    echo "<td>";
                                    // Mostrar la imagen
                                    echo "<img src='$image' alt='Imagen guardada' style='width: 100px; height: auto;'><br>";
                                    echo "</td>";
                                }
                            } else {
                                echo "<td>No hay imágenes disponibles</td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='1'>No se encontraron imágenes.</td></tr>";
                    }

                    // Cerrar la conexión
                    $conn->close();
                }
                ?>
                <tr>
                    <th>Imagen 1</th>
                    <th>Imagen 2</th>
                    <th>Imagen 3</th>
                    <th>Respuesta</th>
                </tr>
                <?php
                if (isset($_GET['id'])) {
                    $id = intval($_GET['id']); // Asegurarse de que el ID es un número entero
                    
                    // Conexión a la base de datos
                    $conn = new mysqli('localhost', 'root', '', 'proyecto_de_grado');
                    if ($conn->connect_error) {
                        die("Conexión fallida: " . $conn->connect_error);
                    }

                    // Recuperar los datos de la actividad específica
                    $stmt = $conn->prepare("SELECT * FROM actividades WHERE id = ?");
                    $stmt->bind_param('i', $id); // Usar el ID de la actividad
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        // Mostrar cada fila de resultados
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            
                            // Deserializar las imágenes de la serie de respuestas
                            $imagenesRespuestas = unserialize($row['respuestas_series']);
                            $respuestaCorrectaS = unserialize($row['correcto_series']); // Deserializar la respuesta correcta

                            if ($imagenesRespuestas !== false && is_array($imagenesRespuestas)) {
                                foreach ($imagenesRespuestas as $key => $image) {
                                    echo "<td>";
                                    // Mostrar la imagen
                                    echo "<img src='$image' alt='Imagen guardada' style='width: 100px; height: auto;'><br>";
                                    echo "</td>";
                                }

                            // Verificar si $images es un array y mostrar cada imagen
                            if (is_array($respuestaCorrectaS)) {
                                foreach ($respuestaCorrectaS as $respuestaCorrecta) {
                                    echo "<td><img src='$respuestaCorrecta' alt='Imagen' style='width:100px; height:100px;'></td>";
                                }
                            } else {
                                echo "<td colspan='4'>Error al cargar las imágenes o respuestas.</td>";
                            }

                            }
                            
                            
                            
                            
                            
                            else {
                                echo "<td colspan='4'>No hay imágenes disponibles o respuesta correcta</td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No se encontraron imágenes.</td></tr>";
                    }

                    // Cerrar la conexión
                    $conn->close();
                }
                ?>
            </table>
        </div>
    </div>

</body>
</html>