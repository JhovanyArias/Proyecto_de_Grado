<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
$nombre_usuario = $_SESSION['nombre_usuario'];

if (isset($_POST['submit'])) {
    $conn = new mysqli('localhost', 'root', '', 'proyecto_de_grado');
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

      //Conteo

    // Imágenes
    $imageDataConteo = array();
    foreach ($_FILES['imagesConteo']['tmp_name'] as $key => $tmp_name) {
        $imageDataConteo[] = file_get_contents($tmp_name); // Obtener el contenido de cada imagen
    }
    $serializedImagesConteo = serialize($imageDataConteo); // Serializar las imágenes

    // Respuestas
    $respuestasConteo = array_map('intval', $_POST['respuestaConteo']);
    if (count($respuestasConteo) === 16) {
        $empaquetadosConteo = array_chunk($respuestasConteo, 4); // Dividir las respuestas en grupos de 4
    } else {
        echo "<p>Error Conteo: Se requieren exactamente 16 respuestas.</p>";
        exit;
    }
    $empaquetadoRespuestasConteo = serialize($empaquetadosConteo); // Serializar las opciones

    // Respuestas Correctas
    $respuestasCorrectasCont = array(); // Almacenar las respuestas correctas seleccionadas en una variable
    $seleccionadasConteo = isset($_POST['ck_respuestaConteo']) ? $_POST['ck_respuestaConteo'] : array(); // Checkbox seleccionados

    foreach ($seleccionadasConteo as $indice) {
        if (isset($respuestasConteo[$indice])) { // Verifica si hay una respuesta asociada a ese índice
            $respuestasCorrectasCont[] = $respuestasConteo[$indice]; // Guarda la respuesta seleccionada
        }
    }

    $respuestasSerializadas = serialize($respuestasCorrectasCont); // Serializar las respuestas correctas



    //Adivina la Imagen

    // Imágenes
    $imageDataAdivina = array();
    foreach ($_FILES['imagesAdivina']['tmp_name'] as $key => $tmp_name) {
        $imageDataAdivina[] = file_get_contents($tmp_name); // Obtener el contenido de cada imagen
    }
    $serializedImagesAdivina = serialize($imageDataAdivina); // Serializar las imágenes

    // Respuestas
    $respuestasAdivina = $_POST['respuestaAdivina'];
    if (count($respuestasAdivina) === 16) {
        $empaquetadosAdivina = array_chunk($respuestasAdivina, 4); // Dividir las respuestas en grupos de 4
    } else {
        echo "<p>Error Conteo: Se requieren exactamente 16 respuestas.</p>";
        exit;
    }
    $empaquetadoRespuestasAdivina = serialize($empaquetadosAdivina); // Serializar las opciones

    // Respuestas Correctas
    $respuestasCorrectasAdiv = array(); // Almacenar las respuestas correctas seleccionadas en una variable
    $seleccionadasAdivina = isset($_POST['ck_respuestaAdivina']) ? $_POST['ck_respuestaAdivina'] : array(); // Checkbox seleccionados

    foreach ($seleccionadasAdivina as $indice) {
        if (isset($respuestasAdivina[$indice])) { // Verifica si hay una respuesta asociada a ese índice
            $respuestasCorrectasAdiv[] = $respuestasAdivina[$indice]; // Guarda la respuesta seleccionada
        }
    }

    $respuestasSerializadasAdivina = serialize($respuestasCorrectasAdiv); // Serializar las respuestas correctas





        //Ordena la Frase

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Inicializamos arrays para almacenar las frases
      $frasesOrdenadas = [];
      $frasesDesordenadas = [];
      
      // Obtenemos las frases del campo de entrada
      for ($i = 1; $i <= 4; $i++) {
          $frase = trim($_POST["frase_$i"]);
          $frasesOrdenadas[$i] = $frase; // Almacenamos la frase ordenada

          // Separamos la cadena en palabras usando explode
          $palabras = explode(" ", $frase);

          // Desordenamos las palabras
          shuffle($palabras);

          // Unimos las palabras desordenadas en una nueva frase
          $fraseDesordenada = implode(" ", $palabras);
          $frasesDesordenadas[$i] = $fraseDesordenada; // Almacenamos la frase desordenada
      }

      // Serializamos las frases
      $frasesOrdenadasSerializadas = serialize($frasesOrdenadas);
      $frasesDesordenadasSerializadas = serialize($frasesDesordenadas);
  }


        //Series Logicas

    //Opciones
    $imagesSeries1 = isset($_POST['imagesSeries1']) ? $_POST['imagesSeries1'] : [];
    // Filtrar el array para quitar los valores "default"
    $filteredImagesSI1 = array_filter($imagesSeries1, function($imageSerie1) {
        return $imageSerie1 !== 'default';
    });
    $serializedImagesSeries = serialize($filteredImagesSI1);

    //Respuestas
    $imagesSeriesCorrecto = isset($_POST['imagesSeriesCorrecto']) ? $_POST['imagesSeriesCorrecto'] : [];
    // Filtrar el array para quitar los valores "default"
    $filteredImagesSR1 = array_filter($imagesSeriesCorrecto, function($imageCorrectoSerie1) {
        return $imageCorrectoSerie1 !== 'default';
    });
    $serializedRespuestasSeries = serialize($filteredImagesSR1);

    //Series Lógicas: Obtener opciones seleccionadas solo si su checkbox está marcado.
    $imagesCorrectaSeries = isset($_POST['imagesSeriesCorrecto']) ? $_POST['imagesSeriesCorrecto'] : [];
    $ck_respuestaConteo = isset($_POST['ck_respuestaSeries']) ? $_POST['ck_respuestaSeries'] : [];
    $filteredImagesSRC1 = [];

    // Recorrer cada checkbox seleccionado para obtener su respectiva imagen.
    foreach ($ck_respuestaConteo as $index) {
        if (isset($imagesCorrectaSeries[$index])) {
            $filteredImagesSRC1[] = $imagesCorrectaSeries[$index];
        }
    }

    $serializedCorrectImagesSeries = serialize($filteredImagesSRC1);

    // Insertar en la base de datos
    $stmt = $conn->prepare("INSERT INTO actividades (images_conteo, answers_conteo, correcto_conteo, images_adivinar, answers_adivinar, correcto_adivinar, frase_ordenar, correcto_ordenar, opciones_series, respuestas_series, correcto_series) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        echo "Error en la preparación del statement: " . $conn->error;
        exit();
    }

    $stmt->bind_param("sssssssssss", $serializedImagesConteo, $empaquetadoRespuestasConteo, $respuestasSerializadas, $serializedImagesAdivina, $empaquetadoRespuestasAdivina, $respuestasSerializadasAdivina, $frasesDesordenadasSerializadas, $frasesOrdenadasSerializadas, $serializedImagesSeries, $serializedRespuestasSeries, $serializedCorrectImagesSeries);

    if ($stmt->execute()) {
        echo "<p>Imágenes y respuestas guardadas correctamente.</p>";
    } else {
        echo "<p>Error al guardar los datos: " . $stmt->error . "</p>";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Creacion de Actividades</title>
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
            <a class="sub-titulo" href="../acceder_profesor.php">Regresar</a>
          </li>
          <li class="list-navigation-page">
            <a class="sub-titulo" onclick="confirmarCierreSesion()" href="../logout.php">Salir</a>
          </li>
        </ul>
      </div>
    </header>
    <form action="creacion_actividades.php" method="post" enctype="multipart/form-data">
    <section class="section_activity_container">
      <h2>Creacion de Actividades</h2>
      <div class="activity_container">
        <h3>Conteo</h3>
          <div class="conteo-generadas">
            <?php for ($i = 0; $i < 4; $i++): ?>
              <div class="conteo-container">
                <div class="input-group div-image">
                    <img class="img-generado" src="../images/image.jpg" alt="Imagen" />
                    <input type="file" name="imagesConteo[]" id="imageConteo<?php echo $i + 1; ?>" accept="image/*" required>
                </div>
                <div class="div-answers">
                    <?php for ($j = 0; $j < 4; $j++): ?>
                      <?php $indiceRespuesta = $i * 4 + $j; ?>
                      <div class="input-group div-answer">
                        <input type="checkbox" class="ck_respuesta_correcta" id="ck_respuesta_correcta<?php echo $indiceRespuesta; ?>" name="ck_respuestaConteo[]" value="<?php echo $indiceRespuesta; ?>">
                        <input type="text" class="respuesta_correcta" name="respuestaConteo[]" id="respuestaConteo<?php echo $indiceRespuesta; ?>" min="0" max="255" required placeholder="Respuesta <?php echo $indiceRespuesta + 1; ?>" />
                      </div>
                    <?php endfor; ?>
                </div>
              </div>
            <?php endfor; ?>
          </div>
      </div>
      <div class="activity_container">
        <h3>Adivina la Imagen</h3>
          <div class="conteo-generadas">
            <?php for ($i = 0; $i < 4; $i++): ?>
              <div class="conteo-container">
                  <div class="input-group, div-image">
                      <img class="img-generado" src="../images/image.jpg" alt="Imagen" />
                      <input type="file" name="imagesAdivina[]" id="image<?php echo $i; ?>" accept="image/*" required>
                  </div>
                  <div class="div-answers">
                      <?php for ($j = 0; $j < 4; $j++): ?>
                          <?php $indiceRespuestaAdiv = $i * 4 + $j; ?>
                          <div class="input-group div-answer">
                              <input type="checkbox" class="ck_respuesta_correcta" id="ck_respuesta_correcta<?php echo $indiceRespuestaAdiv; ?>" name="ck_respuestaAdivina[]" value="<?php echo $indiceRespuestaAdiv; ?>">
                              <input type="text" class="respuesta_correcta" name="respuestaAdivina[]" id="respuestaAdivina<?php echo $indiceRespuestaAdiv; ?>" min="0" max="255" required placeholder="Respuesta <?php echo $indiceRespuestaAdiv + 1; ?>" />
                          </div>
                      <?php endfor; ?>
                  </div>
              </div>
            <?php endfor; ?>
          </div>
        
      </div>
      <div class="activity_container">
          <h3>Ordena la Frase</h3>
          <div class="conteo-generadas">
            <?php for ($i = 1; $i <= 4; $i++): ?>
              <div class="conteo-container">
                  <div class="input-group, div-image">
                      <label for="">Escriba una frase entre 4 y 5 palabras.</label>
                      <input type="text" class="ordenar_frase" id="frase_<?php echo $i; ?>" name="frase_<?php echo $i; ?>" required min="0" max="255" required placeholder="Frase para Ordenar <?php echo $i; ?>" />
                  </div>
              </div>
            <?php endfor; ?>
          </div>
      </div>
      <div class="activity_container">
        <h3>Series Logicas</h3>
        <div class="series-generadas">
          <div class="content-count">
            <div class="conteo-container">
              <div class="image-container-series">
                <ul class="ul-series">
                  <div class="li-imagenes-series opciones">
                    <li>
                      <div class="div-imagenes-series">
                        <img id="selected-image11" src="../images/animals/zoo.png" alt="Imagen" style="width: 150px; height: 150px;">
                        <select name="imagesSeries1[]" id="animals11">
                        <option value="default">Elige una opción</option>
                        <option value="../images/animals/dog.png">Perro</option>
                        <option value="../images/animals/elephant.png">Elefante</option>
                        <option value="../images/animals/giraffe.png">Jirafa</option>
                        <option value="../images/animals/kangaroo.png">Canguro</option>
                        <option value="../images/animals/koala.png">Koala</option>
                        <option value="../images/animals/lion.png">Leon</option>
                        <option value="../images/animals/monkey.png">Mono</option>
                        <option value="../images/animals/sloth.png">Perezoso</option>
                        </select>
                      </div>
                    </li>
                    <li>
                      <div class="div-imagenes-series">
                        <img id="selected-image12" src="../images/animals/zoo.png" alt="Imagen" style="width: 150px; height: 150px;">
                        <select name="imagesSeries1[]" id="animals12">
                        <option value="default">Elige una opción</option>
                        <option value="../images/animals/dog.png">Perro</option>
                        <option value="../images/animals/elephant.png">Elefante</option>
                        <option value="../images/animals/giraffe.png">Jirafa</option>
                        <option value="../images/animals/kangaroo.png">Canguro</option>
                        <option value="../images/animals/koala.png">Koala</option>
                        <option value="../images/animals/lion.png">Leon</option>
                        <option value="../images/animals/monkey.png">Mono</option>
                        <option value="../images/animals/sloth.png">Perezoso</option>
                        </select>
                      </div>
                    </li>
                    <li>
                      <div class="div-imagenes-series">
                        <img id="selected-image13" src="../images/animals/zoo.png" alt="Imagen" style="width: 150px; height: 150px;">
                        <select name="imagesSeries1[]" id="animals13">
                        <option value="default">Elige una opción</option>
                        <option value="../images/animals/dog.png">Perro</option>
                        <option value="../images/animals/elephant.png">Elefante</option>
                        <option value="../images/animals/giraffe.png">Jirafa</option>
                        <option value="../images/animals/kangaroo.png">Canguro</option>
                        <option value="../images/animals/koala.png">Koala</option>
                        <option value="../images/animals/lion.png">Leon</option>
                        <option value="../images/animals/monkey.png">Mono</option>
                        <option value="../images/animals/sloth.png">Perezoso</option>
                        </select>
                      </div>
                    </li>
                    <li>
                      <div class="div-imagenes-series">
                        <img id="selected-image14" src="../images/animals/zoo.png" alt="Imagen" style="width: 150px; height: 150px;">
                        <select name="imagesSeries1[]" id="animals14">
                        <option value="default">Elige una opción</option>
                        <option value="../images/animals/dog.png">Perro</option>
                        <option value="../images/animals/elephant.png">Elefante</option>
                        <option value="../images/animals/giraffe.png">Jirafa</option>
                        <option value="../images/animals/kangaroo.png">Canguro</option>
                        <option value="../images/animals/koala.png">Koala</option>
                        <option value="../images/animals/lion.png">Leon</option>
                        <option value="../images/animals/monkey.png">Mono</option>
                        <option value="../images/animals/sloth.png">Perezoso</option>
                        </select>
                      </div>
                    </li>
                  </div>
                  <div class="li-imagenes-series respuestas">
                      <?php for ($i = 0; $i < 3; $i++): ?>
                      <li>
                          <div class="div-imagenes-series">
                              <img id="selected-image<?php echo $i; ?>" src="../images/animals/zoo.png" alt="Imagen" style="width: 150px; height: 150px;">
                              <div class="div_select">
                                  <!-- Checkbox con índice único para cada elemento -->
                                  <input type="checkbox" class="ck_respuesta_correcta" id="ck_respuesta_correcta<?php echo $i; ?>" name="ck_respuestaSeries[]" value="<?php echo $i; ?>">
                                  <select class="select_series" name="imagesSeriesCorrecto[]" id="animals<?php echo $i; ?>">
                                      <option value="default">Elige una opción</option>
                                      <option value="../images/animals/dog.png">Perro</option>
                                      <option value="../images/animals/elephant.png">Elefante</option>
                                      <option value="../images/animals/giraffe.png">Jirafa</option>
                                      <option value="../images/animals/kangaroo.png">Canguro</option>
                                      <option value="../images/animals/koala.png">Koala</option>
                                      <option value="../images/animals/lion.png">Leon</option>
                                      <option value="../images/animals/monkey.png">Mono</option>
                                      <option value="../images/animals/sloth.png">Perezoso</option>
                                  </select>
                              </div>
                          </div>
                      </li>
                      <?php endfor; ?>
                  </div>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div> 
      <div class="activity_container">
        <button type="submit" name="submit" class="button_guardar">Guardar</button>
      </div>
    </section>
    </form>
  </body>

<script>
    // Función para actualizar la imagen en el contenedor dinámicamente
    function actualizarImagenes() {
        // Obtener todos los elementos select que tienen el prefijo "animals"
        const selects = document.querySelectorAll('select[id^="animals"]');

        selects.forEach((select) => {
            // Obtener el ID del select y su número correspondiente
            const selectId = select.id;
            const imgId = 'selected-image' + selectId.match(/\d+$/)[0]; // Obtener el número del select para asignarlo al img

            const img = document.getElementById(imgId);

            // Agregar el evento de cambio al select
            select.addEventListener('change', function() {
                const selectedValue = this.value;
                if (selectedValue !== "default") {
                    img.src = selectedValue; // Cambiar la imagen a la seleccionada
                    img.style.display = 'block'; // Mostrar la imagen
                } else {
                    img.style.display = 'none'; // Ocultar la imagen si se selecciona "default"
                }
            });
        });
    }

    // Llamar a la función para inicializar el comportamiento
    actualizarImagenes();
</script>

</html>

