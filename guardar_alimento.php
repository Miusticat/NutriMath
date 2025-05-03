<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
  http_response_code(401);
  echo "Usuario no autenticado.";
  exit;
}

require_once "conexion.php"; 


$data = json_decode(file_get_contents("php://input"), true);

$nombre = $data['nombre'] ?? '';
$peso_base = $data['pesoBase'] ?? 0;
$peso_analizado = $data['pesoNuevo'] ?? 0;
$nutrientes = $data['nutrientes'] ?? [];

if (!$nombre || !$peso_base || !$peso_analizado || empty($nutrientes)) {
  http_response_code(400);
  echo "Datos incompletos.";
  exit;
}

$id_usuario = $_SESSION['id_usuario'];
$conn = conectarBD();

$stmt = $conn->prepare("INSERT INTO alimentos (id_usuario, nombre_alimento, peso_base, peso_analizado) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isdd", $id_usuario, $nombre, $peso_base, $peso_analizado);
$stmt->execute();
$id_alimento = $stmt->insert_id;
$stmt->close();

$stmt_nutriente = $conn->prepare("INSERT INTO nutrientes (id_alimento, nombre_nutriente, valor_calculado) VALUES (?, ?, ?)");
foreach ($nutrientes as $nombreNutriente => $valorCalculado) {
  $stmt_nutriente->bind_param("isd", $id_alimento, $nombreNutriente, $valorCalculado);
  $stmt_nutriente->execute();
}
$stmt_nutriente->close();
$conn->close();

echo "Alimento guardado correctamente.";
?>
