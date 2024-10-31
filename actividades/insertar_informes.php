    <?php
$host = 'localhost';
$dbname = 'proyecto_de_grado'; 
$user = 'root'; 
$pass = ''; 

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aciertos_conteo = $_SESSION['intentosConteo'];
    $fallos_conteo = $_SESSION['erroresConteo'];
    $aciertos_adivina = $_SESSION['intentosAdivina'];
    $fallos_adivina = $_SESSION['erroresAdivina'];
    $aciertos_frases = $_SESSION['intentosFrases'];
    $fallos_frases = $_SESSION['erroresFrases'];
    $aciertos_series = $_SESSION['intentosSeries'];
    $fallos_series = $_SESSION['erroresSeries'];

    $stmt = $conn->prepare("INSERT INTO informes (fecha_actividad, nombre_estudiant, aciertos_conteo, fallos_conteo, aciertos_adivina, fallos_adivina, aciertos_frases, fallos_frases, aciertos_series, fallos_series) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param('iiiiiiiii', $nombre_estudiante, $aciertos_conteo, $fallos_conteo, $aciertos_adivina, $fallos_adivina, $aciertos_frases, $fallos_frases, $aciertos_series, $fallos_series);
        $stmt->execute();

        header('Content-Type: application/json');
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se insertaron filas']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Error en la preparación de la declaración']);
    }
}

$conn->close();